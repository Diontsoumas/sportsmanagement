<?php 
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      default_pagnav.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @subpackage results
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;

?>
<!-- matchdays pageNav -->
<br />
<div class="<?php echo $this->divclassrow; ?> table-responsive" id="defaultpagenav">
<table class="table" >
	<tr>
		<td>
			<?php
			if (!empty($this->rounds))
			{
				$pageNavigation  = "<div class='pagenav'>";
				$pageNavigation .= sportsmanagementModelPagination::pagenav($this->project,Factory::getApplication()->input->getInt('cfg_which_database',0));
				$pageNavigation .= "</div>";
				echo $pageNavigation;
			}
		?>
		</td>
	</tr>
</table>
<!-- matchdays pageNav END -->
</div>
