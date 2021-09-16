<?php
/*
Plugin Name: Tooltips
Plugin URI:  https://tooltips.org/features-of-wordpress-tooltips-plugin/
Description: Wordpress Tooltips,You can add text,image,link,video,radio in tooltips, add tooltips in gallery. More amazing features? Do you want to customize a beautiful style for your tooltips? One Minute, Check <a href='https://tooltips.org/features-of-wordpress-tooltips-plugin/' target='_blank'> Features of WordPress Tooltips Pro</a>.
Version: 7.3.7
Author: Tomas Zhu | <a href='https://tooltips.org/wordpress-tooltip-plugin/wordpress-tooltip-plugin-document/' target='_blank'>Docs</a> | <a href='https://tooltips.org/faq/' target='_blank'>FAQ</a> | <a href='https://tooltips.org/contact-us' target='_blank'>Premium Support</a> 
Author URI: https://tooltips.org/wordpress-tooltip-plugin/wordpress-tooltips-demo/
Text Domain: wordpress-tooltips
License: GPLv3 or later
*/
/*  Copyright 2011 - 2021 Tomas Zhu
 This program comes with ABSOLUTELY NO WARRANTY;
 https://www.gnu.org/licenses/gpl-3.0.html
 https://www.gnu.org/licenses/quick-guide-gplv3.html
 */

if (!defined('ABSPATH'))
{
	exit;
}

