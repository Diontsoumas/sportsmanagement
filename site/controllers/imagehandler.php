<?php
/** SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version   1.0.05
 * @file      imagehandler.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage 
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;

require_once (JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'imageselect.php');

class sportsmanagementControllerImagehandler extends BaseController {

    /**
     * Constructor
     *
     * @since 0.9
     */
    function __construct() {
        parent::__construct();

        // Register Extra task
    }

    /**
     * logic for uploading an image
     *
     * @access public
     * @return void
     * @since 0.9
     */
    function upload() {
        $app = Factory::getApplication();
        $option = Factory::getApplication()->input->getCmd('option');

        // Check for request forgeries
        JSession::checkToken() or jexit(Text::_('JINVALID_TOKEN'));

        $file = Factory::getApplication()->input->getVar('userfile', '', 'files', 'array');
        $type = Factory::getApplication()->input->getVar('type');
        $folder = ImageSelectSM::getfolder($type);
        $field = Factory::getApplication()->input->getVar('field');
        $linkaddress = Factory::getApplication()->input->getVar('linkaddress');
        // Set FTP credentials, if given
        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');
        //$ftp = JClientHelper::getCredentials( 'ftp' );
        //set the target directory
        $base_Dir = JPATH_SITE . DS . 'images' . DS . $option . DS . 'database' . DS . $folder . DS;

        $app->enqueueMessage(Text::_($type), '');
        $app->enqueueMessage(Text::_($folder), '');
        $app->enqueueMessage(Text::_($base_Dir), '');

        //do we have an imagelink?
        if (!empty($linkaddress)) {
            $file['name'] = basename($linkaddress);

            if (preg_match("/dfs_/i", $linkaddress)) {
                $filename = $file['name'];
            } else {
                //sanitize the image filename
                $filename = ImageSelectSM::sanitize($base_Dir, $file['name']);
            }

            $filepath = $base_Dir . $filename;

            if (!copy($linkaddress, $filepath)) {
                echo "<script> alert('" . Text::_('COM_SPORTSMANAGEMENT_ADMIN_IMAGEHANDLER_COPY_FAILED') . "'); window.history.go(-1); </script>\n";
                //$app->close();
            } else {
                //echo "<script> alert('" . Text::_( 'COPY COMPLETE'.'-'.$folder.'-'.$type.'-'.$filename.'-'.$field ) . "'); window.history.go(-1); window.parent.selectImage_".$type."('$filename', '$filename','$field'); </script>\n";
                echo "<script>  window.parent.selectImage_" . $type . "('$filename', '$filename','$field');window.parent.SqueezeBox.close(); </script>\n";
                //$app->close();
            }
        }

        //do we have an upload?
        if (empty($file['name'])) {
            echo "<script> alert('" . Text::_('COM_SPORTSMANAGEMENT_ADMIN_IMAGEHANDLER_CTRL_IMAGE_EMPTY') . "'); window.history.go(-1); </script>\n";
            //$app->close();
        }

        //check the image
        $check = ImageSelectSM::check($file);

        if ($check === false) {
            $app->redirect($_SERVER['HTTP_REFERER']);
        }

        //sanitize the image filename
        $filename = ImageSelectSM::sanitize($base_Dir, $file['name']);
        $filepath = $base_Dir . $filename;

        //upload the image
        if (!File::upload($file['tmp_name'], $filepath)) {
            echo "<script> alert('" . Text::_('COM_SPORTSMANAGEMENT_ADMIN_IMAGEHANDLER_CTRL_UPLOAD_FAILED') . "'); window.history.go(-1); </script>\n";
//          $app->close();
        } else {
//          echo "<script> alert('" . Text::_( 'COM_SPORTSMANAGEMENT_ADMIN_IMAGEHANDLER_CTRL_UPLOAD_COMPLETE'.'-'.$folder.'-'.$type.'-'.$filename.'-'.$field ) . "'); window.history.go(-1); window.parent.selectImage_".$type."('$filename', '$filename','$field'); </script>\n";
//          echo "<script> alert('" . Text::_( 'COM_SPORTSMANAGEMENT_ADMIN_IMAGEHANDLER_CTRL_UPLOAD_COMPLETE' ) . "'); window.history.go(-1); window.parent.selectImage_".$type."('$filename', '$filename','$field'); </script>\n";
            echo "<script>  window.parent.selectImage_" . $type . "('$filename', '$filename','$field');window.parent.SqueezeBox.close(); </script>\n";
//          $app->close();
        }
    }

    /**
     * logic to mass delete images
     *
     * @access public
     * @return void
     * @since 0.9
     */
    function delete() {
        $app = Factory::getApplication();
        $option = Factory::getApplication()->input->getCmd('option');
        
        // Set FTP credentials, if given
        jimport('joomla.client.helper');
        JClientHelper::setCredentialsFromRequest('ftp');

        // Get some data from the request
        $images = Factory::getApplication()->input->getVar('rm', array(), '', 'array');
        $type = Factory::getApplication()->input->getVar('type');

        $folder = ImageSelectSM::getfolder($type);

        if (count($images)) {
            foreach ($images as $image) {
                if ($image !== JFilterInput::clean($image, 'path')) {
                    JError::raiseWarning(100, Text::_('COM_SPORTSMANAGEMENT_ADMIN_IMAGEHANDLER_CTRL_UNABLE_TO_DELETE') . ' ' . htmlspecialchars($image, ENT_COMPAT, 'UTF-8'));
                    continue;
                }

                $fullPath = JPath::clean(JPATH_SITE . DS . 'images' . DS . $option . DS . 'database' . DS . $folder . DS . $image);
                $fullPaththumb = JPath::clean(JPATH_SITE . DS . 'images' . DS . $option . DS . 'database' . DS . $folder . DS . 'small' . DS . $image);
                if (is_file($fullPath)) {
                    File::delete($fullPath);
                    if (File::exists($fullPaththumb)) {
                        File::delete($fullPaththumb);
                    }
                }
            }
        }

        $app->redirect('index.php?option=' . $option . '&view=imagehandler&type=' . $type . '&tmpl=component');
    }

}

?>