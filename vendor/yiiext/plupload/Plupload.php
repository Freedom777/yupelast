<?php
/**
 * @author Klimov Paul <klimov@zfort.com>
 * @link http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2014 Zfort Group
 * @license http://www.zfort.com/terms-of-use
 */

// use CClientScript;
// use CHtml;
// use CJavaScript;
// use Yii;

/**
 * Widget Plupload is wrapper for Pupload.
 * upload files using HTML5 Gears, Silverlight, Flash, BrowserPlus or normal forms,
 * providing some unique features such as upload progress, image resizing and chunked uploads.
 *
 * @see http://www.plupload.com/
 * @see http://www.plupload.com/documentation.php
 * @see http://www.plupload.com/examples/core
 *
 * @author Klimov Paul <klimov@zfort.com>
 * @version $Id$
 * @package zfort\widgets\plupload
 * @since 1.0
 */
class Plupload extends CWidget
{
    /**
     * @var mixed URL, which should handle files upload.
     * Refer to {@link CHtml::normalizeURl()} for how to specify this value.
     */
    public $uploadUrl;
    /**
     * @var array javascript component options.
     */
    public $clientOptions = array();
    /**
     * @var string method used for the Plupload initialization.
     * Possible values:
     * - 'queue' - use JQuery Queue widget
     * - 'ui' - use JQuery UI widget
     * - 'raw' - use raw Plupload API.
     */
    public $initMethod = 'queue';
    /**
     * @var boolean whether to start file upload automatically after file is selected.
     */
    public $autoStart = false;
    /**
     * @var integer maximum count of files, which are allowed to be uploaded.
     */
    public $maxFileCount;
    /**
     * @var string i18n code.
     */
    public $language;
    /**
     * @var array JavaScript callbacks for uploaded event handling in format: 'eventName' => 'js function'
     */
    public $callbacks = array();
    /**
     * @var string CSS content, which will be rendered with this widget.
     */
    public $inlineCss = '';
    /**
     * @var string name of the view, which should be rendered in order to compose uploader content.
     * At the stage when view is rendered no script is registered, so you can adjust the future widget behavior
     * via $this variable.
     */
    public $view;
    /**
     * @var string The base URL that contains all published asset files of this widget.
     */
    private $_assetsUrl;

    /**
     * @return string the base URL that contains all published asset files of this widget.
     */
    public function getAssetsUrl()
    {
        if ($this->_assetsUrl === null) {
            $this->_assetsUrl = $this->defaultAssetsUrl();
        }
        return $this->_assetsUrl;
    }

    /**
     * @param string $value the base URL that contains all published asset files of this widget.
     */
    public function setAssetsUrl($value)
    {
        $this->_assetsUrl = $value;
    }

    /**
     * Returns default {@link assetsUrl} value.
     * @return string assets URL.
     */
    protected function defaultAssetsUrl()
    {
        return Yii::app()->getAssetManager()->publish(__DIR__ . '/assets');
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->clientOptions = array_merge($this->defaultClientOptions(), $this->clientOptions);
        if (!empty($this->uploadUrl)) {
            $this->clientOptions['url'] = CHtml::normalizeUrl($this->uploadUrl);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!empty($this->view)) {
            $this->render($this->view);
        } else {
            $this->renderDefaultView();
        }
        $this->registerClientScript();
    }

    /**
     * @return array javascript component options.
     */
    protected function defaultClientOptions()
    {
        $assetsUrl = $this->getAssetsUrl();
        return array(
            //'container' => new \CJavaScriptExpression("document.getElementById('{$this->id}')"),
            'flash_swf_url' => $assetsUrl . '/plupload.flash.swf',
            'silverlight_xap_url' => $assetsUrl . '/plupload.silverlight.xap',
            'runtimes' => 'gears,flash,silverlight,browserplus,html5',
        );
    }

    /**
     * Renders default uploader representation.
     */
    protected function renderDefaultView()
    {
        echo CHtml::openTag('div', array('id' => $this->getId()));
        echo CHtml::tag('p', array(), Yii::t('plupload', "Your browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support."));
        echo CHtml::closeTag('div');
    }

