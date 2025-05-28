<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WifiSetting;

class WifiSettingController extends Controller
{
    public function edit($id)
    {
        $wifiSetting = WifiSetting::findOrFail($id);
        return view('setting', compact('wifiSetting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'ssid' => 'required|string',
            'password' => 'required|string',
        ]);

        // Update atau buat baru jika belum ada
        $wifiSetting = WifiSetting::first() ?? new WifiSetting();

        $wifiSetting->ssid = $request->ssid;
        $wifiSetting->password = $request->password;
        $wifiSetting->save();

        return redirect()->route('setting.edit', $wifiSetting->id)->with('success', 'Pengaturan WiFi berhasil diperbarui.');
    }
}
