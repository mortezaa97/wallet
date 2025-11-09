<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Mortezaa97\Wallet\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Mortezaa97\Wallet\Http\Resources\WithdrawResource;
use Mortezaa97\Wallet\WalletFacade;

class WithdrawController extends Controller
{
    public function index()
    {
        return WithdrawResource::collection(WalletFacade::getWithdrawsByUser(Auth::user()))->response()->getData(true);
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $wallet = WalletFacade::getOrCreateWallet(Auth::user(), 'IRT', Auth::user()->id);
            $withdraw = WalletFacade::withdraw($wallet, $request->amount, $request->bank_id, Auth::user()->id);
            DB::commit();
            return response()->json("با موفقیت ثبت شد");
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json($exception->getMessage(),419);
        }
    }
    public function show(Withdraw $withdraw)
    {
        return new WithdrawResource($withdraw);
    }
    
}

