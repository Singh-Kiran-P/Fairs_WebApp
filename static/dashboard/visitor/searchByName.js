function showResult(str) {
  if (str.length == 0) {
    document.getElementById("livesearch").innerHTML = "";
    return;
  }
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById("livesearch").innerHTML = this.responseText;
    }
  };
  xmlhttp.open(
    "GET",
    "../../../server/dashboard/visitor/searchByNameRequest.php?title=" + str,
    true
  );
  xmlhttp.send();
}
