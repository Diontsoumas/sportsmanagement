<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      teamperson.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage models
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

/**
 * sportsmanagementModelteamperson
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelteamperson extends JSMModelAdmin
{
	
    var $_project_id = 0;
    var $_team_id = 0;
    var $_project_team_id = 0;
    static $db_num_rows = 0;


/**
 * sportsmanagementModelteamperson::assignplayerscountry()
 * 
 * @param integer $persontype
 * @param integer $project_team_id
 * @param integer $team_id
 * @param integer $pid
 * @param integer $season_id
 * @return void
 */
function assignplayerscountry($persontype=1,$project_team_id=0,$team_id=0,$pid=0,$season_id=0)
{
//$this->jsmapp->enqueueMessage(__METHOD__.' '.__LINE__. ' persontype <br><pre>'.print_r($persontype, true).'</pre><br>','Notice');	
//$this->jsmapp->enqueueMessage(__METHOD__.' '.__LINE__. ' project_team_id <br><pre>'.print_r($project_team_id, true).'</pre><br>','Notice');
//$this->jsmapp->enqueueMessage(__METHOD__.' '.__LINE__. ' team_id <br><pre>'.print_r($team_id, true).'</pre><br>','Notice');
//$this->jsmapp->enqueueMessage(__METHOD__.' '.__LINE__. ' pid <br><pre>'.print_r($pid, true).'</pre><br>','Notice');
//$this->jsmapp->enqueueMessage(__METHOD__.' '.__LINE__. ' season_id <br><pre>'.print_r($season_id, true).'</pre><br>','Notice');	

$this->jsmquery->clear();
$this->jsmquery->select('c.country');
$this->jsmquery->from('#__sportsmanagement_club as c');
$this->jsmquery->join('INNER','#__sportsmanagement_team as t on t.club_id = c.id');
$this->jsmquery->where('t.id = '.$team_id);	
$this->jsmdb->setQuery($this->jsmquery);
$this->country = $this->jsmdb->loadResult();	
//$this->jsmapp->enqueueMessage(__METHOD__.' '.__LINE__. ' country <br><pre>'.print_r($this->country, true).'</pre><br>','Notice');		
	
$this->jsmquery->clear();	
$this->jsmquery->select('person_id');
$this->jsmquery->from('#__sportsmanagement_season_team_person_id');
$this->jsmquery->where('team_id = '.$team_id );
$this->jsmquery->where('season_id = '.$season_id) ;
$this->jsmquery->where('persontype = '.$persontype );	
$this->jsmdb->setQuery($this->jsmquery);
$this->person_list = $this->jsmdb->loadObjectList();
//$this->jsmapp->enqueueMessage(__METHOD__.' '.__LINE__. ' person_list <br><pre>'.print_r($this->person_list, true).'</pre><br>','Notice');			

foreach ( $this->person_list as $row )
{
$rowInsert = new stdClass();
$rowInsert->id = $row->person_id ;
$rowInsert->country = $this->country;
$result = Factory::getDbo()->updateObject('#__sportsmanagement_person', $rowInsert, 'id'); 	
}
	
return true;	
}
	
	

/**
 * sportsmanagementModelteamperson::set_state()
 * 
 * @param mixed $ids
 * @param mixed $tpids
 * @param mixed $state
 * @param integer $pid
 * @return void
 */
