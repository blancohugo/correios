# Contributing

Thank you for your interest in contributing to this project.

# How-to

Follow these steps to contribute to this repository:

1. [Fork](https://guides.github.com/activities/forking/) this repository
2. Clone your forked repository
    ``` bash
    $ git clone git@github.com:<your-github-username>/correios.git correios
    ```
3. Run composer in the repository folder
    ``` bash
    $ composer install
    ```
4. Develop your code
5. Check and correct coding standard
6. Provide a test for you code
7. Submit a pull request

# Coding Stardard

It is important that you follow the [PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

To check the coding standard, run the following command:
``` bash
$ composer check
```

# Test

Please try to add a test for your pull request.

``` bash
$ composer test
```