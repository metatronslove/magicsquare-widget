=== Magic Square Widget ===
Contributors: metatronslove
Donate link: https://www.buymeacoffee.com/metatronslove
Tags: magic square, mathematics, puzzle, generator, widget
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A customizable floating widget that generates magic squares of any size. Output as text, HTML, PDF, or PNG.

== Description ==

**Magic Square Widget** adds a beautiful floating calculator to your WordPress site, enabling you to create and explore magic squares:

* ✨ **Generate Magic Squares** - For any size (n >= 3) using various algorithms.
* 🔢 **Customizable Sum** - Adjust the target row/column/diagonal sum.
* 🧮 **Multiple Algorithms** - Siamese (odd), Strachey (doubly even), Durer (doubly even), Simple Exchange (doubly even), Strachey (singly even).
* 🌍 **Multi-language Support** - Interface in English, Turkish, and more via WordPress translations.
* 🎨 **Customizable** - Colors, positions, button styles (emoji, SVG, PNG).
* 📄 **Multiple Output Formats** - Tab-separated text, boxed text, HTML code, PDF, PNG.
* 💝 **Buy Me a Coffee** - Integrated support button.

= Features =

* Real-time generation with live preview.
* Rotate and mirror the generated square.
* Adjust cell dimensions for boxed output.
* Customizable ink color and cell rotation for HTML output.
* Paper size selection for PDF/PNG output (A3, A4, A5, or fit to content).
* Responsive design.
* Buy Me a Coffee integration.

= How to Use =

1. Install and activate the plugin.
2. Go to Settings → Magic Square.
3. Configure your button appearance and Buy Me a Coffee ID.
4. The widget will appear on your site as a floating button.
5. Click the button to open the widget, set your parameters, and generate magic squares!

= Support =

For support, feature requests, or bug reports, please visit the [plugin support forum](https://wordpress.org/support/plugin/magicsquare-widget).

== Installation ==

1. Upload the `magicsquare-widget` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings → Magic Square to configure.
4. Add your Buy Me a Coffee ID and customize colors.
5. The widget will automatically appear on your site.

== Frequently Asked Questions ==

= What is a magic square? =

A magic square is a grid of numbers where the sum of each row, column, and both main diagonals is the same. This sum is called the magic constant.

= What algorithms are used? =

The widget implements several classical methods:
* **Siamese Method:** For odd-sized squares.
* **Strachey Method:** For doubly even squares (size divisible by 4).
* **Dürer's Method:** A variation of the Strachey method.
* **Simple Exchange Method:** Another doubly even method.
* **Strachey's Singly Even Method:** For squares where size/2 is odd (e.g., 6x6, 10x10).

= Can I change the target sum? =

Yes! You can set any target row/column/diagonal sum. The widget will automatically adjust the numbers to achieve that sum while maintaining the magic square property.

= How do I add my Buy Me a Coffee button? =

Enter your Buy Me a Coffee username in the settings. The widget will automatically create a support tab with your custom button.

= Can I customize the button appearance? =

Absolutely! You can use emoji, custom SVG, or PNG images for the button. Also customize color and position.

== Screenshots ==

1. The floating widget button on your site.
2. The main interface with settings and tab-separated output.
3. Boxed text output with custom borders.
4. HTML code output with rotated cells.
5. PDF/PNG preview with size selection.

== Changelog ==

= 1.0.0 =
* Initial release.
* Magic square generation for any size (n>=3).
* Multiple algorithms: Siamese, Strachey, Durer, Simple Exchange.
* Output formats: Tab-separated, Boxed text, HTML, PDF, PNG.
* Customizable button (emoji, SVG, PNG).
* Buy Me a Coffee integration.
* Full RTL support.

== Upgrade Notice ==

= 1.0.0 =
Initial release. Please backup your site before upgrading.
