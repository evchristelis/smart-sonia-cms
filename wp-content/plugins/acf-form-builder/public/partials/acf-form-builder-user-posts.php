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

//  simple check capabilitie
?>
    <!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php if ($the_query->have_posts()) : ?>

    <form action="" class="cpfbFormfilter text-right">
        <span>
            <label for="">Search for:</label>
            <input name="search" placeholder="<?php _e('title', ACF_FORM_BUILDER_TEXTDOMAIN); ?>" type="text" class=""
                   value="<?php echo sanitize_text_field(@$_GET['search']) ?>"
            >

            <?php
            if (current_user_can('edit_others_posts')
                || current_user_can('edit_others_pages')
                || current_user_can('moderate_comments')) {
            ?>
            <input name="author" placeholder="<?php _e('author', ACF_FORM_BUILDER_TEXTDOMAIN); ?>" type="text" class=""
                   value="<?php echo sanitize_text_field(@$_GET['author']) ?>"
            >
            <?php } ?>


            <button type="submit"><?php _e('Search', ACF_FORM_BUILDER_TEXTDOMAIN) ?></button>
        </span>
    </form>

    <table class="cpfs">
        <thead>
        <tr>
            <th><?php _e('ID', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
            <th><?php _e('Thumbnail', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
            <th><?php _e('Title', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
            <th><?php _e('Category/Taxonomy', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
            <th><?php _e('Status', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
            <th><?php _e('Created at', ACF_FORM_BUILDER_TEXTDOMAIN); ?></th>
        </tr>
        </thead>
        <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
            <tr>
                <td><a href="<?php echo get_permalink() ?>"><b>#<?php the_ID() ?></b></a></td>


                <td class="td-thumbnail">
                    <a href="<?php echo get_permalink() ?>">
                        <?php echo get_the_post_thumbnail_url() != '' ? get_the_post_thumbnail() : '<img width="150" height="150" src="' . ACF_FORM_BUILDER_PLUGIN_URL . 'public/img/default-thumbnail-150x150.png' . '" sizes="(max-width: 150px) 100vw, 150px">'; ?>
                    </a>
                </td>


                <td>
                    <?php
                    if (current_user_can('edit_others_posts')
                        || current_user_can('edit_others_pages')
                        || current_user_can('moderate_comments')) {
                        echo "<i>". get_the_author_meta('display_name') ."</i> - ". get_the_author_meta('user_email') ."<br>";
                    } ?>

                    <a href="<?php echo get_permalink() ?>">
                        <b><?php the_title() ?></b>
                    </a>
                    <div>

                        <?php
                        // prepare edit link
                        $form_id = get_post_meta(get_the_ID(), 'acf-form-builder_group_id', true);
                        $form_settings = get_post_meta($form_id, '_acf_form_builder_metabox');

                        if (!empty($form_settings)) {
                            $edit_page_id = $form_settings[0]['edit_page_id'];
                            $edit_page_url = get_permalink($edit_page_id);
                        }

                        if (!empty($form_id)) {
                            $args = array(
                                'group_id' => $form_id,
                                'post_id' => get_the_ID(),
                            );
                            $link_query = add_query_arg($args, $edit_page_url);
                            $link_delete = get_delete_post_link(get_the_ID());
                            ?>
                            <div class="cpfbActions">
                                <a target="_blank" class="button"
                                   href="<?php echo $link_query; ?>"><?php _e('Edit', ACF_FORM_BUILDER_TEXTDOMAIN); ?></a>
                                <a class="button delete-post"
                                   onclick="return window.confirm('<?php _e('Delete this post. Are you sure?', ACF_FORM_BUILDER_TEXTDOMAIN) ?>')"
                                   href="<?php echo $link_delete ?>"
                                   data-id="<?php the_ID(); ?>"><?php _e('Delete', ACF_FORM_BUILDER_TEXTDOMAIN); ?></a>
                            </div>
                        <?php } else { ?>

                            <p>No action</p>
                        <?php } ?>
                    </div>
                </td>


                <td>
                    <?php
                    $postTaxs = get_object_taxonomies(get_post_type(), 'OBJECT');
                    foreach ($postTaxs as $tax) {
                        if ($tax->public && $tax->show_ui) :
                            $terms = get_the_terms(get_the_ID(), $tax->name);

                            if (!is_wp_error($terms) && $terms && count($terms) > 0) :
                                echo "<div><i>{$tax->label}</i>: ";

                                foreach ($terms as $t) :
                                    echo $t->name;
                                endforeach;

                                echo "</div>";
                            endif;

                        endif;
                    }
                    ?>
                </td>


                <td>
                    <span class="cppoststatus <?php echo get_post_status(get_the_ID()) ?>">
                        <?php echo get_post_status(get_the_ID()) ?>
                    </span>
                </td>


                <td><?php echo get_the_date() ?></td>
            </tr>
        <?php endwhile; ?>
    </table>


    <style>
        table.cpfs .cppoststatus.publish {
            font-weight: bold;
            color: #4cae4c;
        }

        table.cpfs .cppoststatus.pending {
            font-weight: bold;
            color: #FF9800;
        }

        table.cpfs tr th:last-child,
        table.cpfs tr td:last-child {
            padding-right: 10px;
            text-align: right;
        }

        table.cpfs tr th:nth-child(1),
        table.cpfs tr td:nth-child(1) {
            padding-left: 10px;
        }

        table.cpfs .td-thumbnail {
            width: 60px;
        }

        table.cpfs tr:nth-child(2n) {
            background-color: #f9f9f9;
        }

        tr:hover .cpfbActions{
            opacity: 1;
        }
        .cpfbActions {
            opacity: 0.3;
            -webkit-transition: all 0.25s;
            -moz-transition: all 0.25s;
            -ms-transition: all 0.25s;
            -o-transition: all 0.25s;
            transition: all 0.25s;
        }
        .cpfbActions a{
            display: inline-block;
            margin-right: 5px;
            padding: 2px 9px;
            border-radius: 3px;
            background-color: #ffffff;
            border: 1px solid #e0e0e0;
            font-size: 0.8rem;
        }


        .cpfbFormfilter  {
            margin-bottom: 15px;
        }
        .cpfbFormfilter label,
        .cpfbFormfilter input{
            width: auto;
            display: inline-block;
        }
        .text-right {
            text-align: right;
        }
    </style>


    <?php wp_reset_postdata(); ?>
<?php else : ?>
    <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>