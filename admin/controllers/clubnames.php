<?php
/** SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version   1.0.05
 * @file      clubnames.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage controllers
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
 
/**
 * sportsmanagementControllerclubnames
 * 
 * @package 
 * @author Dieter Plöger
 * @copyright 2016
 * @version $Id$
 * @access public
 */
class sportsmanagementControllerclubnames extends JSMControllerAdmin
{
  
  /**
   * sportsmanagementControllerclubnames::import()
   * 
   * @return void
   */
  public function import()
    {
    // Reference global application object
        $app = Factory::getApplication();
        // JInput object
        $jinput = $app->input;    
        
      $model = $this->getModel();
       $model->import();  
      $this->setRedirect(Route::_('index.php?option='.$this->option.'&view='.$this->view_list, false));  
        
    } 
 
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'clubname', $prefix = 'sportsmanagementModel', $config = Array() ) 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}