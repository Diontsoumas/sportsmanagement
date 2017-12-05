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


//no direct access
defined('_JEXEC') or die('Restricted access');

if (! defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

if ( !defined('JSM_PATH') )
{
DEFINE( 'JSM_PATH','components/com_sportsmanagement' );
}

// prüft vor Benutzung ob die gewünschte Klasse definiert ist
if ( !class_exists('sportsmanagementHelper') ) 
{
//add the classes for handling
$classpath = JPATH_ADMINISTRATOR.DS.JSM_PATH.DS.'helpers'.DS.'sportsmanagement.php';
JLoader::register('sportsmanagementHelper', $classpath);
JModelLegacy::getInstance("sportsmanagementHelper", "sportsmanagementModel");
}


if (! defined('COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO'))
{
DEFINE( 'COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO',JComponentHelper::getParams('com_sportsmanagement')->get( 'show_debug_info' ) );
}
if (! defined('COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO'))
{
DEFINE( 'COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO',JComponentHelper::getParams('com_sportsmanagement')->get( 'show_query_debug_info' ) );
}

require_once(JPATH_ADMINISTRATOR.DS.JSM_PATH.DS.'libraries'.DS.'sportsmanagement'.DS.'model.php');
require_once(JPATH_SITE.DS.JSM_PATH.DS.'helpers'.DS.'route.php' );
require_once(JPATH_SITE.DS.JSM_PATH.DS.'helpers'.DS.'countries.php');
require_once(JPATH_ADMINISTRATOR.DS.JSM_PATH.DS.'models'.DS.'databasetool.php');


// Reference global application object
$app = JFactory::getApplication();
// JInput object
$jinput = $app->input;
$option = $jinput->getCmd('option');

switch ($option)
{
    case 'com_dmod':
    require_once(JPATH_ADMINISTRATOR.DS.JSM_PATH.DS.'controllers'.DS.'ajax.json.php');
    require_once(JPATH_ADMINISTRATOR.DS.JSM_PATH.DS.'models'.DS.'fields'.DS.'dependsql.php');
    break;
}

//get helper
require_once (dirname(__FILE__).DS.'helper.php');

/**
 * besonderheit für das inlinehockey update, wenn sich das 
 * modul in einem artikel befindet
 * 
 */
if ($params->get('ishd_update'))
{
$projectid = (int)$params->get('p');
require_once(JPATH_SITE.DS.JSM_PATH.DS.'extensions'.DS.'jsminlinehockey'.DS.'admin'.DS.'models'.DS.'jsminlinehockey.php');
$actionsModel = JModelLegacy::getInstance('jsminlinehockey', 'sportsmanagementModel');   

$count_games = modJSMRankingHelper::getCountGames($projectid,(int)$params->get('ishd_update_hour',4)); 
if ( $count_games )
{
$actionsModel->getmatches($projectid);
}

}

$list = modJSMRankingHelper::getData($params);

$document = JFactory::getDocument();
//add css file
$document->addStyleSheet(JURI::base().'modules'.DS.$module->module.DS.'css'.DS.$module->module.'.css');

?>
<div id="<?php echo $module->module; ?>-<?php echo $module->id; ?>">
<?PHP
require(JModuleHelper::getLayoutPath($module->module));
?>
</div>
