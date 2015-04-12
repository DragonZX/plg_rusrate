<?php
# @version		$version 0.9 Amvis United Company Limited  $
# @copyright	Copyright (C) 2015 AUnited Co Ltd. All rights reserved.
# @license		GNU/GPL v2.0
# Updated		11st April 2015
#
# Based on Elcora's StopKids ( http://elcora.com ), under GNU/GPL v2.0
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
		$text='';
		switch($age) {
			case '0': $text='PLG_SYSTEM_RUSRATE_MESSAGE_ZERO'; break;
			case '18': case '21': $text='PLG_SYSTEM_RUSRATE_MESSAGE_ADULT'; break;
			default: $text='RESTRICTED_FOR '.$age.' YEARS_OLD'; break;
		}

		// Getting created page text
		$buffer = JResponse::getBody();
		// Making replacements
		$buffer = str_replace('</body>', '<div id="rr'.$position.'" style=""><div class="rrage'.$position.'">'.$age.'+<div class="rrtip'.$position.'">'.$text.'.</div></div></div></body>', $buffer);

		if ($buffer != '') {
			// Moving page text
			JResponse::setBody($buffer);
		}
		return true;
	}
	
	function onAfterRoute()	{
			$app = JFactory::getApplication();
		    // проверка, что мы не в административной панели
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
			$bgcolor = '';
			$text='';
			if ($age<12) $bgcolor='green';
			if (($age>=12)and($age<=16)) $bgcolor='yellow';
			if ($age>16) $bgcolor='red';
			switch($age) {
				case '0': $text='PLG_SYSTEM_RUSRATE_MESSAGE_ZERO'; break;
				case '18': case '21': $text='PLG_SYSTEM_RUSRATE_MESSAGE_ADULT'; break;
				default: $text='RESTRICTED_FOR'.$age.'YEARS_OLD'; break;
			}
			$style = 
			'#rr, #rrbr, #rrbl, #rrtl, #rrtr {
				background-color: '.$bgcolor.' !important;
				z-index: '.$zindex.';
            }
			.rrage, .rragetl, .rragetr, .rragebl, .rragebr{
				color: '.$color.';
			}
            .rrtip, .rrtiptl, .rrtiptr, .rrtipbl, .rrtipbr {
				background-color: '.$bgcolor.' !important;
				color: '.$color.';
            }';
			$document->addStyleDeclaration($style);
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
