<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @copyright Copyright 2005-2015 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

namespace Components\Blog;

if (!\JFactory::getUser()->authorise('core.manage', 'com_blog'))
{
	return \JError::raiseWarning(404, \JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include scripts
require_once(JPATH_COMPONENT_SITE . DS . 'models' . DS . 'archive.php');
require_once(__DIR__ . DS . 'helpers' . DS . 'permissions.php');

$scope = \JRequest::getCmd('scope', 'site');
$controllerName = \JRequest::getCmd('controller', 'entries');

\JSubMenuHelper::addEntry(
	\JText::_('COM_BLOG_SCOPE_SITE'),
	\JRoute::_('index.php?option=com_blog&controller=entries&scope=site'),
	($controllerName == 'entries' && $scope == 'site')
);
\JSubMenuHelper::addEntry(
	\JText::_('COM_BLOG_SCOPE_MEMBER'),
	\JRoute::_('index.php?option=com_blog&controller=entries&scope=member'),
	($controllerName == 'entries' && $scope == 'member')
);
\JSubMenuHelper::addEntry(
	\JText::_('COM_BLOG_SCOPE_GROUP'),
	\JRoute::_('index.php?option=com_blog&controller=entries&scope=group'),
	($controllerName == 'entries' && $scope == 'group')
);

if (!file_exists(__DIR__ . DS . 'controllers' . DS . $controllerName . '.php'))
{
	$controllerName = 'entries';
}
require_once(__DIR__ . DS . 'controllers' . DS . $controllerName . '.php');
$controllerName = __NAMESPACE__ . '\\Controllers\\' . ucfirst(strtolower($controllerName));

// Instantiate controller
$controller = new $controllerName();
$controller->execute();
$controller->redirect();

