<?php

function p2p_macelree(){

	// Posts and Attorneys
	p2p_register_connection_type( array(
	    'name' => 'post_attorney',
	    'from' => 'post',
	    'to' => 'attorney'
	) );

	// Posts and Practice Areas
	p2p_register_connection_type( array(
	    'name' => 'post_practice-area',
	    'from' => 'post',
	    'to' => 'practice-area',
	    'reciprocal' => true
	) );

	// Posts and Offices
	p2p_register_connection_type( array(
	    'name' => 'post_office',
	    'from' => 'post',
	    'to' => 'office',
	    'reciprocal' => false
	) );



	// Attorney and Office
	p2p_register_connection_type( array(
	    'name' => 'attorney_office',
	    'from' => 'attorney',
	    'to' => 'office',
	    'reciprocal' => true
	) );


	// Attorney and Practice Area
	p2p_register_connection_type( array(
	    'name' => 'attorney_practice-area',
	    'from' => 'attorney',
	    'to' => 'practice-area',
	    'reciprocal' => true,
	    'fields' => array(
        'order' => array(
            'title' => 'Order',
            'type' => 'text',
        ),
      )
	) );


}

add_action( 'p2p_init', 'p2p_macelree' );