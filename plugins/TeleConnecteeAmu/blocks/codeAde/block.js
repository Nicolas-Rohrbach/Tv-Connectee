( function( blocks, element, data  ) {

    var el = element.createElement;

    blocks.registerBlockType( 'tvconnecteeamu/add-code_ade', {
        title: 'Ajout code ADE',
        icon: 'smiley',
        category: 'common',

        edit: function() {
            return "Ajoute un code ADE via un formulaire";
        },
        save: function() {
            return "test";
        },
    } );
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.data,
) );