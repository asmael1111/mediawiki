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

namespace Skins\Darkages\Tests\Unit\Components;

use Skins\Darkages\Components\NavbarHorizontal;

/**
 * @coversDefaultClass \Skins\Darkages\Components\NavbarHorizontal
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-darkages
 * @group   mediawiki-databaseless
 *
 * @author mwjames
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class NavbarHorizontalTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Darkages\Components\NavbarHorizontal';

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

		$darkagesTemplate = $this->getMockBuilder( '\Skins\Darkages\DarkagesTemplate' )
			->disableOriginalConstructor()
			->getMock();

		$darkagesTemplate->expects( $this->any() )
			->method( 'getMsg' )
			->will( $this->returnValue( $message ) );

		$instance = new NavbarHorizontal(
			$darkagesTemplate,
			$element
		);

		$matcher = [ 'tag' => 'nav' ];
		$this->assertTag( $matcher, $instance->getHtml() );
	}

}
