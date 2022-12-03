import "./css/album-style.css";
import React from "react";
// import flowerBoy from "./images/flowerBoy.jpg";
// import tylerPicture from "./images/tyler.bmp";

export default function Album(props) {

  // console.log("Album Data: " + props.item.title);
  return (
      <div className="div-album">
        <div className="album-cover-row">
          {/*<img src={flowerBoy} className="album-cover" alt="flowerBoy" />*/}
          <div className="album-cost">$19.99</div>
        </div>
        <div className="album-info-grid">
          <div className="icon-picture">
              <a href={props.item.album_full_link}>
            <img className="album-cover" src={props.item.art_link} alt="Album Art" />
              </a>
          </div>

          <div className="album-info">
            <p className="album-title">{props.item.title}</p>
            <p className="album-artist">{props.item.artist}</p>
            <p className="album-date">{props.item.release_date}</p>
          </div>
        </div>
      </div>
  );
}
