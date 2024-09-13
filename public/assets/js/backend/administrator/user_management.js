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
    function tanggalFormat(angka){
        var bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September' , 'Oktober', 'November', 'Desember'];

        var date = angka.split(" ")[0];

        var tanggal = date.split("-")[2];
        var bulan = date.split("-")[1];
        var tahun = date.split("-")[0];

        return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun;
    }
	function dataTabel(){
		$("#tbl-user").DataTable({
			"order" : [],
			"processing" : true,
			"serverSide" : true,
			"bDestroy": true,
			"ajax" : {
				"url" : base_url + "administrator/listuser",
				"type" : "POST",
                "data" : {"csrf_token_name" : $(".csrf_tbl_user").val()},
                "data" : function(data){
                    data.csrf_token_name = $(".csrf_tbl_user").val();
                },
                "dataSrc" : function(response){
                    $(".csrf_tbl_user").val(response.csrf_token_name);
                    return response.data;
                }
			},
			"columnDefs" : [{
				"targets" : [0],
				"orderable" : false
			},
            {
                "targets" : [3,4,5],
                "className" : 'text-center'
            }]
		});
	}
    $(document).on("click","#tmbh-user",function(){
        savemethod = "tmbhUser";
		$.ajax({
            url: base_url + "administrator/tambah",
            dataType: 'json',
            data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Tambah User");
                $('.modal').modal("toggle");
                var html = '<option value="">Select Level</option>';
				var i;
				for(i=0; i<res.role.length; i++){
					html += '<option value='+res.role[i].id_role + '>' + res.role[i].nm_role + '</option>';
				}
				$('#level').html(html);
            }
        });
	});
    $(document).on("click","#edit-user",function(){
        savemethod = "edtUser";
        const userId = $(this).data('iduser');
		$.ajax({
            url: base_url + "administrator/tambah",
            dataType: 'json',
            data: {
                userId: userId,
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Edit User");
                $('.modal').modal("toggle");
                $('#id').val(res.user.id_user);
                var html = '<option value="">Select Level</option>';
				var i;
				for(i=0; i<res.role.length; i++){
					html += '<option value='+res.role[i].id_role + '>' + res.role[i].nm_role + '</option>';
				}
				$('#level').html(html);
                $('#level').val(res.user.id_role);
                if (res.user.is_aktif == 1) {
					$("#check-user").prop("checked", true)
				} else {
					$("#check-user").prop("checked", false)
				}
            }
        });
	});
    $(document).on("click","#detail-user",function(){
        const userId = $(this).data('iduser');
		$.ajax({
            url: base_url + "administrator/detail",
            dataType: 'json',
            data: {
                userId: userId,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Detail User");
                $('.modal').modal("toggle");
                if (res.user.role_id == 1) {
                    $('#role').html('<span class="badge badge-info">' + res.user.nm_role + '</span>');
                }else if (res.user.role_id == 2) {
                    $('#role').html('<span class="badge badge-secondary">' + res.user.nm_role + '</span>');
                } else {
                    $('#role').html('<span class="badge badge-success">' + res.user.nm_role + '</span>');
                }
                $('#nama').html(res.user.nama);
                if (res.user.is_aktif == 1) {
                    $('#status').html('<p class="badge badge-warning">Active</p>');
                }else{
                    $('#status').html('<p class="badge badge-danger">Not Active</p>');
                }
                $('#username').html(res.user.username);
                $('#email').html(res.user.email);
                $('#tlp').html(res.user.tlp);
                $('#thumb-img-profile').attr(
                    'src',
                    base_url + "assets/img/profile/" + res.user.img_user
                );
                $('#created').html(tanggalFormat(res.user.created_at));
                $('#lastlogin').html(tanggalFormat(res.user.last_signin));
            }
        });
	});
    $(document).on("click","#delete-user",function(){
        const userId = $(this).data('iduser');
        deletemethod = "deleteUser";
		$.ajax({
			url: base_url + "administrator/delete",
			dataType: 'json',
			data: {
                userId: userId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Delete User");
                $('.modal').modal("toggle");
				$('#id').val(res.user.id_user);
                $('#dt-delete').html(res.user.nama);
				$('#dt-text').html(res.text);
			}
		});
	});
    $(document).on("click","#restore-user",function(){
        const userId = $(this).data('iduser');
        deletemethod = "restoreUser";
		$.ajax({
			url: base_url + "administrator/delete",
			dataType: 'json',
			data: {
                userId: userId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Restore User");
                $('.modal').modal("toggle");
				$('#id').val(res.user.id_user);
                $('#dt-delete').html(res.user.nama);
				$('#dt-text').html(res.text);
                $('#delete').html("Restore");
			}
		});
	});
    $(document).on("click","#remove-user",function(){
        const userId = $(this).data('iduser');
        deletemethod = "removeUser";
		$.ajax({
			url: base_url + "administrator/delete",
			dataType: 'json',
			data: {
                userId: userId,
				deletemethod: deletemethod,
			},
			success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Remove User");
                $('.modal').modal("toggle");
				$('#id').val(res.user.id_user);
                $('#dt-delete').html(res.user.nama);
				$('#dt-text').html(res.text);
                $('#delete').html("Delete Permanent");
			}
		});
	});
    $(document).on("submit","#fm-tmbh-user",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "administrator/tambah",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: "User Berhasil di Tambah",
                    });
                    $('.csrf_tbl_user').val(res.token);
                    dataTabel();
                }else{
                    $('.csrf_tmbh_user').val(res.token);
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
        $("#fm-tmbh-user input").on("keyup",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-tmbh-user input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-tmbh-user select").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });
    $(document).on("submit","#fm-edt-user",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "administrator/tambah",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.modal').modal("toggle");
                    Toast.fire({
                        icon: "success",
                        title: "User Berhasil di Rubah",
                    });
                    $('.csrf_tbl_user').val(res.token);
                    dataTabel();
                }else{
                    $('.csrf_edt_user').val(res.token);
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
        $("#fm-edt-user select").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });
    $(document).on("click","#delete",function(e){
        e.preventDefault();
		const userId = $('#id').val();
        $.ajax({
            url: base_url + "administrator/delete",
            type:'POST',
			data: {
				userId: userId,
				deletemethod: deletemethod,
                csrf_token_name: $(".csrf_del").val(),
			},
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_tbl_user').val(res.token);
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