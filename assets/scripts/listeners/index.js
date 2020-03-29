import {listen as listenMatches} from "./matches";
import {listen as listenHover} from "./hover";
import {listen as listenSteamProfile} from "./steam-profile";
import {listen as listenProfile} from "./profile";

export function listen() {
  listenMatches();
  listenHover();
  listenSteamProfile();
  listenProfile();
}