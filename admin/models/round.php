<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      round.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage models
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;

/**
 * sportsmanagementModelround
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelround extends JSMModelAdmin
{
    var $_identifier = "rounds";
    static $db_num_rows = 0;
    var $_tables_to_delete = array();
   
    /**
	 * Method to update checked project rounds
	 *
	 * @access	public
	 * @return	boolean	True on success
	 *
	 */
	public function saveshort()
	{
		// Reference global application object
        $app = Factory::getApplication();
        $date = Factory::getDate();
	   $user = Factory::getUser();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
       
        //// Get the input
//        $pks = Factory::getApplication()->input->getVar('cid', null, 'post', 'array');
//        $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($pks,true).'</pre>'   ),'');
        $pks = $jinput->get('cid',array(),'array');
//        $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($pks,true).'</pre>'   ),'');
        if ( !$pks )
        {
            return Text::_('COM_SPORTSMANAGEMENT_ADMIN_ROUNDS_SAVE_NO_SELECT');
        }
        
        //$post = $jinput->post;
        $post = $jinput->post->getArray();
//        $app->enqueueMessage(__METHOD__.' '.__LINE__.'post <br><pre>'.print_r($post, true).'</pre><br>','Notice');
//        $post = Factory::getApplication()->input->post->getArray(array());
//        $app->enqueueMessage(__METHOD__.' '.__LINE__.'post <br><pre>'.print_r($post, true).'</pre><br>','Notice');
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $app->enqueueMessage(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($pks, true).'</pre><br>','Notice');
        $app->enqueueMessage(__METHOD__.' '.__LINE__.'<br><pre>'.print_r($post, true).'</pre><br>','Notice');
        }
        
        //$result=true;
		for ($x=0; $x < count($pks); $x++)
		{
			$tblRound = $this->getTable();
			$tblRound->id = $pks[$x];
            $tblRound->roundcode = $post['roundcode'.$pks[$x]];
            $tblRound->tournement = $post['tournementround'.$pks[$x]];
			$tblRound->name	= $post['name'.$pks[$x]];
            
            $tblRound->alias = JFilterOutput::stringURLSafe( $post['name'.$pks[$x]] );
            // Set the values
		    $tblRound->modified = $date->toSql();
		    $tblRound->modified_by = $user->get('id');
        
            $tblRound->round_date_first	= sportsmanagementHelper::convertDate($post['round_date_first'.$pks[$x]], 0);
            $tblRound->round_date_last = sportsmanagementHelper::convertDate($post['round_date_last'.$pks[$x]], 0);;
            
            if ( ( $tblRound->round_date_last == '0000-00-00' || $tblRound->round_date_last == '' )  && $tblRound->round_date_first != '0000-00-00'  )
            {
                $tblRound->round_date_last = $tblRound->round_date_first;
            }
            
            $tblRound->rdatefirst_timestamp = sportsmanagementHelper::getTimestamp($tblRound->round_date_first);
            $tblRound->rdatelast_timestamp = sportsmanagementHelper::getTimestamp($tblRound->round_date_last);

			if(!$tblRound->store()) 
            {
				sportsmanagementModeldatabasetool::writeErrorLog(get_class($this), __FUNCTION__, __FILE__, $this->_db->getErrorMsg(), __LINE__);
				return false;
			}
		}
		return Text::_('COM_SPORTSMANAGEMENT_ADMIN_ROUNDS_SAVE');
	}
    
	
    /**
     * sportsmanagementModelround::massadd()
     * 
     * @return
     */
    function massadd()
	{
	$option = Factory::getApplication()->input->getCmd('option');
	$app = Factory::getApplication();
   
    $post = Factory::getApplication()->input->post->getArray(array());
    $project_id	= $app->getUserState( "$option.pid", '0' );
    $add_round_count = (int)$post['add_round_count'];
    
        $max=0;
		if ($add_round_count > 0) // Only MassAdd a number of new and empty rounds
		{
			$max = $this->getMaxRound($project_id);
			$max++;
			$i=0;
			for ($x=0; $x < $add_round_count; $x++)
			{
				$i++;
                $tblRound =& $this->getTable();
                $tblRound->project_id = $project_id;
				$tblRound->roundcode = $max;
				$tblRound->name = Text::sprintf('COM_SPORTSMANAGEMENT_ADMIN_ROUNDS_CTRL_ROUND_NAME',$max);

				if ( $tblRound->store() )
				{
					$msg = Text::sprintf('COM_SPORTSMANAGEMENT_ADMIN_ROUNDS_CTRL_ROUNDS_ADDED',$i);
				}
				else
				{
					$msg = Text::_('COM_SPORTSMANAGEMENT_ADMIN_ROUNDS_CTRL_ERROR_ADD').$this->_db->getErrorMsg();
				}
				$max++;
			}
		}
   
    return $msg;
       
    }
    
    /**
	 * return 
	 *
	 * @param int project_id
	 * @return int
	 */
    function getMaxRound($project_id)
	{
	   // Get a db connection.
        $db = Factory::getDbo();
        $query = $db->getQuery(true);
        // select some fields
		$query->select('COUNT(roundcode)');
		// from table
		$query->from('#__sportsmanagement_round');
        // where
        $query->where('project_id = '.(int) $project_id);
        
		$result = 0;
		if ($project_id > 0)
		{
			$db->setQuery($query);
			$result = $db->loadResult();
		}
		return $result;
	}
    
   
   
   /**
    * sportsmanagementModelround::getRoundcode()
    * 
    * @param mixed $round_id
    * @return
    */
   public static function getRoundcode($round_id = 0,$cfg_which_database = 0)
   {
    // Reference global application object
        $app = Factory::getApplication();
	   // Get a db connection.
        $db = sportsmanagementHelper::getDBConnection(TRUE, $cfg_which_database );
        $query = $db->getQuery(true);
        // select some fields
		$query->select('roundcode');
		// from table
		$query->from('#__sportsmanagement_round');
        // where
        $query->where('id = '.(int) $round_id);
        try { 
		$db->setQuery($query);
		$result = $db->loadResult();
    }
catch (Exception $e)
{
    $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' '.$e->getMessage()), 'error');
	$app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' '.$e->getCode()), 'error');
    $result = false;
}
	return $result;   
   }
   
    /**
	 * 
	 * @param $roundcode
	 * @param $project_id
	 */
	public static function getRoundId($roundcode, $project_id,$cfg_which_database = 0)
	{
	   // Get a db connection.
        $db = sportsmanagementHelper::getDBConnection(TRUE, $cfg_which_database );
        $query = $db->getQuery(true);
        // select some fields
		//$query->select('id');
        $query->select('CONCAT_WS( \':\', id, alias ) AS id');
		// from table
		$query->from('#__sportsmanagement_round');
        // where
        $query->where('roundcode = '.$roundcode);
        $query->where('project_id = '.(int) $project_id);
        
		$db->setQuery($query);
		$result = $db->loadResult();
		return $result;
	}
    
    
	/**
	 * sportsmanagementModelround::getRound()
	 * 
	 * @param mixed $round_id
	 * @param integer $cfg_which_database
	 * @return
	 */
	public static function getRound($round_id,$cfg_which_database = 0)
	{
	   // Get a db connection.
        $db = sportsmanagementHelper::getDBConnection(TRUE, $cfg_which_database );
        $query = $db->getQuery(true);
        // select some fields
		$query->select('*');
		// from table
		$query->from('#__sportsmanagement_round');
        // where
        $query->where('id = '.(int) $round_id);

		$db->setQuery($query);
		return $db->loadObject();
	}
    
    
    /**
	 * Method to remove matchdays from round
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	public function deleteRoundMatches($pks=array())
	{
	$app = Factory::getApplication();
    //$app->enqueueMessage(Text::_('delete pks<br><pre>'.print_r($pks,true).'</pre>'),'');
    /* Ein Datenbankobjekt beziehen */
    $db = Factory::getDbo();
    /* Ein JDatabaseQuery Objekt beziehen */
    $query = $db->getQuery(true);

    if (count($pks))
		{
            // matches
            $query->clear();
            $query->select('m.id');
            $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match as m');
            $query->where('m.round_id IN ('.implode(",",$pks).')');
            Factory::getDBO()->setQuery($query);
            $matches = Factory::getDbo()->loadColumn();
            
            if ( $matches )
            {
            $field= 'match_id';
            $id = implode(",",$matches);
            $temp = new stdClass();
            $temp->table = '_match_statistic';
            $temp->field = $field;
            $temp->id = $id;
            $export[] = $temp;
            $temp->table = '_match_commentary';
            $temp->field = $field;
            $temp->id = $id;
            $export[] = $temp;
            $temp->table = '_match_staff_statistic';
            $temp->field = $field;
            $temp->id = $id;
            $export[] = $temp;
            $temp->table = '_match_staff';
            $temp->field = $field;
            $temp->id = $id;
            $export[] = $temp;
            $temp->table = '_match_event';
            $temp->field = $field;
            $temp->id = $id;
            $export[] = $temp;
            $temp->table = '_match_referee';
            $temp->field = $field;
            $temp->id = $id;
            $export[] = $temp;
            $temp->table = '_match_player';
            $temp->field = $field;
            $temp->id = $id;
            $export[] = $temp;
            
            $field= 'round_id';
            $id = implode(",",$pks);
            $temp = new stdClass();
            $temp->table = '_match';
            $temp->field = $field;
            $temp->id = $id;
            $export[] = $temp;
            
            }
            
            $this->_tables_to_delete = array_merge($export);
            //$app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' _tables_to_delete<br><pre>'.print_r($this->_tables_to_delete,true).'</pre>'),'');
            
            // jetzt starten wir das löschen
            foreach( $this->_tables_to_delete as $row_to_delete )
            {
            $query->clear();
            $query->delete()->from('#__'.COM_SPORTSMANAGEMENT_TABLE.$row_to_delete->table)->where($row_to_delete->field.' IN ('.$row_to_delete->id.')' );
            Factory::getDbo()->setQuery($query);
            sportsmanagementModeldatabasetool::runJoomlaQuery(__CLASS__);
            if ( self::$db_num_rows )
            {
            $app->enqueueMessage(Text::sprintf('COM_SPORTSMANAGEMENT'.strtoupper($row_to_delete->table).'_ITEMS_DELETED',self::$db_num_rows),'');
            }    
            }

            
        }    
   
   return true;     
   }
    
   /**
	 * Method to remove rounds
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	0.1
	 */
	public function delete(&$pks)
	{
	$app = Factory::getApplication();
   
    $success = $this->deleteRoundMatches($pks);  
    
    if ( $success )
    {        
    return parent::delete($pks);
    }
         
   } 
    
    
	
}
