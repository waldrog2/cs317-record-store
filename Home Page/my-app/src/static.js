import "./css/home-page-header.css";
import "./css/home-page.css";
import "./css/home-page-content.css";

import logo from "./images/logo.png";
import shoppingBag from "./images/shopping_bag.svg";
// import flipping from "./images/flipping.jpg";
// import homePageRecord from "./images/homePageRecord.jpg";
// import flipping2 from "./images/flipping2.jpg";
// import davidBowie from "./images/davidBowie.jpg";

function StaticPage() {
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
            {/*  src={homePageRecord}*/}
            {/*  alt="imageOne"*/}
            {/*/>*/}
            {/*<img class="welcome-image-two" src={flipping2} alt="imageTwo" />*/}
            {/*<img class="welcome-image-three" src={flipping} alt="imageThree" />*/}
          </div>
          <p className="welcome">Welcome to Eagle Records</p>
          <p className="come-in">come on in</p>
        </div>

        <p className="topPick">TOP PICK</p>

        <div className="top-pick-container">
          <div className="top-pick-album">
            {/*<img src={davidBowie} alt="davidBowie" />*/}
          </div>

          <div className="top-pick-info">
            <div>
              <u>
                <p className="album-title">Heroes</p>
              </u>
            </div>
            <div>
              <p className="artist">David Bowie</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default StaticPage;
