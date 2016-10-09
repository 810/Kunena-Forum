<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Crypsis
 * @subpackage      BBCode
 *
 * @copyright       Copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();

// @var KunenaAttachment $attachment

$attachment = $this->attachment;
$location   = JUri::root() . $attachment->getUrl();

if (!$attachment->isAudio())
{
	return;
}
?>
<div class="clearfix"></div>

<audio src="<?php echo $location; ?>" controls>
	Your browser does not support the <code>audio</code> element.
</audio>
<p><?php echo $attachment->getShortName(); ?></p>
<div class="clearfix"></div>
