# TEMPLATE 1 - PHP + APACHE
A simple Docker container to run php + Apache with some useful tools like composer, Node.js, npm, yarn, git, ssh and more. Used to build Template1.

## Pre-Requisites

* git
* ssh
* docker

## Installation

### Folder Structure

You should have already a folder structure like this:

    YourDevFolder
    |- template1        <- This repo
    |- template2        |
    |- ...              |
    |- template7        |<- Optional repos
    |- orchestration    |
    |- mystorepanel     |

### Getting Started

You must have Docker installed and running properly.

clone this repo using git

```sh
git clone git@github.com:wayoxmedia/template1.git
```

cd into your app

```sh
cd template1
```

get a copy of the actual .env file from admins or create your own .env file and edit some values.
```sh
cp .env.sample .env
# edit the file if you need to change some values
nano .env
```

get a copy of the actual config.php file from admins or create your own config.php file and edit some values.
```sh
cp secure/config.sample.php secure/config.php
# edit the file if you need to change some values
nano secure/config.php
```

run docker build
```sh
docker compose --env-file .env build
```

this may take some minutes if this is your first install, images are been downloaded.

Now, bring up the environment.

```sh
docker-compose up -d
```

Check the containers are properly running

```sh
docker ps
```

### Post-Installation
Now you can access your container using SSH.

```sh
docker exec -it template1 bash
```
This step above will give you a shell inside the container.

You can also access the container using SSH with your IDE.
If you are using Visual Studio Code, you can use the Remote - SSH extension to connect to the container.

Time to install FrontEnd dependencies.

```sh
npm install
```
This will install all the dependencies needed for the FrontEnd, the folder node_modules will be created.

## OPTIONAL, ask for instructions to your admins or jump to section `Updating your hosts file`
### Compile SASS
Some base CSS code is already included in the project, but you may want to add your own styles. Please don't modify the base SCSS files, instead create your own SCSS files and import them into the main SCSS file or use the custom.css file.
```sh
npm run build:css
```
This will compile the SCSS files into CSS files. The compiled CSS files will be created in the css folder.
You can also use the watch command to automatically compile the SCSS files when you save them.
```sh
npm run watch:css
```
This will watch the SCSS files for changes and compile them automatically. Again, avoid modifying the base SCSS files unless you know what you are doing.

### Troubleshoot

If container can not start, create `logs` folder inside `html` folder.

### Updating your hosts file
MacOS & Linux
In your terminal, run
```sh
sudo nano /etc/hosts
```
PC
```
Open [SystemRoot]\system32\drivers\etc\hosts and edit the file with your text editor with admin privileges.
```
Add the following lines at the end of this hosts file
```
127.0.0.1     template1.test
```
MacOS & Linux: 'Ctrl+O' then 'y' to save and 'Ctrl+X' to quit nano.
PC: Save and quit your editor.

After these steps, you may need to flush your dns.

Navigate with your browser to the site

`http://template1.test`

If this URL doesn't work, replace `template1.test` with localhost or 127.0.0.1

Check it is properly working.

### That's it! Welcome to your docker LAMP Environment.

### Recommendations

* Use Visual Studio Code with the Remote - Containers extension to open your project in a container.
* Use the Docker extension to manage your containers, images, volumes, networks and containers.

Happy coding!
