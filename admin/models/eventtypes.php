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

/**
 * Sportsmanagement Component Events Model
 *
 * @package	Sportsmanagement
 * @since	1.5.0a
 */
class sportsmanagementModelEventtypes extends JSMModelList
{
	var $_identifier = "eventtypes";
	
    /**
     * sportsmanagementModelEventtypes::__construct()
     * 
     * @param mixed $config
     * @return void
     */
    public function __construct($config = array())
        {   
                $config['filter_fields'] = array(
                        'obj.name',
                        'obj.icon',
                        'obj.sports_type_id',
                        'obj.published',
                        'obj.modified',
                        'obj.modified_by',
                        'obj.id',
                        'obj.ordering'
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
	   if ( JComponentHelper::getParams($this->jsmoption)->get('show_debug_info') )
        {
	    $this->jsmapp->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' context -> '.$this->context.''),'');
        $this->jsmapp->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' identifier -> '.$this->_identifier.''),'');
        }
        
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $published);
        $temp_user_request = $this->getUserStateFromRequest($this->context.'.filter.sports_type', 'filter_sports_type', '');
		$this->setState('filter.sports_type', $temp_user_request);
        $value = $this->getUserStateFromRequest($this->context . '.list.limit', 'limit', $this->jsmapp->get('list_limit'), 'int');
		$this->setState('list.limit', $value);	

		// List state information.
		//parent::populateState($ordering, $direction);
        $value = $this->getUserStateFromRequest($this->context . '.list.start', 'limitstart', 0, 'int');
		$this->setState('list.start', $value);
		// Filter.order
		$orderCol = $this->getUserStateFromRequest($this->context. '.filter_order', 'filter_order', '', 'string');
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'obj.name';
		}
		$this->setState('list.ordering', $orderCol);
		$listOrder = $this->getUserStateFromRequest($this->context. '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'ASC';
		}
		$this->setState('list.direction', $listOrder);
	}
    
	/**
	 * sportsmanagementModelEventtypes::getListQuery()
	 * 
	 * @return
	 */
	function getListQuery()
	{
		// Create a new query object.		
		$this->jsmquery->clear();
		
        // Select some fields
		$this->jsmquery->select('obj.*');
        // From table
		$this->jsmquery->from('#__sportsmanagement_eventtype as obj');
        // Join over the sportstype
		$this->jsmquery->select('st.name AS sportstype');
		$this->jsmquery->join('LEFT', '#__sportsmanagement_sports_type AS st ON st.id = obj.sports_type_id');
        // Join over the users for the checked out user.
		$this->jsmquery->select('uc.name AS editor');
		$this->jsmquery->join('LEFT', '#__users AS uc ON uc.id = obj.checked_out');
                
        if ($this->getState('filter.search'))
		{
        $this->jsmquery->where('LOWER(obj.name) LIKE '.$this->jsmdb->Quote('%'.$this->getState('filter.search').'%'));
        }
		
        if (is_numeric($this->getState('filter.state')))
		{
        $this->jsmquery->where('obj.published = '.$this->getState('filter.state'));
        }
        
        if ($this->getState('filter.sports_type'))
		{
        $this->jsmquery->where('obj.sports_type_id = '.$this->getState('filter.sports_type') );
        }
        
        $this->jsmquery->order($this->jsmdb->escape($this->getState('list.ordering', 'obj.name')).' '.
                $this->jsmdb->escape($this->getState('list.direction', 'ASC')));
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $my_text = ' <br><pre>'.print_r($this->jsmquery->dump(),true).'</pre>';    
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text); 
        }
        //$this->jsmapp->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($this->jsmquery->dump(),true).'</pre>'),'Notice');
		return $this->jsmquery;
        
	}
    
	/**
	 * sportsmanagementModelEventtypes::getEvents()
	 * 
	 * @param integer $sports_type_id
	 * @return
	 */
	public static function getEvents($sports_type_id  = 0)
	{
		//$option = JFactory::getApplication()->input->getCmd('option');
		//$app = JFactory::getApplication();
        $this->jsmdb = sportsmanagementHelper::getDBConnection();
        $this->jsmquery = $this->jsmdb->getQuery(true);
        // Select some fields
		$this->jsmquery->clear();
		$this->jsmquery->select('evt.id AS value, concat(evt.name, " (" , st.name, ")") AS text,evt.name as posname,st.name AS stname');
        // From table
		$this->jsmquery->from('#__sportsmanagement_eventtype as evt');
        // Join over the sportstype
		$this->jsmquery->join('LEFT', '#__sportsmanagement_sports_type AS st ON st.id = evt.sports_type_id');
        $this->jsmquery->where('evt.published = 1');
        if ( $sports_type_id )
        {
            $this->jsmquery->where('evt.sports_type_id = '.$sports_type_id);
        }
        $query->order('evt.name ASC');
                
		$this->jsmdb->setQuery($this->jsmquery);
		if ( !$result = $this->jsmdb->loadObjectList() )
		{
			//sportsmanagementModeldatabasetool::writeErrorLog(__METHOD__, __FUNCTION__, __FILE__, JFactory::getDbo()->getErrorMsg(), __LINE__);
			return false;
		}
		foreach ($result as $position)
        {
            //$position->text = JText::_($position->text);
            $position->text = JText::_($position->posname).' ('.JText::_($position->stname).')';
        }
		return $result;
	}
    
    /**
	* Method to return the position events array (id,name)
	*
	* @access  public
	* @return  array
	* @since 0.1
	*/
	function getEventsPosition($id=0)
	{
		//$option = JFactory::getApplication()->input->getCmd('option');
		//$app = JFactory::getApplication();
        //$db		= $this->getDbo();
        //$db = sportsmanagementHelper::getDBConnection();
//		$query = $db->getQuery(true);
        // Select some fields
		$this->jsmquery->clear();
		$this->jsmquery->select('p.id AS value,p.name as posname,st.name AS stname,concat(p.name, \' (\' , st.name, \')\') AS text');
        // From table
		$this->jsmquery->from('#__sportsmanagement_eventtype AS p');
        // Join over the sportstype
		$this->jsmquery->join('LEFT', '#__sportsmanagement_position_eventtype AS pe ON pe.eventtype_id=p.id');
		$this->jsmquery->join('LEFT', '#__sportsmanagement_sports_type AS st ON st.id = p.sports_type_id');
        
        if ( $id )
        {
        $this->jsmquery->where('pe.position_id = '.$id);
        }
        
        $this->jsmquery->order('pe.ordering ASC');

		$this->jsmdb->setQuery($this->jsmquery);
		if ( !$result = $this->jsmdb->loadObjectList())
		{
			//sportsmanagementModeldatabasetool::writeErrorLog(__METHOD__, __FUNCTION__, __FILE__, JFactory::getDbo()->getErrorMsg(), __LINE__);
			return false;
		}
		foreach ($result as $event)
        {
            //$event->text = JText::_($event->text);
            $event->text = JText::_($event->posname).' ('.JText::_($event->stname).')';
        }
		return $result;
	}
    
    /**
     * sportsmanagementModelEventtypes::getEventList()
     * 
     * @return
     */
    public function getEventList()
	{
		$query='SELECT *,id AS value,name AS text FROM #__'.COM_SPORTSMANAGEMENT_TABLE.'_eventtype ORDER BY name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
    
    
    

}
?>
