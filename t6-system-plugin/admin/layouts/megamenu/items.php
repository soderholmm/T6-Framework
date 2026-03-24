<?php

defined ('_JEXEC') or die ();
use T6Admin\RowColumnSettings AS RowColumnSettings;
$items = $displayData;

$layout_path  = JPATH_ROOT .'/plugins/system/t6/admin/layouts';
$configItem = '';
if(isset($items[1]))
{
	$configItem = RowColumnSettings::getSettings($items[1]);
}
if(!isset($items[1]->megabuild))$items[1]->megabuild = 0;
$output  = '';
$checked = ($items[1]->megabuild == '1') ? 'checked="true"' : "";
$value 	 = ($items[1]->megabuild == '1') ? "1" : "0";
$output .= '<div class="enablemega '.$items[0]->id.' t6-'.$items[0]->alias.'">
				<label for="megabuild-'.$items[0]->id.'">Build Mega Menu</label>
				<input id="megabuild-'.$items[0]->id.'" class="t6-item t6-input t6-input-check-mega" type="checkbox" name="megabuild" data-attrname="megabuild" value="'. $value .'" '. $checked  .'/>
			</div>';
$output .= '<div class="item-mega-config">
				<div class="item-mega-width">
					<label class="item-width" for="width">Submenu Width (px)</label>
					<input id ="width" type="text" placeholder="300px" class="t6-item t6-item-width" name="item-width" value="" />
				</div>
				<div id="mega-extra" class="mega-extra-class">
					<label for="megaextra">Extra Class mega Item</label>
					<input id="mega_extra" type="text" name="mega_extra" value="" class="t6-item t6-mega-extra-class" aria-invalid="false">
				</div>
				<div class="item-mega-align">
					<label class="item-align">Alignment</label>
					<div class="t6-item btn-group">
						<a class="btn t6-item-align-left t6-item-action active" href="#" data-action="alignment" data-align="left" title="Left"><i class="fal fa-align-left"></i></a>
						<a class="btn t6-item-align-right t6-item-action" href="#" data-action="alignment" data-align="right" title="Right"><i class="fal fa-align-right"></i></a>
						<a class="btn t6-item-align-center t6-item-action" href="#" data-action="alignment" data-align="center" title="Center"><i class="fal fa-align-center"></i></a>
					</div>
				</div>
			</div>';
$output .="<div class=\"t6-menu-item\" style='display:none;' $configItem>";
				
if (isset($items[1]->settings) ){
	foreach ($items[1]->settings as $item) {
		$lt_section = new JLayoutFile('megamenu.item', $layout_path );
		$output .= $lt_section->render($item);
	}
}else{
	$lt_section = new JLayoutFile('megamenu.item', $layout_path );
		$obj = new stdClass;
		$obj->idx = 0;
		$output .= $lt_section->render($obj);
}

$output .= "</div>";

$output .= '<div class="t6-menu-add-row" style="display:none;"><a class="" href="#"><i class="fal fa-plus-circle"></i><span>Add Row</span></a></div>';
echo $output;