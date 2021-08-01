<?php
/**
 * File holding the NavbarHorizontal\PersonalTools class
 *
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
 * @ingroup   Skins
 */

namespace Skins\Changeling\Components\NavbarHorizontal;

use Skins\Changeling\Components\Component;
use Skins\Changeling\Components\SearchBar as GenericSearchBar;

/**
 * The NavbarHorizontal\PersonalTools class.
 *
 * Provides a SearchBar component to be included in a NavbarHorizontal component.
 *
 * @author Stephan Gambke
 * @since 1.6
 * @ingroup Skins
 */
class SearchBar extends Component {

	/**
	 * @return String
	 */
	public function getHtml() {
		$search = new GenericSearchBar( $this->getSkinTemplate(), $this->getDomElement(),
			$this->getIndent() );
		$search->addClasses( 'navbar-form' );

		return $search->getHtml();
	}

}
