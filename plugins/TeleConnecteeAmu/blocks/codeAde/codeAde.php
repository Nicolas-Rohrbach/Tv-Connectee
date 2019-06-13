<?php

function code_ade_render_callback( $attributes, $content ) {
    $codeAde = new CodeAde();
    $view = new ViewCodeAde();
    if(is_page()){
        $codeAde->insertCode();
        return $view->displayFormAddCode();
    }
}

function block_code_ade() {
    wp_register_script(
        'code_ade-script',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-data' )
    );

    register_block_type('tvconnecteeamu/add-code_ade', array(
        'editor_script' => 'code_ade-script',
        'render_callback' => 'code_ade_render_callback'
    ));
}
add_action( 'init', 'block_code_ade' );