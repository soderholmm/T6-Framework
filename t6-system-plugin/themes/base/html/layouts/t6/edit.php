<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{t6:language}" lang="{t6:language}" dir="{t6:direction}">

<head>
  {t6:system_advancedCodeAfterHead}
  {t6post:head}

  <!--[if lt IE 9]>
    <script src="<?php

use Joomla\CMS\Uri\Uri;

 echo Uri::root(true); ?>/media/jui/js/html5.js"></script>
  <![endif]-->
  <meta name="viewport"  content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes"/>
  <style>
    @-webkit-viewport   { width: device-width; }
    @-moz-viewport      { width: device-width; }
    @-ms-viewport       { width: device-width; }
    @-o-viewport        { width: device-width; }
    @viewport           { width: device-width; }
  </style>
  <meta name="HandheldFriendly" content="true"/>
  <meta name="apple-mobile-web-app-capable" content="YES"/>
  <!-- //META FOR IOS & HANDHELD -->
  {t6:system_advancedCodeBeforeHead}
</head>

<body class="{t6post:bodyclass} t6-edit-layout">
  {t6:system_advancedCodeAfterBody}
  {t6:offcanvas}
  <div class="t6-wrapper">
    <div class="t6-content">
      <div class="t6-content-inner">
        {t6:body}
      </div>
    </div>
  </div>
  {t6:system_advancedCodeBeforeBody}
</body>
</html>
