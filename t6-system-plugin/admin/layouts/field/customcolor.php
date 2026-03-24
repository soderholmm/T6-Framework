<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$id = $displayData['id'];
$inputName = $displayData['name'];
$inputValue = $displayData['value'];
$data = $displayData['colors'];

$script = "\nvar T6Admin = window.T6Admin || {}; ";
$script .= "\nT6Admin.customcolors = ". json_encode($data) . "; ";
Factory::getDocument()->addScriptDeclaration($script);
Factory::getDocument()->addScript(T6PATH_ADMIN_URI . '/assets/js/custom-color.js');

$edit_class = " can-edit";
$decs = Text::_('T6_FIELD_CUSTOM_COLOR_MASTER_DESC');

?>
<div class="t6-custom-color-wrap">
	<?php if(!empty($data)):?>
		<ul class="custom-color-list">
		<?php foreach($data as $colorName => $colorData):?>
			<li class="t6-custom-color" data-name="<?php echo $colorData['name']; ?>" data-class="<?php echo $colorName; ?>"  data-color="<?php echo $colorData['color']; ?>">
				<input class="t6-color-picker" data-name="<?php echo $colorData['name']; ?>" data-class="<?php echo $colorName; ?>"  data-color="<?php echo $colorData['color']; ?>" name="t6_color_name" type="hidden" value="<?php echo $colorData['color']; ?>" />
			</li>
		<?php endforeach ?>
		<li class="t6-custom-color color-ghost" data-name="" data-class=""  data-color="">
			<span class="preview-icon t6-color"><i class="far fa-plus-square"></i></span>
		</li>
		</ul>
	<?php endif ?>
</div>
<input type="hidden" id="<?php echo $id; ?>" class="t6-custom-colors" name="<?php echo $inputName; ?>" value="<?php echo htmlspecialchars($inputValue); ?>">	
