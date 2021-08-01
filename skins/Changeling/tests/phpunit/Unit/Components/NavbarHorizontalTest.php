<?php
/**
 * This file is part of the MediaWiki skin Changeling.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
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

namespace Skins\Changeling\Tests\Unit\Components;

use Skins\Changeling\Components\NavbarHorizontal;

/**
 * @coversDefaultClass \Skins\Changeling\Components\NavbarHorizontal
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-changeling
 * @group   mediawiki-databaseless
 *
 * @author mwjames
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class NavbarHorizontalTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Changeling\Components\NavbarHorizontal';

	/**
	 * @covers ::getHtml
	 */
	public function testGetHtml_containsNavElement() {
		$element = $this->getMockBuilder( '\DOMElement' )
			->disableOriginalConstructor()
			->getMock();

		$message = $this->getMockBuilder( '\Message' )
			->disableOriginalConstructor()
			->getMock();

		$changelingTemplate = $this->getMockBuilder( '\Skins\Changeling\ChangelingTemplate' )
			->disableOriginalConstructor()
			->getMock();

		$changelingTemplate->expects( $this->any() )
			->method( 'getMsg' )
			->will( $this->returnValue( $message ) );

		$instance = new NavbarHorizontal(
			$changelingTemplate,
			$element
		);

		$matcher = [ 'tag' => 'nav' ];
		$this->assertTag( $matcher, $instance->getHtml() );
	}

}
