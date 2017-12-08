<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
* SportsManagement is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SportsManagement is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von SportsManagement.
*
* SportsManagement ist Freie Software: Sie können es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder späteren
* veröffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es nützlich sein wird, aber
* OHNE JEDE GEWÄHRLEISTUNG, bereitgestellt; sogar ohne die implizite
* Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License für weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');


/**
 * sportsmanagementViewPlayground
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewPlayground extends sportsmanagementView
{
	
	
	/**
	 * sportsmanagementViewPlayground::init()
	 * 
	 * @return
	 */
	public function init ()
	{
		$this->app = JFactory::getApplication();
        $starttime = microtime(); 
        
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
        $this->app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
		
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

        
        
        if ( $this->item->latitude == 255 )
        {
            $this->app->enqueueMessage(JText::_('COM_SPORTSMANAGEMENT_NO_GEOCODE'),'Error');
            $this->map = false;
        }
        else
        {
            $this->map = true;
        }
		
		//$extended = sportsmanagementHelper::getExtended($this->item ->extended, 'playground');
		$this->extended	= sportsmanagementHelper::getExtended($this->item->extended, 'playground');
        
//        $document->addScript('http://maps.google.com/maps/api/js?&sensor=false&language=de');
//        $document->addScript(JURI::root(true).'/administrator/components/com_sportsmanagement/assets/js/gmap3.min.js');

$this->document->addScript((JBrowser::getInstance()->isSSLConnection() ? "https" : "http") . '://maps.googleapis.com/maps/api/js?libraries=places&language=de');
$this->document->addScript(JURI::base() . 'components/'.$this->option.'/assets/js/geocomplete.js');

if( version_compare(JSM_JVERSION,'4','eq') ) 
{
	}
		else
		{		
		$this->document->addScript(JURI::base() . 'components/'.$this->option.'/views/playground/tmpl/edit.js');
		}
//$this->document->addScript(JURI::root(true).'/administrator/components/com_sportsmanagement/assets/js/gmap3.min.js');                    
        //$app->enqueueMessage(JText::_('sportsmanagementViewPlayground display<br><pre>'.print_r($this->extended,true).'</pre>'),'Notice');


	}
 
	
	/**
	 * sportsmanagementViewPlayground::addToolBar()
	 * 
	 * @return void
	 */
	protected function addToolBar() 
	{
		$jinput = JFactory::getApplication()->input;
        $jinput->set('hidemainmenu', true);
        parent::addToolbar();
	}
    
	
}
