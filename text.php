<?php
/*
Plugin Name: Vina Custom HTML Widget
Plugin URI: http://VinaThemes.biz
Description: This plugin allows you to create your own HTML Widget using a WYSIWYG editor.
Version: 1.0
Author: VinaThemes
Author URI: http://VinaThemes.biz
Author email: contact@vinathemes.biz
Demo URI: http://VinaDemo.biz
Forum URI: http://VinaForum.biz
License: GPLv3+
*/

//Defined global variables

if(!defined('VINA_TEXT_DIRECTORY'))     define('VINA_TEXT_DIRECTORY', dirname(__FILE__));
if(!defined('VINA_TEXT_INC_DIRECTORY')) define('VINA_TEXT_INC_DIRECTORY', VINA_TEXT_DIRECTORY . '/includes');
if(!defined('VINA_TEXT_URI'))           define('VINA_TEXT_URI', get_bloginfo('url') . '/wp-content/plugins/vina-text-widget');
if(!defined('VINA_TEXT_INC_URI'))       define('VINA_TEXT_INC_URI', VINA_TEXT_URI . '/includes');

if(!defined('TCVN_FUNCTIONS')) {
	include_once(VINA_TEXT_INC_DIRECTORY . '/functions.php');
	define('TCVN_FUNCTIONS', 1);
}

if(!defined('TCVN_FIELDS')) {
    include_once(VINA_TEXT_INC_DIRECTORY . '/fields.php');
    define('TCVN_FIELDS', 1);
}

class Text_Widget extends WP_Widget
{
	function Text_Widget()
	{
		$widget_ops = array(
			'classname'   => 'text_widget',
			'description' => 'This plugin allows you to create your own HTML Widget using a WYSIWYG editor.'
		);
		
		$control_ops = array('width' => 345,'heigth' => 400);
		$this->WP_Widget('text_widget', __('Vina Custom HTML Widget'), $widget_ops, $control_ops);
	}
	function form($instance)
	{
		$instance = wp_parse_args(
			(array) $instance, 
			array(
				'title' 	=> '', 
				'text' 		=> '',
				'author' 	=> '',
				'position' 	=> '',
				'style'		=> 'default',
			)
		);
		
		$title    = strip_tags($instance['title']);
		$author   = strip_tags($instance['author']);
		$text     = esc_textarea($instance['text']);
		$position = strip_tags($instance['position']);
		$style 	  = strip_tags($instance['style']);
		
		$styleArray = array(
			'default' 	=> 'Default',
			'quote_01' 	=> 'Quotes Style 01',
			'quote_02' 	=> 'Quotes Style 02',
			'messenger' => 'Messenger',
			'note_01'  	=> 'Note Style 01',
			'note_02' 	=> 'Note Style 02',
			'note_03' 	=> 'Note Style 03',
		);
		?>
      	<div id="vina-text" class="tcvn-plugins-container">
            <div id="tcvn-tabs-container">
                <ul id="tcvn-tabs">
                    <li class="active"><a href="#text"><?php _e('Text'); ?></a></li>
                    <li><a href="#display"><?php _e('Display'); ?></a></li>
                </ul>
            </div>
            <div id="tcvn-elements-container">
                <!-- Basic Block -->
                <div id="text" class="tcvn-telement" style="display: block;">
                	<p><?php echo eTextField($this, 'title', 'Title', $title) ?></p>
                    <p id="text-area" style="width: 330px;">
                 		<textarea id="<?php echo $this->get_field_id('text');?>"  name="<?php echo $this->get_field_name('text');?>"><?php echo $text; ?></textarea>
                    </p>
                    <p><?php echo eTextField($this, 'author', 'Author', $author); ?></p>
                    <p><?php echo eTextField($this, 'position', 'Position', $position); ?></p>
                </div>
                <!-- Advanced Block -->
                <div id="display" class="tcvn-telement">
                    <p><?php echo eSelectOption($this, 'style', 'Widget Style', $styleArray, $style); ?></p>
                </div>
            </div>
        </div>
		<script>
			jQuery(document).ready(function($){
				try{
					new nicEditor().panelInstance('<?php echo $this->get_field_id('text');?>');
				}catch(err){}
				
				$('.nicEdit-main').keyup(function(event){
					try{
						var text=$('.nicEdit-selected').html();
						$('#<?php echo $this->get_field_id('text');?>').html(text);
					}catch(err){}
				});
				
				$('.nicEdit-main').click(function(event){
					try{
						var text=$('.nicEdit-selected').html();
						$('#<?php echo $this->get_field_id('text');?>').html(text);
					}catch(err){}
				});
				
				var prefix = '#vina-text ';
				$(prefix + "li").click(function() {
					$(prefix + "li").removeClass('active');
					$(this).addClass("active");
					$(prefix + ".tcvn-telement").hide();
					var selectedTab = $(this).find("a").attr("href");
					$(prefix + selectedTab).show();
					return false;
				});	
			});
			</script>   
        <?php
	}
	
	
	function update($new_instance, $old_instance) 
	{
		return $new_instance;
	}
	
	
	function widget($agrs,$instance)
	{
		extract($agrs);
		$title    		= getConfigValue($instance, 'title',    '');
		$text     		= getConfigValue($instance, 'text',     '');
		$style    		= getConfigValue($instance, 'style',    '');
		$author   		= getConfigValue($instance, 'author',   '');
		$time     		= getConfigValue($instance, 'time',     ''); 
		$font     		= getConfigValue($instance, 'font',     '');
		$position 		= getConfigValue($instance, 'position', '');
		$currentDate 	= getConfigValue($instance, 'current', '');
		?>
        
        <?php echo !empty($title) ? $before_title . $title . $after_title : ''; ?>
        
        <div id="vina-style" class="<?php echo $style; ?>">
        	<div id="vina-text-widget">
				<?php echo $text; ?>
            </div>
            
            <!-- Author Block -->
			<?php if(!empty($author) || !empty($position)) : ?>
            <div id="vina-text-footer">
                <?php if(!empty($author) || !empty($position)) : ?>
                <div class="vina-text-author">
                	<?php echo !empty($author) ? "<span>{$author}</span>" : ''; ?><?php echo !empty($position) ? ", ". $position: ''; ?>
                </div>
                <?php endif; ?>
                <div style="clear:both"></div>
            </div>
            <?php endif; ?>
        </div>
        <div id="tcvn-copyright">
        	<a href="http://vinathemes.biz" title="Free download Wordpress Themes, Wordpress Plugins - VinaThemes.biz">Free download Wordpress Themes, Wordpress Plugins - VinaThemes.biz</a>
        </div>
		<?php
	}
}
add_action('widgets_init', create_function('', 'return register_widget("Text_Widget");'));
wp_enqueue_style('vina-scroller-admin-css', VINA_TEXT_INC_URI . '/admin/css/style.css', '', '1.0', 'screen' );
wp_enqueue_script('vina-tooltips', 			VINA_TEXT_INC_URI . '/admin/js/jquery.simpletip-1.3.1.js', 'jquery', '1.0', true);

wp_enqueue_style('vina-display-css', VINA_TEXT_INC_URI . '/css/style.css', '', '1.0', 'screen');
wp_enqueue_script('vina-editor', VINA_TEXT_INC_URI . '/js/nicEdit.js', 'jquery', '1.0', false);
?>