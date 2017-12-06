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

switch($this->fieldset)
{
case 'training':
?>                
                        <fieldset class="adminform">
                                
                                <table class='admintable'>
                                        <tr>
                                                <td class='key' nowrap='nowrap'>
                                                        <?php echo JText::_('JACTION_CREATE'); ?>&nbsp;<input type='checkbox' name='add_trainingData' id='add' value='1' onchange='javascript:submitbutton("team.apply");' />
                                                </td>
                                                <td class='key' style='text-align:center;' width='5%'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_DAY'); ?></td>
                                                <td class='key' style='text-align:center;' width='5%'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_STARTTIME'); ?></td>
                                                <td class='key' style='text-align:center;' width='5%'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_ENDTIME'); ?></td>
                                                <td class='key' style='text-align:center;'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_PLACE'); ?></td>
                                                <td class='key' style='text-align:center;'><?php echo JText::_('COM_SPORTSMANAGEMENT_ADMIN_P_TEAM_NOTES'); ?></td>
                                        </tr>
                                        <?php
                                        if (!empty($this->trainingData))
                                        {
                                                ?>
                                                <input type='hidden' name='tdCount' value='<?php echo count($this->trainingData); ?>' />
                                                <?php
                                                foreach ($this->trainingData AS $td)
                                                {
                                                        $hours=($td->time_start / 3600); $hours=(int)$hours;
                                                        $mins=(($td->time_start - (3600*$hours)) / 60); $mins=(int)$mins;
                                                        $startTime=sprintf('%02d',$hours).':'.sprintf('%02d',$mins);
                                                        $hours=($td->time_end / 3600); $hours=(int)$hours;
                                                        $mins=(($td->time_end - (3600*$hours)) / 60); $mins=(int)$mins;
                                                        $endTime=sprintf('%02d',$hours).':'.sprintf('%02d',$mins);
                                                        ?>
                                                        <tr>
                                                                <td class='key' nowrap='nowrap'>
                                                                        <?php echo JText::_('JACTION_DELETE');?>&nbsp;<input type='checkbox' name='delete[]' value='<?php echo $td->id; ?>' onchange='javascript:submitbutton("team.apply");' />
                                                                </td>
                                                                <td nowrap='nowrap' width='5%'><?php echo $this->lists['dayOfWeek'][$td->id]; ?></td>
                                                                <td nowrap='nowrap' width='5%'>
                                                                        <input class='text' type='text' name='time_start[<?php echo $td->id; ?>]' size='8' maxlength='5' value='<?php echo $startTime; ?>' />
                                                                </td>
                                                                <td nowrap='nowrap' width='5%'>
                                                                        <input class='text' type='text' name='time_end[<?php echo $td->id; ?>]' size='8' maxlength='5' value='<?php echo $endTime; ?>' />
                                                                </td>
                                                                <td>
                                                                        <input class='text' type='text' name='place[<?php echo $td->id; ?>]' size='40' maxlength='255' value='<?php echo $td->place; ?>' />
                                                                </td>
                                                                <td>
                                                                        <textarea class='text_area' name='notes[<?php echo $td->id; ?>]' rows='3' cols='40' value='' /><?php echo $td->notes; ?></textarea>
                                                                        <input type='hidden' name='tdids[]' value='<?php echo $td->id; ?>' />
                                                                </td>
                                                        </tr>
                                                        <?php
                                                }
                                        }
                                        ?>
                                </table>
                        </fieldset>
<?PHP
break;


// f�r extra felder
case 'extra_fields':
?>
	<fieldset class="adminform">
	
	<table>
    <?php
	for($p=0;$p<count($this->lists['ext_fields']);$p++)
            {
			?>
			<tr>
				<td width="100">
					<?php echo $this->lists['ext_fields'][$p]->name;?>
				</td>
				<td>
					<input type="text" maxlength="100" size="100" name="extraf[]" value="<?php echo isset($this->lists['ext_fields'][$p]->fvalue)?htmlspecialchars($this->lists['ext_fields'][$p]->fvalue):""?>" />
					<input type="hidden" name="extra_id[]" value="<?php echo $this->lists['ext_fields'][$p]->id?>" />
                    <input type="hidden" name="extra_value_id[]" value="<?php echo $this->lists['ext_fields'][$p]->value_id?>" />
				</td>
			</tr>
			<?php	
			}
	?>
    </table>
	</fieldset>
	<?php

break;

// f�r google maps    
case 'maps':
$document = JFactory::getDocument();
$document->addScript('http://maps.google.com/maps/api/js?&sensor=false');
?>
<script language="javascript" type="text/javascript">
var map;

function initialize() {
	var start = new google.maps.LatLng(<?php echo $this->item->latitude?>,<?php echo $this->item->longitude?>);
 	var image = 'http://maps.google.com/mapfiles/kml/pal2/icon49.png';
     var myOptions = {
      zoom: 12,
      center: start,
      mapTypeId: google.maps.MapTypeId.HYBRID
    };
    map = new google.maps.Map($('map'), myOptions);
    
    var marker = new google.maps.Marker({
      position: start,
      map: map,
      icon: image,
      title: '<?php echo $this->item->name?>'
  });
    
    kartenwerte();
	}
	
	function kartenwerte() {
	var mapcenter =  map.getCenter();
	$('conf_center_lat').value =mapcenter.lat();
	$('conf_center_lng').value =mapcenter.lng();	
	$('conf_start_zoom').value = map.getZoom();
	
	} 
</script>

<fieldset class="adminform">
			
<body onLoad="initialize()">              

<div id="map" style="width:400px; height:400px;"></div>
</fieldset>
<?PHP
break;

// f�r die extended daten
case 'extended':
if ( isset($this->extended) )
{
foreach ($this->extended->getFieldsets() as $fieldset)
{
	?>
	<fieldset class="adminform">
	
	<?php
	$fields = $this->extended->getFieldset($fieldset->name);
	
	if(!count($fields)) {
		echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NO_PARAMS');
	}
	
	foreach ($fields as $field)
	{
		echo $field->label;
       	echo $field->input;
	}
	?>
	</fieldset>
	<?php
}
}
else
{
    echo JText::_('COM_SPORTSMANAGEMENT_GLOBAL_NO_PARAMS');
}
break;

// das ist der standard
default:
?>
		<fieldset class="adminform">
			<table class="admintable">
					<?php 
                    foreach ($this->form->getFieldset($this->fieldset) as $field): 
                    ?>
					<tr>
						<td class="key"><?php echo $field->label; ?></td>
						<td><?php echo $field->input; ?></td>
                        <td>
                        <?PHP
                        $suchmuster = array ("jform[","]");
                $ersetzen = array ('', '');
                $var_onlinehelp = str_replace($suchmuster, $ersetzen, $field->name);
                
                switch ($var_onlinehelp)
                {
                    case 'id':
                    break;
                    default:
                ?>
                <a	rel="{handler: 'iframe',size: {x: <?php echo COM_SPORTSMANAGEMENT_MODAL_POPUP_WIDTH; ?>,y: <?php echo COM_SPORTSMANAGEMENT_MODAL_POPUP_HEIGHT; ?>}}"
									href="<?php echo COM_SPORTSMANAGEMENT_HELP_SERVER.'SM-Backend-Felder:'.JFactory::getApplication()->input->getVar( "view").'-'.$var_onlinehelp; ?>"
									 class="modal">
									<?php
									echo JHtml::_(	'image','media/com_sportsmanagement/jl_images/help.png',
													JText::_('COM_SPORTSMANAGEMENT_HELP_LINK'),'title= "' .
													JText::_('COM_SPORTSMANAGEMENT_HELP_LINK').'"');
									?>
								</a>
                
                <?PHP
                break;
                }
                ?> 
                </td>       
					</tr>					
					<?php endforeach; ?>
			</table>
		</fieldset>
<?PHP
break;
}

?>        