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
git clone https://github.com/leustean/sran-nokia.git
```

Secondly you need to build you environment with docker. To do that first cd into the folder where the project was cloned. Then execute the following command build and start your servers.

```
docker-compose up
```

If you need use xdebug on you local machine you can use the alternate docker-compose file(```docker-compose-xdebug.yml```), but when using this image you don't have access to composer commands in the docker VM.

Then you need to run the initial deployment script.

```
#when setting up you developemnt environment
bin/deploy-dev.sh

#when setting up a production environment
bin/deploy-prod.sh
```

Optionally you can to add the following lines to your hosts file(On windows it is C:\Windows\System32\drivers\etc\hosts).
If you skip this step, use localhost instead of ```sran.local``` 

```
127.0.0.1       sran.local
127.0.0.1       www.sran.local
```

Now when you go to http://sran.local/ you should see a working page.

Note: when accessing the admin section on dev environment any user - password - domain combination is valid (the user will be created on the first login and after that you will need to set is_admin to 1 in the db for your user, logout and login again).

## Keeping the app up to date

You can keep the app up to date by using the integrated deploy options (http://sran.local/deploy).

Deploy options include:

* git pull
* composer install and update
* npm install
* compile SCSS
* doctrine commands

## Running the tests

To run the test execute the following command in the project root(this will also install phpunit if not present).

```php bin/phpunit```


### Coding style

We use the PSR-2 with some adjustments for PHP and BEM for HTML and CSS

If you are using PhpStorm you can import the following [file](https://drive.google.com/open?id=1nBVtNTnfetoqtdR359AUw-Agb-OsW8YQ) to be able to reformat files to our codding standard.

```
#PSR-2
function()
{

}

#Our codding standard
function(){

}

```

## Running cron jobs

### Running a cron job when scheduled

To schedule cron jobs they must be added to ```src/Repository/CronRepository.php``` in the ```setUpCronJobs``` method.

Notes: 
* cron jobs can be scheduled only on the prod image
* cron log can be found at ```var/log/cron.log```

### Running a cron job manually

To run a cron job manually run the following command in the app root where ```#cron_id#``` is the ID of the cron you wish to run.

```
php bin/console app:cron:run:one #cron_id#

```

## Built With

* [Docker](https://www.docker.com/) - Used to build the environments
* [Symfony](https://symfony.com/) - The web framework used
* [Composer](https://getcomposer.org/) - Dependency Management
* [NodeJs](https://nodejs.org/en/) - Used to build SCSS files

