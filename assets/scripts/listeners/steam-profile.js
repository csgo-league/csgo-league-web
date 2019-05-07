import axios from "axios";

const parser = new DOMParser();

export function listen() {
  $('.steam-profile').each(v => {
    v = $(v);
    let steamId = v.attr('id');

    updateSteamProfile(v, steamId);
  });
}

const updateSteamProfile = (element, steam) => {
  axios.get(`https://cors-anywhere.herokuapp.com/https://steamcommunity.com/profiles/${steam}?xml=true`)
    .then(response => {
      let xmlDoc;

      if (window.DOMParser)
      {
        xmlDoc = parser.parseFromString(response.data, 'text/xml');
      }
      else // Internet Explorer
      {
        xmlDoc = new ActiveXObject('Microsoft.XMLDOM');
        xmlDoc.async = false;
        xmlDoc.loadXML(response.data);
      }

      console.log(response);
      console.log(xmlDoc);

      let name = xmlDoc.getElementsByTagName('steamID')[0].childNodes[0].nodeValue;
      let avatar = xmlDoc.getElementsByTagName('avatarFull')[0].childNodes[0].nodeValue;

      element.find('.steam-profile-name').val(name);
      element.find('.steam-profile-avatar').attr('src', avatar);
    });
};

// getSteamProfile('76561198028510846');