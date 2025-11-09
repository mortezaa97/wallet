<?php

declare(strict_types=1);

namespace Mortezaa97\Wallet\Http\Controllers;

use App\Http\Controllers\Controller;
use Mortezaa97\Wallet\Models\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Mortezaa97\Wallet\Http\Resources\ChargeResource;
use Mortezaa97\Wallet\WalletFacade;

class ChargeController extends Controller
{
    public function index()
    {
        return ChargeResource::collection(WalletFacade::getChargesByUser(Auth::user()))->response()->getData(true);
    }

    public function show(Charge $charge)
    {
        return new ChargeResource($charge);
    }

}

