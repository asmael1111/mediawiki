<?php
/**
 * This file is part of the MediaWiki skin Darkages.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
 * @license   GPL-3.0-or-later
 *
 * The Darkages skin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * The Darkages skin is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @file
 * @ingroup Skins
 */

namespace Skins\Darkages\Tests\Integration;

use DOMDocument;
use Skins\Darkages\Tests\Util\XmlFileProvider;

/**
 * @coversNothing
 *
 * @group skins-darkages
 * @group skins-darkages-integration
 * @group mediawiki-databaseless
 *
 * @author mwjames
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class XmlLayoutFileValidityTest extends \PHPUnit\Framework\TestCase {

	public function testXmlValidityOfLayoutFiles() {
		$xmlFileProvider = new XmlFileProvider( __DIR__ . '/../../../layouts' );

		$listOfLayoutXmlFiles = $xmlFileProvider->getFiles();

		$this->assertNotEmpty( $listOfLayoutXmlFiles );
		$this->assertXmlFiles( $listOfLayoutXmlFiles );
	}

	protected function assertXmlFiles( $files ) {
		foreach ( $files as $file ) {
			$this->assertXmlDocumentLoad( $file );
		}
	}

	protected function assertXmlDocumentLoad( $file ) {
		$document = new DOMDocument();
		$document->validateOnParse = false;
		$this->assertTrue( $document->load( $file ) );
	}

}
