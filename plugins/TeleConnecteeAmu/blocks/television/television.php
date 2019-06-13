<?php

function television_render_callback( $attributes, $content ) {
    $television = new Television();
    $model = new TelevisionManager();
    $view = new ViewTelevision();
    if(is_page()){
        $years = $model->getCodeYear();
        $groups = $model->getCodeGroup();
        $halfgroups = $model->getCodeHalfgroup();

        $television->insertTelevision();
        return $view->displayFormTelevision($years, $groups, $halfgroups);
    }
}

function block_television() {
    wp_register_script(
        'television-script',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-data' )
    );

    register_block_type('tvconnecteeamu/add-television', array(
        'editor_script' => 'television-script',
        'render_callback' => 'television_render_callback'
    ));
}
add_action( 'init', 'block_television' );