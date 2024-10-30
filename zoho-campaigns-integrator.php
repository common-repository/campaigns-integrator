<?php
/*
Plugin Name: Zoho Campaigns Integrator
Plugin URI: http://databytebank.com/
Description: A simple wordpress plugin to integrate Wordpress with Zoho Campaigns. This plugin will help you to insert a form in any page or post by inserting a short code "[zohocampaign]". The form data will be used to insert a contact into the Zoho Campaigns. User will get an eamil from Zoho Campaigns to confirm subscription. The list key to  be used can be set through the shortcode attribute 'list_key'. The plugin supports recaptcha in the form to stop spam. The settings page can be accessed on the left side bar.
Version: 1.0.0
Author: wp_candyman
Author URI: http://databytebank.com/
License: GPL2

Copyright 2013  wp_candyman  (email : )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

require_once('recaptchalib.php');

function init_zoho_campaigns_integrator()
{
wp_enqueue_style('zoho-campaigns-integrator_css', plugins_url('/css/main.css', __FILE__));
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-form');
wp_enqueue_script('zoho-campaigns-integrator_validate', plugins_url('/js/validate.js', __FILE__));
wp_enqueue_script('zoho-campaigns-integrator_form_submit',plugins_url('/js/ajax-submit-form.js', __FILE__));
wp_localize_script( 'zoho-campaigns-integrator_form_submit', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
wp_localize_script( 'zoho-campaigns-integrator_validate', 'ajax_object',array( ) );
}




function zohoCampaignForm($atts)
{
	
extract( shortcode_atts( array(
		'recaptcha' => 'disable',
		'sendemail' => 'disable',
		'fields'	=> 'all',
		'contact_form7_id'=>'',
		'list_key'=>'',
	), $atts ) );
	
update_option('_list_key', $list_key);
	

if($contact_form7_id=='')
{	
$options = get_option('zoho_campaigns_integrator_recaptcha_options' );
$publickey=$options['public_key'];



if($sendemail=="enable")
{
$emailhtml="<input type='hidden' name='sendemail' value='yes'/>";
}
else{
$emailhtml="";
}
if($recaptcha=="enable")
{
$recaptcha_html="<p><input type='hidden' name='recap' value='yes'/>";
$recaptcha_html.=recaptcha_get_html($publickey);
$recaptcha_html.="</p>";
}
else {
$recaptcha_html="";
}


$form_action=  plugins_url('/form-process.php',__FILE__);

if($fields=="all")
{
$form_string= "
<p>
<label>First Name : </label><br/>
<input type='text' name='first_name' id='first_name'/>
</p>

<p>
<label>Email : </label><br/>
<input type='text' name='email' id='email'/>
</p>
";
}

$return_string= "<div class='lead_form_div'><form id='lead_form' accept-charset='UTF-8' method='POST' name='lead_form' action='$form_action' onsubmit='validate()'>"
.$form_string.$emailhtml.$recaptcha_html
."<p>
<input type='submit' name='submit' value='Submit'/>
</p>
</form></div>";
return $return_string;
}

else{
global $wpcf7_contact_form;
if ( ! ( $wpcf7_contact_form = wpcf7_contact_form( $contact_form7_id ) ) )
return 'Contact form not found!';
$form = $wpcf7_contact_form->form_html();
return $form;
}
}





function recaptcha()
{
$update_action=  plugins_url('/options.php',__FILE__);
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon('themes'); ?>
	<h2>Zoho Campaigns integrator options</h2>
	<form method="post" action="options.php"> 
	<?php
	settings_fields( 'zoho-campaigns-integrator_recaptcha_options_group' ); 
	do_settings_sections('recaptcha-settings');
	submit_button();
	echo '</form>';
	echo '</div>';
}

function reCAPTCHA_settings_text(){
echo '<p>Enter the private key and public key from your re-CAPTCHA account at <a href="http://www.google.com/recaptcha" target="_blank">http://www.google.com/recaptcha</a> </p>';

}

function private_key_field(){
$options = get_option('zoho_campaigns_integrator_recaptcha_options');
echo "<input id='private_key' name='zoho_campaigns_integrator_recaptcha_options[private_key]' size='40' type='text' value='{$options['private_key']}'/><br/>";
}

function public_key_field(){
$options = get_option('zoho_campaigns_integrator_recaptcha_options');
echo "<input id='public_key' name='zoho_campaigns_integrator_recaptcha_options[public_key]' size='40' type='text' value='{$options['public_key']}'/><br/>";
}

function my_plugin_menu() {
	add_menu_page( 'Zoho Campaigns Integrator', 'Zoho Campaigns Integrator', 'activate_plugins', 'zoho-campaigns-integrator-main-menu','my_plugin_options');
	add_submenu_page( 'zoho-campaigns-integrator-main-menu', 'reCAPTCHA Settings', 'reCAPTCHA', 'activate_plugins', 'recaptcha-settings', 'recaptcha' );
	
}

function my_plugin_options() {
$update_action=  plugins_url('/options.php',__FILE__);
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon('themes'); ?>
	<h2>Zoho Campaigns integrator options</h2>
	<form method="post" action="options.php"> 
	<?php
	settings_fields( 'zoho-campaigns-integrator_options_group' ); 
	do_settings_sections('zoho-campaigns-integrator_option');
	submit_button();
	echo '</form>';
	echo '</div>';
}

function plugin_section_text() {
echo '<p>Enter the Auth Token for zoho Campaigns</p>';
echo '<p>You can get the Auth Token after logging into your Campaigns account and visiting <a href="https://campaigns.zoho.com/home.do#settings/api" target="_blank">https://campaigns.zoho.com/home.do#settings/api</a></p>';
}

function plugin_setting_authtoken() {
$options = get_option('my_option_name');
echo "<input id='authtoken' name='my_option_name[authtoken]' size='40' type='text' value='{$options['authtoken']}'/><br/>";
}


function register_my_setting() {
	register_setting( 'zoho-campaigns-integrator_options_group', 'my_option_name' ); 
	add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'zoho-campaigns-integrator_option');
	add_settings_field('authtoken', 'Auth Token', 'plugin_setting_authtoken', 'zoho-campaigns-integrator_option', 'plugin_main');
	
	register_setting( 'zoho-campaigns-integrator_recaptcha_options_group', 'zoho_campaigns_integrator_recaptcha_options' ); 
	add_settings_section('reCAPTCHA_settings', 'reCAPTCHA Settings', 'reCAPTCHA_settings_text', 'recaptcha-settings');
	add_settings_field('private_key', 'Private Key', 'private_key_field', 'recaptcha-settings', 'reCAPTCHA_settings');
	add_settings_field('public_key', 'Public Key', 'public_key_field', 'recaptcha-settings', 'reCAPTCHA_settings');
	
	
} 


function action_wpcf7_before_send_mail( $contact_form ) 
{
	$list_key = get_option('_list_key');
	$submission = WPCF7_Submission::get_instance();
 
if ( $submission ) {
    $posted_data = $submission->get_posted_data();
}

	$form_data="";

if($posted_data['first_name']!="")
$form_data.='<fl val="First Name">'.$posted_data['first_name'].'</fl>';

if($posted_data['email']!="")
$form_data.='<fl val="Contact Email">'.$posted_data['email'].'</fl>';

$url = 'https://campaigns.zoho.com/api/xml/listsubscribe';
$xmldata='<xml>
'.$form_data.'
</xml>';

$options = get_option('my_option_name' );
$authtoken=$options['authtoken'];

$fields = array(
            'version'=>1,
            'authtoken'=>$authtoken,
            'scope'=>'CampaignsAPI',
            'resfmt'=>'XML',
            'listkey'=>$list_key,            
			'contactinfo'=>$xmldata
        );
$fields_string = NULL;
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
$fields_string = rtrim($fields_string,'&');


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL


curl_exec($ch);

curl_close($ch);

}



function my_action_callback()
{
$first_name=sanitize_text_field($_POST['first_name']);
if(is_email($_POST['email']))
$email=sanitize_email($_POST['email']);
$chkrecap=sanitize_text_field($_POST['recap']);


$form_data="";

if($first_name!="")
$form_data.='<fl val="First Name">'.$first_name.'</fl>';

if($email!="")
$form_data.='<fl val="Contact Email">'.$email.'</fl>';


$list_key = get_option('_list_key');
$options = get_option('my_option_name' );
$authtoken=$options['authtoken'];

if($chkrecap=="yes")
{
$recapoptions = get_option('zoho_campaigns_integrator_recaptcha_options' );
$privatekey=$recapoptions['private_key'];
$resp = recaptcha_check_answer ($privatekey,
                                sanitize_text_field($_SERVER["REMOTE_ADDR"]),
                                sanitize_text_field($_POST["recaptcha_challenge_field"]),
                                sanitize_text_field($_POST["recaptcha_response_field"]));
}
                                
                                


if ((!$resp->is_valid)&&($chkrecap=="yes")) {
    // What happens when the CAPTCHA was entered incorrectly
    die ("fail");
  } else {
    // Your code here to handle a successful verification


$url = 'https://campaigns.zoho.com/api/xml/listsubscribe';
$xmldata='<xml>
'.$form_data.'
</xml>';

$fields = array(
            'version'=>1,
            'authtoken'=>$authtoken,
            'scope'=>'CampaignsAPI',
            'resfmt'=>'XML',
            'listkey'=>$list_key,            
			'contactinfo'=>$xmldata
        );
$fields_string = NULL;
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
$fields_string = rtrim($fields_string,'&');


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL


curl_exec($ch);

curl_close($ch);
}
}


if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'my_plugin_menu' );
  add_action( 'admin_init', 'register_my_setting' );
  add_action( 'wp_ajax_my_action', 'my_action_callback' );
  add_action( 'wp_ajax_nopriv_my_action', 'my_action_callback' );
} else {
  // non-admin enqueues, actions, and filters
  
add_action('init', 'init_zoho_campaigns_integrator');
add_shortcode('zohocampaign', 'zohoCampaignForm'); 
add_action( 'wpcf7_before_send_mail', 'action_wpcf7_before_send_mail', 10, 1 );
}

?>