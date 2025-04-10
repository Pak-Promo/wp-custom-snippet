<?php
/****
    * Plugin Name: CUSTOM SNIPPET
    * Description: A plugin for webmasters to add script in head/body tags.
    * Version: 1.0.0
    * Author: PakPromo
    * Author URI: https://pak.promo
    * License: GPLv3
    * License URI: https://www.gnu.org/licenses/gpl-3.0.html
    */
$config = [
    'custom_snippet_version'=> '1.0',
];  
if (isset($_POST['submit_snippet'])) {
    $Custom_Snippet_Head = isset($_POST['Custom_Snippet_Head']) ? $_POST['Custom_Snippet_Head'] : '';
    $Custom_Snippet_Body = isset($_POST['Custom_Snippet_Body']) ? $_POST['Custom_Snippet_Body'] : '';
    $Custom_Snippet_Footer = isset($_POST['Custom_Script_Body_Tag']) ? $_POST['Custom_Script_Body_Tag'] : '';
    update_option('Custom_Snippet_Head', $Custom_Snippet_Head);
    update_option('Custom_Snippet_Body', $Custom_Snippet_Body);
    update_option('Custom_Snippet_Footer', $Custom_Snippet_Footer);
    echo '<div class="updated"><p>Custom snippet saved successfully!</p></div>';
}

function activate_custom_snippet() {
    // this code to create value of opaton in table 
    $current_version = get_option('custom_snippet_version');
    if ($current_version === false) {
        add_option('custom_snippet_version', $config['custom_snippet_version']);
        $current_version =  $config['custom_snippet_version']; 
    }
    if ($current_version !=  $config['custom_snippet_version']) {
        update_option('custom_snippet_version',  $config['custom_snippet_version']);
    }
}

// register activation hooks 
register_activation_hook(__FILE__, 'activate_custom_snippet');

function uninstall_custom_snippet() {
    delete_option('custom_snippet_version');
    delete_option('Custom_Snippet_Head');
    delete_option('Custom_Snippet_Body');
    delete_option('Custom_Snippet_Footer');
}

// register uninstall hook
register_uninstall_hook(__FILE__, 'uninstall_custom_snippet');

function custom_snippet_dasboard(){
    add_menu_page(
        'Custom_Snippet_page',       
        'Custom Snippet',            
        'manage_options',           
        'Custom_Snippet_slug',       
        'Custom_Snippet_dashboard',            
        'dashicons-info',
        20                                  
    );
}
// header code 
function custom_snippet_head() {
    echo get_option('Custom_Snippet_Head');
}

function custom_snippet_body()
{
    echo get_option('Custom_Snippet_Body');
}

function custom_snippet_footer()
{
    echo get_option('Custom_Snippet_Footer');
}

add_action('wp_head', 'custom_snippet_head');
add_action('wp_body_open', 'custom_snippet_body');
add_action('wp_footer', 'custom_snippet_footer');
add_action('admin_menu', 'custom_snippet_dasboard');

function Custom_Snippet_dashboard()
{
?>
<div class="wrap">
    <h1>Custom Snippet</h1>
    <form name="snippet_form" action="" method="POST">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="custom_snippet_head">Head Scripts <br>(Include in Head Tag)</label></th>
                    <td>
                        <textarea rows="5" name="Custom_Snippet_Head" id="custom_snippet_head" placeholder="Custom Script for Head Tag (before closing </head> tag)" class="regular-text"><?php echo get_option('Custom_Snippet_Head') ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="custom_snippet_body">Body Scripts <br>(Include after Body Tag Started)</label></th>
                    <td>
                        <textarea rows="5" name="Custom_Snippet_Body" id="custom_snippet_body" placeholder="Custom Script for Body (after opening <body> tag)" class="regular-text"><?php echo get_option('Custom_Snippet_Body') ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="custom_snippet_footer">Footer Scripts <br>(Include in Footer)</label></th>
                    <td>
                        <textarea rows="5" name="Custom_Snippet_Footer" id="custom_snippet_footer" placeholder="Custom Script for Footer (before closing </body> tag)" class="regular-text"><?php echo get_option('Custom_Snippet_Footer') ?></textarea>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <p class="submit" > <input type="submit" name="submit_snippet" value="Save Scripts" class="button button-primary"></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </form> 
</div> 
<?php } ?>