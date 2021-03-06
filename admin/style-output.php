<?php	

$shortname = get_option('of_shortname'); 
$output = '';

// Variables for style values
$wpr_bg_color = get_option($shortname . '_wpr_bg_color');
$body_bg_color = get_option($shortname . '_body_bg_color');

$body_text = get_option($shortname . '_body_text');
$prim_clr = get_option($shortname . '_primary_font_color');
$sec_clr = get_option($shortname . '_secondary_font_color');
$menu_clr = get_option($shortname.'_menu_hover_color');

// Aligning credits and license. Need to add appropriate style to wpf-styles.php. Should be a loop, but this works:

$li_align = get_option($shortname . '_li_alignment');
//$cred_align = get_option($shortname . '_cred_alignment'); - to be implemented on update

if($li_align == "0") {
	$li_align = 'left';
} else if($li_align == "1") {
	$li_align = 'right';
} else if($li_align == "2"){
	 $li_align = 'center';
} 

/*if($cred_align == "0") {
	$cred_align = 'right';
} else if($cred_align == "1") {
	$cred_align = 'left';
} else if($cred_align == "2"){
	 $cred_align = 'center';
} - to be implemented on update */ 

// Output styles
if ($output <> '') {
	$output = "/* Custom Styling */\n\t" . $output;
}

// Pull Styles from Dynamic StylesSheet (Look in /css/ )
$wpf_coloroptions = STYLESHEETPATH . '/admin/css/wpf-styles.php'; if( is_file( $wpf_coloroptions ) ) 
require $wpf_coloroptions;

// Echo Optional Styles
echo $output;
	
// Function to test options output
function echo_test() {
	$shortname = get_option('of_shortname'); 
	$wpr_border = get_option($shortname . '_li_alignment');
	print_r ($wpr_border);
}	
//add_action('thematic_post', 'echo_test');

?>