define('TOOLTIPS_ADMIN_PATH', plugin_dir_path(__FILE__).'admin'.'/');
define('TOOLTIPS_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('TOOLTIPS_ADDONS_PATH', plugin_dir_path(__FILE__).'addons'.'/');
define('TOOLTIP_PLUGIN_URL', plugin_dir_url( __FILE__ ));
require_once("tooltipsfunctions.php");
require_once("rules/ttsimport.php");
require_once(TOOLTIPS_ADDONS_PATH."addons.php");
require_once('rules/disabletooltipsinglossarypage.php');
require_once('rules/detectmobile.php');

function add_tooltips_post_type() {
	global $wp_rewrite;
	$catlabels = array(
			'name'                          => 'Categories',
			'singular_name'                 => 'Tooltips Categories',
			'all_items'                     => 'All Tooltips',
			'parent_item'                   => 'Parent Tooltips',
			'edit_item'                     => 'Edit Tooltips',
			'update_item'                   => 'Update Tooltips',
			'add_new_item'                  => 'Add New Tooltips',
			'new_item_name'                 => 'New Tooltips',
	);

	$args = array(
			'label'                         => 'Categories',
			'labels'                        => $catlabels,
			'public'                        => true,
			'hierarchical'                  => true,
			'show_ui'                       => true,
			'show_in_nav_menus'             => true,
			'args'                          => array( 'orderby' => 'term_order' ),
			'rewrite'                       => array( 'slug' => 'tooltips_categories', 'with_front' => false ),
			'query_var'                     => true
	);

	register_taxonomy( 'tooltips_categories', 'tooltips', $args );


	$labels = array(
			'name' => __('Tooltips', 'wordpress-tooltips'),
			'singular_name' => __('Tooltip', 'wordpress-tooltips'),
			'add_new' => __('Add New', 'wordpress-tooltips'),
			'add_new_item' => __('Add New Tooltip', 'wordpress-tooltips'),
			'edit_item' => __('Edit Tooltip', 'wordpress-tooltips'),
			'new_item' => __('New Tooltip', 'wordpress-tooltips'),
			'all_items' => __('All Tooltips', 'wordpress-tooltips'),
			'view_item' => __('View Tooltip', 'wordpress-tooltips'),
			'search_items' => __('Search Tooltip', 'wordpress-tooltips'),
			'not_found' =>  __('No Tooltip found', 'wordpress-tooltips'),
			'not_found_in_trash' => __('No Tooltip found in Trash', 'wordpress-tooltips'),
			'menu_name' => __('Tooltips', 'wordpress-tooltips')
	);
 // 7.2.1
	$enableGlossarySearchable =	get_option("enableGlossarySearchable");
	$enableGlossarySearchableFlag = false;
	if (empty($enableGlossarySearchable))
	{
		$enableGlossarySearchableFlag = false;
	}
	elseif ($enableGlossarySearchable == 'yes')
	{
		$enableGlossarySearchableFlag = false;
	}
	elseif ($enableGlossarySearchable == 'no')
	{
		$enableGlossarySearchableFlag = true;
	}
// end 7.2.1	
	
	$enabGlossaryIndexPage =  get_option("enabGlossaryIndexPage");
	if (empty($enabGlossaryIndexPage))
	{
		$enabGlossaryIndexPage = 'YES';
	}

	if ($enabGlossaryIndexPage == 'YES')
	{
		$hasGlossaryIndex = true;
		$tooltipsGlossaryIndexPage = get_option('tooltipsGlossaryIndexPage');
		if (empty($tooltipsGlossaryIndexPage))
		{
			$glossarySlug =  'glossary';
		}
		else
		{
			$glossaryPost = get_post($tooltipsGlossaryIndexPage);
			if ( empty($glossaryPost->ID) )
			{
				$glossarySlug =  'glossary';
			}
			else
			{
				$glossarySlug = $glossaryPost->post_name;
			}
		}


		$hasGlossaryIndexRewrite =  array('slug' => $glossarySlug, 'with_front' => true, 'feeds' => true, 'pages' => true);


	}
	else
	{
		$hasGlossaryIndex = false;
		$hasGlossaryIndexRewrite =  false;
	}

	$args = array(
			'labels' => $labels,
			'public' => $hasGlossaryIndex,
			'show_ui' => true,
			'show_in_menu' => true,
			'_builtin' =>  false,
			'query_var' => "tooltips",
			'rewrite'	=>  $hasGlossaryIndexRewrite,
			'capability_type' => 'post',
			'has_archive' => $hasGlossaryIndex,
			'hierarchical' => false,
			'menu_position' => null,
			'exclude_from_search' => $enableGlossarySearchableFlag, // 7.2.1
			'supports' => array( 'title', 'editor','author','custom-fields','thumbnail' )
	);

	register_post_type('tooltips', $args);
	$wp_rewrite->flush_rules();
}
add_action( 'init', 'add_tooltips_post_type' );

function tooltipsMenu()
{
	add_menu_page(__('Tooltips','wordpress-tooltips'), __('Tooltips','wordpress-tooltips'), 'manage_options', 'tooltipsfunctions.php','editTooltips');
	add_submenu_page('tooltipsfunctions.php',__('Edit Tooltips','wordpress-tooltips'), __('Edit Tooltips','wordpress-tooltips'),'manage_options', 'tooltipsfunctions.php','editTooltips');
}

add_action('admin_menu', 'tooltips_menu');

function tooltips_menu() {
	add_submenu_page('edit.php?post_type=tooltips',__('Global Settings','wordpress-tooltips'), __('Global Settings','wordpress-tooltips'),'manage_options', 'tooltipglobalsettings','tooltipGlobalSettings');
	add_submenu_page('edit.php?post_type=tooltips',__('Glossary Setttings','wordpress-tooltips'), __('Glossary Settings','wordpress-tooltips'),"manage_options", 'glossarysettingsfree','tooltipFreeGlossarySettings');
	add_submenu_page("edit.php?post_type=tooltips", __("Import Tooltips", "wordpress-tooltips"), __("Import Tooltips", "wordpress-tooltips"), "manage_options", "tooltipsimport","tooltipsImportFree");	
	add_submenu_page("edit.php?post_type=tooltips", __("Addons", "wordpress-tooltips"), __("Addons", "wordpress-tooltips"), "manage_options", "tooltipsfreeaddonmanager","tooltipsfreeaddonmanager");
	add_submenu_page('edit.php?post_type=tooltips',__('Knowledge Base','wordpress-tooltips'), __('Knowledge Base','wordpress-tooltips'),"manage_options", 'tooltipsfaq','tooltipFreeFAQ');

}


function tooltipsfreeaddonmanager()
{
	require_once(TOOLTIPS_ADDONS_PATH."addonspanel.php");
}


function tooltipFreeFAQ()
{
	require_once(TOOLTIPS_ADMIN_PATH."howto.php");
}

function tooltipFreeGlossarySettings()
{
	require_once(TOOLTIPS_ADMIN_PATH."glossaryglobalsettings.php");
	glossarysettingsfree();
}

add_action('admin_head', 'tooltips_admin_css');
function tooltips_admin_css()
{
	wp_enqueue_style( 'ionrangeslidercss', plugin_dir_url( __FILE__ ).'css/ionrangeslider/ion.rangeSlider.css' );

	wp_register_script('ionrangesliderjs', plugin_dir_url( __FILE__ ).'js/ionrangeslider/ion.rangeSlider.min.js', array('jquery') );
	wp_enqueue_script( 'ionrangesliderjs' );
}

/*  7.2.3 moved from tooltips.php inline codes to css to speed up page load speed */
/*
function tooltipsAdminHead()
{
	?>
<style type="text/css">
span.question, span.questionimage, span.questionexcerpt, span.questiontags,
span.questionsinglecat, span.hiddenimageinglossary, span.questiontooltippopupanimation,span.questionstyle
, span.questionshowtooltipclosebutton, span.questionzindex , span.questionremoveontag
, span.questionindexforglossary, span.questionglossaryindexpageselect
{
	cursor: pointer;
	display: inline-block;
	line-height: 14px;
	width: 14px;
	height: 14px;
	border-radius: 7px;
	-webkit-border-radius: 7px;
	-moz-border-radius: 7px;
	background: #5893ae;
	color: #fff;
	text-align: center;
	position: relative;
	font-size: 10px;
	font-weight: bold;
}

span.question:hover,span.questionimage:hover, span.questiontags:hover,span.questionsinglecat:hover,span.hiddenimageinglossary:hover, span.questiontooltippopupanimation:hover,span.questionstyle:hover
, span.questionshowtooltipclosebutton:hover, span.questionzindex:hover, span.questionremoveontag:hover, span.questionindexforglossary:hover 
,span.questionglossaryindexpageselect:hover
{
	background-color: #21759b;
}


div.tooltip
{
	text-align: left;
	left: 25px;
	background: #21759b;
	color: #fff;
	position: absolute;
	z-index: 1000000;
	border-radius: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	width: 600px;
	top: -30px;	
}

div.tooltip:before
{
	border-color: transparent #21759b transparent transparent;
	border-right: 6px solid #21759b;
	border-style: solid;
	border-width: 6px 6px 6px 0px;
	content: "";
	display: block;
	height: 0;
	width: 0;
	line-height: 0;
	position: absolute;
	top: 40%;
	left: -6px;
}

div.tooltip p 
{
	margin: 10px;
	line-height: 13px;
	font-size: 11px;
	color: #eee;
}
// glossary 
div.glossary7 {
  text-align: left;
  left: 25px;
  background: #21759b;
  color: #fff;
  position: absolute;
  z-index: 1000000;
  width: 400px;
  border-radius: 5px;
  -webkit-border-radius:5px;
  -moz-border-radius:5px;
top: -50px;
}


div.glossary4, div.glossary5, div.glossary6, div.glossary8, div.glossary9, div.glossary10 , div.glossary11 , div.glossary12 , div.glossary13
 , div.glossary14 , div.glossary15, div.glossary16, div.glossary17, div.glossary18, div.glossary19, div.glossary20, div.glossary21, div.glossary22
 , div.glossary23, div.glossary24, div.glossary25, div.glossary26, div.glossary27, div.glossary28, div.glossary29
 , div.glossarydisabletooltipsforglossary, div.questionglossarysearchable
{
  text-align: left;
  left: 25px;
  background: #21759b;
  color: #fff;
  position: absolute;
  z-index: 1000000;
  width: 400px;
  border-radius: 5px;
  -webkit-border-radius:5px;
  -moz-border-radius:5px;
top: -20px;
}

div.glossary:before, .glossary1:before, .glossary3:before, .glossary4:before,.glossary5:before,.glossary6:before,.glossary7:before,.glossary8:before 
,.glossary9:before,.glossary10:before,.glossary11:before,.glossary12:before,.glossary13:before,.glossary14:before,.glossary15:before
,.glossary16:before ,.glossary17:before ,.glossary18:before ,.glossary19:before ,.glossary20:before ,.glossary21:before,.glossary22:before
,.glossary23:before ,.glossary24:before ,.glossary25:before ,.glossary26:before, .glossary27:before, .glossary28:before
, .glossary29:before, .glossarydisabletooltipsforglossary:before
{
  border-color: transparent #21759b transparent transparent;
  border-right: 6px solid #21759b;
  border-style: solid;
  border-width: 6px 6px 6px 0px;
  content: "";
  display: block;
  height: 0;
  width: 0;
  line-height: 0;
  position: absolute;
  top: 40%;
  left: -6px;
}


div.glossary p, .glossary1 p, .glossary3 p, .glossary4 p,.glossary5 p,.glossary6 p,.glossary7 p,.glossary8 p,.glossary9 p
,.glossary10 p,.glossary11 p,.glossary12 p,.glossary13 p,.glossary14 p,.glossary15 p ,.glossary16 p ,.glossary17 p 
,.glossary18 p  ,.glossary19 p ,.glossary20 p  ,.glossary21 p ,.glossary22 p ,.glossary23 p ,.glossary24 p ,.glossary25 p
,.glossary26 p,.glossary27 p,.glossary28 p,.glossary29 p, .glossarydisabletooltipsforglossary p, .questionglossarysearchable p
{
  margin: 10px;
 line-height:16px;
 font-size:11px;
 color:#eee; 
}

</style>										
<?php
}
*/

$tooltipHookPriorityValue = get_option("tooltipHookPriorityValue");
if (empty($tooltipHookPriorityValue))
{
	$tooltipHookPriorityValue = 20000;
}

// removed from 7.2.3 add_action('admin_head', 'tooltipsAdminHead');

$disabletooltipentiresite = get_option('disabletooltipentiresite');
if ('NO' == $disabletooltipentiresite)
{
	return;
}


add_action( 'wp_enqueue_scripts', 'tooltips_loader_scripts' );
function tooltips_loader_scripts()
{
	
	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		//
	}
	else
	{
		wp_register_style( 'qtip2css', plugin_dir_url( __FILE__ ).'js/qtip2/jquery.qtip.min.css');
		wp_enqueue_style( 'qtip2css' );		
	}
	//end 6.9.3
	
	/*
	 * old code works good
	wp_register_style( 'qtip2css', plugin_dir_url( __FILE__ ).'js/qtip2/jquery.qtip.min.css');
	wp_enqueue_style( 'qtip2css' );
	*/
	
	//wp_register_style( 'directorycss', plugin_dir_url( __FILE__ ).'js/jdirectory/directory.css');
	wp_register_style( 'directorycss', plugin_dir_url( __FILE__ ).'js/jdirectory/directory.min.css');
	wp_enqueue_style( 'directorycss' );
	
	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		//
	}
	else
	{
		wp_register_script( 'qtip2js', plugin_dir_url( __FILE__ ).'js/qtip2/jquery.qtip.min.js', array('jquery'));
		wp_enqueue_script( 'qtip2js' );
	}
	//end 6.9.3
	/*
	 * old code works good	
	wp_register_script( 'qtip2js', plugin_dir_url( __FILE__ ).'js/qtip2/jquery.qtip.min.js', array('jquery'));
	wp_enqueue_script( 'qtip2js' );
	*/
	
	//wp_register_script( 'directoryjs', plugin_dir_url( __FILE__ ).'js/jdirectory/jquery.directory.js', array('jquery'));
	wp_register_script( 'directoryjs', plugin_dir_url( __FILE__ ).'js/jdirectory/jquery.directory.min.js', array('jquery'));
	wp_enqueue_script( 'directoryjs' );	


}

