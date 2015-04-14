<?php
# @version		$version 0.9 Amvis United Company Limited  $
# @copyright	Copyright (C) 2015 AUnited Co Ltd. All rights reserved.
# @license		GNU/GPL v2.0
# Updated		11st April 2015
#
# Based on Ecolora's StopKids ( http://ecolora.com ), under GNU/GPL v2.0
#
# Site: http://aunited.ru
# Email: info@aunited.ru
# Phone
#
# Joomla! is free software. This version may have been modified pursuant
# to the GNU General Public License, and as distributed it includes or
# is derivative of works licensed under the GNU General Public License or
# other free or open source software licenses.
# See COPYRIGHT.php for copyright notices and details.
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

class  plgSystemRusRate extends JPlugin {

    function onAfterRender() {

     	$app = JFactory::getApplication();
		// Checking that we are not in the admin panel
		if ($app->getName()!= 'site') {
			return true;
		}

    	$document = JFactory::getDocument();
        if ($document->getType() !== 'html') return true;

        if (JRequest::getVar('tmpl') === 'component') return true;

        if (plgSystemRusRate::ShowOnFP()) return true;

        JPlugin::loadLanguage('plg_system_rusrate', JPATH_ADMINISTRATOR);
		
		//Getting params
 	    $position = $this->params->get('position', 'tl');    
		$age = $this->params->get('age', 18);
		switch($age) {
			case '0': $text=JText::_('PLG_SYSTEM_RUSRATE_MESSAGE_ZERO'); break;
			case '18': case '21': $text=JText::_('PLG_SYSTEM_RUSRATE_MESSAGE_ADULT'); break;
			default: $text=JText::sprintf('PLG_SYSTEM_RUSRATE_RESTRICTED',$age); break;
		}

		// Getting created page text
		$buffer = JResponse::getBody();
		// Making replacements
		$buffer = str_replace('</body>', '<div class="rr p_'.$position.'"><div class="shape"></div><p class="rrage">'.$age.'+</p><p class="rrmessage">'.$text.'.</p></div></body>', $buffer);

		if ($buffer != '') {
			// Moving page text
			JResponse::setBody($buffer);
		}
		return true;
	}
	
	function onAfterRoute()	{
			$app = JFactory::getApplication();
		    // check if we are not in the admin panel
		    if ($app->getName()!= 'site') {
			  return true;
	     	}

			$document = JFactory::getDocument();
            if ($document->getType() !== 'html') return true;

            if (JRequest::getVar('tmpl') === 'component') return true;

            if (plgSystemRusRate::ShowOnFP()) return true;
			$document->addStyleSheet(JURI::root(true).'/plugins/system/rusrate/css/rusrate.css');
			//Getting params
			$position = $this->params->get('position', 'tl');  
			$age = $this->params->get('age', 18);
			$zindex = $this->params->get('zindex', 800);
			$color = $this->params->get('color', '#FFFFFF');
			$bgcolor = $this->params->get('bgcolor', '#FF0000');
			$colored = $this->params->get('colored', '1');
			$details = $this->params->get('details', 1);
			if($colored){
			if ($age<12) $bgcolor='YellowGreen';
			if (($age>=12)and($age<=16)) $bgcolor='orange';
			if ($age>16) $bgcolor='FireBrick';
			}
			if(!$details){
				$dStyle = '.rrmessage{display:none !important;}';
			}else{
				$dStyle = '.rr:hover .rrage, .rr:hover .shape{display:none;}.rr:hover .rrmessage{display:block;}';
			}
			$style = '
				.p_br .shape, .p_bl .shape{ border-bottom: 75px solid '.$bgcolor.' !important; }
				.p_tr .shape, .p_tl .shape{ border-top: 75px solid '.$bgcolor.' !important; }
				.rrmessage{background-color:'.$bgcolor.';}
				.rr a{color:'.$color.' !important;}
				.rrmessage{color:'.$color.';}
				.rrage{color:'.$color.';}
				.rr {z-index:'.$zindex.';}
				.rrage{z-index:'.($zindex + 1).';}
			';
			$document->addStyleDeclaration($style.$dStyle);
	}

		function ShowOnFP() {

          $fpage = false;

  		  $document = JFactory::getDocument();

     	  $app  = JApplication::getInstance('site');
		  $menu = $app->getMenu();

          //Getting main menu

		  $home = $menu->getDefault($document->language);
		  $active = $menu->getActive();
          //if we are at the homepage
          if (is_object($home) && ($active == $home))
            $fpage=true;

         //at the all pages by default
		 $onlyFP = $this->params->get('onlyFP', 0);

		 if (($onlyFP) && (!$fpage)) {
			return true;
		 } else return false;
		}
}
