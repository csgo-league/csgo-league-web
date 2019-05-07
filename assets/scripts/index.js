import {listen} from "./listeners";
import axios from "axios";

listen();

const parser = new DOMParser();

const getSteamProfile = steam => {
  axios.get(`https://steamcommunity.com/profiles/${steam}?xml=true`)
    .then(response => {
      let xmlDoc;

      if (window.DOMParser)
      {
        xmlDoc = parser.parseFromString(txt, 'text/xml');
      }
      else // Internet Explorer
      {
        xmlDoc = new ActiveXObject('Microsoft.XMLDOM');
        xmlDoc.async = false;
        xmlDoc.loadXML(response);
      }

      console.log(xmlDoc);
    });
};

getSteamProfile('76561198028510846');