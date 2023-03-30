/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/highlight-titles', {
    title       : 'WAH Pro - Highlight titles',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content           : { type: 'string' },
        highlightTitlesButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updatehighlightTitlesButtonTitle(event){
            props.setAttributes({highlightTitlesButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button wahout wah-highlight-titles wah-call-highlight-titles",
                    title        : "Highlight titles",
                    'aria-label' : "Highlight titles",
                },
                props.attributes.highlightTitlesButtonTitle ? props.attributes.highlightTitlesButtonTitle : 'Highlight titles'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Highlight titles',
                    onChange    : updatehighlightTitlesButtonTitle
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
                    className    : "wah-action-button wah-g-action-button wahout wah-highlight-titles wah-call-highlight-titles",
                    title        : "Highlight titles",
                    'aria-label' : "Highlight titles",
                },
                props.attributes.highlightTitlesButtonTitle ? props.attributes.highlightTitlesButtonTitle : 'Highlight titles'
            ),
        );
    }

})
