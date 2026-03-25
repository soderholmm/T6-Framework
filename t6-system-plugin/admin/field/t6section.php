<?php
/*
 * @Copyright: (C) 2026 Ab Söderholms IT-tjänster Oy
 * @Author: Soderholmm
 */
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Form\FormHelper;

defined('_JEXEC') or die;

FormHelper::loadFieldClass('list');
/**
 * Form Field class for the Joomla Framework.
 *
 * @since  2.5
 */
class JFormFieldT6Section extends FormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'T6Section';
	protected function getInput()
	{
		$output   = '<div class="section-group clearfix">';
		$output  .= '<div class="config-section t6-row-container"></div>';
		$output  .= '<input class="t6-layout t6-input-'.$this->element['name'].'" data-attrname="'.$this->element['name'].'" type="hidden">';
		$output  .= '</div>';

		return $output; 
	}
}

 