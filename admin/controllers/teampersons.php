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

use Joomla\Utilities\ArrayHelper;
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
 

/**
 * sportsmanagementControllerteampersons
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementControllerteampersons extends JControllerAdmin
{
	
  /**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
        $this->app = JFactory::getApplication();
		$this->jinput = $this->app->input;
		$this->option = $this->jinput->getCmd('option');
$this->registerTask('unpublish', 'set_season_team_state');
$this->registerTask('publish', 'set_season_team_state');
$this->registerTask('trash', 'set_season_team_state');
$this->registerTask('archive', 'set_season_team_state');
	}



/**
 * sportsmanagementControllerteampersons::set_season_team_state()
 * 
 * @return void
 */
function set_season_team_state()
{
$post = JFactory::getApplication()->input->get( 'post' );
$ids = $this->input->get('cid', array(), 'array');
$tpids = $this->input->get('tpid', array(), 'array');
$values = array('publish' => 1, 'unpublish' => 0, 'archive' => 2, 'trash' => -2);
$task = $this->getTask();
$value = ArrayHelper::getValue($values, $task, 0, 'int');    

//$this->app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' getTask <br><pre>'.print_r($this->getTask(),true).'</pre>'),'Notice');   
//$this->app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' ids    <br><pre>'.print_r($ids,true).'</pre>'),'Notice');            
//$this->app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' tpids    <br><pre>'.print_r($tpids,true).'</pre>'),'Notice');   
$model = $this->getModel();
$model->set_state($ids,$tpids,$value);  

switch ($value)
{
case 0:
$ntext = 'COM_SPORTSMANAGEMENT_N_ITEMS_UNPUBLISHED';
break;
case 1:
$ntext = 'COM_SPORTSMANAGEMENT_N_ITEMS_PUBLISHED';		
break;
case 2:
$ntext = 'COM_SPORTSMANAGEMENT_N_ITEMS_ARCHIVED';		
break;
case -2:
$ntext = 'COM_SPORTSMANAGEMENT_N_ITEMS_TRASHED';		
break;		
}		

$this->setMessage(JText::plural($ntext, count($ids)));	
	
$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.'&persontype='.$post['persontype'].'&project_team_id='.$post['project_team_id'].'&team_id='.$post['team_id'].'&pid='.$post['pid']  , false));    
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
	   $post = JFactory::getApplication()->input->get( 'post' );
	   $model = $this->getModel();
       $model->saveshort();
       $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.'&persontype='.$post['persontype'].'&project_team_id='.$post['project_team_id'].'&team_id='.$post['team_id'].'&pid='.$post['pid']  , false));
    } 
  
  /**
   * sportsmanagementControllerteampersons::remove()
   * 
   * @return void
   */
  function remove()
	{
	$app = JFactory::getApplication();
    $pks = JFactory::getApplication()->input->getVar('cid', array(), 'post', 'array');
    $model = $this->getModel('teampersons');
    $model->remove($pks);
	
    $this->setRedirect('index.php?option=com_sportsmanagement&view=teampersons');    
        
   }
   
  /**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'teamperson', $prefix = 'sportsmanagementModel', $config = Array() ) 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	


	
}
