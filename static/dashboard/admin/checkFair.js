function validateForm() {
  var startDate = document.getElementsByName("start_date")[0].value;
  var endDate = document.getElementsByName("end_date")[0].value;
  var openingHour = document.getElementsByName("opening_hour")[0].value;
  var closingHour = document.getElementsByName("closing_hour")[0].value;
  var error = document.getElementById("error");

  return checkDate(startDate, endDate, error) && checkTime(openingHour, closingHour, error);
}

function checkDate(d1, d2, out) {
  var start_date_orginal = document.getElementsByClassName("start_date_orginal")[0].innerHTML.replace(/\s+/g, '');
  var end_date_orginal = document.getElementsByClassName("end_date_orginal")[0].innerHTML.replace(/\s+/g, '');

  var CurrentDate = new Date();
  var x = new Date(d1);
  var y = new Date(d1);



  /* end Date less then start date error */
  if (start_date_orginal != d1 && end_date_orginal != d2) {
    /* start date less then current date */
    if (x.setHours(0, 0, 0, 0) < CurrentDate.setHours(0, 0, 0, 0)) {
      out.innerHTML = "Start date less then current date";
      return false;
    }

    if (y.setHours(0, 0, 0, 0) < CurrentDate.setHours(0, 0, 0, 0)) {
      out.innerHTML = "End date less then current date";
      return false;
    }


    if (d2 < d1) {
      out.innerHTML = "End date cannot be smaller then start date";
      return false;
    }
  }
  return true;
}

function checkTime(t1, t2, out) {

  /*  */
  if (t2 <= t1) {
    out.innerHTML = "Clossing time cannot be less then openning time"
    return false;
  }
  return true;

}
