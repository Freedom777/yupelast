<?php
/**
 * Отображение для create:
 *
 * @category YupeView
 * @package  yupe
 * @author   Yupe Team <team@yupe.ru>
 * @license  https://github.com/yupe/yupe/blob/master/LICENSE BSD
 * @link     http://yupe.ru
 **/
$this->breadcrumbs = array(
    Yii::t('MailModule.mail', 'Mail events') => array('index'),
    Yii::t('MailModule.mail', 'Create'),
);

$this->menu = array(
    array('label' => Yii::t('MailModule.mail', 'Mail events')),
    array(
        'icon'  => 'fa fa-fw fa-list-alt',
        'label' => Yii::t('MailModule.mail', 'Messages list'),
        'url'   => array('/mail/eventBackend/index')
    ),
    array(
        'icon'  => 'fa fa-fw fa-plus-square',
        'label' => Yii::t('MailModule.mail', 'Create event'),
        'url'   => array('/mail/eventBackend/create')
    ),
);
?>
<div class="page-header">
    <h1><?php echo Yii::t('MailModule.mail', 'Mail events'); ?>
        <small><?php echo Yii::t('MailModule.mail', 'adding'); ?></small>
    </h1>
</div>
<?php echo $this->renderPartial('_form', array('model' => $model)); ?>