<?php
/**
 * This file is part of the MediaWiki skin Changeling.
 *
 * @copyright 2013 - 2019, Stephan Gambke
 * @license   GPL-3.0-or-later
 *
 * The Changeling skin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the Free
 * Software Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * The Changeling skin is distributed in the hope that it will be useful, but
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

namespace Skins\Changeling\Tests\Unit\Components\NavbarHorizontal;

use Skins\Changeling\Components\Component;
use Skins\Changeling\Tests\Unit\Components\GenericComponentTestCase;
use Skins\Changeling\Tests\Util\MockupFactory;

/**
 * @coversDefaultClass \Skins\Changeling\Components\NavbarHorizontal\PersonalTools
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-changeling
 * @group   mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.6
 * @ingroup Skins
 * @ingroup Test
 */
class PersonalToolsTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Changeling\Components\NavbarHorizontal\PersonalTools';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_LoggedInUserHasNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsLoggedIn', true );
		$changelingTemplate = $factory->getChangelingSkinTemplateStub();
		$changelingTemplate->data = [ 'newtalk' => 'foo' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $changelingTemplate, $domElement );

		$this->assertTag( [ 'class' => 'pt-mytalk' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_LoggedInUserHasNoNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsLoggedIn', true );
		$changelingTemplate = $factory->getChangelingSkinTemplateStub();
		$changelingTemplate->data = [ 'newtalk' => '' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $changelingTemplate, $domElement );

		$this->assertNotTag( [ 'class' => 'pt-mytalk' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_LoggedOutUserHasNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsLoggedIn', false );
		$changelingTemplate = $factory->getChangelingSkinTemplateStub();
		$changelingTemplate->data = [ 'newtalk' => 'foo' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $changelingTemplate, $domElement );

		$this->assertTag( [ 'class' => 'pt-anontalk' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_LoggedOutUserHasNoNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$factory->set( 'UserIsLoggedIn', false );
		$changelingTemplate = $factory->getChangelingSkinTemplateStub();
		$changelingTemplate->data = [ 'newtalk' => '' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $changelingTemplate, $domElement );

		$this->assertNotTag( [ 'class' => 'pt-anontalk' ], $instance->getHtml() );
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoDefault( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$changelingTemplate = $factory->getChangelingSkinTemplateStub();
		$changelingTemplate->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				// Icons are rendered without link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'] ],
				[ 'foo', [ 'id' => 'pt-foo'], [ 'tag' => 'div' , 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'tag' => 'div' , 'link-class' => 'pt-bar' ] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $changelingTemplate, $domElement );
		$instance->getHtml();
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoIcons( $domElement ) {
		$domElement->setAttribute( 'showEcho', 'icons' );
		$factory = MockupFactory::makeFactory( $this );
		$changelingTemplate = $factory->getChangelingSkinTemplateStub();
		$changelingTemplate->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				// Icons are rendered without link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'] ],
				[ 'foo', [ 'id' => 'pt-foo'], [ 'tag' => 'div' , 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'tag' => 'div' , 'link-class' => 'pt-bar' ] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $changelingTemplate, $domElement );
		$instance->getHtml();
	}

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_ShowEchoLinks( $domElement ) {
		$domElement->setAttribute( 'showEcho', 'links' );
		$factory = MockupFactory::makeFactory( $this );
		$changelingTemplate = $factory->getChangelingSkinTemplateStub();
		$changelingTemplate->expects( $this->exactly( 4 ) )
			->method( 'makeListItem' )
			->withConsecutive(
				[ 'foo', [ 'id' => 'pt-foo'], [ 'tag' => 'div' , 'link-class' => 'pt-foo' ] ],
				[ 'bar', [ 'id' => 'pt-bar'], [ 'tag' => 'div' , 'link-class' => 'pt-bar' ] ],
				// Links are rendered with link-class
				[ 'notifications-alert', [ 'id' => 'pt-notifications-alert'],
					[ 'tag' => 'div' , 'link-class' => 'pt-notifications-alert' ] ],
				[ 'notifications-notice', [ 'id' => 'pt-notifications-notice'],
					[ 'tag' => 'div' , 'link-class' => 'pt-notifications-notice' ] ]
			);

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $changelingTemplate, $domElement );
		$instance->getHtml();
	}

}
