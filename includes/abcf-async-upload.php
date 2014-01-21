<?php
/**
 * Save file uploaded by plupload to temp dir.
 * File is resized and saved to collection folders. Record is added to DB.
 * This script is loaded by: handle_upload_request()
 *
 * TODO:
 */

// Flash often fails to send cookies with the POST or upload, so we need to pass it in GET or POST instead
if ( is_ssl() && empty($_COOKIE[SECURE_AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) ){ $_COOKIE[SECURE_AUTH_COOKIE] = $_REQUEST['auth_cookie'];}
elseif ( empty($_COOKIE[AUTH_COOKIE]) && !empty($_REQUEST['auth_cookie']) ){ $_COOKIE[AUTH_COOKIE] = $_REQUEST['auth_cookie'];}
if ( empty($_COOKIE[LOGGED_IN_COOKIE]) && !empty($_REQUEST['logged_in_cookie']) ) { $_COOKIE[LOGGED_IN_COOKIE] = $_REQUEST['logged_in_cookie'];}

if ( !current_user_can('upload_files') ){ wp_die(__('You do not have permission to upload files.')); }

header('Content-Type: text/plain; charset=' . get_option('blog_charset'));

$nonce = $_REQUEST['_wpnonce'];
if ( ! wp_verify_nonce( $nonce, 'abcfic_upload_nonce' ) ) { die( 'You do not have permission to upload files. -3' ); }

if ( !defined('ABCFIC_PLUGIN_DIR') ){ die('Collections not available. -4'); }
$collID = (isset($_REQUEST['collid']) ? (int) $_REQUEST['collid'] : 0);

include_once ( ABCFIC_PLUGIN_DIR . 'includes/class-upload.php' );
$upload = new ABCFIC_Upload($collID);
$upload->save_uploaded_image();
return 0;