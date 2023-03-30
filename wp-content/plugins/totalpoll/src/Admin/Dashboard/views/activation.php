<?php ! defined( 'ABSPATH' ) && exit(); ?><script type="text/ng-template" id="dashboard-activation-component-template">
    <div class="totalpoll-box totalpoll-box-activation">
        <div class="totalpoll-box-section">
            <div class="totalpoll-row">
                <div class="totalpoll-column">
                    <div class="totalpoll-box-content" ng-if="$ctrl.activation.status">
                        <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/updates-on.svg" class="totalpoll-box-activation-image">
                        <div class="totalpoll-box-title"><?php esc_html_e( 'Product activated!', 'totalpoll' ); ?></div>
                        <div class="totalpoll-box-description"><?php esc_html_e( 'You\'re now receiving updates.', 'totalpoll' ); ?></div>
                        <table class="wp-list-table widefat striped">
                            <tr>
                                <td><strong><?php esc_html_e( 'Activation code', 'totalpoll' ); ?></strong></td>
                            </tr>
                            <tr>
                                <td>{{$ctrl.activation.key}}</td>
                            </tr>
                            <tr>
                                <td><strong><?php esc_html_e( 'Licensed to', 'totalpoll' ); ?></strong></td>
                            </tr>
                            <tr>
                                <td>{{$ctrl.activation.email}}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="totalpoll-box-content" ng-if="!$ctrl.activation.status">
                        <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/updates-off.svg" class="totalpoll-box-activation-image">
                        <div class="totalpoll-box-title"><?php printf( esc_html__( 'Product activation for %s', 'totalpoll' ), $this->env['domain'] ); ?></div>
                        <div class="totalpoll-box-description"><?php echo wp_kses(__( 'Open <a target="_blank" href="https://codecanyon.net/downloads">downloads page</a>, find the product, click "Download" then select on "License certificate & purchase code (text)".', 'totalpoll' ), ['a' => ['href' => [], 'target' =>[]]]); ?></div>
                        <div class="totalpoll-box-composed-form-error" ng-if="$ctrl.error">{{$ctrl.error}}</div>
                        <form class="totalpoll-box-composed-form" ng-submit="$ctrl.validate()">
                            <input type="text" class="totalpoll-box-composed-form-field" placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx" ng-model="$ctrl.activation.key">
                            <input type="email" class="totalpoll-box-composed-form-field" placeholder="email@domain.tld" ng-model="$ctrl.activation.email">
                            <button type="submit" class="button button-primary button-large totalpoll-box-composed-form-button" ng-disabled="!$ctrl.activation.key || !$ctrl.activation.email || $ctrl.isProcessing()">{{
                                $ctrl.isProcessing() ? '<?php esc_html_e( 'Activating', 'totalpoll' ); ?>' : '<?php esc_html_e( 'Activate', 'totalpoll' ); ?>' }}
                            </button>
                        </form>
                    </div>
                </div>
                <div class="totalpoll-column">
                    <img src="<?php echo esc_attr( $this->env['url'] ); ?>assets/dist/images/activation/how-to.svg" alt="Get license code">
                </div>
            </div>
        </div>
    </div>
</script>
