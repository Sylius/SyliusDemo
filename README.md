<p align="center">
    <a href="https://sylius.com" target="_blank">
        <picture>
          <source media="(prefers-color-scheme: dark)" srcset="https://media.sylius.com/sylius-logo-800-dark.png">
          <source media="(prefers-color-scheme: light)" srcset="https://media.sylius.com/sylius-logo-800.png">
          <img alt="Sylius Logo." src="https://media.sylius.com/sylius-logo-800.png">
        </picture>
    </a>
</p>

[![License](https://img.shields.io/packagist/l/Sylius/Sylius.svg)](https://packagist.org/packages/sylius/sylius)
[![Version](https://img.shields.io/packagist/vpre/Sylius/Sylius.svg)](https://packagist.org/packages/sylius/sylius)
[![Build](https://github.com/Sylius/SyliusDemo/actions/workflows/build.yml/badge.svg?branch=master)](https://github.com/Sylius/SyliusDemo/actions/workflows/build.yml)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/Sylius/Sylius.svg)](https://scrutinizer-ci.com/g/Sylius/Sylius/)
[![Total Downloads](https://poser.pugx.org/sylius/sylius/downloads)](https://packagist.org/packages/sylius/sylius)

**This is Sylius Demo Edition repository deployed to [demo.sylius.com](https://demo.sylius.com/).**

Sylius is the first decoupled eCommerce platform based on [**Symfony**](http://symfony.com) and [**Doctrine**](http://doctrine-project.org). 
The highest quality of code, strong testing culture, built-in Agile (BDD) workflow and exceptional flexibility make it the best solution for application tailored to your business requirements. 
Enjoy being an eCommerce Developer again!

We're using full-stack Behavior-Driven-Development, with [phpspec](http://phpspec.net) and [Behat](http://behat.org)

Documentation
-------------

Documentation is available at [docs.sylius.com](https://docs.sylius.com).

Installation
------------

```bash
$ wget http://getcomposer.org/composer.phar
$ php composer.phar create-project -s beta sylius/sylius-standard project
$ cd project
$ npm install
$ npm run gulp
$ php bin/console sylius:install
$ php bin/console server:start
$ open http://localhost:8000/
```

Troubleshooting
---------------

If something goes wrong, errors & exceptions are logged at the application level:

```bash
$ tail -f var/log/prod.log
$ tail -f var/log/dev.log
```

If you are using the supplied Vagrant development environment, please see the related [Troubleshooting guide](etc/vagrant/README.md#Troubleshooting) for more information.

Contributing
------------

Would like to help us and build the most developer-friendly eCommerce platform? Start from reading our [Contributing Guide](https://docs.sylius.com/en/latest/contributing/index.html)!

Stay Updated
------------

If you want to keep up with the updates, [follow the official Sylius account on Twitter](http://twitter.com/Sylius) and [like us on Facebook](https://www.facebook.com/SyliusEcommerce/).

Bug Tracking
------------

If you want to report a bug or suggest an idea, please use [GitHub issues](https://github.com/Sylius/Sylius/issues).

Community Support
-----------------

Get Sylius support on [Slack](https://sylius.com/slack), [Forum](https://forum.sylius.com/) or [Stack Overflow](https://stackoverflow.com/questions/tagged/sylius).

MIT License
-----------

Sylius is completely free and released under the [MIT License](https://github.com/Sylius/Sylius/blob/master/LICENSE).

Authors
-------

Sylius was originally created by [Paweł Jędrzejewski](http://pjedrzejewski.com).
See the list of [contributors from our awesome community](https://github.com/Sylius/Sylius/contributors).
