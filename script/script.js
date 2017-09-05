$(document).ready(function() {

	//E-mail Ajax Send
	$("form").submit(function() { //Change

		var th = $(this);

		var data = new FormData();
		jQuery.each(jQuery('input[type="file"]')[0].files, function(i, file) {
			data.append('file-'+i, file);
		});

		$(this).find('input, select, textarea').each(function(e) {
			var element = $(this);

			data.append(element.prop('name'), element.val());
		});

		// var th = $(this);
		$.ajax({
			type: "POST",
			url: "mail-pm.php", //Change
			data: data,
			cache: false,
			contentType: false,
			processData: false
		}).done(function() {
			alert("Thank you!");
			setTimeout(function() {
				// Done Functions
				// th.trigger("reset");
			}, 1000);
		});
		return false;
	});

});