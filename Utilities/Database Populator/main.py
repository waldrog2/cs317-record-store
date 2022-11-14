import json

import requests
from urllib.parse import quote_plus
from bs4 import BeautifulSoup
import discogs_client
from time import sleep
# headers = {'User-Agent': "gnations56/CS317RecordStore"}
headers = {'User-Agent': "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246"}

def get_top_five_artist_of_genre(genre):
    artist_ids = []
    url = "https://www.discogs.com/search/?sort=have%2Cdesc&layout=sm&format_exact=Album"
    url += quote_plus(f"&genre_exact={genre}","=&")
    print(url)
    response = requests.get(url,headers=headers)
    if response.status_code == 200: # if request succeeded, start parsing result page
        page = response.content
        soup = BeautifulSoup(page) # create parsing object
        items = soup.find_all('li',attrs={'role':'listitem'}) # find all albums on the page
        target_items = items[0:5] # select the top 5
        for target_item in target_items:
            link = target_item.find_next('a')
            artist_id = link.get('href').split('/')[-1].split('-')[0]
            artist_name = ' '.join(link.get('href').split('/')[-1].split('-')[1:])
            artist_ids.append(artist_id)
            # print(f"Artist ID: {artist_id}")
            # print(f"Artist: {artist_name}")
    else:
        print(response.status_code)
        print(response.content)
    return artist_ids

def get_releases_by_artist(artist_id):
    headers = {
        'User-Agent': "gnations56/CS317RecordStore","Authorization":"Discogs key=OXLJyxCmAaVWjuMTcFgx secret=jwSyAXTyXjLXkcknNnsoVmiYKCLDTurr"}
    url = f"https://api.discogs.com/artists/{artist_id}/releases?sort=format&sort_order=desc"
    response = requests.get(url,headers=headers)
    if response.status_code == 200:
        release_list = []
        release_data = response.json()
        for release in release_data["releases"]:
            print(json.dumps(release,indent=2))
            print(f"release: {release.keys()}")
            if release['type'] == "master":
                if release["title"] not in release_list:
                    release_list.append(release["title"])
        print(release_list)
        # print(json.dumps(release_data,indent=4))



top_hh_artists = get_top_five_artist_of_genre("Hip Hop")
get_releases_by_artist(top_hh_artists[0])