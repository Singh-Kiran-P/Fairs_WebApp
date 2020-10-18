function checkPassword() {
  var p1 = document.getElementsByName("password")[0].value;
  var p2 = document.getElementsByName("password2")[0].value;
  var outError = document.getElementById("error");
  if (p1 != p2) {
    outError.innerHTML = "Passwords do not match!!";
    return false;
  }
  outError.innerHTML = "";

  return true;
}

function allReadyExits() {
  if (checkPassword()) {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = ShowResult;
    xmlhttp.open("POST", "../../server/auth/register.php", true);
    xmlhttp.setRequestHeader("Content-Type", "application/json; charset=UTF-8");

    var username = document.getElementsByName("username")[0].value;
    var password = document.getElementsByName("password")[0].value;
    var email = document.getElementsByName("email")[0].value;

    var data = { username: username, password: password, email: email };

    xmlhttp.send(JSON.stringify(data));

    function ShowResult() {
      if (xmlhttp && xmlhttp.readyState == 4 && xmlhttp.status == 200) {
        var userdata = JSON.parse(xmlhttp.responseText);
        document.getElementById("error").innerHTML = userdata.msg;
      }
    }
  }
}
