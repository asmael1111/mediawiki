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
 * @coversDefaultClass \Skins\Changeling\Components\FooterIcons
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
class FooterIconsTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Changeling\Components\FooterIcons';

	/**
	 * @covers ::getHtml
	 */
	public function testGetHtml() {
		$changelingTemplate = $this->getChangelingSkinTemplateStub();

		$skin = $changelingTemplate->getSkin();

		$skin->expects( $this->at( 0 ) )
			->method( 'makeFooterIcon' )
			->with( $this->equalTo( 'icon1' ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$skin->expects( $this->at( 1 ) )
			->method( 'makeFooterIcon' )
			->with( $this->equalTo( 'icon2' ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$skin->expects( $this->at( 2 ) )
			->method( 'makeFooterIcon' )
			->with( $this->equalTo( 'icon3' ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$skin->expects( $this->at( 3 ) )
			->method( 'makeFooterIcon' )
			->with( $this->equalTo( 'icon4' ) )
			->will( $this->returnValue( 'SomeHTML' ) );

		$changelingTemplate->expects( $this->any() )
			->method( 'getFooterIconsWithImage' )
			->will( $this->returnValue( [
				'block1' => [ 'icon1', 'icon2' ],
				'block2' => [ 'icon3', 'icon4' ],
			] ) );

		$instance = new $this->classUnderTest( $changelingTemplate );

		$this->assertValidHTML( $instance->getHtml() );
	}

}
