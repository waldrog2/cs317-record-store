import "./header-styles/home-page-header.css";
import "./home-page-content/home-page.css";
import "./home-page-content/home-page-content.css";

import logo from "./images/logo.png";
import shoppingBag from "./images/shopping_bag.svg";
import flipping from "./images/flipping.jpg";
import homePageRecord from "./images/homePageRecord.jpg";
import flipping2 from "./images/flipping2.jpg";
import davidBowie from "./images/davidBowie.jpg";

function App() {
  return (
    <div class="App">
      <nav class="header">
        <div class="left-section">
          <img src={logo} alt="logo" />
          <p>Eagle Records</p>
        </div>

        <div class="middle-section">
          <input class="search-bar" type="text" placeholder="Search" />
        </div>

        <div class="right-section">
          <button class="shopping-bag">
            <img
              class="shopping-bag-icon"
              src={shoppingBag}
              alt="shopping-bag"
            />
          </button>
        </div>
      </nav>

      <div class="home-page-content">
        <div class="welcome-message">
          <div class="welcome-images">
            <img
              class="welcome-image-one"
              src={homePageRecord}
              alt="imageOne"
            />
            <img class="welcome-image-two" src={flipping2} alt="imageTwo" />
            <img class="welcome-image-three" src={flipping} alt="imageThree" />
          </div>
          <p class="welcome">Welcome to Eagle Records</p>
          <p class="come-in">come on in</p>
        </div>

        <p class="topPick">TOP PICK</p>

        <div class="top-pick-container">
          <div class="top-pick-album">
            <img src={davidBowie} alt="davidBowie" />
          </div>

          <div class="top-pick-info">
            <div>
              <u>
                <p class="album-title">Heroes</p>
              </u>
            </div>
            <div>
              <p class="artist">David Bowie</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default App;
