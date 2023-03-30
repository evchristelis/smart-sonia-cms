<?php $wah_list = wah_get_admin_widgets_list(); ?>
<div class="wrap">
    <div class="element_row">
        <h1 class="wah-admin-page-header">
            <?php _e("Sidebar widgets order","wp-accessibility-helper"); ?>
        </h1>

        <?php render_wah_header_notice(); ?>

        <div id="fountainG">
            <?php for($i=1;$i<=8;$i++): ?>
                <div id="fountainG_<?php echo $i; ?>" class="fountainG"></div>
            <?php endfor; ?>
        </div>
    </div>

    <div class="element_row wah-sidebar-widgets-order-wrapper">
        <div class="element_container">

            <div class="wah-left-wrap">
                <ul id="sortable-wah-widget">
                    <?php foreach($wah_list as $id=>$item) { ?>
                        <li data-status="<?php echo $item['active']; ?>" id="<?php echo $id; ?>" class="ui-state-default wah-button-widget <?php echo $item['class']; ?>">
                            <span class="dashicons dashicons-menu"></span> <?php echo $item['html']; ?>
                        </li>
                    <?php } ?>
                </ul>
            </div>

            <div class="wah-right-wrap">
                <div>
                    <p><strong><?php _e('Instruction:', 'wp-accessibility-helper'); ?></strong></p>
                    <ol>
                        <li><?php _e('Drag and drop your widgets', 'wp-accessibility-helper'); ?></li>
                        <li><?php _e('New widgets order saves without page refresh (Ajax)', 'wp-accessibility-helper'); ?></li>
                        <li><span class="active_widget"><span class="dashicons dashicons-menu"></span></span><?php _e('Active widget color', 'wp-accessibility-helper'); ?></li>
                        <li><span class="inactive_widget"><span class="dashicons dashicons-menu"></span></span><?php _e('Inactive widget color', 'wp-accessibility-helper'); ?></li>
                    </ol>
                </div>
                <hr>
                <p>
                    <button type="button" name="wah-reset-widgets-order" id="wah-reset-widgets-order" class="button">
                        <?php _e("Reset widgets order","wp-accessibility-helper"); ?>
                    </button>
                </p>
            </div>

        </div>
    </div>

</div>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
