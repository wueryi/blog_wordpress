<?php
global $salong;

function salong_kx_sift_array(){
    global $salong;
        $sift_field = explode(PHP_EOL,$salong['sift_field']);
    foreach( $sift_field as $fields ) {
      $field = explode('=', $fields );
      $field_arr[ $field[0] ] = $field[1]; 
    }
    $sift_array = array(
                'field'=> $field_arr
    );
    return $sift_array;
}


function salong_get_sift_array(){
    global $salong;
        $sift_price = explode(PHP_EOL,$salong['sift_price']);
    foreach( $sift_price as $prices ) {
      $price = explode('=', $prices );
      $price_arr[ $price[0] ] = $price[1]; 
    }
    
        $sift_paixu = explode(PHP_EOL,$salong['sift_paixu']);
    foreach( $sift_paixu as $paixus ) {
      $paixu = explode('=', $paixus );
      $paixu_arr[ $paixu[0] ] = $paixu[1]; 
    }
    
    $sift_array = array(
                'price'=> $price_arr,
                'paixu'=> $paixu_arr,
    );
    return $sift_array;
}

function salong_add_query_vars($public_query_vars) {
  $public_query_vars[] = 'price';
  $public_query_vars[] = 'paixu';
  return $public_query_vars;
}
add_action('query_vars', 'salong_add_query_vars');


add_action('pre_get_posts','salong_sift_posts_per_page');
function salong_sift_posts_per_page($query){
        if(is_category() && $query->is_main_query() && !is_admin()){
    $sift_array = salong_get_sift_array();         
    $price_keys = array_keys( $sift_array['price'] );     $paixu_keys = array_keys( $sift_array['paixu'] );     $relation = 0;         $sift_vars = array();
    $sift_vars['price'] = get_query_var('price');
    $sift_vars['paixu'] = get_query_var('paixu');
    $meta_query = array(
      'relation' => 'OR',
    );
            if( in_array( $sift_vars['price'], $price_keys ) ){
      $meta_query[] = array(
        'key'     =>'price',
        'value'   => $sift_vars['price'],
        'compare' =>'LIKE',
      );
      $relation++;
    }
        if( in_array( $sift_vars['paixu'], $paixu_keys ) ){
      $meta_query[] = array(
        'key'     =>'paixu',
        'value'   => $sift_vars['paixu'],
        'compare' =>'LIKE',
      );
      $relation++;
    }
    if($relation){
            if($relation==2){
        $meta_query['relation'] = 'AND';       }
      $query->set('meta_query',$meta_query);
    }
  }
}


