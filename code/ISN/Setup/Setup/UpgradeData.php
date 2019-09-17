<?php
namespace ISN\Setup\Setup;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
class UpgradeData implements UpgradeDataInterface
{
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            if ($installer->getTableRow($installer->getTable('cms_page'), 'page_id', 2)) {
                $installer->updateTableRow(
                    $installer->getTable('cms_page'),
                    'page_id',
                    2,
                    'content',
                    '<div data-content-type="row" data-appearance="contained" data-element="main"><div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-element="inner" style="justify-content: flex-start; display: flex; flex-direction: column; background-position: left top; background-size: cover; background-repeat: no-repeat; background-attachment: scroll; border-style: none; border-width: 1px; border-radius: 0px; margin: 0px 0px 10px; padding: 10px;"><div data-content-type="banner" data-appearance="poster" data-show-button="never" data-show-overlay="never" data-element="main" style="margin: 0px;"><div data-element="empty_link"><div class="pagebuilder-banner-wrapper" data-background-images="{\&quot;desktop_image\&quot;:\&quot;{{media url=wysiwyg/banner_1.png}}\&quot;}" data-element="wrapper" style="background-position: left top; background-size: cover; background-repeat: no-repeat; background-attachment: scroll; border-style: none; border-width: 1px; border-radius: 0px;"><div class="pagebuilder-overlay pagebuilder-poster-overlay" data-overlay-color="" data-element="overlay" style="border-radius: 0px; min-height: 300px; background-color: transparent; padding: 40px;"><div class="pagebuilder-poster-content"><div data-element="content"></div></div></div></div></div></div></div></div>'
                );
            }
            if ($installer->getTableRow($installer->getTable('cms_block'), 'block_id', 3)) {
                $installer->updateTableRow(
                    $installer->getTable('cms_block'),
                    'block_id',
                    3,
                    'content',
                    '<div class="header-cms-content"></div>'
                );
            }
            if ($installer->getTableRow($installer->getTable('cms_block'), 'block_id', 4)) {
                $installer->updateTableRow(
                    $installer->getTable('cms_block'),
                    'block_id',
                    4,
                    'content',
                    '<div class="argento-grid">
    <div class="col-md-9">
        <ul class="footer links argento-grid">
            <li class="col-md-3 col-xs-6">
                <div class="h4">About us</div>
                <ul>
                    <li><a href="{{store direct_url=\'about\'}}">About Us</a></li>
                    <li><a href="{{store direct_url=\'our-company\'}}">Our company</a></li>
                    <li><a href="{{store direct_url=\'carriers\'}}">Carriers</a></li>
                    <li><a href="{{store direct_url=\'shipping\'}}">Shipping</a></li>
                </ul>
            </li>
            <li class="col-md-3 col-xs-6">
                <div class="h4">Customer center</div>
                <ul>
                    <li><a href="{{store direct_url=\'customer/account\'}}">My Account</a></li>
                    <li><a href="{{store direct_url=\'sales/order/history\'}}">Order Status</a></li>
                    <li><a href="{{store direct_url=\'wishlist\'}}">Wishlist</a></li>
                    <li><a href="{{store direct_url=\'exchanges\'}}">Returns and Exchanges</a></li>
                </ul>
            </li>
            <li class="col-md-3 col-xs-6">
                <div class="h4">Info</div>
                <ul>
                    <li><a href="{{store direct_url=\'privacy\'}}">Privacy policy</a></li>
                    <li><a href="{{store direct_url=\'delivery\'}}">Delivery information</a></li>
                    <li><a href="{{store direct_url=\'returns\'}}">Returns policy</a></li>
                </ul>
            </li>
            <li class="col-md-3 col-xs-6">
                <div class="h4">Contacts</div>
                <ul>
                    <li><a href="{{store direct_url=\'contacts\'}}">Contact Us</a></li>
                    <li><a href="{{store direct_url=\'location\'}}">Store location</a></li>
                </ul>
            </li>
        </ul>
    </div>
    <div class="col-md-3 footer-contacts">
        <div class="h4">Visit Commercial Tire</div>
        <address style="margin-bottom: 10px;">
            2727 Interstate Drive<br>
            Lakeland, FL 33805<br>
            <strong>1.863.603.0777</strong><br>
        </address>
        <a href="{{store direct_url=\'map\'}}" title="Show map">get directions</a><br>
        <img width="200" height="60" style="margin-top: 10px;"
            src="{{view url=\'images/security_sign.gif\'}}"
            srcset="{{view url=\'images/security_sign@2x.gif\'}} 2x"
            alt="Security Seal"
        />
    </div>
</div>'
                );
            }
        }
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            if ($installer->getTableRow($installer->getTable('cms_page'), 'page_id', 2)) {
                $installer->updateTableRow(
                    $installer->getTable('cms_page'),
                    'page_id',
                    2,
                    'content',
                    '<div data-content-type="row" data-appearance="contained" data-element="main">&nbsp;TEST ISN HOMEPAGE</div>'
                );
                $installer->updateTableRow(
                    $installer->getTable('cms_page'),
                    'page_id',
                    2,
                    'title',
                    'ISN Home Page'
                );
                $installer->updateTableRow(
                    $installer->getTable('cms_page'),
                    'page_id',
                    2,
                    'content_heading',
                    'ISN Home Page'
                );
            }
        }
        if (version_compare($context->getVersion(), '1.0.8', '<')) {
            if ($installer->getTableRow($installer->getTable('cms_page'), 'page_id', 2)) {
                $installer->updateTableRow(
                    $installer->getTable('cms_page'),
                    'page_id',
                    2,
                    'content',
                    '<div data-content-type="row" data-appearance="contained" data-element="main">&nbsp;TEST ISN HOMEPAGE 8</div>'
                );
                $installer->updateTableRow(
                    $installer->getTable('cms_page'),
                    'page_id',
                    2,
                    'title',
                    'ISN Home Page 8'
                );
                $installer->updateTableRow(
                    $installer->getTable('cms_page'),
                    'page_id',
                    2,
                    'content_heading',
                    'ISN Home Page 8'
                );
            }
        }
    }
}
