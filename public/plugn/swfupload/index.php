
<link href="__PLROOT__/swfupload/css/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="__PLROOT__/swfupload/swfupload/swfupload.js"></script>
<script type="text/javascript" src="__PLROOT__/swfupload/js/swfupload.queue.js"></script>
<script type="text/javascript" src="__PLROOT__/swfupload/js/fileprogress.js"></script>
<script type="text/javascript" src="__PLROOT__/swfupload/js/handlers.js"></script>
<script type="text/javascript">
		var swfu;
		window.onload = function() {
			var settings = {
				flash_url : "__PLROOT__/swfupload/swfupload/swfupload.swf",
				upload_url: "__PLROOT__/swfupload/upload.php?<?php foreach($_GET as $key=>$value){ echo $key.'='.$value.'&';}?>",
				post_params: {"PHPSESSID" : "<?php echo session_id(); ?>"},
				file_size_limit : "100 MB",
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : 10,  //
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "__PLROOT__/swfupload/images/TestImageNoText_65x29.png",
				button_width: "65",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">浏览</span>',
				button_text_style: ".theFont { font-size: 16;}",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	
			};

			swfu = new SWFUpload(settings);
	     };
	</script>
<div id="content">
		<p>点击“浏览”按钮，选择您要上传的文档文件后，系统将自动上传并在完成后提示您。</p>
		<p>请勿上传包含中文文件名的文件！</p>
		<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend">快速上传</span>
	  </div>
		<div id="divStatus">0 个文件已上传</div>
			<div>
				<span id="spanButtonPlaceHolder"></span>
                <?php if($_REQUEST['url']){ ?>
                    <input type="button" value="下一步" onclick="window.location='<?php echo $_REQUEST['url'];?>';" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
                <?php } else { ?>
				<input id="btnCancel" type="button" value="取消所有上传" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
                <?php } ?>
			</div>
</div>

