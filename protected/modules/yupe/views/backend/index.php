<div class="page-header">
    <h1><?php echo Yii::t(
            'YupeModule.yupe',
            'Control panel "{app}"',
            array('{app}' => CHtml::encode(Yii::t('YupeModule.yupe', Yii::app()->name)))
        ); ?>
    </h1>
</div>

<?php $box = $this->beginWidget(
    'bootstrap.widgets.TbPanel',
    array(
        'title'      => Yii::t('YupeModule.yupe', 'Notify'),
        'headerIcon' => 'fa fa-fw fa-exclamation-circle'
    )
);?>
<?php foreach ($modules as $module): { ?>
    <?php if ($module instanceof yupe\components\WebModule === false): { ?>
        <?php continue; ?>
    <?php } endif; ?>
    <?php if ($module->getIsActive()): { ?>
        <?php $messages = $module->checkSelf(); ?>
        <?php if (is_array($messages)): { ?>
            <?php foreach ($messages as $key => $value): { ?>
                <?php if (!is_array($value)) {
                    continue;
                } ?>
                <div class="panel-group" id="accordion<?php echo $module->getId(); ?>">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="panel-title"
                               data-toggle="collapse"
                               data-parent="#accordion<?php echo $module->getId(); ?>"
                               href="#collapse<?php echo $module->getId(); ?>"
                                >
                                <?php echo Yii::t(
                                    'YupeModule.yupe',
                                    'Module {icon} "{module}", messages: {count}',
                                    array(
                                        '{icon}'   => $module->icon ? "<i class='" . $module->icon . "'></i> " : "",
                                        '{module}' => $module->getName(),
                                        '{count}'  => '<span class="badge alert-danger">' . count($value) . '</span>',
                                    )
                                ); ?>
                            </a>
                        </div>
                        <div id="collapse<?php echo $module->getId(); ?>" class="panel-collapse collapse">
                            <div class="panel-body">
                                <?php foreach ($value as $error): { ?>
                                    <div class="alert alert-<?php echo $error['type']; ?>">
                                        <h4 class="alert-heading">
                                            <?php echo Yii::t(
                                                'YupeModule.yupe',
                                                'Module "{module} ({id})"',
                                                array(
                                                    '{module}' => $module->getName(),
                                                    '{id}'     => $module->getId(),
                                                )
                                            ); ?>
                                        </h4>
                                        <?php echo $error['message']; ?>
                                    </div>
                                <?php } endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } endforeach; ?>
        <?php } endif; ?>
    <?php } endif; ?>
<?php } endforeach; ?>

<?php $this->endWidget(); ?>



<?php foreach ($modules as $module): { ?>
    <?php if ($module instanceof yupe\components\WebModule === false): { ?>
        <?php continue; ?>
    <?php } endif; ?>
    <?php if ($module->getIsActive()): { ?>
        <?php foreach ($module->getPanelWidgets() as $widget => $params): { ?>
            <?php $this->widget($widget, $params); ?>
        <?php } endforeach; ?>
    <?php } endif; ?>

<?php } endforeach; ?>


<legend><?php echo Yii::t('YupeModule.yupe', 'Fast access to modules'); ?> </legend>

<?php
$this->widget(
    'yupe\widgets\YShortCuts',
    array(
        'modules' => $modules
    )
); ?>
<?php $this->menu = $modulesNavigation; ?>
