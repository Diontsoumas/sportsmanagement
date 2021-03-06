<?php
/** SportsManagement ein Programm zur Verwaltung f�r Sportarten
 * @version   1.0.05
 * @file      federationslist.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage fields
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\HTML\HTMLHelper;

jimport('joomla.filesystem.folder');
FormHelper::loadFieldClass('list');



/**
 * FormFieldFederationsList
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class JFormFieldFederationsList extends \JFormFieldList
{
	/**
	 * field type
	 * @var string
	 */
	public $type = 'FederationsList';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$app = Factory::getApplication();
        //$option = Factory::getApplication()->input->getCmd('option');
        $selected = 0;

        // Initialize variables.
		$options = array();
    //echo 'this->element<br /><pre>~' . print_r($this->element,true) . '~</pre><br />';
		//$varname = (string) $this->element['varname'];
    $vartable = (string) $this->element['targettable'];
		$select_id = $app->input->getVar('id');
//echo 'select_id<br /><pre>~' . print_r($select_id,true) . '~</pre><br />';		
 		if (is_array($select_id)) {
 			$select_id = $select_id;
 		}
		
		
		if ($select_id)
		{		
//		$db = Factory::getDbo();
//		$query = $db->getQuery(true);
//		$query->select('country');		
//		$query->from('#__sportsmanagement_'.$vartable.' AS t');
//		$query->where('t.id = '.$select_id);
//		$db->setQuery($query);
//		$country = $db->loadResult();
		//echo 'country<br /><pre>~' . print_r($country,true) . '~</pre><br />';
				
			$db = Factory::getDbo();
			$query = $db->getQuery(true);
			
			$query->select('t.id,t.id AS value, t.name AS text');
			$query->from('#__sportsmanagement_federations AS t');
			//$query->where("t.country = '".$country."'");
			$query->where('t.parent_id = 0');
			$query->order('t.name');
			$db->setQuery($query);
			//$options = $db->loadObjectList();
			
			$sections = $db->loadObjectList ();
            

            
  //$categoryparent = empty($sections) ? 0 : $sections->id;
  //echo 'categoryparent<br /><pre>~' . print_r($categoryparent,true) . '~</pre><br />';
  //$options = $this->JJ_categoryArray();
$list = $this->JJ_categoryArray(0);

$preoptions = array();
$name = 'parent_id';
foreach ( $list as $item ) 
    {
			if (!$preoptions && !$selected && ($sections || !$item->section)) 
      {
				$selected = $item->id;
			}
			$options [] = HTMLHelper::_ ( 'select.option', $item->id, $item->treename, 'value', 'text', !$sections && $item->section);
		}
		
		
		}
		
		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
	
/**
 * FormFieldFederationsList::JJ_categoryArray()
 * 
 * @param integer $admin
 * @return
 */
function JJ_categoryArray($admin=0) 
  {
$db = sportsmanagementHelper::getDBConnection(); 
    // get a list of the menu items
	$query = "SELECT * FROM #__sportsmanagement_federations ";

    $query .= " ORDER BY ordering, name";
    $db->setQuery($query);
    $items = $db->loadObjectList();

// echo 'JJ_categoryArray items<pre>';
//  	print_r($items);
//  	echo '</pre>';
    
    // establish the hierarchy of the menu
    $children = array ();

    // first pass - collect children
    foreach ($items as $v) 
    {
        $pt = $v->parent_id;
        $list = isset($children[$pt]) ? $children[$pt] : array ();
        array_push($list, $v);
        $children[$pt] = $list;
        }

    // second pass - get an indent list of the items
    $array = $this->fbTreeRecurse(0, '', array (), $children, 10, 0, 1);
    
//    echo 'JJ_categoryArray array<pre>';
//  	print_r($array);
//  	echo '</pre>';
	
    return $array;
    }    	
	
/**
 * FormFieldFederationsList::fbTreeRecurse()
 * 
 * @param mixed $id
 * @param mixed $indent
 * @param mixed $list
 * @param mixed $children
 * @param integer $maxlevel
 * @param integer $level
 * @param integer $type
 * @return
 */
function fbTreeRecurse( $id, $indent, $list, &$children, $maxlevel=9999, $level=0, $type=1 ) 
    {

    if (isset($children[$id]) && $level <= $maxlevel) {
        foreach ($children[$id] as $v) {
            $id = $v->id;
			if ( $type ) {
                $pre     = '&nbsp;';
                $spacer = '...';
            } else {
                $pre     = '- ';
                $spacer = '&nbsp;&nbsp;';
            }

            if ( $v->parent_id == 0 ) {
                $txt     = $this->sm_htmlspecialchars($v->name);
            } else {
                $txt     = $pre . $this->sm_htmlspecialchars($v->name);
            }
            $pt = $v->parent_id;
            $list[$id] = $v;
            $list[$id]->treename = $indent . $txt;
            $list[$id]->children = !empty($children[$id]) ? count( $children[$id] ) : 0;
            $list[$id]->section = ($v->parent_id==0);

            $list = $this->fbTreeRecurse( $id, $indent . $spacer, $list, $children, $maxlevel, $level+1, $type );
        }
    }
    return $list;
}    
	
/**
 * FormFieldFederationsList::sm_htmlspecialchars()
 * 
 * @param mixed $string
 * @param mixed $quote_style
 * @param string $charset
 * @return
 */
function sm_htmlspecialchars($string, $quote_style=ENT_COMPAT, $charset='UTF-8') 
  {
	return htmlspecialchars($string, $quote_style, $charset);
}	
	
}