<?php

namespace TotalPoll\Admin\Options;
! defined( 'ABSPATH' ) && exit();


use TotalPoll\Contracts\Migrations\Poll\Migrator;
use TotalPollVendors\TotalCore\Admin\Pages\Page as AdminPageContract;
use TotalPollVendors\TotalCore\Contracts\Foundation\Environment;
use TotalPollVendors\TotalCore\Contracts\Http\Request;
use TotalPollVendors\TotalCore\Helpers\Misc;
use TotalPollVendors\TotalCore\Helpers\Tracking;

/**
 * Class Page
 * @package TotalPoll\Admin\Options
 */
class Page extends AdminPageContract {
	/**
	 * @var Migrator[] $migrators
	 */
	protected $migrators;

	/**
	 * Options.
	 *
	 * @var array $options
	 */
	protected $options;

	/**
	 * Page constructor.
	 *
	 * @param Request $request
	 * @param Environment $env
	 * @param Migrator[] $migrators
	 */
	public function __construct( Request $request, $env, $migrators ) {
		parent::__construct( $request, $env );
		$this->migrators = $migrators;
		$this->options   = TotalPoll( 'options' )->getOptions();

		if ( empty( $this->options ) ):
			$this->options = null;
		endif;
	}

	/**
	 * Enqueue assets.
	 */
	public function assets() {
		// TotalPoll
		wp_enqueue_script( 'totalpoll-admin-options' );
		wp_enqueue_style( 'totalpoll-admin-options' );

		/**
		 * Filters the list of expressions that are available through the interface to override.
		 *
		 * @param array $expressions Array of expressions.
		 *
		 * @return array
		 * @since 4.0.0
		 */
		$expressions = apply_filters(
			'totalpoll/filters/admin/options/expressions',
			[
				'votes'       => [
					'label'       => esc_html__( 'Votes', 'totalpoll' ),
					'expressions' =>
						[
							'%s Vote' => [
								'translations' => [
									esc_html__( '%s Vote', 'totalpoll' ),
									esc_html__( '%s Votes', 'totalpoll' ),
								],
							],
						],
				],
				'buttons'     => [
					'label'       => esc_html__( 'Buttons', 'totalpoll' ),
					'expressions' =>
						[
							'Previous'            => [
								'translations' => [
									esc_html__( 'Previous', 'totalpoll' ),
								],
							],
							'Next'                => [
								'translations' => [
									esc_html__( 'Next', 'totalpoll' ),
								],
							],
							'Results'             => [
								'translations' => [
									esc_html__( 'Results', 'totalpoll' ),
								],
							],
							'Vote'                => [
								'translations' => [
									esc_html__( 'Vote', 'totalpoll' ),
								],
							],
							'Back to vote'        => [
								'translations' => [
									esc_html__( 'Back to vote', 'totalpoll' ),
								],
							],
							'Continue to vote'    => [
								'translations' => [
									esc_html__( 'Continue to vote', 'totalpoll' ),
								],
							],
							'Continue to results' => [
								'translations' => [
									esc_html__( 'Continue to results', 'totalpoll' ),
								],
							],
						],
				],
				'fields'      => [
					'label'       => esc_html__( 'Fields', 'totalpoll' ),
					'expressions' =>
						[
							'Other' => [
								'translations' => [
									esc_html__( 'Other', 'totalpoll' ),
								],
							],
						],
				],
				'errors'      => [
					'label'       => esc_html__( 'Errors', 'totalpoll' ),
					'expressions' =>
						[
							'You cannot vote again in this poll.'                                                                    =>
								[
									'translations' => [
										esc_html__( 'You cannot vote again in this poll.', 'totalpoll' ),
									],
								],
							'You have to vote for at least one choice.'                                                              =>
								[
									'translations' => [
										esc_html__( 'You have to vote for at least one choice.', 'totalpoll' ),
										esc_html__( 'You have to vote for at least %d choices.', 'totalpoll' ),
									],
								],
							'You cannot vote for more than one choice.'                                                              =>
								[
									'translations' => [
										esc_html__( 'You cannot vote for more than one choice.', 'totalpoll' ),
										esc_html__( 'You cannot vote for more than %d choices.', 'totalpoll' ),
									],
								],
							'You have entered an invalid captcha code.'                                                              =>
								[
									'translations' => [
										esc_html__( 'You have entered an invalid captcha code.', 'totalpoll' ),
									],
								],
							'You cannot vote because the quota has been exceeded.'                                                   =>
								[
									'translations' => [
										esc_html__( 'You cannot vote because the quota has been exceeded.', 'totalpoll' ),
									],
								],
							'You cannot see results before voting.'                                                                  =>
								[
									'translations' => [
										esc_html__( 'You cannot see results before voting.', 'totalpoll' ),
									],
								],
							'You cannot vote because this poll has not started yet.'                                                 =>
								[
									'translations' => [
										esc_html__( 'You cannot vote because this poll has not started yet.', 'totalpoll' ),
									],
								],
							'You cannot vote because this poll has expired.'                                                         =>
								[
									'translations' => [
										esc_html__( 'You cannot vote because this poll has expired.', 'totalpoll' ),
									],
								],
							'You cannot vote because this poll is not available in your region.'                                     =>
								[
									'translations' => [
										esc_html__( 'You cannot vote because this poll is not available in your region.', 'totalpoll' ),
									],
								],
							'You cannot vote because you have insufficient rights.'                                                  =>
								[
									'translations' => [
										esc_html__( 'You cannot vote because you have insufficient rights.', 'totalpoll' ),
									],
								],
							'You cannot vote because you are a guest, please <a href="%s">sign in</a> or <a href="%s">register</a>.' =>
								[
									'translations' => [
										wp_kses( __( 'You cannot vote because you are a guest, please <a href="%s">sign in</a> or <a href="%s">register</a>.', 'totalpoll' ), [ 'a' => [ 'href' => [] ] ] ),
									],
								],
							'Voting via links has been disabled for this poll.'                                                      =>
								[
									'translations' => [
										esc_html__( 'Voting via links has been disabled for this poll.', 'totalpoll' ),
									],
								],
							'To continue, you must be a part of these roles: %s.'                                                    =>
								[
									'translations' => [
										esc_html__( 'To continue, you must be a part of these roles: %s.', 'totalpoll' ),
									],
								],
							'Something went wrong. Please try again.'                                                                =>
								[
									'translations' => [
										esc_html__( 'Something went wrong. Please try again.', 'totalpoll' ),
									],
								],
							'This poll has not started yet (%s left).'                                                               =>
								[
									'translations' => [
										esc_html__( 'This poll has not started yet (%s left).', 'totalpoll' ),
									],
								],
							'This poll has ended (since %s).'                                                                        =>
								[
									'translations' => [
										esc_html__( 'This poll has ended (since %s).', 'totalpoll' ),
									],
								],
						],
				],
				'validations' => [
					'label'       => esc_html__( 'Validations', 'totalpoll' ),
					'expressions' =>
						[
							'{{label}} must be a valid email address.'       => [
								'translations' => [
									esc_html__( '{{label}} must be a valid email address.', 'totalpoll' ),
								],
							],
							'{{label}} must be filled.'                      => [
								'translations' => [
									esc_html__( '{{label}} must be filled.', 'totalpoll' ),
								],
							],
							'{{label}} is not within the supported range.'   => [
								'translations' => [
									esc_html__( '{{label}} is not within the supported range.', 'totalpoll' ),
								],
							],
							'{{label}} has been used before.'                => [
								'translations' => [
									esc_html__( '{{label}} has been used before.', 'totalpoll' ),
								],
							],
							'{{label}} is not accepted.'                     => [
								'translations' => [
									esc_html__( '{{label}} is not accepted.', 'totalpoll' ),
								],
							],
							'{{label}} does not allow this value.'           => [
								'translations' => [
									esc_html__( '{{label}} does not allow this value.', 'totalpoll' ),
								],
							],
							'You must vote for at least {{minimum}} choice.' => [
								'translations' => [
									esc_html__( 'You must vote for at least {{minimum}} choice.', 'totalpoll' ),
									esc_html__( 'You must vote for at least {{minimum}} choices.', 'totalpoll' ),
								],
							],
							'You can vote for up to {{maximum}} choice.'     => [
								'translations' => [
									esc_html__( 'You can vote for up to {{maximum}} choice.', 'totalpoll' ),
									esc_html__( 'You can vote for up to {{maximum}} choices.', 'totalpoll' ),
								],
							],
						],
				]
			]
		);

		wp_localize_script( 'totalpoll-admin-options', 'TotalPollExpressions', $expressions );
		wp_localize_script( 'totalpoll-admin-options', 'TotalPollSavedExpressions', Misc::getJsonOption( 'totalpoll_expressions' ) );
		wp_localize_script( 'totalpoll-admin-options', 'TotalPollOptions', $this->options );
		wp_localize_script( 'totalpoll-admin-options', 'TotalPollDebugInformation', Misc::getDebugInfo() );
		wp_localize_script( 'totalpoll-admin-options', 'TotalPollMigrationPlugins', $this->migrators );
	}