    /**
     * Registers all necessary client scripts.
     */
    protected function registerClientScript()
    {
        /** @var \CClientScript $clientScript */
        $clientScript = Yii::app()->getComponent('clientScript');
        $assetsUrl = $this->getAssetsUrl();
        $clientOptions = $this->clientOptions;

        $uploadMethods = explode(',', $clientOptions['runtimes']);
        $uploadMethodScriptFileMap = array(
            'gears' => $assetsUrl . '/gears_init.js',
            'browserplus' => 'http://bp.yahooapis.com/2.4.21/browserplus-min.js',
        );
        foreach ($uploadMethods as $uploadMethod) {
            $uploadMethod = strtolower(trim($uploadMethod));
            if (isset($uploadMethodScriptFileMap[$uploadMethod])) {
                $clientScript->registerScriptFile($uploadMethodScriptFileMap[$uploadMethod]);
            }
        }

        $clientScript->registerScriptFile($assetsUrl . '/plupload.full.min.js');

        if (!empty($this->language)) {
            $clientScript->registerScriptFile($assetsUrl . '/i18n/' . $this->language . '.js');
        }

        switch ($this->initMethod) {
            case 'queue':
                $uploaderInitJs = $this->composeInitJsAsQueue();
                break;
            case 'ui':
                $uploaderInitJs = $this->composeInitJsAsUi();
                break;
            case 'raw':
                $uploaderInitJs = $this->composeInitJsAsRaw();
                break;
            default:
                throw new \CException("Unknown init method '{$this->initMethod}'.");
        }

        $uploaderInitJs = "function initPlupload_{$this->id}() {{$uploaderInitJs}}";

        $clientScriptId = __CLASS__ . '#' . $this->getId();
        $clientScript->registerScript($clientScriptId, $uploaderInitJs, CClientScript::POS_END);
        $clientScript->registerScript($clientScriptId, "initPlupload_{$this->id}();", CClientScript::POS_READY);
        if (!empty($this->inlineCss)) {
            $clientScript->registerCss($clientScriptId, $this->inlineCss);
        }
    }

    /**
     * Composes uploader event handlers binding code.
     * @return string java script code.
     */
    protected function composeUploaderBindingJs()
    {
        $uploaderBindingJsParts = array();

        $callbacks = $this->callbacks;
        if ($this->autoStart || $this->maxFileCount > 0) {
            $function = "function(up, files) {";
            if ($this->maxFileCount > 0) {
                $function .= "if (up.files.length > {$this->maxFileCount}) up.splice({$this->maxFileCount}, up.files.length-{$this->maxFileCount}); ";
            }
            if ($this->autoStart > 0) {
                $function .= "if(up.files.length > 0) setTimeout(function() {uploader.start();}, 250); ";
                $this->inlineCss .= ".plupload_start { display:none; }\n";
            }

            if (!empty($callbacks['FilesAdded'])) {
                $existingCallback = $callbacks['FilesAdded'];
                $function .= "var customHandler = {$existingCallback}; customHandler(up, files);";
            }

            $function .= '}';
            $callbacks['FilesAdded'] = $function;
        }

        foreach ($callbacks as $name => $function) {
            $uploaderBindingJsParts[] = "uploader.bind('{$name}', {$function});";
        }

        return implode("\n", $uploaderBindingJsParts);
    }

    /**
     * Composes uploader init java script via JQuery Queue widget.
     * @return string java script code.
     */
    protected function composeInitJsAsQueue()
    {
        /** @var \CClientScript $clientScript */
        $clientScript = Yii::app()->getComponent('clientScript');
        $assetsUrl = $this->getAssetsUrl();

        $clientScript->registerCoreScript('jquery');
        $clientScript->registerScriptFile($assetsUrl . '/jquery.plupload.queue.min.js');
        $clientScript->registerCssFile($assetsUrl . '/css/jquery.plupload.queue.css');

        $clientOptionsEncoded = CJavaScript::encode($this->clientOptions);

        $uploaderInitJs = "jQuery('#{$this->id}').pluploadQueue({$clientOptionsEncoded}); var uploader = $('#{$this->id}').pluploadQueue(); ";

        $bindingJs = $this->composeUploaderBindingJs();

        if (!empty($bindingJs)) {
            $uploaderInitJs .= "\n" . $bindingJs . "\n";
        }

        return $uploaderInitJs;
    }

    /**
     * Composes uploader init java script via JQuery UI widget.
     * @return string java script code.
     * @throws \CException on failure.
     */
    protected function composeInitJsAsUi()
    {
        /** @var \CClientScript $clientScript */
        $clientScript = Yii::app()->getComponent('clientScript');
        $assetsUrl = $this->getAssetsUrl();

        $clientScript->registerCoreScript('jquery');
        $clientScript->registerCoreScript('jquery.ui');
        $clientScript->registerScriptFile($assetsUrl . '/jquery.ui.plupload.min.js');
        $clientScript->registerCssFile($assetsUrl . '/css/jquery.ui.plupload.css');

        $clientOptionsEncoded = CJavaScript::encode($this->clientOptions);

        $uploaderInitJs = "jQuery('#{$this->id}').plupload({$clientOptionsEncoded});";

        $bindingJs = $this->composeUploaderBindingJs();
        if (!empty($bindingJs)) {
            throw new \CException('Uploader binding has no effect for "UI" mode.');
        }

        return $uploaderInitJs;
    }

    /**
     * Composes uploader init java script as native PUploader API.
     * @return string java script code.
     */
    protected function composeInitJsAsRaw()
    {
        $clientOptionsEncoded = CJavaScript::encode($this->clientOptions);
        $uploaderInitJs = "var uploader = new plupload.Uploader({$clientOptionsEncoded}); ";

        $bindingJs = $this->composeUploaderBindingJs();
        if (!empty($bindingJs)) {
            $uploaderInitJs .= "\n" . $bindingJs . "\n";
        }

        $uploaderInitJs .= "uploader.init();";
        return $uploaderInitJs;
    }
}