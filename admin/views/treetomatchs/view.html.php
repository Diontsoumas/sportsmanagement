<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Toolbar\ToolbarHelper;

class sportsmanagementViewTreetomatchs extends sportsmanagementView
{

	public function init ()
	{
	   

       
		if ( $this->getLayout() == 'editlist' || $this->getLayout() == 'editlist_3' )
		{
			$this->_displayEditlist();
			return;
		}

		if ( $this->getLayout()=='default' || $this->getLayout()=='default_3' )
		{
			$this->_displayDefault();
			return;
		}
		//parent::display($tpl);
	}

	function _displayEditlist()
	{
//		$option = Factory::getApplication()->input->getCmd('option');
//		$app = Factory::getApplication();
		$project_id = $this->jinput->get('pid');
		$node_id = $this->jinput->get('nid');
		
		//$uri = Factory::getURI();

		//$treetomatchs = $this->get('Data');
        $treetomatchs = $this->items;
		//$total = $this->get('Total');
		//$model = $this->getModel();

		//$projectws = $this->get('Data','project');
        $mdlProject = BaseDatabaseModel::getInstance('Project', 'sportsmanagementModel');
		$projectws = $mdlProject->getProject($project_id);
        
        $mdlTreetoNode = BaseDatabaseModel::getInstance('treetonode', 'sportsmanagementModel');
        $nodews = $mdlTreetoNode->getNode($node_id);
        

        
		//$nodews = $this->get('Data','node');
		//build the html select list for node assigned matches
		$ress = array();
		$res1 = array();
		$notusedmatches = array();

		if ($ress = $this->model->getNodeMatches($node_id))
		{
			$matcheslist=array();
			foreach($ress as $res)
			{
				if(empty($res1->info))
				{
					$node_matcheslist[] = JHtmlSelect::option($res->value,$res->text);
				}
				else
				{
					$node_matcheslist[] = JHtmlSelect::option($res->value,$res->text.' ('.$res->info.')');
				}
			}

			$lists['node_matches'] = JHtmlSelect::genericlist($node_matcheslist, 'node_matcheslist[]',
	' style="width:250px; height:300px;" class="inputbox" multiple="true" size="'.min(30,count($ress)).'"',
				'value',
				'text');
		}
		else
		{
			$lists['node_matches']= '<select name="node_matcheslist[]" id="node_matcheslist" style="width:250px; height:300px;" class="inputbox" multiple="true" size="10"></select>';
		}

		if ($ress1 = $this->model->getMatches())
		{
			if ($ress = $this->model->getNodeMatches($node_id))
			{
				foreach ($ress1 as $res1)
				{
					$used=0;
					foreach ($ress as $res)
					{
						if ($res1->value == $res->value){$used=1;}
					}

					if ($used == 0 && !empty($res1->info)){
						$notusedmatches[]=JHtmlSelect::option($res1->value,$res1->text.' ('.$res1->info.')');
					}
					elseif($used == 0 && empty($res1->info))
					{
						$notusedmatches[] = JHtmlSelect::option($res1->value,$res1->text);
					}
				}
			}
			else
			{
				foreach ($ress1 as $res1)
				{
					if(empty($res1->info))
					{
						$notusedmatches[] = JHtmlSelect::option($res1->value,$res1->text);
					}
					else
					{
						$notusedmatches[] = JHtmlSelect::option($res1->value,$res1->text.' ('.$res1->info.')');
					}
				}
			}
		}
		else
		{
			JError::raiseWarning('ERROR_CODE','<br />'.Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETOMATCH_ADD_MATCH').'<br /><br />');
		}

		//build the html select list for matches
		if (count($notusedmatches) > 0)
		{
			$lists['matches'] = JHtmlSelect::genericlist( $notusedmatches,
				'matcheslist[]',
	' style="width:250px; height:300px;" class="inputbox" multiple="true" size="'.min(30,count($notusedmatches)).'"',
			'value',
			'text');
		}
		else
		{
			$lists['matches'] = '<select name="matcheslist[]" id="matcheslist" style="width:250px; height:300px;" class="inputbox" multiple="true" size="10"></select>';
		}

		unset($res);
		unset($res1);
		unset($notusedmatches);

		//$this->assignRef('user',Factory::getUser());
		$this->lists = $lists;
		$this->treetomatchs = $treetomatchs;
		$this->projectws = $projectws;
		$this->nodews = $nodews;
        
        $this->addToolBarEditlist();
        $this->setLayout('editlist');
		//$this->pagination = $pagination;
		//$this->assignRef('request_url',$uri->toString());

		//parent::display($tpl);
	}

	function _displayDefault()
	{
//		$option = Factory::getApplication()->input->getCmd('option');
//		$app = Factory::getApplication();
//		$uri = Factory::getURI();

		//$match = $this->get('Data');
		//$total = $this->get('Total');
		//$pagination = $this->get('Pagination');

		//$model = $this->getModel();
		//$projectws = $this->get('Data','project');
        $this->project_id = $this->jinput->get('pid');
		$mdlProject = BaseDatabaseModel::getInstance('Project', 'sportsmanagementModel');
		$projectws = $mdlProject->getProject($this->project_id);
        
       

        $mdlTreetoNode = BaseDatabaseModel::getInstance('treetonode', 'sportsmanagementModel');
        $nodews = $mdlTreetoNode->getNode($this->jinput->get('nid'));
        
		$this->match = $this->items;
		$this->projectws = $projectws;
		$this->nodews = $nodews;
		$this->total = $this->total;
		$this->pagination = $this->pagination;
        
        $this->addToolBarDefault();
        $this->setLayout('default');
		//$this->assignRef('request_url',$uri->toString());

		//parent::display($tpl);
	}

protected function addToolBarEditlist()
	{
	   $this->title = Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETOMATCH_ASSIGN');
	ToolbarHelper::title( Text::_( 'COM_SPORTSMANAGEMENT_ADMIN_TREETOMATCH_ASSIGN' ) );

ToolbarHelper::save( 'treetomatch.save_matcheslist' );

// for existing items the button is renamed `close` and the apply button is showed
ToolbarHelper::back('Back','index.php?option=com_sportsmanagement&view=treetonodes&layout=default&tid='.$this->jinput->get('tid').'&pid='.$this->jinput->get('pid') );   
     
       
       }
       
protected function addToolBarDefault()
	{
	   $this->title = Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETOMATCH_TITLE');
	ToolbarHelper::title(Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETOMATCH_TITLE'));

//JLToolBarHelper::save();
ToolbarHelper::custom('treetomatch.editlist','upload.png','upload_f2.png',Text::_('COM_SPORTSMANAGEMENT_ADMIN_TREETOMATCH_BUTTON_ASSIGN'),false);
ToolbarHelper::back('Back','index.php?option=com_sportsmanagement&view=treetonodes&layout=default&tid='.$this->jinput->get('tid').'&pid='.$this->jinput->get('pid'));   
       
       
       }


}
?>