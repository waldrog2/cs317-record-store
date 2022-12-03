import "./header-styles/home-page-header.css";
import "./home-page-content/home-page.css";
import "./home-page-content/home-page-content.css";
import StaticPage from "./static";
import Album from "./album-grid";
import albumData from "./data.js";

function App() {
    console.log("Album Data:" + albumData);
    // const albums = {};
  const albums = albumData.new_releases.map((item) => {
      console.log(item);
    return <Album key={item.id} item={item} />;
  });
  return (
    <div className="App">
      <StaticPage />
      <section className="album-list">{albums}</section>
    </div>
  );
}

export default App;
