<?php
/**
 * Plugin Name: simple-contact-form
 * Description: 5th/SEPT/2022. Bemgba, Interview preps. 
 * author: UTOR BEMGBA
 */

 //if any access to this plugin is not through the absolute path, then exit
if(!defined('ABSPATH'))
{
    echo'what are you tring to do? <br>Access the plugin in the Wordpress Aplication';
    exit;
}

class SimpleContactForm
    { 
        //first method() to be call at the class instance i.e __construct()
        Public function __construct()
        {//echo plugin_dir_url(__FILE__).'css/SimpleContactForm.css';
            //wordpress hook (i.e init) functionality
             //create custom post_type
        add_action('init', array($this, 'create_custom_post_type'));
        //  //Add assets(js, css,etc)
          add_action('wp_enqueue_scripts', array($this,'Load_assets'));
        //  //add short code
        add_shortcode('contact-form', array($this,'load_shortcode'));
         
        // load script
        add_action('wp_footer', array($this,'load_scripts'));

        //Register REST API
        add_action('rest_api_init', array($this, 'register_rest_api'));


        }

        function create_custom_post_type()
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
            register_post_type('simple_contact_form',$args);
        }


        function Load_assets()
        {
            wp_enqueue_style(
            'SimpleContactFormH',//handle
            plugin_dir_url(__FILE__).'css/SimpleContactForm.css',//WP function that uses php constant that locates the plugin folder to find the absolute location of the file in question
            array(),//no dependencies (e.g bootstrap)
            1,//version
            'all'//media
            );
            wp_enqueue_script(
                'SimpleContactFormH',//handle
                plugin_dir_url(__FILE__).'js/SimpleContactForm.js',//WP function that uses php constant that locates the plugin folder to find the absolute location of the file in question
                array('jquery'),//no dependencies (e.g bootstrap)
                1,//version
                true//footer
            );

        }

        public function load_shortcode()
        {
                        ?>

            <!-- //this will allow us write standard html into a fuction -->
            <div class="Simple-contact-form">
                <h4> Send us an email </h4>
                <p>Please fill the below form</p>
                <form id="Simple-contact-form_form" class="form-control">
                    <div class="form-group mb-2">
                        <input type="text" name="Name" placeholder="Name" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <input type="email" name="Email" placeholder="Email" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <input type="tel" name="phone" placeholder="phone" class="form-control">
                    </div>
                    <div class="form-group mb-2">
                        <textarea name="message" placeholder="Bemgba msg" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-block w-100" name="Submit">Submit</button>
                    </div>
                </form>
            </div>

            <?php 
        }
      public function load_scripts()
         {
                        //this will allow us write standard jQuery into a fuction
                        ?>
            <!-- now lets write  code -->
            <!-- <script>jQuery('#Simple-contact-form').submit(function(event) {//event.preventDefault();alert('submitted')});</script> -->
            <script>
            var nonce = '<?php echo wp_create_nonce('wp_rest'); ?>' //Creates a cryptographic token tied to a specific action, user, user session, and window of time.
            (function($) {
                $('#Simple-contact-form').submit(function(event) {
                    event.preventDefault();
                    var form = $(this).serialize();//take form data and arrange them serially
                    //console.log(form);

                    $.ajax({
                        method: 'post',
                        url:'<?php echo get_rest_url(null,'simple-contact-form/v1/send-email' ); ?>',

                        headers: {'x-wp-Nonce':nonce},//submits extra data along with form data
                        //nonce stands for number used once, in WP, it stops ppl from posting to my site from anothe site
                        data: form
                    })
                });
            })(jQuery)
            </script>
            <?php 
        }

      public function register_rest_api(){
            register_rest_route('simple-contact-form/v1','send-email',array(
            'method'=> 'POST',
            'callback'=> array($this, 'handle_contact_form')
            
            ));
            
            }
            
      public function handle_contact_form($data)
        {
            echo 'Endpoint working';

            // $headers = $data->get_headers();
            // $params = $data->get_params();
            // //echo json_encode($headers);
            // $nonce=  $headers['x_wp_nonce'][0];
            // //if nonce is not verified
            // if(!wp_verify_nonce($nonce, 'wp_rest')){
            // //echo 'nonce correct'
            //     return new WP_REST_Response('message not sent', 422);//
            // }
            //  //if nonce is verified, add data to the custom post type
            // $post_id= wp_insert_post([
            //     'post_type'=> 'simple_contact_form',
            //     'post_title'=> 'contact enquiry',
            //     'post_status'=> 'publish'
            // ]);
            // if($post_id){
            //     return new WP_REST_Response('Thank you', 200);
            //     }         
        }
    }

  $SimpleContactForm=  new SimpleContactForm;  