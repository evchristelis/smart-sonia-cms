<?php
! defined( 'ABSPATH' ) && exit();
 $minimal = apply_filters( 'totalpoll/filters/admin/dashboard/minimal', false ); ?>
<div id="totalpoll-dashboard" class="wrap totalpoll-page" ng-app="dashboard">
    <h1 class="totalpoll-page-title"><?php esc_html_e( 'Dashboard', 'totalpoll' ); ?></h1>

    <div class="totalpoll-page-tabs">
        <div class="totalpoll-page-tabs-item active" track="{ event : 'overview', target: 'dashboard' }" tab-switch="dashboard>overview">
			<?php esc_html_e( 'Overview', 'totalpoll' ); ?>
        </div>
		<?php if ( ! $minimal ): ?>
            <div class="totalpoll-page-tabs-item" tab-switch="dashboard>get-started">
				<?php esc_html_e( 'Get started', 'totalpoll' ); ?>
            </div>
            <a class="totalpoll-page-tabs-item" href="<?php echo esc_attr( $this->env['links.changelog'] ) ?>" target="_blank">
				<?php esc_html_e( 'What\'s new', 'totalpoll' ); ?>
            </a>
            <div class="totalpoll-page-tabs-item" tab-switch="dashboard>support">
				<?php esc_html_e( 'Support', 'totalpoll' ); ?>
            </div>
            <div class="totalpoll-page-tabs-item" tab-switch="dashboard>credits">
				<?php esc_html_e( 'Credits', 'totalpoll' ); ?>
            </div>
            
            <div class="totalpoll-page-tabs-item" tab-switch="dashboard>activation">
				<?php esc_html_e( 'Activation', 'totalpoll' ); ?>
            </div>
            <div class="totalpoll-page-tabs-item" tab-switch="dashboard>my-account">
				<?php esc_html_e( 'My Account', 'totalpoll' ); ?>
            </div>
            
		<?php endif; ?>
    </div>
    <div class="totalpoll-row">
        <div class="totalpoll-column">
            <div tab="dashboard>overview" class="active">
                <dashboard-overview></dashboard-overview>
            </div>

			<?php if ( ! $minimal ): ?>
                <div tab="dashboard>get-started">
                    <dashboard-get-started></dashboard-get-started>
                </div>

                <div tab="dashboard>whats-new">
                    <dashboard-whats-new></dashboard-whats-new>
                </div>

                <div tab="dashboard>activation">
                    <dashboard-activation></dashboard-activation>
                </div>

                <div tab="dashboard>support">
                    <dashboard-support></dashboard-support>
                </div>

                <div tab="dashboard>credits">
                    <dashboard-credits></dashboard-credits>
                </div>

                <div tab="dashboard>my-account">
                    <dashboard-my-account></dashboard-my-account>
                </div>

                <dashboard-blog-feed></dashboard-blog-feed>

                <div class="totalpoll-box totalpoll-box-totalsuite">

                    <div class="totalpoll-row">
                        <div class="totalpoll-column">
                            <div class="totalpoll-box-totalsuite-content">
                                <div class="totalpoll-box-title"><?php esc_html_e( 'Simple yet Powerful Plugins for WordPress', 'totalpoll' ); ?></div>
                                <div class="totalpoll-box-description"><?php esc_html_e( 'A suite of robust, maintained and secure plugins for WordPress that helps you generate more value for your business.', 'totalpoll' ); ?></div>
								<?php
								$url = add_query_arg(
									[
										'utm_source'   => 'in-app',
										'utm_medium'   => 'totalsuite-box',
										'utm_campaign' => 'totalpoll',
									],
									$this->env['links.totalsuite']
								);
								?>
                                <a href="<?php echo esc_attr( $url ); ?>" target="_blank" class="button button-primary button-large"><?php esc_html_e( 'Get Started', 'totalpoll' ); ?></a>
                            </div>
                        </div>
                        <div class="totalpoll-column">
                            <div class="totalpoll-box-totalsuite-image">
                                <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/general/totalsuite.svg">
                            </div>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
        </div>

		<?php if ( ! $minimal ): ?>
            <div class="totalpoll-column totalpoll-column-sidebar">
                <dashboard-subscribe></dashboard-subscribe>
				<?php
				$product = [ 'totalcontest', 'totalrating', 'totalsurvey' ][ mt_rand( 0, 2 ) ];

				$url = add_query_arg(
					[
						'utm_source'   => 'in-app',
						'utm_medium'   => 'dashboard-box',
						'utm_campaign' => 'totalpoll',
					],
					$this->env["links.{$product}"]
				);
				?>
                <a href="<?php echo esc_attr( $url ); ?>" target="_blank" class="totalpoll-banner">
                    <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/general/<?php echo $product; ?>-banner.svg" alt="<?php echo $product; ?>">
                </a>

                <dashboard-review></dashboard-review>
                <dashboard-translate></dashboard-translate>
            </div>
		<?php endif; ?>
    </div>

    <!-- Templates -->
	<?php include __DIR__ . '/overview.php'; ?>
	<?php include __DIR__ . '/blog-feed.php'; ?>
	<?php include __DIR__ . '/get-started.php'; ?>
	<?php include __DIR__ . '/activation.php'; ?>
	<?php include __DIR__ . '/my-account.php'; ?>
	<?php include __DIR__ . '/support.php'; ?>
	<?php include __DIR__ . '/credits.php'; ?>
	<?php include __DIR__ . '/sidebar.php'; ?>
</div>
