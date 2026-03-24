<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Editors.none
 *
 * @copyright   Copyright (C) 2005 - 2026 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

declare(strict_types=1);

// Load Composer autoloader for vendor dependencies
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// Fallback autoloader for T6 namespace (in case Joomla autoload doesn't work)
spl_autoload_register(function ($class) {
    $prefixes = [
        'T6\\' => __DIR__ . '/src/t6/',
        'T6Admin\\' => __DIR__ . '/admin/src/',
    ];
    
    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        
        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
});

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Router\SiteRouter;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseInterface;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use T6\MVC\Router\T6 as T6Router;

/**
 * Plain Textarea Editor Plugin
 *
 * @since  1.5
 */
class PlgSystemT6 extends CMSPlugin
{
	public bool $updatedRef = false;
	public bool $menuChanged = false;
	public ?object $t6 = null;

	public function __construct(&$subject, array $config)
	{
		parent::__construct($subject, $config);
		
		if (!$this->isSite() && !$this->isAdmin()) {
			return;
		}

		$this->t6 = \T6\T6::getInstance();

		// Define constants
		if (!defined('T6_PLUGIN')) {
			define('T6_PLUGIN', $config['name']);
		}
		if (!defined('T6PATH')) {
			define('T6PATH', __DIR__);
		}
		if (!defined('T6PATH_URI')) {
			define('T6PATH_URI', Uri::root(true) . '/plugins/system/' . T6_PLUGIN);
		}
		if (!defined('T6PATH_THEMES')) {
			define('T6PATH_THEMES', T6PATH . '/themes');
		}
		if (!defined('T6PATH_THEMES_URI')) {
			define('T6PATH_THEMES_URI', T6PATH_URI . '/themes');
		}
		if (!defined('T6PATH_MEDIA')) {
			define('T6PATH_MEDIA', JPATH_ROOT . '/media/' . T6_PLUGIN);
		}
		if (!defined('T6PATH_MEDIA_URI')) {
			define('T6PATH_MEDIA_URI', Uri::root(true) . '/media/' . T6_PLUGIN);
		}
		if (!defined('T6PATH_ADMIN')) {
			define('T6PATH_ADMIN', T6PATH . '/admin');
		}
		if (!defined('T6PATH_ADMIN_URI')) {
			define('T6PATH_ADMIN_URI', T6PATH_URI . '/admin');
		}

		$xml = simplexml_load_file(__DIR__ . '/t6.xml');
		if ($xml && !defined('T6VERSION')) {
			define('T6VERSION', (string) $xml->version);
		}

		if ($this->isSite()) {
			T6\T6::fixContentRoute();
		}

		$this->fakeAuthorMenu();
	}

	protected function isSite() {
		return Factory::getApplication()->isClient('site');
	}

	protected function isAdmin() {
		return Factory::getApplication()->isClient('administrator');
	}

	/**
	 * Joomla 6 compatibility: Always true for Joomla 6
	 */
	protected function isJoomla6() {
		return true;
	}

	/**
	 * Handle data process
	 */
	public function onAfterInitialise() {
		if (!$this->isSite()) return;
		if (!$this->params->get('t6author_on', 1)) {
			return;
		}
		$app = Factory::getApplication();
		$mode_sef = $app->get('sef',0);
		$T6Router = new T6Router($app,$app->getMenu());
		// We need to make sure we are always using the site router, even if the language plugin is executed in admin app.

		$router = Factory::getContainer()->get(SiteRouter::class);
		// Attach build rules for SEF.
		$router->attachBuildRule(array($T6Router, 'preprocessBuildRule'), Router::PROCESS_BEFORE);
		if ($mode_sef)
		{
			$router->attachBuildRule(array($T6Router, 'postprocessSEFBuildRule'), Router::PROCESS_AFTER);
			$router->attachBuildRule(array($T6Router, 'buildRule'), Router::PROCESS_BEFORE);
		}

		// Attach parse rule.
		$router->attachParseRule(array($T6Router, 'parseRule'), Router::PROCESS_BEFORE);
	}
	public function onAfterRoute() {
		if (!$this->isSite()) return;
		$this->t6->init();
	}

