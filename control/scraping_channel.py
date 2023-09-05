import json

import requests
from bs4 import BeautifulSoup
from control.logs import capture_error


def get_data_channel(url: str):
    # Realiza una solicitud GET a YouTube
    _url = url.strip()
    response = requests.get(_url)

    bs = BeautifulSoup(response.content, 'html.parser')

    find_element = str(bs).find('ytInitialData')
    len_find = len('ytInitialData')
    element = str(bs)[find_element+len_find+3:]
    find_script = element.find('</script>')
    element = element[:find_script-1]

    loads_data = json.loads(element)
    youtube_id = ""
    id_video = ""

    try:
        id_video: str = loads_data['contents']['twoColumnBrowseResultsRenderer']['tabs'][1]['tabRenderer']['content']['richGridRenderer']['contents'][0]['richItemRenderer']['content']['videoRenderer']['videoId']
        youtube_id = _url.replace('/videos', '').split("/")[-1]
    except Exception as error:
        capture_error(str(error), '---------- get_data_channel -----------')
        pass

    print(f"Channel: {youtube_id}, Video ID: {id_video}")
    return {
        "video_id": id_video,
        "youtube_id": youtube_id
    }