function set_state($ids,$tpids,$state,$pid=0)
{	
$this->jsmuser = Factory::getUser(); 
$this->jsmdate = Factory::getDate();

for ($x=0; $x < count($ids); $x++)
{
$person_id = $ids[$x];		  
$season_team_person_id = $tpids[$person_id];          
// Create an object for the record we are going to update.
$object = new stdClass();
// Must be a valid primary key value.
$object->id = $season_team_person_id;
$object->published = $state;
$object->modified = $this->jsmdate->toSql();
$object->modified_by = $this->jsmuser->get('id');
// Update their details in the table using id as the primary key.
$result = Factory::getDbo()->updateObject('#__sportsmanagement_season_team_person_id', $object, 'id'); 

$this->jsmquery->clear();
// Fields to update.
$fields = array(
    $this->jsmdb->quoteName('published') . ' = ' . $state,
    $this->jsmdb->quoteName('modified') . ' = '.$this->jsmdb->Quote( '' . $this->jsmdate->toSql() . '' ) ,
    $this->jsmdb->quoteName('modified_by') . ' = '.$this->jsmuser->get('id')
);

// Conditions for which records should be updated.
$conditions = array(
    $this->jsmdb->quoteName('person_id') . ' = '.$person_id, 
    $this->jsmdb->quoteName('project_id') . ' = ' . $pid 
);

try{
$this->jsmquery->update($this->jsmdb->quoteName('#__sportsmanagement_person_project_position'))->set($fields)->where($conditions);
$this->jsmdb->setQuery($this->jsmquery);
$resultupdate = $this->jsmdb->execute();
}
        catch (Exception $e)
        {
        $this->jsmapp->enqueueMessage(Text::_(__METHOD__.' '.' '.__LINE__.' '.$e->getMessage()), 'error');
        //return false;
        }



} 
         
}
	
    /**
	 * Method to update checked teamplayers
	 *
	 * @access	public
	 * @return	boolean	True on success
	 *
	 */
	function saveshort()
	{
		$app = Factory::getApplication();
        $date = Factory::getDate();
	   $user = Factory::getUser();
        /* Ein Datenbankobjekt beziehen */
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        // Get the input
        $pks = Factory::getApplication()->input->getVar('cid', null, 'post', 'array');
        //$post = Factory::getApplication()->input->post->getArray(array());
        $post = $jinput->post->getArray(array());
        $this->_project_id	= $post['pid'];
        $this->persontype	= $post['persontype'];
        
//        $app->enqueueMessage('saveshort pks<br><pre>'.print_r($pks, true).'</pre><br>','Notice');
//        $app->enqueueMessage('saveshort post<br><pre>'.print_r($post, true).'</pre><br>','Notice');
        
        $result = true;
		
// ###############################
// update der positionen bei den spielen, wenn keine vorhanden sind
//build the html options for position
$position_ids = array();        
$mdlPositions = BaseDatabaseModel::getInstance('Positions', 'sportsmanagementModel');
$project_ref_positions = $mdlPositions->getProjectPositions($this->project_id, 1);
if ($project_ref_positions) {
            $position_ids = array_merge($position_ids, $project_ref_positions);
        }

for ($x=0; $x < count($pks); $x++)
{
$team_player_id = $post['tpid'][$pks[$x]];
$project_position_id = $post['project_position_id'.$pks[$x]];
foreach($position_ids as $items => $item) {
    if($item->value == $project_position_id) {
       $results = $item->position_id;
    }
} 	

$this->jsmquery->clear();
// Fields to update.
$fields = array(
    $this->jsmdb->quoteName('project_position_id') . ' = ' . $results
);

// Conditions for which records should be updated.
$conditions = array(
    $this->jsmdb->quoteName('project_position_id') . ' = 0', 
    $this->jsmdb->quoteName('teamplayer_id') . ' = ' . $team_player_id 
);

try{
$this->jsmquery->update($this->jsmdb->quoteName('#__sportsmanagement_match_player'))->set($fields)->where($conditions);
$this->jsmdb->setQuery($this->jsmquery);
$resultupdate = $this->jsmdb->execute();
}
catch (Exception $e)
{
//$this->jsmapp->enqueueMessage(Text::_(__METHOD__.' '.' '.__LINE__.' '.$e->getMessage()), 'error');
}
}
// ###############################
	
		for ($x=0; $x < count($pks); $x++)
		{
		  
          if ( $post['jerseynumber'.$pks[$x]] == '' )
          {
            $post['jerseynumber'.$pks[$x]] = 0;
          }
          if ( $post['market_value'.$pks[$x]] == '' )
          {
            $post['market_value'.$pks[$x]] = 0;
          }
         
            
            // Fields to update.
$fields = array(
    $db->quoteName('project_position_id') . ' = ' . $post['project_position_id'.$pks[$x]],
    $db->quoteName('jerseynumber') . ' = '.$post['jerseynumber'.$pks[$x]],
    $db->quoteName('market_value') . ' = '.$post['market_value'.$pks[$x]],
    $db->quoteName('modified') . ' = '.$db->Quote( '' . $date->toSql() . '' ) ,
    $db->quoteName('modified_by') . ' = '.$user->get('id')
    
);
 
// Conditions for which records should be updated.
$conditions = array(
    $db->quoteName('id') . ' = '.$post['person_id'.$pks[$x]]
);

//exit;

$query->clear(); 
$query->update($db->quoteName('#__sportsmanagement_season_team_person_id'))->set($fields)->where($conditions);
$db->setQuery($query);

			//if(!$tblPerson->store()) 
            if( !sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__) )
            {
                $app->enqueueMessage(__FILE__.' '.get_class($this).' '.__FUNCTION__.' <br><pre>'.print_r($this->_db->getErrorMsg(), true).'</pre><br>','Error');
				sportsmanagementModeldatabasetool::writeErrorLog(get_class($this), __FUNCTION__, __FILE__, $this->_db->getErrorMsg(), __LINE__);
				$result=false;
			}
            else
            {
                $tblprojectposition = Table::getInstance("projectposition", "sportsmanagementTable");
                $tblprojectposition->load((int) $post['project_position_id'.$pks[$x]]);
               
                $tblperson = Table::getInstance("person", "sportsmanagementTable");
                $tblperson->load((int) $pks[$x]);
                $tblperson->position_id = $tblprojectposition->position_id;
                $tblperson->country = $post['country'.$pks[$x]];
		    
                if (!$tblperson->store())
	            {
		        $app->enqueueMessage(__FILE__.' '.__METHOD__.' '.__LINE__.' <br><pre>'.print_r($tblperson->getErrorMsg(), true).'</pre><br>','Error');
	            }
                
                // alten eintrag löschen
                // Create a new query object.
                $query = $db->getQuery(true);
                // delete all
                $conditions = array(
                $db->quoteName('person_id') . '='.$pks[$x],
                $db->quoteName('project_id') . '='.$this->_project_id,
                $db->quoteName('persontype') . '='.$this->persontype
                );
 
                $query->delete($db->quoteName('#__sportsmanagement_person_project_position'));
                $query->where($conditions);
 
                $db->setQuery($query); 
                sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
                if ( self::$db_num_rows )
                {
                $app->enqueueMessage(Text::sprintf('COM_'.strtoupper('sportsmanagement_person_project_position').'_ITEMS_DELETED',self::$db_num_rows),'');
                } 

                                  
                // Create and populate an object.
                $profile = new stdClass();
                //$profile->person_id = $post['person_id'.$pks[$x]];
                $profile->person_id = $pks[$x];
                $profile->project_id = $this->_project_id;
                $profile->project_position_id = $post['project_position_id'.$pks[$x]];
                $profile->persontype = $this->persontype;
                $profile->published = $post['project_published'.$pks[$x]];
                // Insert the object into table.
                $result = Factory::getDbo()->insertObject('#__sportsmanagement_person_project_position', $profile);
                
                if (!$result)
	            {
		        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($db->getErrorMsg(), true).'</pre><br>','Error');
	            }
                
                //$app->enqueueMessage(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($profile, true).'</pre><br>','Error');
                
            }
		}
		return $result;
	}
    
    

    
    /**
	 * Method to remove teamplayer
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	public function delete(&$pks)
	{
	$app = Factory::getApplication();
    
    // JInput object
        $jinput = $app->input;
        $post = $jinput->post->getArray(array());
        $option = $jinput->getCmd('option');
        
        $project_team_id = $post['project_team_id'];
        $team_id = $post['team_id'];
	$season_id = $post['season_id'];
        $pid = $post['pid'];
	$tpid = $post['tpid'];
        $persontype = $post['persontype'];
        
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' project_team_id<br><pre>'.print_r($project_team_id, true).'</pre><br>','Notice');
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' team_id<br><pre>'.print_r($team_id, true).'</pre><br>','Notice');
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' pid<br><pre>'.print_r($pid, true).'</pre><br>','Notice');
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' persontype<br><pre>'.print_r($persontype, true).'</pre><br>','Notice');
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' tpid<br><pre>'.print_r($tpid, true).'</pre><br>','Notice');
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' pks<br><pre>'.print_r($pks, true).'</pre><br>','Notice');
        //$app->enqueueMessage(__METHOD__.' '.__LINE__.' post<br><pre>'.print_r($post, true).'</pre><br>','Notice');
    
    
    
    //$app->enqueueMessage(Text::_('delete pks<br><pre>'.print_r($pks,true).'</pre>'),'');
    /* Ein Datenbankobjekt beziehen */
    $db = Factory::getDbo();
    /* Ein JDatabaseQuery Objekt beziehen */
    $query = $db->getQuery(true);
    
	$result = false;
    if (count($pks))
		{
	    foreach( $pks as $key => $value )
	    {
		$delete_all[] = $tpid[$value];    
	    }
//$app->enqueueMessage(__METHOD__.' '.__LINE__.' delete_all<br><pre>'.print_r($delete_all, true).'</pre><br>','Notice');
	    
			$cids = implode(',',$delete_all);
	    $perspropos = implode(',',$pks);
                        
            // delete all 
$conditions = array(
    $db->quoteName('teamplayer_id') . ' IN ('.$cids.')'
);
$query->clear(); 
$query->delete($db->quoteName('#__sportsmanagement_match_player'));
$query->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
            if ( self::$db_num_rows )
            {
            $app->enqueueMessage(Text::sprintf('COM_'.strtoupper('sportsmanagement_match_player').'_ITEMS_DELETED',self::$db_num_rows),'');
            }
            
            // delete all 
$conditions = array(
    $db->quoteName('in_for') . ' IN ('.$cids.')'
);
$query->clear(); 
$query->delete($db->quoteName('#__sportsmanagement_match_player'));
$query->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
            if ( self::$db_num_rows )
            {
            $app->enqueueMessage(Text::sprintf('COM_'.strtoupper('sportsmanagement_match_player').'_ITEMS_DELETED',self::$db_num_rows),'');
            }
            
	    // delete all 
$conditions = array(
    $db->quoteName('team_staff_id') . ' IN ('.$cids.')'
);
$query->clear(); 
$query->delete($db->quoteName('#__sportsmanagement_match_staff'));
$query->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
            if ( self::$db_num_rows )
            {
            $app->enqueueMessage(Text::sprintf('COM_'.strtoupper('sportsmanagement_match_staff').'_ITEMS_DELETED',self::$db_num_rows),'');
            }
	    
            // delete all 
$conditions = array(
    $db->quoteName('teamplayer_id') . ' IN ('.$cids.')'
);
$query->clear(); 
$query->delete($db->quoteName('#__sportsmanagement_match_statistic'));
$query->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
            if ( self::$db_num_rows )
            {
            $app->enqueueMessage(Text::sprintf('COM_'.strtoupper('sportsmanagement_match_statistic').'_ITEMS_DELETED',self::$db_num_rows),'');
            } 
            
	    // delete all 
$conditions = array(
    $db->quoteName('team_staff_id') . ' IN ('.$cids.')'
);
$query->clear(); 
$query->delete($db->quoteName('#__sportsmanagement_match_staff_statistic'));
$query->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
            if ( self::$db_num_rows )
            {
            $app->enqueueMessage(Text::sprintf('COM_'.strtoupper('sportsmanagement_match_staff_statistic').'_ITEMS_DELETED',self::$db_num_rows),'');
            }
	    
            // delete all 
$conditions = array(
    $db->quoteName('teamplayer_id') . ' IN ('.$cids.')'
);
$query->clear(); 
$query->delete($db->quoteName('#__sportsmanagement_match_event'));
$query->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
            if ( self::$db_num_rows )
            {
            $app->enqueueMessage(Text::sprintf('COM_'.strtoupper('sportsmanagement_match_event').'_ITEMS_DELETED',self::$db_num_rows),'');
            }  
            
	    // delete all 
$conditions = array(
    $db->quoteName('person_id') . ' IN ('.$perspropos.')',
$db->quoteName('project_id') . ' IN ('.$pid.')'
);
$query->clear(); 
$query->delete($db->quoteName('#__sportsmanagement_person_project_position'));
$query->where($conditions);
$db->setQuery($query);
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
            if ( self::$db_num_rows )
            {
            $app->enqueueMessage(Text::sprintf('COM_SPORTSMANAGEMENT_N_ITEMS_DELETED',self::$db_num_rows),'');
            } 
	    
        }  
    
    
    //if ( $result )
    //{        
    return parent::delete($delete_all);
    //}
     
   } 
   
   /**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 * @since	1.6
	 */
	public function save($data)
	{
	   $app = Factory::getApplication();
       $option = Factory::getApplication()->input->getCmd('option');
       $season_id = $app->getUserState( "$option.season_id");
       $post = Factory::getApplication()->input->post->getArray(array());
       $db = $this->getDbo();
	   $query = $db->getQuery(true);
       $date = Factory::getDate();
	   $user = Factory::getUser();
       //$query2 = $db->getQuery(true);
       if (isset($post['extended']) && is_array($post['extended'])) 
		{
			// Convert the extended field to a string.
			$parameter = new Registry;
			$parameter->loadArray($post['extended']);
			$data['extended'] = (string)$parameter;
		}
      
       // update personendaten
       // Fields to update.
    $fields = array(
    $db->quoteName('injury') .'=\''.$data['injury'].'\'',
        $db->quoteName('injury_date') .'=\''.$data['injury_date'].'\'',
        $db->quoteName('injury_end') .'=\''.$data['injury_end'].'\'',
        $db->quoteName('injury_detail') .'=\''.$data['injury_detail'].'\'',
        $db->quoteName('injury_date_start') .'=\''.$data['injury_date_start'].'\'',
        $db->quoteName('injury_date_end') .'=\''.$data['injury_date_end'].'\'',
        $db->quoteName('suspension') .'=\''.$data['suspension'].'\'',
        $db->quoteName('suspension_date') .'=\''.$data['suspension_date'].'\'',
        $db->quoteName('suspension_end') .'=\''.$data['suspension_end'].'\'',
        $db->quoteName('suspension_detail') .'=\''.$data['suspension_detail'].'\'',
        $db->quoteName('susp_date_start') .'=\''.$data['susp_date_start'].'\'',
        $db->quoteName('susp_date_end') .'=\''.$data['susp_date_end'].'\'',
        $db->quoteName('away') .'=\''.$data['away'].'\'',
	$db->quoteName('away_date') .'=\''.$data['away_date'].'\'',
        $db->quoteName('away_end') .'=\''.$data['away_end'].'\'',
        $db->quoteName('away_detail') .'=\''.$data['away_detail'].'\'',
        $db->quoteName('away_date_start') .'=\''.$data['away_date_start'].'\'',
        $db->quoteName('away_date_end') .'=\''.$data['away_date_end'].'\'',
        //$db->quoteName('extended') .'=\''.$data['extended'].'\'',
        $db->quoteName('modified') .' = '. $db->Quote( '' . $date->toSql() . '' ) .'',
        $db->quoteName('modified_by') .'='.$user->get('id')
        );
     // Conditions for which records should be updated.
    $conditions = array(
    $db->quoteName('id') .'='. $data['person_id']
    );
     $query->update($db->quoteName('#__sportsmanagement_person'))->set($fields)->where($conditions);
     $db->setQuery($query);   
 
  
 try{
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);  
}
	catch (Exception $e)
{
    $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' '.$e->getMessage()), 'error');
}
        
