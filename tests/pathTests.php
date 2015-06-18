<?php
/**
* @author      Laurent Jouanneau
* @copyright   2015 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

use Jelix\FileUtilities\Path;

class pathTests extends PHPUnit_Framework_TestCase {

    public function testShortestPath() {
        $this->assertEquals('.', Path::shortestPath('/', '/'));
        $this->assertEquals('.', Path::shortestPath('/aaaa', '/aaaa'));
        $this->assertEquals('.', Path::shortestPath('/aaaa', '/aaaa/.'));
        $this->assertEquals('.', Path::shortestPath('/aaaa/.', '/aaaa/.'));
        $this->assertEquals('.', Path::shortestPath('/aaaa/.', '/aaaa'));
        $this->assertEquals('.', Path::shortestPath('/aaaa/bbbb', '/aaaa/bbbb'));
        $this->assertEquals('aaaa', Path::shortestPath('/', '/aaaa/'));
        $this->assertEquals('..', Path::shortestPath('/aaaa', '/'));
        $this->assertEquals('../../dddd', Path::shortestPath('/aaaa/bbbb/cccc', '/aaaa/dddd/'));
        $this->assertEquals('../../dddd', Path::shortestPath('/aaaa/bbbb/cccc', '/aaaa/dddd/'));
        $this->assertEquals('../../dddd/eeeee', Path::shortestPath('/aaaa/bbbb/cccc', '/aaaa/dddd/eeeee'));
        $this->assertEquals('cccc', Path::shortestPath('/aaaa/bbbb', '/aaaa/bbbb/cccc'));
        $this->assertEquals('cccc/eeeee', Path::shortestPath('/aaaa/bbbb', '/aaaa/bbbb/cccc/eeeee'));
        $this->assertEquals('..', Path::shortestPath('/aaaa/bbbb/cccc', '/aaaa/bbbb/'));

        $this->assertEquals('.', Path::shortestPath('C:/', 'C:/'));
        $this->assertEquals('.', Path::shortestPath('C:/aaaa', 'C:/aaaa'));
        $this->assertEquals('.', Path::shortestPath('C:/aaaa/bbbb', 'C:/aaaa/bbbb'));
        $this->assertEquals('aaaa', Path::shortestPath('C:/', 'C:/aaaa/'));
        $this->assertEquals('..', Path::shortestPath('C:/aaaa', 'C:/'));
        $this->assertEquals('../../dddd', Path::shortestPath('C:/aaaa/bbbb/cccc', 'C:/aaaa/dddd/'));
        $this->assertEquals('../../dddd/eeeee', Path::shortestPath('C:/aaaa/bbbb/cccc', 'C:/aaaa/dddd/eeeee'));
        $this->assertEquals('cccc', Path::shortestPath('C:/aaaa/bbbb', 'C:/aaaa/bbbb/cccc'));
        $this->assertEquals('cccc/eeeee', Path::shortestPath('C:/aaaa/bbbb', 'C:/aaaa/bbbb/cccc/eeeee'));
        $this->assertEquals('..', Path::shortestPath('C:/aaaa/bbbb/cccc', 'C:/aaaa/bbbb/'));
        $this->assertEquals('D:/aaaa/dddd', Path::shortestPath('C:/aaaa/bbbb/cccc', 'D:/aaaa/dddd/'));
        $this->assertEquals('D:/', Path::shortestPath('C:/aaaa/bbbb/cccc', 'D:/'));
    }

    public function testNormalizePath() {
        $this->assertEquals('/', Path::normalizePath('/'));
        $this->assertEquals('/aaa/bbb/ccc', Path::normalizePath('/aaa/bbb/ccc/'));
        $this->assertEquals('/aaa/bbb/ccc', Path::normalizePath('/aaa////bbb/ccc/'));
        $this->assertEquals('/aaa/bbb/ccc', Path::normalizePath('/aaa/./bbb/ccc/'));
        $this->assertEquals('/aaa/bbb/ccc', Path::normalizePath('/aaa/./bbb/ccc/.'));
        $this->assertEquals('/aaa/bbb/ccc', Path::normalizePath('/aaa/bbb/ccc/.'));
        $this->assertEquals('/aaa/bbb/ccc', Path::normalizePath('/aaa/./bbb/./././ccc/'));
        $this->assertEquals('/aaa/ccc', Path::normalizePath('/aaa/bbb/../ccc/'));
        $this->assertEquals('/ccc', Path::normalizePath('/aaa/bbb/../../ccc/'));
        $this->assertEquals('/aaa/ccc', Path::normalizePath('/aaa/./bbb/../ccc/'));
        $this->assertEquals('/ccc', Path::normalizePath('/aaa/bbb/../../../../../ccc/'));

        $this->assertEquals('C:/', Path::normalizePath('C:\\'));
        $this->assertEquals('C:/aaa/bbb/ccc', Path::normalizePath('C:\\aaa\\bbb\\ccc\\'));
        $this->assertEquals('C:/aaa/bbb/ccc', Path::normalizePath('C:/aaa////bbb/ccc/'));
        $this->assertEquals('C:/aaa/bbb/ccc', Path::normalizePath('C:/aaa/./bbb/ccc/'));
        $this->assertEquals('C:/aaa/bbb/ccc', Path::normalizePath('C:/aaa/./bbb/./././ccc/'));
        $this->assertEquals('C:/aaa/ccc', Path::normalizePath('C:/aaa/bbb/../ccc/'));
        $this->assertEquals('C:/ccc', Path::normalizePath('C:/aaa/bbb/../../ccc/'));
        $this->assertEquals('C:/aaa/ccc', Path::normalizePath('C:/aaa/./bbb/../ccc/'));
        $this->assertEquals('C:/ccc', Path::normalizePath('C:/aaa/bbb/../../../../../ccc/'));
    }
}

