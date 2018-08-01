<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */
class modSMEventsrankingHelper
{
	/**
	 * Method to get the list
	 *
	 * @access public
	 * @return array
	 */
	function getData(&$params)
	{ 
	  
       $app = JFactory::getApplication();

		if (!class_exists('sportsmanagementModelEventsRanking')) 
        {
            require_once(JPATH_SITE.DS.JSM_PATH.DS.'models'.DS.'project.php' );
            require_once(JPATH_SITE.DS.JSM_PATH.DS.'models'.DS.'eventsranking.php' );
		}
		
		$usedp = $params->get('p');
		$projectstring = (is_array($usedp)) ? implode(",", $usedp)  : (int) $usedp;
		
		$usedteam = $params->get('tid');
		$teamstring = (is_array($usedteam )) ? implode(",", $usedteam )  : (int) $usedteam ;
		
		sportsmanagementModelProject::$cfg_which_database = $params->get( 'cfg_which_database' );
		sportsmanagementModelProject::setProjectId($projectstring,$params->get( 'cfg_which_database' ));
		
		$project = sportsmanagementModelProject::getProject($params->get( 'cfg_which_database' ),__METHOD__);
		
		sportsmanagementModelEventsRanking::$cfg_which_database = $params->get( 'cfg_which_database' );
		
		sportsmanagementModelEventsRanking::$projectid = $projectstring;
		sportsmanagementModelEventsRanking::$divisionid = 0;
		sportsmanagementModelEventsRanking::$matchid = 0;
		sportsmanagementModelEventsRanking::$teamid = $teamstring;
		sportsmanagementModelEventsRanking::$eventid = $params->get('evid');
		sportsmanagementModelEventsRanking::$limit = $params->get('limit');
		sportsmanagementModelEventsRanking::$limitstart = 0;		
				
		$eventtypes = sportsmanagementModelEventsRanking::getEventTypes();
		$events = sportsmanagementModelEventsRanking::_getEventsRanking( $params->get('evid'), "desc", 20, 0 );
		$teams = sportsmanagementModelProject::getTeamsIndexedById();

		return array('project' => $project, 'ranking' => $events, 'eventtypes' => $eventtypes, 'teams' => $teams, 'model' => $model); 
	}

	/**
	 * get id from the module configuration parameters
	 * (the parameter can either be the id by itself or a complete slug).
	 * @param object configuration parameters for the module
	 * @param string name of the configuration parameter
	 * @return id string for the requested parameter (e.g. project id or statistics id)
	 */
	function getId($params, $paramName)
	{
		$id = $params->get($paramName);
		preg_match('/(?P<id>\d+):.*/', $id, $matches);
		if (array_key_exists('id', $matches))
		{
			$id = $matches['id'];
		}
		return $id;
	}

	
	/**
	 * get img for team
	 * @param object ranking row
	 * @param int type = 1 for club small logo, 2 for country
	 * @return html string
	 */
	function getLogo($item, $type = 1)
	{
		if ($type == 1) // club small logo
		{
			if (!empty($item->logo_small))
			{
				return JHTML::image(JURI::root().$item->logo_small, $item->short_name, 'class="teamlogo"');
			}
		}		
		else if ($type == 2 && !empty($item->country))
		{
			return JSMCountries::getCountryFlag($item->country, 'class="teamcountry"');
		} 
		
		return '';
	}

	/**
	 * modSMEventsrankingHelper::getTeamLink()
	 * 
	 * @param mixed $team
	 * @param mixed $params
	 * @param mixed $project
	 * @return
	 */
	function getTeamLink($team, $params, $project)
	{

$routeparameter = array();
$routeparameter['cfg_which_database'] = JFactory::getApplication()->input->getInt('cfg_which_database', 0);
$routeparameter['s'] = JFactory::getApplication()->input->getInt('s', 0);
$routeparameter['p'] = $project->slug;
               
		switch ($params->get('teamlink'))
		{
            case 'teaminfo':
            $routeparameter['tid'] = $team->team_slug;
                $routeparameter['ptid'] = 0;
				return sportsmanagementHelperRoute::getSportsmanagementRoute('teaminfo', $routeparameter);;
			case 'roster':
            $routeparameter['tid'] = $team->team_slug;
                $routeparameter['ptid'] = 0;
                $routeparameter['division'] = 0;
				return sportsmanagementHelperRoute::getSportsmanagementRoute('roster', $routeparameter);
			case 'teamplan':
            $routeparameter['tid'] = $team->team_slug;
                $routeparameter['division'] = 0;
                $routeparameter['mode'] = 0;
                $routeparameter['ptid'] = 0;
				return sportsmanagementHelperRoute::getSportsmanagementRoute('teamplan', $routeparameter);;
			case 'clubinfo':
            
				return sportsmanagementHelperRoute::getClubInfoRoute($project->slug, $team->club_slug);				
		}
	}
	
	/**
	 * modSMEventsrankingHelper::printName()
	 * 
	 * @param mixed $item
	 * @param mixed $team
	 * @param mixed $params
	 * @param mixed $project
	 * @return void
	 */
	function printName($item, $team, $params, $project)
	{
				$name = sportsmanagementHelper::formatName(null, $item->fname, 
													$item->nname, 
													$item->lname, 
													$params->get("name_format"));
													
				if ($params->get('show_player_link')) 
				{		
					
					$routeparameter = array();
					$routeparameter['cfg_which_database'] = $params->get('cfg_which_database');
					$routeparameter['s'] = $params->get('s');
					$routeparameter['p'] = $project->project_slug;
					$routeparameter['tid'] = $item->team_slug;
					$routeparameter['pid'] = $item->person_slug;					
									
					$link = sportsmanagementHelperRoute::getSportsmanagementRoute('player',$routeparameter);

					echo JHTML::link($link, $name);
					
				}
				else
				{
					echo $name;
				}				

	}
	
	
		/**
		 * modSMEventsrankingHelper::getEventIcon()
		 * 
		 * @param mixed $event
		 * @return
		 */
		function getEventIcon($event)
	{
		if ($event->icon == 'media/com_sportsmanagement/event_icons/event.gif')
		{
			$txt = $event->name;
		}
		else
		{
			$imgTitle=JText::_($event->name);
			$imgTitle2=array(' title' => $imgTitle, ' alt' => $imgTitle);
			$txt=JHTML::image($event->icon, $imgTitle, $imgTitle2);
		}
		return $txt;
	}
	
	
}