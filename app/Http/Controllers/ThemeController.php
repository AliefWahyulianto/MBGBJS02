<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function updateTheme(Request $request)
{
    $request->validate([
        'theme' => 'required|in:light,dark'
    ]);
    
    Setting::set('app_theme', $request->theme);
    session(['theme' => $request->theme]);
    
    return response()->json(['success' => true]);
}
}