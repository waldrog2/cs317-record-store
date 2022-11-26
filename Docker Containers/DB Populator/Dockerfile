FROM ubuntu
RUN apt-get update
RUN curl -sS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | bash
RUN apt-get install -y libmariadb3 libmariadb-dev python3-dev build-essential python3 python3-pip
RUN pip3 install mariadb==1.1.4

CMD tail -f "/dev/null"