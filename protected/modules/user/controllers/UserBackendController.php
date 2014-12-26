<?php

/**
 * Контроллер, отвечающий за работу с пользователями в панели управления
 *
 * @category YupeControllers
 * @package  yupe.modules.user.controllers
 * @author   YupeTeam <team@yupe.ru>
 * @license  BSD http://ru.wikipedia.org/wiki/%D0%9B%D0%B8%D1%86%D0%B5%D0%BD%D0%B7%D0%B8%D1%8F_BSD
 * @version  0.6
 * @link     http://yupe.ru
 *
 **/
class UserBackendController extends yupe\components\controllers\BackController
{
    public function accessRules()
    {
        return array(
            array('allow', 'roles' => array('admin')),
            array('allow', 'actions' => array('create'), 'roles' => array('User.UserBackend.Create')),
            array('allow', 'actions' => array('delete'), 'roles' => array('User.UserBackend.Delete')),
            array('allow', 'actions' => array('index'), 'roles' => array('User.UserBackend.Index')),
            array('allow', 'actions' => array('inlineEdit'), 'roles' => array('User.UserBackend.Update')),
            array('allow', 'actions' => array('update'), 'roles' => array('User.UserBackend.Update')),
            array('allow', 'actions' => array('view'), 'roles' => array('User.UserBackend.View')),
            array('allow', 'actions' => array('view'), 'roles' => array('User.UserBackend.Changepassword')),
            array('deny')
        );
    }

