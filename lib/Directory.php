<?php

/**
 * @author       Laurent Jouanneau
 * @contributor  Christophe Thiriot
 * @contributor  Loic Mathaud
 * @contributor  Bastien Jaillot
 * @contributor Julien Issler
 *
 * @copyright    2005-2015 Laurent Jouanneau, 2006 Christophe Thiriot, 2006 Loic Mathaud, 2008 Bastien Jaillot, 2009 Julien Issler
 *
 * @link         http://jelix.org
 * @licence      MIT
 */

namespace Jelix\FileUtilities;

class Directory
{
    /**
     * create a directory.
     *
     * @return bool false if the directory did already exist
     */
    public static function create($dir, $chmod = 0775)
    {
        if (!file_exists($dir)) {
            mkdir($dir, $chmod, true);

            return true;
        }

        return false;
    }

    /**
     * Recursive function deleting a directory.
     *
     * @param string $path      The path of the directory to remove recursively
     * @param bool   $deleteDir If the path must be deleted too
     *
     * @author Loic Mathaud
     */
    public static function remove($path, $deleteDir = true)
    {
        // minimum security check
        if ($path == '' || $path == '/' || $path == DIRECTORY_SEPARATOR) {
            throw new \Exception('The root cannot be removed !!');
        }

        if (!file_exists($path)) {
            return true;
        }

        $dir = new \DirectoryIterator($path);
        foreach ($dir as $dirContent) {
            // file deletion
            if ($dirContent->isFile() || $dirContent->isLink()) {
                unlink($dirContent->getPathName());
            } else {
                // recursive directory deletion
                if (!$dirContent->isDot() && $dirContent->isDir()) {
                    self::remove($dirContent->getPathName());
                }
            }
        }
        unset($dir);
        unset($dirContent);

        // removes the parent directory
        if ($deleteDir) {
            rmdir($path);
        }
    }

    /**
     * Recursive function deleting all files into a directory except those indicated.
     *
     * @param string $path         The path of the directory to remove recursively
     * @param array  $except       filenames and suffix of filename, for files to NOT delete
     * @param bool   $deleteDir If the path must be deleted too
     *
     * @return bool true if all the content has been removed
     *
     * @author Loic Mathaud
     */
    public static function removeExcept($path, $except, $deleteDir = true)
    {
        if (!is_array($except) || !count($except)) {
            throw new \Exception('list of exception is not an array or is empty');
        }

        if ($path == '' || $path == '/' || $path == DIRECTORY_SEPARATOR) {
            throw new \Exception('The root cannot be removed !!');
        }

        if (!file_exists($path)) {
            return true;
        }

        $allIsDeleted = true;
        $dir = new DirectoryIterator($path);
        foreach ($dir as $dirContent) {
            // test if the basename matches one of patterns
            $exception = false;
            foreach ($except as $pattern) {
                if ($pattern[0] == '*') { // for pattern like *.foo
                    if ($dirContent->getBasename() != $dirContent->getBasename(substr($pattern, 1))) {
                        $allIsDeleted = false;
                        $exception = true;
                        break;
                    }
                } elseif ($pattern == $dirContent->getBasename()) {
                    $allIsDeleted = false;
                    $exception = true;
                    break;
                }
            }
            if ($exception) {
                continue;
            }
            // file deletion
            if ($dirContent->isFile() || $dirContent->isLink()) {
                unlink($dirContent->getPathName());
            } else {
                // recursive directory deletion
                if (!$dirContent->isDot() && $dirContent->isDir()) {
                    $removed = self::removeExcept($dirContent->getPathName(), $except, true);
                    if (!$removed) {
                        $allIsDeleted = false;
                    }
                }
            }
        }
        unset($dir);
        unset($dirContent);

        // removes the parent directory
        if ($deleteDir && $allIsDeleted) {
            rmdir($path);
        }

        return $allIsDeleted;
    }
}
