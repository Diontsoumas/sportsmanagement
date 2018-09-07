<?php
/** SportsManagement ein Programm zur Verwaltung f�r Sportarten
 * @version   1.0.05
 * @file      editclub.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage editclub
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;

//jimport('joomla.application.component.model');
//require_once( JLG_PATH_SITE . DS . 'models' . DS . 'project.php' );

//require_once(JPATH_COMPONENT.DS.'models'.DS.'item.php');
//require_once(JPATH_COMPONENT.DS.'helpers'.DS.'jltoolbar.php');

// Include dependancy of the main model form
jimport('joomla.application.component.modelform');


/**
 * sportsmanagementModelEditClub
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class sportsmanagementModelEditClub extends JModelForm
{
	
  /* interfaces */
	var $latitude	= null;
	var $longitude	= null;
	var $projectid = 0;
	var $clubid = 0;
	var $club = null;
  
  /**
   * sportsmanagementModelEditClub::__construct()
   * 
   * @return void
   */
  function __construct()
	{
	   $app = Factory::getApplication();
		parent::__construct();

		$this->projectid = Factory::getApplication()->input->getInt( 'p', 0 );
		$this->clubid = Factory::getApplication()->input->getInt( 'cid', 0 );
        $this->name = 'club';
        
	}
    

  /**
   * sportsmanagementModelEditClub::getData()
   * 
   * @return
   */
  function getData()
	{
	   //$this->_id = Factory::getApplication()->input->getInt('cid',0);
		if ( is_null( $this->club  ) )
		{
			$this->club = $this->getTable( 'Club', 'sportsmanagementTable' );
			$this->club->load( $this->clubid );
		}
		return $this->club;
	}  


/**
         * Get the data for a new qualification
         */
        public function getForm($data = array(), $loadData = true)
        {
            $app = Factory::getApplication();
        $option = Factory::getApplication()->input->getCmd('option');
        $cfg_which_media_tool = ComponentHelper::getParams($option)->get('cfg_which_media_tool',0);
        $show_team_community = ComponentHelper::getParams($option)->get('show_team_community',0);
 
        $app = Factory::getApplication('site');
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' name<br><pre>'.print_r($this->name,true).'</pre>'),'Notice');
 
        // Get the form.
        JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/forms');
        JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/fields');
		$form = $this->loadForm('com_sportsmanagement.'.$this->name, $this->name,
				array('load_data' => $loadData) );
		if (empty($form))
		{
			return false;
		}
        
        if ( !$show_team_community )
        {
            $form->setFieldAttribute('merge_teams', 'type', 'hidden');
        }
        
        $form->setFieldAttribute('logo_small', 'default', ComponentHelper::getParams($option)->get('ph_logo_small',''));
        $form->setFieldAttribute('logo_small', 'directory', 'com_sportsmanagement/database/clubs/small');
        $form->setFieldAttribute('logo_small', 'type', $cfg_which_media_tool);
        
        $form->setFieldAttribute('logo_middle', 'default', ComponentHelper::getParams($option)->get('ph_logo_medium',''));
        $form->setFieldAttribute('logo_middle', 'directory', 'com_sportsmanagement/database/clubs/medium');
        $form->setFieldAttribute('logo_middle', 'type', $cfg_which_media_tool);
        
        $form->setFieldAttribute('logo_big', 'default', ComponentHelper::getParams($option)->get('ph_logo_big',''));
        $form->setFieldAttribute('logo_big', 'directory', 'com_sportsmanagement/database/clubs/large');
        $form->setFieldAttribute('logo_big', 'type', $cfg_which_media_tool);
        
        $form->setFieldAttribute('trikot_home', 'default', ComponentHelper::getParams($option)->get('ph_logo_small',''));
        $form->setFieldAttribute('trikot_home', 'directory', 'com_sportsmanagement/database/clubs/trikot_home');
        $form->setFieldAttribute('trikot_home', 'type', $cfg_which_media_tool);
        
        $form->setFieldAttribute('trikot_away', 'default', ComponentHelper::getParams($option)->get('ph_logo_small',''));
        $form->setFieldAttribute('trikot_away', 'directory', 'com_sportsmanagement/database/clubs/trikot_away');
        $form->setFieldAttribute('trikot_away', 'type', $cfg_which_media_tool);
        
		return $form;
 
        } 

/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.7
	 */
	protected function loadFormData()
	{
	   $app = Factory::getApplication();
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_sportsmanagement.edit.'.$this->name.'.data', array());
		if (empty($data))
		{
			$data = $this->getData();
		}
		return $data;
	}		

	
}
?>