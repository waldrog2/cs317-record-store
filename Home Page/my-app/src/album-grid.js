import "./css/album-style.css";
import React from "react";
import { Link } from "react-router-dom";
// import flowerBoy from "./images/flowerBoy.jpg";
// import tylerPicture from "./images/tyler.bmp";

export default function Album(props) {


  console.log("Album Data: " + JSON.stringify(props));

  return (
    <div className="div-album">
      <div className="album-cover-row">
        <div className="album-cost">$19.99</div>
      </div>
      <div className="album-info-grid">
        <div className="icon-picture">
          <Link to={props.item.album_full_link}>
            <img
              className="album-cover"
              src={props.item.art_link}
              alt="Album Art"
            />
          </Link>
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
