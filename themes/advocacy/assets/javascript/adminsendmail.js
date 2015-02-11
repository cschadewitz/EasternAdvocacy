$("#previewDiv").hide();
$("#checkDiv").hide();


function updatePreview()
{
	$("#checkDiv").show();
	togglePreview();
	var messagePreview = document.getElementById("previewMessage");
	var message = document.getElementById("message");
	var subject = document.getElementById("subject");
	var subjectPreview = document.getElementById("previewSubject");
	messagePreview.innerHTML = tinyMCE.get('message').getContent();
	subjectPreview.innerHTML = '<b>'+subject.value+'</b>';
}

function togglePreview()
{
	if( $("#previewCheck").is(':checked') )
		$("#previewDiv").show();
	else
		$("#previewDiv").hide();
}