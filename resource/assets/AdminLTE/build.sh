#!/bin/bash

function rebuildDist(){
    rm -rf ./dist
    mkdir ./dist
    mkdir ./dist/js
    mkdir ./dist/css

    cp -R ./src/css/fonts ./dist/css/
    cp -R ./src/img ./dist/
    cp -R ./src/fonts ./dist/

    cp ./src/js/*.js ./dist/js
    cp ./src/css/*.png ./dist/css
    cp ./src/css/*.gif ./dist/css
}

function buildJs() {
    uglifyjs ./src/js/all/*.js --comments '/^!/' --output ./dist/js/all.min.js
    uglifyjs ./src/js/all_2/*.js --comments '/^!/' --output ./dist/js/all_2.min.js
    uglifyjs ./src/js/components/form/*.js --comments '/^!/' --output ./dist/js/form.min.js
    uglifyjs ./src/js/components/tree/*.js --comments '/^!/' --output ./dist/js/tree.min.js
    uglifyjs ./src/js/components/treeview/*.js --comments '/^!/' --output ./dist/js/treeview.min.js
    uglifyjs ./src/js/components/datatable/*.js --comments '/^!/' --output ./dist/js/datatable.min.js
}

function buildCss() {
    uglifycss ./src/css/combine/*.css --output ./dist/css/all.min.css
}

function build() {
    # npm install uglifycss -g
    # npm install -g cnpm --registry=https://registry.npm.taobao.org
    # cnpm install uglify-es -g

    rebuildDist
    buildJs
    buildCss
}

build