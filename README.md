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
How to use gulp
===============

Assume we use same modules for all our sub-projects
Approach:
- If you have no installed Gulp on your development system - install gulp globally
# sudo apt install nodejs
# sudo apt install npm
# sudo npm install --global gulp node-sass gulp-sass gulp-autoprefixer gulp-sourcemaps gulp-rigger gulp-uglify gulp-concat



- Now let's init sub-project structure. As example I'll use radaris.com folder ( /rd/rd/ )
# cd /rd/rd/
if you do not see folder /rd/rd/node_modules/ you should link it to your global gulp modules
# sudo npm link gulp node-sass gulp-sass gulp-autoprefixer gulp-sourcemaps gulp-rigger gulp-uglify gulp-concat
folder /rd/rd/node_modules/ should appear with link to your global node_modules location. 
Note: this folder is ignored by Git
Now you test your environment
# gulp test

Troubleshooting:
1. allow your local user to access global node modules (optional)
# sudo chown -R <YOUR_LOCAL_USER_NAME> /usr/local/lib/node_modules/
2. If you still have "permission denied" error try to re-install node-sass with ignoring permissions issues
# sudo npm install --unsafe-perm -g node-sass
3. Rebuild node-sasss
# sudo npm -g rebuild node-sass
