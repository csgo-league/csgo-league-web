$(document).ready(function() {
  $('.match').each(function(i, el) {
    el = $(el);
    var matchDate = $(el.find('.timestamp')[0]);
    var timestamp = parseInt(matchDate.prop('innerHTML'));
    var date = new Date(timestamp * 1000);
    matchDate.prop('innerHTML', date.toLocaleDateString() + ' ' + date.getHours() + ':' + date.getMinutes());
    el.removeClass('d-none');
  });
});