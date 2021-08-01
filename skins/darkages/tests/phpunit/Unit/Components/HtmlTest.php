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

use Skins\Darkages\Components\Html;

/**
 * @coversDefaultClass \Skins\Darkages\Components\Html
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
class HtmlTest extends GenericComponentTestCase {

	protected $classUnderTest = '\Skins\Darkages\Components\Html';

	/**
	 * @covers ::getHtml
	 * @dataProvider domElementProviderFromSyntheticLayoutFiles
	 *
	 * @param \DOMElement $domElement
	 */
	public function testGetHtml_OnSyntheticLayoutXml( \DOMElement $domElement ) {
		$darkagesTemplate = $this->getDarkagesSkinTemplateStub();

		$expected = '';

		foreach ( $domElement->childNodes as $child ) {
			$expected .= $domElement->ownerDocument->saveHTML( $child );
		}

		$instance = new Html( $darkagesTemplate, $domElement );
		$actual = $instance->getHtml();

		$this->assertEquals( $expected, $actual );
	}
}
