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

namespace Skins\Darkages\Tests\Unit\Components\Modifications;

use Skins\Darkages\Components\Component;
use Skins\Darkages\Components\Silent;
use Skins\Darkages\Tests\Unit\Components\GenericComponentTestCase;

/**
 * @coversDefaultClass \Skins\Darkages\Components\Modifications\Modification
 * @covers ::<private>
 * @covers ::<protected>
 *
 * @group   skins-darkages
 * @group   mediawiki-databaseless
 *
 * @author Stephan Gambke
 * @since 1.1
 * @ingroup Skins
 * @ingroup Test
 */
class GenericModificationTestCase extends GenericComponentTestCase {

	/**
	 * @param \DOMElement|null $domElement
	 *
	 * @return object
	 */
	public function getTestObject( \DOMElement $domElement = null ) {
		$component = $this->getModifiedComponent();
		return new $this->classUnderTest( $component, $domElement );
	}

	/**
	 * @return Component
	 */
	protected function getModifiedComponent() {
		$darkagesTemplate = $this->getDarkagesSkinTemplateStub();
		return new Silent( $darkagesTemplate );
	}
}
