FROM node:18

WORKDIR /app

COPY package.json yarn.lock* package-lock.json* ./

RUN yarn install || npm install

COPY . .

CMD ["yarn", "start"]
