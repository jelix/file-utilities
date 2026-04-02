Some utilities to manipulate files, directories and paths.

# Installation

You can install it from Composer. In your project:

```
composer require "jelix/file-utilities"
```

# Features

The `File` class allows you to read and write file contents. `write()` method
allows changing the content of an existing file by not writing into it
directly, avoiding write lock.

The `Directory` class allows to delete the content of a directory recursively,
and even to do it without deleteting specific files.

For both `File` and `Directory` classes, chmod values can be set globally or
specifically, when they create a file or a directory.

The `Path` class allows cleaning up a path, to resolve a path against another, or
to get the shortest path between two paths. All of its methods work on path string
and do not rely on the file system. So paths can represent files/directories that
do not exist.


# History

Most of the methods of the first version come originally from the `jFile`
class of Jelix 1.6. https://jelix.org
