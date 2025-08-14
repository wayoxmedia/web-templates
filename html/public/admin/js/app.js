$(function () {
  // Admin Panel settings

  let $mainWrapper = $("#main-wrapper");
  let $sidebarToggler =  $(".sidebarToggler");
  //****************************
  /* This is for the mini-sidebar if width is less then 1170*/
  //****************************
  let setSidebarType = function () {
    let width =
      window.innerWidth > 0 ? window.innerWidth : this.screen.width;
    if (width < 1199) {
      $mainWrapper.attr("data-sidebartype", "mini-sidebar");
      $mainWrapper.addClass("mini-sidebar");
    } else {
      $mainWrapper.attr("data-sidebartype", "full");
      $mainWrapper.removeClass("mini-sidebar");
    }
  };
  $(window).ready(setSidebarType);
  $(window).on("resize", setSidebarType);
  //****************************
  /* This is for sidebarToggler*/
  //****************************
  $sidebarToggler.on("click", function () {
    $mainWrapper.toggleClass("mini-sidebar");
    if ($mainWrapper.hasClass("mini-sidebar")) {
      $sidebarToggler.prop("checked", !0);
      $mainWrapper.attr("data-sidebartype", "mini-sidebar");
    } else {
      $sidebarToggler.prop("checked", !1);
      $mainWrapper.attr("data-sidebartype", "full");
    }
  });
  $sidebarToggler.on("click", function () {
    $mainWrapper.toggleClass("show-sidebar");
  });
})
