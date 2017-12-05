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
* OHNE JEDE GEWÄHELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gewährleistung der MARKTFÄHIGKEIT oder EIGNUNG FÜR EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License für weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');


//jimport('joomla.application.component.modellist');


/**
 * sportsmanagementModelPlaygrounds
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelPlaygrounds extends JSMModelList 
{
	var $_identifier = "playgrounds";
	
    public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'v.name',
                        'v.alias',
                        'v.short_name',
                        'v.max_visitors',
                        'v.picture',
                        'v.country',
                        'club',
                        'v.id',
                        'v.ordering'
                        );
                parent::__construct($config);
                $getDBConnection = sportsmanagementHelper::getDBConnection();
                parent::setDbo($getDBConnection);
        }
        
    /**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		//$app = JFactory::getApplication();
        //$option = JRequest::getCmd('option');
        // Initialise variables.
	//	$app = JFactory::getApplication('administrator');
        
        //$app->enqueueMessage(JText::_('sportsmanagementModelsmquotes populateState context<br><pre>'.print_r($this->context,true).'</pre>'   ),'');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
        $temp_user_request = $this->getUserStateFromRequest($this->context.'.filter.search_nation', 'filter_search_nation', '');
		$this->setState('filter.search_nation', $temp_user_request);
        
        $value = $this->jsmjinput->getUInt('limitstart', 0);
		$this->setState('list.start', $value);

//		$image_folder = $this->getUserStateFromRequest($this->context.'.filter.image_folder', 'filter_image_folder', '');
//		$this->setState('filter.image_folder', $image_folder);
        
        //$app->enqueueMessage(JText::_(get_class($this).' '.__FUNCTION__.' image_folder<br><pre>'.print_r($image_folder,true).'</pre>'),'');


//		// Load the parameters.
//		$params = JComponentHelper::getParams('com_sportsmanagement');
//		$this->setState('params', $params);

		// List state information.
		parent::populateState('v.name', 'asc');
	}
    
	/**
	 * sportsmanagementModelPlaygrounds::getListQuery()
	 * 
	 * @return
	 */
	function getListQuery()
	{
		//$app = JFactory::getApplication();
        //$option = JRequest::getCmd('option');
        
        // Create a new query object.
	//	$db		= $this->getDbo();
	//	$query	= $db->getQuery(true);
	//	$user	= JFactory::getUser(); 
		
        // Select some fields
		$this->jsmquery->select('v.*');
        // From table
		$this->jsmquery->from('#__sportsmanagement_playground as v');
        // Join over the clubs
		$this->jsmquery->select('c.name As club');
		$this->jsmquery->join('LEFT', '#__sportsmanagement_club AS c ON c.id = v.club_id');
        // Join over the users for the checked out user.
		$this->jsmquery->select('uc.name AS editor');
		$this->jsmquery->join('LEFT', '#__users AS uc ON uc.id = v.checked_out');
        
        
        if ($this->getState('filter.search'))
		{
        $this->jsmquery->where('LOWER(v.name) LIKE '.$this->jsmdb->Quote('%'.$this->getState('filter.search').'%'));
        }
        if ($this->getState('filter.search_nation'))
		{
        $this->jsmquery->where("v.country LIKE '".$this->getState('filter.search_nation')."'");
        }

        
        $this->jsmquery->order($this->jsmdb->escape($this->getState('list.ordering', 'v.name')).' '.
                $this->jsmdb->escape($this->getState('list.direction', 'ASC')));
        
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $my_text = ' <br><pre>'.print_r($this->jsmquery->dump(),true).'</pre>';    
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text); 
        }
        
        
		return $this->jsmquery;
        
        
        
	}




    
    
    /**
	 * Method to return a playground/venue array (id,text)
		*
		* @access	public
		* @return	array
		* @since 0.1
		*/
	function getPlaygrounds()
	{
	  // $app = JFactory::getApplication();
        //$option = JRequest::getCmd('option');
	//	$db		= JFactory::getDbo();
	//	$query	= $db->getQuery(true);
        $starttime = microtime(); 
        $results = array();
        
		$this->jsmquery='SELECT id AS value, name AS text FROM #__sportsmanagement_playground ORDER BY text ASC ';
		$this->jsmdb->setQuery($this->jsmquery);
		if ( !$result = $this->jsmdb->loadObjectList() )
		{
			//sportsmanagementModeldatabasetool::writeErrorLog(get_class($this), __FUNCTION__, __FILE__, $db->getErrorMsg(), __LINE__);
			return false;
		}
		return $result;
	}
    
    /**
     * sportsmanagementModelPlaygrounds::getPlaygroundListSelect()
     * 
     * @return
     */
    public function getPlaygroundListSelect()
	{
	   //$app = JFactory::getApplication();
        //$option = JRequest::getCmd('option');
	//	$db		= JFactory::getDbo();
	//	$query	= $db->getQuery(true);
        $starttime = microtime(); 
        $results = array();
        
         // Select some fields
		$this->jsmquery->select('id,name,id AS value,name AS text,short_name,club_id');
        // From table
		$this->jsmquery->from('#__sportsmanagement_playground');
        $this->jsmquery->order('name');
        
		//$query='SELECT id,name,id AS value,name AS text,short_name,club_id FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_playground ORDER BY name';
		$this->jsmdb->setQuery($this->jsmquery);
		if ( $results = $this->jsmdb->loadObjectList() )
		{
			return $results;
		}
		//return false;
        return $results;
	}
    
    

	
}
?>
