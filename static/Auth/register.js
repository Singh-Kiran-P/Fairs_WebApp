/**
 * Checking password
 *
 * @return true if passwords match else false
 */
function checkPassword() {
  var p1 = $("#password").val();
  var p2 = $("#password2").val();

  if (p1 != p2) {
    return false;
  }
  return true;
}
