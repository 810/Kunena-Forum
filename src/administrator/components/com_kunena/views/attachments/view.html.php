<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Views
 *
 * @copyright       Copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

/**
 * Attachments view for Kunena backend
 */
class KunenaAdminViewAttachments extends KunenaView
{
	/**
	 *
	 * @param   null $tpl
	 *
	 * @return mixed
	 * @since Kunena
	 */
	function display($tpl = null)
	{
		$this->setToolbar();
		$this->items      = $this->get('Items');
		$this->state      = $this->get('state');
		$this->pagination = $this->get('Pagination');

		$this->sortFields          = $this->getSortFields();
		$this->sortDirectionFields = $this->getSortDirectionFields();

		$this->filterSearch     = $this->escape($this->state->get('list.search'));
		$this->filterTitle      = $this->escape($this->state->get('filter.title'));
		$this->filterType       = $this->escape($this->state->get('filter.type'));
		$this->filterSize       = $this->escape($this->state->get('filter.size'));
		$this->filterDimensions = $this->escape($this->state->get('filter.dims'));
		$this->filterUsername   = $this->escape($this->state->get('filter.username'));
		$this->filterPost       = $this->escape($this->state->get('filter.post'));
		$this->filterActive     = $this->escape($this->state->get('filter.active'));
		$this->listOrdering     = $this->escape($this->state->get('list.ordering'));
		$this->listDirection    = $this->escape($this->state->get('list.direction'));

		return parent::display($tpl);

	}

	/**
	 *
	 * @since Kunena
	 */
	protected function setToolbar()
	{
		$help_url = 'https://www.kunena.org/docs/';
		JToolBarHelper::help('COM_KUNENA', false, $help_url);
		JToolBarHelper::title(JText::_('COM_KUNENA') . ': ' . JText::_('COM_KUNENA_FILE_MANAGER'), 'folder-open');
		JToolBarHelper::spacer();
		JToolBarHelper::custom('delete', 'trash.png', 'trash_f2.png', 'COM_KUNENA_GEN_DELETE');

		JToolBarHelper::spacer();
	}

	/**
	 * Returns an array of review filter options.
	 *
	 * @return    array
	 */
	protected function getSortFields()
	{
		$sortFields   = array();
		$sortFields[] = JHtml::_('select.option', 'filename', JText::_('COM_KUNENA_ATTACHMENTS_FIELD_LABEL_TITLE'));
		$sortFields[] = JHtml::_('select.option', 'filetype', JText::_('COM_KUNENA_ATTACHMENTS_FIELD_LABEL_TYPE'));
		$sortFields[] = JHtml::_('select.option', 'size', JText::_('COM_KUNENA_ATTACHMENTS_FIELD_LABEL_SIZE'));
		$sortFields[] = JHtml::_('select.option', 'username', JText::_('COM_KUNENA_ATTACHMENTS_USERNAME'));
		$sortFields[] = JHtml::_('select.option', 'post', JText::_('COM_KUNENA_ATTACHMENTS_FIELD_LABEL_MESSAGE'));
		$sortFields[] = JHtml::_('select.option', 'id', JText::_('JGRID_HEADING_ID'));

		return $sortFields;
	}

	/**
	 * Returns an array of review filter options.
	 *
	 * @return    array
	 */
	protected function getSortDirectionFields()
	{
		$sortDirection   = array();
		$sortDirection[] = JHtml::_('select.option', 'asc', JText::_('JGLOBAL_ORDER_ASCENDING'));
		$sortDirection[] = JHtml::_('select.option', 'desc', JText::_('JGLOBAL_ORDER_DESCENDING'));

		return $sortDirection;
	}
}