    public function actions()
    {
        return array(
            'inline' => array(
                'class'           => 'yupe\components\actions\YInLineEditAction',
                'model'           => 'User',
                'validAttributes' => array('access_level', 'status', 'email_confirm')
            ),
            'upload' => array(
                'class' => 'vendor.yiiext.plupload.actions.UploadAction',
                'completeCallback' => function ($fileFullName, $fileSelfName) {
                        $Image = new Image;
                        $Image->name = $fileSelfName;
                        $Image->file = $fileFullName;
                        $Image->alt = $fileFullName;
                        $Image->user_id = Yii::app()->user->id;
                        // $Image->type = self::TYPE_SIMPLE;

                        if (!$Image->save()) {
                            throw new CHttpException(500, CVarDumper::dumpAsString($Image->getErrors()));
                        }

                        $response = array(
                            'downloadUrl' => Yii::app()->createUrl('downloadfile', array('id' => $Image->id)),
                            'deleteUrl' => Yii::app()->createUrl('deletefile', array('id' => $Image->id)),
                        );

                        return $response;


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
            ),

            // 'upload' => 'vendor.yiiext.plupload.actions.UploadAction',
            // 'destroy' => 'vendor.yiiext.plupload.actions.DestroyAction',
        );
    }

    /**
     * @var CActiveRecord the currently loaded data model instance.
     */
    private $_model;

    /**
     * Displays a particular model.
     *
     * @param int $id - record ID
     *
     * @return void
     */
    public function actionView($id)
    {
        $this->render('view', array('model' => $this->loadModel($id)));
    }

    /**
     * Экшен смены пароля:
     *
     * @param int $id - record ID
     *
     * @return void
     */
    public function actionChangepassword($id)
    {
        $model = $this->loadModel($id);

        $form = new ChangePasswordForm();

        if (($data = Yii::app()->getRequest()->getPost('ChangePasswordForm')) !== null) {

            $form->setAttributes($data);

            if ($form->validate() && Yii::app()->userManager->changeUserPassword($model, $form->password)) {

                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('UserModule.user', 'Password was changed successfully')
                );

                $this->redirect(array('/user/userBackend/view', 'id' => $model->id));
            }
        }

        $this->render('changepassword', array('model' => $model, 'changePasswordForm' => $form));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return void
     */
    public function actionCreate()
    {
        Yii::import('vendor.yiiext.multimodelform.MultiModelForm');
        Yii::import('application.modules.contact.ContactModule');

        $ContactUser = new ContactUser;
        $deleteContacts = array(); // just init for later use as argument
        $validatedContacts = array();  //ensure an empty array

        $model = new User();

        if (($data = Yii::app()->getRequest()->getPost('User')) !== null) {

            $model->setAttributes($data);


            //validate detail before saving the master
            $contactFlagOK = MultiModelForm::validate($ContactUser, $validatedContacts, $deleteContacts);

/*
            if ( !empty($contactFlagOK) && empty($validatedContacts) )
            {
                Yii::app()->user->setFlash('error','No contacts submitted');
                $contactFlagOK = false;
            }
*/
            $model->setAttributes(
                array(
                    'hash' => Yii::app()->userManager->hasher->hashPassword(
                            Yii::app()->userManager->hasher->generateRandomPassword()
                        ),
                )
            );

            if ( !empty($contactFlagOK) && $model->save() )
            {
                //the value for the foreign key 'groupid'
                $masterValues = array ('user_id' => $model->id);

                if ( MultiModelForm::save($ContactUser, $validatedContacts, $deleteContacts, $masterValues) )
                {
                    Yii::app()->user->setFlash(
                        yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                        Yii::t('UserModule.user', 'New user was created!')
                    );

                    $this->redirect(
                        (array)Yii::app()->getRequest()->getPost(
                            'submit-type',
                            array('create')
                        )
                    );
                }
            }
        }
        else
        {
            $model->setAttribute('gender', User::GENDER_FEMALE);
        }

        $this->render('create', array(
            'model' => $model,
            'ContactUser' => $ContactUser,
            'validatedContacts' => $validatedContacts,
            'contactUserFormConfig' => ContactUser::getFormConfig(),
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param int $id - record ID
     *
     * @return void
     */
    public function actionUpdate($id)
    {
        Yii::import('vendor.yiiext.multimodelform.MultiModelForm');
        Yii::import('application.modules.contact.ContactModule');

        $ContactUser = new ContactUser;
        $validatedContacts = array();  //ensure an empty array
        $deleteContacts = array(); // just init for later use as argument

        $model = $this->loadModel($id);

        if (($data = Yii::app()->getRequest()->getPost('User')) !== null) {

            // $contactFlagOK = MultiModelForm::validate($ContactUser, $validatedContacts, $deleteContacts);
            // var_dump($contactFlagOK, $ContactUser, $validatedContacts, $deleteContacts);die();
            $model->setAttributes($data);

            // if ( !empty($contactFlagOK)  )
            // {
                $masterValues = array ('user_id' => $model->id);
// var_dump($validatedContacts);die();
                if ( MultiModelForm::save($ContactUser, $validatedContacts, $deleteContacts, $masterValues) && $model->save() )
                {
                    Yii::app()->user->setFlash(
                        yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                        Yii::t('UserModule.user', 'Data was updated!')
                    );

                    $this->redirect(
                        (array)Yii::app()->getRequest()->getPost(
                            'submit-type',
                            array('update', 'id' => $model->id)
                        )
                    );
                }
            // }
        }

        $this->render('update', array(
            'model' => $model,
            'ContactUser' => $ContactUser,
            'validatedContacts' => $validatedContacts,
            'contactUserFormConfig' => ContactUser::getFormConfig(),
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param int $id - record ID
     *
     * @return void
     *
     * @throws CHttpException If record not found
     */
    public function actionDelete($id)
    {
        if (Yii::app()->getRequest()->getIsPostRequest()) {

            // we only allow deletion via POST request
            if ($this->loadModel($id)->delete()) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::SUCCESS_MESSAGE,
                    Yii::t('UserModule.user', 'Record was removed!')
                );
            } else {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('UserModule.user', 'You can\'t make this changes!')
                );
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            Yii::app()->getRequest()->getParam('ajax') !== null || $this->redirect(
                (array)Yii::app()->getRequest()->getPost('returnUrl', 'index')
            );
        } else {
            throw new CHttpException(
                400,
                Yii::t('UserModule.user', 'Bad request. Please don\'t use similar requests anymore!')
            );
        }
    }

    /**
     * Manages all models.
     *
     * @return void
     */
    public function actionIndex()
    {
        $model = new User('search');

        $model->unsetAttributes(); // clear any default values

        $model->setAttributes(
            Yii::app()->getRequest()->getParam(
                'User',
                array()
            )
        );

        $this->render('index', array('model' => $model));
    }

    /**
     * Для отправки письма с активацией:
     *
     * @return void
     */
    public function actionSendactivation($id)
    {
        Yii::app()->getRequest()->getIsAjaxRequest() === true || $this->badRequest();

        if (($user = $this->loadModel($id)) === null) {
            if (Yii::app()->getRequest()->getIsAjaxRequest() === false) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('UserModule.user', 'User with #{id} was not found', array('{id}' => $id))
                );
                $this->redirect(array('index'));
            } else {
                Yii::app()->ajax->failure(
                    Yii::t('UserModule.user', 'User with #{id} was not found', array('{id}' => $id))
                );
            }
        }

        if ($user->status == User::STATUS_ACTIVE) {
            if (Yii::app()->getRequest()->getIsAjaxRequest() === false) {
                Yii::app()->user->setFlash(
                    yupe\widgets\YFlashMessages::ERROR_MESSAGE,
                    Yii::t('UserModule.user', 'User #{id} is already activated', array('{id}' => $id))
                );

                $this->redirect(array('index'));
            } else {
                Yii::app()->ajax->failure(
                    Yii::t('UserModule.user', 'User #{id} is already activated', array('{id}' => $id))
                );
            }
        }

        $tokenStorage = new TokenStorage();

        if (($token = $tokenStorage->createEmailVerifyToken($user))) {
            //@TODO
            Yii::app()->notify->send(
                $user,
                Yii::t(
                    'UserModule.user',
                    'Registration on {site}',
                    array('{site}' => Yii::app()->getModule('yupe')->siteName)
                ),
                '//user/email/needAccountActivationEmail',
                array(
                    'token' => $token
                )
            );

            Yii::app()->ajax->success(Yii::t('UserModule.user', 'Sent!'));
        }

        Yii::app()->ajax->failure();

    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     *
     * @param int $id - record ID
     *
     * @return User
     *
     * @throws CHttpException
     */
    public function loadModel($id = null)
    {
        if ($this->_model === null || $this->_model instanceof User && $this->_model->id !== $id) {

            if (($this->_model = User::model()->findbyPk($id)) === null) {
                throw new CHttpException(
                    404,
                    Yii::t('UserModule.user', 'requested page was not found!')
                );
            }
        }

        return $this->_model;
    }

    /**
     * Отправить письмо для подтверждения email:
     *
     * @param integer $id - ID пользователя
     *
     * @throws CHttpException
     *
     * @return void
     */
    public function actionVerifySend($id = null)
    {
        Yii::app()->getRequest()->getIsAjaxRequest() === true || $this->badRequest();

        if ($id === null || ($user = $this->loadModel($id)) === null) {
            throw new CHttpException(
                404,
                Yii::t('UserModule.user', 'requested page was not found!')
            );
        } elseif ($user->email_confirm) {
            return $this->badRequest();
        }

        $tokenStorage = new TokenStorage();

        if (($token = $tokenStorage->createEmailVerifyToken($user))) {
            Yii::app()->notify->send(
                $user,
                Yii::t('UserModule.user', 'Email verification'),
                '//user/email/needEmailActivationEmail',
                array(
                    'token' => $token
                )
            );

            Yii::app()->ajax->success(Yii::t('UserModule.user', 'Sent!'));
        }

        Yii::app()->ajax->failure();
    }
}
