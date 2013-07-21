$(document).ready(function() {

	// overwrite contains function
	$.expr[":"].contains = $.expr.createPseudo(function(arg) {
    	return function( elem ) {
        	return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    	};
	});

	// show only facebook friends matching input
	$("#facebook-input").keyup(function() {
		input_content = $(this).val();
		if (input_content === ''){
			$('#facebook-table .name').closest("tr").hide();
		}else{
			$('#facebook-table .name').closest("tr").show().find(".name").not(':contains(' + input_content + ')').closest("tr").hide();
		}
	});
});
