import "./css/home-page-header.css";
import "./css/home-page.css";
import "./css/home-page-content.css";
import StaticPage from "./static";
import Album from "./album-grid";
import React,{useState,useEffect} from 'react';
import { BrowserRouter, Link, Route, Switch  } from 'react-router-dom';
// import albumData from "./data.js";

function App() {
    const [albumList,updateAlbumList] = useState([]);

    useEffect(() => {
            fetch('http://localhost:8044/api/gridpage').then(
                res => res.json().then(
                    data => {
                        updateAlbumList(data.entries)
                    }
                )
            );
    },[]);


  const albums = albumList.map((item) => {
      return <Album key={item.title} item={item}/>;
  });

  return (
    <div className="App">

        <BrowserRouter>
            <Switch >
                <Route exact path="/">
                    <>
                        <StaticPage />
                        <section className="album-list">
                            {albums}
                        </section>
                    </>
                </Route>

                <Route path="/album/:id">
                    <p>Album Page</p>
                </Route>

                <Route path="/search">
                    <>
                        <h1>Search Results</h1>
                        <p>Search Page</p>
                    </>

                </Route>

            </Switch >

        </BrowserRouter>
    </div>
  );
}

export default App;
