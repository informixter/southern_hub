FROM python:3.8.5-buster
RUN apt update && apt install git bash
WORKDIR /usr/src/app
COPY requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt

COPY . .
CMD [ "python", "./start.py" ]