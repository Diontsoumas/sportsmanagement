<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      githubinstall.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage models
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\Archive\Archive;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Log\Log;

use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerHelper;

if( version_compare(JSM_JVERSION,'3','eq') ) 
{
jimport('joomla.filesystem.archive');	
}	

/**
 * sportsmanagementModelgithubinstall
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementModelgithubinstall extends JSMModelLegacy
{

    var $storeFailedColor = 'red';
	var $storeSuccessColor = 'green';
	var $existingInDbColor = 'orange';
    var $_success_text = array();
    
/**
 * sportsmanagementModelgithubinstall::CopyGithubLink()
 * 
 * @param mixed $link
 * @return
 */
function CopyGithubLink($link)
{
    
$gitinstall = '';

if ( $gitinstall )
{
    
}   
else
{     
/** 
 * set the target directory 
 */
$base_Dir = JPATH_SITE . DS . 'tmp'. DS;
$file['name'] = basename($link);
$filename = $file['name'];
$filepath = $base_Dir . $filename;

if ( !isset($this->_success_text['Komponente:']) )
{
$this->_success_text['Komponente:'] = 'text';	
}
	
$my_text = '';

if( version_compare(JSM_JVERSION,'3','eq') ) 
{
/** Get the handler to download the package */
try
{
$http = JHttpFactory::getHttp(null, array('curl', 'stream'));
}
catch (RuntimeException $e)
{
Log::add($e->getMessage(), Log::WARNING, 'jsmerror');    
return false;
}

/** Download the package */
try
{
$result = $http->get($link);
$my_text = '<span style="color:'.$this->storeSuccessColor.'">';
$my_text .= Text::sprintf('Die ZIP-Datei der Komponente [ %1$s ] konnte kopiert werden!',"</span><strong>".$link."</strong>");
$my_text .= '<br />';	
}
catch (RuntimeException $e)
{
$my_text = '<span style="color:'.$this->storeFailedColor.'">';
$my_text .= Text::sprintf('Die ZIP-Datei der Komponente [ %1$s ] konnte nicht kopiert werden!',"</span><strong>".$link."</strong>");
$my_text .= '<br />';	
return false;
}

if (!$result || ($result->code != 200 && $result->code != 310))
{
$this->jsmapp->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' code<br><pre>'.print_r($result->code,true).'</pre>'),'Notice');	
return false;
}

try
{	
/** Write the file to disk */
File::write($filepath, $result->body);
}
catch (RuntimeException $e)
{
$this->jsmapp->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' '.$e->getMessage()), 'error');
$this->jsmapp->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' '.$e->getCode()), 'error');	
return false;
}

}
elseif( version_compare(JSM_JVERSION,'4','eq') ) 
{	
/** Download the package at the URL given. */
$p_file = InstallerHelper::downloadPackage($link);
/** Was the package downloaded? */
if (!$p_file)
{
$my_text = '<span style="color:'.$this->storeFailedColor.'">';
$my_text .= Text::sprintf('Die ZIP-Datei der Komponente [ %1$s ] konnte nicht kopiert werden!',"</span><strong>".$p_file."</strong>");
$my_text .= '<br />';    
Factory::getApplication()->enqueueMessage(Text::_('COM_INSTALLER_MSG_INSTALL_INVALID_URL'), 'error');
return false;
}
else
{
$my_text = '<span style="color:'.$this->storeSuccessColor.'">';
$my_text .= Text::sprintf('Die ZIP-Datei der Komponente [ %1$s ] konnte kopiert werden!',"</span><strong>".$p_file."</strong>");
$my_text .= '<br />';	    
}

}
	
$this->_success_text['Komponente:'] = $my_text;


$extractdir = JPATH_SITE.DS.'tmp';
$dest = JPATH_SITE.DS.'tmp'.DS.$file['name'];

if( version_compare(JSM_JVERSION,'3','eq') ) 
{
try {
	$result = JArchive::extract($dest,$extractdir);
	} catch (Exception $e) {
                $this->jsmapp->enqueueMessage(__METHOD__ . ' ' . __LINE__ . Text::_($e->getMessage()), 'Error');
                $result = false;
            }
}
elseif( version_compare(JSM_JVERSION,'4','eq') ) 
{	

$archive = new Archive;
$result = $archive->extract($dest, $extractdir);
}	
	
/** Get an installer instance */

$installer = Installer::getInstance();

/** Get the path to the package to install */

$p_dir = JPATH_SITE.DS.'tmp'.DS.'sportsmanagement-master'.DS;
$p_dir_modules = JPATH_SITE.DS.'tmp'.DS.'sportsmanagement-master'.DS.'modules'.DS;

/** Detect the package type */
$type = InstallerHelper::detectType($p_dir);   


$package['packagefile'] = null;
$package['extractdir'] = null;
$package['dir'] = $p_dir;
$package['type'] = $type;


/** Install the package */

if (!$installer->install($package['dir']))
    {
			
        $my_text .= '<span style="color:'.$this->storeFailedColor.'">';
		$my_text .= Text::sprintf('Die Komponente [ %1$s ] konnte nicht installiert werden!', "</span><strong>".strtoupper($package['type'])."</strong>");
		$my_text .= '<br />';
			
	}
else
    {
		$my_text .= '<span style="color:'.$this->storeSuccessColor.'">';
		$my_text .= Text::sprintf('Die Komponente [ %1$s ] wurde installiert!', "</span><strong>".strtoupper($package['type'])."</strong>");
		$my_text .= '<br />';
            
	}

$this->_success_text['Komponente:'] = $my_text;

$install_modules = Folder::folders($p_dir_modules , $filter = '.');

$my_text = '';

foreach( $install_modules as $key => $value)
{
    $my_text .= '<span style="color:'.$this->storeSuccessColor.'">';
    $my_text .= Text::sprintf('Das Modul [ %1$s ] wurde installiert!', "</span><strong>".strtoupper($value)."</strong>");
    $my_text .= '<br />';
    
}
$this->_success_text['Module:'] = $my_text;

}

return $this->_success_text;	
}


    
}


?>    
