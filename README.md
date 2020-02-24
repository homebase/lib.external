lib.external
============

* External opensource libraries used in (our development)
* Never place non-opensource code here

How to use:
===========

1. copy external library to directry with *version*
2. create a symlink to non versioned lib


Submodules
----------

When remote project is available through git - use submodules:

    ADD:
    $ git submodule add USER@HOST:PROJEECT LOCATION
    UPDATE SUBMODULE:
    $ git submodule update


############################
########  GULP HOWTO
############################

How tso use gulp:
- path to gulpfile.js - /hbfr/lib.external/gulpfile.js
- to compile css from sass cd to /hbfr/lib.external/ then type in your terminal gulp bamCSS

On all r-k already installed: node & npm & gulp and it working now.
p\s - for the future we need to update node to 6.3.0 & npm to 3.10.3 

How to install gulp to fresh system:
#https://hostadvice.com/how-to/how-to-install-node-js-on-ubuntu-18-04/
1) install node & npm
    #Install Node.js 8 on
```    
    # Using Ubuntu
    curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
    sudo apt-get install -y nodejs

    # Using Debian, as root
    curl -sL https://deb.nodesource.com/setup_8.x | bash -
    apt-get install -y nodejs

    # Enterprise Linux Distributions
    curl -sL https://rpm.nodesource.com/setup_8.x | bash -
    yum install -y nodejs
```
2) install gulp & gulp-cli
```
    npm install --global gulp
    npm install --global gulp-cli
```
3) create link to use gulp module from project
```
    export PATH=./node_modules/../bin:./node_modules/.bin:../../node_modules/.bin:$PATH
```
4) future upgrades
    cd /rd/lib.external
    npm install --save-dev gulp
    npm audit fix