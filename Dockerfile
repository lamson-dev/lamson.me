FROM node:8.14-alpine as builder

WORKDIR /_lamson.me
COPY package.json yarn.lock ./

# Install dependencies
# node_modules/sharp requires `python` and `libvips`...
# See http://sharp.pixelplumbing.com/en/stable/install/#alpine-linux 
#
# --no-cache: download package index on-the-fly, no need to cleanup afterwards
# --virtual: bundle packages, remove whole bundle at once, when done
# See https://github.com/nodejs/docker-node/issues/282#issuecomment-358907790
RUN apk update && apk --no-cache --virtual build-dependencies add python make g++ \
  vips-dev fftw-dev build-base \
  --repository https://alpine.global.ssl.fastly.net/alpine/edge/testing/ \
  --repository https://alpine.global.ssl.fastly.net/alpine/edge/main \
  && yarn install --frozen-lockfile --no-cache \
  && yarn build \
  && apk del build-dependencies

FROM nginx
EXPOSE 80
COPY --from=builder /_lamson.me/public /usr/share/nginx/html

# Default start command for `docker run ...`
# CMD ...
