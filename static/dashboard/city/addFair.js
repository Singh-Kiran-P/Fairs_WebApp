
function validateForm() {
  var startDate = document.getElementsByName("startDate")[0].value;
  var endDate = document.getElementsByName("endDate")[0].value;
  var openingHour = document.getElementsByName("openingHour")[0].value;
  var closingHour = document.getElementsByName("closingHour")[0].value;
  var error = document.getElementById("error");

  return checkDate(startDate, endDate, error) && checkTime(openingHour, closingHour, error);
}

function checkDate(d1, d2, out) {
  var CurrentDate = new Date();
  var x = new Date(d1);

  /* start date less then current date */
  if(x.setHours(0,0,0,0) <CurrentDate.setHours(0,0,0,0)){
    out.innerHTML = "Start date less then current date";
    return false;
  }

  /* end Date less then start date error */
  if (d2 < d1) {
    out.innerHTML = "End date cannot be smaller then start date";
    return false;
  }

  return true;
}

function checkTime(t1, t2, out) {

  /*  */
  if(t2<=t1){
    out.innerHTML = "Clossing time cannot be less then openning time"
    return false;
  }
  return true;

}
