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

//echo ' matchstaffs<br><pre>'.print_r($this->matchstaffs,true).'</pre><br>';

?>
<!-- Show Match staff -->
<?php
if (!empty($this->matchstaffpositions))
{
	?>
	<table class="table">
		<?php
		foreach ($this->matchstaffpositions as $pos)
		{
			?>
			<tr><td colspan="2" class="positionid"><?php echo JText::_($pos->name); ?></td></tr>
			<tr>
				<!-- list of home-team -->
				<td class="list">
					<div style="text-align: right; ">
						<ul style="list-style-type: none;">
							<?php
							foreach ($this->matchstaffs as $player)
							{
								if ($player->pposid==$pos->pposid && $player->ptid==$this->match->projectteam1_id)
								{
									?>
									<li class="list">
										<?php
                                        $routeparameter = array();
       $routeparameter['cfg_which_database'] = JFactory::getApplication()->input->getInt('cfg_which_database',0);
       $routeparameter['s'] = JFactory::getApplication()->input->getInt('s',0);
       $routeparameter['p'] = $this->project->slug;
       $routeparameter['tid'] = $player->team_slug;
       $routeparameter['pid'] = $player->person_slug;
                                        
										$player_link = sportsmanagementHelperRoute::getSportsmanagementRoute('staff',$routeparameter);
										$match_player = sportsmanagementHelper::formatName(null,$player->firstname,$player->nickname,$player->lastname, $this->config["name_format"]);
										echo JHtml::link($player_link,$match_player);
										$imgTitle = JText::sprintf('Picture of %1$s',$match_player);
										$picture = $player->picture;
										if (!file_exists($picture)){$picture = sportsmanagementHelper::getDefaultPlaceholder("player");}
										echo '&nbsp;';
echo sportsmanagementHelperHtml::getBootstrapModalImage('matchstaff'.$player->person_id,$picture,$imgTitle,$this->config['staff_picture_width']);
                                        ?>
                                        
                                        



                                        
									</li>
									<?php
								}
							}
							?>
						</ul>
					</div>
				</td>
				<!-- list of guest-team -->
				<td class="list">
					<div style="text-align: left;">
						<ul style="list-style-type: none;">
							<?php
							foreach ($this->matchstaffs as $player)
							{
								if ($player->pposid==$pos->pposid && $player->ptid==$this->match->projectteam2_id)
								{
									?>
									<li class="list">
										<?php
										$match_player = sportsmanagementHelper::formatName(null,$player->firstname,$player->nickname,$player->lastname, $this->config["name_format"]);
										$imgTitle = JText::sprintf('Picture of %1$s',$match_player);
										$picture = $player->picture;
										if (!file_exists($picture)){$picture = sportsmanagementHelper::getDefaultPlaceholder("player");}

echo sportsmanagementHelperHtml::getBootstrapModalImage('matchstaff'.$player->person_id,$picture,$imgTitle,$this->config['staff_picture_width']);

                                        ?>
                                        
 


                                        <?php
                                        echo '&nbsp;';
                                        $routeparameter = array();
       $routeparameter['cfg_which_database'] = JFactory::getApplication()->input->getInt('cfg_which_database',0);
       $routeparameter['s'] = JFactory::getApplication()->input->getInt('s',0);
       $routeparameter['p'] = $this->project->slug;
       $routeparameter['tid'] = $player->team_slug;
       $routeparameter['pid'] = $player->person_slug;
										$player_link = sportsmanagementHelperRoute::getSportsmanagementRoute('staff',$routeparameter);

										echo JHtml::link($player_link,$match_player);
										?>
									</li>
									<?php
								}
							}
							?>
						</ul>
					</div>
				</td>
			</tr>
			<?php
		}
		?>
	</table>
	<?php
}
?>
<!-- END of Match staff -->