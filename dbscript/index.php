<?php
header('Content-type: text/html; charset=utf-8');
require_once("../wp-load.php");
    
      header("Content-type: text/csv");
      header("Content-Disposition: attachment; filename=file.csv");
      header("Pragma: no-cache");
      header("Expires: 0");
if(MULTISITE){
    $blogs  = $wpdb->get_col("select * from ".$table_prefix."blogs");
    for($i=0;$i<1;$i++){
    $count =1;
//        switch_to_blog( $blogs[$i] );
        $getCategoryOrTag = get_categories(array(
            'orderby' => 'name',
            'order'   => 'ASC'
        ));
        fetchPostNumber($getCategoryOrTag,$table_prefix,"Category".$blogs[$i],$blogs[$i]);            
        $getCategoryOrTag = get_tags(array(
            'orderby' => 'name',
            'order'   => 'ASC'
        ));
        fetchPostNumber($getCategoryOrTag,$table_prefix,"Tag".$blogs[$i],$blogs[$i]);

    }
}else{
    $getCategoryOrTag = get_categories(array(
        'orderby' => 'name',
        'order'   => 'ASC'
    ));
    fetchPostNumber($getCategoryOrTag,$table_prefix,"Category by post count",1);    
    $getCategoryOrTag = get_tags(array(
       'orderby' => 'name',
       'order'   => 'ASC'
    ));
    fetchPostNumber($getCategoryOrTag,$table_prefix,"Tag by post count",1);    
}

function fetchPostNumber($getCategory,$table_prefix,$heading,$blogs){
$cateName = array();
$postCount = array();
$file = fopen("php://output","w");
fputcsv($file, array('S no', $heading.'Name', 'Postno'));    
global $wpdb;
//echo "<table style='width:100%;text-align:center' id='#myTable'><tr><th>No</th><th>Name</th><th>Number of Post</th></tr>";
//echo "<caption>".$heading."</caption>";
$blogs = $blogs == 1 ? '':$blogs."_";
foreach($getCategory as $categoryName){
    //echo "<tr><td>".$count."</td><td>".utf8_encode($categoryName->slug)."</td>";
    $myrows = $wpdb->get_col("select count(*) from ".$table_prefix."term_relationships where term_taxonomy_id =".$categoryName->term_id);
    //echo "<td>".$myrows[0]."</td></tr>";
    $count++;
    fputcsv($file, array($count, $categoryName->slug, $myrows[0]));    
   
    }
fclose($file);
}
?>