	public function render() {
		Tracking::trackScreens( 'options' );
		/**
		 * Filters the list of tabs in options page.
		 *
		 * @param array $tabs Array of tabs [id => [label, icon, file]].
		 *
		 * @return array
		 * @since 4.0.0
		 */
		$tabs = apply_filters(
			'totalpoll/filters/admin/options/tabs',
			[
				'general'       => [ 'label' => esc_html__( 'General', 'totalpoll' ), 'icon' => 'admin-settings' ],
				'performance'   => [ 'label' => esc_html__( 'Performance', 'totalpoll' ), 'icon' => 'performance' ],
				'services'      => [ 'label' => esc_html__( 'Services', 'totalpoll' ), 'icon' => 'cloud' ],
				'sharing'       => [ 'label' => esc_html__( 'Sharing', 'totalpoll' ), 'icon' => 'share' ],
				'advanced'      => [ 'label' => esc_html__( 'Advanced', 'totalpoll' ), 'icon' => 'admin-generic' ],
				'notifications' => [ 'label' => esc_html__( 'Notifications', 'totalpoll' ), 'icon' => 'email' ],
				'expressions'   => [ 'label' => esc_html__( 'Expressions', 'totalpoll' ), 'icon' => 'admin-site' ],
				'migration'     => [ 'label' => esc_html__( 'Migration', 'totalpoll' ), 'icon' => 'migrate' ],
				'import-export' => [ 'label' => esc_html__( 'Import & Export', 'totalpoll' ), 'icon' => 'update' ],
				'debug'         => [ 'label' => esc_html__( 'Debug', 'totalpoll' ), 'icon' => 'info' ],
			]
		);

		include_once __DIR__ . '/views/index.php';
	}
}
