// TUT : https://www.studentstutorial.com/ajax/login-signup


function checkPassword() {
  var p1 = $("#password").val();
  var p2 = $("#password2").val();

  if (p1 != p2) {
    return false;
  }
  return true;
}
