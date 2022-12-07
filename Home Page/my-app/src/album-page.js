import {useParams} from "react-router-dom";
import React, { useState, useEffect } from "react";
import TrackList from "./tracklist";
import "./css/album-page.css";
import "./css/home-page-header.css";
import logo from "./images/logo.png";
import shoppingBag from "./images/shopping_bag.svg";
export default function AlbumPage(props) {
  const { id } = useParams();
  const [albumData, updateAlbumData] = useState({songs:[]});

  useEffect(() => {
    fetch("http://localhost:8044/api/album?id=" + id).then((res) =>
      res.json().then((data) => {

        updateAlbumData(data);
      })
    );
  }, []);

  console.log("Data: " + JSON.stringify(albumData));
  console.log("Props: " + JSON.stringify(props));
  const songs = albumData.songs.map((item) => {
    return <TrackList key={item.name} item={item} />;
  });
  return (
    <div>
      <nav className="header">
        <div className="left-section">
          <img src={logo} alt="logo" />
          <p>Eagle Records</p>
        </div>

        <div className="middle-section">
          <input className="search-bar" type="text" placeholder="Search" />
        </div>

        <div className="right-section">
          <button className="shopping-bag">
            <img
              className="shopping-bag-icon"
              src={shoppingBag}
              alt="shopping-bag"
            />
          </button>
        </div>
      </nav>
      <div className="container">
        <div className="album-page">
          <div className="album-page-image">
            <img
              className="album-page-cover"
              src={albumData.art_link}
              alt="Album Art"
            />
          </div>
          <div className="album-page-info">
            <p className="album-page-title">{albumData.album_name}</p>
            <p className="album-page-artist">{albumData.artist}</p>
            <p className="album--pagedate">{albumData.release_date}</p>
          </div>
        </div>

        <div className="song-list">
          <section className="song-list">{songs}</section>
        </div>
      </div>
    </div>
  );
}