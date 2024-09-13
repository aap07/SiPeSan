!(function ($) {
    "use strict";
	$(document).ready(function() {
		dataTabel();
	});

    function dataTabel(){
		$("#tbl-results").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
			"ajax" : {
				"url" : base_url + "recruitment/listResult",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_result").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_result").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_result").val(response.csrf_token_name);
                    return response.data;
                }
			},
			"columnDefs" : [{
				// "targets" : [0],
				// "orderable" : false
			},
            {
                "targets" : [6,7],
                "className" : 'text-center'
            }]
		});
	}
})(jQuery);