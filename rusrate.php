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

 	    $age = $this->params->get('age', 18);
		$txtmess = $this->params->get('txtmess', '');

		// Getting plugin params
 		$tmpl = $this->params->get('tmpl', 'red');
   		$pos = $this->params->get('pos', 0);

		// Getting created page text
		$buffer = JResponse::getBody();
		// Making replacements
		$buffer = str_replace('</body>', '<div id="rrtl" style="background-color:green;">
	<div class="rragetl">',$rrage,'+<div class="rrtiptl">
     Разрешено для детей старше ',$rrage,' лет
    </div></div>
</div></body>', $buffer);

		if ($buffer != '') {
			// Moving page text
			JResponse::setBody($buffer);
		}
		return true;
	}

		function onAfterRoute()	{

		    $app = JFactory::getApplication();
		    // Checking that we are not in the admin panel
		    if ($app->getName()!= 'site') {
			  return true;
	     	}

			$document = JFactory::getDocument();
            if ($document->getType() !== 'html') return true;

            if (JRequest::getVar('tmpl') === 'component') return true;

            if (plgSystemRusRate::ShowOnFP()) return true;

    		$pos = $this->params->get('pos', 0);
   		    $age = $this->params->get('age', 18);
   		    $zindex = $this->params->get('zindex', 800);
   		    $color = $this->params->get('color', '#FFFFFF');
   		    $bgcolor = $this->params->get('bgcolor', '#BC121F');
   		    $tmpl = $this->params->get('tmpl', 'red');

            $document->addStyleSheet(JURI::root(true).'/plugins/system/rusrate/css/rusrate.css');
         
            $document->addStyleDeclaration('
             #rr, #rrbr, #rrbl, #rrtl, #rrtr {
                z-index: '.$zindex.';
            }
            .rrtip, .rrtiptl, .rrtiptr, .rrtipbl, .rrtipbr {
               background-color: '.$bgcolor.' !important;
               color: '.$color.';
            }');
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
