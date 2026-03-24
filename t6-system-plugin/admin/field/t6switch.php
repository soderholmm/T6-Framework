<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Form\Field\RadioField;
use Joomla\CMS\Form\FormHelper;

defined('_JEXEC') or die;
FormHelper::loadFieldClass('radio');

if (!class_exists('RadioFieldLegacy')) {
	class RadioFieldLegacy extends RadioField
	{
	}
}

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  2.5
 */
class JFormFieldT6Switch extends RadioFieldLegacy
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'T6Switch';
	protected function getInput()
	{
		return parent::getInput();
	}
	protected function getOptions()
	{
		$options = parent::getOptions();
		return $options;
	}
}