// update personendaten pro saison
       // Fields to update.
    unset($fields);
    unset($conditions);  
    $query->clear(); 
    $fields = array(
    $db->quoteName('picture') .'=\''.$data['picture'].'\'',
    $db->quoteName('modified') .'=\''.$date->toSql().'\'',
    $db->quoteName('modified_by') .'='.$user->get('id')
        );
     // Conditions for which records should be updated.
    $conditions = array(
    $db->quoteName('person_id') .'='. $data['person_id'],
    $db->quoteName('season_id') .'='. $season_id
    );
     $query->update($db->quoteName('#__sportsmanagement_season_person_id'))->set($fields)->where($conditions);
     $db->setQuery($query);   
 
 try{
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);  
}
	catch (Exception $e)
{
    $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' '.$e->getMessage()), 'error');
}
        
        // alten eintrag löschen
        // Create a new query object.
        $query = $db->getQuery(true);
        // delete all
        $conditions = array(
        $db->quoteName('person_id') . '='.$data['person_id'],
        $db->quoteName('project_id') . '='.$post['pid'],
        $db->quoteName('persontype') . '='.$post['persontype']
        );
 
$query->delete($db->quoteName('#__sportsmanagement_person_project_position'));
$query->where($conditions);
$db->setQuery($query); 
try{
sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);  
}
	catch (Exception $e)
{
    $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' '.$e->getMessage()), 'error');
}     
// Create and populate an object.
$profile = new stdClass();
//$profile->person_id = $post['person_id'.$pks[$x]];
$profile->person_id = $data['person_id'];
$profile->project_id = $post['pid'];
$profile->project_position_id = $data['project_position_id'];
$profile->persontype = $post['persontype'];
try{		
// Insert the object into table.
$result = Factory::getDbo()->insertObject('#__sportsmanagement_person_project_position', $profile);        
}
	catch (Exception $e)
{
//    $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' '.$e->getMessage()), 'error');
}  

        // Proceed with the save
		return parent::save($data);   
    }
    
    
   
}
