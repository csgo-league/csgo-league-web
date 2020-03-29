function secondsToReadable(seconds) {
  seconds = Number(seconds);
  const h = Math.floor(seconds / 3600);

  if (seconds < 60) {
    return "None";
  }

  const hDisplay = h > 0 ? h + (h === 1 ? " h" : " h") : "";

  if (hDisplay !== '') {
    return hDisplay
  }

  const m = Math.floor(seconds % 3600 / 60);
  return m > 0 ? m + (m === 1 ? " m" : " m") : "";
}

export function listen() {
  $('.seconds-to-readable').each((k, v) => {
    v = $(v);

    v.html(secondsToReadable(v.html()));
  });
}
