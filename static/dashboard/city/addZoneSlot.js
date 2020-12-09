function validateForm() {
  var openingSlot = document.getElementsByName("openingSlot")[0].value;
  var closingSlot = document.getElementsByName("closingSlot")[0].value;

  var error = document.getElementById("error");

  return checkTime(openingHour, closingHour, error);
}


function checkTime(t1, t2, out) {

  /*  */
  if (t2 <= t1) {
    out.innerHTML = "Closing time cannot be less then openning time"
    return false;
  }
  return true;

}
