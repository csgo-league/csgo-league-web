export function listen() {
  $('[data-bs-hover-animate]')
    .mouseenter(() => {
      let elem = $(this); elem.addClass('animated ' + elem.attr('data-bs-hover-animate'));
    })
    .mouseleave(() => {
      let elem = $(this); elem.removeClass('animated ' + elem.attr('data-bs-hover-animate'));
    });
}