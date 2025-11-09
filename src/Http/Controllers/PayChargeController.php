<?php

declare(strict_types=1);
namespace Mortezaa97\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Mortezaa97\Wallet\Models\Charge;
use Mortezaa97\Wallet\WalletFacade;

class PayChargeController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            DB::beginTransaction();
            $wallet = WalletFacade::getOrCreateWallet(Auth::user(), 'IRT', Auth::user()->id);
            $charge = WalletFacade::charge($wallet, $request->price, Auth::user()->id);
            
            $paymentService = new PaymentService;
            $payment = $paymentService->pay(Auth::user(), $request->price, Charge::class, $charge->id, $request->gateway);
            DB::commit();
            return response()->json($payment);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response($exception->getMessage(), 419);
        }
    }
}