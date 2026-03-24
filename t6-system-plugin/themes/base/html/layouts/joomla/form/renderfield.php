<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//t6 override
defined('JPATH_BASE') or die;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;

extract($displayData);

/**
 * Layout variables
 * ---------------------
 * 	$options      : (array)  Optional parameters
 * 	$name         : (string) The id of the input this label is for
 * 	$label        : (string) The html code for the label (not required if $options['hiddenLabel'] is true)
 * 	$input        : (string) The input field html code
 * 	$description  : (string) An optional description to use in a tooltip
 */
$name = $label;
if (!empty($options['showonEnabled']))
{
	/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
	$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
	$wa->useScript('showon');
}
$class           = empty($options['class']) ? '' : ' ' . $options['class'];
$rel             = empty($options['rel']) ? '' : ' ' . $options['rel'];
$hideLabel       = !empty($options['hiddenLabel']);
$hideDescription = empty($options['hiddenDescription']) ? false : $options['hiddenDescription'];
?>
<div class="control-group<?php echo $class; ?>"<?php echo $rel; ?>>
	<?php if ($hideLabel) : ?>
		<div class="visually-hidden"><?php echo $label; ?></div>
	<?php else : ?>
		<div class="control-label"><?php echo $label; ?></div>
	<?php endif; ?>

	<?php $id = $displayData['name'] . '-desc'; ?>
	<div class="controls"<?php if (!$hideDescription) : ?> aria-describedby="<?php echo $id; ?>"<?php endif; ?>>
		<?php echo $input; ?>
		<?php if (!$hideDescription && !empty($description)) : ?>
			<div id="<?php echo $id; ?>">
				<small class="form-text">
					<?php echo $description; ?>
				</small>
			</div>
		<?php endif; ?>
	</div>
</div>