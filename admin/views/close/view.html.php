<?php
/** SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version   1.0.05
 * @file      view.html.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage close
 */

defined('_JEXEC') or die;
use Joomla\CMS\Language\Text;

/**
 * This view is displayed after successfull saving of config data.
 * Use it to show a message informing about success or simply close a modal window.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_config
 */
class sportsmanagementViewClose extends sportsmanagementView
{
	
    /**
	 * Display the view
	 */
	function init()
	{
	   
        $this->jsminfo = $this->jinput->getCmd('info');
        $this->onlymodal = $this->jinput->getCmd('onlymodal');
 
 if ( !$this->onlymodal )
 {
		// close a modal window
        $this->document->addScriptDeclaration('
        window.parent.location.href=window.parent.location.href;
			window.parent.SqueezeBox.close();
		// available msg types: success, error, notice
var msg = {
    error: [\'it is an error!<br />\', \'it is enother error!\'],
    success: [\'It works!!\']
};
Joomla.renderMessages( msg );
            
		');
		}
		else
		{
// close a modal window
        $this->document->addScriptDeclaration('
			window.parent.SqueezeBox.close();
		// available msg types: success, error, notice
var msg = {
    error: [\'it is an error!<br />\', \'it is enother error!\'],
    success: [\'It works!!\']
};
Joomla.renderMessages( msg );
            
		');		
		}
		
        
        switch($this->jsminfo)
        {
            case 'truncate':
            $this->app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' isAdmin<br><pre>'.print_r($this->app->isAdmin(),true).'</pre>'),'');
            break;
        }
        
        
	}
}
