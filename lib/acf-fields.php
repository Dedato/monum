<?php
// Auto-populate section slug with section title
function my_acf_update_value( $post_id ) {
  $repeater  = 'page_sections'; // the field name of the repeater field
  $subfield1 = 'page_section_title'; // the field I want to get
  $subfield2 = 'page_section_slug'; // the field I want to set
  // get the number of rows in the repeater
  $count = intval(get_post_meta($post_id, $repeater, true));
  // loop through the rows
  for ($i=0; $i<$count; $i++) {
    $get_field = $repeater.'_'.$i.'_'.$subfield1;
    $set_field = $repeater.'_'.$i.'_'.$subfield2;
    $value     = get_post_meta($post_id, $get_field, true);
    $new_value = sanitize_title($value);
    update_post_meta($post_id, $set_field, $new_value);
  }
}
add_action('acf/save_post', 'my_acf_update_value', 20);