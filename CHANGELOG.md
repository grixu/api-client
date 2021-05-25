# Changelog

All notable changes to `api-client` will be documented in this file

## 3.3.2 - 2021-05-25

- Added access to UrlCompose in `JsonApiFetcher`

## 3.3.1 - 2021-05-25

- Added access to `DataFetcher` in `JsonApiFetcher`
- Added timeout

## 3.3.0 - 2021-05-21

- Added `chunk()` method in `JsonApiFetcher`
- Added `getDataCollection` in `PaginatedData`
- Added definition of `getDataCollection` in `FetchedData`
- Make method `parseElement` public in `StraightKeyParser`
- Added definition of `praseElement` in `ResponseParser` interface


## 3.2.1 - 2021-05-04

- Bug fixed in `StraightKeyParser`

## 3.2.0 - 2021-04-29

- Use snake_case naming convention in results data array instead of camelCase
- Updated DTO to v3

## 3.1.2 - 2021-03-30

- New StraightKeyParser

## 3.1.1 - 2021-03-09

- Bugs fixed

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
- Made interfaces & classes for configuration, authorization, data fetching, containing & parsing to final classes
  delivered by user
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
