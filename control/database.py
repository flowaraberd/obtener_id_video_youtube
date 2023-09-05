from datetime import datetime
from control.logs import capture_error


def is_table_exists(**data):
    try:
        cursor = data['cursor']
        call = data['call']
        for llamar in call:
            datos = llamar()
            sql = f"SHOW TABLES LIKE '{datos['table_name']}'"
            cursor.execute(sql)
            resultado = cursor.fetchall()
            if len(resultado) <= 0:
                cursor.execute(datos['sql'])
                print(f"Creando tabla... {datos['table_name']}")
    except Exception as error:
        capture_error(str(error), '---------- is_table_exists -----------')
        pass


def create_table_video_yt_id() -> dict:
    table_name = "video_yt_id"
    sql = """
    CREATE TABLE video_yt_id (
    id int AUTO_INCREMENT,
    active BOOLEAN default 1,
    video_id VARCHAR(64) not null,
    youtube_channel VARCHAR(64) not null,
    publish_in TIMESTAMP, 
    PRIMARY KEY (id)
    );
    """
    return dict(table_name=table_name, sql=sql)


def is_video_id_exists(cursor, video_id: str, youtube_channel: str):
    timestamp = datetime.now()
    sql = f"SELECT 1 FROM video_yt_id WHERE video_id='{video_id}'"
    cursor.execute(sql)
    resultado = cursor.fetchall()
    if len(resultado) <= 0:
        sql = f"INSERT INTO video_yt_id(video_id, youtube_channel, publish_in) VALUES ('{video_id}', '{youtube_channel}', '{timestamp}')"
        cursor.execute(sql)
