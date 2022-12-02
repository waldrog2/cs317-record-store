import "./header-styles/home-page-header.css";
import "./home-page-content/home-page.css";
import "./home-page-content/home-page-content.css";
import StaticPage from "./static";
import Card from "./album-grid";

function App() {
  return (
    <div className="App">
      <StaticPage />
      <Card />
    </div>
  );
}

export default App;
