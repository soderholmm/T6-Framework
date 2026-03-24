<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use T6Admin\Settings AS Settings;
use T6Admin\MegaSettings AS MegaSettings;
defined ('_JEXEC') or die ();

$layout_path  = JPATH_ROOT .'/plugins/system/t6/admin/layouts';
echo Settings::getMegaRowConfig();
echo Settings::getMegaItemSettings();
$Megamenu = MegaSettings::getMenu();
$option = [''=> Text::_('T6_SELECT_MENU')];
$menuOpt = MegaSettings::getMenuType();
$option = array_merge($option, $menuOpt);
$default = $megamenu_data ? key((array) $megamenu_data) : '';
echo "<div class='select-menu-type'>";
echo "<label for=\"menu_type\">Select Menu Type</label>";
echo $html = HTMLHelper::_('select.genericlist',$option,'menu_type','class="megamenu"',$value = '',$text = '',$default);
echo "</div>";
?>
<div class="t6-megamenu-builder">
	<div class="menu_items">
		<?php echo MegaSettings::getMenuItems(); ?>
	</div>
	<div class="item-config">
		<div class="menu-item-extra">
			<label for="extra"><?php echo Text::_('T6_MENU_EXTRA_CLASS');?></label>
			<input id="extra" type="text" name="extra_class" value="" class="t6-item t6-extra-class" />
			<!-- <span class="control-helper">Description here</span> -->
		</div>
		<div class="menu-item-icon">
			<label for="icons"><?php echo Text::_('T6_MENU_ITEM_ICON');?></label>
			<input id="icons" type="text" name="t6_item_icon" value="" class="t6-item  t6-item-icon" />
			<!-- <span class="control-helper">Description here</span> -->
		</div>
		<div class="menu-item-caption">
			<label for="caption"><?php echo Text::_('T6_MENU_ITEM_CAPTION');?></label>
			<input id="caption" type="text" name="t6_item_caption" value="" class="t6-item t6-item-caption" />
			<!-- <span class="control-helper">Description here</span> -->
		</div>
	</div>
	<div class="t6-menu-layout">
		<!-- Layout Builder Section -->
		<div class="t6-menu-layout-builder" >
			<div style="display: none">
			    <?php
			        $lt_section = new JLayoutFile('megamenu.item', $layout_path );
			        $obj = new stdClass;
			        $obj->id = true;
			        echo $lt_section->render($obj);
			    ?>
			</div>
		<?php
		    $output = '';
		    foreach ($Megamenu as $type =>  $Items) {
		    	$output .=  "<div class=\"t6-megamenu t6-{$type}\" data-type=\"{$type}\" >";
		    	$i = 0;
		    	foreach ($Items as $item) {
		    		$data = [$item];
		    		if(!empty($megamenu_data->{$type}->{$item->id})){
		    			$data[] = $megamenu_data->{$type}->{$item->id};
			    	}else{
			    		 $obj = new stdClass;
			        	$obj->id = true;
			    		$data[] = $obj;
			    	}
			    	$style = "style='display:none;'";
					$output .= "<div class='t6-menu-items itemid-".$item->id."' data-itemid='".$item->id."' $style >";
			    	$lt_section = new JLayoutFile('megamenu.items', $layout_path );
		        	$output .= $lt_section->render($data);
		        	$output .=  '</div>';
		        	$i++;
		    	}
		    	$output .=  '</div>';
		    }
		    echo $output;
	    ?>
		</div>
	</div>
</div>
<div class="clearfix"></div>
