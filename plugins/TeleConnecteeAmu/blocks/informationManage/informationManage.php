<?php

function information_management_render_callback( $attributes, $content ) {
    $information = new Information();
    if(is_page()){
        $information->deleteInformations();
        $information->informationManagement();
    }
}

function block_information_management() {
    wp_register_script(
        'information_manage-script',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-data' )
    );

    register_block_type('tvconnecteeamu/manage-information', array(
        'editor_script' => 'information_manage-script',
        'render_callback' => 'information_management_render_callback'
    ));
}
add_action( 'init', 'block_information_management' );