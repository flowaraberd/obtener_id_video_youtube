from mysql import connector
from config.config import CONFIG
from control.scraping_channel import get_data_channel
from control.logs import capture_error
from control.database import (
    is_table_exists,
    is_video_id_exists,
    create_table_video_yt_id
)


def set_connect():
    connect = connector.connect(**CONFIG.config_db)
    cursor = connect.cursor()

    # Para verificar si la tabla existe, en caso de no,
    # realiza una llamada a las funciones que esten en "call"
    is_table_exists(
        cursor=cursor,
        call=[
            create_table_video_yt_id
        ]
    )

    try:
        # Leer las URLs de los canales a realizar el Scraping.
        with open(CONFIG.path_urls_channel, 'r') as channels:
            for url_channel in channels.readlines():
                try:
                    # LÓGICA DEL LA OBTENCIÓN DEL ID DEL VIDEO
                    video_id = get_data_channel(url_channel)

                    is_video_id_exists(
                        cursor=cursor,
                        video_id=video_id['video_id'], # El ID del Video de YouTube
                        youtube_channel=video_id['youtube_id'] # Usuario de YouTube
                    )
                    connect.commit()
                except Exception as error:
                    capture_error(str(error), '---------- set connect file - al insertar los datos -----------')
                    pass

    except Exception as error:
        capture_error(str(error), '---------- set connect -----------')

    connect.commit()
    connect.close()
    print("Todos los video ID guardadas.")
