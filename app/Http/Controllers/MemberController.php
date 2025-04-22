<?php

namespace App\Http\Controllers;

use App\Models\member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function checkMemberHistory(Request $request)
    {
        $member = Member::where('no_phone', $request->no_phone)->first();

        if (!$member) {
            return response()->json(['exists' => false]);
        }

        $hasPurchases = $member->purchases()->exists();

        return response()->json([
            'exists' => true,
            'name' => $member->name,
            'points' => $member->poin,
            'hasPurchases' => $hasPurchases
        ]);
    }

    public function getOrCreate(Request $request)
    {
        $request->validate([
            'no_phone' => 'required|numeric',
            'name' => 'required|string|max:255',
        ]);

        $member = Member::firstOrCreate(
            ['no_phone' => $request->no_phone],
            ['name' => $request->name, 'poin' => 0]
        );

        return $member;
    }

    public function applyPoints(Member $member, int $total_price)
{
    $diskon_poin = 0;

    if ($member->poin > 0 && $member->purchases()->exists()) {
        $max_potongan = $member->poin;

        if ($max_potongan >= $total_price) {
            $diskon_poin = $total_price;
            $total_price = 0;
        } else {
            $diskon_poin = $max_potongan;
            $total_price -= $diskon_poin;
        }

        $member->decrement('poin', $diskon_poin);
    }

    return [
        'total_price' => $total_price,
        'diskon_poin' => $diskon_poin
    ];
}

    public function addPoints(Member $member, int $total_price)
    {
        $earned_points = floor($total_price * 0.01);
        $member->increment('poin', $earned_points);
    }
}
