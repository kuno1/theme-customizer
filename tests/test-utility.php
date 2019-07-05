<?php

class UtilityTest extends \PHPUnit\Framework\TestCase {
	
	use \Kunoichi\ThemeCustomizer\Utilities;
	
	/**
	 * Check test case.
	 */
	public function test_camelize() {
		$this->assertEquals( 'foo_bar_fuga_hoge', $this->camelize( 'FooBarFugaHoge' ) );
		$this->assertEquals( 'wp_unit_test', $this->camelize( 'WP_Unit_Test' ) );
		$this->assertEquals( 'wp_groovy_master_class', $this->camelize( 'WP\Groovy\MasterClass' ) );
	}
	
}