	public function onAfterDispatch()
	{
		if(!$this->isSite()) return;
		$app = Factory::getApplication();
		$temp = $app->getTemplate(true);
		//check if use t6 template then override layout edit
		if(file_exists(JPATH_ROOT .'/templates/'.$temp->template . '/error-t6.php')){
			//get global params
			$paramsTemp = \T6\Helper\TemplateStyle::loadGlobalParams($temp);
			//get edit option
			$t6EditLayout = $temp->params->get('system_t6frontendedit',1);
			$input = $app->getInput();
			$inedit = $input->get('layout') == 'edit' || ($input->get('option') == 'com_config' && $input->get('view') != 'templates');
			if($inedit && $t6EditLayout){
				$app->set('themes.base', T6PATH_ADMIN);
				$app->set('theme','theme');
			}
		}
	}
	/**
	 * Init T6Admin if T6 template style is editting
	 */
	public function onContentBeforeSave($context, $data, $isNew)
	{
		// Check we are handling the frontend edit form.
		if ($context == 'com_content.form')
		{
			$this->t6->onContentBeforeSave($context, $data, $isNew);
		}
		return true;
	}

	public function onContentPrepareForm($form, $data) {
		if(!$this->isSite() && !$this->isAdmin()) return;
		
		// Joomla 6: No need for Admin::initj3() - removed for Joomla 6 compatibility
		
		$form_name = $form->getName();
		if (!$this->isSite() && $form_name == 'com_templates.style') {
			// load the language
			$this->loadLanguage();

			\T6Admin\Admin::init($form, $data);
		}

		$this->t6->contentPrepareForm($form, $data);
	}
	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $item        The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
 	*/
	public function onContentPrepare($context, $item, $params, $limitstart = 0)
	{
		if(!$this->isSite() && !$this->isAdmin()) return true;
		return $this->t6->onContentPrepare($context, $item, $params, $limitstart);
	}
	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $item        The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterTitle($context, $item, $params, $limitstart = 0)
	{
		return $this->t6->onContentAuthordisplay($context, $item, $params, 'after_title');
	}

	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $item        The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentBeforeDisplay($context, $item, $params, $limitstart = 0)
	{
		return $this->t6->onContentAuthordisplay($context, $item, $params, 'before_content');
	}

	/**
	 * The display event.
	 *
	 * @param   string    $context     The context
	 * @param   stdClass  $item        The item
	 * @param   Registry  $params      The params
	 * @param   integer   $limitstart  The start
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentAfterDisplay($context, $item, $params, $limitstart = 0)
	{
		if ($this->isSite()) {
		    $this->loadPageCss();
		}
		return $this->t6->onContentAuthordisplay($context, $item, $params, 'after_content');
	}

	public function onBeforeCompileHead() {
		if (!$this->isSite() || !\T6\T6::isT6()) return;
		$this->t6->compileHead();
	}


	/**
	 * Clean output html, remove empty column
	 */
	public function onBeforeRender() {
		if (!$this->isSite() || !\T6\T6::isT6()) return;
		$this->t6->beforeRender();
	}

	/**
	 * Clean output html, remove empty column
	 */
	public function onAfterRender() {
		if (!$this->isSite() || !\T6\T6::isT6()) return;
		$this->t6->afterRender();
	}


	/**
	 * Prepare save, make some data modification
	 */
	public function onExtensionBeforeSave($context, $table, $isNew = false) {
		$app = Factory::getApplication();
		$debugEnabled = $app->get('debug', false);
		
		if ($debugEnabled) {
			$app->getLogger()->debug(
				'T6 onExtensionBeforeSave() called',
				['context' => 'system.t6', 'event_context' => $context, 'is_t6' => \T6\T6::isT6()]
			);
		}
		
		if ($context == 'com_templates.style') {
			if(\T6\T6::isT6()){
				if ($debugEnabled) {
					$app->getLogger()->debug(
						'T6 onExtensionBeforeSave() - Calling Params::beforeSave()',
						['context' => 'system.t6', 'table_id' => $table->id ?? 'unknown']
					);
				}
				\T6Admin\Params::beforeSave($table);
			}
		}
	}
	public static function onAfterGetMenuTypeOptions(&$list, $model)
	{
		\T6Admin\T6menutype::onAfterGetMenuTypeOptions($list,$model);
	}
	/* Clean T6 cache */
	public function onExtensionAfterSave($context, $table, $isNew) {
		$app = Factory::getApplication();
		$debugEnabled = $app->get('debug', false);
		
		if ($debugEnabled) {
			$app->getLogger()->debug(
				'T6 onExtensionAfterSave() called',
				['context' => 'system.t6', 'event_context' => $context, 'table_id' => $table->id ?? 'unknown']
			);
		}
		
		if ($context == 'com_templates.style') {
			\T6\Helper\Cache::clean();
			\T6Admin\Draft::clean();
			
			if ($debugEnabled) {
				$app->getLogger()->debug(
					'T6 onExtensionAfterSave() - Cache and draft cleaned',
					['context' => 'system.t6']
				);
			}
		}
	}
	/**
	 * Implement event onRenderModule to include the module chrome provide by T6
	 * This event is fired by overriding ModuleHelper class
	 * Return false for continueing render module
	 *
	 * @param   object &$module   A module object.
	 * @param   array $attribs   An array of attributes for the module (probably from the XML).
	 *
	 * @return  bool
	 */
	function onRenderModule(&$module, $attribs)
	{
		// Joomla 6: Module chrome is handled by WebAssetManager
		return false;
	}


