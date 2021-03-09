# Changelog

All notable changes to `api-client` will be documented in this file

## 3.1.0 - 2021-03-09

- Bug fixed in OAuthToken
- Added parsing datetime strings to Carbon objects in StraightKeyParser
- Add GitHub Actions integration

## 3.0.1 - 2021-02-15

- Minor bug fixes
- Changed Data layout in PaginatedData to be compatible with Laravel Resources

## 3.0.0 - 2021-02-12

- Huge rebuild package both in concept and code layer.
- Uses PHP8, dropped PHP 7.4 compatibility.
- More concentrated on delivery flexible package that could be use directly in apps rather than in other packages
- Made interfaces & classes for configuration, authorization, data fetching, containing & parsing to final classes delivered by user
- Prepared classes for config, url creating & fetching data from JSON API

## 2.0.1 - 2021-01-20

- Updated to be compatible with PHP8

## 2.0.0 - 2021-01-04

- Delete "Action" in class names
- Leave hard-coded configuration of Base URL and OAuth in config file
- Use ApiClient as factory for CallApi class
- Optimized for multiple use in same project

## 1.0.0 - 2020-12-18

- initial release
