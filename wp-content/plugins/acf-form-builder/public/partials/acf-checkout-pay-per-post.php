<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://support.catsplugins.com
 * @since      1.0.0
 *
 * @package    Acf_Form_Builder
 * @subpackage Acf_Form_Builder/public/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
    if(isset($post_data[ACF_FORM_BUILDER_TEXTDOMAIN . '_post_type'])){
        $post_types = unserialize($post_data[ACF_FORM_BUILDER_TEXTDOMAIN . '_post_type'][0]);
    }

    $total = $form_settings['pay_per_post'];

    if(isset($_GET['package_id']) && isset($_GET['group_id'])) {
        $checkout_currency = $form_settings['checkout_currency'];
    } else {
        $checkout_currency = cs_get_option( 'currency_options_non_group_id' );
    }  


?>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    
    <div class="checkout-content app active">
        <header>
            <div class="cust-num">
                <svg height="42" width="42" viewBox="0 0 64 64">
                    <path class="path1" fill="rgb(0, 157, 223)" d="M58.125 19.288c-2.987 13.262-12.212 20.262-26.75 20.262h-4.837l-3.363 21.35h-4.050l-0.212 1.375c-0.137 0.913 0.563 1.725 1.475 1.725h10.35c1.225 0 2.263-0.888 2.462-2.1l0.1-0.525 1.95-12.362 0.125-0.675c0.188-1.212 1.237-2.1 2.462-2.1h1.538c10.025 0 17.875-4.075 20.175-15.85 0.862-4.475 0.538-8.275-1.425-11.1z"></path>
                    <path fill="rgb(0, 46, 135)" class="path2" d="M51.938 4.825c-2.962-3.375-8.325-4.825-15.175-4.825h-19.887c-1.4 0-2.6 1.012-2.813 2.4l-8.287 52.525c-0.162 1.038 0.638 1.975 1.688 1.975h12.287l3.087-19.563-0.1 0.612c0.212-1.388 1.4-2.4 2.8-2.4h5.837c11.462 0 20.438-4.65 23.063-18.125 0.075-0.4 0.15-0.788 0.2-1.163 0.775-4.975 0-8.375-2.7-11.438z"></path>
                </svg>
                <p><?php echo current_time('Y-m-d'); ?>
                    <br><?php echo $transaction; ?></p>
            </div>
            <div class="cust-info">
                <h3><?php _e('Hi', ACF_FORM_BUILDER_TEXTDOMAIN); ?>, <?php echo $user->data->user_nicename; ?></h3>
                <p><?php _e('Please purchase to submit your post', ACF_FORM_BUILDER_TEXTDOMAIN); ?></p>
            </div>
        </header>
        <main>
            <h3 class="center"><?php _e('Cart', ACF_FORM_BUILDER_TEXTDOMAIN); ?> :</h3>
            <ul>
                <li><i>1</i> <?php _e('Pay per post', ACF_FORM_BUILDER_TEXTDOMAIN); ?> <span><?php echo $checkout_currency; ?><?php echo $form_settings['pay_per_post']; ?></span></li>
                <?php if(isset($post_types) && count($post_types)): ?>
                    <?php foreach($post_types as $post_type) : ?>
                        <?php
                            $total += $form_settings['pay_per_post_' . $post_type];
                            switch ($post_type) {
                                case 'vip':
                                    echo "<li><i>1</i>" . __('Vip post', ACF_FORM_BUILDER_TEXTDOMAIN) . "<span>$" . $form_settings['pay_per_post_' . $post_type] . "</span></li>";
                                    break;
                                case 'feature':
                                    echo "<li><i>1</i>" . __('Feature post', ACF_FORM_BUILDER_TEXTDOMAIN) . "<span>$" . $form_settings['pay_per_post_' . $post_type] . "</span></li>";

                                    break;
                                
                                default:
                                    # code...
                                    break;
                            }
                        ?>
                    <?php endforeach; ?>
                <?php endif;
                    $_SESSION['transaction_price'] = $total;
                ?>
            </ul>
            <div class="total">
                <p><?php _e('Total', ACF_FORM_BUILDER_TEXTDOMAIN); ?> <span><?php echo $checkout_currency; ?><?php echo $total; ?></span></p>
            </div>
        </main>
        
        <footer>
            <?php // Paypal Payment Gateway // ;?>
        	<?php if($form_settings['enable_paypal_gateway']) : ?>
                <div id="paypal-button" class="center"></div>
                <script>
                    paypal.Button.render({

                        env: '<?php echo $form_settings['paypal_mode']; ?>', // Or 'sandbox',

                        commit: true, // Show a 'Pay Now' button

                        style: {
                            size: 'responsive',
                            color: 'gold',
                            shape: 'pill',
                            label: 'checkout'
                        },

                        client: {
                            sandbox: '<?php echo $form_settings['paypal_sandbox_key']; ?>',
                            production: '<?php echo $form_settings['paypal_production_key']; ?>'
                        },

                        payment: function(data, actions) {
                            return actions.payment.create({
                                payment: {
                                    transactions: [{
                                        amount: {
                                            total: '<?php echo $total; ?>',
                                            currency: '<?php echo $checkout_currency; ?>'
                                        },
                                        custom: '<?php echo $_SESSION['transaction_secret']; ?>'
                                    }]
                                },
                            });
                        },

                        onAuthorize: function(data, actions) {
                            return actions.payment.execute().then(function(payment) {
                                window.location = '<?php echo $return_page; ?>';
                                // The payment is complete!
                                // You can now show a confirmation message to the customer
                            });
                        },

                        onError: function(err) {
                            window.location = '<?php echo get_permalink($form_settings['failed_page']); ?>';
                        },

                        onCancel: function(data, actions) {
                            return actions.redirect();
                        }

                    }, '#paypal-button');
                </script>
            <?php endif; ?>

            <?php // Stripe Payment Gateway // ;?>
            <?php if($form_settings['enable_stripe_gateway']) : ?>
                <?php
                    $args = array(
                        'stripe_ipn' => true
                    );
                    $ipn_stripe = add_query_arg($args, $return_page);
                ?>
                <form action="<?php echo $ipn_stripe; ?>" method="POST">
                    <input type="hidden" name="amount" value="<?php echo $total; ?>">
                    <input type="hidden" name="name" value="<?php _e('Pay per post', ACF_FORM_BUILDER_TEXTDOMAIN); ?>">
                  <script
                    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="<?php echo $form_settings['stripe_mode'] == 'sandbox' ? $form_settings['stripe_sandbox_key'] : $form_settings['stripe_production_key']; ?>"
                    data-amount="<?php echo $total * 100; ?>"
                    data-name="<?php _e('Pay per post', ACF_FORM_BUILDER_TEXTDOMAIN); ?>"
                    data-image="https://stripe.com/img/documentation/checkout/marketplace.png">
                  </script>
                </form>
            <?php endif; ?>

            <?php // WooCommerce Paygate // ;?>
            <?php if($form_settings['enable_woo_gateway']) :
                    $woo_gateway_title = $form_settings['woo_gateway_title'];
                    $woo_gateway_description = $form_settings['woo_gateway_description'];
                ?>

                    <div class="acf-fb-woocommerce-checkout"><p><strong><?php echo $woo_gateway_title; ?></strong></p></div>
                    <form action="<?php echo get_permalink( wc_get_page_id( 'checkout' ) ); ?>" method="POST">
                        <div class="woo-gateway">
                            <?php echo $woo_gateway_description; ?>
                        </div>
                        <button name="acf_woo_gateway" value="<?php echo $form_settings['woo_gateway']; ?>" style="height: 30px;padding:0;" class="stripe-button-el"><?php _e('Submit', ACF_FORM_BUILDER_TEXTDOMAIN); ?></button>
                    </form>
            <?php endif; ?>
            
        </footer>
    </div>
    