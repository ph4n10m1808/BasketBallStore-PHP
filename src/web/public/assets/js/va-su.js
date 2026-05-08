$(document).ready(() => {
  $("#login-container input").on("keyup", (event) => {
    $(event.currentTarget).parent().children("label").children("span").html("");
    $(event.currentTarget).parent().parent().find(".msg-check-login").html("");
  });
});

$(document).ready(function () {
  $("#register-container input").on("keyup", function () {
    $(this).parent().children("h6").children("span").html("");
  });
  $('#register-container input[name="gender"]').change(() => {
    $('#register-container input[name="gender"]')
      .parent()
      .parent()
      .children("h6")
      .children("span")
      .html("");
  });
});

$(document).ready(() => {
  $("#form-login").on("submit", (e) => {
    e.preventDefault();

    const $btn = $("#button-login");
    const originalText = $btn.html();
    $btn
      .html('<i class="fas fa-spinner fa-spin me-2"></i>Signing in...')
      .prop("disabled", true);

    $.post(
      "Middlewares/login.php",
      {
        username: $("#input-username-login").val(),
        password: $("#input-password-login").val(),
      },
      (data) => {
        const msg = JSON.parse(data);

        // Check only login-related message fields (not redirect)
        const hasError = msg.msgUsername || msg.msgPassword || msg.msgLogin;

        if (hasError) {
          $("#login-container .msg-check-username").html(
            msg?.msgUsername || "",
          );
          $("#login-container .msg-check-password").html(
            msg?.msgPassword || "",
          );

          // Show/hide the error alert box
          if (msg.msgLogin) {
            $("#login-container .msg-check-login").html(msg.msgLogin);
            $("#login-error-alert").fadeIn(300);
          } else {
            $("#login-error-alert").fadeOut(200);
          }

          // Shake animation on error
          $("#form-login").addClass("shake-animation");
          setTimeout(
            () => $("#form-login").removeClass("shake-animation"),
            500,
          );

          $btn.html(originalText).prop("disabled", false);
        } else {
          // Success - show toast then redirect
          $btn.html('<i class="fas fa-check me-2"></i>Success!');
          $btn.removeClass("btn-login-gradient").addClass("btn-success");

          if (typeof $.toast === "function") {
            $.toast({
              heading: "Đăng nhập thành công!",
              text: "Đang chuyển hướng...",
              icon: "success",
              position: "top-right",
              showHideTransition: "slide",
              hideAfter: 1500,
            });
          }

          setTimeout(() => {
            window.location.href = msg?.redirect || "?page=home";
          }, 800);
        }
      },
    ).fail(function () {
      $btn.html(originalText).prop("disabled", false);
      $("#login-container .msg-check-login").html(
        "Có lỗi xảy ra, vui lòng thử lại.",
      );
    });
  });
});

$(document).ready(function () {
  $("#button-register").click(function (event) {
    event.preventDefault();
    $.post(
      "Middlewares/register.php",
      {
        firstname: $("#reg-firstname").val(),
        lastname: $("#reg-lastname").val(),
        gender: $('input[name="gender"]:checked').val(),
        user: $("#reg-username").val(),
        pass: $("#reg-password").val(),
        repass: $("#reg-re-password").val(),
        email: $("#reg-email").val(),
        phone: $("#reg-phone").val(),
      },
      function (data) {
        const msg = JSON.parse(data);
        let check = true;
        for (const each in msg) {
          if (msg[each]) {
            check = false;
          }
        }
        if (!check) {
          $("#register-container .msg-check-fn").html(msg?.msgFn);
          $("#register-container .msg-check-ln").html(msg?.msgLn);
          $("#register-container .msg-check-gender").html(msg?.msgGender);
          $("#register-container .msg-check-username").html(msg?.msgUsername);
          $("#register-container .msg-check-pass").html(msg?.msgPassword);
          $("#register-container .msg-check-email").html(msg?.msgEmail);
          $("#register-container .msg-check-phone").html(msg?.msgPhone);
        } else {
          if (typeof $.toast === "function") {
            $.toast({
              heading: "Đăng ký thành công!",
              text: "Đang chuyển đến trang đăng nhập...",
              icon: "success",
              position: "top-right",
              showHideTransition: "slide",
              hideAfter: 1500,
            });
          }
          setTimeout(() => {
            window.location = "?page=login";
          }, 800);
        }
      },
    );
  });
});

