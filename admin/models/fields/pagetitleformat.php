<?php


defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;

class JFormFieldPageTitleFormat extends JFormField
{
	protected $type = 'pagetitleformat';

	function getInput() 
    {
		$lang = JFactory::getLanguage();
		$extension = "com_sportsmanagement";
		$source = JPath::clean(JPATH_ADMINISTRATOR . '/components/' . $extension);
		$lang->load($extension, JPATH_ADMINISTRATOR, null, false, false)
		||	$lang->load($extension, $source, null, false, false)
		||	$lang->load($extension, JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
		||	$lang->load($extension, $source, $lang->getDefault(), false, false);
		
		$mitems = array();
		$mitems[] = JHtml::_('select.option', 0, Text::_('COM_SPORTSMANAGEMENT_FES_PARAM_PAGE_TITLE_PROJECT'));
		$mitems[] = JHtml::_('select.option', 1, Text::_('COM_SPORTSMANAGEMENT_FES_PARAM_PAGE_TITLE_PROJECT_LEAGUE'));
		$mitems[] = JHtml::_('select.option', 2, Text::_('COM_SPORTSMANAGEMENT_FES_PARAM_PAGE_TITLE_PROJECT_LEAGUE_SEASON'));
		$mitems[] = JHtml::_('select.option', 3, Text::_('COM_SPORTSMANAGEMENT_FES_PARAM_PAGE_TITLE_PROJECT_SEASON'));
		$mitems[] = JHtml::_('select.option', 4, Text::_('COM_SPORTSMANAGEMENT_FES_PARAM_PAGE_TITLE_LEAGUE'));
		$mitems[] = JHtml::_('select.option', 5, Text::_('COM_SPORTSMANAGEMENT_FES_PARAM_PAGE_TITLE_LEAGUE_SEASON'));
		$mitems[] = JHtml::_('select.option', 6, Text::_('COM_SPORTSMANAGEMENT_FES_PARAM_PAGE_TITLE_SEASON'));
		$mitems[] = JHtml::_('select.option', 7, Text::_('COM_SPORTSMANAGEMENT_FES_PARAM_PAGE_TITLE_NONE'));
		
		$output= JHtml::_('select.genericlist',  $mitems,
							$this->name,
							'class="inputbox" size="1"', 
							'value', 'text', $this->value, $this->id);
		return $output;
	}
}
