FROM node:22-alpine

WORKDIR /app

COPY package*.json ./

RUN npm install
RUN npm install --platform=linux --arch=x64

COPY . .

EXPOSE 3000

ENV PORT 3000
ENV NODE_ENV development

CMD ["npm", "run", "dev"]