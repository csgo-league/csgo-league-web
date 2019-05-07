import {listen as listenMatches} from "./matches";
import {listen as listenHover} from "./hover";
import {listen as listenSteamProfile} from "./steam-profile";

export function listen() {
  listenMatches();
  listenHover();
  listenSteamProfile();
}