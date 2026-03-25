<?php
/*
 * @Copyright: (C) 2026 Ab Söderholms IT-tjänster Oy
 * @Author: Soderholmm
 */
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2026 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

use Joomla\CMS\Form\FormField;
use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die;

/**
 * Form Field class for the Joomla Framework.
 *
 * @since  2.5
 */
class JFormFieldT6Layout extends FormField
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 't6layout';
	protected $layout = 'field.t6layout';
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   2.5
	 */
	protected function getInput()
	{
		$htmls = '';
  	$t6_layout_path = T6PATH_ADMIN . '/layouts/';
    $rows = json_decode($this->value);
    if(empty($rows->sections)){
      $layout_file = T6\Helper\Path::getFileContent('etc/layout/default.json');
  	$rows = $layout_file ? json_decode($layout_file ) : [];
    }
    $data['id'] = $this->id;
    $data['name'] = $this->name;
    $data['value'] = $this->value;
    $data['layout'] = $rows;
    return LayoutHelper::render ($this->layout, $data, T6PATH_ADMIN . '/layouts');
	}

	/**
	 * Method to get the field label markup for a spacer.
	 * Use the label text or name from the XML element as the spacer or
	 * Use a hr="true" to automatically generate plain hr markup
	 *
	 * @return  string  The field label markup.
	 *
	 * @since   11.1
	 */
	protected function getLabel()
	{
		return null;
	}
}
 