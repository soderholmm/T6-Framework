<?php
namespace T6Admin;

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;

class Params {
	public static function load($form, $data) {
		$tplXml = T6PATH_TPL . '/templateDetails.xml';

		//wrap
		$form = new T6form($form);

		//remove all fields from group 'params' and reload them again in right other base on template.xml
		$form->removeGroup('params');
		//load the template
		$form->loadFile(T6PATH_BASE . '/params/template.xml');
		//overwrite / extend with params of template
		$form->loadFile($tplXml, true, '//config');

		if (empty($data)) return;

		\T6\Helper\TemplateStyle::loadGlobalParams($data);

		// // update data
		// $defaultTpl = \T6\Helper\TemplateStyle::getMaster($data->template);

		// if ($data->id != $defaultTpl->id) {
		// 	\T6\Helper\TemplateStyle::updateDefaultSettings($defaultTpl, $data);
		// }

		// \T6\Helper\TemplateStyle::initDefault($data);

		// define('T6AMIN_DEFAULT', $data->id == $defaultTpl->id);
		// define('T6AMIN_DEFAULT_ID', $defaultTpl->id);
	}

	public static function beforeSave($table) {
		$app = Factory::getApplication();
		$debugEnabled = $app->get('debug', false);
		
		// Debug logging
		if ($debugEnabled) {
			$app->getLogger()->debug(
				'T6 Params::beforeSave() called',
				['context' => 'system.t6', 'table_id' => $table->id ?? 'unknown']
			);
		}
		
		$params = is_string($table->params) ? new Registry($table->params) : $table->params;
		
		if ($debugEnabled) {
			$app->getLogger()->debug(
				'T6 Params::beforeSave() - Original params',
				['context' => 'system.t6', 'params_keys' => array_keys($params->toArray())]
			);
		}

		// Merge draft data into params before saving
		$token = \Joomla\CMS\Session\Session::getFormToken();
		$id = $app->input->getInt('id');
		$draftKey = Draft::makekey($token, $id);
		$draftData = Draft::load($draftKey);
		
		if ($debugEnabled) {
			$app->getLogger()->debug(
				'T6 Params::beforeSave() - Draft data',
				['context' => 'system.t6', 'draft_key' => $draftKey, 'draft_data' => $draftData]
			);
		}
		
		if (!empty($draftData)) {
			// Merge draft data into params
			foreach ($draftData as $type => $draftValues) {
				if (is_array($draftValues)) {
					foreach ($draftValues as $name => $value) {
						$params->set($name, $value);
					}
				}
			}
			
			// Clear draft data after merging
			Draft::clear($id, $token);
			
			if ($debugEnabled) {
				$app->getLogger()->debug(
					'T6 Params::beforeSave() - Draft data merged',
					['context' => 'system.t6', 'merged_keys' => array_keys($params->toArray())]
				);
			}
		}

		// save global params
		$props = array_keys($params->toArray());

		$data = [];
		foreach($props as $name) {
			if (preg_match ('/^system(_|$)/', $name)) {
				$data[$name] = $params->get($name);
			}
		}
		\T6\Helper\Path::saveLocalContent('etc/global.json', json_encode($data));
		
		if ($debugEnabled) {
			$app->getLogger()->debug(
				'T6 Params::beforeSave() - Global params saved to file',
				['context' => 'system.t6', 'global_params' => $data]
			);
		}

		// Update params back to table
		$table->params = $params->toString();
		
		if ($debugEnabled) {
			$app->getLogger()->debug(
				'T6 Params::beforeSave() - Final params',
				['context' => 'system.t6', 'final_params_length' => strlen($table->params)]
			);
		}
	}


	protected static function getTemplateParams($id) {
		if (!$id) return null;

        $db = Factory::getDbo();

        $query = $db->getQuery(true);
        $query->select('params');
        $query->from( $db->quoteName('#__template_styles') );
        $query->where( $db->quoteName('id') . ' = ' . $db->quote($id) );

        $db->setQuery($query);
        return $db->loadResult();
	}

	protected static function saveTemplateParams($id, $params) {
		if (!$id) return null;

    $db = Factory::getDbo();

    $query = $db->getQuery(true);
    $query->update( $db->quoteName('#__template_styles') );
    $query->set($db->quoteName('params') . ' = ' . $db->quote($params));
    $query->where( $db->quoteName('id') . ' = ' . $db->quote($id) );

    $db->setQuery($query);
    return $db->execute();
	}

}
