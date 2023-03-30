/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/readable-font', {
    title       : 'WAH Pro - Readable font',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content           : { type: 'string' },
        underlineLinksButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateReadableFontButtonTitle(event){
            props.setAttributes({underlineLinksButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout wah-call-readable-fonts",
                    title        : "Readable font",
                    'aria-label' : "Readable font",
                },
                props.attributes.underlineLinksButtonTitle ? props.attributes.underlineLinksButtonTitle : 'Readable font'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Readable font',
                    onChange    : updateReadableFontButtonTitle
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
                    className    : "wah-action-button wah-g-action-button wahout wah-call-readable-fonts",
                    title        : "Readable font",
                    'aria-label' : "Readable font",
                },
                props.attributes.underlineLinksButtonTitle ? props.attributes.underlineLinksButtonTitle : 'Readable font'
            ),
        );
    }

})
