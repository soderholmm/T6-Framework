<?php

declare(strict_types=1);

namespace T6;

use Joomla\CMS\Factory;
use Joomla\Registry\Registry;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;

class T6
{
	protected ?\T6\Document\Template $doc = null;

	public function __construct()
	{
	}

	public static function getInstance($doc = null): self
	{
		static $t6 = null;
		if (!$t6) {
			$t6 = new self();
		}
		if ($doc) {
			$t6->setDocument($doc);
		}
		return $t6;
	}

	public static function fixContentRoute(): void
	{
		$loader = require JPATH_LIBRARIES . '/vendor/autoload.php';

		$classMap = $loader->getClassMap();
		$classMap['Joomla\Component\Content\Site\Service\Router'] = T6PATH . '/src/t6/MVC/Router/Content/Router.php';

		$loader->addClassMap($classMap);
	}
	
	/**
	 * Magic method to proxy T6 method calls to T6\Document\Template
	 *
	 * @param   string  $name       Name of the function
	 * @param   array   $arguments  Array of arguments for the function
	 *
	 * @return  mixed
	 *
	 * @since   1.7.0
	 */
	public function __call(string $name, array $arguments): mixed
	{
		return $this->getDocument()->{$name}(...$arguments);
	}

	public function init(): void
	{
		// Check base theme
		$template = Factory::getApplication()->getTemplate();
		// parse xml
		$filePath = JPATH_THEMES . '/' . $template . '/templateDetails.xml';
		$base = null;
		$bs5 = null;
		if (is_file($filePath)) {
			$xml = simplexml_load_file($filePath);
			// check t6
			if (isset($xml->t6) && isset($xml->t6->basetheme)) {
				$base = trim(strtolower((string) $xml->t6->basetheme));
			}
		}

		// not an T6 template, ignore
		if (!$base) return;
		// define load bootstrap 4 | 5 on template
		$bs5 = trim(strtolower((string) $xml->t6->bootstrap));
		if ($bs5 === 'bs5' && !defined('T6_BS5')) {
			define('T6_BS5', 1);
		}
		// validate base
		$path = T6PATH_THEMES . '/' . $base;
		if (!is_dir($path)) return;

		// define const
		if (!defined('T6PATH_BASE')) {
			define('T6PATH_BASE', T6PATH_THEMES . '/' . $base);
		}
		if (!defined('T6PATH_BASE_URI')) {
			define('T6PATH_BASE_URI', T6PATH_THEMES_URI . '/' . $base);
		}

		// define template const
		$tpl_path = '/templates/' . $template;
		if (!defined('T6PATH_TPL')) {
			define('T6PATH_TPL', JPATH_ROOT . $tpl_path);
		}
		if (!defined('T6PATH_TPL_URI')) {
			define('T6PATH_TPL_URI', Uri::root(true) . $tpl_path);
		}
		// define local const
		if (!defined('T6PATH_LOCAL')) {
			define('T6PATH_LOCAL', T6PATH_TPL . '/local');
		}
		if (!defined('T6PATH_LOCAL_URI')) {
			define('T6PATH_LOCAL_URI', T6PATH_TPL_URI . '/local');
		}

		// overwrite original Joomla
		$loader = require JPATH_LIBRARIES . '/vendor/autoload.php';
		// update class maps
		$classMap = $loader->getClassMap();
		$classMap['Joomla\CMS\Layout\FileLayout'] = T6PATH . '/src/joomla/src/Layout/FileLayout.php';
		$classMap['Joomla\CMS\Helper\ModuleHelper'] = T6PATH . '/src/joomla/src/Helper/ModuleHelper.php';
		$classMap['Joomla\CMS\MVC\View\HtmlView'] = T6PATH . '/src/joomla/src/MVC/View/HtmlView.php';
		$classMap['Joomla\Component\Content\Site\View\Author\HtmlView'] = T6PATH . '/src/t6/MVC/View/Author/HtmlView.php';
		$classMap['Joomla\Component\Content\Site\Model\AuthorModel'] = T6PATH . '/src/t6/MVC/Model/AuthorModel.php';

		$loader->addClassMap($classMap);

		// Register renderer alias for backward compatibility
		if (!class_exists('JDocumentRendererHtmlElement', false)) {
			class_alias(\T6\Renderer\Element::class, 'JDocumentRendererHtmlElement');
		}
	}

