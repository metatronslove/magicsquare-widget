# Magic Square Widget for WordPress

A powerful, customizable floating widget that generates magic squares of any size. Supports multiple algorithms and output formats (text, HTML, PDF, PNG).

![WordPress Plugin Version](https://img.shields.io/wordpress/plugin/v/magicsquare-widget)
![WordPress Plugin Downloads](https://img.shields.io/wordpress/plugin/dt/magicsquare-widget)
![License](https://img.shields.io/github/license/metatronslove/magicsquare-widget)

## Features

- ✨ **Magic Square Generation** - For any size (n ≥ 3)
- 🔢 **Customizable Sum** - Set your own target row/column/diagonal total
- 🧮 **Multiple Algorithms** - Siamese, Strachey, Dürer, Simple Exchange
- 🌍 **Multi-language** - English, Turkish, and more via WordPress translations
- 🎨 **Customizable** - Colors, positions, button styles (emoji, SVG, PNG)
- 📄 **Multiple Output Formats** - Tab-separated, boxed text, HTML, PDF, PNG
- 🎯 **Interactive Controls** - Rotate, mirror, adjust cell dimensions
- 💝 **Buy Me a Coffee** - Integrated support button

## Installation

### WordPress.org
1. Go to Plugins → Add New
2. Search for "Magic Square Widget"
3. Install and activate

### Manual
1. Upload `magicsquare-widget` folder to `/wp-content/plugins/`
2. Activate via WordPress admin
3. Configure at Settings → Magic Square

## Requirements

- WordPress 5.0+
- PHP 7.2+
- jQuery (included with WordPress)

## Configuration

1. Enter your Buy Me a Coffee username
2. Choose button color and position
3. Select button type (emoji/SVG/PNG)
4. Save and enjoy!

## Development

### Local Setup
```bash
git clone https://github.com/metatronslove/magicsquare-widget.git
cd magicsquare-widget
# Symlink to your WordPress plugins directory
ln -s $(pwd) /path/to/wp-content/plugins/magicsquare-widget
```

### Building
No build process required - pure PHP/JavaScript.

### Translations
```bash
# Generate .pot file
wp i18n make-pot . languages/magicsquare-widget.pot
# Update .po files
msgmerge -U languages/tr_TR.po languages/magicsquare-widget.pot
# Compile .mo
msgfmt languages/tr_TR.po -o languages/tr_TR.mo
```

## License

GPL-2.0+ - See [LICENSE](LICENSE) file.

## Support

- [WordPress.org Forum](https://wordpress.org/support/plugin/magicsquare-widget)
- [GitHub Issues](https://github.com/metatronslove/magicsquare-widget/issues)

## ☕ Buy Me a Coffee

If you like my project, you can support me by buying me a coffee!

[!["Buy Me A Coffee"](https://www.buymeacoffee.com/assets/img/custom_images/orange_img.png)](https://buymeacoffee.com/metatronslove)

Thank you! 🙏
