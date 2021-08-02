<?php
/**
 * This file is part of the MediaWiki skin Brave.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
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

namespace Skins\Brave\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Skins\Brave\Brave;
use Skins\Brave\BraveTemplate;

/**
 * @uses \Skins\Brave\BraveTemplate
 *
 * @group skins-Brave
 * @group skins-Brave-unit
 * @group mediawiki-databaseless
 *
 * @license   GPL-3.0-or-later
 * @since 1.0
 *
 * @author mwjames
 * @since 1.0
 * @ingroup Skins
 * @ingroup Test
 */
class BraveTemplateTest extends TestCase {

	// This is to ensure that the original value is cached since we are unable
	// to inject the setting during testing
	protected $bnwBraveLayoutFile = null;
	protected $bnwBraveThemeFile = null;

	protected function setUp(): void {
		parent::setUp();

		$this->datBraveLayoutFile = $GLOBALS['datBraveLayoutFile'];
		$this->datBraveThemeFile = $GLOBALS['datBraveThemeFile'];
	}

	protected function tearDown(): void {
		$GLOBALS['datBraveLayoutFile'] = $this->datBraveLayoutFile;
		$GLOBALS['datBraveThemeFile'] = $this->datBraveThemeFile;

		parent::tearDown();
	}

	/**
	 * @covers \Skins\Brave\BraveTemplate
	 */
	public function testCanConstruct() {
		$this->assertInstanceOf(
			'\Skins\Brave\BraveTemplate',
			new BraveTemplate()
		);
	}

	/**
	 * @covers \Skins\Brave\BraveTemplate
	 */
	public function testInaccessibleLayoutFileThrowsExeception() {
		$this->expectException( 'RuntimeException' );

		$GLOBALS['datBraveLayoutFile'] = 'setInaccessibleLayoutFile';

		$skin = new Brave();

		$instance = new BraveTemplate;
		$instance->set( 'skin', $skin );
		$instance->execute();
	}

}
