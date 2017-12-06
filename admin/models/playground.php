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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
//jimport('joomla.application.component.modeladmin');
 

/**
 * sportsmanagementModelPlayground
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelPlayground extends JSMModelAdmin
{
    
    static $playground = NULL;
    static $cfg_which_database = 0;
    
    /**
     * sportsmanagementModelplayground::getAddressString()
     * 
     * @return
     */
    function getAddressString()
	{
		$playground = self::getPlayground();
		if ( !isset ( $playground ) ) { return null; }
 		$address_parts = array();
		if (!empty($playground->address))
		{
			$address_parts[] = $playground->address;
		}
		if (!empty($playground->state))
		{
			$address_parts[] = $playground->state;
		}
		if (!empty($playground->location))
		{
			if (!empty($playground->zipcode))
 			{
				$address_parts[] = $playground->zipcode. ' ' .$playground->location;
			}
			else
			{
				$address_parts[] = $playground->location;
			}
		}
		if (!empty($playground->country))
		{
			$address_parts[] = JSMCountries::getShortCountryName($playground->country);
		}
		$address = implode(', ', $address_parts);
		return $address;
	}
    
    
    /**
     * sportsmanagementModelPlayground::getNextGames()
     * 
     * @param integer $project
     * @return
     */
    function getNextGames( $project = 0, $pgid = 0 )
    {
        $option = JFactory::getApplication()->input->getCmd('option');
	    $app = JFactory::getApplication();
        // Get a db connection.
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        
        $result = array();
        $starttime = microtime(); 

        $playground = self::getPlayground($pgid);
        if ( $playground->id > 0 )
        {
            $query->select('m.*, DATE_FORMAT(m.time_present, \'%H:%i\') time_present');
            $query->select('p.name AS project_name');
            $query->select('st1.team_id AS team1');
            $query->select('st2.team_id AS team2');
            $query->from('#__sportsmanagement_match AS m ');
            $query->join('INNER',' #__sportsmanagement_project_team tj ON tj.id = m.projectteam1_id  ');
            $query->join('INNER',' #__sportsmanagement_project_team tj2 ON tj2.id = m.projectteam2_id  ');
            $query->join('INNER',' #__sportsmanagement_project AS p ON p.id = tj.project_id ');
            $query->join('INNER',' #__sportsmanagement_season_team_id as st1 ON st1.id = tj.team_id ');
            $query->join('INNER',' #__sportsmanagement_season_team_id as st2 ON st2.id = tj2.team_id ');
            //$query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_team AS t ON t.id = st.team_id ');
            //$query->join('INNER',' #__'.COM_SPORTSMANAGEMENT_TABLE.'_club AS c ON c.id = t.club_id ');
            
            $query->where('m.playground_id = '. (int)$playground->id);
            $query->where('m.match_date > NOW()');
            $query->where('m.published = 1');
            $query->where('p.published = 1');

            if ( $project )
            {
                $query->where('p.id = '. (int)$project);
            }
            
            $query->group('m.id');
            $query->order('match_date ASC');
            
            $db->setQuery( $query );
            $result = $db->loadObjectList();
        }
        
        if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
        return $result;
    }
    
    
    /**
     * sportsmanagementModelPlayground::updateHits()
     * 
     * @param integer $pgid
     * @param integer $inserthits
     * @return void
     */
    public static function updateHits($pgid=0,$inserthits=0)
    {
        $option = JFactory::getApplication()->input->getCmd('option');
	$app = JFactory::getApplication();
    $db = JFactory::getDbo();
 $query = $db->getQuery(true);
 
 if ( $inserthits )
 {
 $query->update($db->quoteName('#__sportsmanagement_playground'))->set('hits = hits + 1')->where('id = '.$pgid);
 
$db->setQuery($query);
 
$result = $db->execute();
$db->disconnect(); // See: http://api.joomla.org/cms-3/classes/JDatabaseDriver.html#method_disconnect	 
}  
//$app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($db->getErrorMsg(),true).'</pre>'),'Error');     
    }
    
    /**
     * sportsmanagementModelPlayground::getPlayground()
     * 
     * @param integer $pgid
     * @param integer $inserthits
     * @return
     */
    public static function getPlayground( $pgid = 0,$inserthits=0 )
    {
        $option = JFactory::getApplication()->input->getCmd('option');
	    $app = JFactory::getApplication();
        $db = sportsmanagementHelper::getDBConnection(TRUE, $app->getUserState( "com_sportsmanagement.cfg_which_database", FALSE ) );
        $query = $db->getQuery(true);
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
            $my_text = 'playground <br><pre>'.print_r(self::$playground,true).'</pre>'; 
        
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text);
        }
        /*
        if ( JComponentHelper::getParams($option)->get('show_debug_info_frontend') )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' playground<br><pre>'.print_r(self::$playground,true).'</pre>'),'');    
        }
        */
        self::updateHits($pgid,$inserthits); 
        
        if ( is_null( self::$playground ) )
        {
            if ( $pgid < 1 )
            {
            $pgid = JFactory::getApplication()->input->getInt( "pgid", 0 );
            }    
            
            if ( $pgid > 0 )
            {
                $query->select('*');
                $query->from('#__sportsmanagement_playground');
                $query->where('id = '. $pgid);
                $db->setQuery( $query );
                
                if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
            $my_text = 'query <br><pre>'.print_r($query->dump(),true).'</pre>'; 
        sportsmanagementHelper::setDebugInfoText(__METHOD__,__FUNCTION__,__CLASS__,__LINE__,$my_text);
                }
                
                self::$playground = $db->loadObject();

            }
        }
        $db->disconnect(); // See: http://api.joomla.org/cms-3/classes/JDatabaseDriver.html#method_disconnect
        return self::$playground;
    }
    
    
    
    
}
