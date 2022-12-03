import "./album-grid-styles/album-style.css";
import React from "react";
import flowerBoy from "./images/flowerBoy.jpg";
import tylerPicture from "./images/tyler.bmp";

export default function Album(props) {
  console.log(props);
  return (
    <div className="album-grid">
      <div className="div-album">
        <div className="album-cover-row">
          <img src={flowerBoy} className="album-cover" alt="flowerBoy" />
          <div className="album-cost">$19.99</div>
        </div>
        <div className="album-info-grid">
          <div className="icon-picture">
            <img className="icon" src={tylerPicture} alt="tyler" />
          </div>

          <div className="album-info">
            <p className="album-title">({props.new_releases.title})</p>
            <p className="album-artist">(props.items.new_rea)</p>
            <p className="album-date">July 21, 2017</p>
          </div>
        </div>
      </div>
    </div>
  );
}
