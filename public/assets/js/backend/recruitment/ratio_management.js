!(function ($) {
    "use strict";
    hitung();
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
    });
    
    $(".inputnumber").each(function(){
        $(this).change(function(){		
            hitung();
        });
    });

    function hitung()
    {
        $(".inputnumber").each(function(){
                var dtarget=$(this).attr('data-target');
                var dkolom=$(this).attr('data-kolom');
                var jumlah=$(this).val();
                var rumus=1/parseFloat(jumlah);
                var fx=rumus;
                $("#"+dtarget).val(fx);
                total();
                mnk();
                mptb();
                rk();
                //alert(dkolom);
        //	});
        });	
    }

    function total()
    {
        for(var i=1;i<=jumlahData;i++)
        {
            var sum=0;
            $(".kolom"+i).each(function(){
                sum+=parseFloat($(this).val());
            });
            var fx=sum;
            $("#total"+i).val(fx);
        }	
    }

    function mnk()
    {	
        for(var i=1;i<=jumlahData;i++)
        {
            var jml=0;
            for(var x=1;x<= jumlahData ;x++)
            {			
                var vtarget=$("#k"+i+"b"+x).val();
                var vkolom=$("#total"+x).val();
                var rumus=parseFloat(vtarget)/parseFloat(vkolom);
                var fx=rumus;			
                jml+=parseFloat(rumus);
                $("#mn-k"+i+"b"+x).val(fx);
                //$("#mn-k"+i+"b"+x).val(i+" "+x);						
            }
            var jumlahmnk=jml;
            var prio=parseFloat(jml)/parseFloat(jumlahData);
            var totprio=prio;
            $("#jml-b"+i).val(jumlahmnk);
            $("#pri-b"+i).val(totprio);		
            
        }
    }

    function mptb()
    {	
        for(var i=1;i<=jumlahData;i++)
        {
            var jml=0;
            for(var x=1;x<=jumlahData;x++)
            {			
                var prio=$("#pri-b"+x).val();
                var nilai=$("#k"+i+"b"+x).val();
                var rumus=parseFloat(nilai)*parseFloat(prio);
                var fx=rumus;
                jml+=parseFloat(rumus);
                $("#mptb-k"+i+"b"+x).val(fx);
            }
            var jumlahmnk=jml;
            $("#jmlmptb-b"+i).val(jumlahmnk);
        }
    }

    function rk()
    {
        var total=0;	
        for(var i=1;i<=jumlahData;i++)
        {
            var prio=$("#pri-b"+i).val();
            var jml=$("#jmlmptb-b"+i).val();
            var hasil=parseFloat(prio)+parseFloat(jml);
            // var hasil=parseFloat(jml)/parseFloat(prio);
            var fx=hasil;
            total+=hasil;
            $("#jmlrk-b"+i).val(jml);
            $("#priork-b"+i).val(prio);
            $("#hasilrk-b"+i).val(fx);
        }
        var fx2=total;
        $("#totalrk").val(fx2);
        $("#sumrk").val(fx2);
        var summaks=parseFloat(total)/parseFloat(jumlahData);
        var fx_summaks=summaks;
        $("#summaks").val(fx_summaks);
        var ci_r_1=parseFloat(summaks)-parseFloat(jumlahData);
        var ci=parseFloat(ci_r_1)/parseFloat(jumlahData);
        var fx_ci=ci;
        $("#sumci").val(fx_ci);
        var cr=parseFloat(ci)/parseFloat(ir);
        var fx_cr=cr;
        $("#sumcr").val(fx_cr);
        $("#crvalue").val(fx_cr);
    }

    $(document).on("submit","#fm-nilai",function(e){
        e.preventDefault();
        $.ajax({
            url: base_url + "recruitment/saveNilai",
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
                    $('.csrf_ratio_kriteria').val(x.token);
                    console.log(x.msg[1]);
                    Toast.fire({
                        icon: "success",
                        title: x.msg,
                    });
                }else{
                    $('.csrf_ratio_kriteria').val(x.token);
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