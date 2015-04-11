<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );

class  plgSystemStopKids extends JPlugin {

    function onAfterRender() {

     	$app = JFactory::getApplication();
		// Checking that we are not in the admin panel
		if ($app->getName()!= 'site') {
			return true;
		}

    	$document = JFactory::getDocument();
        if ($document->getType() !== 'html') return true;

        if (JRequest::getVar('tmpl') === 'component') return true;

        if (plgSystemStopKids::ShowOnFP()) return true;

        JPlugin::loadLanguage('plg_system_stopkids', JPATH_ADMINISTRATOR);

 	    $age = $this->params->get('age', 18);
		$txtmess = $this->params->get('txtmess', '');

		if (trim($txtmess) == '') {
			switch ($age) {
             case 0:
               $txtmess = JTEXT::_('PLG_SYSTEM_STOPKIDS_MESSAGE_ZERO');
             break;
             case 6:
               $txtmess = JTEXT::_('PLG_SYSTEM_STOPKIDS_MESSAGE_SIX');
             break;
             case 18:
               $txtmess = JTEXT::_('PLG_SYSTEM_STOPKIDS_MESSAGE_EIGHTING');
             break;
             default:
               $txtmess = JTEXT::sprintf('PLG_SYSTEM_STOPKIDS_MESSAGE', $age);
            }
        }
        else $txtmess = str_replace('%s',$age,$txtmess);

		// Getting plugin params
 		$tmpl = $this->params->get('tmpl', 'red');
   		$pos = $this->params->get('pos', 0);

        switch ($pos) {
             case 0:
              $folder = 'top_right';
             break;
             case 1:
              $folder = 'top_left';
             break;
             case 2:
              $folder = 'bottom_right';
             break;
             case 3:
              $folder = 'bottom_left';
             break;
        }

		// Getting created page text
		$buffer = JResponse::getBody();
		// Making replacements
		$buffer = str_replace('</body>', '<a class="stopkids" target="_blank" href="'.$this->params->get('infoURL', JTEXT::_('PLG_SYSTEM_STOPKIDS_URL')).'">
		<img style="border: 0px none;" src="'.JURI::root(true).'/plugins/system/stopkids/themes/'.$tmpl.'/'.$folder.'/'.$age.'+.png">
		</a><a class="stopkids" target="_blank" href="'.$this->params->get('infoURL', JTEXT::_('PLG_SYSTEM_STOPKIDS_URL')).'"><span class="tip">'.$txtmess.'</span>
		</a></body>', $buffer);

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

            if (plgSystemStopKids::ShowOnFP()) return true;

    		$pos = $this->params->get('pos', 0);
   		    $age = $this->params->get('age', 18);
   		    $zindex = $this->params->get('zindex', 999);
   		    $color = $this->params->get('color', '#FFFFFF');
   		    $bgcolor = $this->params->get('bgcolor', '#BC121F');
   		    $tmpl = $this->params->get('tmpl', 'red');

            $document->addStyleSheet(JURI::root(true).'/plugins/system/stopkids/css/stopkids.css');

            switch ($pos) {
             case 0:
              $document->addStyleDeclaration('
              .stopkids, .stopkids:hover {
                right: 0;
                top: 0;
              }
              .stopkids .tip {
             	right: 30px;
                top: 30px;
              }');
             break;
             case 1:
              $document->addStyleDeclaration('
              .stopkids, .stopkids:hover {
                left: 0;
                top: 0;
              }
              .stopkids .tip {
             	left: 30px;
                top: 30px;
              }');
             break;
             case 2:
              $document->addStyleDeclaration('
              .stopkids, .stopkids:hover {
                right: 0;
                bottom: 0;
              }
              .stopkids .tip {
             	right: 30px;
                bottom: 30px;
              }');
             break;
             case 3:
              $document->addStyleDeclaration('
              .stopkids, .stopkids:hover {
                left: 0;
                bottom: 0;
              }
              .stopkids .tip {
             	left: 30px;
                bottom: 30px;
              }');
             break;
            }

            $document->addStyleDeclaration('
             .stopkids {
                z-index: '.$zindex.';
            }
            .stopkids .tip {
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
