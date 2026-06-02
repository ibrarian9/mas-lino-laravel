<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        if (!session('no_meja')) {
            return redirect()->route('customer.home');
        }

        $cart = session('cart', []);
        $total = array_sum(array_column($cart, 'subtotal'));
        $cartCount = array_sum(array_column($cart, 'qty'));

        return view('customer.cart', compact('cart', 'total', 'cartCount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'id_menu' => 'required|exists:tb_menu,id_menu',
            'qty'     => 'integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->id_menu);
        $cart = session('cart', []);
        $key = 'item_' . $menu->id_menu;

        if (isset($cart[$key])) {
            $cart[$key]['qty'] += $request->input('qty', 1);
            $cart[$key]['subtotal'] = $cart[$key]['qty'] * $cart[$key]['harga'];
        } else {
            $qty = $request->input('qty', 1);
            $cart[$key] = [
                'id_menu'  => $menu->id_menu,
                'nama'     => $menu->nama_menu,
                'harga'    => $menu->harga_c1,
                'gambar'   => $menu->gambar,
                'qty'      => $qty,
                'subtotal' => $qty * $menu->harga_c1,
            ];
        }

        session(['cart' => $cart]);

        if ($request->wantsJson()) {
            $cartCount = array_sum(array_column($cart, 'qty'));
            return response()->json([
                'success' => true,
                'cartCount' => $cartCount,
                'message' => $menu->nama_menu . ' ditambahkan ke keranjang'
            ]);
        }

        return back()->with('success', $menu->nama_menu . ' ditambahkan ke keranjang');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'id_menu' => 'required',
        ]);

        $cart = session('cart', []);
        $key = 'item_' . $request->id_menu;

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session(['cart' => $cart]);
        }

        if ($request->wantsJson()) {
            $cartCount = array_sum(array_column($cart, 'qty'));
            $total = array_sum(array_column($cart, 'subtotal'));
            return response()->json(['success' => true, 'cartCount' => $cartCount, 'total' => $total]);
        }

        return back()->with('success', 'Item dihapus dari keranjang');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id_menu' => 'required',
            'qty'     => 'required|integer|min:0',
        ]);

        $cart = session('cart', []);
        $key = 'item_' . $request->id_menu;

        if (isset($cart[$key])) {
            if ($request->qty <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['qty'] = $request->qty;
                $cart[$key]['subtotal'] = $cart[$key]['qty'] * $cart[$key]['harga'];
            }
            session(['cart' => $cart]);
        }

        if ($request->wantsJson()) {
            $cartCount = array_sum(array_column($cart, 'qty'));
            $total = array_sum(array_column($cart, 'subtotal'));
            return response()->json([
                'success' => true,
                'cartCount' => $cartCount,
                'total' => $total,
                'item_subtotal' => isset($cart[$key]) ? $cart[$key]['subtotal'] : 0,
            ]);
        }

        return back();
    }
}
