<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Administrator
 * @subpackage      Controllers
 *
 * @copyright       Copyright (C) 2008 - 2017 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

/**
 * Kunena Users Controller
 *
 * @since  2.0
 */
class KunenaAdminControllerUsers extends KunenaController
{
	/**
	 * @var null|string
	 * @since Kunena
	 */
	protected $baseurl = null;

	/**
	 * Construct
	 *
	 * @param   array $config construct
	 *
	 * @since    2.0
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->baseurl = 'administrator/index.php?option=com_kunena&view=users';
	}

	/**
	 * Edit
	 *
	 * @throws Exception
	 *
	 * @return boolean|void
	 *
	 * @since    2.0
	 */
	public function edit()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);
		$userid = array_shift($cid);

		if ($userid <= 0)
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$this->app->setUserState('kunena.user.userid', $userid);

		$this->setRedirect(JRoute::_("index.php?option=com_kunena&view=user&layout=edit&userid={$userid}", false));
	}

	/**
	 * Save
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function save()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$newview      = JFactory::getApplication()->input->getString('newview');
		$newrank      = JFactory::getApplication()->input->getString('newrank');
		$signature    = JFactory::getApplication()->input->getString('signature', '', 'POST', JREQUEST_ALLOWRAW);
		$deleteSig    = JFactory::getApplication()->input->getInt('deleteSig');
		$moderator    = JFactory::getApplication()->input->getInt('moderator');
		$uid          = JFactory::getApplication()->input->getInt('uid');
		$deleteAvatar = JFactory::getApplication()->input->getInt('deleteAvatar');
		$neworder     = JFactory::getApplication()->input->getInt('neworder');
		$modCatids    = $moderator ? JFactory::getApplication()->input->get('catid', array(), 'post', 'array') : array();
		Joomla\Utilities\ArrayHelper::toInteger($modCatids);

		if ($uid)
		{
			$user = KunenaFactory::getUser($uid);

			// Prepare variables
			if ($deleteSig == 1)
			{
				$user->signature = '';
			}
			else
			{
				$user->signature = $signature;
			}

			$user->personalText = JFactory::getApplication()->input->getString('personaltext', '');
			$birthdate                = JFactory::getApplication()->input->getString('birthdate');

			if ($birthdate)
			{
				$date = JFactory::getDate($birthdate);

				$birthdate = $date->format('Y-m-d');
			}

			$user->birthdate   = $birthdate;
			$user->location    = trim(JFactory::getApplication()->input->getString('location', ''));
			$user->gender      = JFactory::getApplication()->input->getInt('gender', '');
			$user->icq         = trim(JFactory::getApplication()->input->getString('icq', ''));
			$user->aim         = trim(JFactory::getApplication()->input->getString('aim', ''));
			$user->yim         = trim(JFactory::getApplication()->input->getString('yim', ''));
			$user->microsoft   = trim(JFactory::getApplication()->input->getString('microsoft', ''));
			$user->skype       = trim(JFactory::getApplication()->input->getString('skype', ''));
			$user->google      = trim(JFactory::getApplication()->input->getString('google', ''));
			$user->twitter     = trim(JFactory::getApplication()->input->getString('twitter', ''));
			$user->facebook    = trim(JFactory::getApplication()->input->getString('facebook', ''));
			$user->myspace     = trim(JFactory::getApplication()->input->getString('myspace', ''));
			$user->linkedin    = trim(JFactory::getApplication()->input->getString('linkedin', ''));
			$user->delicious   = trim(JFactory::getApplication()->input->getString('delicious', ''));
			$user->friendfeed  = trim(JFactory::getApplication()->input->getString('friendfeed', ''));
			$user->digg        = trim(JFactory::getApplication()->input->getString('digg', ''));
			$user->blogspot    = trim(JFactory::getApplication()->input->getString('blogspot', ''));
			$user->flickr      = trim(JFactory::getApplication()->input->getString('flickr', ''));
			$user->bebo        = trim(JFactory::getApplication()->input->getString('bebo', ''));
			$user->instagram   = trim(JFactory::getApplication()->input->getString('instagram', ''));
			$user->qq          = trim(JFactory::getApplication()->input->getString('qq', ''));
			$user->qzone       = trim(JFactory::getApplication()->input->getString('qzone', ''));
			$user->weibo       = trim(JFactory::getApplication()->input->getString('weibo', ''));
			$user->wechat      = trim(JFactory::getApplication()->input->getString('wechat', ''));
			$user->apple       = trim(JFactory::getApplication()->input->getString('apple', ''));
			$user->vk          = trim(JFactory::getApplication()->input->getString('vk', ''));
			$user->telegram    = trim(JFactory::getApplication()->input->getString('telegram', ''));
			$user->websitename = JFactory::getApplication()->input->getString('websitename', '');
			$user->websiteurl  = JFactory::getApplication()->input->getString('websiteurl', '');
			$user->hideEmail   = JFactory::getApplication()->input->getString('hidemail');
			$user->showOnline  = JFactory::getApplication()->input->getString('showonline');
			$user->cansubscribe  = JFactory::getApplication()->input->getString('cansubscribe');
			$user->userlisttime  = JFactory::getApplication()->input->getString('userlisttime');
			$user->view     = $newview;
			$user->ordering = $neworder;
			$user->rank     = $newrank;

			if ($deleteAvatar == 1)
			{
				$user->avatar = '';
			}

			if (!$user->save())
			{
				$this->app->enqueueMessage(JText::_('COM_KUNENA_USER_PROFILE_SAVED_FAILED'), 'error');
			}
			else
			{
				$this->app->enqueueMessage(JText::_('COM_KUNENA_USER_PROFILE_SAVED_SUCCESSFULLY'));
			}

			// Update moderator rights
			$categories = KunenaForumCategoryHelper::getCategories(false, false, 'admin');

			foreach ($categories as $category)
			{
				$category->setModerator($user, in_array($category->id, $modCatids));
			}

			// Global moderator is a special case
			if ($this->me->isAdmin())
			{
				KunenaAccess::getInstance()->setModerator(0, $user, in_array(0, $modCatids));
			}
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Apply
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function apply()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');

			return;
		}

		$newview      = JFactory::getApplication()->input->getString('newview');
		$newrank      = JFactory::getApplication()->input->getString('newrank');
		$signature    = JFactory::getApplication()->input->getString('signature', '', 'POST', JREQUEST_ALLOWRAW);
		$deleteSig    = JFactory::getApplication()->input->getInt('deleteSig');
		$moderator    = JFactory::getApplication()->input->getInt('moderator');
		$uid          = JFactory::getApplication()->input->getInt('uid');
		$deleteAvatar = JFactory::getApplication()->input->getInt('deleteAvatar');
		$neworder     = JFactory::getApplication()->input->getInt('neworder');
		$modCatids    = $moderator ? JFactory::getApplication()->input->get('catid', array(), 'post', 'array') : array();
		Joomla\Utilities\ArrayHelper::toInteger($modCatids);

		if ($uid)
		{
			$user = KunenaFactory::getUser($uid);

			// Prepare variables
			if ($deleteSig == 1)
			{
				$user->signature = '';
			}
			else
			{
				$user->signature = $signature;
			}

			$user->personalText = JFactory::getApplication()->input->getString('personaltext', '');
			$birthdate                = JFactory::getApplication()->input->getString('birthdate');

			if ($birthdate)
			{
				$date = JFactory::getDate($birthdate);

				$birthdate = $date->format('Y-m-d');
			}

			$user->birthdate   = $birthdate;
			$user->location    = trim(JFactory::getApplication()->input->getString('location', ''));
			$user->gender      = JFactory::getApplication()->input->getInt('gender', '');
			$user->icq         = trim(JFactory::getApplication()->input->getString('icq', ''));
			$user->aim         = trim(JFactory::getApplication()->input->getString('aim', ''));
			$user->yim         = trim(JFactory::getApplication()->input->getString('yim', ''));
			$user->microsoft   = trim(JFactory::getApplication()->input->getString('microsoft', ''));
			$user->skype       = trim(JFactory::getApplication()->input->getString('skype', ''));
			$user->google      = trim(JFactory::getApplication()->input->getString('google', ''));
			$user->twitter     = trim(JFactory::getApplication()->input->getString('twitter', ''));
			$user->facebook    = trim(JFactory::getApplication()->input->getString('facebook', ''));
			$user->myspace     = trim(JFactory::getApplication()->input->getString('myspace', ''));
			$user->linkedin    = trim(JFactory::getApplication()->input->getString('linkedin', ''));
			$user->delicious   = trim(JFactory::getApplication()->input->getString('delicious', ''));
			$user->friendfeed  = trim(JFactory::getApplication()->input->getString('friendfeed', ''));
			$user->digg        = trim(JFactory::getApplication()->input->getString('digg', ''));
			$user->blogspot    = trim(JFactory::getApplication()->input->getString('blogspot', ''));
			$user->flickr      = trim(JFactory::getApplication()->input->getString('flickr', ''));
			$user->bebo        = trim(JFactory::getApplication()->input->getString('bebo', ''));
			$user->instagram   = trim(JFactory::getApplication()->input->getString('instagram', ''));
			$user->qq          = trim(JFactory::getApplication()->input->getString('qq', ''));
			$user->qzone       = trim(JFactory::getApplication()->input->getString('qzone', ''));
			$user->weibo       = trim(JFactory::getApplication()->input->getString('weibo', ''));
			$user->wechat      = trim(JFactory::getApplication()->input->getString('wechat', ''));
			$user->apple       = trim(JFactory::getApplication()->input->getString('apple', ''));
			$user->vk          = trim(JFactory::getApplication()->input->getString('vk', ''));
			$user->telegram    = trim(JFactory::getApplication()->input->getString('telegram', ''));
			$user->websitename = JFactory::getApplication()->input->getString('websitename', '');
			$user->websiteurl  = JFactory::getApplication()->input->getString('websiteurl', '');
			$user->hideEmail   = JFactory::getApplication()->input->getString('hidemail');
			$user->showOnline  = JFactory::getApplication()->input->getString('showonline');
			$user->cansubscribe  = JFactory::getApplication()->input->getString('cansubscribe');
			$user->userlisttime  = JFactory::getApplication()->input->getString('userlisttime');

			$user->view     = $newview;
			$user->ordering = $neworder;
			$user->rank     = $newrank;

			if ($deleteAvatar == 1)
			{
				$user->avatar = '';
			}

			if (!$user->save())
			{
				$this->app->enqueueMessage(JText::_('COM_KUNENA_USER_PROFILE_SAVED_FAILED'), 'error');
			}
			else
			{
				$this->app->enqueueMessage(JText::_('COM_KUNENA_USER_PROFILE_SAVED_SUCCESSFULLY'));
			}

			// Update moderator rights
			$categories = KunenaForumCategoryHelper::getCategories(false, false, 'admin');

			foreach ($categories as $category)
			{
				$category->setModerator($user, in_array($category->id, $modCatids));
			}

			// Global moderator is a special case
			if ($this->me->isAdmin())
			{
				KunenaAccess::getInstance()->setModerator(0, $user, in_array(0, $modCatids));
			}
		}
	}

	/**
	 * Trash menu
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function trashusermessages()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);

		if ($cid)
		{
			foreach ($cid as $id)
			{
				list($total, $messages) = KunenaForumMessageHelper::getLatestMessages(false, 0, 0, array('starttime' => '-1', 'user' => $id));

				foreach ($messages as $mes)
				{
					$mes->publish(KunenaForum::DELETED);
				}
			}
		}
		else
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$this->app->enqueueMessage(JText::_('COM_KUNENA_A_USERMES_TRASHED_DONE'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Move
	 *
	 * @throws Exception
	 *
	 * @return  void
	 *
	 * @since    2.0
	 */
	public function move()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);

		if (empty($cid))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$this->app->setUserState('kunena.usermove.userids', $cid);

		$this->setRedirect(JRoute::_("index.php?option=com_kunena&view=user&layout=move", false));
	}

	/**
	 * Move Messages
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function movemessages()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$catid = JFactory::getApplication()->input->getInt('catid');
		$uids  = (array) $this->app->getUserState('kunena.usermove.userids');

		$error = null;

		if ($uids)
		{
			foreach ($uids as $id)
			{
				list($total, $messages) = KunenaForumMessageHelper::getLatestMessages(false, 0, 0, array('starttime' => '-1', 'user' => $id));

				foreach ($messages as $object)
				{
					$topic = $object->getTopic();

					if (!$object->authorise('move'))
					{
						$error = $object->getError();
					}
					else
					{
						$target = KunenaForumCategoryHelper::get($catid);

						if (!$topic->move($target, false, false, '', false))
						{
							$error = $topic->getError();
						}
					}
				}
			}
		}
		else
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		if ($error)
		{
			$this->app->enqueueMessage($error, 'notice');
		}
		else
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_A_USERMES_MOVED_DONE'));
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Logout
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function logout()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);
		$id = array_shift($cid);

		if ($id <= 0)
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$options = array('clientid' => 0);
		$this->app->logout((int) $id, $options);

		$this->app->enqueueMessage(JText::_('COM_KUNENA_A_USER_LOGOUT_DONE'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Remove
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function remove()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);

		if (empty($cid))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$users = KunenaUserHelper::loadUsers($cid);

		$my        = JFactory::getUser();
		$usernames = array();

		foreach ($users as $user)
		{
			$groups = JUserHelper::getUserGroups($user->userid);

			if ($my->id == $user->userid)
			{
				$this->app->enqueueMessage(JText::_('COM_KUNENA_USER_ERROR_CANNOT_DELETE_YOURSELF'), 'notice');
				continue;
			}

			$instance = JUser::getInstance($user->userid);

			if ($instance->authorise('core.admin'))
			{
				$this->app->enqueueMessage(JText::_('COM_KUNENA_USER_ERROR_CANNOT_DELETE_ADMINS'), 'notice');
				continue;
			}

			$result = $user->delete();

			if (!$result)
			{
				$this->app->enqueueMessage(JText::sprintf('COM_KUNENA_USER_DELETE_KUNENA_USER_TABLE_FAILED', $user->userid), 'notice');
				continue;
			}

			// Delete the user too from Joomla!
			$jresult = $instance->delete();

			if (!$jresult)
			{
				$this->app->enqueueMessage(JText::sprintf('COM_KUNENA_USER_DELETE_JOOMLA_USER_TABLE_FAILED', $user->userid), 'notice');
				continue;
			}

			$usernames[] = $user->username;
		}

		if (!empty($usernames))
		{
			$this->app->enqueueMessage(JText::sprintf('COM_KUNENA_USER_DELETE_DONE_SUCCESSFULLY', implode(', ', $usernames)));
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Ban
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function ban()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);
		$userid = array_shift($cid);

		if ($userid <= 0)
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$ban = KunenaUserBan::getInstanceByUserid($userid, true);

		if (!$ban->id)
		{
			$ban->ban($userid, null, 0);
			$success = $ban->save();
		}
		else
		{
			jimport('joomla.utilities.date');
			$now = new JDate;
			$ban->setExpiration($now);
			$success = $ban->save();
		}

		$message = JText::_('COM_KUNENA_USER_BANNED_DONE');

		if (!$success)
		{
			$this->app->enqueueMessage($ban->getError(), 'error');
		}
		else
		{
			$this->app->enqueueMessage($message);
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Unban
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function unban()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);
		$userid = array_shift($cid);

		if ($userid <= 0)
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$ban = KunenaUserBan::getInstanceByUserid($userid, true);

		if (!$ban->id)
		{
			$ban->ban($userid, null, 0);
			$success = $ban->save();
		}
		else
		{
			jimport('joomla.utilities.date');
			$now = new JDate;
			$ban->setExpiration($now);
			$success = $ban->save();
		}

		$message = JText::_('COM_KUNENA_USER_UNBAN_DONE');

		if (!$success)
		{
			$this->app->enqueueMessage($ban->getError(), 'error');
		}
		else
		{
			$this->app->enqueueMessage($message);
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Block
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function block()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);
		$userid = array_shift($cid);

		if ($userid <= 0)
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$ban = KunenaUserBan::getInstanceByUserid($userid, true);

		if (!$ban->id)
		{
			$ban->ban($userid, null, 1);
			$success = $ban->save();
		}
		else
		{
			jimport('joomla.utilities.date');
			$now = new JDate;
			$ban->setExpiration($now);
			$success = $ban->save();
		}

		$message = JText::_('COM_KUNENA_USER_BLOCKED_DONE');

		if (!$success)
		{
			$this->app->enqueueMessage($ban->getError(), 'error');
		}
		else
		{
			$this->app->enqueueMessage($message);
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Unblock
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function unblock()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);
		$userid = array_shift($cid);

		if ($userid <= 0)
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_PROFILE_NO_USER'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$ban = KunenaUserBan::getInstanceByUserid($userid, true);

		if (!$ban->id)
		{
			$ban->ban($userid, null, 1);
			$success = $ban->save();
		}
		else
		{
			jimport('joomla.utilities.date');
			$now = new JDate;
			$ban->setExpiration($now);
			$success = $ban->save();
		}

		$message = JText::_('COM_KUNENA_USER_UNBLOCK_DONE');

		if (!$success)
		{
			$this->app->enqueueMessage($ban->getError(), 'error');
		}
		else
		{
			$this->app->enqueueMessage($message);
		}

		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Batch Moderators
	 *
	 * @throws Exception
	 *
	 * @return void
	 *
	 * @since    2.0
	 */
	public function batch_moderators()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$cid = JFactory::getApplication()->input->get('cid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($cid);
		$catids = JFactory::getApplication()->input->get('catid', array(), 'post', 'array');
		Joomla\Utilities\ArrayHelper::toInteger($catids);

		if (empty($cid))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_USERS_BATCH_NO_USERS_SELECTED'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		if (empty($catids))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_USERS_BATCH_NO_CATEGORIES_SELECTED'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		// Update moderator rights
		$categories = KunenaForumCategoryHelper::getCategories(false, false, 'admin');
		$users      = KunenaUserHelper::loadUsers($cid);

		foreach ($users as $user)
		{
			foreach ($categories as $category)
			{
				if (in_array($category->id, $catids))
				{
					$category->setModerator($user, true);
				}
			}

			// Global moderator is a special case
			if ($this->me->isAdmin() && in_array(0, $catids))
			{
				KunenaAccess::getInstance()->setModerator(0, $user, true);
			}
		}

		$this->app->enqueueMessage(JText::_('COM_KUNENA_USERS_SET_MODERATORS_DONE'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Method to just redirect to main manager in case of use of cancel button
	 *
	 * @return void
	 *
	 * @since K4.0
	 */
	public function cancel()
	{
		$this->app->redirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Remove categories subscriptions for the users selected
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 * @since Kunena
	 */
	public function removecatsubscriptions()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$db  = JFactory::getDbo();
		$cid = $this->app->input->get('cid', array(), 'array');

		if (!empty($cid))
		{
			foreach ($cid as $userid)
			{
				$query = $db->getQuery(true);
				$query->update($db->quoteName('#__kunena_user_categories'))->set($db->quoteName('subscribed') . ' = 0')->where($db->quoteName('user_id') . ' = ' . $userid);
				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (Exception $e)
				{
					$e->getMessage();
				}
			}
		}

		$this->app->enqueueMessage(JText::_('COM_KUNENA_USERS_REMOVE_CAT_SUBSCRIPTIONS_DONE'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}

	/**
	 * Remove topics subscriptions for the users selected
	 *
	 * @since 4.0.0
	 *
	 * @return void
	 * @since Kunena
	 */
	public function removetopicsubscriptions()
	{
		if (!JSession::checkToken('post'))
		{
			$this->app->enqueueMessage(JText::_('COM_KUNENA_ERROR_TOKEN'), 'error');
			$this->setRedirect(KunenaRoute::_($this->baseurl, false));

			return;
		}

		$db  = JFactory::getDBO();
		$cid = $this->app->input->get('cid', array(), 'array');

		if (!empty($cid))
		{
			foreach ($cid as $userid)
			{
				$query = $db->getQuery(true);
				$query->update($db->quoteName('#__kunena_user_topics'))->set($db->quoteName('subscribed') . ' = 0')->where($db->quoteName('user_id') . ' = ' . $userid);
				$db->setQuery($query);

				try
				{
					$db->execute();
				}
				catch (Exception $e)
				{
					$e->getMessage();
				}
			}
		}

		$this->app->enqueueMessage(JText::_('COM_KUNENA_USERS_REMOVE_TOPIC_SUBSCRIPTIONS_DONE'));
		$this->setRedirect(KunenaRoute::_($this->baseurl, false));
	}
}
