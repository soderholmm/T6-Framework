<?php

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

$doc = Factory::getDocument();
$rows = $displayData['layout'];
$inputId = $displayData['id'];
$inputName = $displayData['name'];
$inputVal = $displayData['value'];

// add assets
Factory::getDocument()->addScript(T6PATH_ADMIN_URI . '/assets/js/t6layout.js');

use T6Admin\Settings AS Settings;

echo Settings::getRowSettings();
echo Settings::getColSettings();


?>
<div style="display: none">
  <div id="t6-layout-section" class="t6-layout-section" data-sectionid="1" data-cols="1">
  	<div class="t6-section-settings clearfix">
  		<div class="pull-left">
  			<strong class="t6-section-title">Section</strong>
  		</div>
  		<div class="pull-right">
  			<ul class="t6-row-option-list">
  				<li><a class="t6-move-row" href="#" data-tooltip="Move"><i class="fal fa-arrows-alt"></i></a></li>
  				<li><a class="t6-row-options" href="#" data-tooltip="Configure"><i class="fal fa-cog fa-fw"></i></a></li>
  				<li><a class="t6-remove-row" href="#" data-tooltip="Remove"><i class="fal fa-trash-alt fa-fw"></i></a></li>
  			</ul>
  		</div>
  	</div>
  	<div class="t6-row-container ui-sortable">
  		<div class="row ui-sortable">
  			<div class="t6-col t6-layout-col col-md" data-type="block" data-col="12" data-name="none" data-xl="" data-lg="" data-md="" data-sm="" data-xs="">
  				<div class="col-inner clearfix">
  					<span class="t6-column-title">None</span>
  					<span class="t6-col-remove hidden" title="Remove column" data-content="Remove column"><i class="fal fa-minus"></i> </span>
  					<span class="t6-admin-layout-vis" title="Click here to hide this position on current device layout" style="display:none;" data-idx="0"><i class="fal fa-eye"></i></span>
  					<a class="t6-column-options" href="#"><i class="fal fa-cog fa-fw"></i></a>
  				</div>
  			</div>
  		</div>
  	</div>
  	<a class="t6-add-row" href="#"><i class="fal fa-plus"></i><span>Add Row</span></a>
  </div>
</div>
<div class="clearfix"></div>
<!-- Layout Builder Section -->
<div id="t6-layout-builder" class=""></div>

<div class="clearfix"></div>
<input type="hidden" id="<?php echo $inputId; ?>" name="<?php echo $inputName; ?>" class="t6-layouts" value="<?php echo htmlspecialchars($inputVal); ?>">

<!-- modal block custom css -->
<div class="t6-block-css-modal" style="display:none;">
    <div class="t6-modal-overlay"></div>
    <div class="t6-modal t6-block-css-editor" data-target="#">
        <div class="t6-modal-header">
            <span class="t6-modal-header-title"><i class="fal fa-cog"></i>Block Css Editor</span>
            <a href="#" class="action-t6-modal-close"><span class="fal fa-times"></span></a>
        </div>
        <div class="t6-modal-inner t6-css-editor-inner">
            <div class="t6-modal-content tab-pane">
                <textarea id="t6_block_css" name="t6_block_css">block css</textarea>
            </div>
        </div>
        <div class="t6-modal-footer">
            <a href="#" class="btn btn-secondary btn-xs t6-block-css-cancel"><span class="fal fa-times"></span> <?php echo Text::_('JCANCEL');?></a>
            <a href="#" class="btn btn-success btn-xs block-css-editor-apply" data-flag="css-editors"><span class="fal fa-check"></span> <?php echo Text::_('JAPPLY');?></a>
        </div>
    </div>
</div>
