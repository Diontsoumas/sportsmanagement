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

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.folder');
JFormHelper::loadFieldClass('list');


/**
 * JFormFieldpositionlist
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class JFormFieldpositionlist extends JFormFieldList
{
    
	/**
	 * field type
	 * @var string
	 */
	public $type = 'positionlist';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Reference global application object
        $this->jsmapp = JFactory::getApplication();
        // JInput object
        $this->jsmjinput = $this->jsmapp->input;
        $this->jsmoption = $this->jsmjinput->getCmd('option');
        // Initialize variables.
		$options = array();
    $vartable = (string) $this->element['targettable'];
		$select_id = JFactory::getApplication()->input->getVar('id');
    $db = JFactory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('pos.id AS value, pos.name AS text');
			$query->from('#__sportsmanagement_position as pos');
			$query->join('INNER', '#__sportsmanagement_sports_type AS s ON s.id = pos.sports_type_id');
      //$query->join('INNER','#__sportsmanagement_'.$vartable.' AS t on t.sports_type_id = pos.sports_type_id');
            
			$query->where('pos.published = 1');
            //$query->where('pos.id = '.$select_id);
			$query->order('pos.ordering,pos.name');
			$db->setQuery($query);
            if ( JComponentHelper::getParams($this->jsmoption)->get('show_debug_info_backend') )
        {
		$this->jsmapp->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        }
		
        	try { 
			$options = $db->loadObjectList();
            }
catch (Exception $e) {
//    // catch any database errors.
//    $db->transactionRollback();
//    JErrorPage::render($e);
JFactory::getApplication()->enqueueMessage($db->getErrorMsg());
}
            
            foreach ( $options as $row )
            {
                $row->text = JText::_($row->text);
            }
    
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
