{**
*
* NOTICE OF LICENSE
*
* This product is licensed for one customer to use on one installation (test stores and multishop included).
* Site developer has the right to modify this module to suit their needs, but can not redistribute the module in
* whole or in part. Any other use of this module constitues a violation of the user agreement.
*
* DISCLAIMER
*
* NO WARRANTIES OF DATA SAFETY OR MODULE SECURITY
* ARE EXPRESSED OR IMPLIED. USE THIS MODULE IN ACCORDANCE
* WITH YOUR MERCHANT AGREEMENT, KNOWING THAT VIOLATIONS OF
* PCI COMPLIANCY OR A DATA BREACH CAN COST THOUSANDS OF DOLLARS
* IN FINES AND DAMAGE A STORES REPUTATION. USE AT YOUR OWN RISK.
*
*  @author    idnovate.com <info@idnovate.com>
*  @copyright 2019 idnovate.com
*  @license   See above
*}

{*module = array('name', 'id', 'description')*}
{$modules[0]=[
    'name' => 'Promotions and discounts - (3x2, reductions, campaigns)',
    'id' => '9129',
    'description' => "Offer attractive discounts to your customers! Boost your conversion with effective offers that motivate your customers to purchase! Increase your customer average ticket by encouraging them to buy more items to get an extra discount!"]
}
{$modules[1]=[
    'name' => 'Cash On Delivery With Fee/Surcharge Plus (COD)',
    'id' => '6337',
    'description' => "Charge a commission/fee to your customer when they choose cash on delivery/collect on delivery/pay on shipment!"]
}
{$modules[2]=[
    'name' => 'Price Increment/Reduction by group, category and more',
    'id' => '7422',
    'description' => "With our module you can set the product prices to your customers which do you want to really have. Increments, reductions (discounts) and massive fixed price changes with an unlimited conditions and combinations to modify your product prices."]
}
{$modules[3]=[
    'name' => 'Credit Card Offline Payment - Manual processing',
    'id' => '6270',
    'description' => "Accept payments by credit or debit card without contracting a virtual POS! Enable a basic payment method! Just get all data needed to charge the order with this card capture module!"]
}
{$modules[4]=[
    'name' => 'Easy Delete Orders Plus',
    'id' => '7113',
    'description' => "Delete orders safe and easily and all related information with just one click. And you can also erase all the related data like carts and invoices."]
}
{$modules[5]=[
    'name' => 'Content Protection - Secure your shop',
    'id' => '8382',
    'description' => "Content Protection provides complete security for your shop, so that the plagiarists could not copy the content and steal data or images from your site."]
}
{$modules[6]=[
    'name' => 'Super User - Log in as customer',
    'id' => '7280',
    'description' => "Log in to your shop as one of your customers! Help your customers to fill their shopping carts! Test the problems that your customers tell you about your store!"]
}
{$modules[7]=[
    'name' => 'Auto Change Language And Currency - Geolocation',
    'id' => '7363',
    'description' => "Automatically redirect customers to their local language and/or the currency by their location. Increase probability of purchase thanks to proper content presentation, familiar currency and language."]
}
{$modules[8]=[
    'name' => 'Add Sticky elements Cart, Menu, Product, Filters',
    'id' => '22465',
    'description' => "With this module you can make Sticky (Fixed) ecommerce elements: Cart, Main Menu, Header, Product Box (add to cart and information) and Filters when the page scrolls. This will improve the eCommerce User Experience (UX) for your customers."]
}
{$modules[9]=[
    'name' => 'Advanced Price Rounding',
    'id' => '22633',
    'description' => "Would you like to round the prices in your catalog product (including Swiss Round to 0.05)? With this module you could round the prices by Currency, Category, Product, Group, Customer, Country, Zone, Manufacturer and Supplier."]
}
{$modules[10]=[
    'name' => 'Force Currency at Checkout',
    'id' => '8913',
    'description' => "Would you like to have different currencies at your store but leave only some of them at checkout? Let your customers visit your store in a different currency than checkout and avoid them to convert it to their local currency."]
}
{$modules[11]=[
    'name' => 'Cookies Plus - EU Cookie law (notification + block)',
    'id' => '21644',
    'description' => "Comply with the EU cookie law using this module. This module lets you block the cookies until the customer gives his consent accepting the warning."]
}
{$modules[12]=[
    'name' => 'Popup on enter, on exit, when add to cart, newsletter',
    'id' => '23773',
    'description' => "Create as many popups as you can imagine. Inform your customers about promotions, sales, news or whatever you need. You can displayed them only for selected categories, products, manufacturers, suppliers, customer groups, countries and zones."]
}
{$modules[13]=[
    'name' => 'Facebook Messenger - Live chat',
    'id' => '24292',
    'description' => "Get in touch with your potential customers with Facebook Messenger live chat, the app used by more than 1 billion users."]
}
{$modules[14]=[
    'name' => 'Shipping premium flat rate',
    'id' => '24876',
    'description' => "Offer to your customers a Premium flat rate of shipments! Add an unlimited number of premium flat rate configurations and define it by customer group, carrier, zone and weight/price ranges."]
}
{$modules[15]=[
    'name' => 'Share cart - Link a cart at newsletters, forums, etc..',
    'id' => '26537',
    'description' => "Share an add to cart from url link. Compose an url to create a cart automatically. Use it in your newsletters. Use it to share in social networks. Use it to share with your customers easily."]
}
{$modules[16]=[
    'name' => 'WhatsApp Live Chat With Customers - WhatsApp Business',
    'id' => '26395',
    'description' => "Chat with your customers through WhatsApp, the most popular messaging app."]
}
{$modules[17]=[
    'name' => 'Hide price and disallow purchase of products',
    'id' => '26993',
    'description' => "The module allows to you to hide prices and disallow purchases of products with an incredible flexibility."]
}
{$modules[18]=[
    'name' => 'Minimum and maximum unit quantity to purchase',
    'id' => '27632',
    'description' => "Define the minimum and maximum purchase unit quantity of products. Also allows to set up the multiples or the increments of the product units."]
}
{$modules[19]=[
    'name' => 'Change or remove the decimals and format currency',
    'id' => '27821',
    'description' => "Essential module to remove decimals or change the number of decimals that you want to display in your product prices."]
}
{$modules[20]=[
    'name' => 'Edit specific prices',
    'id' => '39351',
    'description' => "You can now edit, visually and easily, any specific price of your shop without the need to delete it before."]
}

