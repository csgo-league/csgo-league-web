$(document).ready(() => {
  $('.match').each((i, el) => {
    el = $(el);
    var matchDate = $(el.find('.timestamp')[0]);
    var timestamp = parseInt(matchDate.prop('innerHTML'));
    var date = new Date(timestamp * 1000);
    var hours = date.getHours();
    hours = ('0' + hours).slice(-2);
    var minutes = date.getMinutes();
    minutes = ('0' + minutes).slice(-2);
    matchDate.prop('innerHTML', date.toLocaleDateString() + ' ' + hours + ':' + minutes);
    el.removeClass('d-none');
  });

  function getSteamProfile(steam) {
    if (window.DOMParser)
    {
      parser = new DOMParser();
      xmlDoc = parser.parseFromString(txt, "text/xml");
    }
    else // Internet Explorer
    {
      xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
      xmlDoc.async = false;
      xmlDoc.loadXML(txt);
    }
  }
});