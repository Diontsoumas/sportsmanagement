<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      view.html.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage specialextensions
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper; 
use Joomla\CMS\Factory;

/**
 * sportsmanagementViewspecialextensions
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class sportsmanagementViewspecialextensions extends sportsmanagementView
{
	
	/**
	 * sportsmanagementViewspecialextensions::init()
	 * 
	 * @return void
	 */
	public function init ()
	{
		$app = Factory::getApplication();
		$jinput = $app->input;
		$option = $jinput->getCmd('option');
		$model	= $this->getModel();
        
		$this->Extensions = $model->getSpecialExtensions();
        

	}
	
	/**
	 * sportsmanagementViewspecialextensions::addIcon()
	 * 
	 * @param mixed $image
	 * @param mixed $url
	 * @param mixed $text
	 * @param bool $newWindow
	 * @return void
	 */
	public function addIcon( $image , $url , $text , $newWindow = false )
	{
		$lang		= Factory::getLanguage();
		$newWindow	= ( $newWindow ) ? ' target="_blank"' : '';
?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a href="<?php echo $url; ?>"<?php echo $newWindow; ?>>
					<?php echo HTMLHelper::_('image', 'administrator/components/com_sportsmanagement/assets/icons/' . $image , NULL, NULL ); ?>
					<span><?php echo $text; ?></span></a>
			</div>
		</div>
<?php
	}
	
}