$(document).ready(function () {
  $("#button-update-info").click(function (event) {
    event.preventDefault();
    // const password = prompt("Nhập mật khẩu để xác nhận");
    $.post(
      "Middlewares/changeInfo.php",
      {
        firstname: $("#reg-firstname").val(),
        lastname: $("#reg-lastname").val(),
        gender: $('input[name="gender"]:checked').val(),
        email: $("#reg-email").val(),
        phone: $("#reg-phone").val(),
        address: $("#reg-address").val(),
      },
      function (data) {
        let check = true;
        let msg;
        if (typeof data !== "string") {
          msg = JSON.parse(data);
          for (const each in msg) {
            if (msg[each]) {
              check = false;
            }
          }
        }
        if (!check) {
          $("#register-container .msg-check-fn").html(msg?.msgFn);
          $("#register-container .msg-check-ln").html(msg?.msgLn);
          $("#register-container .msg-check-gender").html(msg?.msgGender);
          $("#register-container .msg-check-email").html(msg?.msgEmail);
          $("#register-container .msg-check-phone").html(msg?.msgPhone);
          $("#register-container .msg-check-address").html(msg?.msgAddress);
        } else {
          if (typeof $.toast === "function") {
            $.toast({
              heading: "Thành công!",
              text: "Cập nhật thông tin thành công",
              icon: "success",
              position: "top-right",
              showHideTransition: "slide",
              hideAfter: 1500,
            });
          } else {
            alert("Cập nhật thông tin thành công");
          }
          window.location = "?page=profile";
        }
      },
    );
  });
});

$(document).ready(function () {
  $("#button-change-password").click(function (event) {
    event.preventDefault();
    $.post(
      "Middlewares/changePassword.php",
      {
        oldPassword: $("#old-password").val(),
        newPassword: $("#new-password").val(),
        confirmPassword: $("#confirm-password").val(),
      },
      function (data) {
        let check = true;
        let msg;
        if (typeof data !== "string") {
          msg = JSON.parse(data);
          for (const each in msg) {
            if (msg[each]) {
              check = false;
            }
          }
        }
        if (!check) {
          $("#register-container .msg-check-old-password").html(msg?.msgCheckOld || msg?.msgOldPw || "");
          $("#register-container .msg-check-pass").html(msg?.msgCheckConfirm || msg?.msgNewPw || "");
          // $("#register-container .msg-check-retype-pass").html(msg?.msgCheckConfirm);
        } else {
          if (typeof $.toast === "function") {
            $.toast({
              heading: "Thành công!",
              text: "Thay đổi mật khẩu thành công, vui lòng đăng nhập lại",
              icon: "success",
              position: "top-right",
              showHideTransition: "slide",
              hideAfter: 2000,
            });
          } else {
            alert("Thay đổi mật khẩu thành công, vui lòng đăng nhập lại");
          }
          window.location = "?page=logout";
        }
      },
    );
  });
});

$(document).ready(function () {
  $("#submit-buy").click(function (event) {
    $.post(
      "Middlewares/addCart.php",
      {
        size: $("#size-selected").val(),
        quantity: $("#quantity-cart").val(),
        typeSize: $("#type-product").val(),
        id: $("#id-product").val(),
        restQuantity: $("#quantity-product").text(),
      },
      function (data) {
        if (!data) {
          $("#alert-cart").html("Vui lòng chọn size");
          setTimeout(() => $("#alert-cart").html(""), 5000);
        } else {
          if (typeof $.toast === "function") {
            $.toast({
              heading: "Thành công!",
              text: "Đã thêm sản phẩm vào giỏ hàng",
              icon: "success",
              position: "top-right",
              showHideTransition: "slide",
              hideAfter: 2000,
            });
          } else {
            alert("Đã thêm vào giỏ hàng");
          }
        }
      },
    );
  });
});

$(document).ready(function () {
  $(".button-delete-item").click(function (event) {
    $.post(
      "Middlewares/deleteCartSession.php",
      {
        id: $(this).val(),
        size: $(this).data("size") || "",
      },
      function (data) {
        if (data) {
          if (typeof $.toast === "function") {
            $.toast({
              heading: "Thành công!",
              text: "Đã xoá sản phẩm khỏi giỏ hàng",
              icon: "success",
              position: "top-right",
              showHideTransition: "slide",
              hideAfter: 1500,
            });
          } else {
            alert("Xoá thành công");
          }
          window.location = "?page=cart";
        }
      },
    );
  });
});

$(document).ready(function () {
  $(".btn-update-cart").click(function (e) {
    const id = $(this).attr("id").split("-")[1];
    const size = $(this).attr("id").split("-")[2];
    const type = $(this).attr("id").split("-")[0];
    $.post(
      "Middlewares/updateCart.php",
      {
        id: id,
        size: size,
        type: type,
      },
      function (data) {
        $(`#total-cost-product`).html(
          Intl.NumberFormat().format(data - 30000) + " đ",
        );
        $("#total-cost").html(Intl.NumberFormat().format(data) + " đ");
      },
    );
  });
});
