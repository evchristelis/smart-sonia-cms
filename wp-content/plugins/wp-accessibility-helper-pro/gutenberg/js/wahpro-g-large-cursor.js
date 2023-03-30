/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/large-cursor', {
    title       : 'WAH Pro - Large cursor',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content           : { type: 'string' },
        largeCursorButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateLargeCursorButtonTitle(event){
            props.setAttributes({largeCursorButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout wah_large_cursor wah-call-large_cursor",
                    title        : "Large cursor",
                    'aria-label' : "Large cursor",
                },
                props.attributes.largeCursorButtonTitle ? props.attributes.largeCursorButtonTitle : 'Large cursor'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Large cursor',
                    onChange    : updateLargeCursorButtonTitle
                }
            )
        );
    },

    save: function(props) {

        return wp.element.createElement(
            "span",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout wah_large_cursor wah-call-large_cursor",
                    title        : "Large cursor",
                    'aria-label' : "Large cursor",
                },
                props.attributes.largeCursorButtonTitle ? props.attributes.largeCursorButtonTitle : 'Large cursor'
            ),
        );
    }

})
