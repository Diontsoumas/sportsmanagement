<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      view.html.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage smextxmleditor
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * sportsmanagementViewsmextxmleditor
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2013
 * @access public
 */
class sportsmanagementViewsmextxmleditor extends sportsmanagementView
{
	/**
	 * sportsmanagementViewsmextxmleditor::init()
	 * 
	 * @return void
	 */
	public function init ()
	{
		$app = Factory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
        $model = $this->getModel();
		// $data2 = $jinput->getString('file_name', "");
		// var_dump($data2);
        $this->file_name = $jinput->getString('file_name', "");
        
        // Initialise variables.
		$this->form		= $this->get('Form');
        $this->source	= $this->get('Source');

        $this->option = $option;

	}
    
    /**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{
		$jinput = Factory::getApplication()->input;
				$jinput->set('hidemainmenu', true);
				parent::addToolbar();
	ToolbarHelper::apply('smextxmleditor.apply');
	ToolbarHelper::save('smextxmleditor.save');
	ToolbarHelper::cancel('smextxmleditor.cancel', 'JTOOLBAR_CANCEL');


//        // Set toolbar items for the page
	$this->title = $this->file_name;
	$this->icon = 'xml-edit';

        
    }    
    
    
    
}
?>