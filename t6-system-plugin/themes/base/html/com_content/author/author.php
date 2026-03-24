<?php
/**
T6 Overide
 */

use Joomla\CMS\Factory;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use T6\Helper\Layout;

defined('_JEXEC') or die;
if(!class_exists('ContentHelperRoute')){
	abstract class ContentHelperRoute extends RouteHelper{};
}

$app = Factory::getApplication();

// process for ajax pagination, just get items content and return to browser
$isAjax = $app->input->get('loadpage') !== null;
if ($isAjax) {
	echo $this->loadTemplate('posts');
	exit;
}
?>
<div class="author-detail">
	<?php echo LayoutHelper::render('t6.content.author_info', ["author"=> $this->author,"link"=>false, "class"=> "author-block-posts"]); ?>
</div>

<?php if(count($this->article_items)): ?>

<div class="blog author-posts" itemscope itemtype="https://schema.org/Blog">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
		</div>
	<?php endif; ?>

	<?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
		<h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
			<?php if ($this->params->get('show_category_title')) : ?>
				<span class="subheading-category"><?php echo $this->category->title; ?></span>
			<?php endif; ?>
		</h2>
	<?php endif; ?>

	<?php if ($this->params->get('show_cat_tags', 1) && !empty($this->category->tags->itemTags)) : ?>
		<?php $this->category->tagLayout = new Layout('joomla.content.tags'); ?>
		<?php echo $this->category->tagLayout->render($this->category->tags->itemTags); ?>
	<?php endif; ?>
	
	<?php echo $this->loadTemplate('posts');?>
	
	<?php echo LayoutHelper::render('t6.content.pagination', ["params"=> $this->params,'pagination'=> $this->pagination,'elem'=>'.author-posts > .content-item']); ?>
</div>
<?php else: ?>
	<div class="alert alert-info mt-3 mb-5">No articles post</div>
<?php endif ?>
