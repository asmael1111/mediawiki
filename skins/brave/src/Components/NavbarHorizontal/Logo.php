<?php
/**
 * File holding the NavbarHorizontal\Logo class
 *
 * This file is part of the MediaWiki skin Brave.
 *
 * @copyright 2013 - 2019, Stephan Gambke
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
 * @ingroup   Skins
 */

namespace Skins\Brave\Components\NavbarHorizontal;

use Skins\Brave\Components\Component;
use Skins\Brave\Components\Logo as GenLogo;

/**
 * The NavbarHorizontal\Logo class.
 *
 * Provides a Logo component to be included in a NavbarHorizontal component.
 *
 * @author Stephan Gambke
 * @since 1.6
 * @ingroup Skins
 */
class Logo extends Component {

	/**
	 * @return String
	 * @throws \MWException
	 */
	public function getHtml() {
		$logo = new GenLogo( $this->getSkinTemplate(), $this->getDomElement(), $this->getIndent() );
		$logo->addClasses( 'navbar-brand' );

		return $logo->getHtml();
	}

}
