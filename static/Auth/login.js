// TUT : https://www.studentstutorial.com/ajax/login-signup
$(document).ready(run());

function run() {
  $("#btn").on("click", function () {
    // $("#butsave").attr("disabled", "disabled");
    var email = $("#email").val();
    var password = $("#password").val();
    if (email != "" && password != "") {
      $.ajax({
        url: "/~kiransingh/project/server/auth/login.php",
        type: "POST",
        data: {
          email: email,
          password: password,
        },
        cache: false,
        success: function (dataResult) {
          var dataResult = JSON.parse(dataResult);
          if (dataResult.status === 200)
            window.location.href = dataResult.redirectTo;
          else {
            $("#error").html(dataResult.msg);
          }
        },
      });
    } else {
      $("#error").html("All inputs are required");
    }
  });
}
