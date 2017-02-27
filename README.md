# Index Operatur

Operatur is a libary to help building background processes around workers and queues. 

It also provides an interface for writing workers for different platforms and adapters for 
docker, azure functions, iron.io workers, aws lambda.

To use, create a 'Workers' folder in your application src. Look into tests\App for usage.

## Install

``` bash
composer require index-io/operatur
```

## To run

Generate skeleton structure, the -p argument is the folder name of where your app sits, default is 'app' but any custom one can be used
``` bash
vendor/bin/console operatur:skeleton -p app
```

This command will generate the folders and files necessary for the functions you just wrote that can then be deployed through git or other mechanisms. If config or routes paths are not provided then it will use the default ones located at /app/Workers/config.php and /app/Workers/routes.php

``` bash
vendor/bin/console operatur:functions -c sample/config.php  -r sample/routes.php -p azure-functions
```

To remove the generated folders / files use the 'remove' argument:
``` bash
vendor/bin/console operatur:functions remove -c sample/config.php  -r sample/routes.php -p azure-functions
```


## Testing

``` bash
$ vendor/bin/phpunit
```

## Contributing

In the future.

## Credits

- [Cezar Grigore](https://github.com/grigorecezar)
- [Liviu Nasoi](https://github.com/Liviu92)
- [Tom Jenkins](https://github.com/tomtwo)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