/*
add_action('admin_head', 'tooltips_admin_css');
function tooltips_admin_css()
{
	wp_enqueue_style( 'ionrangeslidercss', plugin_dir_url( __FILE__ ).'css/ionrangeslider/ion.rangeSlider.css' );	
	
	wp_register_script('ionrangesliderjs', plugin_dir_url( __FILE__ ).'js/ionrangeslider/ion.rangeSlider.min.js', array('jquery') );
	wp_enqueue_script( 'ionrangesliderjs' );
}
*/

//require_once("tooltipsfunctions.php");
function tooltipsHead()
{
	
	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return '';
	}
	//end 6.9.3
	
	$m_pluginURL = get_option('siteurl').'/wp-content/plugins';
	$showImageinglossary = get_option("showImageinglossary");
	$toolstipsAnimationClass = get_option("toolstipsAnimationClass");
	$tooltipZindexValue = get_option("tooltipZindexValue");
	if (empty($tooltipZindexValue))
	{
		$tooltipZindexValue = 15001;
	}
	if ((!(empty($tooltipZindexValue))) && ($tooltipZindexValue <>15001))
	{
		?>
		<style type="text/css">
		.qtip
		{
			z-index:<?php echo $tooltipZindexValue; ?> !important;
		}
		</style>
		<?php						
	}
	
	if ($showImageinglossary == 'NO')
	{
		?>
		<style type="text/css">
		.tooltips_list img
		{
			display:none !important;
		}
		.tooltips_list .wp-caption-text
		{
			display:none !important;
		}
		</style>
		<?php 		
	}
	?>
	<style type="text/css">
	.tooltips_table .tooltipsall
	{
		border-bottom:none !important;
	}
	.tooltips_table span {
    color: inherit !important;
	}
	.qtip-content .tooltipsall
	{
		border-bottom:none !important;
		color: inherit !important;
	}
	
	<?php //!!! 6.9.3 ?>
	.tooltipsincontent
	{
		border-bottom:2px dotted #888;	
	}

	.tooltipsPopupCreditLink a
	{
		color:gray;
	}	
	</style>
	<?php
	$selectedTooltipStyle = get_option("selectedTooltipStyle");
	if (empty($selectedTooltipStyle))
	{
		$selectedTooltipStyle = 'qtip-dark';
	}	
?>
 	<script type="text/javascript">	
	if(typeof jQuery=='undefined')
	{
		document.write('<'+'script src="<?php echo $m_pluginURL; ?>/<?php echo  '/wordpress-tooltips'; ?>/js/qtip/jquery.js" type="text/javascript"></'+'script>');
	}
	</script>
	<script type="text/javascript">

	function toolTips(whichID,theTipContent)
	{
			jQuery(whichID).qtip
			(
				{
					content:
					{
						text:theTipContent,
						<?php
							$showToolstipsCloseButtonSelect = get_option("showToolstipsCloseButtonSelect");
							if ((!(empty($showToolstipsCloseButtonSelect))) && ($showToolstipsCloseButtonSelect == 'yes'))
							{
								echo "button:'Close'";
							}
						?>						
					},
   					style:
   					{
   						classes:' <?php echo $selectedTooltipStyle; ?> wordpress-tooltip-free qtip-rounded qtip-shadow <?php echo $toolstipsAnimationClass; ?>'
    				},
    				position:
    				{
    					viewport: jQuery(window),
    					my: 'bottom center',
    					at: 'top center'
    				},
					show:'mouseover',
					hide: { fixed: true, delay: 200 }
				}
			)
	}
</script>
	
<?php
}



function showTooltips($content)
{
	global $table_prefix,$wpdb,$post;

	do_action('action_before_showtooltips', $content);
	remove_filter('the_title', 'wptexturize');
	$content = apply_filters( 'filter_before_showtooltips',  $content);
	
	
	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return $content;
	}
	//end 6.9.3
	
	$curent_post = get_post($post);
	
	if (empty($curent_post->post_content))
	{
		$curent_content = '';
	}
	else
	{
		$curent_content = $curent_post->post_content;
	}
	
	

	
	$m_result = tooltips_get_option('tooltipsarray','post_title', 'DESC', 'LENGTH');
	
	$m_keyword_result = '';
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
			$tooltip_post_id = $m_single['post_id'];
			$tooltip_unique_id = $m_single['unique_id'];
			
			$get_post_meta_value_for_this_page = get_post_meta($tooltip_post_id, 'toolstipssynonyms', true);
			$get_post_meta_value_for_this_page = trim($get_post_meta_value_for_this_page);
			
			$tooltsip_synonyms = false;
			if (!(empty($get_post_meta_value_for_this_page)))
			{
				$tooltsip_synonyms = explode('|', $get_post_meta_value_for_this_page);
			}
				
				
			if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
			{
					
			}
			else
			{
				$tooltsip_synonyms = array();
				$tooltsip_synonyms[] = $m_single['keyword'];
					
			}
				
			if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
			{
				$tooltsip_synonyms[] = $m_single['keyword'];
				$tooltsip_synonyms = array_unique($tooltsip_synonyms);
			
				foreach ($tooltsip_synonyms as $tooltsip_synonyms_single)
				{
					$m_keyword = $tooltsip_synonyms_single;
						
						
					if (stripos($curent_content,$m_keyword) === false)
					{
							
					}
					else
					{
						$m_keyword_result .= '<script type="text/javascript">';
						$m_content = $m_single['content'];

						$m_content = do_shortcode($m_content);
						
						$m_content = preg_quote($m_content,'/');
						$m_content = str_ireplace('\\','',$m_content);
						$m_content = str_ireplace("'","\'",$m_content);
						$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
						$m_content = preg_replace('|\r|', '', $m_content);
						$m_content = preg_replace('|\n|', '<br/>', $m_content);
						
						if (!(empty($m_content)))
						{
							
							//!!!start
							$tooltipsPopupCreditLink =	'';
							$enabletooltipsPopupCreditLinkInPopupWindow = get_option("enabletooltipsPopupCreditLinkInPopupWindow");
							
							
							if ($enabletooltipsPopupCreditLinkInPopupWindow == 'YES')
							{
								$tooltipsPopupCreditLink =	'<div class="tooltipsPopupCreditLink" style="float:left;margin-top:4px;margin-left:2px;"><a href="https://tooltips.org/contact-us" target="_blank">'."Tooltip Support"."</a></div>";
							}
							else
							{
								$tooltipsPopupCreditLink =	'';
							}
							
							$tooltiplinkintooltipboxstart = '<div class="tooltiplinkintooltipbox">';
							$tooltiplinkintooltipboxclearfloat = '<div style="clear:both"></div>';
							$tooltiplinkintooltipboxend = "</div>";
							
							
							if ($enabletooltipsPopupCreditLinkInPopupWindow == 'YES')
							{
								$m_content = $m_content.$tooltiplinkintooltipboxstart.$tooltipsPopupCreditLink.$tooltiplinkintooltipboxclearfloat.$tooltiplinkintooltipboxend;
							}
							//!!!end
							
							
							$m_title_in_tooltip = $m_keyword;
							$m_keyword_result .= '//##'. " toolTips('.classtoolTips$m_keyword_id','$m_content'); ".'##]]';
						}
						$m_keyword_result .= '</script>';
					}
				}
			}
			$m_keyword_id++;
		}
	}
	
	$content = $content.$m_keyword_result;
	do_action('action_after_showtooltips', $content);
	$content = apply_filters( 'filter_after_showtooltips',  $content);
	add_filter('the_title', 'wptexturize');
	return $content;
}

