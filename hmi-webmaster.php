<?php
/*
  Plugin Name: HMI Webmaster
  Description: A plugin for webmasters to manage google tags.
  Version: 1.0
  Author: HMI INNOVATIONS
  Author URI: https://hmiinnovations.com
  License: GPL2
 */
// bootstrap loadding...
$config = [
    'version'=> '1.0',
];
function hmi_settings() {
  
    if (isset($_POST['submit_setting'])) {
        $hmi_get_url_parameters = isset($_POST['hmi_url_parameters']) ? $_POST['hmi_url_parameters']: '';
        $hmi_url_parameters = json_encode( $hmi_get_url_parameters);
        $google_analytics_id = isset($_POST['HMI_Google_Analytics_Id']) ? $_POST['HMI_Google_Analytics_Id'] : '';
        $google_tag_manager_id = isset($_POST['HMI_Google_Tag_Manager_Id']) ? $_POST['HMI_Google_Tag_Manager_Id']: '';
        $hmi_url_parameters_form_redirect_url = isset($_POST['HMI_GCLID_Form_Redirect_URL']) ? $_POST['HMI_GCLID_Form_Redirect_URL']: '';
        update_option('hmi_url_parameters', $hmi_url_parameters);
        update_option('HMI_Google_Analytics_Id', $google_analytics_id);
        update_option('HMI_Google_Tag_Manager_Id', $google_tag_manager_id);
        update_option('HMI_GCLID_Form_Redirect_URL', $hmi_url_parameters_form_redirect_url);
        echo '<div class="updated"><p>Settings saved successfully!</p></div>';
    }     
}
hmi_settings();
function load_bootstrap()  {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('jquery');   
}
add_action('wp_enqueue_scripts', 'load_bootstrap');
// creations of page.
function create_custom_page() {
    $page_title = 'book-online';
    $page_check = get_page_by_title($page_title);
    if (!$page_check) {
        $new_page = array(
            'post_title'    => $page_title,
            'post_content'  => 'This is the content of my custom page. You can change this.',  
            'post_status'   => 'publish',  
            'post_author'   => 1,  
            'post_type'     => 'page',  
        );
        wp_insert_post($new_page);
    }
}

add_action('admin_init', 'create_custom_page'); 
function hmi_activate_plugin() {
    // this code to create value of opaton in table 
    $current_version = get_option('plugin_version');
    if ($current_version === false) {
        add_option('plugin_version', $config['version']);
        $current_version =  $config['version']; 
    }
    if ($current_version !=  $config['version']) {
        update_option('plugin_version',  $config['version']);
    }
}
// te file in the active themes.
$plugin_directory = WP_PLUGIN_DIR . '/hmi-webmaster';  
$source_file = $plugin_directory . '/page-book-online.php'; 
$theme_directory = get_template_directory();  
$destination_file = $theme_directory . '/page-book-online.php'; 
copy($source_file, $destination_file);

// register activation hooks 
register_activation_hook(__FILE__, 'hmi_activate_plugin');
function hmi_remove_plugin() {
    delete_option('plugin_version');
    delete_option('hmi_url_parameters');
    delete_option('HMI_Google_Analytics_Id');
    delete_option('HMI_Google_Tag_Manager_Id');
    delete_option('HMI_GCLID_Form_Redirect_URL');
    $page = get_page_by_path('redirect-hmi-form');
    wp_delete_post($page->ID , true);

}

// register uninstall hook
register_uninstall_hook(__FILE__, 'hmi_remove_plugin');

function hmi_set_gclid() {
    $queried_object = get_queried_object();

    if ($queried_object && isset($queried_object->post_name)) {
        $my_slug = $queried_object->post_name;
    
        if (!empty($my_slug) && $my_slug != 'book-online') {
            setcookie("page_slug", $my_slug, time() + 3600, "/");  
        }
    } else {
    }
    if(isset($_GET['gclid'])) {
        setcookie("hmi_gclid", $_GET['gclid']);
    }
    if(isset($_GET['utm_source'])) {
        setcookie("hmi_utm_source", $_GET['utm_source']); 
    }
    if(isset($_GET['utm_medium'])) {
        setcookie("hmi_utm_medium", $_GET['utm_medium']); 
    }

    if(isset($_GET['utm_campaign '])) {
        setcookie("hmi_utm_campaign ", $_GET['utm_campaign ']); 
    }
    if(isset($_GET['utm_term'])) {
         setcookie("hmi_utm_term", $_GET['utm_term']); 
    }
    if(isset($_GET['utm_content'])) {
         setcookie("hmi_utm_content", $_GET['utm_content']); 
    }
    }

