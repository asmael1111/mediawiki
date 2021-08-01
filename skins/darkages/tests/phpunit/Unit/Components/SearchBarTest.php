<?php
/**
 * This file is part of the MediaWiki skin Darkages.
 *
 * @copyright 2013 - 2019, Stephan Gambke
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

namespace Skins\Darkages\Tests\Unit\Components;

use Skins\Darkages\Tests\Util\MockupFactory;

/**
 * @coversDefaultClass \Skins\Darkages\Components\SearchBar
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-darkages
 * @group   mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class SearchBarTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Darkages\Components\SearchBar';

	/**
	 * @covers ::getHTML
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHTML_ShowDefaultButtons( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$darkagesTemplate = $factory->getDarkagesSkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $darkagesTemplate, $domElement );
		$html = $instance->getHTML();

		self::assertTag( [ 'class' => 'mw-searchButton' ], $instance->getHTML() );
		self::assertTag( [ 'class' => 'searchGoButton' ], $instance->getHTML() );
	}

	/**
	 * @covers ::getHTML
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHTML_ShowBothButtons( $domElement ) {
		$domElement->setAttribute( 'buttons', 'search go' );

		$factory = MockupFactory::makeFactory( $this );
		$darkagesTemplate = $factory->getDarkagesSkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $darkagesTemplate, $domElement );
		$html = $instance->getHTML();

		self::assertTag( [ 'class' => 'mw-searchButton' ], $instance->getHTML() );
		self::assertTag( [ 'class' => 'searchGoButton' ], $instance->getHTML() );
	}

	/**
	 * @covers ::getHTML
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHTML_ShowOnlySearchButton( $domElement ) {
		$domElement->setAttribute( 'buttons', 'search' );

		$factory = MockupFactory::makeFactory( $this );
		$darkagesTemplate = $factory->getDarkagesSkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $darkagesTemplate, $domElement );
		$html = $instance->getHTML();

		self::assertTag( [ 'class' => 'mw-searchButton' ], $instance->getHTML() );
		self::assertNotTag( [ 'class' => 'searchGoButton' ], $instance->getHTML() );
	}

	/**
	 * @covers ::getHTML
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHTML_ShowOnlyGoButton( $domElement ) {
		$domElement->setAttribute( 'buttons', 'go' );

		$factory = MockupFactory::makeFactory( $this );
		$darkagesTemplate = $factory->getDarkagesSkinTemplateStub();

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $darkagesTemplate, $domElement );
		$html = $instance->getHTML();

		self::assertNotTag( [ 'class' => 'mw-searchButton' ], $instance->getHTML() );
		self::assertTag( [ 'class' => 'searchGoButton' ], $instance->getHTML() );
	}

}
