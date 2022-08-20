<?php
/**
 * Plugin Name: Bem-wp-contact-form
 * Description: 19th/AUG/2022. Bemgba, my second plugin. 
 * author: UTOR BEMGBA
 */

//if any access to this plugin is not through the absolute path, then exit
if(!defined('ABSPATH'))
{
    echo'what are you tring to do? <br>Access the plugin in the Wordpress Aplication';
    exit;
}
//creating plugin using OOP
    class BemWpContactForm
    {
        public function _construct()
        {
            //wordpress hook (i.e init) functionality
        add_action('init',array($this, 'create_custom_post_type'));
        }

        public function create_custom_post_type()
        {
            $args=array
            (
                'public'=> true,
                'has_archive'=> true,
                'supports'=>array('title'),
                'exclude_from_search'=>true,
                'publicly_queryable'=>false,
                'capability'=> 'manage_options',
                'labels'=> array(
                    'name'=>'Contact Form',
                    'singular_name'=>'Contact Form Entry'),
                'menu_icon'=>'dashicons-media-text',
            );
            register_post_type('contact_form',$args);
        }

    }


    new BemWpContactForm;  
