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
		$("#tbl-role").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
            "responsive": true,
            "autoWidth": false,
			"ajax" : {
				"url" : base_url + "administrator/listrole",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_role").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_role").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_role").val(response.csrf_token_name);
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
    function accessTabel(roleId){
		$("#tbl-access").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
            "responsive": true,
            "autoWidth": false,
			"ajax" : {
				"url" : base_url + "administrator/listaccess",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_access").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_access").val();
                    data.roleId = roleId;
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_access").val(response.csrf_token_name);
                    $(".csrf_tbl_role").val(response.csrf_token_name);
                    return response.data;
                }
			},
			"columnDefs" : [{
				"targets" : [0],
				"orderable" : false
			},
            {
                "targets" : [2],
                "className" : 'form-check text-center'
            }]
		});
	}
    $(document).on("click","#tmbh-role",function(){
        savemethod = "tmbhRole";
		$.ajax({
            url: base_url + "administrator/tambahrole",
            dataType: 'json',
            data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Tambah Role");
                $('.modal').modal("toggle");
            }
        });
	});
    $(document).on("click","#edit-role",function(){
        savemethod = "edtRole";
        const roleId = $(this).data('idrole');
		$.ajax({
            url: base_url + "administrator/tambahrole",
            dataType: 'json',
            data: {
                roleId: roleId,
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Edit Role");
                $('.modal').modal("toggle");
                $('#id').val(res.role.id_role);
                $('#role').val(res.role.nm_role);
                if (res.role.is_aktive == 1) {
					$("#check-roleaktif").prop("checked", true)
				} else {
					$("#check-roleaktif").prop("checked", false)
				}
            }
        });
	});
    $(document).on("click","#delete-role",function(){
        const roleId = $(this).data('idrole');
        deletemethod = "deleteRole";
		$.ajax({
			url: base_url + "administrator/deleterole",
			dataType: 'json',
			data: {
                roleId: roleId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Delete Role");
                $('.modal').modal("toggle");
				$('#id').val(res.role.id_role);
                $('#dt-delete').html(res.role.nm_role);
				$('#dt-text').html(res.text);
			}
		});
	});
    $(document).on("click","#restore-role",function(){
        const roleId = $(this).data('idrole');
        deletemethod = "restoreRole";
		$.ajax({
			url: base_url + "administrator/deleterole",
			dataType: 'json',
			data: {
                roleId: roleId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Restore Role");
                $('.modal').modal("toggle");
				$('#id').val(res.role.id_role);
                $('#dt-delete').html(res.role.nm_role);
				$('#dt-text').html(res.text);
                $('#delete').html("Restore");
			}
		});
	});
    $(document).on("click","#remove-role",function(){
        const roleId = $(this).data('idrole');
        deletemethod = "removeRole";
		$.ajax({
			url: base_url + "administrator/deleterole",
			dataType: 'json',
			data: {
                roleId: roleId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Remove Role");
                $('.modal').modal("toggle");
				$('#id').val(res.role.id_role);
                $('#dt-delete').html(res.role.nm_role);
				$('#dt-text').html(res.text);
                $('#delete').html("Delete Permanent");
			}
		});
	});
    $(document).on("click","#access-role",function(){
        // savemethod = "tmbhRole";
        const roleId = $(this).data('idrole');
		$.ajax({
            url: base_url + "administrator/roleaccess",
            dataType: 'json',
            data: {
                roleId: roleId,
				// savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Role-Access " + res.role.nm_role);
                $('.modal').modal("toggle");
                accessTabel(res.role.id_role);
            }
        });
	});
    $(document).on("submit","#fm-role",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "administrator/tambahrole",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_role').val(res.token);
                    dataTabel();
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                }else{
                    $('.csrf_role').val(res.token);
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
        $("#fm-role input").on("keyup",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-role input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });
    $(document).on("click","#delete",function(e){
        e.preventDefault();
		const roleId = $('#id').val();
        $.ajax({
            url: base_url + "administrator/deleterole",
            type:'POST',
			data: {
				roleId: roleId,
				deletemethod: deletemethod,
                csrf_token_name: $(".csrf_del").val(),
			},
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_role').val(res.token);
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
    $(document).on("click",".form-check-input",function(e){
        e.preventDefault();
        const menuId = $(this).data('menu');
        const roleId = $(this).data('role');
        $.ajax({
            url: base_url + 'administrator/changeaccess',
            type: 'POST',
            data: {
                menuId: menuId,
                roleId: roleId,
                csrf_token_name: $(".csrf_access").val()
            },
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_access').val(res.token);
                    accessTabel(res.role);
                    $('.csrf_tbl_role').val(res.token);
                    $('.csrf_access').val(res.token);
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                    if(res.link == 1){
                        refresh();
                    }
                }
            }
        });
    });
    function refresh()
    {
        $(document).on("click",".close",function(){
            document.location.href = base_url + 'administrator/rolemenu';
        });
    }
})(jQuery);