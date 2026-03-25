<?php
/*
 * @Copyright: (C) 2026 Ab Söderholms IT-tjänster Oy
 * @Author: Soderholmm
 */
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace T6\Renderer;

defined('_JEXEC') or die;

use Joomla\CMS\Document\DocumentRenderer;
use Joomla\CMS\Layout\LayoutHelper;

/**
 * HTML document renderer for the document `<head>` element
 *
 * @since  3.5
 */
class Element extends DocumentRenderer
{
	public function render($name, $params = array(), $content = null)
	{
		$data = (object) [
			'doc' => $this->_doc,
			'name' => $name,
			'content' => $content,
			'params' => $params
		];

		return LayoutHelper::render('t6.element.' . $name, $data);
	}

}
 