<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2026 Laurent Jouanneau
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

use Jelix\FileUtilities\Directory;

class directoryTests extends \PHPUnit\Framework\TestCase
{
    public function testCreate()
    {
        $subdirectory = __DIR__.'/temp/subdirectory/';
        if (is_dir($subdirectory)) {
            if (is_file($subdirectory.'test.txt')) {
                unlink($subdirectory.'test.txt');
            }

            if (is_file($subdirectory.'test2.log')) {
                unlink($subdirectory.'test2.log');
            }
            rmdir($subdirectory);
        }

        $this->assertTrue(Directory::create($subdirectory));
        $this->assertTrue(is_dir($subdirectory));
    }


    /**
     * @depends testCreate
     */
    public function testCopy()
    {
        $subdirectory = __DIR__.'/temp/subdirectory/';
        $subdirectory2 = __DIR__.'/temp/subdirectory2/';

        if (is_file($subdirectory2.'test.txt')) {
            unlink($subdirectory2.'test.txt');
        }

        if (is_file($subdirectory2.'test2.log')) {
            unlink($subdirectory2.'test2.log');
        }

        file_put_contents($subdirectory.'test.txt', 'test');
        file_put_contents($subdirectory.'test2.log', 'test2');

        Directory::copy($subdirectory, $subdirectory2);
        $this->assertTrue(is_dir($subdirectory2));
        $this->assertTrue(is_file($subdirectory2.'test.txt'));
        $this->assertTrue(is_file($subdirectory2.'test2.log'));
        $this->assertEquals('test', file_get_contents($subdirectory2.'test.txt'));
        $this->assertEquals('test2', file_get_contents($subdirectory2.'test2.log'));
    }

    /**
     * @depends testCopy
     */
    public function testRemove()
    {
        $subdirectory2 = __DIR__.'/temp/subdirectory2/';
        Directory::remove($subdirectory2);
        $this->assertFalse(is_dir($subdirectory2));

    }

    /**
     * @depends testRemove
     */
    public function testRemoveContent()
    {
        $subdirectory = __DIR__.'/temp/subdirectory/';
        $subdirectory2 = __DIR__.'/temp/subdirectory2/';

        Directory::copy($subdirectory, $subdirectory2);
        // delete only content
        Directory::remove($subdirectory2, false);
        $this->assertTrue(is_dir($subdirectory2));
        $this->assertFalse(is_file($subdirectory2.'test.txt'));
        $this->assertFalse(is_file($subdirectory2.'test2.log'));
    }

    /**
     * @depends testRemoveContent
     */
    public function testRemoveExcept()
    {
        $subdirectory = __DIR__.'/temp/subdirectory/';
        $subdirectory2 = __DIR__.'/temp/subdirectory2/';
        Directory::copy($subdirectory, $subdirectory2);

        Directory::removeExcept($subdirectory2, array('*.log'));
        $this->assertTrue(is_dir($subdirectory2));
        $this->assertFalse(is_file($subdirectory2.'test.txt'));
        $this->assertTrue(is_file($subdirectory2.'test2.log'));
    }

}