	public function getDocument($doc = null): \T6\Document\Template
	{
		if (!$this->doc) {
			$this->doc = \T6\Document\Template::getInstance($doc);
		}
		return $this->doc;
	}

	public function setDocument($doc): void
	{
		$this->doc = \T6\Document\Template::getInstance($doc);
	}

	public function renderTemplate($doc = null): void
	{
		$doc = $this->getDocument($doc);
		echo $doc->render();
	}

	public function compileHead(): void
	{
		if (!$this->isT6()) return;
		$this->getDocument()->compileHead();
		Optimizer\Base::run();
	}

	// Build default settings in default template style
	public function buildTemplateParams(): void
	{
		$app = Factory::getApplication();
		$template = $app->getTemplate(true);
		$defaultTpl = Helper\TemplateStyle::getMaster($template->template);

		if ($template->id != $defaultTpl->id) {
			Helper\TemplateStyle::updateDefaultSettings($defaultTpl, $template);
		}

		Helper\TemplateStyle::initDefault($template);
	}


	public function contentPrepareForm($form, $data): void
	{
		if (($this->isSite() && !$this->isT6())) return;
		Helper\ExtraField::extendForm($form, $data);
	}

	public function onContentPrepare(string $context, &$article, &$params, int $page = 0): bool
	{
		if ($this->isSite() && !$this->isT6()) return true;
		$this->loadBSComponent($article);
		return $this->renderOpenGraph($context, $article, $params);
	}

	public function loadBSComponent($article): void
	{
		$buffer = $article->text ?? '';

		if (!$buffer) {
			return;
		}

		$wam = Factory::getDocument()->getWebAssetManager();
		$buffer = str_replace(
			['data-toggle', 'data-title', 'data-dismiss', 'data-trigger', 'data-target', 'data-slide', 'data-ride', 'data-interval'],
			['data-bs-toggle', 'data-bs-title', 'data-bs-dismiss', 'data-bs-trigger', 'data-bs-target', 'data-bs-slide', 'data-bs-ride', 'data-bs-interval'],
			$buffer
		);
		if (preg_match('/data-bs-toggle="tab"/mi', $buffer)) {
			$wam->useScript('bootstrap.tab');
		}
		if (preg_match('/data-bs-ride="carousel"/mi', $buffer)) {
			$wam->useScript('bootstrap.carousel');
		}
		if (preg_match('/data-bs-toggle="collapse"/mi', $buffer)) {
			$wam->useScript('bootstrap.collapse');
		}
	}

	public function isSite(): bool
	{
		return Factory::getApplication()->isClient('site');
	}

	public function onContentBeforeSave(string $context, $data, bool $isNew): void
	{
		if (!$this->isT6()) return;
		Helper\ExtraField::onContentBeforeSave($context, $data, $isNew);
	}

	/**
	 * Static function
	 */
	public static function isT6(): bool
	{
		return defined('T6PATH_BASE');
	}

	public static function isCurrentT6(): bool
	{
		$app = Factory::getApplication();
		$tmpId = $app->getInput()->getInt('id', '');
		if (empty($tmpId)) return false;
		return Helper\TemplateStyle::checkCurrentT6template($tmpId);
	}

	// Alias function
	public static function render($doc = null): void
	{
		$t6 = self::getInstance($doc);
		$t6->renderTemplate($doc);
	}

	public static function inEdit(): bool
	{
		$input = Factory::getApplication()->getInput();
		return $input->get('layout') === 'edit' || ($input->get('option') === 'com_config' && $input->get('view') !== 'templates');
	}

	/**
	 * Performs the display event.
	 *
	 * @param   string    $context      The context
	 * @param   \stdClass  $item         The item
	 * @param   Registry  $params       The params
	 * @param   string    $displayType  The type
	 *
	 * @return  string
	 *
	 * @since   3.7.0
	 */
	public function onContentAuthordisplay(string $context, $item, $params, string $displayType): string
	{
		if ($context !== 'com_content.article' || !$this->isT6()) return '';
		$app = Factory::getApplication();
		$template = $app->getTemplate(true);
		$author = Helper\Author::render($item, $params, $displayType, $template->params);

		return $author ?? '';
	}

	public function renderOpenGraph(string $context, $item, $params): bool
	{
		if (!$this->isT6()) return true;
		Helper\Metadata::renderOpenGraph($context, $item, $params);
		return true;
	}
}
