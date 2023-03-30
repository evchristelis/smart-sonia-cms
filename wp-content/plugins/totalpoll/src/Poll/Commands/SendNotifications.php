<?php

namespace TotalPoll\Poll\Commands;
! defined( 'ABSPATH' ) && exit();


use TotalPoll\Contracts\Poll\Model;
use TotalPoll\Notification\Mail;
use TotalPoll\Notification\Push;
use TotalPoll\Notification\WebHook;
use TotalPollVendors\TotalCore\Helpers\Command;
use TotalPollVendors\TotalCore\Helpers\Strings;

/**
 * Class SendNotifications
 * @package TotalPoll\Poll\Commands
 */
class SendNotifications extends Command {
	/**
	 * @var Model $poll
	 */
	protected $poll;
	/**
	 * @var \TotalPoll\Contracts\Log\Model $log
	 */
	protected $log;
	/**
	 * @var \TotalPoll\Contracts\Entry\Model $entry
	 */
	protected $entry;
	/**
	 * @var array $templateVars
	 */
	protected $templateVars;

	/**
	 * SendNotifications constructor.
	 *
	 * @param Model $poll
	 */
	public function __construct( Model $poll ) {
		$this->poll         = $poll;
		$this->log          = static::getShared( 'log', null );
		$this->entry        = static::getShared( 'entry', null );

		$labels = [];

		foreach($this->poll->getFields() as $field) {
			$labels[$field['name']] = empty($field['label']) ? $field['name'] : $field['label'];
		}

		$this->templateVars = [
			'title'      => $this->poll->getTitle(),
			'ip'         => $this->log->getIp(),
			'browser'    => $this->log->getUseragent(),
			'user'       => $this->log->getId() ? $this->log->getUser()->user_login : 'anonymous',
			'date'       => $this->log->getDate(),
			'poll'       => $this->poll->toArray(),
			'fields'     => '',
			'choices'    => implode( ' & ', array_map(
				function ( $choice ) {
					return $choice['label'];
				},
				$this->poll->getReceivedChoices()
			) ),
			'log'        => $this->log->toArray(),
			'deactivate' => esc_url( admin_url( "post.php?post={$this->poll->getId()}&action=edit&tab=editor>settings>general>notifications" ) ),
		];

		if($this->entry) {
			$this->templateVars['fields'] = implode( PHP_EOL, array_map(
				function ( $key ) use ($labels) {
					$label = isset($labels[$key]) ? $labels[$key] : ucfirst($key);
					return $label . ' : ' . implode(' , ', (array) $this->entry->getField($key));
				},
				array_keys($this->entry->getFields())
			) );
		}

		$this->templateVars['fieldsHTML'] = nl2br($this->templateVars['fields']);
	}

	protected function handle() {
		$email   = $this->poll->getSettingsItem( 'notifications.email', [] );
		$push    = $this->poll->getSettingsItem( 'notifications.push', [] );
		$webhook = $this->poll->getSettingsItem( 'notifications.webhook', [] );

		/**
		 * Fires before sending notifications.
		 *
		 * @param Model $poll     WebHook settings.
		 * @param array $settings Notifications settings.
		 * @param array $log      Log entry.
		 *
		 * @since 4.0.0
		 */
		do_action( 'totalpoll/actions/before/poll/command/notify', $this->poll, [
			'email'   => $email,
			'push'    => $push,
			'webhook' => $webhook,
		], $this->log->toArray() );

		if ( ! empty( $email['on']['newVote'] ) && ! empty( $email['recipient'] ) ):
			$this->sendEmail( $email['recipient'], $this->getTitle(), $this->getTemplate() );
		endif;

		if ( ! empty( $push['on']['newVote'] ) && ! empty( $push['appId'] ) && ! empty( $push['apiKey'] ) ):
			$this->sendPush( [ 'All' ], $this->getTitle(), $this->getBody(), [ 'appId' => $push['appId'], 'apiKey' => $push['apiKey'] ] );
		endif;

		if ( ! empty( $webhook['on']['newVote'] ) && ! empty( $webhook['url'] ) ):
			$this->sendWebhook(
				$webhook['url'],
				array_merge(
					$this->poll->toArray(), [
					'form'  => $this->poll->getForm()->toArray(),
					'log'   => $this->log ? $this->log->toArray() : [],
					'entry' => $this->entry ? $this->entry->toArray() : [],
				] )
			);
		endif;

		/**
		 * Fires after sending notifications.
		 *
		 * @param \TotalPoll\Contracts\Poll\Model $poll Poll model object.
		 *
		 * @since 4.0.0
		 */
		do_action( 'totalpoll/actions/after/poll/command/notify', $this->poll );

		return true;
	}

	/**
	 * @param $recipient
	 *
	 * @return bool|\WP_Error
	 */
	private function sendEmail( $recipient, $subject, $body ) {
		try {
			$notification = new Mail();
			$notification->setTo( $recipient )
			             ->setSubject( $subject )
			             ->setBody( $body )
			             ->send();

			return true;
		} catch ( \Exception $e ) {
			return new \WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	private function sendPush( $recipient, $title, $message, $args = [] ) {
		try {
			$notification = new Push();
			$notification->setTo( $recipient )
			             ->setSubject( $title )
			             ->setBody( $message )
			             ->setArgs( $args )
			             ->send();

			return true;
		} catch ( \Exception $e ) {
			return new \WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	private function sendWebhook( $url, $payload, $userAgent = 'TotalPoll Notification' ) {
		try {
			$notification = new WebHook();
			$notification->setTo( $url )
			             ->setFrom( $userAgent )
			             ->setBody( $payload )
			             ->send();

			return true;
		} catch ( \Exception $e ) {
			return new \WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * @return string
	 */
	private function getTitle() {
		$template = TotalPoll()->option( 'notifications.title' ) ?: 'New vote on {{poll.title}}';

		return Strings::template( $template, $this->templateVars );
	}

	/**
	 * @return string
	 */
	private function getBody() {
		$template = TotalPoll()->option( 'notifications.body' ) ?: 'Someone just voted for {{choices}} on {{poll.title}}';

		return Strings::template( $template, $this->templateVars );
	}

	/**
	 * @return string
	 */
	private function getTemplate() {
		$template = TotalPoll()->option( 'notifications.template' ) ?: file_get_contents( __DIR__ . '/views/notifications/new-vote.php' );

		return Strings::template( $template, $this->templateVars );
	}
}
