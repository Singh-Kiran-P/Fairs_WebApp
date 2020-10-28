/**
 * Checking password
 *
 * @return true if passwords match else false
 */
function validateForm() {
  var p1 = document.getElementsByName('password')[0].value;
  var p2 = document.getElementsByName('password2')[0].value;
  var error = document.getElementById('error')
  if (p1 != p2) {
    error.innerHTML = "Passwords do not match!!"
    return false;
  }
  return true;
}
