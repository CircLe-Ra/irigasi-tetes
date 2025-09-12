<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class RelayController extends Controller
{
    public function get(Device $device) {
        return response()->json([
            'channels' => $device->channels()->orderBy('channel')->pluck('state')
        ]);
    }

    public function set(Device $device, $channel, Request $request) {
        $relay = $device->channels()->where('channel', $channel)->firstOrFail();
        $relay->update(['state' => $request->input('state')]);
        return response()->json(['success' => true]);
    }
}
