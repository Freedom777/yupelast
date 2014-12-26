
     /**
     * @return array list of actions.
     */
    public function actions()
    {
        return array(
            'upload' => array(
                'class' => '\yii-ext\plupload\Action',
                'completeCallback' => function ($fileFullName, $fileSelfName) {
            $fileModel = new UserFileModel();
            $fileModel->userId = Yii::app()->user->id;
            $fileModel->name = $fileSelfName;
            $fileModel->file = $fileFullName;
            if (!$fileModel->save()) {
                throw new CHttpException(500, CVarDumper::dumpAsString($fileModel->getErrors()));
            }
            $response = array(
                'downloadUrl' => Yii::app()->createUrl('downloadfile', array('id' => $fileModel->id)),
                'deleteUrl' => Yii::app()->createUrl('deletefile', array('id' => $fileModel->id)),
            );
            return $response;
        }
            )
        );
    }
    
    
    
<div class="details-form pass-form clearfix">
    <div class="f-row clearfix">
        <label>Upload CV Files:</label>
        <div class="f-input">
            <?php
            $this->widget('FileUploader', array(
                'id' => 'filelist',
                'uploadUrl' => array('upload'),
                'files' => $userModel->files,
                'maxFileCount' => 3,
                'clientOptions' => array(
                    'filters' => array(
                        'mime_types' => array(
                            array(
                                'title' => 'Documents',
                                'extensions' => implode(',', Yii::app()->params['allowedUserFileTypes']),
                            )
                        ),
                        'max_file_size' => '2mb',
                        'prevent_duplicates' => true,
                    ),
                ),
            ));
            ?>
        </div>
    </div>
</div>
    
    
    
    
    **
     * Allow downloading of the user file specified by its id.
     * @param integer $id user file id.
     * @throws CHttpException on failure.
     */
    public function actionDownloadfile($id)
    {
        $userFileModel = $this->loadUserFileModel($id);
        Yii::app()->getRequest()->sendFile($userFileModel->getFileSelfName(), $userFileModel->getFileContent());
    }

    /**
     * Deletes user file specified by its id.
     * @param integer $id user file id.
     * @throws CHttpException on failure.
     */
    public function actionDeletefile($id)
    {
        $userFileModel = $this->loadUserFileModel($id);
        $userFileModel->delete();
        if (!Yii::app()->getRequest()->getIsAjaxRequest()) {
            $this->redirect(array('resume'));
        } else {
            Yii::app()->end();
        }
    }

    /**
     * Finds the user file model specified by id.
     * Checks if model belongs to the current user.
     * @param integer $id user file id.
     * @return UserFileModel|\zfort\db\ar\behaviors\File user file model instance.
     * @throws CHttpException on failure.
     */
    protected function loadUserFileModel($id)
    {
        /* @var UserFileModel|zfort\db\ar\behaviors\File $userFileModel */
        /* @var WebUser $webUser */
        $webUser = Yii::app()->getComponent('user');
        $userFileModel = UserFileModel::model()->findByPk($id);
        if (!is_object($userFileModel) || $userFileModel->userId != $webUser->getId()) {
            throw new CHttpException(404, 'Unable to find requested file');
        }
        return $userFileModel;
    }