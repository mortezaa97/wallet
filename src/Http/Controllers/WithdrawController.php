<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Mortezaa97\Wallet\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Mortezaa97\Wallet\Http\Resources\WithdrawResource;

class WithdrawController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Withdraw::class);
        return WithdrawResource::collection(Withdraw::all());
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Withdraw::class);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return new WithdrawResource($withdraw);
    }

    public function show(Withdraw $withdraw)
    {
        Gate::authorize('view', $withdraw);
        return new WithdrawResource($withdraw);
    }

    public function update(Request $request, Withdraw $withdraw)
    {
        Gate::authorize('update', $withdraw);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return new WithdrawResource($withdraw);
    }

    public function destroy(Withdraw $withdraw)
    {
        Gate::authorize('delete', $withdraw);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return response()->json("با موفقیت حذف شد");
    }
}

