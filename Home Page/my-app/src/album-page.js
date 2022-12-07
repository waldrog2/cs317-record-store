import React from "react";
import "./css/album-page.css";
import "./css/home-page-header.css";
export default function AlbumPage(props) {
  const { id } = useParams();
  const [albumData, updateAlbumData] = useState({});

  useEffect(() => {
    fetch("http://localhost:8044/api/album?id=" + id).then((res) =>
      res.json().then((data) => {
        updateAlbumData(data);
      })
    );
  }, []);

  const songs = albumList.songs.map((item) => {
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
              src={props.item.art_link}
              alt="Album Art"
            />
          </div>
          <div className="album-page-info">
            <p className="album-page-title">{props.item.title}</p>
            <p className="album-page-artist">{props.item.artist}</p>
            <p className="album--pagedate">{props.item.release_date}</p>
          </div>
        </div>

        <div className="song-list">
          <section className="song-list">{songs}</section>
        </div>
      </div>
    </div>
  );
}