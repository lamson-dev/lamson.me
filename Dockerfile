FROM node:8.14-alpine as builder

WORKDIR /_lamson.me
COPY . .

# Install dependencies
# node_modules/sharp requires `python` and `libvips`...
# See http://sharp.pixelplumbing.com/en/stable/install/#alpine-linux 
RUN apk update \
  && apk add vips-tools \
  --repository http://dl-3.alpinelinux.org/alpine/edge/testing \
  && apk add vips-dev fftw-dev build-base \
  --repository https://alpine.global.ssl.fastly.net/alpine/edge/testing/ \
  --repository https://alpine.global.ssl.fastly.net/alpine/edge/main \
  && apk add python \
  && rm -rf /var/cache/apk/*

RUN yarn install && yarn build

FROM nginx
EXPOSE 80
COPY --from=builder /_lamson.me/public /usr/share/nginx/html

# Default start command for `docker run ...`
# CMD ...