function showTooltipsInTag($content)
{
	global $table_prefix,$wpdb,$post;

	do_action('action_before_showtooltipsintag', $content);
	$content = apply_filters( 'filter_before_showtooltipsintag',  $content);
	
	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return $content;
	}
	//end 6.9.3
	
	$curent_content = $content;

	$m_result = tooltips_get_option('tooltipsarray','post_title', 'DESC', 'LENGTH');
	
	$m_keyword_result = '';
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
			if (stripos($curent_content,$m_single['keyword']) === false)
			{
				
			}
			else 
			{			
				$m_keyword_result .= '<script type="text/javascript">';
				$m_content = $m_single['content'];
				$m_content = preg_quote($m_content,'/');
				$m_content = str_ireplace('\\','',$m_content);
				$m_content = str_ireplace("'","\'",$m_content);
				$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
				$m_content = preg_replace('|\r|', '', $m_content);
				$m_content = preg_replace('|\n|', '<br/>', $m_content);
				
				
				if (!(empty($m_content)))
				{
					$m_keyword_result .= '//##'. " toolTips('.classtoolTips$m_keyword_id','$m_content'); ".'##]]';
				}
				$m_keyword_result .= '</script>';
			}
			$m_keyword_id++;
		}
	}
	$content = $content.$m_keyword_result;

	do_action('action_after_showtooltipsintag', $content);
	$content = apply_filters( 'filter_after_showtooltipsintag',  $content);

	return $content;
}


function showTooltipsInTitle($content)
{
	global $table_prefix,$wpdb,$post;

	do_action('action_before_showtooltipsintag', $content);
	$content = apply_filters( 'filter_before_showtooltipsintag',  $content);

	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return $content;
	}
	//end 6.9.3

	$curent_content = $content;

	$m_result = tooltips_get_option('tooltipsarray','post_title', 'DESC', 'LENGTH');

	$m_keyword_result = '';
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
			if (stripos($curent_content,$m_single['keyword']) === false)
			{

			}
			else
			{
				$m_keyword_result .= '<script type="text/javascript">';
				$m_content = $m_single['content'];
				$m_content = preg_quote($m_content,'/');
				$m_content = str_ireplace('\\','',$m_content);
				$m_content = str_ireplace("'","\'",$m_content);
				$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
				$m_content = preg_replace('|\r|', '', $m_content);
				$m_content = preg_replace('|\n|', '<br/>', $m_content);


				if (!(empty($m_content)))
				{
					$m_keyword_result .= '//##'. " toolTips('.classtoolTips$m_keyword_id','$m_content'); ".'##]]';
				}
				$m_keyword_result .= '</script>';
			}
			$m_keyword_id++;
		}
	}
	$content = $content.$m_keyword_result;

	do_action('action_after_showtooltipsintag', $content);
	$content = apply_filters( 'filter_after_showtooltipsintag',  $content);

	return $content;
}

function tooltipsInContent($content)
{
	global $table_prefix,$wpdb,$post;
	
	do_action('action_before_tooltipsincontent', $content);
	$content = apply_filters( 'filter_before_tooltipsincontent',  $content);

	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		//return $content;
	}
	//end 6.9.3
	
	//!!!start
	$post_id = 0;
	if (is_object($post))
	{
		$post_id = $post->ID;
	}
	//!!!end
	
	$disableInHomePage = get_option("disableInHomePage");
	
	if ($disableInHomePage == 'NO')
	{
		if (is_home())
		{
			return $content;
		}		
	}
	
	$showOnlyInSingleCategory = get_option("showOnlyInSingleCategory");
	
	if ($showOnlyInSingleCategory != 0)
	{
		
		$post_cats = wp_get_post_categories($post->ID);
		if (in_array($showOnlyInSingleCategory,$post_cats))
		{
			
		}
		else 
		{
			return $content;
		}
	}	
	
	//!!!start
	if ((isset($post->ID)) && (!(empty($post->ID))))
	{
		$disableTooltipsForGlossaryPage = disableTooltipsFreeForGlossaryPage($post_id);
		if ($disableTooltipsForGlossaryPage == true)
		{
			return $content;
		}		
	}
	//!!!end
	
	$onlyFirstKeyword = get_option("onlyFirstKeyword");
	if 	($onlyFirstKeyword == false)
	{
		$onlyFirstKeyword = 'all';
	}

	$m_result = tooltips_get_option('tooltipsarray','post_title', 'DESC', 'LENGTH');
	
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
		
			$m_keyword = $m_single['keyword'];
			$m_content = $m_single['content'];

			$tooltip_post_id = $m_single['post_id'];
			$tooltip_unique_id = $m_single['unique_id'];

			$get_post_meta_value_for_this_page = get_post_meta($tooltip_post_id, 'toolstipssynonyms', true);
			$get_post_meta_value_for_this_page = trim($get_post_meta_value_for_this_page);
				
			$tooltsip_synonyms = false;
			if (!(empty($get_post_meta_value_for_this_page)))
			{
				$tooltsip_synonyms = explode('|', $get_post_meta_value_for_this_page);
			}

			if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
			{
			
			}
			else
			{
				$tooltsip_synonyms = array();
				$tooltsip_synonyms[] = $m_keyword;
			
			}
				
			if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
			{
				$tooltsip_synonyms[] = $m_keyword;
				$tooltsip_synonyms = array_unique($tooltsip_synonyms);
				
				foreach ($tooltsip_synonyms as $tooltsip_synonyms_single)
				{
					$m_keyword = $tooltsip_synonyms_single;
					$m_keyword = trim($m_keyword);
					//!!! $m_replace = "<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">$m_keyword</span>";
					//6.9.3
					$m_replace = "<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">$m_keyword</span>";
					
					if (stripos($content,$m_keyword) === false)
					{
					
					}
					else
					{
						$m_keyword = str_replace('/','\/',$m_keyword); //!!! 6.2.9
						
						if ($onlyFirstKeyword == 'all')
						{
							$m_keyword = preg_quote($m_keyword,'/');
							//!!!$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">"."\\2"."</span>"."\\3",$content);
							//6.9.3
							$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content);
						}
							
						if ($onlyFirstKeyword == 'first')
						{
							$m_keyword = preg_quote($m_keyword,'/');
							//!!!$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">"."\\2"."</span>"."\\3",$content,1);
							//6.9.3
							$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content,1);
						}
						
						if ($content1 == $content)
						{
							$content1 = " x98 ".$content." x98 ";
							if ($onlyFirstKeyword == 'all')
							{
								$m_keyword = preg_quote($m_keyword,'/');
								//!!! $content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">"."\\2"."</span>"."\\3",$content1);
								//6.9.3
								$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content1);
							}
								
							if ($onlyFirstKeyword == 'first')
							{
								$m_keyword = preg_quote($m_keyword,'/');
								//!!! $content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">"."\\2"."</span>"."\\3",$content1,1);
								//6.9.3
								$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content1,1);
							}
						
							$content1 = trim($content1," x98 ");
						}
						$m_check = "<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">";
						if (stripos($content1, $m_check.$m_check) === false)
						{
							$content = $content1;
						}
						else
						{
							$content = $content;
						}
						
					}
				}
				//!!! old $content = str_replace($m_single['keyword'], $tooltip_unique_id, $content);
				//!!! 6.2.9 $content = preg_replace("/"."(".$m_single['keyword'].")(?![^@@@@]*[####])/s",'@@@@'.$tooltip_unique_id.'####'."\\2",$content); //!!!new
				
				$m_single_keyword = str_replace('/','\/',$m_single['keyword']); //!!! 6.2.9
				$content = preg_replace("/"."(".$m_single_keyword.")(?![^@@@@]*[####])/s",'@@@@'.$tooltip_unique_id.'####'."\\2",$content); //!!!new
			}
							
			$m_keyword_id++;
		}
		foreach ($m_result as $m_single)
		{
			$m_keyword = $m_single['keyword'];
			$m_content = $m_single['content'];
			$tooltip_post_id = $m_single['post_id'];
			$tooltip_unique_id = $m_single['unique_id'];
			$content = str_ireplace($tooltip_unique_id, $m_keyword , $content);
		}		
	}
	
	do_action('action_after_tooltipsincontent', $content);
	$content = apply_filters( 'filter_after_tooltipsincontent',  $content);
		
	//!!!start
	$content = str_replace('@@@@', '', $content);
	$content = str_replace('####', '', $content);	
	//!!!end
	$content = str_ireplace('//##', '', $content);
	$content = str_ireplace('##]]', '', $content);	
	
	return $content;
}

