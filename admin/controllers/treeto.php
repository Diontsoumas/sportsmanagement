<?php
/** SportsManagement ein Programm zur Verwaltung f�r Sportarten
 * @version   1.0.05
 * @file      treeto.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage controllers
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Session\Session;

/**
 * sportsmanagementControllerTreeto
 * 
 * @package 
 * @author Dieter Pl�ger
 * @copyright 2016
 * @version $Id$
 * @access public
 */
class sportsmanagementControllerTreeto extends JSMControllerForm
{
//	protected $view_list = 'treetos';
//	

	/**
	 * sportsmanagementControllerTreeto::__construct()
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
//
//		// Register Extra tasks
////		$this->registerTask('add','display');
////		$this->registerTask('edit','display');
////		$this->registerTask('apply','save');
//        
        // Reference global application object
        $this->jsmapp = Factory::getApplication();
        // JInput object
        $this->jsmjinput = $this->jsmapp->input;
        $this->jsmoption = $this->jsmjinput->getCmd('option');
        $this->jsmdocument = Factory::getDocument();
	}

	// save the checked rows inside the treetos list (save division assignment)
	/**
	 * sportsmanagementControllerTreeto::saveshort()
	 * 
	 * @return void
	 */
	public function saveshort()
	{
//		$option		= Factory::getApplication()->input->getCmd('option');
//		$app	= Factory::getApplication();
 		$project_id = $this->jsmjinput->get('pid');
		
		$post = $this->jsmjinput->post->getArray();
		$cid = $cid = $this->jsmjinput->get('cid',array(),'array');;
		ArrayHelper::toInteger($cid);
		
		$model = $this->getModel('treetos');
		
		if ($model->storeshort($cid, $post))
		{
			$msg = Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETO_CTRL_SAVED');
		}
		else
		{
			$msg = Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETO_CTRL_ERROR_SAVED') . $model->getError();
		}

		$link = 'index.php?option=com_sportsmanagement&view=treetos&task=treeto.display';
		$this->setRedirect($link, $msg);
	}

	/**
	 * sportsmanagementControllerTreeto::genNode()
	 * 
	 * @return void
	 */
	public function genNode()
	{
	   /*
		//$option = Factory::getApplication()->input->getCmd('option');
//		$app = Factory::getApplication();
//		$document = Factory::getDocument();
		$proj = $this->jsmapp->getUserState($this->jsmoption.'.pid',0);
		$post = $this->jsmjinput->post->getArray(array());
		$cid = $this->jsmjinput->post->get('cid');
		ArrayHelper::toInteger($cid);

		$model = $this->getModel('treeto');

		$viewType = $this->jsmdocument->getType();
		$view = $this->getView('treeto',$viewType);
		$view->setModel($model,true);	// true is for the default model;

		$projectws = $this->getModel('project');
		//$projectws->setId($app->getUserState($option.'project',0));
		$view->setModel($projectws);

		Factory::getApplication()->input->setVar('hidemainmenu',0);
		Factory::getApplication()->input->setVar('layout','gennode');
		Factory::getApplication()->input->setVar('view','treeto');
		Factory::getApplication()->input->setVar('edit',true);

		// Checkout the project
		//$model=$this->getModel('treeto');
		$model->checkout();
		parent::display();
        */
	}


	/**
	 * sportsmanagementControllerTreeto::generatenode()
	 * 
	 * @return void
	 */
	public function generatenode()
	{
		Session::checkToken() or die(Text::_('COM_SPORTSMANAGEMENT_GLOBAL_INVALID_TOKEN'));
		//$option = Factory::getApplication()->input->getCmd('option');
//		$app = Factory::getApplication();
		$post = $this->jsmjinput->post->getArray(array());
		$model = $this->getModel('treeto');
		$project_id = $this->jsmapp->getUserState($this->jsmoption.'.pid');
        //$this->jsmapp->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' project_id<br><pre>'.print_r($project_id,true).'</pre>'),'Notice');
		if ( $model->setGenerateNode() )
		{
			$msg = Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETO_CTRL_GENERATE_NODE');
			$link = 'index.php?option=com_sportsmanagement&view=treetonodes&task=treetonode.display&tid='.$this->jsmjinput->post->get('id').'&pid='.$this->jsmjinput->post->get('pid');
		}
		else
		{
			$msg = Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETO_CTRL_ERROR_GENERATE_NODE').$model->getError();
			$link = 'index.php?option=com_sportsmanagement&view=treetos&task=treeto.display';
		}
		$this->setRedirect( $link, $msg );
	}

	/**
	 * sportsmanagementControllerTreeto::remove()
	 * 
	 * @return void
	 */
	public function remove()
	{
		$cid = $this->jsmjinput->get('cid',array(),'array');
		ArrayHelper::toInteger($cid);
		if (count($cid) < 1){JError::raiseError(500,Text::_('COM_SPORTSMANAGEMENT_GLOBAL_ISELECT_TO_DELETE'));}
		$model = $this->getModel('treeto');
		if (!$model->delete($cid)){echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";}
		$this->setRedirect('index.php?option=com_sportsmanagement&view=treetos&task=treeto.display');
	}

	/**
	 * sportsmanagementControllerTreeto::cancel()
	 * 
	 * @param mixed $key
	 * @return void
	 */
	public function cancel($key = NULL)
	{
		// Checkin the project
		#$model=$this->getModel('treeto');
		#$model->checkin();
		$this->setRedirect('index.php?option=com_sportsmanagement&task=treeto.display&view=treetos');
	}

	/**
	 * Proxy for getModel
	 *
	 * @param	string	$name	The model name. Optional.
	 * @param	string	$prefix	The class prefix. Optional.
	 *
	 * @return	object	The model.
	 * @since	1.6
	 */
	public function getModel($name = 'Treeto', $prefix = 'sportsmanagementModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}
?>
