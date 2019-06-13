( function( blocks, element, data  ) {

    var el = element.createElement;

    blocks.registerBlockType( 'tvconnecteeamu/add-student', {
        title: 'Ajout d\'étudiant',
        icon: 'smiley',
        category: 'common',

        edit: function() {
            return "Formulaire pour inscrire des élèves avec un fichier Excel";
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