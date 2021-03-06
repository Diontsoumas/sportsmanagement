<?php 
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      default.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage smquotestxt
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
$templatesToLoad = array('footer','listheader');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);
?>
<fieldset class="adminform">
			<legend><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_EXT_TXT'); ?></legend>
<table>
<?PHP
foreach ( $this->files as $file )
{


			$link = Route::_('index.php?option=com_sportsmanagement&view=smquotetxt&layout=default&file_name='.$file);
			?>
			<tr class="">
				<td class="center"></td>
				<?php
					
                    ?>
                    <td class="center" nowrap="nowrap">
								<a	href="<?php echo $link; ?>" >
                                    <?php
									$imageTitle=Text::_('COM_SPORTSMANAGEMENT_ADMIN_EXT_TXT_EDIT');
									echo HTMLHelper::_(	'image','administrator/components/com_sportsmanagement/assets/images/edit.png',
													$imageTitle,'title= "'.$imageTitle.'"');
									?>
                    </a>                 
					</td>
				<td>
                <?php
					
					echo $file;
					
					?>
     </td>
	
			</tr>
			<?php

    
}    

?>
</table>
</fieldset>
<?PHP
echo "<div>";
echo $this->loadTemplate('footer');
echo "</div>";
?>  