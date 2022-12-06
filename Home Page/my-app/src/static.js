import "./css/home-page.css";
import "./css/home-page-content.css";
import "./css/home-page-header.css";
import logo from "./images/logo.png";
import shoppingBag from "./images/shopping_bag.svg";
// import flipping from "./images/flipping.jpg";
// import homePageRecord from "./images/homePageRecord.jpg";
// import flipping2 from "./images/flipping2.jpg";
// import davidBowie from "./images/davidBowie.jpg";

import React, { useState, useEffect } from "react";
function StaticPage() {
  const [featuredAlbum, updateFeaturedAlbum] = useState([]);
  useEffect(function effectFunction() {
    async function fetchHomepage() {
      const response = await fetch("http://localhost:8044/api/homepage");
      const json = await response.json();
      updateFeaturedAlbum(json.featured_album);
    }
    fetchHomepage();
  }, []);

  return (
    <div className="App">
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
      <div className="home-page-content">
        <div className="welcome-message">
          <div className="welcome-images">
            {/*<img*/}
            {/*  class="welcome-image-one"*/}
            {/*  // src={featuredAlbum.art_link}*/}
            {/*  alt="imageOne"*/}
            {/*/>*/}
            {/*<img class="welcome-image-two" src={flipping2} alt="imageTwo" />*/}
            {/*<img class="welcome-image-three" src={flipping} alt="imageThree" />*/}
          </div>
          <p className="welcome">Welcome to Eagle Records</p>
          <p className="come-in">come on in</p>
        </div>

        <h2 className="topPick">FEATURED ALBUM</h2>

        <div className="top-pick-container">
          <div className="top-pick-album">
            <img src={featuredAlbum.art_link} alt="albumArt" />
          </div>

          <div className="top-pick-info">
            <div>
              <u>
                <p className="album-title">{featuredAlbum.title}</p>
              </u>
            </div>
            <div>
              <p className="artist">{featuredAlbum.artist}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default StaticPage;