{capture}{$modules|@shuffle|escape:'htmlall':'UTF-8'}{/capture}

<link rel="stylesheet" type="text/css" href="{$this_path|escape:'htmlall':'UTF-8'}views/css/company.css">

<div id="idnovate" class="panel">
    <div class="panel-heading">
        <i class="icon-info"></i> Information
    </div>
    <div class="form-wrapper">
        <div class="form-group">
            <div class="row">
                <div class="col-lg-4">
                    <div class="row util-links">
                        <div class="col-lg-12">
                            <p>
                                <i class="icon-star"></i> <a target="_blank" title="Rate this module" href="https://addons.prestashop.com/en/ratings.php">Enjoying this module? How about rating on Addons?</a><br/><br/>
                                <i class="icon-external-link"></i> <a target="_blank" title="Documentation" href="{$this_path|escape:'htmlall':'UTF-8'}readme_en.pdf">Documentation</a><br/><br/>
                                <i class="icon-medkit"></i> <a target="_blank" title="Support" href="https://addons.prestashop.com/en/Write-to-developper?id_product={$support_id|escape:'htmlall':'UTF-8'}">Need help? Contact us</a><br/><br/>
                            </p>
                        </div>
                    </div>
                    <div class="col-xs-12 developers">
                        <p>Module developed by</p>
                        <a target="_blank" href="https://addons.prestashop.com/en/109_idnovate"><img src="{$this_path|escape:'htmlall':'UTF-8'}views/img/company/logo_idnovate.png" /></a><br /><br />
                    </div>
                    <div class="col-xs-12 partnership">
                        <p>We are PrestaShop partners!</p>
                        <a target="_blank" href="https://www.prestashop.com/en/experts/web-agencies/idnovate"><img src="{$this_path|escape:'htmlall':'UTF-8'}views/img/company/partner.png" /></a>
                    </div>
                </div>
                <div class="col-lg-8 module-list">
                    <div class="row">
                        <div class="col-lg-12">
                            {for $counter=0 to 2}
                                {if $modules[$counter|escape:'htmlall':'UTF-8']['id'] == $support_id}
                                    {capture}{$counter++|escape:'htmlall':'UTF-8'}{/capture}
                                {/if}

                                <div class="col-md-4 column">
                                    <div class="module module-modules">
                                        <a target="_blank" href="https://addons.prestashop.com/en/{$modules[$counter|escape:'htmlall':'UTF-8']['id']|escape:'htmlall':'UTF-8'}-.html" title="{$modules[$counter|escape:'htmlall':'UTF-8']['name']|escape:'htmlall':'UTF-8'}">
                                            <div class="module-head">
                                                <div class="module-image">
                                                    <img alt="{$modules[$counter|escape:'htmlall':'UTF-8']['name']|escape:'htmlall':'UTF-8'}" height="57" width="57" src="{$this_path|escape:'htmlall':'UTF-8'}views/img/company/{$modules[$counter|escape:'htmlall':'UTF-8']['id']|escape:'htmlall':'UTF-8'}.png">
                                                </div>
                                                <p class="title-block-module" title="{$modules[$counter|escape:'htmlall':'UTF-8']['name']|escape:'htmlall':'UTF-8'}">{$modules[$counter|escape:'htmlall':'UTF-8']['name']|escape:'htmlall':'UTF-8'}
                                                </p>
                                            </div>

                                            <div class="module-body">
                                                <div class="module-entry clearfix">
                                                    <p>{$modules[$counter|escape:'htmlall':'UTF-8']['description']|escape:'htmlall':'UTF-8'}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            {/for}
                        </div>
                    </div>
                    <div class="col-xs-12 addons-link">
                        <p><i class="icon-external-link"></i> <a target="_blank" href="https://addons.prestashop.com/en/109_idnovate"><strong>Check all our awesome modules at PrestaShop Addons Marketplace</a></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>