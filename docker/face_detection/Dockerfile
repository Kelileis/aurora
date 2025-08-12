FROM --platform=linux/arm64 python:3.8
LABEL org.opencontainers.image.source https://github.com/serengil/deepface

SHELL ["/bin/bash", "-c"]
WORKDIR /app/

COPY ./deepface ./api/app.py ./api/api.py ./api/routes.py ./api/service.py ./requirements.txt ./setup.py ./README.md ./

RUN apt-get update && \
    apt-get install -y --no-install-recommends libgl1 libsm6 libxext6 && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN pip install --no-cache-dir h5py==3.10.0

RUN pip install --upgrade pip \
    && pip install tensorflow==2.13.1 deepface Deprecated

ENV PYTHONUNBUFFERED=1

EXPOSE 5000
CMD ["gunicorn", "--workers=1", "--timeout=3600", "--bind=0.0.0.0:5000", "app:create_app()"]
