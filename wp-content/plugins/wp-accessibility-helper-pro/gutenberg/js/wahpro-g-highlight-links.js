/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/highlight-links', {
    title       : 'WAH Pro - Highlight links',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content           : { type: 'string' },
        hLinksButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateHighlightLinksButtonTitle(event){
            props.setAttributes({hLinksButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout wah-call-highlight-links",
                    title        : "Highlight links",
                    'aria-label' : "Highlight links",
                },
                props.attributes.hLinksButtonTitle ? props.attributes.hLinksButtonTitle : 'Highlight links'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Highlight links',
                    onChange    : updateHighlightLinksButtonTitle
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
                    className    : "wah-action-button wah-g-action-button wahout wah-call-highlight-links",
                    title        : "Highlight links",
                    'aria-label' : "Highlight links",
                },
                props.attributes.hLinksButtonTitle ? props.attributes.hLinksButtonTitle : 'Highlight links'
            ),
        );
    }

})
