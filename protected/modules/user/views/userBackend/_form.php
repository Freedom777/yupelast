<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id'                     => 'user-form',
        'enableAjaxValidation'   => false,
        'enableClientValidation' => true,
        'type'                   => 'vertical',
        'htmlOptions'            => array('class' => 'well'),
    )
); ?>

<div class="alert alert-info">
    <?php echo Yii::t('UserModule.user', 'Fields with'); ?>
    <span class="required">*</span>
    <?php echo Yii::t('UserModule.user', 'are required'); ?>
</div>

<?php echo $form->errorSummary($model); ?>

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
