<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.sef
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Joomla! SEF Plugin
 *
 * @package     Joomla.Plugin
 * @subpackage  System.sef
 * @since       1.5
 */
class PlgSystemBytesTogether extends JPlugin
{
	/**
	 * Converting the site URL to fit to the HTTP request
	 *
	 * @return  void
	 */
	
	protected $is_together = false;
	
	public function __construct(&$subject, $config = array())
	{
		parent::__construct($subject, $config);
		
		$scretWord = $this->params->get('secret_word', '');
		
		$app = JFactory::getApplication();
		$urlWord = $app->input->get('bytestogether','');
		
		if($scretWord && $scretWord === $urlWord) {
			$this->is_together = true;
		}
		
		// TESTING
		if ($app->isSite()) {
			$this->is_together = true;
		}
	}
	 
	public function onAfterRoute()
	{
		if(!$this->is_together) {
			return true;
		}
		
		$assets_url = JUri::root().'/plugins/system/bytestogether/assets/';
		
		JFactory::getDocument()->addStyleSheet($assets_url.'bytestogether.css');
		JFactory::getDocument()->addScript($assets_url.'bytestogether.js');
				
		//JFactory::getDocument()->addScriptDeclaration("window.onload = function(){	TogetherJS(this);	};");
	}
	
	public function onAfterRender()
	{
		if(!$this->is_together) {
			return true;
		}
		
		$this->_addScript();		
	}
	
	private function _addScript()
	{
		ob_start();
		?>

			<div class="bytestogether_footer">
				<div class="bytestogether_footer_text">
					 <a href="https://togetherjs.com/" target="_blank">Mozilla's TogetherJS</a> system added into <a href="http://www.joomla.org/" target="_blank">Joomla</a> by <a href="http://www.readybytes.net/" target="_blank">readyBytes.net</a>
					 
<!--					<a href="#" id="start-togetherjs" class="togetherjs-button" onclick="TogetherJS(this); return false;">Start TogetherJS</a>-->
				</div>
			</div>
			<script src="https://togetherjs.com/togetherjs-min.js" type="text/javascript"></script>
		<?php 

		$script = ob_get_contents();
		ob_clean();
		
		$body = JResponse::getBody();
		$body = str_replace('</body>', $script.'</body>', $body);
		JResponse::setBody($body);		
	}

}
