phar-build:
	@php -d "phar.readonly=off" ./phar.php
phar-install-system: phar-build
	@sudo chmod +x ./install-emoticon-phpunit.phar
	@sudo mv ./install-emoticon-phpunit.phar /usr/local/bin/install-emoticon-phpunit
	@echo 'Use install-emoticon-phpunit generate :)'

