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

defined('_JEXEC') or die();

// welche joomla version ?
if(version_compare(JVERSION,'3.0.0','ge')) 
{
//JHtml::_('jquery.framework');
}
elseif(version_compare(JVERSION,'2.5.0','ge')) 
{
// Joomla! 2.5 code here
//JHtml::_('behavior.modal');
//JHtml::_('behavior.framework');
} 
elseif(version_compare(JVERSION,'1.7.0','ge')) 
{
// Joomla! 1.7 code here
} 
elseif(version_compare(JVERSION,'1.6.0','ge')) 
{
// Joomla! 1.6 code here
} 
else 
{
// Joomla! 1.5 code here
}

/**
 * führt zu fehlern
 */
//JHtml::_('bootstrap.framework', false);

/**
 * sportsmanagementView
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class sportsmanagementView extends JViewLegacy
{

	protected $icon = '';
	protected $title = '';
    protected $layout = '';
    protected $tmpl = '';
    protected $table_data_class = '';
    protected $table_data_div = '';

	/**
	 * sportsmanagementView::display()
	 * 
	 * @param mixed $tpl
	 * @return
	 */
	public function display ($tpl = null)
	{
	   // Reference global application object
        $this->app = JFactory::getApplication();
        // JInput object
        $this->jinput = $this->app->input;
        $this->uri = JFactory :: getURI();
        $this->action = $this->uri->toString();
        $this->params = $this->app->getParams();
        // Get a refrence of the page instance in joomla
		$this->document = JFactory::getDocument();
        $this->option = $this->jinput->getCmd('option');
        $this->user = JFactory::getUser();
        $this->view = $this->jinput->getVar("view");
        
        $this->model = $this->getModel();
//        $js ="registerhome('".JURI::base()."','JSM Sports Management','".$this->app->getCfg('sitename')."','0');". "\n";
//        $this->document->addScriptDeclaration( $js );

$headData = $this->document->getHeadData();
$scripts = $headData['scripts'];
//$this->app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' picture server <br><pre>'.print_r($scripts,true).'</pre>'),'');

/**
 * führt zu fehlern
 */
//unset($scripts[JUri::root(true) . '/media/jui/js/jquery.min.js']);
//unset($scripts[JUri::root(true) . '/media/jui/js/jquery-noconflict.js']);
//unset($scripts[JUri::root(true) . '/media/jui/js/jquery-migrate.min.js']);
//unset($scripts[JUri::root(true) . '/media/jui/js/bootstrap.min.js']);

$headData['scripts'] = $scripts;
$this->document->setHeadData($headData);
		
        switch ($this->view)
        {
            case 'resultsranking':
            $this->project = sportsmanagementModelProject::getProject(sportsmanagementModelProject::$cfg_which_database);
            $this->overallconfig = sportsmanagementModelProject::getOverallConfig(sportsmanagementModelProject::$cfg_which_database);
            
            break;	
            default:
            $this->project = sportsmanagementModelProject::getProject(sportsmanagementModelProject::$cfg_which_database);
	        $this->overallconfig = sportsmanagementModelProject::getOverallConfig(sportsmanagementModelProject::$cfg_which_database);
	        $this->config = sportsmanagementModelProject::getTemplateConfig($this->getName(),sportsmanagementModelProject::$cfg_which_database);
            $this->config = array_merge($this->overallconfig,$this->config);
            break;
        }

		$this->init();
        
        $this->addToolbar();
        
		parent::display($tpl);
	}

	/**
	 * sportsmanagementView::addToolbar()
	 * 
	 * @return void
	 */
	protected function addToolbar ()
	{
	   
        
        
	}

	/**
	 * sportsmanagementView::init()
	 * 
	 * @return void
	 */
	protected function init ()
	{
	}
}
