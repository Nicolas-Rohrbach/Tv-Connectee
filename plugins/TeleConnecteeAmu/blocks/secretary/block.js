( function( blocks, element, data  ) {

    var el = element.createElement;

    blocks.registerBlockType( 'tvconnecteeamu/add-secretary', {
        title: 'Ajout de secretaire',
        icon: 'smiley',
        category: 'common',

        edit: function() {
            return "Formulaire pour inscrire des secretaires";
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