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

<!-- Section -->
<section class="grid-csssection">	
	<div class="row">
		<div class="col-xs-12">	
			<table>
				<thead>
					<tr>
						<th><?php _e('Transaction ID', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
						<th><?php _e('Purchased package', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
						<th><?php _e('Total', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
						<th><?php _e('Status', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
					</tr>
				</thead>
				<?php if(isset($user_transactions) && $user_transactions->found_posts) : $i = 0; ?>
				<tbody>	
				<?php foreach ($user_transactions->posts as $key => $transaction) : 
					$i++; 
					$post_meta = get_post_meta($transaction->ID);
					$package_id = $post_meta['_package_id'][0];
					$package_price = $post_meta['_price'][0];
					$transaction_status = $post_meta['_transaction_status'][0];
				?>				
					<tr <?php echo $i % 2 == 0 ? 'even' : ''; ?>>
						<td><?php echo $transaction->ID; ?></td>
						<td><?php echo get_the_title($package_id); ?></td>
						<td>$<?php echo $package_price; ?></td>
						<td><?php echo $transaction_status; ?></td>
					</tr>
				<?php endforeach; ?>
				</tbody>			
				<?php endif; ?>
			</table>
		</div>
	</div>
</section>
<!-- End / Section -->
