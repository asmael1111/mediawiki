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

namespace Skins\Changeling\Tests\Unit\Components;

/**
 * @coversDefaultClass \Skins\Changeling\Components\FooterPlaces
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-changeling
 * @group   mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class FooterPlacesTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Changeling\Components\FooterPlaces';

	/**
	 * @covers ::getHtml
	 */
	public function testGetHtml_GetsAllKeys() {
		$changelingTemplate = $this->getChangelingSkinTemplateStub();

		$changelingTemplate->expects( $this->at( 1 ) )
			->method( 'get' )
			->with( $this->equalTo( 'privacy' ), $this->equalTo( null ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$changelingTemplate->expects( $this->at( 2 ) )
			->method( 'get' )
			->with( $this->equalTo( 'about' ), $this->equalTo( null ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$changelingTemplate->expects( $this->at( 3 ) )
			->method( 'get' )
			->with( $this->equalTo( 'disclaimer' ), $this->equalTo( null ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$instance = new $this->classUnderTest( $changelingTemplate );

		$instance->getHtml();
	}
}
