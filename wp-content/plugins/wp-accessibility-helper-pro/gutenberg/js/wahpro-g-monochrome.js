/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/monochrome', {
    title       : 'WAH Pro - Monochrome',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content           : { type: 'string' },
        monochromeButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateMonochromeButtonTitle(event){
            props.setAttributes({monochromeButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout wah_enable_monochrome_mode wah-call-monochrome_mode",
                    title        : "Monochrome",
                    'aria-label' : "Monochrome",
                },
                props.attributes.monochromeButtonTitle ? props.attributes.monochromeButtonTitle : 'Monochrome'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Monochrome',
                    onChange    : updateMonochromeButtonTitle
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
                    className    : "wah-action-button wah-g-action-button wahout wah_enable_monochrome_mode wah-call-monochrome_mode",
                    title        : "Monochrome",
                    'aria-label' : "Monochrome",
                },
                props.attributes.monochromeButtonTitle ? props.attributes.highlightTitlesButtonTitle : 'Monochrome'
            ),
        );
    }

})
