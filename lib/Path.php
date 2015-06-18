<?php

/**
 * @author       Laurent Jouanneau
 * @copyright    2015 Laurent Jouanneau
 *
 * @link         http://jelix.org
 * @licence      MIT
 */

namespace Jelix\FileUtilities;

class Path
{
    /**
     * normalize a path : translate '..', '.', replace '\' by '/' and so on..
     * support windows path.
     *
     * @param string $path
     *
     * @return string the normalized path
     */
    public static function normalizePath($path)
    {
        list($prefix, $path, $absolute) = self::_normalizePath($path, false);
        if (!is_string($path)) {
            $path = implode('/', $path);
        }

        return $prefix.($absolute ? '/' : '').$path;
    }

    /**
     * calculate the shortest path between two directories.
     *
     * @param string $from absolute path from which we should start
     * @param string $to   absolute path to which we should go
     *
     * @return string relative path between the two path
     */
    public static function shortestPath($from, $to)
    {
        list($fromprefix, $from, $fromabsolute) = self::_normalizePath($from, true);
        list($toprefix, $to, $toabsolute) = self::_normalizePath($to, true);
        if (!$fromabsolute || !$toabsolute) {
            throw new \Exception('Absolute path is required');
        }
        if ($fromprefix != $toprefix) {
            return $toprefix.'/'.rtrim(implode('/', $to), '/');
        }
        while (count($from) && count($to) && $from[0] == $to[0]) {
            array_shift($from);
            array_shift($to);
        }

        if (!count($from)) {
            if (!count($to)) {
                return '.';
            }
            $prefix = '';
        } else {
            $prefix = rtrim(str_repeat('../', count($from)), '/');
        }
        if (!count($to)) {
            $suffix = '';
        } else {
            $suffix = implode('/', $to);
        }

        return $prefix.($suffix != '' && $prefix != '' ? '/' : '').$suffix;
    }

    protected static function _normalizePath($path, $alwaysArray)
    {
        $path = str_replace('\\', '/', $path);
        $path = preg_replace('#(/+)#', '/', $path);
        $prefix = '';
        $absolute = false;
        if (preg_match('#^([a-z]:)/#i', $path, $m)) {
            $prefix = strtoupper($m[1]);
            $path = substr($path, 2);
            $absolute = true;
        } else {
            $absolute = ($path[0] == '/');
        }
        if ($absolute && $path != '') {
            if ($path == '/') {
                $path = '';
            } else {
                $path = substr($path, 1);
            }
        }

        if (strpos($path, './') === false && substr($path, -1) != '.') {
            if ($alwaysArray) {
                if ($path == '') {
                    return array($prefix, array(), $absolute);
                }

                return array($prefix, explode('/', rtrim($path, '/')), $absolute);
            } else {
                if ($path == '') {
                    return array($prefix, $path, $absolute);
                }

                return array($prefix, rtrim($path, '/'), $absolute);
            }
        }
        $path = explode('/', $path);
        $path2 = array();
        $up = false;
        foreach ($path as $chunk) {
            if ($chunk === '..') {
                if (count($path2)) {
                    array_pop($path2);
                }
            } elseif ($chunk !== '' && $chunk != '.') {
                $path2[] = $chunk;
            }
        }

        return array($prefix, $path2, $absolute);
    }
}
