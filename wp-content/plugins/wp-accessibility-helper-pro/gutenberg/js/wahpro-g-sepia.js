/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/sepia', {
    title       : 'WAH Pro - Sepia',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content           : { type: 'string' },
        sepiaButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateSepiaButtonTitle(event){
            props.setAttributes({sepiaButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout wah_enable_sepia_mode wah-call-sepia_mode",
                    title        : "Sepia",
                    'aria-label' : "Sepia",
                },
                props.attributes.sepiaButtonTitle ? props.attributes.sepiaButtonTitle : 'Sepia'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Sepia',
                    onChange    : updateSepiaButtonTitle
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
                    className    : "wah-action-button wah-g-action-button wahout wah_enable_sepia_mode wah-call-sepia_mode",
                    title        : "Sepia",
                    'aria-label' : "Sepia",
                },
                props.attributes.sepiaButtonTitle ? props.attributes.highlightTitlesButtonTitle : 'Sepia'
            ),
        );
    }

})
