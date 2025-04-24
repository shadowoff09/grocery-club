<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// TODO

class OperationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->card) {
            abort(404, 'Card not found.');
        }

        $operations = $user->card->operations()->orderByDesc('date')->paginate(10);

        return view('operations.index', compact('operations'));
    }

//    public function show(Operation $operation)
//    {
//        $this->authorize('view', $operation);
//        return view('operations.show', compact('operation'));
//    }
}
