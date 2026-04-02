Changelog
=========


1.8.6
-----

Compatibility with PHP 8.5.

1.8.5
-----

Compatibility with PHP 8.1.

1.8.4
-----

- `Directory:copy()`: new parameter to overwrite existing files or not

1.8.3
-----

- Support of custom mime types for `File::getMimeTypeFromFilename()`

1.8.2
-----

- Add `Directory::copy()` to copy directory content.

1.8.1
-----

Fix a namespace syntax issue.

1.8.0
-----

- New `Path::isAbsolute()` method
- `Path::normalizePath()` can now resolve a relative path with a base path
- Default `chmod` static member in Directory and File. It allows configuring the Directory and File classes
  so we don't need to give chmod at every call of concerned methods
- Fix support of a relative path given to `Path::normalizePath()`
