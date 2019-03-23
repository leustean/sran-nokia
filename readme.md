# sRAN Advanced Detection of Available Resources

This project has the objective of detecting the state of user defined network resources.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

To install the project you need to have git, docker and docker-compose(comes with docker on windows and mac) installed.

Git: https://git-scm.com/

Docker: https://www.docker.com/products/docker-desktop

### Installing

First you need to clone this repository.

```
git clone https://<AtlassianId>@bitbucket.org/uvt-sran/sran.git
```

Secondly you need to build you environment with docker. To do that first cd into the folder where the project was cloned. Then execute the following command build and start your servers.

```
docker-compose -f docker-compose-prod.yml
```

If you need use xdebug on you local machine you can use the alternate docker-compose file(docker-compose-dev.yml), but when using this image you don't have access to composer commands in the docker VM.

Then you need to run the initial deployment script.

```
bin/deploy-dev.sh
```

Next you need to add the following lines to your hosts file(On windows it is C:\Windows\System32\drivers\etc\hosts).

```
127.0.0.1       sran.local
127.0.0.1       www.sran.local
```

Now when you go to http://sran.local/deploy/ you should see some deployment options.

## Running the tests

There are no automated test yes.


### Coding style

We use the PSR-4 with some adjustments for PHP and BEM for HTML and CSS

If you are using PhpStorm you can import the following [file](https://drive.google.com/open?id=1nBVtNTnfetoqtdR359AUw-Agb-OsW8YQ) to be able to reformat files to our codding standard.

```
#PSR-4
function()
{

}

#Our codding standard
function(){

}

```

## Built With

* [Docker](https://www.docker.com/) - Used to build the environments
* [Symfony](https://symfony.com/) - The web framework used
* [Composer](https://getcomposer.org/) - Dependency Management
* [NodeJs](https://nodejs.org/en/) - Used to build SCSS files

