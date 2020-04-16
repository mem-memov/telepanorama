FROM jobber:latest

USER root
COPY jobfile /home/jobberuser/.jobber
RUN chown jobberuser:jobberuser /home/jobberuser/.jobber && \
    chmod 0600 /home/jobberuser/.jobber
USER jobberuser
