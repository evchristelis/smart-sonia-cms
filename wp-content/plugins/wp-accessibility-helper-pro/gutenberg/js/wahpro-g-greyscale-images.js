/* This section of the code registers a new block, sets an icon and a category, and indicates what type of fields it'll include. */

wp.blocks.registerBlockType('wahpro/greyscale-images', {
    title       : 'WAH Pro - greyscale images',
    icon        : 'universal-access',
    category    : 'widgets',
    attributes  : {
        content           : { type: 'string' },
        greyscaleImagesButtonTitle : { type: 'string' },
    },

    /* This configures how the content and color fields will work, and sets up the necessary elements */
    edit: function(props) {

        function updateGreyscaleImagesButtonTitle(event){
            props.setAttributes({greyscaleImagesButtonTitle: event.target.value})
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "button",
                {
                    type         : "button",
                    className    : "greyscale wah-action-button wah-g-action-button wahout wah-call-greyscale",
                    title        : "Greyscale images",
                    'aria-label' : "Greyscale images",
                },
                props.attributes.greyscaleImagesButtonTitle ? props.attributes.greyscaleImagesButtonTitle : 'Greyscale images'
            ),
            React.createElement("input",
                {
                    type        : "text",
                    value       : props.attributes.content,
                    placeholder : 'Greyscale images',
                    onChange    : updateGreyscaleImagesButtonTitle
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
                    className    : "greyscale wah-action-button wah-g-action-button wahout wah-call-greyscale",
                    title        : "Greyscale images",
                    'aria-label' : "Greyscale images",
                },
                props.attributes.greyscaleImagesButtonTitle ? props.attributes.greyscaleImagesButtonTitle : 'Greyscale images'
            ),
        );
    }

})
