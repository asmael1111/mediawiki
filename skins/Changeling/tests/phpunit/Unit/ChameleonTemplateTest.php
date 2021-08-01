<?php
/**
 * This file is part of the MediaWiki skin Changeling.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
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

namespace Skins\Changeling\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Skins\Changeling\Changeling;
use Skins\Changeling\ChangelingTemplate;

/**
 * @uses \Skins\Changeling\ChangelingTemplate
 *
 * @group skins-Changeling
 * @group skins-Changeling-unit
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
class ChangelingTemplateTest extends TestCase {

	// This is to ensure that the original value is cached since we are unable
	// to inject the setting during testing
	protected $egChangelingLayoutFile = null;
	protected $egChangelingThemeFile = null;

	protected function setUp(): void {
		parent::setUp();

		$this->egChangelingLayoutFile = $GLOBALS['egChangelingLayoutFile'];
		$this->egChangelingThemeFile = $GLOBALS['egChangelingThemeFile'];
	}

	protected function tearDown(): void {
		$GLOBALS['egChangelingLayoutFile'] = $this->egChangelingLayoutFile;
		$GLOBALS['egChangelingThemeFile'] = $this->egChangelingThemeFile;

		parent::tearDown();
	}

	/**
	 * @covers \Skins\Changeling\ChangelingTemplate
	 */
	public function testCanConstruct() {
		$this->assertInstanceOf(
			'\Skins\Changeling\ChangelingTemplate',
			new ChangelingTemplate()
		);
	}

	/**
	 * @covers \Skins\Changeling\ChangelingTemplate
	 */
	public function testInaccessibleLayoutFileThrowsExeception() {
		$this->expectException( 'RuntimeException' );

		$GLOBALS['egChangelingLayoutFile'] = 'setInaccessibleLayoutFile';

		$skin = new Changeling();

		$instance = new ChangelingTemplate;
		$instance->set( 'skin', $skin );
		$instance->execute();
	}

}
