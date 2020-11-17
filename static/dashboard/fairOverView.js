function showTimeSlot(zoneId) {
  window.location.href = "ZoneOverView.php?zoneId=" + zoneId;
}

function checkInput() {
  var zoneId = document.getElementsByName("zoneId")[0].value;
  if (zoneId == "") {
    document.getElementsByClassName("zoneTimeslotstable")[0].innerHTML = "";
    document.getElementById("error").innerHTML = "Invaild Zone";
    return false;
  }

  return true;
}
