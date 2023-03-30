/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/font-resize', {
    title       : 'WAH Pro - font resize',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content : { type: 'string' },
        minusButtonTitle : { type: 'string' },
        plusButtonTitle  : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateMinusButtonValue(event){
            props.setAttributes({minusButtonTitle: event.target.value})
        }
        function updatePlusButtonValue(event){
            props.setAttributes({plusButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button smaller wahout",
                    title        : "smaller font size",
                    'aria-label' : "smaller font size",
                },
                props.attributes.minusButtonTitle ? props.attributes.minusButtonTitle : 'A-'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'A- button title',
                    onChange    : updateMinusButtonValue
                }
            ),
            React.createElement(
                "div"
            ),
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button larger wahout",
                    title        : "larger font size",
                    'aria-label' : "larger font size",
                },
                props.attributes.plusButtonTitle ? props.attributes.plusButtonTitle : 'A+'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'A+ button title',
                    onChange    : updatePlusButtonValue
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
                    className    : "wah-action-button wah-g-action-button smaller wahout",
                    title        : "smaller font size",
                    'aria-label' : "smaller font size",
                },
                props.attributes.minusButtonTitle ? props.attributes.minusButtonTitle : 'A-'
            ),
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "wah-action-button wah-g-action-button larger wahout",
                    title        : "larger font size",
                    'aria-label' : "larger font size",
                },
                props.attributes.plusButtonTitle ? props.attributes.plusButtonTitle : 'A+'
            ),
        );
    }

})
