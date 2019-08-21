echo "Modifying PHP Configuration";

# Set Extension Directory
echo 'extension_dir="ext"' >> /c/tools/$PHP_DIR/php.ini;

# Add Extensions
echo "extension=php_openssl.dll" >> /c/tools/$PHP_DIR/php.ini;
echo "extension=php_curl.dll" >> /c/tools/$PHP_DIR/php.ini;
echo "extension=php_mbstring.dll" >> /c/tools/$PHP_DIR/php.ini;
echo "extension=php_pdo_mysql.dll" >> /c/tools/$PHP_DIR/php.ini;
echo "extension=php_pdo_sqlite.dll" >> /c/tools/$PHP_DIR/php.ini;
echo "extension=php_fileinfo.dll" >> /c/tools/$PHP_DIR/php.ini;
echo "extension=php_gd2.dll" >> /c/tools/$PHP_DIR/php.ini;
echo "extension=php_ftp.dll" >> /c/tools/$PHP_DIR/php.ini;
echo "extension=php_com_dotnet.dll" >> /c/tools/$PHP_DIR/php.ini;

# Misc
echo "memory_limit=256M" >> /c/tools/$PHP_DIR/php.ini;
echo "curl.cainfo=C:\tools\cacert.pem" >> /c/tools/$PHP_DIR/php.ini;

echo "Completed PHP Configuration";