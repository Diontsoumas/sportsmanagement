<?php 
/** SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version   1.0.05
 * @file      default_teams.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage projectteams
 */
 
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Router\Route;

HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.modal');
$app = Factory::getApplication();

$view = $this->jinput->getVar( "view") ;
$view = ucfirst(strtolower($view));
$cfg_help_server = ComponentHelper::getParams($this->jinput->getCmd('option'))->get('cfg_help_server','') ;
$cfg_bugtracker_server = ComponentHelper::getParams($this->jinput->getCmd('option'))->get('cfg_bugtracker_server','') ;

?>

	<div  class="table-responsive" id="editcell">

			<legend><?php echo Text::sprintf('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_LEGEND','<i>'.$this->project->name.'</i>'); ?></legend>
			<?php $cell_count=25; ?>
			<table class="<?php echo $this->table_data_class; ?>">
				<thead>
					<tr>
						<th ><?php echo Text::_('COM_SPORTSMANAGEMENT_GLOBAL_NUM'); ?></th>
						<th >
							<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
						</th>

						<th>
							<?php echo HTMLHelper::_('grid.sort','COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_TEAMNAME','t.name',$this->sortDirection,$this->sortColumn); ?>
							<a href="mailto:<?php
											$first_dest=1;
											foreach ($this->projectteam as $r)
											{
												if (($r->club_id) > 0 and strlen($r->club_email) > 0)
												{
													if (!$first_dest)
													{
														echo ",%20".str_replace(" ","%20",$r->club_email);
													}
													else
													{
														$first_dest=0;
														echo str_replace(" ","%20",$r->club_email);
													}
												}
											}
											?>?subject=[<?php echo $app->getCfg('sitename'); ?>]">
								<?php
								$imageFile = 'administrator/components/com_sportsmanagement/assets/images/mail.png';
								$imageTitle = Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_SEND_MAIL_TEAMS');
								$imageParams = 'title= "'.$imageTitle.'"';
								$image = HTMLHelper::image($imageFile,$imageTitle,$imageParams);
								$linkParams = '';
								echo $image;
								?>
							</a>
						</th>
                        <th >
						<?php
						echo HTMLHelper::_('grid.sort','COM_SPORTSMANAGEMENT_ADMIN_AGEGROUP_COUNTRY','obj.country',$this->sortDirection,$this->sortColumn);
						?>
                        <br />
                        <?php 
                        echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUB_CITY'); 
                        ?>
                        <br />
                        <?php 
                        echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUB_FOUNDED_YEAR'); 
                        ?>
                        <br />
                        <?php 
                        echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUB_UNIQUE_ID'); 
                        ?>
					</th>
						<th colspan="2"><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_MANAGE_PERSONNEL'); ?></th>
						<th >
							<?php echo HTMLHelper::_('grid.sort','COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_ADMIN','tl.admin',$this->sortDirection,$this->sortColumn); ?>
							<a href="mailto:<?php
											$first_dest=1;
											foreach ($this->projectteam as $r)
											{
												if (strlen($r->editor) > 0 and strlen($r->email) > 0)
												{
													if (!$first_dest)
													{
														echo ",%20".str_replace(" ","%20",$r->email);
													}
													else
													{
														$first_dest=0;
														echo str_replace(" ","%20",$r->email);
													}
												}
											}
											?>?subject=[<?php echo $app->getCfg('sitename'); ?>]">
								<?php
								$imageFile='administrator/components/com_sportsmanagement/assets/images/mail.png';
								$imageTitle=Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_SEND_MAIL_ADMINS');
								$imageParams='title= "'.$imageTitle.'"';
								$image=HTMLHelper::image($imageFile,$imageTitle,$imageParams);
								$linkParams='';
								echo $image;
								?></a>
						</th>
						<?php
						if ($this->project->project_type == 'DIVISIONS_LEAGUE')
						{
							$cell_count++;
							?><th >
								<?php echo HTMLHelper::_('grid.sort','COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_DIVISION','d.name',$this->sortDirection,$this->sortColumn);
									echo '<br>'.HTMLHelper::_(	'select.genericlist',
														$this->lists['divisions'],
														'division',
														'class="inputbox" size="1" onchange="window.location.href=window.location.href.split(\'&division=\')[0]+\'&division=\'+this.value"',
														'value','text', $this->division);
//echo $this->lists['divisions'];
								?>
							</th><?php
						}
						?>
						<th>
							<?php echo HTMLHelper::_('grid.sort','COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_PICTURE','tl.picture',$this->sortDirection,$this->sortColumn); ?>
                            <br />
                            <?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_VENUE'); ?>
						</th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_INITIAL_POINTS'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_MA'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_PLUS_P'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_MINUS_P'); ?></th>
                        <th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_PENALTY_P'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_W'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_D'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_L'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_HG'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_GG'); ?></th>
						<th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_DG'); ?></th>
                        
                        <th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_IS_IN_SCORE'); ?></th>
                        <th><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_USE_FINALLY'); ?></th>
                        
                        <th >
							<?php echo HTMLHelper::_('grid.sort','STID','st.id',$this->sortDirection,$this->sortColumn); ?>
						</th>
						<th >
							<?php echo HTMLHelper::_('grid.sort','TID','st.team_id',$this->sortDirection,$this->sortColumn); ?>
						</th>
						<th >
							<?php echo HTMLHelper::_('grid.sort','JGRID_HEADING_ID','tl.id',$this->sortDirection,$this->sortColumn); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
                <tr>
                <td colspan="<?php echo $cell_count - 4; ?>">
                <?php echo $this->pagination->getListFooter(); ?>
                </td>
                <td colspan="4">
                <?php echo $this->pagination->getResultsCounter(); ?>
                </td>
                </tr>
                </tfoot>
				<tbody>
					<?php
					$k=0;
					for ($i=0, $n=count($this->projectteam); $i < $n; $i++)
					{
						$row = &$this->projectteam[$i];
						$link1 = Route::_('index.php?option=com_sportsmanagement&task=projectteam.edit&id='.$row->id.'&pid='.$this->project->id."&team_id=".$row->team_id );
                        $link2 = Route::_('index.php?option=com_sportsmanagement&view=teampersons&persontype=1&project_team_id='.$row->id."&team_id=".$row->team_id.'&pid='.$this->project->id);
						$link3 = Route::_('index.php?option=com_sportsmanagement&view=teampersons&persontype=2&project_team_id='.$row->id."&team_id=".$row->team_id.'&pid='.$this->project->id);
                        $canEdit	= $this->user->authorise('core.edit','com_sportsmanagement');
                        $canCheckin = $this->user->authorise('core.manage','com_checkin') || $row->checked_out == $this->user->get ('id') || $row->checked_out == 0;
						$checked = HTMLHelper::_('jgrid.checkedout', $i, $this->user->get ('id'), $row->checked_out_time, 'projectteams.', $canCheckin);
						?>
						<tr class="">
						<td class="center">
                        <?php
                        echo $this->pagination->getRowOffset($i);
                        ?>
                        </td>
                        <td class="center">
                        <?php 
                        echo HTMLHelper::_('grid.id', $i, $row->id);  
                        ?>
                      <!--  </td> -->
							<?php
							
								$inputappend='';
								?>
					<!--			<td style="text-align:center; "> -->
                                
                                <?php if ($row->checked_out) : ?>
						<?php echo HTMLHelper::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'projectteams.', $canCheckin); ?>
					<?php endif; ?>
					<?php if ($canEdit && !$row->checked_out ) : ?>
						<?php
									$imageFile = 'administrator/components/com_sportsmanagement/assets/images/edit.png';
									$imageTitle = Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_EDIT_DETAILS');
									$imageParams = 'title= "'.$imageTitle.'"';
									$image = HTMLHelper::image($imageFile,$imageTitle,$imageParams);
									$linkParams = '';
									echo HTMLHelper::link($link1,$image);
                                    

									?>
					<?php else : ?>
							<?php //echo $this->escape($row->name); ?>
					<?php endif; 
                    
                    
                            
									?>
                                    
                                    </td>
								<?php
							
							?>
							<td>
                            <?php 
