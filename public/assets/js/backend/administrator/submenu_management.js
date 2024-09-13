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
		$("#tbl-submenu").DataTable({
            // "searching": false,
            // "paging": false,
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
			"ajax" : {
				"url" : base_url + "administrator/listsubmenu",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_submenu").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_submenu").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_submenu").val(response.csrf_token_name);
                    return response.data;
                }
			},
			"columnDefs" : [{
				"targets" : [0],
				"orderable" : false
			},
            {
                "targets" : [4,5,6],
                "className" : 'text-center'
            }]
		});
	}
    $(document).on("click","#tmbh-submenu",function(){
		savemethod = "tmbhSubmenu";
		$.ajax({
            url: base_url + "administrator/tambahsubmenu",
            dataType: 'json',
			data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $('.modal').modal("toggle");
				$('#judul').html('Tambah Submenu');
                var html = '<option value="">Select Menu</option>';
				var i;
				for(i=0; i<res.menu.length; i++){
					html += '<option value='+res.menu[i].id_menu + '>' + res.menu[i].nm_menu + '</option>';
				}
				$('#menu').html(html);
            }
        });
	});
    $(document).on("click","#edit-submenu",function(){
		savemethod = "edtSubmenu";
		const submenuId = $(this).data('idsub');
		$.ajax({
            url: base_url + "administrator/tambahsubmenu",
            dataType: 'json',
			data: {
				submenuId: submenuId,
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $('.modal').modal("toggle");
				$('#judul').html('Edit Submenu');
				$('#id').val(res.submenu.id_sub);
				$('#title').val(res.submenu.title);
				$('#url').val(res.submenu.sub_url);
                $('#icon').val(res.submenu.icon);
				if (res.submenu.is_aktive == 1) {
					$("#check-submenuaktif").prop("checked", true)
				} else {
					$("#check-submenuaktif").prop("checked", false)
				}
                var html = '<option value="">Select Menu</option>';
				var i;
				for(i=0; i<res.menu.length; i++){
					html += '<option value='+res.menu[i].id_menu + '>' + res.menu[i].nm_menu + '</option>';
				}
				$('#menu').html(html);
				$('#menu').val(res.submenu.id_menu);
            }
        });
	});
	$(document).on("click","#delete-submenu",function(){
        const submenuId = $(this).data('idsub');
        deletemethod = "deleteSubmenu";
		$.ajax({
			url: base_url + "administrator/deletesubmenu",
			dataType: 'json',
			data: {
                submenuId: submenuId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Delete Submenu");
                $('.modal').modal("toggle");
				$('#id').val(res.submenu.id_sub);
                $('#dt-delete').html(res.submenu.title);
				$('#dt-text').html(res.text);
			}
		});
	});
    $(document).on("click","#restore-submenu",function(){
        const submenuId = $(this).data('idsub');
        deletemethod = "restoreSubmenu";
		$.ajax({
			url: base_url + "administrator/deletesubmenu",
			dataType: 'json',
			data: {
                submenuId: submenuId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Restore Submenu");
                $('.modal').modal("toggle");
				$('#id').val(res.submenu.id_sub);
                $('#dt-delete').html(res.submenu.title);
				$('#dt-text').html(res.text);
                $('#delete').html("Restore");
			}
		});
	});
    $(document).on("click","#remove-submenu",function(){
        const submenuId = $(this).data('idsub');
        deletemethod = "removeSubmenu";
		$.ajax({
			url: base_url + "administrator/deletesubmenu",
			dataType: 'json',
			data: {
                submenuId: submenuId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Remove Submenu");
                $('.modal').modal("toggle");
				$('#id').val(res.submenu.id_sub);
                $('#dt-delete').html(res.submenu.title);
				$('#dt-text').html(res.text);
                $('#delete').html("Delete Permanent");
			}
		});
	});
    $(document).on("submit","#fm-submenu",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "administrator/tambahsubmenu",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
					$('.csrf_tbl_submenu').val(res.token);
					dataTabel();
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                    setInterval(function () {
                        document.location.href = base_url + "administrator/submenu";
                    }, 500)
                }else{
					$('.csrf_submenu').val(res.token);
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
        $("#fm-submenu input").on("keyup",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-submenu input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-submenu select").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });
	$(document).on("click","#delete",function(e){
        e.preventDefault();
		const submenuId = $('#id').val();
        $.ajax({
            url: base_url + "administrator/deletesubmenu",
            type:'POST',
			data: {
				submenuId: submenuId,
				deletemethod: deletemethod,
                csrf_token_name: $(".csrf_del").val(),
			},
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_submenu').val(res.token);
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