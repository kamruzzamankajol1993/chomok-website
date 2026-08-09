<?php

namespace App\Http\Controllers\website;

use App\Http\Controllers\Controller;
use App\Models\WebsiteContent;
use Illuminate\View\View;

class ExtrapageController extends Controller
{
    public function privacyPolicy(): View
    {
        return view('website.extrapage.privacy-policy', ['content' => WebsiteContent::current()->privacy_policy]);
    }

    public function termsAndConditions(): View
    {
        return view('website.extrapage.termandcondition', ['content' => WebsiteContent::current()->terms_and_conditions]);
    }

    public function refundPolicy(): View
    {
        return view('website.extrapage.return-policy', ['content' => WebsiteContent::current()->refund_policy]);
    }

    public function deliveryPolicy(): View
    {
        return view('website.extrapage.delivery-policy', ['content' => WebsiteContent::current()->delivery_info]);
    }
}
