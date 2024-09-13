!(function ($) {
    "use strict";
    var savemethod;
    $(document).ready(function() {
        bsCustomFileInput.init();
        dataProfile();
    });
    $(document).on("change","#img_photo",function(){
        const pic_prof = document.querySelector('#img_photo');
        const pic_prof_label = document.querySelector('.custom-file-label');
        const img_prev = document.querySelector('.img-preview');
        pic_prof_label.textContent = pic_prof.files[0].name;
        const file_pic_prof = new FileReader();
        file_pic_prof.readAsDataURL(pic_prof.files[0]);
        file_pic_prof.onload = function(e){
            img_prev.src = e.target.result;
        }
    });
    function tanggalFormat(angka){
        var bulanIndo = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September' , 'Oktober', 'November', 'Desember'];

        var date = angka.split(" ")[0];

        var tanggal = date.split("-")[2];
        var bulan = date.split("-")[1];
        var tahun = date.split("-")[0];

        return tanggal + " " + bulanIndo[Math.abs(bulan)] + " " + tahun;
    }
    function dataProfile(){
        $.ajax({
            url: base_url + "data",
            dataType: 'json',
            success: function(res) {
                $('#dt-img').attr(
                    'src',
                    base_url + "assets/img/profile/" + res.user.img_user
                );
				$('#dt-nama').html(res.user.nama);
				$('#dt-nik').html(res.user.nik);
				$('#dt-role').html(res.user.nm_role);
				$('#dt-username').html(res.user.username);
				$('#dt-email').html(res.user.email);
				$('#dt-tlp').html(res.user.tlp);
				$('#created').html(tanggalFormat(res.user.created_at));
				$('#updated').html(tanggalFormat(res.user.updated_at));
            }
        });
    }
	const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
    });
    $(document).on("click","#edit-prof",function(){
        savemethod = "dataProfile";
		$.ajax({
            url: base_url + "edit",
            dataType: 'json',
            data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Edit Profile");
                $('.modal').modal("toggle");
				$('#pic_lama').val(res.user.img_user);
				$('#username').val(res.user.username);
				$('#name').val(res.user.nama);
				$('#email').val(res.user.email);
				$('#tlp').val(res.user.tlp);
                $('#img-profile').attr(
                    'src',
                    base_url + "assets/img/profile/" + res.user.img_user
                );
				$('#nm-img').html(res.user.img_user);
            }
        });
	});
    $(document).on("submit","#fm-edt-prof",function(e){
        const formData  = new FormData(this);
        formData.append('savemethod', savemethod);
        e.preventDefault();
        $.ajax({
            url: base_url + "edit",
            type: $(this).attr("method"),
            data:formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_edt_prof').val(res.token);
                    $('.modal').modal("toggle");
                    dataProfile();
                    Toast.fire({
                        icon: "success",
                        title: res.title,
                    });
                }else{
                    $('.csrf_edt_prof').val(res.token);
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
        $("#fm-edt-prof input").on("keyup",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-edt-prof input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });
    $(document).on("click","#change-pass",function(){
        savemethod = "changePass";
		$.ajax({
            url: base_url + "edit",
            dataType: 'json',
            data: {
				savemethod: savemethod,
			},
            success: function(res) {
                $(".view-modal").html(res.modals);
                $(".view-form").html(res.form);
                $("#jdl-modal").html("Change Password");
                $('.modal').modal("toggle");
            }
        });
	});
    $(document).on("submit","#fm-change-pass",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "edit",
            type: $(this).attr("method"),
            data: $(this).serialize() + '&savemethod=' + savemethod,
            dataType: 'json',
            success: function(res) {
                if(res.status){
                    $('.csrf_edt_prof').val(res.token);
                    $('.modal').modal("toggle");
                    dataProfile();
                    if(res.verified == 1){
                        Toast.fire({
                            icon: "error",
                            title: "Password Lama Anda Salah",
                        });
                    }else if(res.verified == 2){
                        Toast.fire({
                            icon: "error",
                            title: "Password Tidak Boleh Sama",
                        });
                    }else {
                        Toast.fire({
                            icon: "success",
                            title: "Password Berhasil Dirubah",
                        });
                    }
                }else{
                    $('.csrf_edt_prof').val(res.token);
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
        $("#fm-change-pass input").on("keyup",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
        $("#fm-change-pass input").on("click",function(){
            $(this).removeClass('is-invalid is-invalid')
        });
    });
})(jQuery);