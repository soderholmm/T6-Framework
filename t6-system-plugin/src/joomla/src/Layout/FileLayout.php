<?php
/*
 * @Copyright: (C) 2026 Ab Söderholms IT-tjänster Oy
 * @Author: Soderholmm
 */
/**
 * Joomla! Content Management System
 *
 * @copyright  Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Layout;

defined('_JEXEC') or die;

// Make alias of original FileLayout
\T6\Helper\Joomla::makeAlias(JPATH_LIBRARIES . '/src/Layout/FileLayout.php', 'FileLayout', '_JFileLayout');

// Override original FileLayout to trigger event when find layout
class FileLayout extends _JFileLayout
{
    public function getDefaultIncludePaths()
    {
        $path = parent::getDefaultIncludePaths();

        //Trigger event, then can alter include paths
        \Joomla\CMS\Factory::getApplication()->triggerEvent('onLayoutIncludePaths', array(&$path));

        return $path;
    }

    public function getSuffixes()
    {
        $suffixes = parent::getSuffixes();
        if (\T6\Helper\J3J4::isJ3()) {
            if (!$suffixes) $suffixes = [];
            array_unshift($suffixes, 'j3');
        }
        return $suffixes;
    }
}
 