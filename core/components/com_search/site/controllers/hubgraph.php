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
 * @author    Steve Snyder <snyder13@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Search\Site\Controllers;

use Components\Search\Models\Hubgraph\Configuration;
use Components\Search\Models\Hubgraph\Request;
use Components\Search\Models\Hubgraph\client;
use Hubzero\Component\SiteController;
use Exception;
use Pathway;
use Route;
use Log;
use App;

include_once(dirname(dirname(__DIR__)) . DS . 'helpers' . DS . 'hubgraph.php');
include_once(dirname(dirname(__DIR__)) . DS . 'models' . DS . 'hubgraph' . DS . 'inflect.php');
include_once(dirname(dirname(__DIR__)) . DS . 'models' . DS . 'hubgraph' . DS . 'request.php');
include_once(dirname(dirname(__DIR__)) . DS . 'models' . DS . 'hubgraph' . DS . 'client.php');
include_once(dirname(dirname(__DIR__)) . DS . 'models' . DS . 'hubgraph' . DS . 'db.php');

/**
 * Hubgraph controller class
 */
class Hubgraph extends SiteController
{
	/**
	 * Determine task and execute it
	 * 
	 * @return  void
	 */
	public function execute()
	{
		foreach (array('SCRIPT_URL', 'URL', 'REDIRECT_SCRIPT_URL', 'REDIRECT_URL') as $k)
		{
			if (isset($_SERVER[$k]))
			{
				$this->base = $_SERVER[$k];
				break;
			}
		}

		$base = explode('/', $this->base);
		$base = array_map('urldecode', $base);
		$base = array_map('trim', $base);
		foreach ($base as $i => $segment)
		{
			$segment = trim($segment, '"');
			$segment = trim($segment, "'");
			if (strstr($segment, '='))
			{
				unset($base[$i]);
				continue;
			}
			$segment = urlencode($segment);
			$base[$i] = $segment;
		}
		$this->base = implode('/', $base);

		$this->req  = new Request($_GET);
		$this->conf = Configuration::instance();
		$this->perPage = \Config::get('list_limit', 50);

		$this->registerTask('page', 'index');
		$this->registerTask('__default', 'index');

		// Try to execute
		try
		{
			Pathway::append(
				Lang::txt('COM_SEARCH'),
				'index.php?option=' . $this->_option
			);

			parent::execute();
		}
		catch (Exception $ex)
		{
			// Log the error
			Log::error($ex->getMessage());

			// If not displaying inline...
			if (!defined('HG_INLINE'))
			{
				App::get('session')->set('searchfallback', time() + 60);

				// Redirect back to this component wil the fallback flag set
				// so it knows to load the default, basic controller.
				$terms = \Request::getVar('terms', '', 'get');

				App::redirect(
					Route::url('index.php?option=' . $this->_option . ($terms ? '&terms=' . $terms : ''), false),
					(App::get('config')->get('debug') ? $ex->getMessage() : null),
					(App::get('config')->get('debug') ? 'error' : null)
				);
				return;
			}
		}
	}

	/**
	 * Display search form and results (if any)
	 * 
	 * @return  void
	 */
	public function indexTask()
	{
		$this->view->results = $this->req->anyCriteria()
				? json_decode(Client::execView('search', $this->req->getTransportCriteria(array('limit' => $this->perPage))), TRUE)
				: NULL;

		$this->view->req       = $this->req;
		$this->view->tags      = $this->req->getTags();
		$this->view->users     = $this->req->getContributors();
		$this->view->groups    = $this->req->getGroup();
		$this->view->domainMap = $this->req->getDomainMap();
		$this->view->loggedIn  = (bool) \User::get('id');
		$this->view->perPage   = $this->perPage;
		ksort($this->view->domainMap);

		if ($this->_task == 'page')
		{
			define('HG_AJAX', 1);
			$this->view
				->set('base', $this->base)
				->setLayout('page')
				->display();
			exit();
		}

		$this->view
			->set('base', $this->base)
			->setLayout('index-update')
			->display();
	}

	/**
	 * Display complete
	 * 
	 * @return  void
	 */
	public function completeTask()
	{
		$args = array(
			'limit'     => 20,
			'threshold' => 3,
			'tagLimit'  => 100
		);

		header('Content-type: text/json');
		echo Client::execView('complete', array_merge($_GET, $args));
		exit();
	}

	/**
	 * Display related
	 * 
	 * @return  void
	 */
	public function getrelatedTask()
	{
		$args = $this->req->getTransportCriteria(array(
			'limit'  => 5,
			'domain' => $_GET['domain'],
			'id'     => $_GET['id']
		));

		header('Content-type: text/json');
		echo Client::execView('related', array_merge($_GET, $args));
		exit();
	}

	/**
	 * Update
	 * 
	 * @return  void
	 */
	public function updateTask()
	{
		$this->view->results = $this->req->anyCriteria()
				? json_decode(Client::execView('search', $this->req->getTransportCriteria(array('limit' => $this->perPage))), TRUE)
				: NULL;

		define('HG_AJAX', 1);

		$this->view->req       = $this->req;
		$this->view->tags      = $this->req->getTags();
		$this->view->users     = $this->req->getContributors();
		$this->view->groups    = $this->req->getGroup();
		$this->view->domainMap = $this->req->getDomainMap();
		$this->view->loggedIn  = (bool) \User::get('id');
		$this->view->perPage   = $this->perPage;
		ksort($this->view->domainMap);

		$this->view
			->set('base', $this->base)
			->setLayout('index-update')
			->display();
	}
}