	/**
	 * Implement event to allow select layout from base theme inside plugin.
	 * These events are fireed by overriding Core Joomla lib: FileLayout, HtmlView, ModuleHelper
	*/
	public function onLayoutIncludePaths (&$path) {
		\T6\Helper\Path::addIncludePath($path);
	}
	public function onHtmlViewAddPath ($type, &$path) {
		\T6\Helper\Path::addIncludePath($path);
	}
	public function onGetLayoutPath($path, $layout)
	{
		if (!defined('T6PATH_BASE')) return false;

		$template = Factory::getApplication()->getTemplate();
		if(!$this->isSite()  && !\T6\T6::isCurrentT6()) return false;
		if (strpos($layout, ':') !== false)
		{
			$temp = explode(':', $layout);
			$template = $temp[0] === '_' ? $template : $temp[0];
			$layout = $temp[1];
		}

		$files = [];

		// Detect layout path in T6 base
		$files[] = T6PATH_LOCAL . '/html/' . $path . '/' . $layout . '.php';
		$files[] = T6PATH_TPL . '/html/' . $path . '/' . $layout . '.php';
		$files[] = T6PATH_BASE . '/html/' . $path . '/' . $layout . '.php';

		foreach ($files as $file) {
			if (is_file($file)) return $file;
		}

		return false;
	}


	/* Process Ajax for T6 Admin */
	public function onAjaxT6(){
		// load the language
		$this->loadLanguage();
		// Clean T6 cache
		\T6\Helper\Cache::clean();
		// Saving
		\T6Admin\Action::run();
	}

	/* Clean media cache */
	public function onAfterPurge($group = null) {
		if (($group == 't6'|| $group == '') && is_dir(T6PATH_MEDIA)) {
			Folder::delete(T6PATH_MEDIA);
		}
	}

	protected function fakeAuthorMenu()
	{
		$origin = JPATH_PLUGINS . '/system/t6/themes/base/html/com_content/author/list.xml';
		$originTime = filemtime($origin);
		$targetFile = JPATH_ROOT . '/components/com_content/tmpl/author/list.xml';

		if (!is_file($targetFile) || filemtime($targetFile) < $originTime) {
			$content = file_get_contents($origin);
			File::write($targetFile, $content);
		}
	}

	/**
     * Check if article is a T6 builder page
     * 
     * @param int $articleId The article ID to check
     * @return string|null CSS content or null if not found
     * @throws \RuntimeException If database query fails
     */
    protected function isT6builderPage(int $articleId): ?string
    {
        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);
            $tables = $db->getTableList();
            $dbPrefix = Factory::getApplication()->get('dbprefix', '');
            
            if (!in_array($dbPrefix . 'jae_item', $tables)) {
                return null;
            }
            
            $query = $db->getQuery(true)
                ->select($db->quoteName('css'))
                ->from($db->quoteName('#__jae_item'))
                ->where($db->quoteName('asset_id') . ' = :articleId')
                ->where($db->quoteName('asset_name') . ' = :assetName')
                ->bind(':articleId', $articleId, \PDO::PARAM_INT)
                ->bind(':assetName', 'jform.articletext', \PDO::PARAM_STR);
            
            $db->setQuery($query);
            $result = $db->loadResult();
            
            return $result !== null ? (string) $result : null;
        } catch (\Throwable $e) {
            // Log error but don't break the page
            Factory::getApplication()->getLogger()->error(
                'T6 Builder Page Check Failed: ' . $e->getMessage(),
                ['context' => 'system.t6', 'articleId' => $articleId]
            );
            return null;
        }
    }

	/**
	 *
	 * @return null
	 * @throws Exception
	 * @since version
	 */
    protected function loadPageCss()
    {
		$app = Factory::getApplication();
		$input = $app->getInput();
		if ($input->get('option') !== 'com_content') {
			return ;
		}
		$articleId = $input->get('id');
		if (!$articleId) return ;

        $css = self::isT6builderPage($articleId);
        if (!$css || strtolower($css) === 'null') return ;

        $doc = Factory::getDocument();
        $doc->addStyleDeclaration($css);
    }
}