function nextgenTooltips()
{
	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return '';
	}
	//end 6.9.3
?>
<script type="text/javascript">
jQuery("document").ready(function()
{
	jQuery("body img").each(function()
	{
		if ((jQuery(this).parent("a").attr('title') != '' )  && (jQuery(this).parent("a").attr('title') != undefined ))
		{
			toolTips(jQuery(this).parent("a"),jQuery(this).parent("a").attr('title'));
		}
		else
		{
			var tempAlt = jQuery(this).attr('alt');
			if (typeof(tempAlt) !== "undefined")
			{
				tempAlt = tempAlt.replace(' ', '');
				if (tempAlt == '')
				{

				}
				else
				{
					toolTips(jQuery(this),jQuery(this).attr('alt'));
				}
			}
		}
	}

	);
})
</script>
<?php
}


add_action('the_content','tooltipsInContent',$tooltipHookPriorityValue+1);
add_action('wp_head', 'tooltipsHead');
add_action('the_content','showTooltips',$tooltipHookPriorityValue);
 

$enableTooltipsForExcerpt = get_option("enableTooltipsForExcerpt");
if ($enableTooltipsForExcerpt =='YES')
{
	add_action('the_excerpt','tooltipsInContent',$tooltipHookPriorityValue+1);
	add_action('the_excerpt','showTooltips',$tooltipHookPriorityValue);	
}

$enableTooltipsForTags = get_option("enableTooltipsForTags");
if ($enableTooltipsForTags =='YES')
{
	add_action('the_tags','tooltipsInContent',$tooltipHookPriorityValue+1);
	add_action('the_tags','showTooltipsInTag',$tooltipHookPriorityValue);
}

$enableTooltipsForCategoryTitle = get_option("enableTooltipsForCategoryTitle");
if ($enableTooltipsForCategoryTitle =='YES')
{
	add_filter( 'get_the_archive_title','tooltipsInContent',$tooltipHookPriorityValue+1);
	add_action('get_the_archive_title','showTooltipsInTitle',$tooltipHookPriorityValue);
}



$enableTooltipsForImageCheck = get_option("enableTooltipsForImage");
if ($enableTooltipsForImageCheck == false)
{
	update_option("enableTooltipsForImage", "YES");
}
if ($enableTooltipsForImageCheck == 'YES')
{
	add_action('wp_footer','nextgenTooltips');
}


function upgrade_check()
{
	$currentVersion = get_option('ztooltipversion');

	if (empty($currentVersion))
	{
		$m_result = get_option('tooltipsarray');
		if (!(empty($m_result)))
		{
			$m_keyword_id = 0;
			foreach ($m_result as $m_single)
			{
				$m_keyword = $m_single['keyword'];
				$m_content = $m_single['content'];				
				$my_post = array(
  				'post_title'    => $m_keyword,
  				'post_content'  => $m_content,
  				'post_status'   => 'publish',
  				'post_type'   => 'tooltips',
  				'post_author'   => 1,
				);
				wp_insert_post( $my_post );
			}
		}
	
	}
	update_option('ztooltipversion','7.3.7');
}
add_action( 'init', 'upgrade_check');

function tooltips_get_option($type,$orderby='null',$orderdesc='null', $funccall = 'null')
{
	global $wpdb;
	$tooltipsarray = array();
	$m_single = array();
	if ($type == 'tooltipsarray')
	{
		$post_type = 'tooltips';
		if (($orderby == 'null') || ($orderdesc=='null'))
		{
			$orderbysql = '';
		}
		else
		{
			if ($funccall == 'null')
			{
				$orderbysql = "ORDER BY $orderby $orderdesc";
			}
			else
			{
				$orderbysql = "ORDER BY $funccall($orderby) $orderdesc";
			}
				
		}

		$sql = $wpdb->prepare( "SELECT ID, post_title, post_content FROM $wpdb->posts WHERE post_type=%s AND post_status='publish' $orderbysql",$post_type);
		$results = $wpdb->get_results( $sql );

		if ((!(empty($results))) && (is_array($results)) && (count($results) >0))
		{
			$m_single = array();
			foreach ($results as $single)
			{
				$m_single['keyword'] = $single->post_title;
				$m_single['content'] = $single->post_content;
				$m_single['post_id'] = $single->ID;
				$m_single['unique_id'] = tooltips_unique_id();
				$tooltipsarray[] = $m_single;
			}
		}
	}
	return $tooltipsarray;
}


$enableTooltipsForImageCheck = get_option("enableTooltipsForImage");
if ($enableTooltipsForImageCheck == false)
{
	update_option("enableTooltipsForImage", "YES");
}

function showTooltipsInShorcode($content)
{
	global $table_prefix,$wpdb,$post;

	do_action('action_before_showtooltips', $content);
	$content = apply_filters( 'filter_before_showtooltips',  $content);
	
	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return $content;
	}
	//end 6.9.3
	
	$curent_content = $content;

	
	$m_result = tooltips_get_option('tooltipsarray','post_title', 'DESC', 'LENGTH');
	$m_keyword_result = '';
	if (!(empty($m_result)))
	{
		$m_keyword_id = 0;
		foreach ($m_result as $m_single)
		{
			
			if (stripos($curent_content,$m_single['keyword']) === false)
			{
				
			}
			else 
			{			
				$m_keyword_result .= '<script type="text/javascript">';
				$m_content = $m_single['content'];
				$m_content = do_shortcode($m_content);
				
				$m_content = preg_quote($m_content,'/');
				$m_content = str_ireplace('\\','',$m_content);
				$m_content = str_ireplace("'","\'",$m_content);
				$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
				$m_content = preg_replace('|\r|', '', $m_content);
				$m_content = preg_replace('|\n|', '<br/>', $m_content);				
								
				if (!(empty($m_content)))
				{
					$m_keyword_result .= " toolTips('.classtoolTips$m_keyword_id','$m_content'); ";
				}
				$m_keyword_result .= '</script>';
			}
			$m_keyword_id++;
		}
	}
	$content = $content.$m_keyword_result;
	do_action('action_after_showtooltips', $content);
	$content = apply_filters( 'filter_after_showtooltips',  $content);
	return $content;
}

