<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package Astra
 * @since 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$url = get_option('HMI_GCLID_Form_Redirect_URL');

  $get_hmi_url_parameters =  get_option('hmi_url_parameters');
  $hmi_get_url_parameters_array =(array) json_decode($get_hmi_url_parameters, true);
  
$url_gclid = '';
$url_utm_source = '';
$url_utm_medium = '';
$url_utm_campaign = '';
$url_utm_term = '';
$url_utm_content = '';
$url_page_slug = '';

if (is_array($hmi_get_url_parameters_array) && array_key_exists('gclid',$hmi_get_url_parameters_array) && isset($_COOKIE["hmi_gclid"])) {
	$hmi_gclid = $_COOKIE["hmi_gclid"];
	$url_gclid = '&gclid='.$hmi_gclid;
}

if (is_array($hmi_get_url_parameters_array) && array_key_exists('page_slug',$hmi_get_url_parameters_array)  && isset($_COOKIE["page_slug"])) {
	$page_slug = $_COOKIE["page_slug"];
	$url_page_slug = '&page_slug='.$page_slug;
}

if (is_array($hmi_get_url_parameters_array) && array_key_exists('utm_source',$hmi_get_url_parameters_array)  && isset($_COOKIE["hmi_utm_source"])) {
	$utm_source = $_COOKIE["hmi_utm_source"];
	$url_utm_source = '&utm_source='.$utm_source;
}
if (is_array($hmi_get_url_parameters_array) && array_key_exists('utm_medium',$hmi_get_url_parameters_array)  && isset($_COOKIE["hmi_utm_medium"])) {
	$utm_medium = $_COOKIE["hmi_utm_medium"];
	$url_utm_medium = '&utm_medium='.$utm_medium;
}
if (is_array($hmi_get_url_parameters_array) && array_key_exists('utm_campaign',$hmi_get_url_parameters_array)  && isset($_COOKIE["hmi_utm_campaign"])) {
	$utm_campaign = $_COOKIE["hmi_utm_campaign"];
	$url_utm_campaign = '&utm_campaign='.$utm_campaign;
}
if (is_array($hmi_get_url_parameters_array) && array_key_exists('utm_term',$hmi_get_url_parameters_array)  && isset($_COOKIE["hmi_utm_term"])) {
	$utm_term = $_COOKIE["utm_term"];
	$url_utm_term = '&utm_term='.$utm_term;
}
if (is_array($hmi_get_url_parameters_array) && array_key_exists('utm_content',$hmi_get_url_parameters_array)  && isset($_COOKIE["hmi_utm_content"])) {
	$utm_content = $_COOKIE["hmi_utm_content"];
	$url_utm_content = '&utm_content='.$utm_content;
}
$url = $url.'?'.$url_gclid.$url_page_slug.$url_utm_source.$url_utm_medium.$url_utm_campaign.$url_utm_term.$url_utm_content;
header('Location: '.$url);
?>