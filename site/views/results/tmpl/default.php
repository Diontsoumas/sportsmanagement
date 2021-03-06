<?php 
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version   1.0.05
 * @file      default.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @subpackage results
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Component\ComponentHelper;
if ( ComponentHelper::getParams('com_sportsmanagement')->get('show_debug_info_frontend') )
{
echo __METHOD__.' '.__LINE__.' divclasscontainer<br><pre>'.print_r($this->divclasscontainer,true).'</pre>'; 
echo __METHOD__.' '.__LINE__.' divclassrow<br><pre>'.print_r($this->divclassrow,true).'</pre>'; 	
}

// Make sure that in case extensions are written for mentioned (common) views,
// that they are loaded i.s.o. of the template of this view
$templatesToLoad = array('globalviews');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);
?>
<div class="<?php echo $this->divclasscontainer;?>" id="defaultresults">
<?php
    
if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
{
echo $this->loadTemplate('debug');
}    
echo $this->loadTemplate('projectheading');

if ( $this->config['show_sectionheader'] )
{
echo $this->loadTemplate('sectionheader');
}

if ( $this->config['show_matchday_pagenav'] == 2 || $this->config['show_matchday_pagenav'] == 3 )
{
echo $this->loadTemplate('pagnav');
}

if ( $this->config['show_pagenav'] )
{	
echo $this->loadTemplate('pagination');
}	

echo $this->loadTemplate('results');
	
if ( $this->config['show_matchday_pagenav'] == 1 || $this->config['show_matchday_pagenav'] == 3 )
{
echo $this->loadTemplate('pagnav');
}
	
if ( $this->overallconfig['show_project_rss_feed'] )
{
if ( $this->rssfeeditems )
{
echo $this->loadTemplate('rssfeed');    
}
}

echo $this->loadTemplate('jsminfo');
?>
</div>
