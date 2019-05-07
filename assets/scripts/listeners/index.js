import {listen as listenMatches} from "./matches";
import {listen as listenHover} from "./hover";

export function listen() {
  listenMatches();
  listenHover();
}