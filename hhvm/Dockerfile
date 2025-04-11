FROM hhvm/hhvm:latest

COPY . /var/www
WORKDIR /var/www

CMD ["hhvm", "-m", "server", "-d", "hhvm.server.type=fastcgi", "-d", "hhvm.server.port=9000", "-d", "hhvm.server.default_document=router.php", "-d", "hhvm.repo.authoritative=false"]
