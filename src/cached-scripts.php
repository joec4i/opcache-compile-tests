<?php
header('Content-Type: text/plain');

print_r(opcache_get_status()['scripts']);
