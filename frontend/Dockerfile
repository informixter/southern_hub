FROM node:12-alpine as builder
WORKDIR /usr/src/eye-of-shiva
COPY package.json ./
RUN npm i
COPY . .
RUN npm run build

FROM nginx:1.19-alpine
RUN rm -rf /usr/share/nginx/html/*
COPY --from=builder /usr/src/eye-of-shiva/dist/frontend /var/www/html/frontend
CMD ["nginx", "-g", "daemon off;"]
