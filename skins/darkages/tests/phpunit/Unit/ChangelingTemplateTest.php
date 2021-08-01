<?php
/**
 * This file is part of the MediaWiki skin Darkages.
 *
 * @copyright 2013 - 2019, Stephan Gambke, mwjames
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

namespace Skins\Darkages\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Skins\Darkages\Darkages;
use Skins\Darkages\DarkagesTemplate;

/**
 * @uses \Skins\Darkages\DarkagesTemplate
 *
 * @group skins-darkages
 * @group skins-darkages-unit
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
class DarkagesTemplateTest extends TestCase {

	// This is to ensure that the original value is cached since we are unable
	// to inject the setting during testing
	protected $datDarkagesLayoutFile = null;
	protected $datDarkagesThemeFile = null;

	protected function setUp(): void {
		parent::setUp();

		$this->datDarkagesLayoutFile = $GLOBALS['datDarkagesLayoutFile'];
		$this->datDarkagesThemeFile = $GLOBALS['datDarkagesThemeFile'];
	}

	protected function tearDown(): void {
		$GLOBALS['datDarkagesLayoutFile'] = $this->datDarkagesLayoutFile;
		$GLOBALS['datDarkagesThemeFile'] = $this->datDarkagesThemeFile;

		parent::tearDown();
	}

	/**
	 * @covers \Skins\Darkages\DarkagesTemplate
	 */
	public function testCanConstruct() {
		$this->assertInstanceOf(
			'\Skins\Darkages\DarkagesTemplate',
			new DarkagesTemplate()
		);
	}

	/**
	 * @covers \Skins\Darkages\DarkagesTemplate
	 */
	public function testInaccessibleLayoutFileThrowsExeception() {
		$this->expectException( 'RuntimeException' );

		$GLOBALS['datDarkagesLayoutFile'] = 'setInaccessibleLayoutFile';

		$skin = new Darkages();

		$instance = new DarkagesTemplate;
		$instance->set( 'skin', $skin );
		$instance->execute();
	}

}
