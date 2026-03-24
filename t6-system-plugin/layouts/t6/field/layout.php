<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

defined('JPATH_BASE') or die;

extract($displayData);
$doc = Factory::getDocument();
$doc->addStylesheet(Uri::root(true) . '/media/t6/builder/css/style.css');

?>
<div class="t6-item-wrapper">

    <input type="hidden" name="<?php echo $name ?>" value="<?php echo htmlentities($value) ?>" data-t6editor />
</div>

