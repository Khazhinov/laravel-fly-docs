
<p align="center"><img src="/art/header.jpg" alt="Social Card of Laravel FlyDocs"></p>

# Laravel FlyDocs 📚

Библиотека для формирования документации по стандарту OpenApi 3.0 в экосистеме Laravel со встроенным UI

## Описание

Данная библиотека является форком [vyuldashev/laravel-openapi](https://github.com/vyuldashev/laravel-openapi).
Документация по основным сущностям доступна по [ссылке](https://vyuldashev.github.io/laravel-openapi/).

Обратите внимание, что Artisan команды FlyDocs начинаются с *fly-docs:*, например:

Команда в исходной библиотеке:
```bash
$ php artisan openapi:make-requestbody StoreUser
```

Команда FlyDocs:
```bash
$ php artisan fly-docs:make-requestbody StoreUser
```

## Установка

Для установки использовать:

```bash
$ composer require khazhinov/laravel-fly-docs
```

## Благодарности ❤️

Большая благодарность [Владимиру Юлдашеву](https://github.com/vyuldashev), чья библиотека [vyuldashev/laravel-openapi](https://github.com/vyuldashev/laravel-openapi) стала фундаментом данного решения.

## Лицензия

Лицензия MIT. Для получения большей информации обращайтесь к [тексту лицензии](LICENSE.md).
