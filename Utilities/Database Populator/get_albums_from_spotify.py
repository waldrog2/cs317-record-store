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


def ms_to_hms(ms):
    seconds = int((ms / 1000) % 60)
    minutes = int((ms / (1000 * 60)) % 60)
    return [minutes, seconds]


def load_input_file(filename):
    global genre_list
    with codecs.open(filename, "r","utf-8-sig") as fp:
        genre_list = json.load(fp)


def write_output_file(filename):
    global data_dump
    with codecs.open(filename, "w","utf-8-sig") as fp:
        json.dump(data_dump, fp, indent=2)


def get_album_info(album_id):
    album_info = sp.album(album_id)
    if "US" not in album_info["available_markets"]:
        return {}
    # elif "deluxe" in album_info["name"].lower():
    #     return {}
    # elif "expanded" in album_info['name'].lower():
    #     return {}
    else:
        return album_info


def get_album_track_info(album_data,debug=False):
    new_tracks_base = data_dump["genres"][-1]["subgenres"][-1]["artists"][-1]["albums"][-1]["tracks"]
    track_data = album_data["tracks"]
    for track in track_data["items"]:
        runtime = ms_to_hms(track['duration_ms'])
        formatted_runtime = f"{runtime[0]:02d}:{runtime[1]:02d}"
        if debug:
            print(f"Name: {track['name']}, Duration: {formatted_runtime}")
        new_tracks_base.append({"name": track['name'].replace(';',','), "duration": formatted_runtime})


def get_album_list(artist_name):
    artist = sp.search(q=artist_name, type="artist", limit=1)
    artist_id = artist["artists"]["items"][0]["id"]
    album_data = sp.artist_albums(artist_id=artist_id, album_type="album", limit=50)
    return album_data


def download_art_for_album(artist, album_name, url, debug=False):
    albums_base = data_dump["genres"][-1]["subgenres"][-1]["artists"][-1]["albums"]
    if debug:
        print(f"Unfixed Album Name: {album_name}")
    fixed_artist_name = artist
    fixed_album_name = album_name
    if not os.path.exists(f"./album_art/"):
        os.makedirs(f"./album_art/")
    response = requests.get(url)
    illegal_characters = [' ', '\"', '/', '\\', '*', '|', '<', '>',
                          ':','?']  # these characters cannot appear in paths on Windows
    if response.status_code == 200:
        for illegal_char in illegal_characters:
            fixed_artist_name = fixed_artist_name.replace(illegal_char, "_")
            fixed_album_name = fixed_album_name.replace(illegal_char, '_')
        fixed_artist_name = fixed_artist_name.lower()
        fixed_album_name = fixed_album_name.lower()
        albums_base[-1]["art_path"] = f"/var/www/album_art/{fixed_artist_name}_{fixed_album_name}.jpg"
        if debug:
            print(f"Fixed artist name: {fixed_artist_name}")
            print(f"Fixed album name: {fixed_album_name}")
            print(f"Saving art as: ./album_art/{fixed_artist_name}_{fixed_album_name}.jpg")
        with open(f"./album_art/{fixed_artist_name}_{fixed_album_name}.jpg", "wb") as fp:
            fp.write(response.content)
    else:
        print(f"Error getting art: {response.status_code}")


def get_albums_for_genre(genre, delay=5,debug=False):
    subgenres_root = data_dump["genres"][-1]["subgenres"]
    subgenres_root.append({"name": genre['name'], "artists": []})
    if debug:
        print(data_dump["genres"][-1].keys())
    new_genre_root = data_dump["genres"][-1]["subgenres"][-1]
    if debug:
        print(f"Getting Albums for Genre: {genre['name']}")
    for artist_name in genre["artists"]:
        new_artist_base = new_genre_root["artists"]
        artist_album_info = get_album_list(artist_name)
        new_artist_base.append({"name": artist_name, "albums": []})
        if debug:
            print(f"Artist: {artist_name}\n===============================")
        for album in artist_album_info["items"]:
            seen = False
            album_info = get_album_info(album['id'])
            if len(album_info) == 0:
                continue

            albums_base = data_dump["genres"][-1]["subgenres"][-1]["artists"][-1]["albums"]
            for existing_album in albums_base:
                if album['name'].lower() == existing_album['name'].lower():
                    seen = True
                else:
                    if debug:
                        print(f"Target: {repr(album['name'])}, Existing: {repr(existing_album['name'])}")
            if not seen:
                if len(album_info['release_date'].split('-')) != 3:
                    album_info['release_date'] = album_info['release_date'].split('-')[0] + "-01-01"
                albums_base.append(
                    {"name": album_info['name'].replace(';',','), "art_path": "", "release_date":album_info['release_date'],"tracks": []})
                if debug:
                    print(f"Album Name: {albums_base[-1]['name']}")
                get_album_track_info(album_info)
                download_art_for_album(artist_name, albums_base[-1]['name'], album_info['images'][0]['url'],debug=False)
                if debug:
                    print("----------------------------")
                    print(f"Waiting {delay} Seconds...")
        sleep(delay)


@click.command()
@click.option('--input_file', default="input.json", help="Path to the input JSON file")
@click.option('--output_file', default="output.json", help="Path to the output JSON file")
def download(input_file, output_file):
    if not os.path.exists(input_file):
        print(f"Input File {input_file} does not exist")
        return
    load_input_file(input_file)
    for genre in genre_list["genres"]:
        data_dump["genres"].append({"name":genre["name"],"subgenres":[]})

        for subgenre in genre["subgenres"]:
            get_albums_for_genre(subgenre, 3,debug=False)
    write_output_file(output_file)


if __name__ == '__main__':
    download()
