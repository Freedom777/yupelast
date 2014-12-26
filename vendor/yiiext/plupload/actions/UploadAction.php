<?php
/**
 * @author Klimov Paul <klimov@zfort.com>
 * @link http://www.zfort.com/
 * @copyright Copyright &copy; 2000-2014 Zfort Group
 * @license http://www.zfort.com/terms-of-use
 */
/*
use CAction;
use CFileHelper;
use CHttpException;
use CJSON;
use Yii;
*/

/**
 * This action serves as end point for the Plupload widget.
 * It handles the incoming files.
 *
 * @see Plupload
 *
 * @author Klimov Paul <klimov@zfort.com>
 * @version $Id$
 * @package zfort\widgets\plupload
 * @since 1.0
 */
class UploadAction extends CAction
{
    /**
     * @var callable PHP callback, which should be invoked when file uploading is complete.
     */
    public $completeCallback;
    /**
     * @var integer the number of seconds after which tmp file will be seen as 'garbage' and deleted.
     */
    public $tmpFileLifeTime = 1440;
    /**
     * @var boolean whether to vary temporary directory name using current session id.
     */
    public $varyBySession = true;
    /**
     * @var string temporary directory name.
     */
    protected $_tmpDirName;

    /**
     * @param string $tmpDirName temporary directory name.
     */
    public function setTmpDirName($tmpDirName)
    {
        $this->_tmpDirName = $tmpDirName;
    }

    /**
     * @return string temporary directory name.
     */
    public function getTmpDirName()
    {
        if ($this->_tmpDirName === null) {
            $this->_tmpDirName = Yii::getPathOfAlias('application.runtime') . DIRECTORY_SEPARATOR . 'plupload';
        }
        return $this->_tmpDirName;
    }

    /**
     * Runs the action.
     * Handles incoming uploaded file.
     * @throws CHttpException on failure.
     */
    public function run()
    {
        // 5 minutes execution time
        @set_time_limit(5 * 60);
        //usleep(5000);

        // Settings
        $tmpDirName = $this->getTmpDirName();
        if ($this->varyBySession) {
            $tmpDirName .= DIRECTORY_SEPARATOR . Yii::app()->session->sessionID;
        }

        // Get parameters
        $chunk = isset($_REQUEST['chunk']) ? $_REQUEST['chunk'] : 0;
        $chunks = isset($_REQUEST['chunks']) ? $_REQUEST['chunks'] : 0;
        $fileName = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';

        // Create target dir
        if (!file_exists($tmpDirName)) {
            @mkdir($tmpDirName, 0777, true);
        }
        $this->collectGarbage();

        // Clean the fileName for security reasons
        $fileName = preg_replace('/[^\w\._\s]+/', '', $fileName);
        $fullFileName = $tmpDirName . DIRECTORY_SEPARATOR . $fileName;

        $contentType = $this->getRequestContentType();
        if (strpos($contentType, 'multipart') !== false) {
            if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                // Open temp file
                $out = fopen($fullFileName, $chunk == 0 ? 'wb' : 'ab');
                if ($out) {
                    // Read binary input stream and append it to temp file
                    $in = fopen($_FILES['file']['tmp_name'], 'rb');

                    if ($in) {
                        while ($buff = fread($in, 4096)) {
                            fwrite($out, $buff);
                        }
                    } else {
                        throw new CHttpException (500, Yii::t('app', "Can't open input stream."));
                    }

                    fclose($out);
                    unlink($_FILES['file']['tmp_name']);
                } else {
                    throw new CHttpException(500, Yii::t('app', "Can't open output stream."));
                }
            } else {
                throw new CHttpException(500, Yii::t('app', "Can't move uploaded file."));
            }
        } else {
            // Open temp file
            $out = fopen($fullFileName, $chunk == 0 ? 'wb' : 'ab');
            if ($out) {
                // Read binary input stream and append it to temp file
                $in = fopen('php://input', 'rb');

                if ($in) {
                    while ($buff = fread($in, 4096)) {
                        fwrite($out, $buff);
                    }
                } else {
                    throw new CHttpException (500, Yii::t('app', "Can't open input stream."));
                }

                fclose($out);
            } else {
                throw new CHttpException (500, Yii::t('app', "Can't open output stream."));
            }
        }

        // After last chunk is received, process the file
        if (intval($chunk) + 1 >= intval($chunks)) {
            $originalName = $fileName;
            if (isset($_SERVER['HTTP_CONTENT_DISPOSITION'])) {
                $matches = array();
                preg_match('@^attachment; filename="([^"]+)"@', $_SERVER['HTTP_CONTENT_DISPOSITION'], $matches);
                if (isset($matches[1])) {
                    $originalName = $matches[1];
                }
            }

            $response = call_user_func($this->completeCallback, $fullFileName, $originalName);

            if (file_exists($fullFileName)) {
                unlink($fullFileName);
            }
        }

        // Return response
        if (empty($response)) {
            $response = array('success' => true);
        }
        $this->sendResponse($response);
    }

    /**
     * @return string request content type.
     */
    protected function getRequestContentType()
    {
        if (isset($_SERVER['HTTP_CONTENT_TYPE'])) {
            return $_SERVER['HTTP_CONTENT_TYPE'];
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            return $_SERVER['CONTENT_TYPE'];
        }
        return 'application/octet-stream';
    }

    /**
     * Removes outdated temporary files.
     * @throws CHttpException on failure.
     */
    protected function collectGarbage()
    {
        $tmpDirName = $this->getTmpDirName();
        // Remove old temp files
        if (is_dir($tmpDirName) && ($dir = opendir($tmpDirName))) {
            while (($file = readdir($dir)) !== false) {
                $filePath = $tmpDirName . DIRECTORY_SEPARATOR . $file;

                // Remove temp files if they are older than the max age
                if (preg_match('/\\.tmp$/', $file) && (filemtime($filePath) < time() - $this->tmpFileLifeTime)) {
                    if (is_dir($filePath)) {
                        CFileHelper::removeDirectory($filePath);
                    } else {
                        @unlink($filePath);
                    }
                }
            }
            closedir($dir);
        } else {
            throw new CHttpException(500, Yii::t('app', "Can't open temporary directory."));
        }
    }

    /**
     * Sends necessary headers: for no cache etc.
     */
    protected function sendHeaders()
    {
        header('Content-type: application/json; charset=UTF-8');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }

    /**
     * Sends response.
     * @param mixed $response raw response.
     * @param boolean $terminate whether to terminate script.
     */
    protected function sendResponse($response, $terminate = true)
    {
        $this->sendHeaders();
        echo CJSON::encode($response);
        if ($terminate) {
            Yii::app()->end();
        }
    }
}