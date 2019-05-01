function formatAMPM(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  return hours + ':' + minutes + hours >= 12 ? 'pm' : 'am';
}

$(document).ready(function() {
  $('.match').each(function(i, el) {
    el = $(el);
    var matchDate = $(el.find('.timestamp')[0]);
    var timestamp = parseInt(matchDate.prop('innerHTML'));
    var date = new Date(timestamp * 1000);
    matchDate.prop('innerHTML', date.toLocaleDateString() + ' ' + formatAMPM(date));
    el.removeClass('d-none');
  });
});