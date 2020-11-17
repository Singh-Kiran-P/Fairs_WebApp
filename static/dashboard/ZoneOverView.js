/* AJAX REQUEST */
function showTimeSlotByDate(date) {
  var zoneId = document.getElementsByName("zoneId")[0].value;

  if (checkInputDate()) {
    var xhttp;

    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        var tableHeading =
          "<tr><th>Date</th><th>Start time</th><th>End time</th><th>Open</th></tr>";
        document.getElementsByClassName("zoneTimeslotstable")[0].innerHTML =
          tableHeading + this.responseText;
        document.getElementById("error").innerHTML = "";
      }
    };

    xhttp.open(
      "GET",
      "../../server/dashboard/city/showTimeslots.php?zoneId=" +
      zoneId +
      "&date=" +
      date,
      true
    );
    xhttp.send();
  } else {
    document.getElementsByClassName("zoneTimeslotstable")[0].innerHTML = "";
  }
}


function checkInputDate() {
  var zoneId = document.getElementsByName("zoneId")[0].value;
  var date = document.getElementsByName("date")[0].value;
  if (zoneId == "" || date == "") {
    document.getElementsByClassName("zoneTimeslotstable")[0].innerHTML = "";
    document.getElementById("error").innerHTML = "Invaild Zone";
    return false;
  }

  return true;
}
