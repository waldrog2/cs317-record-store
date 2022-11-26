# import json
# import codecs
# import random

# migration = ""
# with codecs.open("output.json", "r","utf-8-sig") as fp:
#     data_dump = json.load(fp)

# genre_sql = "-- INSERTING GENRE\n INSERT INTO Genre (genre_name) VALUES (\"{}\");\n"
# artist_sql = "--INSERTING ARTIST\n INSERT INTO Artist (artist_name,description) VALUES (\"{}\",\"\");\n"
# album_art_sql = "-- INSERTING ALBUM ART\n INSERT INTO AlbumArt(path) VALUES (\"{}\");\n"
# album_song_sql = "-- INSERTING ALBUM->SONG\n INSERT INTO AlbumSong (album_id,song_id,format_id) VALUES({},{},{});\n"
# album_sql = "-- INSERTING INTO ALBUM\n INSERT INTO Album(album_id,album_name,artist_id,genre_id,subgenre_id,art_id,release_date) VALUES ({},\"{}\",{},{},{},{},\"{}\");\n"
# song_sql = "-- INSERTING INTO SONG\n INSERT INTO Song (song_name,artist_id,duration) VALUES (\"{}\",{},\"{}\");\n"
# format_sql = "-- INSERTING INTO FORMAT\n INSERT INTO Format (format_name) VALUES (\"{}\");\n"
# inventory_sql = "INSERT INTO Inventory (album_id,price,stock) VALUES ({},{},{});\n"
# genre_id = 1
# subgenre_id = 2
# artist_id = 1
# art_id = 1
# album_id = 1
# song_id = 1

# migration_lines = []
# migration_lines.append(format_sql.format("Vinyl"))
# migration_lines.append(format_sql.format("CD"))
# seen_tracks = {}
# for genre in data_dump["genres"]:
#     migration_lines.append(genre_sql.format(genre["name"]))
#     for subgenre in genre["subgenres"]:
#         migration_lines.append(genre_sql.format(subgenre['name']))
#         # print(f"Genre ID: {genre_id}")
#         for artist in subgenre["artists"]:
#             # print(f"Artist: {artist['name']}")
#             # print(f"Artist ID: {artist_id}")
#             migration_lines.append(artist_sql.format(artist["name"]))
#             for album in artist["albums"]:
#                 migration_lines.append("-- START OF ALBUM TRANSACTION\n")
#                 album['name'] = album['name'].replace('\"', '').replace(";",",")
#                 # print(f"Album: {album['name']}")
#                 # print(f"Album ID: {album_id}")
#                 migration_lines.append(album_art_sql.format(album["art_path"]))
#                 # print(f"Art ID: {art_id}")
#                 for track in album["tracks"]:
#                     target_song_id = song_id
#                     duration_parts = track['duration'].split(':')
#                     if int(duration_parts[0]) > 59:
#                         duration = str(int(duration_parts[0] % 60)) + ":" + track['duration']
#                     else:
#                         duration = f"00:{track['duration']}"

#                     seen = False
#                     if track['name'] in seen_tracks:
#                         if seen_tracks[track['name']][0] == artist['name'] and seen_tracks[track['name']][1] == duration:
#                             print(f"Found song {track['name']} already in DB with id {seen_tracks[track['name']][2]}")
#                             target_song_id = seen_tracks[track['name']][2]
#                             seen = True
#                     if not seen:
#                         print(f"Found new song: name: {track['name']}, artist: {artist['name']}, duration: {duration}")
#                         query = song_sql.format(track['name'].replace('\"',''), artist_id, duration)
#                         # if query not in migration_lines:
#                         migration_lines.append(query)
#                         seen_tracks[track['name']] = [artist['name'],duration,song_id]
#                         song_id += 1
#                     migration_lines.append("-- START OF ALBUM->SONG TRANSACTION\n")
#                     migration_lines.append(album_song_sql.format(album_id, target_song_id,1))
#                     migration_lines.append(album_song_sql.format(album_id, target_song_id,2))
#                     migration_lines.append("-- END OF ALBUM->SONG TRANSACTION\n")
#                 migration_lines.append(album_sql.format(album_id,album['name'],artist_id,genre_id,subgenre_id,art_id,album['release_date']))
#                 migration_lines.append(inventory_sql.format(album_id,12,random.randint(1,6)))
#                 migration_lines.append(inventory_sql.format(album_id,8,random.randint(1,6)))
#                 art_id += 1
#                 album_id += 1
#                 migration_lines.append("-- END OF ALBUM TRANSACTION\n")
#             artist_id += 1
#         subgenre_id += 1
#     genre_id += 1


# with codecs.open("up.sql", "w","utf-8-sig") as fp:
#     for line in migration_lines:
#         fp.write(line)


