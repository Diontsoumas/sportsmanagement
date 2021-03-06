<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      edit.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage projectteam
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;

$templatesToLoad = array('footer','fieldsets');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();

?>
<!-- import the functions to move the events between selection lists	-->
<?php
//$version = urlencode(sportsmanagementHelper::getVersion());

?>
<form action="<?php echo Route::_('index.php?option=com_sportsmanagement&view='.$this->view.'&layout=edit&id='.(int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">

<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo Text::_('COM_SPORTSMANAGEMENT_TABS_DETAILS'); ?></legend>
			<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('details') as $field) :?>
				<li><?php echo $field->label; ?>
				<?php echo $field->input; 
                
                if ( $field->name == 'jform[country]' || $field->name == 'jform[address_country]' )
                {
                echo JSMCountries::getCountryFlag($field->value);    
                }
                
                if ( $field->name == 'jform[website]' )
                {
                echo '<img style="" src="http://www.thumbshots.de/cgi-bin/show.cgi?url='.$field->value.'">';  
                }
                if ( $field->name == 'jform[twitter]' )
                {
                echo '<img style="" src="http://www.thumbshots.de/cgi-bin/show.cgi?url='.$field->value.'">';  
                }
                if ( $field->name == 'jform[facebook]' )
                {
                echo '<img style="" src="http://www.thumbshots.de/cgi-bin/show.cgi?url='.$field->value.'">';  
                }
                
                $suchmuster = array ("jform[","]");
                $ersetzen = array ('', '');
                $var_onlinehelp = str_replace($suchmuster, $ersetzen, $field->name);
                
                switch ($var_onlinehelp)
                {
                    case 'id':
                    case 'project_id':
                    case 'team_id':
                    break;
                    default:
                ?>
                <a	rel="{handler: 'iframe',size: {x: <?php echo COM_SPORTSMANAGEMENT_MODAL_POPUP_WIDTH; ?>,y: <?php echo COM_SPORTSMANAGEMENT_MODAL_POPUP_HEIGHT; ?>}}"
									href="<?php echo COM_SPORTSMANAGEMENT_HELP_SERVER.'SM-Backend-Felder:'.Factory::getApplication()->input->getVar( "view").'-'.$var_onlinehelp; ?>"
									 class="modal">
									<?php
									echo HTMLHelper::_(	'image','media/com_sportsmanagement/jl_images/help.png',
													Text::_('COM_SPORTSMANAGEMENT_HELP_LINK'),'title= "' .
													Text::_('COM_SPORTSMANAGEMENT_HELP_LINK').'"');
									?>
								</a>
                
                <?PHP
                break;
                }
                
                ?></li>
			<?php endforeach; ?>
			</ul>
		</fieldset>
	</div>

<div class="width-40 fltrt">
		<?php

if ( $this->change_training_date )
{
$startoffset = 2;     
}
else
{
$startoffset = 0; 
}    
		
        echo HTMLHelper::_('sliders.start','adminteam',array('startOffset'=>$startoffset));
		foreach ($fieldsets as $fieldset) :
			if ($fieldset->name == 'details') :
				continue;
			endif;
			echo HTMLHelper::_('sliders.panel', Text::_($fieldset->label), $fieldset->name);
		if (isset($fieldset->description) && !empty($fieldset->description)) :
				echo '<p class="tab-description">'.Text::_($fieldset->description).'</p>';
			endif;
		//echo $this->loadTemplate($fieldset->name);
        $this->fieldset = $fieldset->name;
        echo $this->loadTemplate('fieldsets');
		endforeach; ?>
		<?php echo HTMLHelper::_('sliders.end'); ?>

	
	</div>
    	
		<div class="clr"></div>
        
<div>        
<input type="hidden" name="pid" value="<?php echo $this->item->project_id; ?>" />
<input type="hidden" name="project_id" value="<?php echo $this->item->project_id; ?>" />
<input type="hidden" name="season_id" value="<?php echo $this->season_id; ?>" />
<input type="hidden" name="task" value="projectteam.edit" />
</div>
<?php echo HTMLHelper::_('form.token'); ?>
</form>
<?PHP
echo "<div>";
echo $this->loadTemplate('footer');
echo "</div>";
?>   