<?php
/**
 * @author Klimov Paul <klimov@zfort.com>
 * @link http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2014 Zfort Group
 * @license http://www.zfort.com/terms-of-use
 */

Yii::import('vendor.yiiext.plupload.Plupload');

/**
 * FileUploader is a shortcut widget for the file uploader.
 * The actual widget configuration is performed inside its view file.
 *
 * @see Plupload
 *
 * @author Klimov Paul <klimov@zfort.com>
 * @version $Id$
 * @package default
 * @since 1.0
 */
class FileUploaderWidget extends Plupload
{
    /**
     * @inheritdoc
     */
    public $view = 'template';

    /**
     * base download url for files
     * @var string
     */
    public $baseDownloadUrl  = "/admin/extra/downloadfile";

    /**
     * base delete url for files
     * @var string
     */
    public $baseDeleteUrl  = "/admin/extra/deletefile";
    /**
     * upload button label
     * @var string
     */
    public $buttonLabel = "Add Attachments";

    /**
     * upload button class
     * @var string
     */
    public $buttonClass = "btn btn-success pt pl btn";
    /**
     * @var array list of files, which should be displayed as uploaded at start.
     */
    public $files = array();

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->files = $this->normalizeFiles($this->files);
        parent::init();
    }

    /**
     * Normalizes raw files data for the existing files list composing.
     * @param array $rawFiles raw files to process.
     * @return array normalized files data
     * @throws CException on failure.
     */
    protected function normalizeFiles(array $rawFiles)
    {
        $currentRoute = Yii::app()->getController()->getRoute();

        $pos = mb_strrpos($currentRoute, '/');
        if ($pos !== false) {
            $baseRoute = mb_substr($currentRoute, 0, $pos);
        } else {
            $baseRoute = $currentRoute;
        }

        $normalizedFiles = array();
        foreach ($rawFiles as $rawFile) {
            if (is_object($rawFile)) {
                $normalizedFile = array(
                    'id' => $rawFile->id,
                    'name' => $rawFile->name,
                );
            } elseif (is_array($rawFile)) {
                $normalizedFile = $rawFile;
            } else {
                throw new CException('File should either array or object!');
            }
            if (empty($normalizedFile['downloadUrl'])) {
                $normalizedFile['downloadUrl'] = Yii::app()->createUrl($this->baseDownloadUrl, array('id' => $normalizedFile['id']));
            }
            if (empty($normalizedFile['deleteUrl'])) {
                $normalizedFile['deleteUrl'] = Yii::app()->createUrl($baseRoute . $this->baseDeleteUrl, array('id' => $normalizedFile['id']));
            }
            $normalizedFiles[] = $normalizedFile;
        }
        return $normalizedFiles;
    }
}