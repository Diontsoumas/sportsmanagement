<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version   1.0.05
 * @file      controller.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage admin
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

if (! defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

if (! defined('JSM_PATH'))
{
DEFINE( 'JSM_PATH','components/com_sportsmanagement' );
}

if ( !class_exists('sportsmanagementHelper') ) 
{
    JLoader::import('components.com_sportsmanagement.helpers.sportsmanagement', JPATH_ADMINISTRATOR);
} 

/**
 * SportsManagementController
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class SportsManagementController extends BaseController
{
	
	/**
	 * SportsManagementController::display()
	 * 
	 * @param bool $cachable
	 * @param bool $urlparams
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) 
	{
		$jinput = Factory::getApplication()->input;
        
/**
 * set default view if not set
 */
        $view = $jinput->set('view', $jinput->getCmd('view', 'cpanel'));
        $layout = $jinput->getCmd('layout', 'default');
/**
 * call parent behavior
 */
		parent::display($cachable);
        if ($layout != 'edit' )
		{
/**
 * Set the submenu
 */
		sportsmanagementHelper::addSubmenu('messages');
        }
        
	}
}
