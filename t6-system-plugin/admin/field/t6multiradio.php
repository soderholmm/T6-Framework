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
class JFormFieldT6multiradio extends FormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'T6MultiRadio';
	protected function getInput(){
		
		$html  = '<div class="fuildwidth btn-group">
						<a class="contaifix btn active" data-container="container">Fix</a>
						<a class="btn fluid" data-container="container-fluid">Fluid</a>
						<a class="btn fluid-none" data-container="none">None</a>
						<input id="t6-cont" type="hidden" name="container" data-attrname="container" value="container" class="t6-layout t6-layout-container" />
					</div>';
		// $html  .= '</div>';
		return $html;
	}
}
 