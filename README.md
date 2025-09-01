# WooCommerce Purchase Orders

[![WordPress](https://img.shields.io/badge/WordPress-4.7+-blue.svg)](https://wordpress.org/)
[![WooCommerce](https://img.shields.io/badge/WooCommerce-3.0+-green.svg)](https://woocommerce.com/)
[![License](https://img.shields.io/badge/License-GPL%20v2+-red.svg)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Version](https://img.shields.io/badge/Version-1.0.3-orange.svg)](https://github.com/Bbioon/wc-purchase-orders/releases)

A powerful WooCommerce plugin that enables purchase orders as a payment method, allowing customers to upload necessary documents and shop managers to review and approve orders seamlessly.

## 🚀 Features

- **Purchase Order Payment Gateway**: Integrates seamlessly with WooCommerce checkout
- **Document Upload Support**: Customers can upload PDF, DOC, DOCX files during checkout
- **User Restriction**: Control which users can access the purchase order payment method
- **Admin Management**: Shop managers can review documents and approve orders
- **Secure File Storage**: Documents are securely stored and accessible across the platform
- **Email Integration**: Purchase order documents are included in order detail emails
- **Multi-language Support**: Built with internationalization in mind
- **WooCommerce 3.0+ Compatible**: Works with the latest WooCommerce versions

## 📋 Requirements

- **WordPress**: 4.7 or higher
- **WooCommerce**: 3.0 or higher
- **PHP**: 7.0 or higher
- **Tested up to**: WordPress 6.8

## 🛠️ Installation

### Method 1: WordPress Admin (Recommended)
1. Go to **Plugins > Add New** in your WordPress admin
2. Search for "WooCommerce Purchase Orders"
3. Click **Install Now** and then **Activate**

### Method 2: Manual Installation
1. Download the plugin files
2. Upload the `wc-purchase-orders` folder to `/wp-content/plugins/`
3. Activate the plugin through **Plugins > Installed Plugins**

### Method 3: FTP Upload
1. Extract the plugin files
2. Upload via FTP to `/wp-content/plugins/wc-purchase-orders/`
3. Activate through WordPress admin

## ⚙️ Configuration

### 1. Enable the Payment Gateway
1. Go to **WooCommerce > Settings > Payments**
2. Find **Purchase Orders** in the payment methods list
3. Click **Set up** or **Manage**
4. Enable the payment method

### 2. Configure Settings
- **Enable/Disable**: Toggle the payment gateway on/off
- **Title**: Customize the payment method name shown to customers
- **Description**: Add instructions for customers
- **Restrict to Specific Users**: Control access to specific users only
- **Require Document Upload**: Make document upload mandatory or optional

### 3. User Permissions
1. Go to **Users > Profile** for specific users
2. Check "Can use Purchase Orders" to grant access
3. Save the profile

## 📖 Usage

### For Customers
1. Add products to cart and proceed to checkout
2. Select **Purchase Orders** as the payment method
3. Upload required documents (PDF, DOC, DOCX)
4. Complete the order

### For Shop Managers
1. Go to **WooCommerce > Orders**
2. Open the purchase order
3. Review uploaded documents
4. Approve or process the order as needed

## 🔧 File Structure

```
wc-purchase-orders/
├── admin/                          # Admin functionality
│   ├── class-bbpo-purchase-orders-admin.php
│   ├── css/                       # Admin styles
│   └── js/                        # Admin scripts
├── includes/                       # Core plugin classes
│   ├── class-bbpo-purchase-orders.php
│   ├── class-bbpo-purchase-orders-gateway.php
│   ├── class-bbpo-purchase-orders-files.php
│   └── ...
├── public/                         # Frontend functionality
│   ├── class-bbpo-purchase-orders-public.php
│   ├── css/                       # Frontend styles
│   ├── js/                        # Frontend scripts
│   └── icons/                     # File type icons
├── languages/                      # Translation files
├── wc-purchase-orders.php         # Main plugin file
└── README.md                      # This file
```

## 🎯 Supported File Types

- **PDF** (.pdf)
- **Microsoft Word** (.doc, .docx)

## 🔒 Security Features

- Secure file upload handling
- File type validation
- User permission checks
- Admin-only access to sensitive functions

## 🌐 Internationalization

The plugin supports multiple languages through WordPress's built-in internationalization system. Translation files are located in the `languages/` directory.

## 📝 Changelog

### Version 1.0.3
- **Added**: Improved security checks for file deletion on checkout
- **Added**: Enhanced styling for purchase orders form on checkout
- **Fixed**: User profile meta key "wcpo_can_user_purchase_orders"

### Version 1.0.2
- **Added**: "Restrict to Specific Users" setting
- **Added**: "Require Document Upload" setting
- **Added**: Admin notice for new settings

### Version 1.0.1
- **Update**: Plugin description improvements

### Version 1.0.0
- **Initial Release**: Core purchase order functionality

## ❓ Frequently Asked Questions

### Is this plugin compatible with the latest WooCommerce versions?
Yes, this plugin is compatible with WooCommerce versions 3.0 and higher.

### How do customers upload documents for their purchase orders?
Customers can upload necessary document files during the checkout process when selecting the 'Purchase Order' payment method.

### Can shop managers review and approve orders easily?
Yes, shop managers can conveniently review uploaded documents and approve orders directly from the WooCommerce order pages.

### Can I restrict purchase orders to specific users only?
Yes, you can enable user restriction and grant access to specific users through their profile settings.

### Are document uploads mandatory?
You can configure whether document uploads are required or optional through the plugin settings.

## 🐛 Troubleshooting

### Common Issues

1. **Payment method not showing**: Ensure WooCommerce is active and the plugin is properly configured
2. **File upload errors**: Check file size limits and supported file types
3. **Permission issues**: Verify user has "Can use Purchase Orders" enabled in their profile

### Debug Mode
Enable WordPress debug mode to get more detailed error information:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## 🤝 Contributing

We welcome contributions! Here's how you can help:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Setup
1. Clone the repository
2. Install dependencies (if any)
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This plugin is licensed under the GNU General Public License v2 or later. See the [LICENSE.txt](LICENSE.txt) file for details.

## 👨‍💻 Author

**Ahmad Wael** - [bbioon.com](https://www.bbioon.com)

### Connect with Ahmad
- 🐦 Twitter: [@devwael](https://twitter.com/devwael)
- 💼 Hire on Codeable: [Ahmad Wael on Codeable](https://www.codeable.io/developers/ahmad-wael?ref=MzT5A)
- 📺 YouTube: [Meet Ahmad Wael](https://www.youtube.com/watch?v=sBlZoJ9apTw)
- 🌐 Website: [bbioon.com](https://www.bbioon.com)

## 🙏 Credits

This plugin was created by Ahmad Wael, inspired by the needs of WooCommerce stores requiring purchase order functionalities.

## 📞 Support

For support, feature requests, or bug reports:

1. **GitHub Issues**: [Create an issue](https://github.com/Bbioon/wc-purchase-orders/issues)
2. **Contact**: Reach out through [bbioon.com](https://www.bbioon.com)
3. **Codeable**: Hire Ahmad for custom development work

---

**⭐ If you find this plugin helpful, please consider giving it a star on GitHub!**

[![GitHub stars](https://img.shields.io/github/stars/Bbioon/wc-purchase-orders.svg?style=social&label=Star)](https://github.com/Bbioon/wc-purchase-orders)
[![GitHub forks](https://img.shields.io/github/forks/Bbioon/wc-purchase-orders.svg?style=social&label=Fork)](https://github.com/Bbioon/wc-purchase-orders)
