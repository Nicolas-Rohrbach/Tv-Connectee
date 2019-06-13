<?php

function alert_render_callback( $attributes, $content ) {
    $alert = new Alert();
    $model = new AlertManager();
    $view = new ViewAlert();
    if(is_page()){
        $years = $model->getCodeYear();
        $groups = $model->getCodeGroup();
        $halfgroups = $model->getCodeHalfgroup();

        $alert->createAlert();
        return $view->displayAlertCreationForm($years, $groups, $halfgroups);
    }
}

function block_alert() {
    wp_register_script(
        'alert-script',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-data' )
    );

    register_block_type('tvconnecteeamu/add-alert', array(
        'editor_script' => 'alert-script',
        'render_callback' => 'alert_render_callback'
    ));
}
add_action( 'init', 'block_alert' );