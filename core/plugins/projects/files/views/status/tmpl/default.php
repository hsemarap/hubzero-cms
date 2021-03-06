<?php
/**
 * @package		HUBzero CMS
 * @author		Alissa Nedossekina <alisa@purdue.edu>
 * @copyright	Copyright 2005-2009 HUBzero Foundation, LLC.
 * @license		http://opensource.org/licenses/MIT MIT
 *
 * Copyright 2005-2009 HUBzero Foundation, LLC.
 * All rights reserved.
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
 */

// No direct access
defined('_HZEXEC_') or die();

$subdirlink = $this->subdir ? '&amp;subdir=' . urlencode($this->subdir) : '';

?>
<div id="abox-content">
<h3><?php echo Lang::txt('PLG_PROJECTS_FILES_GIT_STATUS'); ?></h3>
<form id="hubForm-ajax" method="post" action="<?php echo Route::url('index.php?option=' . $this->option . '&id=' . $this->model->get('id')); ?>">
	<fieldset >
		<?php echo $this->status; ?>
		<p class="submitarea">
			<?php if ($this->ajax) { ?>
				<input type="reset" id="cancel-action" class="btn btn-cancel" value="<?php echo Lang::txt('PLG_PROJECTS_FILES_CANCEL'); ?>" />
			<?php } else {  ?>
				<a id="cancel-action" class="btn btn-cancel" href="<?php echo $this->url . '?a=1' . $subdirlink; ?>"><?php echo Lang::txt('PLG_PROJECTS_FILES_GO_BACK'); ?></a>
			<?php } ?>
		</p>
	</fieldset>
</form>
</div>