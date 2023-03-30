<?php ! defined( 'ABSPATH' ) && exit(); ?><script type="text/ng-template" id="modules-manager-component-template">
    <div class="totalpoll-modules-list" ng-class="{'totalpoll-processing': $ctrl.modules === null}" ng-init="$root.$manager = $ctrl">
        <div class="totalpoll-row">
            <div class="totalpoll-column totalpoll-column-6" ng-repeat="module in $ctrl.getModules()" ng-if="($root.isCurrentTab('modules>installed') && module.isInstalled()) || ($root.isCurrentTab('modules>store') && !module.isInstalled())">
                <div class="totalpoll-modules-list-item"
                     ng-class="{'totalpoll-processing': $ctrl.isProcessing(module), 'totalpoll-successful': $ctrl.isSuccessful(module)}">
                    <div class="totalpoll-modules-list-item-error" ng-show="$ctrl.getError(module)" ng-click="$ctrl.dismissError(module)">
                        {{$ctrl.getError(module)}}

                        <button type="button" class="notice-dismiss"></button>
                    </div>

                    <div class="totalpoll-modules-list-item-body">
                        <div class="totalpoll-modules-list-item-image">
                            <img ng-src="{{module.getImage('icon')}}">
                        </div>
                        <div class="totalpoll-modules-list-item-details">
                            <div class="totalpoll-modules-list-item-title">
                                <a ng-href="{{module.getPermalink()}}" target="_blank">{{module.getName()}}</a>
                            </div>
                            <div class="totalpoll-modules-list-item-author"><?php esc_html_e( 'By', 'totalpoll' ); ?> <a ng-href="{{module.getAuthorUrl()}}">{{module.getAuthorName()}}</a>
                            </div>
                            <div class="totalpoll-modules-list-item-description">
                                <p ng-bind-html="module.get('description')"></p>
                            </div>
                        </div>
                        <div class="totalpoll-modules-list-item-actions">
                            <div ng-show="module.isPurchased() && !module.isInstalled()">
                                <button ng-click="$ctrl.applyAction('installFromStore', module)" class="widefat button button-primary button-large">
                                    <span class="dashicons dashicons-arrow-down-alt"></span> <?php esc_html_e( 'Install', 'totalpoll' ); ?>
                                </button>
                            </div>
                            <div ng-show="!module.isPurchased() && !module.isInstalled()">
                                <a target="_blank" ng-href="{{module.getPermalink()}}" class="widefat button button-primary button-large">
                                    <span class="dashicons dashicons-external"></span> <?php esc_html_e( 'Get it', 'totalpoll' ); ?>
                                </a>
                            </div>
                            <div ng-show="module.isInstalled() && module.hasUpdate()">
                                <button ng-click="$ctrl.applyAction('update', module)" class="widefat button button-primary button-large">
                                    <span class="dashicons dashicons-update"></span> <?php esc_html_e( 'Update', 'totalpoll' ); ?>
                                </button>
                            </div>
                            <div ng-show="module.isInstalled() && !module.isActivated()">
                                <button ng-click="$ctrl.applyAction('activate', module)" class="widefat button button-large">
                                    <span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'Activate', 'totalpoll' ); ?>
                                </button>
                            </div>
                            <div ng-show="module.isInstalled() && !module.isActivated()">
                                <button ng-click="$ctrl.applyAction('uninstall', module)" class="widefat button button-danger button-large">
                                    <span class="dashicons dashicons-trash"></span> <?php esc_html_e( 'Uninstall', 'totalpoll' ); ?>
                                </button>
                            </div>
                            <div ng-show="module.isInstalled() && module.isActivated()">
                                <button class="widefat button button-danger button-large"
                                        ng-disabled="module.getId() === 'basic-template'"
                                        ng-click="$ctrl.applyAction('deactivate', module)">
                                    <span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Deactivate', 'totalpoll' ); ?>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="totalpoll-modules-list-item-footer">
                        <div class="totalpoll-modules-list-item-version">
                            <strong><?php esc_html_e( 'Version', 'totalpoll' ); ?> {{module.getVersion()}}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</script>
