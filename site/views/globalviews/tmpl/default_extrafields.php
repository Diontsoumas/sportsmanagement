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

defined('_JEXEC') or die('Restricted access');

?>

<div class="row-fluid">
<h4>
<?php echo JText::_('COM_SPORTSMANAGEMENT_EXTRA_FIELDS'); ?>
</h4>
</div>

<?php
if ( isset($this->extrafields) )
{
foreach ($this->extrafields as $field)
{
$value = $field->fvalue;
$field_type = $field->field_type;
if (!empty($value)) // && !$field->backendonly)
{
?>
<div class="<?php echo COM_SPORTSMANAGEMENT_BOOTSTRAP_DIV_CLASS; ?>">
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
<strong><?php echo JText::_( $field->name); ?></strong>
</div>
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
<?php 
switch (JFactory::getApplication()->input->getVar('view'))
{
    case 'clubinfo':
    $title = $this->club->name;
    break;
    
}    
switch ($field_type)
{
    case 'link':
    echo JHtml::_( 'link', $field->fvalue,$title,  array( "target" => "_blank" ) );
    break;
    default:
    echo JText::_( $field->fvalue); 
    break;
}


?>
</div>
</div>
<?php
}
}
}
?>


