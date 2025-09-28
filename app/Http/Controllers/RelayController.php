<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Soil;
use Illuminate\Http\Request;

class RelayController extends Controller
{
    public function get(Device $device) {
        return response()->json([
            'channels' => $device->channels()->orderBy('channel')->pluck('state')
        ]);
    }

    public function set(Device $device, $channel) {
        $relay = $device->channels()->where('channel', $channel)->firstOrFail();
        $relay->update(['state' => !$relay->state]);
        return response()->json(['success' => true]);
    }

    //SOIL
    public function show($device)
    {
        $soil = Soil::where('id', $device)->firstOrFail();

        return response()->json([
            'threshold' => $soil->threshold,
            'reset' => $soil->reset,
            'device_relay_id' => $soil->device_id,
            'channel' => $soil->channel,
        ]);
    }

    public function reset($device)
    {
        $soil = Soil::where('device_id', $device)->firstOrFail();
        $soil->reset = 0;
        $soil->save();

        return response()->json(['status' => 'ok']);
    }

    public function updateSensor(Device $device, Request $request)
    {
        $soil = Soil::where('device_id', $device->id)->firstOrFail();
        $soil->update(['soil_value' => $request->soil_value, 'soil_percent' => $request->soil_percent]);

        $relay = $device->channels()->where('channel', $request->channel)->firstOrFail();
        if($request->soil_value > $soil->threshold){
            if(!$relay->state){
                $relay->update(['state' => true]);
            }
        }else{
            $relay->update(['state' => false]);
        }
        return response()->json(['status' => 'ok']);
    }
}
