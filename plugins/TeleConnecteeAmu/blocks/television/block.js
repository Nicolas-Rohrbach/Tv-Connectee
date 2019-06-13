( function( blocks, element, data  ) {

    var el = element.createElement;

    blocks.registerBlockType( 'tvconnecteeamu/add-television', {
        title: 'Ajout de television',
        icon: 'smiley',
        category: 'common',

        edit: function() {
            return "Formulaire pour inscrire des comptes television";
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