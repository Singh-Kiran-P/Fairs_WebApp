function onLoad() {
  checkWaitingList();
}

function checkWaitingList() {

  var fairInfo = document.getElementsByClassName('open_spot');

  if (fairInfo.length != null) {
    var list = createListOfOpenSpotWaitingList(fairInfo);

    list.forEach(element => {
      if (element['open'] > 0) {
        alertify.warning(element['fairTitle'] + ' has ' + element['open'] + ' open spots!');
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
