// https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_js_dropdown

function onLoad() {
  checkIfNewMessages();
  setInterval(checkIfNewMessages, 1000);
}

/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}


/**
 * Check every 5sec if there are new messages through AJAX request
 */
function checkIfNewMessages() {

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      msg = JSON.parse(this.responseText);
      msg.forEach(element => {
        // var msg = alertify.warning();
        // msg.delay(4).setContent(element['msgCount'] + ' new messages from <a  href="message.php?msgTo=' + element['user_id'] + '"><b  id="linkinnotification" >' + element['msgFrom'] + '</b></a>');

        var d = document.createElement('div');
        d.className = "msg";
        d.innerHTML =element['msgCount'] + ' new messages from <a  href="message.php?msgTo=' + element['user_id'] + '&opened=true"><b  id="linkinnotification" >' + element['msgFrom'] + '</b></a>';

        var alert_area = document.getElementById("alert-area");
        alert_area.appendChild(d);
      });
    }
  };
  xmlhttp.open(
    "GET",
    "../../../server/dashboard/visitor/message.php",
    true
  );
  xmlhttp.send();
}
