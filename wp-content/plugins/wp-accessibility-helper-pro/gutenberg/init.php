<?php
// Font resize buttons
add_action('enqueue_block_editor_assets', 'loadWAHProFontResize');
function loadWAHProFontResize() {
    wp_enqueue_script(
        'wahpro-font-resize',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-font-resize.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Letter spacing button
add_action('enqueue_block_editor_assets', 'loadWAHProLetterSpacing');
function loadWAHProLetterSpacing() {
    wp_enqueue_script(
        'wahpro-letter-spacing',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-letter-spacing.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Highlight links button
add_action('enqueue_block_editor_assets', 'loadWAHProHighlightLinks');
function loadWAHProHighlightLinks() {
    wp_enqueue_script(
        'wahpro-highlight-links',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-highlight-links.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Highlight links button
add_action('enqueue_block_editor_assets', 'loadWAHProHighlightTitles');
function loadWAHProHighlightTitles() {
    wp_enqueue_script(
        'wahpro-highlight-titles',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-highlight-titles.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Underline links button
add_action('enqueue_block_editor_assets', 'loadWAHProUnderlineLinks');
function loadWAHProUnderlineLinks() {
    wp_enqueue_script(
        'wahpro-underline-links',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-underline-links.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Readable font button
add_action('enqueue_block_editor_assets', 'loadWAHProReadableFont');
function loadWAHProReadableFont() {
    wp_enqueue_script(
        'wahpro-readable-font',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-readable-font.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Large cursor button
add_action('enqueue_block_editor_assets', 'loadWAHProLargeCursor');
function loadWAHProLargeCursor() {
    wp_enqueue_script(
        'wahpro-large-cursor',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-large-cursor.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Monochrome button
add_action('enqueue_block_editor_assets', 'loadWAHProMonochrome');
function loadWAHProMonochrome() {
    wp_enqueue_script(
        'wahpro-monochrome',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-monochrome.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Sepia button
add_action('enqueue_block_editor_assets', 'loadWAHProSepia');
function loadWAHProSepia() {
    wp_enqueue_script(
        'wahpro-sepia',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-sepia.js',
        array('wp-blocks','wp-editor'),
        true
    );
}

// Sepia button
add_action('enqueue_block_editor_assets', 'loadWAHProGreyscaleImages');
function loadWAHProGreyscaleImages() {
    wp_enqueue_script(
        'wahpro-greyscale-images',
        plugin_dir_url(__FILE__) . 'js/wahpro-g-greyscale-images.js',
        array('wp-blocks','wp-editor'),
        true
    );
}
