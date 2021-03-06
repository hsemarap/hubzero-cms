<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Alissa Nedossekina <alisa@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Jobs\Admin;

if (!\User::authorise('core.manage', 'com_jobs'))
{
	return \App::abort(404, \Lang::txt('JERROR_ALERTNOAUTHOR'));
}

// Include scripts
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'admin.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'application.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'category.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'employer.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'job.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'prefs.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'resume.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'seeker.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'shortlist.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'stats.php');
include_once(dirname(__DIR__) . DS . 'tables' . DS . 'type.php');
include_once(dirname(__DIR__) . DS . 'helpers' . DS . 'permissions.php');
include_once(dirname(__DIR__) . DS . 'helpers' . DS . 'html.php');

$controllerName = \Request::getCmd('controller', 'jobs');
if (!file_exists(__DIR__ . DS . 'controllers' . DS . $controllerName . '.php'))
{
	$controllerName = 'jobs';
}

\Submenu::addEntry(
	\Lang::txt('COM_JOBS_JOBS'),
	\Route::url('index.php?option=com_jobs&controller=jobs'),
	$controllerName == 'jobs'
);
\Submenu::addEntry(
	\Lang::txt('COM_JOBS_CATEGORIES'),
	\Route::url('index.php?option=com_jobs&controller=categories'),
	$controllerName == 'categories'
);
\Submenu::addEntry(
	\Lang::txt('COM_JOBS_TYPES'),
	\Route::url('index.php?option=com_jobs&controller=types'),
	$controllerName == 'types'
);

require_once(__DIR__ . DS . 'controllers' . DS . $controllerName . '.php');
$controllerName = __NAMESPACE__ . '\\Controllers\\' . ucfirst($controllerName);

// Initiate controller
$controller = new $controllerName();
$controller->execute();
$controller->redirect();

