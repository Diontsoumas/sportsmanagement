<?php
/** SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version   1.0.05
 * @file      view.html.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage seasons
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * sportsmanagementViewSeasons
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewSeasons extends sportsmanagementView
{

	/**
	 * sportsmanagementViewSeasons::init()
	 * 
	 * @return void
	 */
	public function init ()
	{
		
        $season_id = $this->jinput->getVar('id');

		$this->table = Table::getInstance('season', 'sportsmanagementTable');
        $lists = array();
        
/**
 * build the html options for nation
 */
		$nation[] = HTMLHelper::_('select.option','0',Text::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_COUNTRY'));
		if ( $res = JSMCountries::getCountryOptions() )
		{
            $nation = array_merge($nation, $res);
			$this->search_nation = $res;
		}
		
        $lists['nation'] = $nation;
        $lists['nation2'] = JHtmlSelect::genericlist($nation, 
						'filter_search_nation', 
						'class="inputbox" style="width:140px; " onchange="this.form.submit();"', 
						'value', 
						'text', 
						$this->state->get('filter.search_nation'));
		
		$this->lists = $lists;
        $this->season_id = $season_id;
        switch ( $this->getLayout() )
        {
            case 'assignteams':
            case 'assignteams_3':
            case 'assignteams_4':
            $this->setLayout('assignteams'); 
            break;
            case 'assignpersons':
            case 'assignpersons_3':
            case 'assignpersons_4':
            $season_teams[] = HTMLHelper::_('select.option', '0', Text::_('COM_SPORTSMANAGEMENT_GLOBAL_SELECT_TEAM'));
        $res = $this->model->getSeasonTeams($season_id); 
        $season_teams = array_merge($season_teams,$res); 
        $lists['season_teams'] = $season_teams;
        $this->lists = $lists;
            $this->setLayout('assignpersons'); 
            break;
            
        }
		
	}
	
	/**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{ 
              
        $canDo = sportsmanagementHelper::getActions();
/**
 * Set toolbar items for the page
 */
		$this->title = Text::_('COM_SPORTSMANAGEMENT_ADMIN_SEASONS_TITLE');
		if ($canDo->get('core.create')) 
		{
			ToolbarHelper::addNew('season.add', 'JTOOLBAR_NEW');
		}
		if ($canDo->get('core.edit')) 
		{
			ToolbarHelper::editList('season.edit', 'JTOOLBAR_EDIT');
		}

        parent::addToolbar();
        
		
	}
}
?>
