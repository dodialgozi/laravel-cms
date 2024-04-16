<?php

namespace App\View\Components\Pdf;

use Illuminate\View\Component;

class Header extends Component
{
    public $logo;
    public $company;
    public $companyAddress;
    public $companyTelp;

    /**
     * logo => String; optional
     * company => String; optional
     * companyAddress => String; optional
     * companyTelp => String; optional
     */
    public function __construct($logo = null, $company = null, $companyAddress = null, $companyTelp = null)
    {
        $this->logo = $logo ?? fileThumbnail(getSetting('sistem_logo', asset('backend/assets/images/logo.png')), '');
        $this->company = $company ?? getSetting('sistem_company', env('APP_COMPANY'));
        $this->companyAddress = $companyAddress ?? getSetting('sistem_company_address');
        $this->companyTelp = $companyTelp ?? getSetting('sistem_company_telp');
    }

    public function render()
    {
        return view('components.pdf.header');
    }
}
