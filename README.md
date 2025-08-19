# WEB-TEMPLATES - PHP + APACHE
A simple Docker container to run php + Apache with some useful tools like composer, Node.js, npm, yarn, git, ssh and more. Used to build Web-Templates.

## Pre-Requisites
* git
* ssh
* docker

## Installation

### Folder Structure
You should have a folder structure like this:

    YourDevFolder
    |- template1        |
    |- template2        |<- Optional repos
    |- ...              |
    |- template7        |
    |- orchestration    |<- Required repo if using orchestration
    |- mystorepanel     |<- Required repo if using orchestration
    |- web-templates    |<- This repo

## Getting Started
### Git

You must have Docker installed and running properly.

Clone this repo using git
```sh
git clone git@github.com:wayoxmedia/web-templates.git
```

cd into your app
```sh
cd web-templates
```

## Environment Installation
### Running app in orchestration mode
Just skip ahead to the section `FrontEnd Dependencies` if you are using the orchestration mode. Make sure you already have the orchestration repo cloned.

### Running app in standalone mode
Make sure you have the `BACKEND_SERVICE_TOKEN` and additional keys to connect with the online backend service.

#### Docker .env file
Get a copy of the actual .env file from admins or create your own .env file and edit some values. (This .env file is for Docker, not for Laravel)
```sh
cp .env.sample .env
# edit the file if you need to change some values
nano .env
```

#### Building the Docker image
Run docker build
```sh
docker compose --env-file .env build
```

This may take some minutes if this is your first install, images are been downloaded.

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
docker exec -it web-templates bash
```
This step above will give you a shell inside the container.

You can also access the container using SSH with your IDE.
If you are using Visual Studio Code, you can use the Remote - SSH extension to connect to the container.

## FrontEnd Dependencies
### OPTIONAL, ask for instructions to your admins or jump to section `Updating your hosts file`
Time to install FrontEnd dependencies.

```sh
npm install
```
This will install all the dependencies needed for the FrontEnd, the folder node_modules will be created.

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

## Troubleshoot

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

`http://web-templates.test`

If this URL doesn't work, replace `web-templates.test` with localhost or 127.0.0.1

Check it is properly working.

## That's it! Welcome to your docker LAMP Environment.

### Additional Recommendations

* Use Visual Studio Code with the Remote - Containers extension to open your project in a container.
* Use the Docker extension to manage your containers, images, volumes, networks and containers.

Happy coding!
