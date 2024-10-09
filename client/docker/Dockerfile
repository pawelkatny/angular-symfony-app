FROM node:alpine

RUN mkdir -p /usr/src/app && chown node:node /usr/src/app

WORKDIR /usr/src/app

COPY ./ /usr/src/app/

RUN npm install -g @angular/cli
RUN npm install

CMD ["ng", "serve", "--host", "0.0.0.0"]
