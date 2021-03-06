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
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Wiki\Models\Adapters;

use Hubzero\Base\Object;

/**
 * Abstract adapter class for a forum post link
 */
abstract class Base extends Object
{
	/**
	 * Script name
	 *
	 * @var  string
	 */
	protected $_base = 'index.php';

	/**
	 * URL segments
	 *
	 * @var  array
	 */
	protected $_segments = array();

	/**
	 * Scope ID
	 *
	 * @var  integer
	 */
	protected $_scope_id = 0;

	/**
	 * Constructor
	 *
	 * @param   string   $pagename
	 * @param   string   $path
	 * @param   integer  $scope_id
	 * @return  void
	 */
	public function __construct($pagename=null, $path=null, $scope_id=0)
	{
		$pagename = ($path ? $path . '/' : '') . $pagename;

		$this->_segments['pagename'] = $pagename;

		$this->_scope_id = $scope_id;
	}

	/**
	 * Generate and return various links to the entry
	 * Link will vary depending upon action desired, such as edit, delete, etc.
	 *
	 * @param   string  $type    The type of link to return
	 * @param   mixed   $params  Optional string or associative array of params to append
	 * @return  string
	 */
	public function link($type='', $params=null)
	{
		return $this->_base;
	}

	/**
	 * Flatten array of segments into querystring
	 *
	 * @param   array  $segments  An associative array of querystring bits
	 * @return  string
	 */
	protected function _build(array $segments)
	{
		$bits = array();
		foreach ($segments as $key => $param)
		{
			$bits[] = $key . '=' . $param;
		}
		return implode('&', $bits);
	}

	/**
	 * Get an array of routing inputs
	 *
	 * @param   string  $task
	 * @return  array
	 */
	public function routing($task='save')
	{
		return array(
			'task' => $task
		);
	}

	/**
	 * Get permissions for a user
	 *
	 * @param   object  $page
	 * @return  boolean
	 */
	public function authorise($page)
	{
		return true;
	}
}
