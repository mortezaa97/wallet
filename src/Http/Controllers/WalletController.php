<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Mortezaa97\Wallet\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Mortezaa97\Wallet\Http\Resources\WalletResource;
use Mortezaa97\Wallet\WalletFacade;

class WalletController extends Controller
{
    public function index()
    {
        return WalletResource::collection(WalletFacade::getWalletsByUser(Auth::user()->id))->response()->getData(true);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Wallet::class);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return new WalletResource($wallet);
    }

    public function show(Wallet $wallet)
    {
        Gate::authorize('view', $wallet);
        return new WalletResource($wallet);
    }

    public function update(Request $request, Wallet $wallet)
    {
        Gate::authorize('update', $wallet);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return new WalletResource($wallet);
    }

    public function destroy(Wallet $wallet)
    {
        Gate::authorize('delete', $wallet);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return response()->json("با موفقیت حذف شد");
    }
}

