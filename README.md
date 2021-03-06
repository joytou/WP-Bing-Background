# WP-Bing-Background
Change your wordpress's background to the image which provided by Bing.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/wp-bing-background` directory, or install the plugin through the WordPress plugins screen directly.

2. Activate the plugin through the 'Plugins' screen in WordPress

3. Use the Settings->WP Bing Bakground screen to configure the plugin

## Frequently Asked Questions 

### Why does the background not show the image? 

1. Please make sure that the domain name in the 'Interface Domain Name' column is Bing's official domain name. As stated in the description, don't modify it casually unless it doesn't work or it makes you feel unsatisfactory.

2. Please try several more times to save the settings of this plugin so that it can regenerate the required static resource files.

3. Please check whether the directory 'wp-content -> uploads -> bing' exists, whether there were any files in it, and whether that files can be opened and read normally. If it is broken, please delete it and refresh your website again.

4. Please ensure that you do not use any cache, because once you use the cache, what you get will always be the result of the cache unless you clear the cache to ensure that it can get the latest static resource files.

5. If all the above methods cannot solve your problem, please contact me through email <joytou.wu@qq.com > to help you solve the problem in a timely manner.

## Screenshots

In directory: <a href="./wp-bing-background/">wp-bing-background</a> -> <a href="./wp-bing-background/assets/">assets</a>

1. Plugin setting screen

2. Plugin setting option's location

3. The final effect of the plugin is on the pc side.

4. The final effect of the plugin is on the mobile side.

## Changelog 

### 1.1.4

* Fixed that some translations did not display.
* Fixed that could not change the display value when slide the range on the mobile.
* Fixed that was reported error directly with no message because GD library is not supported.
* Add the function that user-defined the location of the saved directory for the plugin static files.
* Add the Warning that return the error message if the plugin can not service normally.

### 1.0.0

* The first version.

## Upgrade Notice

- null
