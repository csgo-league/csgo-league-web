import axios from "axios";

export function listen() {
  $('.steam-profile').each((k, v) => {
    v = $(v);

    let steamId = v.attr('id');

    updateSteamProfile(v, steamId);
  });
}

const updateSteamProfile = (element, steam) => {
  axios.get(`https://cors-anywhere.herokuapp.com/https://steamcommunity.com/profiles/${steam}?xml=true`)
    .then(response => {
      let profileXML;

      if (window.DOMParser)
      {
        const parser = new DOMParser();
        profileXML = parser.parseFromString(response.data, 'text/xml');
      }
      else // Internet Explorer
      {
        profileXML = new ActiveXObject('Microsoft.XMLDOM');
        profileXML.async = false;
        profileXML.loadXML(response.data);
      }

      let name = profileXML.getElementsByTagName('steamID')[0].childNodes[0].nodeValue;
      let avatar = profileXML.getElementsByTagName('avatarFull')[0].childNodes[0].nodeValue;

      element.find('.steam-profile-name').text(name);
      element.find('.steam-profile-avatar').attr('src', avatar);
    });
};

// getSteamProfile('76561198028510846');