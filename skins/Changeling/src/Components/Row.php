<?php
/**
 * File holding the Row class
 *
 * This file is part of the MediaWiki skin Changeling.
 *
 * @copyright 2013 - 2014, Stephan Gambke
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

namespace Skins\Changeling\Components;

use Skins\Changeling\ChangelingTemplate;

/**
 * The Row class.
 *
 * @author Stephan Gambke
 * @since 1.0
 * @ingroup Skins
 */
class Row extends Container {

	/**
	 * @param ChangelingTemplate $template
	 * @param \DOMElement|null $domElement
	 * @param int $indent
	 */
	public function __construct( ChangelingTemplate $template, \DOMElement $domElement = null,
		$indent = 0 ) {
		parent::__construct( $template, $domElement, $indent );
		$this->addClasses( 'row' );
	}
}
