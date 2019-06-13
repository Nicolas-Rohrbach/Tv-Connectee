( function( blocks, element, data  ) {

    var el = element.createElement;

    blocks.registerBlockType( 'tvconnecteeamu/add-technician', {
        title: 'Ajout de technicien',
        icon: 'smiley',
        category: 'common',

        edit: function() {
            return "Formulaire pour inscrire des techniciens";
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