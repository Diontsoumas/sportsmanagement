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

defined( '_JEXEC' ) or die( 'Restricted access' );

//echo 'output <pre>'.print_r($this->output,true).'</pre>';

?>
<div class="row-fluid" id="show_tabs">
<?php

if(version_compare(JVERSION,'3.0.0','ge')) 
{
// Joomla! 3.0 code here
$idxTab = 0;
$view = JFactory::getApplication()->input->getCmd('view');


?>

<!-- This is a list with tabs names. -->
<div class="panel with-nav-tabs panel-default">
<div class="panel-heading">
<!-- Tabs-Navs -->
<ul class="nav nav-tabs" role="tablist">
<?PHP			
foreach ($this->output as $key => $templ) 
{
$active = ($idxTab==0) ? 'active' : '';   
switch ($view)
{
case 'player':
$template = $templ['template'];
$text = $templ['text'];   
break;
default:
$template = $templ;
$text = $key;
break;
}    

?>

<li role="presentation" class="<?PHP echo $active; ?>"><a href="#<?PHP echo $text; ?>" role="tab" data-toggle="tab"><?PHP echo JText::_($text); ?></a>
</li>
<?PHP
$idxTab++;
}
?>
</ul>
</div>

<!-- Tab-Inhalte -->
<div class="panel-body">
<div class="tab-content">

<?PHP	
$idxTab = 0;		
foreach ($this->output as $key => $templ) 
{
$active = ($idxTab==0) ? 'in active' : '';   
switch ($view)
{
case 'player':
$template = $templ['template'];
$text = $templ['text'];   
break;
default:
$template = $templ;
$text = $key;
break;
}    

?>
<div role="tabpanel" class="tab-pane fade <?PHP echo $active; ?>" id="<?PHP echo $text; ?>">
<?PHP   
echo $this->loadTemplate($template);
?>
</div>
<?PHP
$idxTab++;
}
?>
</div>
</div>
</div>

<?PHP   
        
}
elseif(version_compare(JVERSION,'2.5.0','ge')) 
{
// Joomla! 2.5 code here
$view = JFactory::getApplication()->input->getCmd('view');
?>

<div class="panel with-nav-tabs panel-default">
<div class="panel-heading">

<!-- Tabs-Navs -->
<ul class="nav nav-tabs" >
<?PHP
$count = 0;

foreach ($this->output as $key => $templ)
{
$active = ($count==0) ? 'active' : '';   

switch ($view)
{
case 'player':
$template = $templ['template'];
$text = $templ['text'];   
break;
default:
$template = $templ;
$text = $key;
break;
}
?>  
<li class="<?PHP echo $active; ?>"><a href="#<?PHP echo $template; ?>" data-toggle="tab"><?PHP echo JText::_($text); ?></a></li>
<?PHP
$count++;
}
?>
</ul>
</div>
<!-- Tab-Inhalte -->
<div class="panel-body">
<div class="tab-content">
<?PHP
$count = 0;

foreach ($this->output as $key => $templ)
{
$active = ($count==0) ? 'in active' : '';
switch ($view)
{
case 'player':
$template = $templ['template'];
$text = $templ['text'];      
break;
default:
$template = $templ;
$text = $key;
break;
}
?>
<div class="tab-pane fade <?PHP echo $active; ?>" id="<?PHP echo $template; ?>">
<?PHP   
switch ($template)
{
case 'previousx':
$this->currentteam = $this->match->projectteam1_id;
echo $this->loadTemplate($template);
$this->currentteam = $this->match->projectteam2_id;
echo $this->loadTemplate($template);
break;
default:
echo $this->loadTemplate($template);
break;
}  
?>
</div>
<?PHP
$count++;
}
?>
</div>
</div>
</div>
<?PHP
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
?>
</div>