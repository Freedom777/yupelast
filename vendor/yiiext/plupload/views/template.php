<?php
/**
 * @var FileUploader $this
 * @var CClientScript $clientScript
 */

$clientScript = Yii::app()->getClientScript();
$clientScript->registerCoreScript('jquery');

$this->initMethod = 'raw';

$browseButtonId = $this->id . '-browse';
$fileListId = $this->id . '-file-list';
$errorLogId = $this->id . '-error-log';

$this->clientOptions = array_merge(
    $this->clientOptions,
    array(
        'browse_button' => $browseButtonId,
        //'container' => 'container',
    )
);

if (!empty($this->maxFileCount)) {
    $maxFileCount = $this->maxFileCount;
    $this->maxFileCount = null;
} else {
    $maxFileCount = 100;
}

$this->callbacks = array_merge(
    $this->callbacks,
    array(
        'PostInit' => "function() {
            $('#{$this->id} .loading').hide();
            $('#{$browseButtonId}').closest('div').show();
            $('#{$fileListId}').show();
        }",
        'FilesAdded' => "function(up, files) {
            $('#{$errorLogId}').html('');
            var filesCount = $('#{$fileListId}').find('div').size();
            if (filesCount >= {$maxFileCount}) {
                $('#{$errorLogId}').append('<p>' + 'Maximum {$maxFileCount} files allowed.' + '</p>');
            } else {
                plupload.each(files, function(file) {
                    var fileRowHtml = '<div id=\"' + file.id + '\">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>';
                    $('#{$fileListId}').append(fileRowHtml);
                });
                setTimeout(function() {uploader.start();}, 250);
            }
        }",
        'FileUploaded' => "function(up, file, info) {
            var response = JSON.parse(info.response);
            $('#' + file.id).html('<a href=\"' + response.downloadUrl + '\">' + file.name + '</a>&nbsp;&nbsp;<a href=\"' + response.deleteUrl + '\" class=\"delete\">[X]</a>');
        }",
        'UploadProgress' => "function(up, file) {
            $('#' + file.id).find('b').html('<span>' + file.percent + '%</span>');
        }",
        'Error' => "function(up, err) {
            $('#{$errorLogId}').append('<p>' + err.message + '</p>');
        }",
    )
);

$deleteJs = <<<JS
$(document).on('click', '#{$fileListId} a.delete', function(event) {
    event.preventDefault();
    if (confirm('Are you sure?')) {
        var link = $(this);
        var linkContainer = link.closest('div');
        linkContainer.hide();

        $.ajax({
            url: link.attr('href'),
            success: function (data) {
                linkContainer.remove();
            },
            error: function (XHR, textStatus, errorThrown) {
                alert('Error ' + XHR.status + ': ' + XHR.responseText);
            }
        });
    }
});
JS;
$clientScript->registerScript(get_class($this) . '#' . $this->id . '#delete', $deleteJs);

?>
<div id="<?php echo $this->id; ?>" class="file-uploader">
    <div class="loading">
        <a class="btn btn-success pt pl btn disabled" style="position: relative; z-index: 1;">Please wait...</a>
    </div>
    <div style="display: none;">
        <a id="<?php echo $browseButtonId; ?>" href="#upload" class="<?php echo $this->buttonClass; ?>"><?php echo $this->buttonLabel; ?></a>
    </div>
    <div id="<?php echo $fileListId; ?>" style="display: none;">
        <?php foreach ($this->files as $file): ?>
            <div><a href="<?php echo $file['downloadUrl']; ?>"><?php echo $file['name']; ?></a>&nbsp;&nbsp;<a href="<?php echo $file['deleteUrl']; ?>" class="delete">[X]</a></div>
        <?php endforeach; ?>
    </div>
    <div id="<?php echo $errorLogId; ?>" class="error"></div>
</div>