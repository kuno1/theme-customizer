# Kunoichi Theme Customizer

A handy PHP class to integrate WordPress Theme Customizer.

[![Travis CI Master](https://travis-ci.org/kuno1/theme-customizer.svg?branch=master)](https://travis-ci.org/kuno1/theme-customizer)

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

```php
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

```php
YourNameSpace\Customizer\YourClass::get_instance();
```

Or, simply scan directory for convenience. Let's suppose that your theme is `my-theme` and directory is PSR-0 ready like below:

```
my-theme
└src
  └ MyBrand
     └ MyTheme
        └ Customizers
          ├ ColorSetting
          └ AdSetting
```

You should call like this:

```php
Kunoichi\ThemeCustomizer::register( 'YourBrand\YourTheme\Customizers' );
```

### Arguments

`$namespace`

Your PSR-0 name space. Directory will be scan recursively.

`$direcotry`

If not set, default is `get_template_directory() . '/src`, which seem to be a common location in theme development.

## Predefined Classes

Some customizers are predefined for convenience.
See `src/Kunoichi\ThemeCustomizer\Pattern`.

### 1. Meta and SEO

`Kunoichi\ThemeCutomizer\Pattern\Seo` adds HTML meta information like meta-description, OGP, etc.

This customizer is userful to provide basic SEO features.
For more advanced features, you should recommmend some plugins.

- Google Analytics or Google Tag Manager scirpt.
- Default and Site top OGP image.
- Top page description.
- Option to stop all feature(in plugins' favor).

### 2. Sharing

`Kunoichi\ThemeCutomizer\Pattern\Share` adds share buttons for SNS. The only codes you should implement:

- Write code to display share buttons via `Kunoichi\ThemeCutomizer\Pattern\Share::render( $position )`

Available services are listed in `src/Kunoithi/ThemeCustomizer/Models/Brand`

## Development

[wp-env](https://developer.wordpress.org/block-editor/packages/packages-env/) is supported for instant development. You need node, npm, and docker installed.

```bash
# Install libraries.
npm install
# Start Container
npm start
# wp-env commands are available via npm.
npm run env stop
```

## License

GPL 3.0 or later.