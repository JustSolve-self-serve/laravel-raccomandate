<?php

namespace JustSolve\Raccomandate\Http\Controllers;

use Illuminate\Http\Request;
use SplFileObject;

class StateController {
    public function update(Request $request) {
        $callbackFile = new SplFileObject(__DIR__ . '/../../../callback.json', 'a');
        $callbackFile->fwrite(json_encode($request->all()) . "\n\n");
        return 'ok';
    }
}
