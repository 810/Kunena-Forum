<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Crypsis
 * @subpackage      Layout.Category
 *
 * @copyright       Copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;

$categoryActions = $this->getCategoryActions();
$cols            = empty($this->checkbox) ? 5 : 6;
$this->addStyleSheet('assets/css/rating.css');
?>

<?php if ($this->category->headerdesc) : ?>
	<div class="clearfix"></div>
	<br>
	<h1 class="alert alert-info">
		<a class="close" data-dismiss="alert" href="#"></a>
		<?php echo $this->category->displayField('headerdesc'); ?>
	</h1>
<?php endif; ?>

<?php if (!$this->category->isSection()) : ?>

<?php if (!empty($this->topics)) : ?>
	<div class="row">
		<div class="col-md-12">
			<h2 class="pull-right">
				<?php echo $this->subLayout('Widget/Search')
					->set('catid', $this->category->id)
					->setLayout('topic'); ?>
			</h2>

			<div class="pull-left">
				<?php echo $this->subLayout('Widget/Pagination/List')
					->set('pagination', $this->pagination)
					->set('display', true); ?>
			</div>
		</div>
	</div>
<?php endif; ?>

<form action="<?php echo KunenaRoute::_('index.php?option=com_kunena'); ?>" method="post" id="categoryactions">
	<input type="hidden" name="view" value="topics"/>
	<?php echo JHtml::_('form.token'); ?>
	<div>
		<ul class="inline">
			<?php if ($categoryActions) : ?>
				<li>
					<?php echo implode($categoryActions); ?>
				</li>
			<?php endif; ?>
		</ul>
	</div>
	<?php if ($this->topics) : ?>
	<table class="table<?php echo KunenaTemplate::getInstance()->borderless(); ?>">
		<thead>
		<tr>
			<td class="col-md-1 center hidden-xs">
				<a id="forumtop"> </a>
				<a href="#forumbottom">
					<?php echo KunenaIcons::arrowdown(); ?>
				</a>
			</td>
			<td class="col-md-<?php echo $cols ?>">
				<?php echo JText::_('COM_KUNENA_GEN_SUBJECT'); ?>
			</td>
			<td class="col-md-2 hidden-xs">
				<?php echo JText::_('COM_KUNENA_GEN_REPLIES'); ?> / <?php echo JText::_('COM_KUNENA_GEN_HITS'); ?>
			</td>
			<td class="col-md-3 hidden-xs">
				<?php echo JText::_('COM_KUNENA_GEN_LAST_POST'); ?>
			</td>
			<?php if (!empty($this->topicActions)) : ?>
				<td class="col-md-1 center">
					<label>
						<input class="kcheckall" type="checkbox" name="toggle" value=""/>
					</label>
				</td>
			<?php endif; ?>
		</tr>
		</thead>
		<?php endif; ?>
		<?php
		/** @var KunenaForumTopic $previous */
		$previous = null;

		foreach ($this->topics as $position => $topic)
		{
			echo $this->subLayout('Topic/Row')
				->set('topic', $topic)
				->set('spacing', $previous && $previous->ordering != $topic->ordering)
				->set('position', 'kunena_topic_' . $position)
				->set('checkbox', !empty($this->topicActions))
				->setLayout('category');
			$previous = $topic;
		}
		?>
		<tfoot>
		<?php if ($this->topics) : ?>
			<tr>
				<td class="center hidden-xs">
					<a id="forumbottom"> </a>
					<a href="#forumtop" rel="nofollow">
						<span class="divider"></span>
						<?php echo KunenaIcons::arrowup(); ?>
					</a>
					<?php // FIXME: $this->displayCategoryActions() ?>
				</td>
				<td colspan="6" class="hidden-xs">
					<div class="form-group">
						<div class="input-group" role="group">
							<div class="input-group-btn">
								<?php if (!empty($this->moreUri))
								{
									echo JHtml::_(
										'kunenaforum.link', $this->moreUri,
										JText::_('COM_KUNENA_MORE'), null, null, 'follow');
								} ?>

								<?php if (!empty($this->topicActions)) : ?>
									<?php echo JHtml::_(
										'select.genericlist', $this->topicActions, 'task',
										'class="form-control kchecktask"', 'value', 'text', 0, 'kchecktask'); ?>

									<?php if ($this->actionMove) : ?>
										<?php
										$options = array(JHtml::_('select.option', '0', JText::_('COM_KUNENA_BULK_CHOOSE_DESTINATION')));
										echo JHtml::_(
											'kunenaforum.categorylist', 'target', 0, $options, array(),
											'class="form-control fbs" disabled="disabled"', 'value', 'text', 0,
											'kchecktarget'
										);
										?>
										<button class="btn btn-default" name="kcheckgo" type="submit"><?php echo JText::_('COM_KUNENA_GO') ?></button>
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		</tfoot>
		<?php endif; ?>
	</table>
</form>

<?php if ($this->topics) : ?>
	<div class="pull-left">
		<?php echo $this->subLayout('Widget/Pagination/List')
			->set('pagination', $this->pagination)
			->set('display', true); ?>
	</div>
<?php endif; ?>

<div class="clearfix"></div>

<?php if (!empty($this->moderators))
{
	echo $this->subLayout('Category/Moderators')
		->set('moderators', $this->moderators);
}
?>

<?php if ($this->ktemplate->params->get('writeaccess')) : ?>
	<div><?php echo $this->subLayout('Widget/Writeaccess')->set('id', $this->category->id); ?></div>
<?php endif; ?>
