
$(document).ready(function() {
    $('#warrantyForm').on('submit', function(event) {
        event.preventDefault();
    });
});

function check_warranty(serialNumber) {
    $('#warranty-status').html('Loading...');
    $('#serialNumber').attr('disabled', true);
    $('#submit').attr('disabled', true);
	$.ajax({
		url : 'treatments/check_warranty.php',
		type : 'POST',
		data : 'fromHome=1&serialNumber='+serialNumber,
		dataType : 'html',
		success : function(code_html, statut) {
            $('#warranty-status').html(code_html);
            $('#serialNumber').attr('disabled', false);
            $('#submit').attr('disabled', false);
		},
		error : function (resultat, statut, error) {
			$('#warranty-status').html('Ajax error...');
            $('#serialNumber').attr('disabled', false);
            $('#submit').attr('disabled', false);
		}
	});
}