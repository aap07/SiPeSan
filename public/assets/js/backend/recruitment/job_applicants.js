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
		$("#tbl-applicants").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
			"ajax" : {
				"url" : base_url + "recruitment/listapplicants",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_applicants").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_applicants").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_applicants").val(response.csrf_token_name);
                    return response.data;
                }
			},
			"columnDefs" : [{
				"targets" : [0],
				"orderable" : false
			},
            {
                "targets" : [2,3,4,5],
                "className" : 'text-center'
            }]
		});
	}

    $(document).on("click","#detail-applicants",function(){
        const applicantsId = $(this).data('idapplicants');
		$.ajax({
            url: base_url + "recruitment/detailapplicants",
            dataType: 'json',
            data: {
                applicantsId: applicantsId,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Detail Applicants");
                $('.modal').modal("toggle");
                $('#nama').html(res.applicants.nm_applicants);
                $('#pendidikan').html(res.applicants.pend_applicants);
                $('#jurusan').html(res.applicants.jurusan_applicants);
                $('#nilai').html(res.applicants.nilai_ijazah);
                $('#posisi-terakhir').html(res.applicants.posisi_terakhir);
                $('#pengalaman').html(res.applicants.pengalaman);
            }
        });
	});

    $(document).on("click","#tmbh-applicants",function(){
        savemethod = "tmbhApplicants";
		$.ajax({
            url: base_url + "recruitment/tambahapplicants",
            dataType: 'json',
            data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Tambah Applicants");
                $('.modal').modal("toggle");
            }
        });
	});

    $(document).on("click","#edit-applicants",function(){
        savemethod = "edtApplicants";
        const applicantsId = $(this).data('idapplicants');
		$.ajax({
            url: base_url + "recruitment/tambahapplicants",
            dataType: 'json',
            data: {
                applicantsId: applicantsId,
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Edit Menu");
                $('.modal').modal("toggle");
                $('#id').val(res.applicants.id_applicants);
                $('#name').val(res.applicants.nm_applicants);
                $('#pendidikan').val(res.applicants.pend_applicants);
                $('#jurusan').val(res.applicants.jurusan_applicants);
                $('#nilai').val(res.applicants.nilai_ijazah);
                $('#posisi').val(res.applicants.posisi_terakhir);
                $('#pengalaman').val(res.applicants.pengalaman);
            }
        });
	});

    $(document).on("submit","#fm-tmbh-applicants",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "recruitment/tambahapplicants",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: "Applicants Berhasil di Tambah",
                    });
                    $('.csrf_tbl_applicants').val(res.token);
                    dataTabel();
                }else{
                    $('.csrf_tmbh_applicants').val(res.token);
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
        $("#ffm-tmbh-applicants input").on("keyup",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-tmbh-applicants input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });

    $(document).on("click","#delete-applicants",function(){
        const applicantsId = $(this).data('idapplicants');
        deletemethod = "deleteApplicants";
		$.ajax({
			url: base_url + "recruitment/deleteapplicants",
			dataType: 'json',
			data: {
                applicantsId: applicantsId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Delete Applicants");
                $('.modal').modal("toggle");
				$('#id').val(res.applicants.id_applicants);
                $('#dt-delete').html(res.applicants.nm_applicants);
				$('#dt-text').html(res.text);
			}
		});
	});

    $(document).on("click","#restore-applicants",function(){
        const applicantsId = $(this).data('idapplicants');
        deletemethod = "restoreApplicants";
		$.ajax({
			url: base_url + "recruitment/deleteapplicants",
			dataType: 'json',
			data: {
                applicantsId: applicantsId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Restore Applicants");
                $('.modal').modal("toggle");
				$('#id').val(res.applicants.id_applicants);
                $('#dt-delete').html(res.applicants.nm_applicants);
				$('#dt-text').html(res.text);
                $('#delete').html("Restore");
			}
		});
	});

    $(document).on("click","#remove-applicants",function(){
        const applicantsId = $(this).data('idapplicants');
        deletemethod = "removeApplicants";
		$.ajax({
			url: base_url + "recruitment/deleteapplicants",
			dataType: 'json',
			data: {
                applicantsId: applicantsId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Remove Applicants");
                $('.modal').modal("toggle");
				$('#id').val(res.applicants.id_applicants);
                $('#dt-delete').html(res.applicants.nm_applicants);
				$('#dt-text').html(res.text);
                $('#delete').html("Delete Permanent");
			}
		});
	});

    $(document).on("click","#delete",function(e){
        e.preventDefault();
		const applicantsId = $('#id').val();
        $.ajax({
            url: base_url + "recruitment/deleteapplicants",
            type:'POST',
			data: {
				applicantsId: applicantsId,
				deletemethod: deletemethod,
                csrf_token_name: $(".csrf_del").val(),
			},
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_applicants').val(res.token);
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