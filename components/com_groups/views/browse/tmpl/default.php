<?php
/**
 * @package		HUBzero CMS
 * @author		Shawn Rice <zooley@purdue.edu>
 * @copyright	Copyright 2005-2009 by Purdue Research Foundation, West Lafayette, IN 47906
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 *
 * Copyright 2005-2009 by Purdue Research Foundation, West Lafayette, IN 47906.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License,
 * version 2 as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

$maxtextlen = 42;
$juser =& JFactory::getUser();
?>
<div id="content-header">
	<h2><?php echo $this->title; ?></h2>
</div>

<div id="content-header-extra">
	<ul id="useroptions">
		<li class="last"><a class="add" href="<?php echo JRoute::_('index.php?option='.$this->option.'&task=new'); ?>"><?php echo JText::_('GROUPS_CREATE_GROUP'); ?></a></li>
	</ul>
</div><!-- / #content-header-extra -->

<?php if ($this->getError()) { ?>
	<p class="error"><?php echo $this->getError(); ?></p>
<?php } ?>

<form action="<?php echo JRoute::_('index.php?option='.$this->option.'&task=browse'); ?>" method="get">
	<div class="main section">
		<div class="aside">
			<div class="container">
				<h3>Member Groups</h3>
				<p class="starter"><span class="starter-point"></span>When people create a group it will appear here.</p>
				<p>Use the sorting and filtering options to see groups listed alphabetically by their title, by their alias, or join policy.</p>
				<p>Use the 'Search' to find specific groups if you would like to check out their contributions or possibly join them.</p>
			</div><!-- / .container -->
			
			<div class="container">
				<h3>Looking for someone?</h3>
				<p class="starter"><span class="starter-point"></span>Go to the <a href="<?php echo JRoute::_('index.php?option=com_members'); ?>">Members page</a>.</p>
			</div><!-- / .container -->
		</div><!-- / .aside -->
		<div class="subject">

			<div class="container data-entry">
				<input class="entry-search-submit" type="submit" value="Search" />
				<fieldset class="entry-search">
					<legend>Search for Groups</legend>
					<label for="entry-search-field">Enter keyword or phrase</label>
					<input type="text" name="search" id="entry-search-field" value="<?php echo htmlentities($this->filters['search'], ENT_COMPAT, 'UTF-8'); ?>" />
					<input type="hidden" name="sortby" value="<?php echo $this->filters['sortby']; ?>" />
					<input type="hidden" name="policy" value="<?php echo $this->filters['policy']; ?>" />
					<!-- <input type="hidden" name="option" value="<?php echo $this->option; ?>" /> -->
					<input type="hidden" name="index" value="<?php echo $this->filters['index']; ?>" />
				</fieldset>
			</div><!-- / .container -->

			<div class="container">
				<ul class="entries-menu order-options">
					<li><a<?php echo ($this->filters['sortby'] == 'title') ? ' class="active"' : ''; ?> href="<?php echo JRoute::_('index.php?option='.$this->option.'&task=browse&index='.$this->filters['index'].'&policy='.$this->filters['policy'].'&sortby=title'); ?>" title="Sort by title">&darr; Title</a></li>
					<li><a<?php echo ($this->filters['sortby'] == 'alias') ? ' class="active"' : ''; ?> href="<?php echo JRoute::_('index.php?option='.$this->option.'&task=browse&index='.$this->filters['index'].'&policy='.$this->filters['policy'].'&sortby=alias'); ?>" title="Sort by alias">&darr; Alias</a></li>
				</ul>
				
				<ul class="entries-menu filter-options">
					<li><a<?php echo ($this->filters['policy'] == '') ? ' class="active"' : ''; ?> href="<?php echo JRoute::_('index.php?option='.$this->option.'&task=browse&index='.$this->filters['index'].'&sortby='.$this->filters['sortby']); ?>" title="Show All groups">All</a></li>
					<li><a<?php echo ($this->filters['policy'] == 'open') ? ' class="active"' : ''; ?> href="<?php echo JRoute::_('index.php?option='.$this->option.'&task=browse&index='.$this->filters['index'].'&policy=open&sortby='.$this->filters['sortby']); ?>" title="Show groups with an Open join policy">Open</a></li>
					<li><a<?php echo ($this->filters['policy'] == 'restricted') ? ' class="active"' : ''; ?> href="<?php echo JRoute::_('index.php?option='.$this->option.'&task=browse&index='.$this->filters['index'].'&policy=restricted&sortby='.$this->filters['sortby']); ?>" title="Show groups with a Restricted join policy">Restricted</a></li>
					<li><a<?php echo ($this->filters['policy'] == 'invite') ? ' class="active"' : ''; ?> href="<?php echo JRoute::_('index.php?option='.$this->option.'&task=browse&index='.$this->filters['index'].'&policy=invite&sortby='.$this->filters['sortby']); ?>" title="Show groups with an Invite only join policy">Invite only</a></li>
				</ul>

<?php
$qs = array();
foreach ($this->filters as $f=>$v) 
{
	$qs[] = ($v != '' && $f != 'index' && $f != 'authorized' && $f != 'type' && $f != 'fields') ? $f.'='.$v : '';
}
$qs[] = 'limitstart=0';
$qs = implode('&amp;',$qs);

$url  = 'index.php?option='.$this->option.'&task=browse';
$url .= ($qs) ? '&'.$qs : '';

$html  = '<a href="'.JRoute::_($url).'"';
if ($this->filters['index'] == '') {
	$html .= ' class="active-index"';
}
$html .= '>'.JText::_('ALL').'</a> '."\n";

$letters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
foreach ($letters as $letter)
{
	$url  = 'index.php?option='.$this->option.'&task=browse&index='.strtolower($letter);
	$url .= ($qs) ? '&'.$qs : '';
	
	$html .= "\t\t\t\t\t\t\t\t".'<a href="'.JRoute::_($url).'"';
	if ($this->filters['index'] == strtolower($letter)) {
		$html .= ' class="active-index"';
	}
	$html .= '>'.$letter.'</a> '."\n";
}
?>
				<div class="clearfix"></div>

				<table class="groups entries" summary="<?php echo JText::_('GROUPS_BROWSE_TBL_SUMMARY'); ?>">
					<caption>
<?php
						$s = $this->filters['start']+1;
						$e = ($this->total > ($this->filters['start'] + $this->filters['limit'])) ? ($this->filters['start'] + $this->filters['limit']) : $this->total;

						if ($this->filters['search'] != '') {
							echo 'Search for "'.$this->filters['search'].'" in ';
						}
?>
						<?php echo JText::_('Groups'); ?> 
<?php if ($this->filters['index']) { ?>
							<?php echo JText::_('starting with'); ?> "<?php echo strToUpper($this->filters['index']); ?>"
<?php } ?>
<?php if ($this->groups) { ?>
						<span>(<?php echo $s.'-'.$e; ?> of <?php echo $this->total; ?>)</span>
<?php } ?>
					</caption>
					<thead>
						<tr>
							<th colspan="<?php echo ($this->authorized) ? '4' : '3'; ?>">
								<?php echo $html; ?>
							</th>
						</tr>
					</thead>
					<tbody>
<?php
if ($this->groups) {
	foreach ($this->groups as $group) 
	{
		$status = '';
		if ($this->authorized) {
			if ($group->manager && $group->published) {
				$status = 'manager';
			} else {
				if (!$group->published) {
					$status = 'new';
				} else {
					if ($group->registered) {
						if ($group->regconfirmed) {
							$status = 'member';
						} else {
							$status = 'pending';
						}
					} else {
						if ($group->regconfirmed) {
							$status = 'invitee';
						}
					}
				}
			}
		}	
?>
						<tr<?php echo ($status) ? ' class="'.$status.'"' : ''; ?>>
							<th>
								<span class="entry-id"><?php echo $group->gidNumber; ?></span>
							</th>
							<td>
								<a class="entry-title" href="<?php echo JRoute::_('index.php?option='.$this->option.'&gid='.$group->cn); ?>"><?php echo stripslashes($group->description); ?></a><br />
								<span class="entry-details">
									<span class="entry-alias"><?php echo $group->cn; ?></span>
								</span>
							</td>
							<td>
								<?php
								switch ($group->join_policy) 
								{
									case 3: echo '<span class="closed join-policy">'.JText::_('Closed').'</span>'."\n"; break;
									case 2: echo '<span class="inviteonly join-policy">'.JText::_('Invite Only').'</span>'."\n"; break;
									case 1: echo '<span class="restricted join-policy">'.JText::_('Restricted').'</span>'."\n";  break;
									case 0:
									default: echo '<span class="open join-policy">'.JText::_('Open').'</span>'."\n"; break;
								}
?>
							</td>
<?php if ($this->authorized) { ?>
							<td>
								<span class="<?php echo $status; ?> status"><?php
									switch ($status) 
									{
										case 'manager': echo JText::_('GROUPS_STATUS_MANAGER'); break;
										case 'new': echo JText::_('GROUPS_STATUS_NEW_GROUP'); break;
										case 'member': echo JText::_('GROUPS_STATUS_APPROVED'); break;
										case 'pending': echo JText::_('GROUPS_STATUS_PENDING'); break;
										case 'invitee': echo JText::_('GROUPS_STATUS_INVITED'); break;
										default: break;
									}
								?></span>
							</td>
<?php } ?>
						</tr>
<?php 
	} // for loop 
} else {
?>
						<tr>
							<td colspan="<?php echo ($this->authorized) ? '4' : '3'; ?>">
								<p class="warning"><?php echo JText::_('No results found'); ?></p>
							</td>
						</tr>
<?php } ?>
					</tbody>
				</table>
<?php
$pn = $this->pageNav->getListFooter();
if (!strstr($pn,'/browse')) {
	$pn = str_replace('/?','/browse/?',$pn);
	$pn = str_replace('&amp;&amp;','&amp;',$pn);
}
echo $pn;
?>
				<div class="clearfix"></div>
			</div><!-- / .container -->
		</div><!-- / .subject -->
	</div><!-- / .main section -->
	<div class="clear"></div>
</form>