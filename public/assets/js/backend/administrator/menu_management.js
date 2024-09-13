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
		$("#tbl-menu").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
			"ajax" : {
				"url" : base_url + "administrator/listmenu",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_menu").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_menu").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_menu").val(response.csrf_token_name);
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

    $(document).on("click","#tmbh-menu",function(){
        savemethod = "tmbhMenu";
		$.ajax({
            url: base_url + "administrator/tambahmenu",
            dataType: 'json',
            data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Tambah Menu");
                $('.modal').modal("toggle");
            }
        });
	});

    $(document).on("click","#edit-menu",function(){
        savemethod = "edtMenu";
        const menuId = $(this).data('idmenu');
		$.ajax({
            url: base_url + "administrator/tambahmenu",
            dataType: 'json',
            data: {
                menuId: menuId,
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Edit Menu");
                $('.modal').modal("toggle");
                $('#id').val(res.menu.id_menu);
                $('#menu').val(res.menu.nm_menu);
                $('#fungsi').val(res.menu.fungsi_menu);
                $('#url').val(res.menu.url);
                if (res.menu.sub_menu == 1) {
					$("#check-submenu").prop("checked", true)
				} else {
					$("#check-submenu").prop("checked", false)
				}
                if (res.menu.is_aktif == 1) {
					$("#check-menuaktif").prop("checked", true)
				} else {
					$("#check-menuaktif").prop("checked", false)
				}
            }
        });
	});
    
    $(document).on("click","#delete-menu",function(){
        const menuId = $(this).data('idmenu');
        deletemethod = "deleteMenu";
		$.ajax({
			url: base_url + "administrator/deletemenu",
			dataType: 'json',
			data: {
                menuId: menuId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Delete Menu");
                $('.modal').modal("toggle");
				$('#id').val(res.menu.id_menu);
                $('#dt-delete').html(res.menu.nm_menu);
				$('#dt-text').html(res.text);
			}
		});
	});
    $(document).on("click","#restore-menu",function(){
        const menuId = $(this).data('idmenu');
        deletemethod = "restoreMenu";
		$.ajax({
			url: base_url + "administrator/deletemenu",
			dataType: 'json',
			data: {
                menuId: menuId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Restore Menu");
                $('.modal').modal("toggle");
				$('#id').val(res.menu.id_menu);
                $('#dt-delete').html(res.menu.nm_menu);
				$('#dt-text').html(res.text);
                $('#delete').html("Restore");
			}
		});
	});
    $(document).on("click","#remove-menu",function(){
        const menuId = $(this).data('idmenu');
        deletemethod = "removeMenu";
		$.ajax({
			url: base_url + "administrator/deletemenu",
			dataType: 'json',
			data: {
                menuId: menuId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Remove Menu");
                $('.modal').modal("toggle");
				$('#id').val(res.menu.id_menu);
                $('#dt-delete').html(res.menu.nm_menu);
				$('#dt-text').html(res.text);
                $('#delete').html("Delete Permanent");
			}
		});
	});
    $(document).on("submit","#fm-menu",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "administrator/tambahmenu",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_menu').val(res.token);
                    dataTabel();
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                    setInterval(function () {
                        document.location.href = base_url + "administrator/menu";
                    }, 500)
                }else{
                    $('.csrf_menu').val(res.token);
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
        $("#fm-menu input").on("keyup",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-menu input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });
    $(document).on("click","#delete",function(e){
        e.preventDefault();
		const menuId = $('#id').val();
        $.ajax({
            url: base_url + "administrator/deletemenu",
            type:'POST',
			data: {
				menuId: menuId,
				deletemethod: deletemethod,
                csrf_token_name: $(".csrf_del").val(),
			},
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_menu').val(res.token);
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