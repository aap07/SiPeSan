!(function ($) {
    "use strict";
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
    });
    
    $(document).on("submit","#fm-penilaian-applicants",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "recruitment/saveNilaiApplicants",
            type: $(this).attr("method"),
            data:$(this).serialize(),
            dataType: 'json',
            error:function(){
                Toast.fire({
                    icon: "danger",
                    title: "Gagal menyimpan data",
                });
                $("#fm-nilai select").removeAttr("disabled");
                $("#fm-nilai button").removeAttr("disabled");
            },
            beforeSend:function(){
                $("#fm-nilai select").attr('disabled','disabled');
                $("#fm-nilai button").attr('disabled','disabled');
                Toast.fire({
                    icon: "info",
                    title: "Tunggu sebentar,lagi menyimpan data",
                });
            },
            success:function(x){
                if(x.status)
                {
                    $('.csrf_nilai_applicants').val(x.token);
                    console.log(x.msg[1]);
                    Toast.fire({
                        icon: "success",
                        title: x.msg,
                    });
                }else{
                    $('.csrf_nilai_applicants').val(x.token);
                    Toast.fire({
                        icon: "danger",
                        title: x.msg,
                    });
                }
                $("#fm-nilai select").removeAttr("disabled");
                $("#fm-nilai button").removeAttr("disabled");
            },
        });
    });

})(jQuery);