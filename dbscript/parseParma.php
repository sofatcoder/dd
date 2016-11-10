<?php
require_once("../wp-load.php");
define('WP_USE_THEMES', false);
$replaceString  = array("localhost/multiWp/","localhost/multiWp/");
$replaceStringWith  = array("localhost/multiWp","localhost/multiWp");
$s3url = 's3apnlivemumbai.s3-website.ap-south-1.amazonaws.com/';
$s3urlPath = array("apnlive/uploads","IL/uploads");
$blogs  = $wpdb->get_col("select * from ".$table_prefix."blogs");
global $wpdb;
if(MULTISITE){
    for($i=0;$i<count($blogs);$i++){
        switch_to_blog($blogs[$i]);      
        replaceQuery($wpdb,$table_prefix,$replaceString[$i],$replaceStringWith[$i],$s3url,$s3urlPath[$i]);
     }
     echo "Done";
}else{
        replaceQuery($wpdb,$table_prefix,$replaceString[0],$replaceStringWith[0],$s3url,$s3urlPath[0]);
     echo "Done";
        
}
function replaceQuery($wpdb,$table_prefix,$replaceString,$replaceStringWith,$s3url,$s3urlPath){
        $update_post = $wpdb->get_results("UPDATE ".$table_prefix."posts SET post_content = REPLACE(post_content, '".$replaceString."', '".$replaceStringWith."')
                ,guid =REPLACE(guid, '".$replaceString."', '".$replaceStringWith."')");
        $update_post = $wpdb->get_results("UPDATE ".$table_prefix."post_meta SET meta_value = REPLACE(meta_value, '".$replaceString."', '".$replaceStringWith."')");
        $update_post = $wpdb->get_results("UPDATE ".$table_prefix."comments SET comment_content = REPLACE(comment_content, '".$replaceString."', '".$replaceStringWith."')");
        $update_post = $wpdb->get_results("UPDATE ".$table_prefix."commentmeta SET meta_value = REPLACE(meta_value, '".$replaceString."', '".$replaceStringWith."')");
        $update_post = $wpdb->get_results("UPDATE ".$table_prefix."options SET option_value = '".$s3url.$s3urlPath." ' WHERE option_name = 'upload_url_path'");
}
?>