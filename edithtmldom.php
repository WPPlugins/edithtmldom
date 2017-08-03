<?php
/*
Plugin Name: edithtmldom
Plugin Script: edithtmldom.php
Plugin URI: http://ulmdesign.mediamaster.eu/edithtmldom/
Description: Get DOM url or file and remove or replace contents in few minutes 
Version: 1.0
License: GPL
Author: Francesco De Stefano
Author URI: http://www.mediamaster.eu/

=== RELEASE NOTES ===
2013-01-30 - v1.0 - first version
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// edithtmldom parameters 
include('simple_html_dom.php');
function edithtmldom($html)
{
	
	$url = get_option('eHTML_input_url');
	$tag = get_option('eHTML_input_tag');
	$replace = get_option('eHTML_input_replace');
    $html = file_get_html($url);
	//find tag, id or class html
	foreach($html->find($tag) as $edit)
//replace with
    $edit->innertext = $replace;
	return $html;
}
add_shortcode( 'modifycontents', 'edithtmldom' );
add_filter('the_content', 'do_shortcode', 'edithtmldom');
add_filter('widget_text', 'do_shortcode', 'edithtmldom');
add_filter('wp_list_pages', 'do_shortcode', 'edithtmldom');

// input variable
function eHTML_activate_set_default_options()
{
    add_option('eHTML_input_url', 'Insert url');
	add_option('eHTML_input_tag', 'Insert tag, tag#id or tag.class');
	add_option('eHTML_input_replace', 'Replace content that you desire');
	
}
 
register_activation_hook( __FILE__, 'eHTML_activate_set_default_options');

//save settings
function eHTML_register_options_group()
{
    register_setting('eHTML_options_group', 'eHTML_input_url');
	register_setting('eHTML_options_group', 'eHTML_input_tag');
	register_setting('eHTML_options_group', 'eHTML_input_replace');
}
add_action('admin_init', 'eHTML_register_options_group');

function e_update_html_options_form() 
{
	?>
	<style>
		.wp-editor-container {width:650px}
	</style>
	<div id="wrap">
		<div class="icon32" id="icon-options-general"></div><br>
		<h3>Modify content your file html or url</h3>
		<form method="post" action="options.php"><?php settings_fields('eHTML_options_group'); ?>
			<p><label for="eHTML_input_url">Type the url (ex: http://www.example.com) of the page that you want to modify:</label></p>
    		<p><input style="width: 300px" type="text" value="<?php echo get_option('eHTML_input_url'); ?>" id="eHTML_input_url"  name="eHTML_input_url"/></p>
			<p><label for="eHTML_input_tag">Choose the tag also with id or the class html that you want to replace (ex: div, div#example, div.example, h1, h1#example, h1.example):</label></p>
			<p><textarea cols="100px" rows="10px" id="eHTML_input_tag"  name="eHTML_input_tag"><?php echo get_option('eHTML_input_tag'); ?></textarea></p>
			<p></p><label for="eHTML_input_replace">Type code html that you want to see and to replace it with the previous or you may to leave blank. Enjoy!</label></p>
			<p><?php wp_editor(get_option('eHTML_input_replace'), 'eHTML_input_replace', $settings = array(editor_class => 'wp-editor-container')); ?></p>
			<p><input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Save Changes'); ?>"/></p>
		</form>
		<legend>To see the modified page insert this shortcode in your post, page or widget text: [modifycontents]</legend>
	</div>
	<?php
	
}
// custom admin menu
function edithtmldom_opt_page()
{
    add_menu_page('EditHtmlDom', 'EditHtmlDom', 'administrator', 'edithtml_dom-options-page', 'e_update_html_options_form');
	
}

add_action('admin_menu', 'edithtmldom_opt_page');
?>