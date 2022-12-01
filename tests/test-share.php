<?php

class ShareTest extends WP_UnitTestCase {

	/**
	 * @var \Kunoichi\ThemeCustomizer\Models\Brand\Facebook
	 */
	protected $fb = null;

	function setUp():void {
		parent::setUp();
		$this->fb = new \Kunoichi\ThemeCustomizer\Models\Brand\Facebook();
		// create test.
		if ( ! $this->get_post( false ) ) {
			wp_insert_post( [
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_title'   => 'Facebook Share',
				'post_content' => 'Share this post.',
			] );
		}
	}

	/**
	 * Get post to share.
	 *
	 * @throws Exception
	 * @param bool $enforce
	 * @return WP_Post
	 */
	private function get_post( $enforce = true ) {
		$posts = get_posts( [
			'post_type'      => 'post',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			's'              => 'Facebook Share'
		] );
		if ( ! $posts && $enforce ) {
			throw new Exception( 'No post found.' );
		}
		return $posts ? $posts[0] : null;
	}

	/**
	 * Test url is valid.
	 *
	 * @throws Exception
	 */
	public function test_url() {
		$url = $this->fb->get_url( $this->get_post() );
		$this->assertRegExp( '#https://www\.facebook\.com/share\.php\?u=(.*)#u', $url );

		$tw = new \Kunoichi\ThemeCustomizer\Models\Brand\Twitter();
		$url = $tw->get_url( $this->get_post() );
		$this->assertRegExp( '#url=#u', $url );
		$this->assertRegExp( '#text=#u', $url );
	}

	/**
	 * Test labels are proper.
	 *
	 * @throws Exception
	 */
	public function test_label() {
		$this->assertEquals( 'Facebook', $this->fb->get_label( $this->get_post() ) );
		$hatena = new \Kunoichi\ThemeCustomizer\Models\Brand\HatenaBookmark();
		$this->assertEquals( 'Hatena Bookmark', $hatena->get_label() );
	}
}