function tooltips_list_shortcode($atts)
{
	global $table_prefix,$wpdb,$post;

	$tooltipsarray = array();
	$m_single = array();

	$return_content = '';
	$return_content .= '<div class="tooltips_directory">';

	$post_type = 'tooltips';
	$sql = $wpdb->prepare( "SELECT ID, post_title, post_content FROM $wpdb->posts WHERE post_type=%s AND post_status='publish' order by post_title ASC",$post_type);
	$results = $wpdb->get_results( $sql );

	if ((!(empty($results))) && (is_array($results)) && (count($results) >0))
	{
		$m_single = array();
		foreach ($results as $single)
		{
			$return_content .= '<div class="tooltips_content_wrap"><div class="tooltips_list">'.$single->post_title.'</div></div>';
		}
	}

	$return_content = tooltipsInContent($return_content);
	$return_content = showTooltipsInShorcode($return_content);

	$return_content .= '</div>';
	
	$return_content = do_shortcode($return_content);
	
	return $return_content;

}

add_shortcode( 'tooltipslist', 'tooltips_list_shortcode' );

function tooltips_wiki_reference($atts, $keyword = null)
{
	extract(shortcode_atts( array(
	'content' => "Proper Shortcode Usage is: <div>[tooltips keyword='wordpress' content = 'hello, wp']</div>",
	), $atts ));

	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return $keyword;
	}
	//end 6.9.3
	
	$m_keyword_result = '';
	$keywordmd = md5(uniqid('',TRUE));
	$m_replace = "<span class=\"tooltipsall tooltip_post_id_custom_$keywordmd classtoolTipsCustomShortCode\" style=\"border-bottom:2px dotted #888;\">".$keyword."</span>";
	$m_keyword_result .= $m_replace;

	$m_keyword_result .= '<script type="text/javascript">';
	$m_content = $content;

	$m_content = preg_quote($m_content,'/');
	$m_content = str_ireplace("'","\'",$m_content);
	$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
	$m_content = preg_replace('|\r|', '', $m_content);
	$m_content = preg_replace('|\n|', '<br/>', $m_content);
	
	if (!(empty($m_content)))
	{
		$m_keyword_result .= " toolTips('.tooltip_post_id_custom_$keywordmd','$m_content'); ";
	}
	$m_keyword_result .= '</script>';

	return $m_keyword_result;
}

add_shortcode( 'tooltips_wiki_reference', 'tooltips_wiki_reference' );

add_shortcode( 'ttsref', 'tooltips_wiki_reference' );


//!!!function tomas_one_tooltip_shortcode( $atts, $content = null )
function tomas_one_tooltip_shortcode( $atts, $inputcontent = null )
{
	extract(shortcode_atts( array(
	'keyword' => "Proper Shortcode Usage is: <div>[tooltips keyword='wordpress' content = 'hello, wp']</div> or <div>[tooltips content = 'hello, wp']wordpress[/tooltips]</div>",
	'content' => "Proper Shortcode Usage is: <div>[tooltips keyword='wordpress' content = 'hello, wp']</div> or <div>[tooltips content = 'hello, wp']wordpress[/tooltips]</div></div>",
	), $atts ));
	//!!!start
	if (!empty(trim($inputcontent)))
	{
		$keyword = $inputcontent;
	}
	//!!!end
	
	//6.9.3
	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return $keyword;
	}
	//end 6.9.3
	
	
	$m_keyword_result = '';
	$keywordmd = md5(uniqid('',TRUE));
	$m_replace = "<span class=\"tooltipsall tooltip_post_id_custom_$keywordmd classtoolTipsCustomShortCode\" style=\"border-bottom:2px dotted #888;\">$keyword</span>";
	$m_keyword_result .= $m_replace;

	$m_keyword_result .= '<script type="text/javascript">';
	$m_content = $content;

	$m_content = preg_quote($m_content,'/');
	$m_content = str_ireplace("'","\'",$m_content);
	$m_content = preg_replace('|\r\n|', '<br/>', $m_content);
	$m_content = preg_replace('|\r|', '', $m_content);
	$m_content = preg_replace('|\n|', '<br/>', $m_content);
	
	if (!(empty($m_content)))
	{
		$m_keyword_result .= " toolTips('.tooltip_post_id_custom_$keywordmd','$m_content'); ";
	}
	$m_keyword_result .= '</script>';

	return $m_keyword_result;
}

add_shortcode('tooltips', 'tomas_one_tooltip_shortcode');


add_action('widgets_init', 'TooltipsWidgetInit');


/**** localization ****/
add_action('plugins_loaded','tooltips_load_textdomain');

function tooltips_load_textdomain()
{
	load_plugin_textdomain('wordpress-tooltips', false, dirname( plugin_basename( __FILE__ ) ).'/languages/');
}

