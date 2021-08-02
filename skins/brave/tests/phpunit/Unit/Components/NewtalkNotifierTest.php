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
 * @coversDefaultClass \Skins\Brave\Components\NewtalkNotifier
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
class NewtalkNotifierTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Brave\Components\NewtalkNotifier';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 */
	public function testGetHtml_UserHasNewMessages( $domElement ) {
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
	public function testGetHtml_UserHasNoNewMessages( $domElement ) {
		$factory = MockupFactory::makeFactory( $this );
		$braveTemplate = $factory->getBraveSkinTemplateStub();
		$braveTemplate->data = [ 'newtalk' => '' ];

		/** @var Component $instance */
		$instance = new $this->classUnderTest( $braveTemplate, $domElement );

		$this->assertNotTag( [ 'class' => 'usermessage' ], $instance->getHtml() );
	}

}
