<?php 
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version   1.0.05
 * @file      default_explanation.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage rankingalltime
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
$config   = &$this->tableconfig;

$columns = explode( ",", $config['ordered_columns'] );
$column_names	= explode( ',', $config['ordered_columns_names'] );
?>

<br />
<table width="96%" align="center" border="0" cellpadding="0" cellspacing="0">
	<tr class="explanation">
		<td>
		<?php
		$d = 0;
		foreach (  $columns as $k => $column)
		{
			if (empty($column_names[$k])){$column_names[$k]='???';}	
			$c=strtoupper(trim($column));
			$c="COM_JOOMLEAGUE_".$c;
			echo "<td class=\"col$d\">";
			echo $column_names[$k] ." = ".Text::_($c) ;
			echo "</td>";
			$d=(1-$d);
		}
		?>
		</td>
	</tr>
</table>