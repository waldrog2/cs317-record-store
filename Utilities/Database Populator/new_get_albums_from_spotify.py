import spotipy
from spotipy.oauth2 import SpotifyClientCredentials
import json
from time import sleep
import os
import requests
import click
import codecs

auth_manager = SpotifyClientCredentials(client_id="3330d930a2944181a3b7ce0e8616aa8d",
                                        client_secret="784256d0dbd24727bf3ef66e06b2c255")
sp = spotipy.Spotify(auth_manager=auth_manager)

data_dump = {"genres": []}
genre_list = {}
genres_root = data_dump["genres"]

album_art_list = []

album_art_db = {}
album_db = {}

def load_input_file(filename):
    global genre_list
    with codecs.open(filename, "r","utf-8-sig") as fp:
        genre_list = json.load(fp)


def write_output_file(filename):
    global data_dump
    with codecs.open(filename, "w","utf-8-sig") as fp:
        json.dump(data_dump, fp, indent=2)

def escape_windows_chars(path):
    fixed_path = path
    illegal_characters = [' ', '\"', '/', '\\', '*', '|', '<', '>',
                          ':','?']  # these characters cannot appear in paths on Windows
    for illegal_char in illegal_characters:
        fixed_path = fixed_path.replace(illegal_char, "_")
    return fixed_path

def ms_to_hms(ms):
    seconds = int((ms / 1000) % 60)
    minutes = int((ms / (1000 * 60)) % 60)
    return [minutes, seconds]


def download_all_art():
    if not os.path.exists(f"./album_art/"):
        os.makedirs(f"./album_art/")
    for art in album_art_db.keys():
        keyparts = art.split("||")
        response = requests.get(album_art_db[art][0])
        if response.status_code == 200:
            escaped_artist_name = escape_windows_chars(keyparts[0])
            escaped_album_name = escape_windows_chars(keyparts[1])
            server_path = f"/var/www/album_art/{escaped_artist_name}_{escaped_album_name}.jpg"
            path = f"./album_art/{escaped_artist_name}_{escaped_album_name}.jpg"
            album_art_db[art].append(server_path)
            with open(path, "wb") as fp:
                fp.write(response.content)

def get_album_data(album_id):
    album_info = sp.album(album_id)
    if "US" not in album_info["available_markets"]:
        return # there are some non-US only records, we won't include those since no one could buy them
    # print(album_info)
    album_db_key = f"{album_info['name']}||{album_info['artists'][0]['name']}"
    album_db[album_db_key] = []
    # print(album_info['tracks'])
    for track in album_info['tracks']['items']:
        # print(track)
        runtime = ms_to_hms(track['duration_ms'])
        formatted_runtime = f"{runtime[0]:02d}:{runtime[1]:02d}"
        album_db[album_db_key].append(track['name'])
        album_db[album_db_key].append(formatted_runtime)
        album_art_db[album_db_key] = [album_info['images'][0]['url']]

def get_artist_data(artist_name):
    artist = sp.search(q=artist_name, type="artist", limit=1)
    artist_id = artist["artists"]["items"][0]["id"]
    album_list = sp.artist_albums(artist_id=artist_id, album_type="album", limit=50)['items']
    for album in album_list:
        get_album_data(album["id"])


load_input_file("input.json")
for genre in genre_list["genres"]:
    data_dump["genres"].append(genre['name'])
    for subgenre in genre['subgenres']:
        data_dump['genres'][-1] = {"name":subgenre['name'],"artists":[]}
        for artist in subgenre['artists']:
            get_artist_data("Rage Against The Machine")

download_all_art()


# print(json.dumps(album_art_db,indent=4))
