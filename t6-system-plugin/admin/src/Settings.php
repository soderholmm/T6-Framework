<?php

namespace T6Admin;

defined('_JEXEC') or die();

use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use T6Admin\T6form as T6form;

class Settings
{
    private static function getInputElements($key, $field)
    {
        return call_user_func(array( 'T6Field' . ucfirst($key), 'getInput'), $key, $field);
    }
    public static function getRowSettings()
    {
        $tplXml = T6PATH_TPL . '/templateDetails.xml';
        // new form
        $xmlfile = \T6\Helper\Path::findInTheme('params/layout.xml');
        $form = Form::getInstance('t6layout', $xmlfile);
        // if ($xmlfile) $form->loadFile($xmlfile);
        $form = new T6form($form);
        //remove all fields from group 'params' and reload them again in right other base on template.xml
        $form->removeGroup('t6layout');
        //load the template
        $form->loadFile($xmlfile);
        //overwrite / extend with params of template
        $form->loadFile($tplXml, true, '//layouts');
        $fieldsets = $form->getFieldsets();
        $output = '<div class="t6-row-settings" style="display:none;" >';
        $output .= '<div class="t6-modal-overlay"></div>';
        $output .= '<div class="t6-modal t6-row-setting" data-target="#">';

        $output .= '<div class="t6-modal-header">';
        $output .= '<span class="t6-modal-header-title"><i class="fal fa-cog"></i>Row Options</span>';
        $output .= '<a href="#" class="action-t6-modal-close"><span class="fal fa-times"></span></a>';
        $output .= '</div>';

        $output .= '<div class="t6-modal-inner t6-row-inner"></div>';
        $output .= '<div class="t6-modal-content t6-modal-row">';
        $output .= '<ul class="nav nav-tabs mb-3" role="tablist">';
        $outputs = '<div class="tab-content" id="pills-tabContent">';
        // $options = array();
        foreach ($fieldsets as $key => $fieldset) {
            if (count($fieldsets) > 1) {
                $output .= self::renderFieldsetStart($fieldset);
            }
            $fields = $form->getFieldset($key);
            $fieldArray = array();
            foreach ($fields as $key => $field) {
                $group = $field->getAttribute('group') ? $field->getAttribute('group') : 'no-group';
                $type = $field->getAttribute('type');
                $filed_html = self::renderInputField($field, $group);
                $fieldArray[$group]['fields_html'][] = $filed_html;
            }
            $outputs .= self::renderGroups($fieldArray);
        }
        $outputs .= '</div>';
        $output .= '</ul>';
        $output .= $outputs;
        $output .= '</div>';
        $output .= '<div class="t6-modal-footer">';
        $output .= '<a href="#" class="btn btn-secondary btn-xs t6-settings-cancel"><span class="fal fa-times"></span> Cancel</a>';
        $output .= '<a href="#" class="btn btn-success btn-xs t6-settings-apply" data-flag="row-setting"><span class="fal fa-check"></span> Apply</a>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }

    public static function getColSettings()
    {
        $tplXml = T6PATH_TPL . '/templateDetails.xml';
        // new form
        $xmlfile = \T6\Helper\Path::findInTheme('params/layoutCol.xml');
        $form = Form::getInstance('t6layoutCol', $xmlfile);
        // if ($xmlfile) $form->loadFile($xmlfile);
        $form = new T6form($form);
        //remove all fields from group 'params' and reload them again in right other base on template.xml
        $form->removeGroup('t6layoutCol');
        $form->removeGroup('t6layout');
        //load the template
        $form->loadFile($xmlfile);
        //overwrite / extend with params of template
        $form->loadFile($tplXml, true, '//layouts');

        $fieldsets = $form->getFieldsets();

        $output = '<div class="t6-cols-settings" style="display:none;">';
        $output .= '<div class="t6-modal-overlay"></div>';
        $output .= '<div class="t6-modal t6-cols-setting" data-target="#">';
        $output .= '<div class="t6-modal-header">';
        $output .= '<span class="t6-modal-header-title"><i class="fal fa-cog"></i>'.Text::_('T6_LAYOUT_COL_SETTINGS').'</span>';
        $output .= '<span class="t6-modal-header-title t6-edit-block-title" style="display:none;"><i class="fal fa-cog"></i>Edit bLock</span>';
        $output .= '<a href="#" class="action-t6-modal-close"><span class="fal fa-times"></span></a>';
        $output .= '<a href="#" class="t6-modal-block-close"  style="display:none;"><span class="fal fa-times"></span></a>';
        $output .= '</div>';
        $output .= '<div class="t6-modal-inner t6-cols-inner">';
        $outputs = '<div class="tab-content t6-modal-content t6-modal-col" id="pills-tabContent">';

        foreach ($fieldsets as $key => $fieldset) {
            //only one tab show
            /*if(count($fieldsets) > 1){
                $output .= self::renderFieldsetStart($fieldset);
            }*/

            $fields = $form->getFieldset($key);

            $fieldArray = array();
            foreach ($fields as $key => $field) {
                $group = $field->getAttribute('group') ? $field->getAttribute('group') : 'no-group';
                $type = $field->getAttribute('type');
                $filed_html = self::renderInputField($field, $group);
              
                $fieldArray[$group]['fields_html'][] = $filed_html;
            }
            $outputs .= self::renderGroups($fieldArray);
        }
        $output .= $outputs;
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="t6-modal-footer">';
        $output .= '<button type="button" class="btn btn-secondary btn-xs t6-settings-cancel"><span class="fal fa-times"></span> Cancel</button>';
        $output .= '<button type="button" class="btn btn-success btn-xs t6-settings-apply" data-flag="column-setting"><span class="fal fa-check"></span> <span class="btn-text">Apply</span></button>';
        $output .= '</div>';
        $output .= '<div class="t6-modal-footer t6-edit-block-footer"  style="display:none;">';
        $output .= '<button type="button" class="btn btn-secondary btn-xs t6-edit-block-remove" data-local="0"><span class="fal fa-trash-alt"></span>Delete Block</button>';
        $output .= '<button type="button" class="btn btn-secondary btn-xs t6-edit-block-cancel"><span class="fal fa-times"></span> Cancel</button>';
        $output .= '<button type="button" class="btn btn-success btn-xs t6-edit-block-save"><span class="fal fa-check"></span> <span class="btn-text">Save</span></button>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }
    public static function getMegaRowConfig()
    {
        $tplXml = T6PATH_TPL . '/templateDetails.xml';
        // new form
        $xmlfile = \T6\Helper\Path::findInTheme('params/megamenu.xml');
        $form = Form::getInstance('megaRow', $xmlfile);
        // if ($xmlfile) $form->loadFile($xmlfile);
        $form = new T6form($form);
        //remove all fields from group 'params' and reload them again in right other base on template.xml
        $form->removeGroup('megaRow');
        //load the template
        $form->loadFile($xmlfile);
        //overwrite / extend with params of template
        $form->loadFile($tplXml, true, '//layouts');
        $fieldsets = $form->getFieldsets('megaRow');
        $output = '<div class="t6-mega-row-modal"  data-target="#" style="display:none;">';
        $output .= '<div class="t6-modal-overlay"></div>';
        $output .= '<div class="t6-modal t6-mega-row">';
        $output .= '<div class="t6-modal-header">';
        $output .= '<span class="t6-modal-header-title"><i class="fal fa-cog"></i>Menu row options</span>';
        $output .= '<a href="#" class="action-t6-modal-close"><span class="fal fa-times"></span></a>';
        $output .= '</div>';
        $output .= '<div class="t6-modal-inner t6-mega-inner">';
        $outputs = '<div class="tab-content t6-modal-content" id="pills-tabContent">';
        // $options = array();
        foreach ($fieldsets as $key => $fieldset) {
            if (count($fieldsets) > 1) {
                $output .= self::renderFieldsetStart($fieldset);
            }

            $fields = $form->getFieldset($key);

            $fieldArray = array();
            foreach ($fields as $key => $field) {
                $group = $field->getAttribute('group') ? $field->getAttribute('group') : 'no-group';
                $type = $field->getAttribute('type');
                $filed_html = self::renderInputField($field, $group);
              
                $fieldArray[$group]['fields_html'][] = $filed_html;
            }
            $outputs .= self::renderGroups($fieldArray);
        }
        $output .= $outputs;
        $output .= '</div>';
        $output .= '</div>';

        $output .= '<div class="t6-modal-footer">';
        $output .= '<a href="#" class="btn btn-secondary btn-xs t6-settings-cancel"><span class="fal fa-times"></span> Cancel</a>';
        $output .= '<a href="#" class="btn btn-success btn-xs t6-menu-settings-apply" data-flag="mega-setting"><span class="fal fa-check"></span> Apply</a>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }
    public static function getMegaItemSettings()
    {
        $tplXml = T6PATH_TPL . '/templateDetails.xml';
        // new form
        $xmlfile = \T6\Helper\Path::findInTheme('params/megamenu.xml');
        $form = Form::getInstance('megaCol', $xmlfile);
        // if ($xmlfile) $form->loadFile($xmlfile);
        $form = new T6form($form);
        //remove all fields from group 'params' and reload them again in right other base on template.xml
        $form->removeGroup('megaCol');
        //load the template
        $form->loadFile($xmlfile);
        //overwrite / extend with params of template
        $form->loadFile($tplXml, true, '//megamenu');
        $fieldsets = $form->getFieldsets('megaCol');
        $output = '<div class="t6-mega-item-modal" data-target="#" style="display:none;">';
        $output .= '<div class="t6-modal-overlay"></div>';
        $output .= '<div class="t6-modal t6-mega-item">';
        $output .= '<div class="t6-modal-header">';
        $output .= '<span class="t6-modal-header-title"><i class="fal fa-cog"></i>Menu Item options</span>';
        $output .= '<a href="#" class="action-t6-modal-close"><span class="fal fa-times"></span></a>';
        $output .= '</div>';
        $output .= '<div class="t6-modal-inner t6-mega-item-inner">';
        $outputs = '<div class="tab-content t6-modal-content" id="pills-tabContent">';
        // $options = array();
        foreach ($fieldsets as $key => $fieldset) {
            if (count($fieldsets) > 1) {
                $output .= self::renderFieldsetStart($fieldset);
            }
            $fields = $form->getFieldset($key);

            $fieldArray = array();
            foreach ($fields as $key => $field) {
                $group = $field->getAttribute('group') ? $field->getAttribute('group') : 'no-group';
                $type = $field->getAttribute('type');
                $filed_html = self::renderInputField($field, $group);
              
                $fieldArray[$group]['fields_html'][] = $filed_html;
            }
            $outputs .= self::renderGroups($fieldArray);
        }
        $output .= $outputs;
        $output .= '</div>';
        $output .= '</div>';
        $output .= '<div class="t6-modal-footer">';
        $output .= '<a href="#" class="btn btn-secondary btn-xs t6-settings-cancel"><span class="fal fa-times"></span> Cancel</a>';
        $output .= '<a href="#" class="btn btn-success btn-xs t6-menu-settings-apply" data-flag="item-setting"><span class="fal fa-check"></span> Apply</a>';
        $output .= '</div>';
        $output .= '</div>';
        $output .= '</div>';
        return $output;
    }
    public static function renderFieldsetStart($fieldset)
    {
        $html  = '<li class="nav-item t6-group t6-group-'.$fieldset->name.'">';
        $html .= '<a class="nav-link" id="'.$fieldset->name.'-tab" data-toggle="pill" href="#'.$fieldset->name.'" role="tab" aria-controls="'.$fieldset->name.'" aria-selected="false">'. Text::_($fieldset->label) .'</a>';
        $html .= '</li>';

        return $html;
    }
    public static function renderFieldsetEnd()
    {
        return '</div></div>';
    }


    public static function renderGroups($groups)
    {
        $html = '';
        foreach ($groups as $key => $group) {
            if ($key != 'no-group') {
                $html .= self::renderGroupStart($key);
            }

            $html .= self::getFields($group['fields_html']);

            if ($key != 'no-group') {
                $html .= self::renderGroupEnd();
            }
        }

        return $html;
    }

    public static function renderGroupStart($group)
    {
        if ($group == 'col') {
            $group = 'general';
        }
        $html  = '<div id="'.$group.'" class="tab-pane t6-group-list-'.$group.' active">';
        $html .= '<div class="row">';

        return $html;
    }

    public static function renderGroupEnd()
    {
        return '</div></div>';
    }

    public static function getFields($fields)
    {
        $html = '';
        foreach ($fields as $field) {
            $html .= $field;
        }

        return $html;
    }

    public static function renderInputField($field = '', $group = '')
    {
        $showon = $field->getAttribute('showon');
        $attribs = '';
        $field_html = '';
        $field_html .= '<div class="control-group ' . $field->getAttribute('class_field') . '"'. $attribs .'>';
        
        $field_html .= '<div class="control-group-inner">';
        if (!$field->getAttribute('hideLabel')) {
			$label = !empty($field->label) ? $field->label : '';
            $field_html .= '<div class="control-label">' . str_replace('</label>', ' <span class="hasTooltip fal fa-question-circle"></span></label>', $label) .'</div>';
        }
        $field_html .= '<div class="controls">';
        $field_html .= $field->input;
        $field_html .= '</div>';
        $field_html .= '</div>';
        $field_html .= '</div>';

        return $field_html;
    }
}