/**
 * die möglichkeit bieten, das vereinslogo zu aktualisieren
 */
$link = 'index.php?option=com_sportsmanagement&view=club&layout=edit&tmpl=component&id='.$row->club_id;
$image = 'icon-16-Teams.png';

                            if ( $row->club_logo == '' )
							{
								$imageTitle = Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUBS_NO_IMAGE');
								echo HTMLHelper::_(	'image','administrator/components/com_sportsmanagement/assets/images/information.png',
												$imageTitle,'title= "'.$imageTitle.'"');
echo sportsmanagementHelper::getBootstrapModalImage('projectteam'.$row->club_id,
Uri::root().'administrator/components/com_sportsmanagement/assets/images/'.$image,
Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUBS_EDIT_DETAILS'),
'20',
Uri::base().$link,
$this->modalwidth,
$this->modalheight);
?>
              
														
              <?php                               

							}
							elseif ( $row->club_logo == sportsmanagementHelper::getDefaultPlaceholder("clublogobig") )
							{
								$imageTitle = Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUBS_DEFAULT_IMAGE');
								echo HTMLHelper::_(	'image','administrator/components/com_sportsmanagement/assets/images/information.png',
												$imageTitle,'title= "'.$imageTitle.'"');
?>
<a href="<?php echo Uri::root().$row->club_logo;?>" title="<?php echo $imageTitle;?>" class="modal">
<img src="<?php echo Uri::root().$row->club_logo;?>" alt="<?php echo $imageTitle;?>" width="20" />
</a>
<?PHP

echo sportsmanagementHelper::getBootstrapModalImage('projectteam'.$row->club_id,
Uri::root().'administrator/components/com_sportsmanagement/assets/images/'.$image,
Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUBS_EDIT_DETAILS'),
'20',
Uri::base().$link,
$this->modalwidth,
$this->modalheight);
?>
              
													
              <?php           


                                                
							} else {
                                
                                if ( File::exists(JPATH_SITE.DS.$row->club_logo) ) {
									$imageTitle = Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUBS_CUSTOM_IMAGE');
									echo HTMLHelper::_(	'image','administrator/components/com_sportsmanagement/assets/images/ok.png',
													$imageTitle,'title= "'.$imageTitle.'"');
?>
<a href="<?php echo Uri::root().$row->club_logo;?>" title="<?php echo $imageTitle;?>" class="modal">
<img src="<?php echo Uri::root().$row->club_logo;?>" alt="<?php echo $imageTitle;?>" width="20" />
</a>
<?PHP 
echo sportsmanagementHelper::getBootstrapModalImage('projectteam'.$row->club_id,
Uri::root().'administrator/components/com_sportsmanagement/assets/images/'.$image,
Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUBS_EDIT_DETAILS'),
'20',
Uri::base().$link,
$this->modalwidth,
$this->modalheight);                                

?>
              
							
							
              <?php                                                                
								} else {
//									$imageTitle=Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUBS_NO_IMAGE');
//									echo HTMLHelper::_(	'image','administrator/components/com_sportsmanagement/assets/images/delete.png',
//													$imageTitle,'title= "'.$imageTitle.'"');

echo sportsmanagementHelper::getBootstrapModalImage('projectteam'.$row->club_id,
Uri::root().'administrator/components/com_sportsmanagement/assets/images/'.$image,
Text::_('COM_SPORTSMANAGEMENT_ADMIN_CLUBS_NO_IMAGE'),
'20',
Uri::base().$link,
$this->modalwidth,
$this->modalheight);                                

?>
              
							
							
              <?php                                
                                
                                
                                
                                                    
								}
							}
                            echo $row->name; ?>
                            </td>
                            <td class="center">
                            <?php
                            echo JSMCountries::getCountryFlag($row->country);
                            ?>
                            <br>
                            <?PHP
                            echo $row->latitude;
                            ?>
                            <br>
                            <?PHP
                            echo $row->longitude;
                            ?>
                            <br>
	<input<?php echo $inputappend; ?> type="text" size="25" class="form-control form-control-inline"
	name="location<?php echo $row->id; ?>"
	value="<?php echo $row->location; ?>"
	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />  
	<br>
	<input<?php echo $inputappend; ?> type="text" size="25" class="form-control form-control-inline"
	name="founded_year<?php echo $row->id; ?>"
	value="<?php echo $row->founded_year; ?>"
	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />  
    
    <br>
	<input<?php echo $inputappend; ?> type="text" size="20" class="form-control form-control-inline"
	name="unique_id<?php echo $row->id; ?>"
	value="<?php echo $row->unique_id; ?>"
	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
    
	<input<?php echo $inputappend; ?> type="hidden" size="25" class="form-control form-control-inline"
	name="club_id<?php echo $row->id; ?>"
	value="<?php echo $row->club_id; ?>"
	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />  
	
                            </td>
                            
							<td class="center"><?php
								if( $row->playercount == 0 ) {
									$image = "players_add.png";
								} else {
									$image = "players_edit.png";
								}
								$imageFile = 'administrator/components/com_sportsmanagement/assets/images/'.$image;
								$imageTitle = Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_MANAGE_PLAYERS');
								$imageParams = 'title= "'.$imageTitle.'"';
								$image = HTMLHelper::image($imageFile,$imageTitle,$imageParams).' <sub>'.$row->playercount.'</sub>';
								$linkParams = '';
								echo HTMLHelper::link($link2,$image);
								?></td>
							<td class="center"><?php
								if( $row->staffcount == 0 ) {
									$image = "players_add.png";
								} else {
									$image = "players_edit.png";
								}
								$imageFile = 'administrator/components/com_sportsmanagement/assets/images/'.$image;
								$imageTitle = Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_MANAGE_STAFF');
								$imageParams = 'title= "'.$imageTitle.'"';
								$image = HTMLHelper::image($imageFile,$imageTitle,$imageParams).' <sub>'.$row->staffcount.'</sub>';
								$linkParams = '';
								echo HTMLHelper::link($link3,$image);
								?></td>
							<td class="center"><?php echo $row->editor; ?></td>
							<?php
							if ( $this->project->project_type == 'DIVISIONS_LEAGUE' )
							{
								?>
								<td class="nowrap" class="center">
									<?php
									$append='';
									if ($row->division_id == 0)
									{
										$append=' style="background-color:#bbffff"';
									}
									echo HTMLHelper::_(	'select.genericlist',
													$this->lists['divisions'],
													'division_id'.$row->id,
													$inputappend.'class="form-control form-control-inline" size="1" onchange="document.getElementById(\'cb' .
													$i.'\').checked=true"'.$append,
													'value','text',$row->division_id);
									?>
								</td>
								<?php
							}
							?>
							<td class="center">
								<?php
								if (empty($row->picture) || !File::exists(JPATH_SITE.DS.$row->picture))
								{
									$imageTitle=Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_NO_IMAGE').$row->picture;
									echo HTMLHelper::image(	'administrator/components/com_sportsmanagement/assets/images/delete.png',
														$imageTitle,'title= "'.$imageTitle.'"');
								}
								elseif ($row->picture == sportsmanagementHelper::getDefaultPlaceholder("team"))
								{
									$imageTitle=Text::_('COM_SPORTSMANAGEMENT_ADMIN_PROJECTTEAMS_DEFAULT_IMAGE');
									echo HTMLHelper::image('administrator/components/com_sportsmanagement/assets/images/information.png',
														$imageTitle,'title= "'.$imageTitle.'"');
								
?>
<a href="<?php echo Uri::root().$row->picture;?>" title="<?php echo $imageTitle;?>" class="modal">
<img src="<?php echo Uri::root().$row->picture;?>" alt="<?php echo $imageTitle;?>" width="100"  />
</a>
<?PHP                                 
                                
                                }
								else
								{
								    if (File::exists(JPATH_SITE.DS.$row->picture)) {
									$imageTitle=Text::_('COM_SPORTSMANAGEMENT_ADMIN_TEAMS_CUSTOM_IMAGE');
									echo HTMLHelper::_(	'image','administrator/components/com_sportsmanagement/assets/images/ok.png',
													$imageTitle,'title= "'.$imageTitle.'"');
?>
<a href="<?php echo Uri::root().$row->picture;?>" title="<?php echo $imageTitle;?>" class="modal">
<img src="<?php echo Uri::root().$row->picture;?>" alt="<?php echo $imageTitle;?>" width="100" />
</a>
<?PHP                                                     
								} else {
									$imageTitle=Text::_('COM_SPORTSMANAGEMENT_ADMIN_TEAMS_NO_IMAGE');
									echo HTMLHelper::_(	'image','administrator/components/com_sportsmanagement/assets/images/delete.png',
													$imageTitle,'title= "'.$imageTitle.'"');
								}

								}
								?>
                                <br />
                                <?PHP
                                if ( $row->playground_picture )
                                {
                                ?>
<a href="<?php echo Uri::root().$row->playground_picture;?>" title="<?php echo $imageTitle;?>" class="modal">
<img src="<?php echo Uri::root().$row->playground_picture;?>" alt="<?php echo $imageTitle;?>" width="100"  />
</a>
<?PHP 
      }
      ?>                          
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="start_points<?php echo $row->id; ?>"
																	value="<?php echo $row->start_points; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="matches_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->matches_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="points_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->points_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="neg_points_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->neg_points_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
                            <td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="penalty_points<?php echo $row->id; ?>"
																	value="<?php echo $row->penalty_points; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
                            
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="won_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->won_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="draws_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->draws_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="lost_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->lost_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="homegoals_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->homegoals_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="guestgoals_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->guestgoals_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
							<td class="center">
								<input<?php echo $inputappend; ?>	type="text" size="2" class="form-control form-control-inline"
																	name="diffgoals_finally<?php echo $row->id; ?>"
																	value="<?php echo $row->diffgoals_finally; ?>"
																	onchange="document.getElementById('cb<?php echo $i; ?>').checked=true" />
							</td>
                            
                            <td class="center">
									<?php
                                    $append=' style="background-color:#bbffff"';
									echo HTMLHelper::_(	'select.genericlist',
													$this->lists['is_in_score'],
													'is_in_score'.$row->id,
													$inputappend.'class="form-control form-control-inline" size="1" onchange="document.getElementById(\'cb' .
													$i.'\').checked=true"'.$append,
													'value','text',$row->is_in_score);
									?>
								</td>
                            <td class="center">
									<?php
                                    $append=' style="background-color:#bbffff"';
									echo HTMLHelper::_(	'select.genericlist',
													$this->lists['use_finally'],
													'use_finally'.$row->id,
													$inputappend.'class="form-control form-control-inline" size="1" onchange="document.getElementById(\'cb' .
													$i.'\').checked=true"'.$append,
													'value','text',$row->use_finally);
									?>
								</td>
                            
                            <td class="center"><?php echo $row->season_team_id; ?></td>
							<td class="center"><?php echo $row->team_id; ?></td>
							<td class="center"><?php echo $row->id; ?></td>
						</tr>
						<?php
						$k=(1-$k);
					}
					?>
				</tbody>

			</table>
	<!--	</fieldset> -->
	</div>
	
<?PHP

?>   
