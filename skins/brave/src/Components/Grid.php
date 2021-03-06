<?php
/**
 * File holding the Grid class
 *
 * This file is part of the MediaWiki skin Brave.
 *
 * @copyright 2013 - 2014, Stephan Gambke
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

namespace Skins\Brave\Components;

use Skins\Brave\BraveTemplate;

/**
 * The Grid class.
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 */
class Grid extends Container {

	private const ATTR_MODE = 'mode';
	private const MODE_FIXEDWIDTH = 'fixedwidth';
	private const MODE_FLUID = 'fluid';

	/**
	 * @param BraveTemplate $template
	 * @param \DOMElement|null $domElement
	 * @param int $indent
	 */
	public function __construct( BraveTemplate $template, \DOMElement $domElement = null,
		$indent = 0 ) {
		parent::__construct( $template, $domElement, $indent );

		if ( $this->isFluidMode() ) {
			$this->addClasses( 'container-fluid' );
		} else {
			$this->addClasses( 'container' );
		}
	}

	protected function isFluidMode() {
		return $this->getAttribute( self::ATTR_MODE, self::MODE_FIXEDWIDTH ) === self::MODE_FLUID;
	}

}
