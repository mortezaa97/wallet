<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Mortezaa97\Wallet\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Mortezaa97\Wallet\Http\Resources\ChargeResource;

class ChargeController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Charge::class);
        return ChargeResource::collection(Charge::all());
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Charge::class);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return new ChargeResource($charge);
    }

    public function show(Charge $charge)
    {
        Gate::authorize('view', $charge);
        return new ChargeResource($charge);
    }

    public function update(Request $request, Charge $charge)
    {
        Gate::authorize('update', $charge);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return new ChargeResource($charge);
    }

    public function destroy(Charge $charge)
    {
        Gate::authorize('delete', $charge);
        try {
            DB::beginTransaction();
            DB::commit();
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(),419);
        }
        return response()->json("با موفقیت حذف شد");
    }
}

