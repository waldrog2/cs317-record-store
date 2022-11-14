import json
import codecs
migration = ""
with codecs.open("output.json", "r","utf-8-sig") as fp:
    data_dump = json.load(fp)

genre_sql = "INSERT INTO Genre (genre_name,subgenre_name) VALUES (\"{}\",\"{}\");\n"
artist_sql = "INSERT INTO Artist (artist_name,genre_id,description) VALUES (\"{}\",{},\"\");\n"
album_art_sql = "INSERT INTO AlbumArt(path) VALUES (\"{}\");\n"
album_song_sql = "INSERT INTO AlbumSong (album_id,song_id,format_id) VALUES({},{},{});\n"
album_sql = "INSERT INTO Album(album_id,album_name,artist_id,art_id,release_date) VALUES ({},\"{}\",{},{},\"{}\");\n"
song_sql = "INSERT INTO Song (song_name,artist_id,duration) VALUES (\"{}\",{},\"{}\");\n"
format_sql = "INSERT INTO Format (format_name) VALUES (\"{}\");\n"

genre_id = 1
artist_id = 1
art_id = 1
album_id = 1
song_id = 1

migration += format_sql.format("Vinyl")
migration += format_sql.format("CD")
seen_tracks = []
for genre in data_dump["genres"]:
    for subgenre in genre["subgenres"]:
        query = genre_sql.format(genre["name"], subgenre["name"])
        print(f"Genre ID: {genre_id}")
        migration += query
        for artist in subgenre["artists"]:
            print(f"Artist: {artist['name']}")
            print(f"Artist ID: {artist_id}")
            migration += artist_sql.format(artist["name"], genre_id)
            for album in artist["albums"]:
                album['name'] = album['name'].replace('\"', '').replace(";",",")
                print(f"Album: {album['name']}")
                print(f"Album ID: {album_id}")
                migration += album_art_sql.format(album["art_path"])
                print(f"Art ID: {art_id}")
                for track in album["tracks"]:
                    target_song_id = song_id
                    seen = False
                    for t in seen_tracks:
                        if t["name"] == track['name'] and t['artist'] == artist['name'] and t['duration'] == track["duration"]:
                            target_song_id = t['song_id']
                            seen = True
                    if not seen:
                        duration_parts = track['duration'].split(':')
                        if int(duration_parts[0]) > 59:
                            duration = str(int(duration_parts[0] % 60)) + ":" + track['duration']
                        else:
                            duration = f"00:{track['duration']}"
                        migration += song_sql.format(track['name'].replace('\"',''), artist_id, duration)
                        seen_tracks.append(
                            {'name': track['name'], 'artist': artist['name'], 'duration': track['duration'],
                             'song_id': song_id})
                        song_id += 1
                    migration += album_song_sql.format(album_id, target_song_id,1)
                    migration += album_song_sql.format(album_id, target_song_id,2)
                migration += album_sql.format(album_id,album['name'],artist_id,art_id,album['release_date'])
                art_id += 1
                album_id += 1
            artist_id += 1
        genre_id += 1



with codecs.open("up.sql", "w","utf-8-sig") as fp:
    fp.write(migration)
