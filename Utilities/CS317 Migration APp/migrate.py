import os
import mariadb
import sys
import time
import codecs
host = "127.0.0.1"
# port = 3306
port = 3066
username = "records"
password = "records"
database = "records"
migrations_directory = "migrations/"
line_number = 0
cnx = mariadb.connect(user=username, password=password, host=host, port=port, database=database)
cursor = cnx.cursor()



def time_convert(sec):
  mins = sec // 60
  sec = sec % 60
  hours = mins // 60
  mins = mins % 60
  print(f"Query took {int(hours):02d}:{int(mins):02d}:{int(sec):02d}")

if len(sys.argv) < 2:
    print("Please provide an operation {migrate,rollback}")
    exit()

if sys.argv[1].lower() == "migrate":
    with open("./migrations/CURRENT","r") as fp:
        file_data = fp.readlines()
        max_version = int(file_data[0])
    for version in range(1,max_version+1):
        # print(os.path.join(migrations_directory,dir))
        # print(f"Files in {dir}: ")
        files = os.listdir(os.path.join(migrations_directory,str(version)))
        if "up.sql" in files:
            line_number = 0
            query_lines = ""
            print(f"Running migration {str(version)}...")
            with codecs.open(os.path.join(migrations_directory,str(version),"up.sql"),"r","utf-8-sig") as fp:
                query_lines = fp.read()
                query_commands = query_lines.split(';')
                start_time = time.time()
                for command in query_commands:
                    line_number += 1
                    command = command.replace('\n','')
                    if command != "":
                        command += ";"
                        # if line_number > 3:
                        #     break
                        # print(f"Command: {command}")
                        try:
                            cursor.execute(command)
                            cnx.commit()
                        except mariadb.Error as e:
                            print(f"Line {line_number}: {command}")
                            print(f"The following error occurred: {e}")
                            cnx.rollback()
                            break
                end_time = time.time()
                time_convert(end_time-start_time)
elif sys.argv[1].lower() == "rollback":
    with codecs.open("./migrations/CURRENT","r","utf-8-sig") as fp:
        file_data = fp.readlines()
        max_version = int(file_data[0])
    for version in range(max_version,0,-1):
        files = os.listdir(os.path.join(migrations_directory,str(version)))
        if "down.sql" in files:
            line_number = 0
            query_lines = ""
            print(f"Rolling back migration {str(version)}...")
            with open(os.path.join(migrations_directory,str(version),"down.sql"),"r") as fp:
                query_lines = fp.read()
                query_commands = query_lines.split(';')
                for command in query_commands:
                    line_number += 1
                    command = command.replace('\n','')
                    if command != "":
                        command += ";"
                        # print(f"Command: {command}")
                        print(f"Line {line_number}: {command}")
                        cursor.execute(command)
                        cnx.commit()
                    

