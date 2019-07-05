# Kunoichi Theme Customizer

A handy PHP class to integrate WordPress Theme Customizer.

## Installation

```
composer require kunoichi/theme-customizer
```

In your theme's `functions.php`:

```
require __DIR__ . '/vendor/autoload.php';
```

## How to Implement

This library is an abstract class, so you have to implement it.

```
<?php

namespace YourNameSpace\Customizer;

use Kunoichi\ThemeCustomizer\CustomizerSetting;

class YourClass extends CustomizerSetting {

	protected $section_id = 'your_custom_setting';
	
	/**
	 * Override this function to register a new section.
	 */
	protected function section_setting() {
		return [
			'title'      => __( 'My Theme Setting', 'domain' ),
			'priority'   => 100,
		];
	}

	/**
	 * Return associative array for each fields.
	 *
	 * @return array
	 */
	protected function get_fields(): array {
		return [
			'yoru_serrint_id' => [
				'label'       => __( 'Ad after title', 'domain' ),
				'stored'      => 'option',  // 'option' or 'theme_mod'(default)
				'type'        => 'textarea' // Type of UI.
			],
		];
	}
}

```

And you can call them in your theme's `functions.php`.

```
YourNameSpace\Customizer\YourClass::register();
```

Or, simply scan directory for convenience.

```
foreach ( scandir( get_template_directory() . '/includes/customizer' ) as $file ) {
	// Get file name to create class.
	if ( ! preg_match( '/^(.*)\.php$/u', $file, $matches ) ) {
		continue;
	}
	$class_name = "YourNameSpace\\Customizer\\{$matches[1]}";
	if ( class_exists( $class_name ) ) {
		$class_name::register();
	}
}
```

## License

GPL 3.0 or later.