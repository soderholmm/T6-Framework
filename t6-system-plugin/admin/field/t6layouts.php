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

use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;

defined('_JEXEC') or die;

if (!class_exists('ListFieldLegacy')) {
	class ListFieldLegacy extends ListField
	{
	}
}

class JFormFieldT6layouts extends ListFieldLegacy
{
	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 't6layouts';

	protected function getOptions()
	{
		$options = [];
		$options[] = (object) ['value' => '', 'text' => Text::_('JGLOBAL_INHERIT')];
		// get all exist layouts
		$layouts = \T6\Helper\Path::files('etc/layout');
		if (!empty($layouts)) {
			foreach ($layouts as $layout) {
				$options[] = (object) ['value' => $layout, 'text' => $layout];
			}
		}

		return $options;
	}
}
 