/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/letter-spacing', {
    title       : 'WAH Pro - letter spacing',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content                  : { type: 'string' },
        letterSpacingButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateletterSpacingButtonTitle(event){
            props.setAttributes({letterSpacingButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout set-wah-letter_spacing",
                    title        : "Letter spacing",
                    'aria-label' : "Letter spacing",
                },
                props.attributes.letterSpacingButtonTitle ? props.attributes.letterSpacingButtonTitle : 'Letter spacing'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Letter spacing',
                    onChange    : updateletterSpacingButtonTitle
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
                    className    : "wah-action-button wah-g-action-button wahout set-wah-letter_spacing",
                    title        : "Letter spacing",
                    'aria-label' : "Letter spacing",
                },
                props.attributes.letterSpacingButtonTitle ? props.attributes.letterSpacingButtonTitle : 'Letter spacing'
            ),
        );
    }

})
