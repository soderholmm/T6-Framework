<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Editors.none
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use T6\Document\Edit as T6Edit;
/** @var JDocumentHtml $this */

$app  = Factory::getApplication();
$user = Factory::getUser();

?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{t6:language}" lang="{t6:language}" dir="{t6:direction}">

<head>
  <!--[if lt IE 9]>
    <script src="<?php echo Uri::root(true); ?>/media/jui/js/html5.js"></script>
  <![endif]-->
  <meta name="viewport"  content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes" />
  <style>
    @-webkit-viewport { width: device-width; } @-moz-viewport { width: device-width; } @-ms-viewport { width: device-width; } @-o-viewport { width: device-width; } @viewport { width: device-width; }
  </style>
  <meta name="HandheldFriendly" content="true" />
  <meta name="apple-mobile-web-app-capable" content="YES" />
  <jdoc:include type="head" />
  <link rel="stylesheet" href="<?php echo T6PATH_ADMIN_URI ?>/theme/css/style.css">
  <link rel="stylesheet" href="<?php echo T6PATH_BASE_URI ?>/vendors/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo T6PATH_BASE_URI ?>/vendors/font-awesome5/css/all.min.css">
  {t6post:EditCss}
  <script src="<?php echo T6PATH_BASE_URI ?>/js/frontend-edit.js"></script>
  <script src="<?php echo T6PATH_ADMIN_URI ?>/theme/js/script.js"></script>
</head>

<body class="{t6post:bodyclass} t6-edit-layout">
  <!-- Header -->
  <header class="t6-header">
    <div class="container">
      <span class="brand">
        {t6post:logoedit}
      </span>
    </div>
  </header>
  <!-- // Header -->

  <!-- Main body -->
  <div class="t6-mainbody">
    <div class="container">
      <jdoc:include type="message" />
      <jdoc:include type="component" />
    </div>
  </div>
  <!-- // Main body -->

  <!-- Footer -->
  <footer class="t6-footer">
    <div class="container">
      <p>Copyright &copy; <?php echo date("Y"); ?> <?php echo Factory::getConfig()->get('sitename');?>. All Rights Reserved</p>
    </div>
  </footer>
  <!-- // Footer -->
</body>

</html>
