
## Test setup for PHP bug [GH-16668](https://github.com/php/php-src/issues/16668)

To start the test server, run the following command:
```bash
docker-compose up -d
```

Below is my test output:
```
$ curl -s http://localhost:8080/compile-test.php
bool(true)
<br />
<b>Fatal error</b>:  Cannot redeclare foo() (previously declared in /var/www/html/releases/v1/functions.php:4) in <b>/var/www/html/releases/v2/functions.php</b> on line <b>4</b><br />
<br />
<b>Warning</b>:  Zend OPcache could not compile file /var/www/html/releases/v2/functions.php in <b>/var/www/html/compile-test.php</b> on line <b>3</b><br />
bool(false)
```

The expected output should be
```
bool(true)
bool(true)
```

### Workaround

Compile the two files in separate requests:
```
$ curl -s http://localhost:8080/compile-v1.php
bool(true)
$ curl -s http://localhost:8080/compile-v2.php
bool(true)
```

### Check cached scripts
```
$ curl -s http://localhost:8080/cached-scripts.php
Array
(
    [/var/www/html/compile-v2.php] => Array
        (
            [full_path] => /var/www/html/compile-v2.php
            [hits] => 1
            [memory_consumption] => 808
            [last_used] => Fri Nov  1 11:43:04 2024
            [last_used_timestamp] => 1730461384
            [timestamp] => 1730460660
            [revalidate] => 1730461386
        )

    [/var/www/html/cached-scripts.php] => Array
        (
            [full_path] => /var/www/html/cached-scripts.php
            [hits] => 3
            [memory_consumption] => 944
            [last_used] => Fri Nov  1 11:47:02 2024
            [last_used_timestamp] => 1730461622
            [timestamp] => 1730460699
            [revalidate] => 1730461624
        )

    [/var/www/html/compile-test.php] => Array
        (
            [full_path] => /var/www/html/compile-test.php
            [hits] => 3
            [memory_consumption] => 1016
            [last_used] => Fri Nov  1 11:45:34 2024
            [last_used_timestamp] => 1730461534
            [timestamp] => 1730460651
            [revalidate] => 1730461536
        )

    [/var/www/html/releases/v2/functions.php] => Array
        (
            [full_path] => /var/www/html/releases/v2/functions.php
            [hits] => 5
            [memory_consumption] => 992
            [last_used] => Fri Nov  1 11:45:34 2024
            [last_used_timestamp] => 1730461534
            [timestamp] => 1730458160
            [revalidate] => 1730461536
        )

    [/var/www/html/releases/v1/functions.php] => Array
        (
            [full_path] => /var/www/html/releases/v1/functions.php
            [hits] => 11
            [memory_consumption] => 992
            [last_used] => Fri Nov  1 11:45:34 2024
            [last_used_timestamp] => 1730461534
            [timestamp] => 1730458130
            [revalidate] => 1730461536
        )

    [/var/www/html/compile-v1.php] => Array
        (
            [full_path] => /var/www/html/compile-v1.php
            [hits] => 2
            [memory_consumption] => 808
            [last_used] => Fri Nov  1 11:43:01 2024
            [last_used_timestamp] => 1730461381
            [timestamp] => 1730460658
            [revalidate] => 1730461383
        )

)
```
