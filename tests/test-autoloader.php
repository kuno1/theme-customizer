<?php


/**
 * Test autoloader.
 *
 * @package kunoichi
 */
class AutoloaderTest extends WP_UnitTestCase {

	public function test_register() {
		$result = \Kunoichi\ThemeCustomizer::register( 'Kunoichi\ThemeCustomizerTest\NotExists', __DIR__ . '/tests/src' );
		$this->assertWPError( $result );
		$result = \Kunoichi\ThemeCustomizer::register( 'Kunoichi\ThemeCustomizerTest',  __DIR__ . '/src' );
		$this->assertTrue( $result );
	}
}
