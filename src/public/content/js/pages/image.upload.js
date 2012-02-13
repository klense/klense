$(function() {
	$("#uploader").pluploadQueue({
		// General settings
		runtimes : 'html5,html4',
		url : 'upload_b',
		max_file_size : '7mb',
		chunk_size : '350kb',
		unique_names : false,
		
		// Resize images on clientside if we can
		//resize : {width : 320, height : 240, quality : 90},
		
		// Specify what files to browse for
		filters : [
		{title : "Image files", extensions : "jpg,jpeg,png"}
		],
		
		// Flash settings
		//flash_swf_url : '/plupload/js/plupload.flash.swf',
		
		// Silverlight settings
		//silverlight_xap_url : '/plupload/js/plupload.silverlight.xap'
		});
	
	// Client side form validation
	$('form').submit(function(e) {
		var uploader = $('#uploader').pluploadQueue();
		
		// Files in queue upload them first
		if (uploader.files.length > 0) {
			// When all files are uploaded submit form
			uploader.bind('StateChanged', function() {
				if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
					$('form')[0].submit();
				}
			});
			
			uploader.start();
		} else {
			alert('You must queue at least one file.');
		}
		
		return false;
	});
});