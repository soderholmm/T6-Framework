<?php

$params = $displayData->params;
$blockname = isset($params['blockname']) ? $params['blockname'] : '';
$doc = $displayData->doc;

$block_content = \T6\Helper\Block::loadShareBlock($blockname);

// parse <style>
$block_content = preg_replace_callback('/<style\s*[^>]*>(.*)<\/style>/ims', function ($styles) use ($doc) {
	$doc->addStyleDeclaration ($styles[1]);
	return '';
}, $block_content);

echo \T6\Helper\Html::renderJdoc($block_content);