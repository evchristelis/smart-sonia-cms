<div class="wpb_manage_popups wpb_tabs_content ">
    <?php if ( isset( $_GET['action'] ) && ($_GET['action'] == 'edit' || $_GET['action'] == 'add') ) { ?>
        <div class="wpb-create-new">
            <?php include(WPB_ABSPATH . 'inc/backend/create_new_popup.php'); ?>    
        </div>
    <?php } else {
        ?>



        <div class="wpb-listing postbox">
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . "popup_banners";
            $logs = $wpdb->get_results( "SELECT * FROM $table_name order by id DESC", ARRAY_A );
            $wpb_clear_log_nonce = wp_create_nonce( 'wpb-clear-log-nonce' );
            ?>

            <div class="wpb-add-new">
                <a href="<?php echo admin_url( 'admin.php' ); ?>?page=wpb&action=add" class="add-new-h2"><?php echo __( 'Add New', 'wp-popup-banners' ) ?></a>
            </div>

            <table class="widefat stripped">
                <thead>
                    <tr>
                        <th><?php _e( 'SN', 'wp-popup-banners' ); ?></th>
                        <th><?php _e( 'Title', 'wp-popup-banners' ); ?></th>
                        <th><?php _e( 'Popup Type', 'wp-popup-banners' ); ?></th>
                        <th><?php _e( 'Created Date', 'wp-popup-banners' ); ?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th><?php _e( 'SN', 'wp-popup-banners' ); ?></th>
                        <th><?php _e( 'Title', 'wp-popup-banners' ); ?></th>
                        <th><?php _e( 'Popup Type', 'wp-popup-banners' ); ?></th>
                        <th><?php _e( 'Created Date', 'wp-popup-banners' ); ?></th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    if ( count( $logs ) > 0 ) {
                        $log_count = 1;
                        $parameteres = array();

                        foreach ( $logs as $log ) {
                            $parameteres = unserialize( $log['option_value'] );

                            $id = $log['id'];
                            $delete_nonce = wp_create_nonce( 'wpb_delete_nonce' );
                            $row_class = ($log_count % 2 == 0) ? 'wpb-even-row' : 'wpb-odd-row';
                            ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td <?php if ( $wpb_default_settings['default_popup_id'] == $log['id'] ) { ?> class="wpb_default" <?php } ?>><?php echo $log_count; ?></td>
                                <td class="title column-title">
                                    <?php echo ($parameteres['popup_parameters']['title']!='')?$parameteres['popup_parameters']['title']:__('Untitled Popup','wp-popup-banners'); ?>
                                    <div class="row-actions">
                                        <span class="post-link"><a href="<?php echo admin_url( "admin.php?page=wpb&action=edit&wpbid={$log['id']}" ); ?>"><?php _e( 'Edit', 'wp-popup-banners' ) ?></a></span>&nbsp;|&nbsp;
                                        <span class="delete"><a href="<?php echo admin_url( "admin-post.php?action=wpb_delete&wpb_id=$id&_wpnonce=$delete_nonce" ); ?>" onclick="return confirm('<?php _e( 'Are you sure to delete this Popup Banner?', 'wp-popup-banners' ); ?>');">Delete</a></span>&nbsp;|&nbsp;
                                        <span class="post-link"><a href="<?php echo site_url('?wpb_preview=true&banner_id='.$id);?>"  target="_blank"><?php _e( 'Preview', 'wp-popup-banners' ) ?></a></span>
                                    </div>
                                </td>
                                <td><?php echo ucfirst($parameteres['popup_parameters']['popup_options']); ?></td>
                                <td><?php echo $parameteres['popup_parameters']['added_date']; ?></td>
                            </tr>
                            <?php
                            $log_count++;
                        }
                    } else {
                        ?>
                        <tr colspan="3"><td><?php _e( 'No Popup Banners found', 'wp-popup-banners' ); ?></td></tr>
                        <?php
                    }
                    ?>

                </tbody>
            </table>


            <div class="wpb-add-new">
                <a href="<?php echo admin_url( 'admin.php' ); ?>?page=wpb&action=add" class="add-new-h2"><?php echo __( 'Add New', 'wp-popup-banners' ) ?></a>
            </div>
            
        </div>
    <?php } ?>
</div>