add_action('wp', 'hmi_set_gclid');
function hmi_dasboard(){
    add_menu_page(
        'HMI_Webmaster_page',       
        'HMI Webmaster',            
        'manage_options',           
        'HMI_Webmaster_slug',       
        'HMI_dashboard',            
        'dashicons-info',
        20                                  
    );
}
// header code 
function hmi_header_code() {
 $gtag = get_option('HMI_Google_Tag_Manager_Id');
 if($gtag == ''){
 $gtag = get_option('HMI_Google_Analytics_Id');
                 }
 if($gtag != ''){
    ?>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-QCW41XS33F"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $gtag; ?>');
</script>
    <?php
}
}
add_action( 'wp_head', 'hmi_header_code' );
add_action('admin_menu', 'hmi_dasboard');
    function HMI_dashboard() {
    ?>
    <!-- commentes -->
    <div class="wrap">
<h1>HMI Webmaster Settings</h1>
<form name="setting_form" action="" method="POST">
<table class="form-table" role="presentation">
<tbody><tr>
<th scope="row"><label for="Hmi_Google_Analytics_Id">  Google Analytics Id</label></th>
<td><input type="text" name="HMI_Google_Analytics_Id" id="google_id" value="<?php echo get_option('HMI_Google_Analytics_Id') ?>" placeholder="Google Analytics Id" class="regular-text"></td>
</tr>
<tr>
<th scope="row"><label for="HMI_Google_Tag_manager_Id">Google Tag  Manager Id</label></th>
<td><input  type="text" name="HMI_Google_Tag_Manager_Id" id="google_tag_id" value="<?php echo get_option('HMI_Google_Tag_Manager_Id') ?>" placeholder="Google Tag Manager Id" class="regular-text"></td>
</tr>
<tr>
<th scope="row">Track Parameters </th>
<td>
<?php  $get_hmi_url_parameters =  get_option('hmi_url_parameters');
   $hmi_get_url_parameters_array =(array) json_decode($get_hmi_url_parameters, true);
 ?>
<fieldset><legend class="screen-reader-text">
		</legend>
	<label><input type="checkbox" name="hmi_url_parameters[gclid]" value="" <?php //print_r($hmi_get_url_parameters_array);exit;
 if(is_array($hmi_get_url_parameters_array) && array_key_exists('gclid',$hmi_get_url_parameters_array)) { echo 'checked="checked"';} ?>> <span  style="display: inline-block;min-width: 4em;">gclid</span></label>
	<label><input type="checkbox" name="hmi_url_parameters[utm_source]" value="" <?php if(is_array($hmi_get_url_parameters_array) && array_key_exists('utm_source',$hmi_get_url_parameters_array))  { echo 'checked="checked"'; } ?>> <span style="display: inline-block;min-width: 7em;">utm_source</span></label>
    <label><input type="checkbox" name="hmi_url_parameters[utm_medium]" value="" <?php if(is_array($hmi_get_url_parameters_array) && array_key_exists('utm_medium', $hmi_get_url_parameters_array))  { echo 'checked="checked"';} ?>> <span  style="display: inline-block;min-width: 7em;">utm_medium</span></label>
	<label><input type="checkbox" name="hmi_url_parameters[utm_campaign]" value="" <?php if(is_array($hmi_get_url_parameters_array) && array_key_exists('utm_campaign',$hmi_get_url_parameters_array) )  { echo 'checked="checked"';} ?>> <span style="display: inline-block;min-width: 7em;">utm_campaign </span></label>
    <label><input type="checkbox" name="hmi_url_parameters[utm_term]" value="" <?php if(is_array($hmi_get_url_parameters_array) && array_key_exists('utm_term', $hmi_get_url_parameters_array) )  { echo 'checked="checked"';} ?>> <span style="display: inline-block;min-width: 7em;">utm_term  </span></label>
    <label><input type="checkbox" name="hmi_url_parameters[utm_content]" value="" <?php if(is_array($hmi_get_url_parameters_array) && array_key_exists('utm_content', $hmi_get_url_parameters_array))  { echo 'checked="checked"';} ?>> <span style="display: inline-block;min-width: 7em;" span>utm_content </span></label>
    <label><input type="checkbox" name="hmi_url_parameters[page_slug]" value="" <?php if(is_array($hmi_get_url_parameters_array) && array_key_exists('page_slug', $hmi_get_url_parameters_array))  { echo 'checked="checked"';} ?>> <span>page_slug </span></label>
</fieldset>
</td>
</tr>
<tr>
<th scope="row"><label for="HMI_GCLID_Form_Redirect_URL">HMI GCLID Form Redirect URL</label></th>
<td><input  type="text" name="HMI_GCLID_Form_Redirect_URL" id="google_tag_id" value="<?php echo get_option('HMI_GCLID_Form_Redirect_URL') ?>" placeholder="Hmi Gclid form Redirect Url" class="regular-text" required='required'></td>
</tr>
<tr>
    <th></th>
    <td>
<p class="submit" > <input type="submit" name="submit_setting" value="Save Changes" class="button button-primary"></p>
</td> </tr>
<tr>
    <th scope="row">CTA Link Url</th>
        <td>
        <p id="url" class="submit"  style="color:#135e96;"><?php echo site_url('book-online'); ?></p>
        <button type="button" class="button button-primary" style = "width:100px;" onclick="Copy();">Copy</button>
    </td>
    </tr>
</tbody></table>
</form> 
<script>
function Copy() {
    var hmi_url_color = document.getElementById("url");
    var Url = document.getElementById("url").innerText; // Get the URL text
 navigator.clipboard.writeText(Url)
    hmi_url_color.style.color = 'green';
}
</script>
 </div> 
<?php } ?>

