<?php
namespace T6\Document;

use Joomla\CMS\Factory;
use Joomla\Registry\Registry as JRegistry;
use T6\Helper\Path;
use T6\Helper\Layout;

class Preview extends Template {
	var $key;
	var $mode = 'preview';

	public function __construct($doc) {
		parent::__construct($doc);

		$app = Factory::getApplication();
		$this->key = $app->getInput()->get('t6preview');

		define('T6PREVEW', 1);

		// check if current page is assigned to correct template style
		$itemid = $app->getInput()->getInt('Itemid');
		$template = $app->getTemplate(true);
        $db = Factory::getDbo();

        $query = $db->getQuery(true);
        $query->select('template_style_id');
        $query->from( $db->quoteName('#__menu') );
        $query->where( $db->quoteName('id') . ' = ' . $db->quote($itemid) );

        $db->setQuery($query);
        $tid = (int) $db->loadResult();

        if ($tid && $template->id != $tid) {
        	define('T6PREVEW_NOT_ASSIGNED', 1);
        }
	}


	protected function renderHead() {
		parent::renderHead();
		// add preview css
		$preview_css = T6PATH_BASE_URI . '/css/preview.css';
		$this->doc->addStylesheet($preview_css, ['version' => 'auto']);
		$preview_js = T6PATH_BASE_URI . '/js/preview.js';
		$this->doc->addScript($preview_js, ['version' => 'auto']);
	}

	protected function getCachekey() {
		return null;
	}


	protected function getCustomCssFilename() {
		$app = Factory::getApplication('site');
		$template = $app->getTemplate(true);
		return $template->id . '-preview.css';
	}


	protected function loadTypelistData() {
		parent::loadTypelistData();
		// disable optimize
		$this->doc->params->set('system_optimizecss', false);
		$this->doc->params->set('system_optimizejs', false);

		// load preview data
		$draft = \T6Admin\Draft::load($this->key);

		$groups = ['site', 'navigation', 'theme', 'layout'];
		foreach ($groups as $group) {
			// $name = 'typelist-' . $group;
			// $profile = $this->doc->params->get($name);
			$profile = $this->doc->params->get('typelist-' . $group, 'default');
			// check overwrite profile for sub layout
			if ($group == 'layout' && Layout::isSubpage()) {
				$_profile = isset($draft['sub-layout']) ? $draft['sub-layout'] : $this->doc->params->get('sub-layout');
				if ($_profile) {
					$profile = $_profile;
					$draft[$group] = '';
				}
			}

			if (!empty($draft[$group])) {
				$data = $draft[$group];
			} else {
				$content = Path::getFileContent('etc/' . $group . '/' . $profile . '.json');
				if (!$content) {
					$profile = 'default';
					$content = Path::getFileContent('etc/' . $group . '/' . $profile . '.json');
				}
				$data = json_decode($content, true);

			}

			$this->doc->params->set($group . '-settings', new JRegistry($data));

			// add body class
			$this->addBodyClass($group . '-' . str_replace(' ', '-', strtolower($profile)));
		}


	}

}
