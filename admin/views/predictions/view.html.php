<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version   1.0.05
 * @file      view.html.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage predictions
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory; 
use Joomla\CMS\HTML\HTMLHelper;

/**
 * sportsmanagementViewpredictions
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewpredictions extends sportsmanagementView
{
	/**
	 *  view display method
	 * @return void
	 */
	public function init ()
	{
		
 
//		// Set the toolbar
//		$this->addToolBar();
// 
//		// Display the template
//		parent::display($tpl);
// 
//		// Set the document
//		$this->setDocument();
	}
 
	
	/**
	 * sportsmanagementViewpredictions::addToolBar()
	 * 
	 * @return void
	 */
	protected function addToolBar() 
	{
        parent::addToolbar();
	}
    
	
	
	/**
	 * sportsmanagementViewpredictions::addIcon()
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
