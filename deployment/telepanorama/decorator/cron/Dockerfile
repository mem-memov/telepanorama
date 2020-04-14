FROM debian:buster

RUN apt-get update && \
    apt-get install -y cron && \
    apt-get install -y wget && \
    apt-get install -y nano && \
    apt-get install -y procps


CMD cron -f