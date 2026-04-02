<?php
/**
 * @author      Laurent Jouanneau
 * @copyright   2026 Laurent Jouanneau
 * @link        https://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

use Jelix\FileUtilities\File;

class fileTests extends \PHPUnit\Framework\TestCase
{
    public function testMimeType()
    {
        $this->assertEquals('text/xml', File::getMimeType(__DIR__.'/phpunit.xml'));
    }

    public function testMimeTypeFromFilename()
    {
        $this->assertEquals('application/xml', File::getMimeTypeFromFilename(__DIR__.'/phpunit.xml'));

        File::registerMimeTypes(array('xml'=>'text/xml'));

        $this->assertEquals('text/xml', File::getMimeTypeFromFilename(__DIR__.'/phpunit.xml'));
    }

    public function testWriteRead()
    {
        $fileName = __DIR__.'/temp/phpunit.txt';
        if (file_exists($fileName)) {
            unlink($fileName);
        }

        $this->assertTrue(File::write($fileName, 'test'));

        $this->assertEquals('test', File::read($fileName));
    }
}