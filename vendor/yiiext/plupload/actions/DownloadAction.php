<?php
/**
 * @author Igor Chepurnoy
 * @link http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2014 Zfort Group
 * @license http://www.zfort.com/terms-of-use
 */


use CAction;
use CFileHelper;
use CHttpException;
use CJSON;
use Yii;

/**
 * This action allows to upload image
 *
 * @see Plupload
 *
 * @author Igor Chepurnoy
 * @version $Id$
 * @package zfort\widgets\plupload
 * @since 1.0
 */
class DownloadAction extends CAction
{

    /**
     * Runs the action.
     * Handles incoming uploaded file.
     * @throws CHttpException on failure.
     */
    public function run($id)
    {
        $userFileModel = $this->loadUserFileModel($id);
        Yii::app()->getRequest()->sendFile($userFileModel->getFileSelfName(), $userFileModel->getFileContent());
    }

    /**
     * Finds the user file model specified by id.
     * Checks if model belongs to the current user.
     * @param type $id
     * @return UserFileModel
     * @throws CHttpException
     */
    protected function loadUserFileModel($id)
    {
        /* @var UserFileModel|zfort\db\ar\behaviors\File $userFileModel */
        /* @var WebUser $webUser */
        $userFileModel = \mailContact\models\MailAttachmentModel::model()->findByPk($id);
        if (!is_object($userFileModel)) {
            throw new CHttpException(404, 'Unable to find requested file');
        }
        return $userFileModel;
    }


}