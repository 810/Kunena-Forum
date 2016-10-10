<?php
/**
 * Kunena Component
 * @package         Kunena.Template.Crypsis
 * @subpackage      Layout.Widget
 *
 * @copyright       Copyright (C) 2008 - 2016 Kunena Team. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die;

$pathway = $this->breadcrumb->getPathway();
$item    = array_shift($pathway);

if ($item) : ?>

	<ul class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
		<li class="active" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
			<?php echo KunenaIcons::home(); ?>
			<a href="<?php echo $item->link; ?>" rel="nofollow"><?php echo $this->escape($item->name); ?></a>
		</li>

		<?php foreach ($pathway as $item) : ?>
			<li class="divider"><?php echo KunenaIcons::chevronright(); ?></li>
			<li itemscope itemtype="http://schema.org/ListItem">
				<a href="<?php echo $item->link; ?>" rel="nofollow"><?php echo $this->escape($item->name); ?></a>
			</li>
		<?php endforeach; ?>

	</ul>
<?php endif; ?>
