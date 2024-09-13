!(function ($) {
    "use strict";
	var savemethod;
    var deletemethod;
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
		$("#tbl-posisi").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
			"ajax" : {
				"url" : base_url + "recruitment/listJob",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_posisi").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_posisi").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_posisi").val(response.csrf_token_name);
                    return response.data;
                }
			},
			"columnDefs" : [{
				"targets" : [0],
				"orderable" : false
			},
            {
                "targets" : [4],
                "className" : 'text-center'
            }]
		});
	}

    $(document).on("click","#tmbh-posisi",function(){
        savemethod = "tmbhPosisi";
		$.ajax({
            url: base_url + "recruitment/tambahposisi",
            dataType: 'json',
            data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Tambah Posisi");
                $('.modal').modal("toggle");
            }
        });
	});

    $(document).on("click","#edit-posisi",function(){
        savemethod = "edtPosisi";
        const posisiId = $(this).data('idposisi');
		$.ajax({
            url: base_url + "recruitment/tambahposisi",
            dataType: 'json',
            data: {
                posisiId: posisiId,
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Edit Posisi");
                $('.modal').modal("toggle");
                $('#id').val(res.posisi.id_job_list);
                $('#posisi').val(res.posisi.nm_posisi);
                $('#min').val(res.posisi.nil_min);
                $('#max').val(res.posisi.nil_max);
                if (res.posisi.is_aktif == 1) {
					$("#check-jobList").prop("checked", true)
				} else {
					$("#check-jobList").prop("checked", false)
				}
            }
        });
	});
    
    $(document).on("click","#delete-posisi",function(){
        const posisiId = $(this).data('idposisi');
        deletemethod = "deletePosisi";
		$.ajax({
			url: base_url + "recruitment/deleteposisi",
			dataType: 'json',
			data: {
                posisiId: posisiId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Delete Posisi");
                $('.modal').modal("toggle");
				$('#id').val(res.posisi.id_job_list);
                $('#dt-delete').html(res.posisi.nm_posisi);
				$('#dt-text').html(res.text);
			}
		});
	});

    $(document).on("click","#restore-posisi",function(){
        const posisiId = $(this).data('idposisi');
        deletemethod = "restorePosisi";
		$.ajax({
			url: base_url + "recruitment/deleteposisi",
			dataType: 'json',
			data: {
                posisiId: posisiId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Restore Posisi");
                $('.modal').modal("toggle");
				$('#id').val(res.posisi.id_job_list);
                $('#dt-delete').html(res.posisi.nm_posisi);
				$('#dt-text').html(res.text);
                $('#delete').html("Restore");
			}
		});
	});

    $(document).on("click","#remove-posisi",function(){
        const posisiId = $(this).data('idposisi');
        deletemethod = "removePosisi";
		$.ajax({
			url: base_url + "recruitment/deleteposisi",
			dataType: 'json',
			data: {
                posisiId: posisiId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Remove Posisi");
                $('.modal').modal("toggle");
				$('#id').val(res.posisi.id_job_list);
                $('#dt-delete').html(res.posisi.nm_posisi);
				$('#dt-text').html(res.text);
                $('#delete').html("Delete Permanent");
			}
		});
	});

    $(document).on("submit","#fm-posisi",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "recruitment/tambahposisi",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_posisi').val(res.token);
                    dataTabel();
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                }else{
                    $('.csrf_posisi').val(res.token);
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
        $("#fm-posisi input").on("keyup",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-posisi input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });

    $(document).on("click","#delete",function(e){
        e.preventDefault();
		const posisiId = $('#id').val();
        $.ajax({
            url: base_url + "recruitment/deleteposisi",
            type:'POST',
			data: {
				posisiId: posisiId,
				deletemethod: deletemethod,
                csrf_token_name: $(".csrf_del").val(),
			},
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_posisi').val(res.token);
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
})(jQuery);