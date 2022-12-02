import "./header-styles/home-page-header.css";
import "./home-page-content/home-page.css";
import "./home-page-content/home-page-content.css";
import StaticPage from "./static";
import Album from "./album-grid";
import albumData from "./data.js";

function App() {
  const albums = albumData.map((item) => {
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
