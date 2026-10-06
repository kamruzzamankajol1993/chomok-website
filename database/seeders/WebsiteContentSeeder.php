<?php

namespace Database\Seeders;

use App\Models\WebsiteContent;
use Illuminate\Database\Seeder;

class WebsiteContentSeeder extends Seeder
{
    public function run(): void
    {
        WebsiteContent::query()->updateOrCreate(
            ['id' => 1],
            [
                'terms_and_conditions' => <<<'HTML'
<p>Welcome to Chomok. By using this website, creating an account, or placing an order, you agree to these Terms and Conditions.</p>
<h3>Orders and Availability</h3>
<p>All orders are subject to product availability, branch availability, delivery coverage, and final acceptance by Chomok. We may contact you if an item is unavailable or if an order needs clarification.</p>
<h3>Prices and Payment</h3>
<p>Prices displayed on the website may be updated from time to time. Applicable taxes, service charges, delivery fees, discounts, and the final payable amount are shown during checkout before you place the order.</p>
<h3>Customer Information</h3>
<p>You are responsible for providing accurate contact, delivery, and branch information. Please review your selected branch and delivery address carefully before submitting an order.</p>
<h3>Website Use</h3>
<p>You may not misuse the website, attempt unauthorized access, interfere with the ordering system, or use the service for unlawful purposes.</p>
<h3>Changes</h3>
<p>Chomok may update these terms when business, operational, or legal requirements change. The version published on this website will apply from the time it is posted.</p>
HTML,
                'privacy_policy' => <<<'HTML'
<p>Chomok respects your privacy and uses personal information only as needed to provide and improve our services.</p>
<h3>Information We Collect</h3>
<p>We may collect your name, email address, phone number, delivery address, order details, account information, and messages you send through the website.</p>
<h3>How We Use Information</h3>
<p>We use this information to create and manage accounts, process orders, arrange delivery, provide customer support, prevent misuse, and improve website and restaurant operations.</p>
<h3>Sharing</h3>
<p>Information may be shared with relevant Chomok staff, selected branches, delivery personnel, and service providers only when required to complete your order or operate the service. We do not sell personal information.</p>
<h3>Security and Retention</h3>
<p>We take reasonable measures to protect stored information and retain it only for operational, accounting, support, and legal needs.</p>
<h3>Your Choices</h3>
<p>You may contact Chomok through the website contact page if you need help regarding your account or personal information.</p>
HTML,
                'refund_policy' => <<<'HTML'
<p>We want every Chomok order to reach you correctly and in good condition. Refund or replacement requests are reviewed based on the circumstances of each order.</p>
<h3>Eligible Issues</h3>
<p>Please contact us as soon as possible if your order is missing an item, contains an incorrect item, arrives damaged, or has another significant quality problem.</p>
<h3>Review Process</h3>
<p>We may request the order number, photographs, or other details needed to verify the issue. Approved resolutions may include replacement, store credit, partial refund, or full refund depending on the case.</p>
<h3>Change of Mind</h3>
<p>Because food is prepared for each order, cancellations or refunds may not be available after preparation has started unless Chomok approves an exception.</p>
<h3>Payment Timing</h3>
<p>When a monetary refund is approved, processing time may depend on the original payment method and the payment service provider.</p>
HTML,
                'delivery_info' => <<<'HTML'
<p>Chomok provides delivery from participating branches and service areas shown during ordering.</p>
<h3>Branch Selection</h3>
<p>Please select your branch carefully during checkout. Branch selection is mandatory and helps us route your order to the correct location.</p>
<h3>Delivery Address</h3>
<p>Please provide a complete and accurate address, a reachable phone number, and any useful delivery notes. Incorrect or incomplete information can delay delivery.</p>
<h3>Delivery Time</h3>
<p>Estimated delivery times can vary because of order volume, food preparation, traffic, weather, distance, and other conditions outside our control.</p>
<h3>Delivery Fees</h3>
<p>Any applicable delivery fee is displayed in the checkout summary before the order is placed.</p>
<h3>Receiving Your Order</h3>
<p>Please keep your phone available after ordering. If our team cannot reach you or cannot access the delivery location, the order may be delayed or cancelled.</p>
HTML,
            ],
        );
    }
}
