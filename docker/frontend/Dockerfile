FROM node:14.15.0 as compile-image
WORKDIR /opt/ng
COPY frontend/src ./src
COPY frontend/package.json ./
COPY frontend/package-lock.json ./
COPY frontend/angular.json ./
COPY frontend/tsconfig.app.json ./
COPY frontend/tsconfig.json ./
COPY frontend/tsconfig.spec.json ./
RUN ls /opt/ng/src/
RUN ls /opt/ng/src/environments
RUN npm install
RUN npm install -g @angular/cli
# RUN ng serve --host 0.0.0.0
RUN ng build --prod

FROM nginx
COPY docker/nginx/frontend.conf /etc/nginx/conf.d/default.conf
COPY --from=compile-image /opt/ng/dist/frontend /usr/share/nginx/html