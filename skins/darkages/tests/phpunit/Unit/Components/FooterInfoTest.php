<?php
/**
 * This file is part of the MediaWiki skin Darkages.
 *
 * @copyright 2013 - 2014, Stephan Gambke
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

/**
 * @coversDefaultClass \Skins\Darkages\Components\FooterInfo
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
class FooterInfoTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Darkages\Components\FooterInfo';
	protected $componentUnderTest = 'FooterInfo';

	/**
	 * @covers ::getHtml
	 */
	public function testGetHtml_GetsAllKeys() {
		$darkagesTemplate = $this->getDarkagesSkinTemplateStub();

		$darkagesTemplate->expects( $this->at( 1 ) )
			->method( 'get' )
			->with( $this->equalTo( 'key1' ), $this->equalTo( null ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$darkagesTemplate->expects( $this->at( 2 ) )
			->method( 'get' )
			->with( $this->equalTo( 'key2' ), $this->equalTo( null ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$darkagesTemplate->expects( $this->at( 3 ) )
			->method( 'get' )
			->with( $this->equalTo( 'key3' ), $this->equalTo( null ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$darkagesTemplate->expects( $this->at( 4 ) )
			->method( 'get' )
			->with( $this->equalTo( 'key4' ), $this->equalTo( null ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$instance = new $this->classUnderTest( $darkagesTemplate );

		$instance->getHtml();
	}

}
