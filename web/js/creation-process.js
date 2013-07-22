$(document).ready(function() {

	// overwrite contains function
	$.expr[":"].contains = $.expr.createPseudo(function(arg) {
    	return function( elem ) {
        	return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    	};
	});

	// show only facebook friends matching input
	$("#friend-input").keyup(function() {
		input_content = $(this).val();
		if (input_content === ''){
			$('#friend-table .name').closest("tr").show();
		}else{
			$('#friend-table .name').closest("tr").show().find(".name").not(':contains(' + input_content + ')').closest("tr").hide();
		}
	});
	// Scroll in friend table
$("#friend-table").slimScroll({
    height: '160px'
});
});


