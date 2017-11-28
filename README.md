# wexnz-bot

This is php bot with simple strategy to trade bitcoin 
on the https://wex.nz/ exchange.

## Table Of Contents

- [Installation](#installation)
- [Running the bot](#running-the-bot)
    - [Run on the local machine](#run-on-the-local-machine)
    - [Run in the Docker container](#run-in-the-docker-container)


## Installation

Download latest release [here](https://github.com/madmis/wexnz-bot/releases) 
and extract sources to a project (destination) folder **or** clone project
```bash
    $ git clone https://github.com/madmis/wexnz-bot.git ~/wexnz-bot
```

Create configuration file:
```bash
    $ cp ~/wexnz-bot/app/simple-bot-config.yaml.dist ~/wexnz-bot/app/conf.bthbtc.yaml
```
and change configuration parameters with your requirements.


## Running the bot

You can run bot on the local machine or in the Docker container.

### Run on the local machine
To run bot on the local machine please install: 
* [php >=7.1.3](http://php.net/manual/en/install.php)
* [php-bcmath](http://php.net/manual/en/book.bc.php)
* [Сomposer](https://getcomposer.org/doc/00-intro.md)

Then do next steps:
```bash
    $ cd ~/wexnz-bot/app
    $ composer install
```
and run the bot:
```bash
    $ php ~/wexnz-bot/app/bin/console simple-bot:run ~/wexnz-bot/app/conf.bthbtc.yaml 
```


### Run in the Docker container 
To run bot in the Docker container:
* [Install Docker](https://docs.docker.com/engine/installation/)
* [Install Docker Compose](https://docs.docker.com/compose/install/)

Then do next steps:
```bash
    $ cd ~/wexnz-bot
    $ docker-compose up -d
    $ docker exec -ti wexnzbot_php_1 bash
```
and run the bot:
```bash
    $ php /var/www/bin/console simple-bot:run /var/www/conf.bthbtc.yaml 
```

### Concurrent Running
**! Notice** Don't run more than one bot instance for one trading (exchange) account.

You can run 2 (or more) bot instances from one application/container.
For this create separate trading (exchange) accounts for different pairs.

To run 2 bot instances:
* Create 2 configuration files, for different pairs
* Create different trading (exchange) accounts and generate API keys for it. 
Then put this case to configuration files (each api key in the corresponding config file)
* Run 2 bot instances (in separate terminal windows)
```bash
    $ php ~/wexnz-bot/app/bin/console simple-bot:run ~/wexnz-bot/app/conf.bthbtc.yaml 
    $ php ~/wexnz-bot/app/bin/console simple-bot:run ~/wexnz-bot/app/conf.ethbtc.yaml 
```
