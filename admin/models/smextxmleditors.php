<?php
/** SportsManagement ein Programm zur Verwaltung f�r Sportarten
 * @version   1.0.05
 * @file      smextxmleditors.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage models
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Filesystem\Folder;
jimport('joomla.filesystem.file');



/**
 * sportsmanagementModelsmextxmleditors
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class sportsmanagementModelsmextxmleditors extends BaseDatabaseModel
{

/**
 * sportsmanagementModelsmextxmleditors::getXMLFiles()
 * 
 * @return
 */
function getXMLFiles()
    {
        // Reference global application object
        $app = Factory::getApplication();
        // JInput object
        $jinput = $app->input;
        $option = $jinput->getCmd('option');
        $path = JPATH_ADMINISTRATOR.DS.'components'.DS.$option.DS.'assets'.DS.'extended';
        // Get a list of files in the search path with the given filter.
       $files = Folder::files($path, '.xml$|.php$');
       
        return $files;
        
    }	

}
?>