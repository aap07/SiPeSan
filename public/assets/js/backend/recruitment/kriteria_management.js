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
		$("#tbl-kriteria").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
			"ajax" : {
				"url" : base_url + "recruitment/listkriteria",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_kriteria").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_kriteria").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_kriteria").val(response.csrf_token_name);
                    return response.data;
                }
			},
			"columnDefs" : [{
				"targets" : [0],
				"orderable" : false
			},
            {
                "targets" : [2],
                "className" : 'text-center'
            }]
		});
	}

    $(document).on("click","#tmbh-kriteria",function(){
        savemethod = "tmbhKriteria";
		$.ajax({
            url: base_url + "recruitment/tambahkriteria",
            dataType: 'json',
            data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Tambah Kriteria");
                $('.modal').modal("toggle");
            }
        });
	});

    $(document).on("click","#edit-kriteria",function(){
        savemethod = "edtKriteria";
        const kriteriaId = $(this).data('idkriteria');
		$.ajax({
            url: base_url + "recruitment/tambahkriteria",
            dataType: 'json',
            data: {
                kriteriaId: kriteriaId,
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Edit Kriteria");
                $('.modal').modal("toggle");
                $('#id').val(res.kriteria.id_kriteria);
                $('#kriteria').val(res.kriteria.nm_kriteria);
                if (res.kriteria.is_aktif == 1) {
					$("#check-kriteriaaktif").prop("checked", true)
				} else {
					$("#check-kriteriaaktif").prop("checked", false)
				}
            }
        });
	});
    
    $(document).on("click","#delete-kriteria",function(){
        const kriteriaId = $(this).data('idkriteria');
        deletemethod = "deleteKriteria";
		$.ajax({
			url: base_url + "recruitment/deletekriteria",
			dataType: 'json',
			data: {
                kriteriaId: kriteriaId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Delete Kriteria");
                $('.modal').modal("toggle");
				$('#id').val(res.kriteria.id_kriteria);
                $('#dt-delete').html(res.kriteria.nm_kriteria);
				$('#dt-text').html(res.text);
			}
		});
	});
    $(document).on("click","#restore-kriteria",function(){
        const kriteriaId = $(this).data('idkriteria');
        deletemethod = "restoreKriteria";
		$.ajax({
			url: base_url + "recruitment/deletekriteria",
			dataType: 'json',
			data: {
                kriteriaId: kriteriaId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Restore Kriteria");
                $('.modal').modal("toggle");
				$('#id').val(res.kriteria.id_kriteria);
                $('#dt-delete').html(res.kriteria.nm_kriteria);
				$('#dt-text').html(res.text);
                $('#delete').html("Restore");
			}
		});
	});
    $(document).on("click","#remove-kriteria",function(){
        const kriteriaId = $(this).data('idkriteria');
        deletemethod = "removeKriteria";
		$.ajax({
			url: base_url + "recruitment/deletekriteria",
			dataType: 'json',
			data: {
                kriteriaId: kriteriaId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Remove Kriteria");
                $('.modal').modal("toggle");
				$('#id').val(res.kriteria.id_kriteria);
                $('#dt-delete').html(res.kriteria.nm_kriteria);
				$('#dt-text').html(res.text);
                $('#delete').html("Delete Permanent");
			}
		});
	});

    $(document).on("click","#delete",function(e){
        e.preventDefault();
        console.log("Delete");
		const kriteriaId = $('#id').val();
        $.ajax({
            url: base_url + "recruitment/deletekriteria",
            type:'POST',
			data: {
				kriteriaId: kriteriaId,
				deletemethod: deletemethod,
                csrf_token_name: $(".csrf_del").val(),
			},
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_kriteria').val(res.token);
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

    $(document).on("submit","#fm-kriteria",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "recruitment/tambahkriteria",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_kriteria').val(res.token);
                    dataTabel();
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                }else{
                    $('.csrf_kriteria').val(res.token);
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
        $("#fm-kriteria input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });

})(jQuery);