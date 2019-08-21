echo "Starting XDebug";

cp /c/tools/$PHP_DIR/php.ini /c/tools/$PHP_DIR/php.ini.base;
echo "zend_extension=xdebug.dll" >> /c/tools/$PHP_DIR/php.ini;
php -v;
