!(function ($) {
    "use strict";
    var savemethod;
    var deletemethod;
    var did;
    var opsi;
	$(document).ready(function() {
        dataTabel();
	});
	const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
    });
	function dataTabel(){
		$("#tbl-subkriteria").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
			"ajax" : {
				"url" : base_url + "recruitment/listsubkriteria",
				"type" : "POST",
                // "data" : {"csrf_token_name" : $(".csrf_tbl_subkriteria").val()},
                // "data" : {"id_kriteria" : $(".id_kriteria").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_subkriteria").val();
                    data.id_kriteria = $(".id_kriteria").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_subkriteria").val(response.csrf_token_name);
                    return response.data;
                }
			},
			"columnDefs" : [{
				"targets" : [0],
				"orderable" : false
			},
            {
                "targets" : [2,3],
                "className" : 'text-center'
            }]
		});
	}

    $(document).on("change", ".tipe", function(){
        did = $(this).data('id');
        $(".opsi").hide();
        $("#div_" + did).show()
    })

    $(document).on("click","#tmbh-subkriteria",function(){
		savemethod = "tmbhSubkriteria";
		$.ajax({
            url: base_url + "recruitment/tambahsubkriteria",
            dataType: 'json',
			data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $('.modal').modal("toggle");
				$('#judul').html('Tambah Subkriteria');
                // var html = '<option value="">Select Kriteria</option>';
				// var i;
				// for(i=0; i<res.kriteria.length; i++){
				// 	html += '<option value='+res.kriteria[i].id_kriteria + '>' + res.kriteria[i].nm_kriteria + '</option>';
				// }
				// $('#kriteria').html(html);
            }
        });
	});
    
    $(document).on("click","#edit-subkriteria",function(){
		savemethod = "edtSubkriteria";
		const subkriteriaId = $(this).data('idsubkriteria');
		$.ajax({
            url: base_url + "recruitment/tambahsubkriteria",
            dataType: 'json',
			data: {
				subkriteriaId: subkriteriaId,
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $('.modal').modal("toggle");
				$('#judul').html('Edit Subkriteria');
				$('#id').val(res.subkriteria.id_subkriteria);
				$('#teks_subkriteria').val(res.subkriteria.nm_subkriteria);
				$('#min_subkriteria').val(res.subkriteria.min);
				$('#max_subkriteria').val(res.subkriteria.max);
                if(res.subkriteria.tipe == "teks"){
                    $("#teksRadio").prop("checked", true)
                }else if(res.subkriteria.tipe == "nilai"){
                    $("#nilaiRadio").prop("checked", true)
                }
                $("#div_" + res.subkriteria.tipe).show()
                // var html = '<option value="">Select Kriteria</option>';
				// var i;
				// for(i=0; i<res.kriteria.length; i++){
				// 	html += '<option value='+res.kriteria[i].id_kriteria + '>' + res.kriteria[i].nm_kriteria + '</option>';
				// }
				// $('#kriteria').html(html);
				// $('#kriteria').val(res.subkriteria.id_kriteria);
            }
        });
	});

    $(document).on("click","#delete",function(e){
        e.preventDefault();
		const subkriteriaId = $('#id').val();
        $.ajax({
            url: base_url + "recruitment/deletesubkriteria",
            type:'POST',
			data: {
				subkriteriaId: subkriteriaId,
				deletemethod: deletemethod,
                csrf_token_name: $(".csrf_del").val(),
			},
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_subkriteria').val(res.token);
					dataTabel();
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                }
            }
        });
	});

	$(document).on("click","#delete-subkriteria",function(){
        const subkriteriaId = $(this).data('idsubkriteria');
        deletemethod = "deleteSubkriteria";
		$.ajax({
			url: base_url + "recruitment/deletesubkriteria",
			dataType: 'json',
			data: {
                subkriteriaId: subkriteriaId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Delete Subkriteria");
                $('.modal').modal("toggle");
				$('#id').val(res.subkriteria.id_subkriteria);
                $('#dt-delete').html(res.subkriteria.nm_kriteria);
				$('#dt-text').html(res.text);
			}
		});
	});
    $(document).on("click","#restore-subkriteria",function(){
        const subkriteriaId = $(this).data('idsubkriteria');
        deletemethod = "restoreSubkriteria";
		$.ajax({
			url: base_url + "recruitment/deletesubkriteria",
			dataType: 'json',
			data: {
                subkriteriaId: subkriteriaId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Restore Subkriteria");
                $('.modal').modal("toggle");
				$('#id').val(res.subkriteria.id_subkriteria);
                $('#dt-delete').html(res.subkriteria.nm_kriteria);
				$('#dt-text').html(res.text);
                $('#delete').html("Restore");
			}
		});
	});
    $(document).on("click","#remove-subkriteria",function(){
        const subkriteriaId = $(this).data('idsubkriteria');
        deletemethod = "removeSubkriteria";
		$.ajax({
			url: base_url + "recruitment/deletesubkriteria",
			dataType: 'json',
			data: {
                subkriteriaId: subkriteriaId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Remove Subkriteria");
                $('.modal').modal("toggle");
				$('#id').val(res.subkriteria.id_subkriteria);
                $('#dt-delete').html(res.subkriteria.nm_kriteria);
				$('#dt-text').html(res.text);
                $('#delete').html("Delete Permanent");
			}
		});
	});
    
    $(document).on("submit","#fm-subkriteria",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "recruitment/tambahsubkriteria",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod + '&kriteriaId=' + kriteriaId,
            dataType: 'json',
            success: function(res) {
                if(res.status){
					$('.csrf_tbl_subkriteria').val(res.token);
					dataTabel();
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                }else{
					$('.csrf_subkriteria').val(res.token);
                    $.each(res.errors,function(key,value){
                        $('[name = "' + key + '"]').addClass('is-invalid')
                        $('[name = "' + key + '"]').next().text(value)
                        if(value == ""){
                            $('[name = "' + key + '"]').removeClass('is-invalid')
                            $('[name = "' + key + '"]').addClass('is-valid')
                        }
                    });
                }
            }
        });
        $("#fm-subkriteria input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-subkriteria select").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });

})(jQuery);