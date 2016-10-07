<?php
/**
 * Kunena Component
 * @package         Kunena.Site
 * @subpackage      Controller.Topic
 *
 * @copyright       Copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;

/**
 * Class ComponentKunenaControllerTopicItemRatingDisplay
 *
 * @since  K5.0
 */
class ComponentKunenaControllerTopicItemRatingDisplay extends KunenaControllerDisplay
{
	protected $name = 'Topic/Item/Rating';

	/**
	 * @var KunenaForumTopic
	 */
	public $topic;

	/**
	 * Prepare topic actions display.
	 *
	 * @return void
	 * @since Kunena
 	 */
	protected function before()
	{
		parent::before();
	}
}
