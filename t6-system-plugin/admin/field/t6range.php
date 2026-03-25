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
class JFormFieldT6Range extends FormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'T6Range';
	protected function getInput()
	{
		$html = '';
		$html .= '<div class="t6-slider"><input id="'.$this->id.'" type="range" class="t6-input '.$this->element['class'].'" name="' . $this->name . '" min="0" max="1" step="0.01" data-attrname="'.$this->element['name'].'" value="'.$this->value.'"><div class="slider-bg"><div class="slider-bg-lower"></div></div></div><span><input type="number"  min="0" step="0.01" max="1" id="opacityVal" value="'.$this->value.'" name="opacity_val" /></span>'; 
		return $html;
	}
}
 