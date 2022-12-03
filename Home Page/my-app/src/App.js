import "./css/home-page-header.css";
import "./css/home-page.css";
import "./css/home-page-content.css";
import StaticPage from "./static";
import Album from "./album-grid";
import React,{useState,useEffect} from 'react';
// import albumData from "./data.js";

function App() {
    const [albumList,updateAlbumList] = useState([]);
    useEffect(function effectFunction() {
        async function fetchGridPage()
        {
            const response = await fetch('http://localhost:8044/api/gridpage');
            const json = await response.json();
            updateAlbumList(json.entries);
        }
        fetchGridPage();
    },[]);
    // console.log(albumData);
    // console.log(albumData);

  const albums = albumList.map((item) => {
      return <Album key={item.title} item={item}/>;
  });

  return (
    <div className="App">
      <StaticPage />
      <section className="album-list">{albums}</section>
    </div>
  );
}

export default App;
