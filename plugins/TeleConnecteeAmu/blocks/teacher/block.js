( function( blocks, element, data  ) {

    var el = element.createElement;

    blocks.registerBlockType( 'tvconnecteeamu/add-teacher', {
        title: 'Ajout d\'enseignant',
        icon: 'smiley',
        category: 'common',

        edit: function() {
            return "Formulaire pour inscrire des enseignants avec un fichier Excel";
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