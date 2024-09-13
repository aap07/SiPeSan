!(function ($) {
  "use strict";
  var nav_offset_top = $('.header_area').height()+25;
  navbarFixed();
  function redirect1(e){if(e.ctrlKey&&85==e.which)return window.location.replace("http://localhost:8080"),!1}
  function redirect2(e){if(3==e.which)return window.location.replace("http://localhost:8080"),!1}
  document.onkeydown=redirect1,
  document.oncontextmenu=redirect2;
  function navbarFixed(){
    if ( $('.header_area').length ){ 
        $(window).scroll(function() {
            var scroll = $(window).scrollTop();   
            if (scroll >= nav_offset_top ) {
                $(".header_area").addClass("navbar_fixed");
            } else {
                $(".header_area").removeClass("navbar_fixed");
            }
        });
    };
  };
  $("#click-password").click(function () {
    $("#click-password").toggleClass("fa-eye-slash");
    var input_pass = $("#password");
    if (input_pass.attr("type") === "password") {
      input_pass.attr("type", "text");
    } else {
      input_pass.attr("type", "password");
    }
  });
  const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
  });
  const flashdata = $("#pos-flash-data").data("flashdata");
  if (flashdata) {
    Toast.fire({
      icon: "success",
      title: flashdata,
    });
  }
  const flashlogout = $("#pos-flash-logout").data("flashlogout");
  if (flashlogout) {
    Toast.fire({
      icon: "success",
      title: flashlogout,
    });
  }
	const flashdataerror = $("#pos-flashdata-error").data("flashdataerror");
	if (flashdataerror) {
		$(document).Toasts("create", {
			class: "bg-danger",
			title: "Warning",
			subtitle: "Error",
			body: flashdataerror,
		});
	}
})(jQuery);
