function onLoad() {
  checkWaitingList();

  checkIfNewMessages();
  setInterval(checkIfNewMessages, 1000);

}

function checkWaitingList() {

  var fairInfo = document.getElementsByClassName('open_spot');

  if (fairInfo.length != null) {
    var list = createListOfOpenSpotWaitingList(fairInfo);

    list.forEach(element => {
      if (element['open'] > 0) {
        var msg = alertify.warning();
        msg.delay(5).setContent(element['fairTitle'] + ' has ' + element['open'] + ' open spots!');
      }
    });
  }
}

function createListOfOpenSpotWaitingList(fairInfo) {
  var list = [];
  var i = 0;
  while (i < fairInfo.length) {
    if (i % 3 == 0) {
      var j = i;
      var out = {
        'fairTitle': fairInfo[j].innerHTML,
        'zoneTitle': fairInfo[j + 1].innerHTML,
        'open': fairInfo[j + 2].innerHTML,
      }
      list.push(out);
    }
    i++;
  }

  return list;
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
        var msg = alertify.warning('Default message');
        msg.delay(5).setContent(element['msgCount'] + ' new messages from ' + element['msgFrom']);
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