import json
import codecs
import random

migration = ""
with codecs.open("output.json", "r","utf-8-sig") as fp:
    data_dump = json.load(fp)

migration_lines = []
genre_sql = "INSERT INTO Genre (genre_name) VALUES (\"{}\");\n"
artist_sql = "INSERT INTO Artist (artist_name,description) VALUES (\"{}\",\"\");\n"
album_art_sql = "INSERT INTO AlbumArt(path) VALUES (\"{}\");\n"
album_song_sql = "INSERT INTO AlbumSong (album_id,song_id,format_id) VALUES({},{},{});\n"
album_sql = "INSERT INTO Album(album_name,artist_id,genre_id,subgenre_id,art_id,release_date) VALUES (\"{}\",{},{},{},{},\"{}\");\n"
song_sql = "INSERT INTO Song (song_name,artist_id,duration) VALUES (\"{}\",{},\"{}\");\n"
format_sql = "INSERT INTO Format (format_name) VALUES (\"{}\");\n"
inventory_sql = "INSERT INTO Inventory (album_id,price,stock) VALUES ({},{},{});\n"

genre_id = 1
subgenre_id = 1
artist_id = 1
art_id = 1
album_id = 1
song_id = 1

genre_db = {}
subgenre_db = {}
artist_db = {}
album_db = {}
song_db = {}
albumsong_db = {}
seen_tracks = {}
album_db = {}
art_db = {}
artist_id_list = []
for genre in data_dump["genres"]:
    genre_db[genre["name"]] = genre_id
    genre_id += 1

subgenre_id = genre_id
for genre in data_dump["genres"]:
    for subgenre in genre["subgenres"]:
        subgenre_db[subgenre["name"]] = subgenre_id
        subgenre_id += 1

for genre in data_dump["genres"]:
    for subgenre in genre["subgenres"]:
        for artist in subgenre["artists"]:
            if artist["name"] not in artist_db.keys():
                artist_id_list.append(artist_id)
                artist_db[artist["name"]] = artist_id
                artist_id += 1

            else:
                print(f"Found duplicate artist: {artist['name']}")
            for album in artist["albums"]:
                album["name"] = album["name"].replace("\"","\'")
                if album["name"] not in album_db.keys():
                    art_db[album["name"]] = album["art_path"]
                    album_db[album["name"]] = [artist_db[artist["name"]],genre_db[genre["name"]],subgenre_db[subgenre["name"]],art_id,album["release_date"],album_id]
                    for track in album["tracks"]:
                        duration_parts = track['duration'].split(':')
                        if int(duration_parts[0]) > 59:
                            duration = str(int(duration_parts[0] % 60)) + ":" + track['duration']
                        else:
                            duration = f"00:{track['duration']}"
                        track['name'] = track['name'].replace("\"","\'")
                        search_key = f"{track['name']}||{artist['name']}||{duration}"
                        if search_key not in song_db.keys():
                            song_db[search_key] = song_id
                            song_id += 1
                        else:
                            songdb_key = f"{album_id}||{song_db[search_key]}"
                            if songdb_key not in albumsong_db.keys():
                                albumsong_db[songdb_key] = [1,2]
                    album_id += 1
                    art_id += 1

migration_lines.append(format_sql.format("Vinyl"))
migration_lines.append(format_sql.format("CD"))      
for genre in genre_db.keys():
    migration_lines.append(genre_sql.format(genre))

for subgenre in subgenre_db.keys():
    migration_lines.append(genre_sql.format(subgenre))

# artist_id_list.sort()
print(len(artist_id_list))
print(len(artist_db))
for artist in artist_db.keys():
    migration_lines.append(artist_sql.format(artist))

for song in song_db.keys():
    keyparts = song.split('||')
    migration_lines.append(song_sql.format(keyparts[0],artist_db[keyparts[1]],keyparts[2]))

for album_art in art_db.keys():
    migration_lines.append(album_art_sql.format(art_db[album_art]))

for album in album_db.keys():
    migration_lines.append(album_sql.format(album,album_db[album][0],album_db[album][1],album_db[album][2],album_db[album][3],album_db[album][4]))

for album_song in albumsong_db.keys():
    keyparts = album_song.split("||")
    migration_lines.append(album_song_sql.format(keyparts[0],keyparts[1],1))
    migration_lines.append(album_song_sql.format(keyparts[0],keyparts[1],2))
    
for album in album_db.keys():
    migration_lines.append(inventory_sql.format(album_db[album][5],random.randint(20,50),random.randint(1,15)))

with codecs.open("up.sql", "w","utf-8-sig") as fp:
    for line in migration_lines:
        fp.write(line)