function tooltips_plugin_action_links( $links, $file ) 
{
	if ( $file == plugin_basename( __FILE__ ))
	{

		$settings_link = '<i><a href="https://tooltips.org/features-of-wordpress-tooltips-plugin/" target="_blank">'.esc_html__( 'Pro Feature' , 'wordpress-tooltips').'</a></i>';
		array_unshift($links, $settings_link);
		
		$settings_link = '<i><a href="https://tooltips.org/wordpress-tooltip-plugin/wordpress-tooltips-demo" target="_blank">'.esc_html__( 'DEMOs' , 'wordpress-tooltips').'</a></i>';
		array_unshift($links, $settings_link);
		
		$settings_link = '<a href="' . admin_url( 'edit.php?post_type=tooltips' ) . '">'.esc_html__( 'Settings' , 'wordpress-tooltips').'</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}

add_filter( 'plugin_action_links', 'tooltips_plugin_action_links', 10, 2 );


function tooltips_pro_meta_box_control_meta_box()
{
	?>
<div class="inside" style=''>
<ul>
<li>										 
* Custom unique pretty style for each tooltip, each tooltip can have their own pretty style, Tooltip Box Background,Width,Font Color,... many more, <a href="https://tooltips.org/features-of-wordpress-tooltips-plugin/" target="_blank">build a colorful and varied and graceful tooltips site super easy and fast</a> 
</li>
<li>
*  Support tooltips for <a href="https://tooltips.org/product/show-tooltips-in-woocommerce-products/">WooCommerce Product</a>, <a href="https://tooltips.org/add-tooltips-for-table/">Tables</a>, <a href="https://tooltips.org/tooltips-for-pricing-table/" >Pricing Table</a>, <a href="https://tooltips.org/tooltips-for-button/">Buttons</a>, Contact Form 7, BuddyPress, bbPress, ACF, HTML5 FAQ, menu item, posts title, tags, <a href="https://tooltips.org/contact-us/" target="_blank">form tooltips to add tooltip for each form fields</a>...more
</li>
<li>
* <a class="" target="_blank" href="https://tooltips.org/features-of-wordpress-tooltips-plugin/">Fine-grained options to custom tooltips and glossary:</a> powerful tooltip custom style panel to make pretty & amazing tooltips for users, one click to custom more than 28 tooltip elements, include border, opacity, width, position, shadow, font, title bar, close button, color via  color picker... and more, enable / disable tooltips for specified post types and specified pages, multi trigger methods, tooltip support video / audio / links / image in tooltips, image tooltips use alt / title / rel, use image as tooltip image for another image. Glossary pages, customized glossary style, tooltip by id / categories name, glossary by id, / categories name...a lot of more! 
</li>
<li>
* Responsive, WPML Multilingual, Hit stats, import tooltips, custom your own language, <a href='https://tooltips.org/bullet-screen'>Bullet Screen effects</a> ...more features
</li>
<li>
* $9 only <a class="" target="_blank" href="https://tooltips.org/features-of-wordpress-tooltips-plugin/">Lifetime Upgrades, Unlimited Download, Ticket Support</a>
</li>
</ul>
</div>
<?php
}

function tooltips_pro_meta_box()
{
	global $post;

	if ($post->post_type == 'tooltips')
	{
		add_meta_box("tooltips_pro_meta_box", __( 'Features & Demos of Tooltips Pro', 'wordpress-tooltips' ), 'tooltips_pro_meta_box_control_meta_box', null, "side", "high", null);
	}
}

add_action( 'add_meta_boxes',  'tooltips_pro_meta_box' );


function content_tooltips_keyword_synonyms_control_meta_box()
{
	global $post;
	$current_page_id = get_the_ID();
	$get_post_meta_value_for_this_page = get_post_meta($current_page_id, 'toolstipssynonyms', true);
	global $wpdb;

	?>
	<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
	    <tbody>
	    <tr class="form-field">
	        <td>
	        	<p>Synonyms of the keyword</p>
				<input type="text" id="toolstipssynonyms" name="toolstipssynonyms" value="<?php echo $get_post_meta_value_for_this_page;  ?>">
				<p style="color:gray;font-size:12px;"><i>( separated by '|' )</i></p>
	        </td>
	    </tr>
	    </tbody>
	</table>
	<?php

}


function tooltips_keyword_synonyms_control_meta_box()
{
	global $post;

	if ($post->post_type == 'tooltips')
	{
		add_meta_box("tooltips_keyword_synonyms_control_meta_box_id", __( 'Synonyms of this tooltip', 'wordpress-tooltips' ), 'content_tooltips_keyword_synonyms_control_meta_box', null, "side", "high", null);
	}

}

function save_content_tooltips_keyword_synonyms_control_meta_box($post_id, $post, $update)
{
	global $post;

	$current_page_id = get_the_ID();

	$get_post_meta_value_for_this_page = get_post_meta($current_page_id, 'toolstipssynonyms', true);

	if(isset($_POST['toolstipssynonyms']) != "") {
		$meta_box_checkbox_value = $_POST['toolstipssynonyms'];
		update_post_meta( $current_page_id, 'toolstipssynonyms', $meta_box_checkbox_value );
	} else {
		update_post_meta( $current_page_id, 'toolstipssynonyms', '' );
	}
}

add_action( 'add_meta_boxes',  'tooltips_keyword_synonyms_control_meta_box' );
add_action( 'save_post', 'save_content_tooltips_keyword_synonyms_control_meta_box' , 10, 3);

function tooltips_table_shortcode($atts)
{
	global $table_prefix,$wpdb,$post;

	$args = array( 'post_type' => 'tooltips', 'post_status' => 'publish' );

	$tooltipsarray = array();
	$m_single = array();

	$return_content = '';
	$return_content .= '<div class="tooltips_directory">';

	$post_type = 'tooltips';
	$sql = $wpdb->prepare( "SELECT ID, post_title, post_content FROM $wpdb->posts WHERE post_type=%s AND post_status='publish' order by post_title ASC",$post_type);
	$results = $wpdb->get_results( $sql );

	if ((!(empty($results))) && (is_array($results)) && (count($results) >0))
	{
		$m_single = array();
		foreach ($results as $single)
		{
			$return_content .= '<div class="tooltips_list">';
			$return_content .= '<span class="tooltips_table_items">';
			$return_content .= '<div class="tooltips_table">';
			$return_content .= '<div class="tooltips_table_title">';
			$return_content .=	$single->post_title;
			$return_content .='</div>';
			$return_content .= '<div class="tooltips_table_content">';
			$return_content .=	$single->post_content;
			$return_content .='</div>';
			$return_content .='</div>';
			$return_content .='</span>';
			$return_content .='</div>';
		}
	}
	$return_content .= '</div>';

	$return_content = do_shortcode($return_content);
	
	return $return_content;
}
add_shortcode( 'glossary', 'tooltips_table_shortcode' );


function footernav()
{
	global $post;

	$choseLanguageForGlossary = get_option("enableLanguageForGlossary");
	if (empty($choseLanguageForGlossary)) $choseLanguageForGlossary = 'en';

	$enableLanguageCustomization = get_option("enableLanguageCustomization");
	if (empty($enableLanguageCustomization)) $choseLanguageForGlossary = 'en';
	if ('NO' == $enableLanguageCustomization) $choseLanguageForGlossary = 'en';

// 7.3.1
	$glossaryNumbersOrNot =  get_option("glossaryNumbersOrNot");
	if (empty($glossaryNumbersOrNot))
	{
		$glossaryNumbersOrNot = 'yes';
	}
	$glossaryNumbersOrNot = strtolower($glossaryNumbersOrNot);
// end 7.3.1	
?>
<script type="text/javascript">
var inboxs = new Array();
inboxs['language'] = "<?php echo $choseLanguageForGlossary; ?>";
inboxs['navitemselectedsize'] = '18px';
inboxs['selectors'] = '.tooltips_list > span';
<?php  // before 7.3.1  inboxs['number'] = 'yes'; 
//7.3.4
?>
inboxs['number'] = "<?php echo $glossaryNumbersOrNot; ?>";
<?php 
if ($choseLanguageForGlossary == 'custom')
{
	$glossaryLanguageCustomNavALL = get_option('glossaryLanguageCustomNavALL');
	if (empty($glossaryLanguageCustomNavALL))
	{
		$glossaryLanguageCustomNavALL = 'ALL';
	}
	?>
	inboxs['wordofselectall'] = "<?php echo $glossaryLanguageCustomNavALL; ?>";
	<?php
}
?>
jQuery('.tooltips_directory').directory(inboxs);
</script>
<?php
}

add_action('wp_footer','footernav');


function rangesliderinit()
{
	$tooltipZindexValue = get_option("tooltipZindexValue");
	if (empty($tooltipZindexValue))
	{
		$tooltipZindexValue = 15001;
	}
	$tooltipHookPriorityValue = get_option("tooltipHookPriorityValue");
	if (empty($tooltipHookPriorityValue))
	{
		$tooltipHookPriorityValue = '20000';
	}
	?>
	<script type="text/javascript">
	var tooltip_zindex_custom_values = [-1, 10, 100, 1000, 10000, 100000, 1000000];
	jQuery(document).ready(function($) 
	{
		$('#tooltipZindexValue').ionRangeSlider({min: -1,max: 1000000,from: <?php echo $tooltipZindexValue; ?>,step:1000,grid: true,skin: "modern"});
	});

	jQuery(document).ready(function($) 
	{
		$('#tooltipHookPriorityValue').ionRangeSlider({min: 7,max: 100000,from: <?php echo $tooltipHookPriorityValue; ?>,step:1,grid: true,skin: "flat"});
	});	
	</script>
	<?php
}

add_action('admin_footer','rangesliderinit');



function footerdisabletooltipinhtmltag()
{
	$disabletooltipinhtmltag = get_option("disabletooltipinhtmltag");
	if (!(empty($disabletooltipinhtmltag)))
	{
		$patterns = '';
		$replacements = '';
		$disabletooltipinhtmltag = trim($disabletooltipinhtmltag);
		$disabletooltipinhtmltagarray = explode(',', $disabletooltipinhtmltag);
		if ((!(empty($disabletooltipinhtmltagarray))) && (is_array($disabletooltipinhtmltagarray)) && (count($disabletooltipinhtmltagarray) > 0))
		{
			echo '<script type="text/javascript">';
			foreach ($disabletooltipinhtmltagarray as $disabletooltipinhtmltagSingle)
			{
				$disabletooltipinhtmltagSingle = trim($disabletooltipinhtmltagSingle);
				?>
				jQuery(document).ready(function () {
					jQuery('<?php echo $disabletooltipinhtmltagSingle;?> .tooltipsall').each
					(function()
					{
					disabletooltipinhtmltagSinglei = jQuery(this).html();
					jQuery(this).replaceWith(disabletooltipinhtmltagSinglei);
					})
				})
				<?php 
			}
			echo '</script>';
		}			
	}

}
add_action('wp_footer','footerdisabletooltipinhtmltag');


function tooltips_free_admin_css()
{
	wp_enqueue_style('tooltips_free_admin_css', plugin_dir_url( __FILE__ ) .'asset/admin/css/admin.css');

	$current_edit_page = strtolower($_SERVER['REQUEST_URI']);
	if (!(empty($current_edit_page)))
	{
		if (strpos($current_edit_page, 'tooltipsfaq') === false)
		{

		}
		else
		{
			wp_register_script( 'tooltips_admin_js', plugin_dir_url( __FILE__ ).'/asset/admin/js/admin.js', array('jquery'));
			wp_enqueue_script( 'tooltips_admin_js' );
		}
	}
}
add_action('admin_head', 'tooltips_free_admin_css');

function create_glossary_index_page()
{
	global $table_prefix,$wpdb,$post, $wp_rewrite;
	$enabGlossaryIndexPage = get_option("enabGlossaryIndexPage");
	if ($enabGlossaryIndexPage <> 'NO')
	{
		$tooltipsGlossaryIndexPage = get_option("tooltipsGlossaryIndexPage");

		if (empty($tooltipsGlossaryIndexPage))
		{
			$glossary_index_page = $wpdb->get_var($wpdb->prepare("select id from $wpdb->posts where post_title=%s and post_status='publish' ", 'glossary'));
			if (empty($glossary_index_page))
			{
				$insert_glossary_index_page = array();
				$insert_glossary_index_page['post_title'] = 'glossary';
				$insert_glossary_index_page['post_content'] = '';
				$insert_glossary_index_page['post_status'] = 'publish';
				$insert_glossary_index_page['post_author'] = 1;
				$insert_glossary_index_page['post_type'] = 'page';
				$inserted_glossary_index_page = wp_insert_post($insert_glossary_index_page);
				$wp_rewrite->flush_rules();
			}
			else
			{

			}
		}
	}
}
add_action('wp_head', 'create_glossary_index_page');



//!!!start
$selectsignificantdigitalsuperscripts = get_option('selectsignificantdigitalsuperscripts');
if ('no' == strtolower($selectsignificantdigitalsuperscripts))
{
	require_once('rules/glossarysuperscripts.php');
}
//!!!end

function tt_free_user_first_run_guide_bar()
{
	$is_user_first_run_guide_bar = get_option('user_first_run_guide_bar');
	if (empty($is_user_first_run_guide_bar))
	{
		echo "<div class='notice tooltips-notice notice-info'><p>Thanks for installing <strong>Tooltips</strong>! Please check <a href='" . admin_url() . "edit.php?post_type=tooltips&page=tooltipsfaq' target='_blank'>Tooltip Knowledge Base</a> and <a href='" . admin_url() . "post-new.php?post_type=tooltips' target='_blank'>Create First Tooltip</a>, it will starting work automatically. Here is <a href='" . admin_url() . "edit.php?post_type=tooltips&page=tooltipglobalsettings' target='_blank'>Setting Panel</a>, Any question or feature request please contact <a href='https://tooltips.org/support-ticket/'  target='_blank'>Support</a> :)</p></div>";
		update_option('user_first_run_guide_bar','yes');
	}
}

add_action( 'admin_notices', 'tt_free_user_first_run_guide_bar' );



function tomas_tooltip_by_id_shortcode_free( $atts, $content = null )
{

	if (tooltips_pro_disable_tooltip_in_mobile_free())
	{
		return '';
	}


	$keyword = '';
	extract(shortcode_atts( array(
			'tooltip_id' => __("Proper Shortcode Usage is: <div>[tooltip_by_id tooltip_id='1']</div>",'wordpress-tooltips'),
	), $atts ));


	$tooltip_id = (INT) $tooltip_id;

	if (!(is_int($tooltip_id)))
	{
		return false;
	}

	$tooltip_id_post = tooltips_get_by_id_free($tooltip_id);

	if ($tooltip_id_post == false)
	{
		return false;
	}

	if ((!(empty($tooltip_id_post))) && (is_array($tooltip_id_post)) && (count($tooltip_id_post) >0))
	{
		$keyword = $tooltip_id_post['keyword'];
		$content = $tooltip_id_post['content'];
	}


	$m_keyword_result = '';
	$keywordmd = md5(uniqid('',TRUE));

	$m_replace = "<span class='tooltipsall tooltip_post_id_custom_$keywordmd classtoolTipsCustomShortCode'>$keyword</span>";
	$m_keyword_result .= $m_replace;

	$m_keyword_result .= '<script type="text/javascript">';
	$m_keyword_result .= 'jQuery("document").ready(function(){';

	$m_content = $content;

	$m_content = str_ireplace('\\','',$m_content);
	$m_content = str_ireplace("'","\'",$m_content);

	$m_content = preg_replace('/([^>])(\r\n)/is', "\\1".'<br/>', $m_content);
	$m_content = preg_replace('/([>])(\r\n)/is', "\\1".'', $m_content);

	if (!(empty($m_content)))
	{
		$m_keyword_result .= '//##'. " toolTips('.tooltip_post_id_custom_$keywordmd','$m_content'); ".'##]]';
	}

	$m_keyword_result .= '});';  
	$m_keyword_result .= '</script>';

	return $m_keyword_result;
}

add_shortcode('tooltip_by_id', 'tomas_tooltip_by_id_shortcode_free');


function tooltips_get_by_id_free($tooltip_id)
{
	global $wpdb;
	$tooltipsarray = false;
	$m_single = array();
	if (empty($tooltip_id))
	{
		return false;
	}


	$post_type = 'tooltips';

	$sql = $wpdb->prepare( "SELECT ID, post_title, post_content FROM $wpdb->posts WHERE post_type=%s AND post_status='publish' AND ID=%s",$post_type,$tooltip_id);
	$results = $wpdb->get_row( $sql,ARRAY_A );

	if ((!(empty($results))) && (is_array($results)) && (count($results) >0))
	{
		$tooltipsarray = array();
		$tooltipsarray['keyword'] = $results['post_title'];
		$tooltipsarray['content'] = $results['post_content'];
		$tooltipsarray['post_id'] = $results['ID'];
		$tooltipsarray['unique_id'] = tooltips_unique_id();
	}
	return $tooltipsarray;
}


function tooltips_unique_id_free()
{
	$tooltips_unique_id = md5(uniqid(mt_rand(),1));
	return $tooltips_unique_id;
}


