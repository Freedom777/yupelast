<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id'                     => 'user-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'type'                   => 'vertical',
        'htmlOptions'            => array('class' => 'well', 'enctype'=>'multipart/form-data'),
    )
); ?>

<div class="alert alert-info">
    <?php echo Yii::t('UserModule.user', 'Fields with'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('UserModule.user', 'are required'); ?>
</div>

<?php echo $form->errorSummary($model); ?>

<?php

// var_dump(Yii::app()->params['allowedUserFileTypes']);die();

$this->widget('vendor.yiiext.plupload.FileUploaderWidget', array(
    'id' => 'filelist',
    'uploadUrl' => array('upload'),
    'files' => $model->files,
    'maxFileCount' => 3,
    'clientOptions' => array(
        'filters' => array(
            'mime_types' => array(array(
                'title' => 'Documents',
                'extensions' => implode(',', Yii::app()->params['allowedUserFileTypes']),
            )),
            'max_file_size' => '2mb',
            'prevent_duplicates' => true,
        ),
        'multipart_params' => array(
            Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken,
        ),
    ),
));

/*
$this->widget('CMultiFileUpload', array(
    'model'=>$model,
    'name'=>'image',
    'attribute'=>'image',
    'accept'=>'jpg|gif|png',
));
*/
// $this->widget('vendor.yiiext.plupload.Plupload');

$this->widget('vendor.yiiext.multimodelform.MultiModelForm',array(
    'id' => 'id_user', //the unique widget id
    'formConfig' => $contactUserFormConfig, //the form configuration array
    'model' => $ContactUser, //instance of the form model

    //if submitted (not empty) from the controller,
    //the form will be rendered with validation errors
    'validatedItems' => $validatedContacts,

    //array of member instances loaded from db
    //only needed if validatedItems are empty (not in displaying validation errors mode)
    'data' => empty($validatedContacts) ? $ContactUser->findAll(
            array('condition'=>'user_id = :user_id',
                'params'=>array(':user_id' => $model->id),
                //'order'=>'name', //see 'sortAttribute' below
            )) : null,

    'sortAttribute' => 'name', //if assigned: sortable fieldsets is enabled
    //'removeOnClick' => 'alert("test")',
    'hideCopyTemplate'=>true,
    'clearInputs'=>false,
    'tableView' => true, //sortable will not work
    //'jsAfterCloneCallback'=>'alertIds',
    'showAddItemOnError' => false, //not allow add items when in validation error mode (default = true)

    //------------ output style ----------------------
    //'tableView' => true, //sortable will not work

    //add position:relative because of embedded removeLinkWrapper with style position:absolute
    'fieldsetWrapper' => array('tag' => 'div',
        'htmlOptions' => array('class' => 'view','style'=>'position:relative;background:#EFEFEF;')
    ),

    'removeLinkWrapper' => array('tag' => 'div',
        'htmlOptions' => array('style'=>'position:absolute; top:1em; right:1em;')
    ),

));

?>


<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup(
            $model,
            'nick_name'
        ); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'first_name'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'age'); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-3">
        <?php echo $form->datePickerGroup(
            $model,
            'meeting_date',
            array(
                'widgetOptions' => array(
                    'options' => array(
                        'format'      => 'yyyy-mm-dd',
                        'weekStart'   => 1,
                        'autoclose'   => true,
                        'orientation' => 'auto right',
                        'startView'   => 2,
                    ),
                ),
                'prepend'       => '<i class="fa fa-calendar"></i>',
            )
        );
        ?>
    </div>
</div>



<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'email'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'last_name'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'first_name'); ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'middle_name'); ?>
    </div>
</div>
<?php echo $form->hiddenField($model, 'site', array('value' => '')); ?>
<!--
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->textFieldGroup($model, 'site'); ?>
    </div>
</div>
-->

<div class="row">
    <div class="col-sm-3">
        <?php echo $form->datePickerGroup(
            $model,
            'birth_date',
            array(
                'widgetOptions' => array(
                    'options' => array(
                        'format'      => 'yyyy-mm-dd',
                        'weekStart'   => 1,
                        'autoclose'   => true,
                        'orientation' => 'auto right',
                        'startView'   => 2,
                    ),
                ),
                'prepend'       => '<i class="fa fa-calendar"></i>',
            )
        );
        ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <?php echo $form->labelEx($model, 'about'); ?>
        <?php
        $this->widget(
            $this->module->getVisualEditor(),
            array(
                'model'     => $model,
                'attribute' => 'about',
            )
        ); ?>
    </div>
</div>

<br/>

<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'gender',
            array(
                'widgetOptions' => array(
                    'data' => $model->getGendersList(),
                ),
            )
        ); ?>
    </div>
</div>

<?php echo $form->hiddenField($model, 'status', array('value' => User::STATUS_NOT_ACTIVE)); ?>
<!--
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'status',
            array(
                'widgetOptions' => array(
                    'data' => $model->getStatusList(),
                ),
            )
        ); ?>
    </div>
</div>
-->

<?php echo $form->hiddenField($model, 'email_confirm', array('value' => User::EMAIL_CONFIRM_NO)); ?>
<!--
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'email_confirm',
            array(
                'widgetOptions' => array(
                    'data' => $model->getEmailConfirmStatusList(),
                ),
            )
        ); ?>
    </div>
</div>
-->

<?php echo $form->hiddenField($model, 'access_level', array('value' => User::ACCESS_LEVEL_USER)); ?>
<!--
<div class="row">
    <div class="col-sm-7">
        <?php echo $form->dropDownListGroup(
            $model,
            'access_level',
            array(
                'widgetOptions' => array(
                    'data' => $model->getAccessLevelsList(),
                ),
            )
        ); ?>
    </div>
</div>
-->

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType' => 'submit',
        'context'    => 'primary',
        'label'      => $model->isNewRecord ? Yii::t('UserModule.user', 'Create user and continue') : Yii::t(
            'UserModule.user',
            'Save user and continue'
        ),
    )
); ?>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    array(
        'buttonType'  => 'submit',
        'htmlOptions' => array('name' => 'submit-type', 'value' => 'index'),
        'label'       => $model->isNewRecord ? Yii::t('UserModule.user', 'Create user and close') : Yii::t(
            'UserModule.user',
            'Save user and close'
        ),
    )
); ?>

<?php $this->endWidget(); ?>
