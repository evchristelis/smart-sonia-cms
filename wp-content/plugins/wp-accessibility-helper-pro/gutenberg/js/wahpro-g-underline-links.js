/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/underline-links', {
    title       : 'WAH Pro - Underline links',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content           : { type: 'string' },
        underlineLinksButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateUnderlineLinksButtonTitle(event){
            props.setAttributes({underlineLinksButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout wah-call-underline-links",
                    title        : "Underline links",
                    'aria-label' : "Underline links",
                },
                props.attributes.underlineLinksButtonTitle ? props.attributes.underlineLinksButtonTitle : 'Underline links'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Underline links',
                    onChange    : updateUnderlineLinksButtonTitle
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
                    className    : "wah-action-button wah-g-action-button wahout wah-call-underline-links",
                    title        : "Underline links",
                    'aria-label' : "Underline links",
                },
                props.attributes.underlineLinksButtonTitle ? props.attributes.underlineLinksButtonTitle : 'Underline links'
            ),
        );
    }

})
