<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
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
* SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/

defined('_JEXEC') or die('Restricted access');

if ($this->config['show_teams_seasons'] == "1")
{
	?>
<table class="fixtures">
	<tr class="sectiontableheader">
		<td><?php echo JText::_('COM_SPORTSMANAGEMENT_TEAMINFO_SEASON_TITLE');?></td>
	</tr>
</table>

	<?php
	foreach ($this->seasons as $season)
	{
		?>
<table class="fixtures">
<?php
if ($season->projectname)
{
	?>
	<tr>
		<td><?php
		/*
		 //Maybe this thing with ul and li should be solved by css so everybody may decide for himself about using it or not
		 <ul>
		 <li>
		 */
		?> <a href="javascript:void(0)"
			onclick="switchMenu('tid<?php echo $this->team->id . $season->projectid; ?>');"
			title="<?php echo JText::_('COM_SPORTSMANAGEMENT_SHOW_OPTIONS'); ?>"><?php echo $season->projectname; ?>
		</a> <?php
		/*
		 //Maybe this thing with ul and li should be solved by css so everybody may decide for himself about using it or not
		 </li>
		 </ul>
		 */
		?></td>
	</tr>
	<?php
}

/*
 //We should think about this part with the info.
 //I think it is of no use and might blow up the page size if we have a lot of seasons and long team descripions
 <?php
 if ( $season->info )
 {
 ?>
 <tr>
 <td>
 <b><?php echo JText::_('COM_SPORTSMANAGEMENT_TEAMINFO_TEAMSEASON_DESC');?></b>
 </td>
 <td><?php echo $season->info; ?></td>
 </tr>
 <?php
 }
 */
?>
</table>

<div id="tid<?php echo $this->team->id . $season->projectid;?>"
	align="center" style="display: none"><?php
	if ($this->config['show_teams_logos'])
	{
		$picture = $season->picture;

		if ((@is_null($picture)) or
		(strpos($picture, "/com_sportsmanagement/images/placeholders/placeholder_450.png")) or
		(strpos($picture, "/joomleague/placeholders/placeholder_450.png")))
		{
			$picture = JoomleagueHelper::getDefaultPlaceholder("team");
		}

		$picture_descr = JText::_("COM_SPORTSMANAGEMENT_TEAMINFO_PLAYERS_PICTURE") . " " . $this->team->name . " (" . $season->projectname . ")";
		echo JHtml::image($picture, $picture_descr, array("title" => $picture_descr));
	}
	?> <br />
	<?php
	$routeparameter = array();
       $routeparameter['cfg_which_database'] = JFactory::getApplication()->input->getInt('cfg_which_database',0);
       $routeparameter['s'] = JFactory::getApplication()->input->getInt('s',0);
       $routeparameter['p'] = $season->project_slug;
       $routeparameter['tid'] = $season->team_slug;
       $routeparameter['ptid'] = 0;
       		$link = sportsmanagementHelperRoute::getSportsmanagementRoute('roster',$routeparameter);
	
	echo JHtml::link($link, JText::_('COM_SPORTSMANAGEMENT_TEAMINFO_SEASON_PLAYERS'));
	?> <br />
	<?php
	$routeparameter = array();
       $routeparameter['cfg_which_database'] = JFactory::getApplication()->input->getInt('cfg_which_database',0);
       $routeparameter['s'] = JFactory::getApplication()->input->getInt('s',0);
       $routeparameter['p'] = $season->project_slug;
       $routeparameter['r'] = 0;
       $routeparameter['division'] = 0;
       $routeparameter['mode'] = 0;
       $routeparameter['order'] = 0;
       $routeparameter['layout'] = 0;
       		$link = sportsmanagementHelperRoute::getSportsmanagementRoute('results',$routeparameter);

	echo JHtml::link($link, JText::_('COM_SPORTSMANAGEMENT_TEAMINFO_SEASON_RESULTS'));
	?> <br />
	<?php
	$routeparameter = array();
       $routeparameter['cfg_which_database'] = JFactory::getApplication()->input->getInt('cfg_which_database',0);
       $routeparameter['s'] = JFactory::getApplication()->input->getInt('s',0);
       $routeparameter['p'] = $season->project_slug;
       $routeparameter['type'] = 0;
       $routeparameter['r'] = 0;
       $routeparameter['from'] = 0;
       $routeparameter['to'] = 0;
       $routeparameter['division'] = 0;
       		$link = sportsmanagementHelperRoute::getSportsmanagementRoute('ranking',$routeparameter);
	
	echo JHtml::link($link, JText::_('COM_SPORTSMANAGEMENT_TEAMINFO_SEASON_TABLES'));
	?> <br />
</div>
	<?php
	}
}
?>