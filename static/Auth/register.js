// TUT : https://www.studentstutorial.com/ajax/login-signup
$(document).ready(run());

function checkPassword() {
  var p1 = $("#password").val();
  var p2 = $("#password2").val();

  if (p1 != p2) {
    return false;
  }
  return true;
}

function run() {
  $("#btn").on("click", function () {
    // $("#butsave").attr("disabled", "disabled");
    var username = $("#username").val();
    var email = $("#email").val();
    var password = $("#password").val();
    var password2 = $("#password2").val();
    var type = $("#type").val();
    if (checkPassword()) {
      if (username != "" && email != "" && password != "" && password2 != "") {
        $.ajax({
          url: "../../server/auth/register.php",
          type: "POST",
          data: {
            username: username,
            email: email,
            password: password,
            type:type
          },
          cache: false,
          success: function (dataResult) {
            var dataResult = JSON.parse(dataResult);
            $("#error").html(dataResult.msg);
          },
        });
      } else {
        $("#error").html("All inputs are required");
      }
    } else {
      $("#error").html("Passwords do not match!!");
    }
  });
}
