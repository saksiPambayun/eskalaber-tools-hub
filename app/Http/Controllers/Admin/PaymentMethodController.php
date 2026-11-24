<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\PaymentMethodController as BasePaymentMethodController;

class PaymentMethodController extends BasePaymentMethodController
{
    protected string $panel = 'admin';
}
