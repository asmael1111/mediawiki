<?php
/**
 * This file is part of the MediaWiki skin Brave.
 *
 * @copyright 2013 - 2019, Stephan Gambke
 * @license   GPL-3.0-or-later
 *
 * The Brave skin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * The Brave skin is distributed in the hope that it will be useful, but
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

namespace Skins\Brave\Tests\Unit\Components;

use Skins\Brave\Tests\Util\MockupFactory;

/**
 * @coversDefaultClass \Skins\Brave\Components\PersonalTools
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-brave
 * @group   mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class PersonalToolsTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Brave\Components\PersonalTools';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowNewtalkNotifier( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$braveTemplate = $factory->getBraveSkinTemplateStub();
		$braveTemplate->data = [ 'newtalk' => 'foo' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $braveTemplate, $domElement );

		$this->assertTag( [ 'class' => 'usermessage' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_HideNewtalkNotifier( $domElement ) {
		$domElement->setAttribute( 'hideNewtalkNotifier', true );

		$factory = MockupFactory::makeFactory( $this );
		$braveTemplate = $factory->getBraveSkinTemplateStub();
		$braveTemplate->data = [ 'newtalk' => 'foo' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $braveTemplate, $domElement );

		$this->assertNotTag( [ 'class' => 'usermessage' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoDefault( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$braveTemplate = $factory->getBraveSkinTemplateStub();
		$braveTemplate->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				[ 'foo', [ 'id' => 'pt-foo'], [ 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'link-class' => 'pt-bar' ] ],
				// Icons are rendered without link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $braveTemplate, $domElement );
		$instance->getHtml();
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoIcons( $domElement ) {
		$domElement->setAttribute( 'showEcho', 'icons' );
		$factory = MockupFactory::makeFactory( $this );
		$braveTemplate = $factory->getBraveSkinTemplateStub();
		$braveTemplate->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				[ 'foo', [ 'id' => 'pt-foo'], [ 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'link-class' => 'pt-bar' ] ],
				// Icons are rendered without link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $braveTemplate, $domElement );
		$instance->getHtml();
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoLinks( $domElement ) {
		$domElement->setAttribute( 'showEcho', 'links' );
		$factory = MockupFactory::makeFactory( $this );
		$braveTemplate = $factory->getBraveSkinTemplateStub();
		$braveTemplate->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				[ 'foo', [ 'id' => 'pt-foo'], [ 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'link-class' => 'pt-bar' ] ],
				// Links are rendered with link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'],
					[ 'link-class' => 'pt-notifications-alert' ] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'],
					[ 'link-class' => 'pt-notifications-notice' ] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $braveTemplate, $domElement );
		$instance->getHtml();
	}

}
