<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Store;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class WebApiController extends Controller
{
    public function read_category()  //list all category
    {
        $categories = Category::select('id', 'english_name', 'arabic_name', 'kurdish_name', 'urdu_name', 'indian_name', 'logo', 'background_color')->get();
        return response()->json(['category' => $categories]);
    } 
    public function read_store(Request $request, $category_id)  //list all store
    {
        $stores = Store::select('id', 'name', 'logo', 'cat_id')
            ->where('cat_id', $category_id)
            ->whereIn('id', Product::where('quantity', '>', 0)->select('store_id'))
            ->get();
        return response()->json(['store' => $stores]);
    } 
    public function read_products(Request $request, $store_id) //list all product
    {
        $products = Product::select('id', 'store_id', 'quantity', 'name', 'img_logo', 'img_back', 'price', 'currency')
            ->where('store_id', $store_id)
            ->get();
        return response()->json(['product' => $products]);
    }
    public function read_slider()  //list all slider
    {
        $slider = Slider::select('id', 'image', 'text')->get();
        return response()->json(['slider' => $slider]);
    } 
    public function register_user(Request $request)
    {
        $email             = strtolower($request->input('email'));
        $social_media_type = $request->input('social_media_type');
        $social_media_id   = $request->input('social_media_id');

        if (UserProfile::where('email', $email)->count() === 0) {
            $mobile        = $request->input('mobile');
            $register_type = $request->input('register_type');
            if (UserProfile::where('mobile', $mobile)->count() === 0) {
                $id = UserProfile::insertGetId([
                    'email'             => $email,
                    'name'              => $request->input('name'),
                    'mobile'            => $mobile,
                    'account_password'  => Hash::make($request->input('account_password')),
                    'country_id'        => $request->input('country_id'),
                    'points'            => 0,
                    'register_type'     => $register_type,
                    'social_media_type' => $social_media_type,
                    'social_media_id'   => $social_media_id,
                ]);
                DB::table('fcm_token')->insertGetId([
                    'user_id'    => $id,
                    'fcm-token'  => $request->input('fcm_token'),
                    'devicetype' => $register_type,
                ]);
                $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'is_email_verified', 'is_mobile_verified', 'loc_lat', 'loc_lng', 'apartment_no', 'street_name', 'city', 'notes', 'location_label', 'social_media_type')
                    ->where('id', $id)->get();
                return response()->json(['error_code' => $user]);
            }
            return response()->json(['error_code' => 2]);
        }
        return response()->json(['error_code' => 1]);
    }
    
    public function update_userprofile(Request $request)
    {
        $data         = ['updated_at' => date('Y-m-d H:i:s')];
        $user_id      = $request->input('user_id');
        $email        = strtolower($request->input('email'));
        $mobile       = $request->input('mobile');

        if ($request->has('name'))             $data['name']             = $request->input('name');
        if ($request->has('country_id'))       $data['country_id']       = $request->input('country_id');
        if ($request->has('loc_lat'))          $data['loc_lat']          = $request->input('loc_lat');
        if ($request->has('loc_lng'))          $data['loc_lng']          = $request->input('loc_lng');
        if ($request->has('apartment_no'))     $data['apartment_no']     = $request->input('apartment_no');
        if ($request->has('street_name'))      $data['street_name']      = $request->input('street_name');
        if ($request->has('city'))             $data['city']             = $request->input('city');
        if ($request->has('notes'))            $data['notes']            = $request->input('notes');
        if ($request->has('loc_label'))        $data['loc_label']        = $request->input('loc_label');
        if ($request->has('account_password')) $data['account_password'] = Hash::make($request->input('account_password'));

        $user         = UserProfile::select('id', 'email', 'mobile')->where('id', $user_id)->get();
        $email_count  = -1;
        $mobile_count = -1;

        if (!$user->isEmpty()) {
            if ($email !== strtolower($user[0]->email)) {
                $email_count = UserProfile::where('email', $email)->where('id', '!=', $user_id)->count();
            }
            if ($mobile !== strtolower($user[0]->mobile)) {
                $mobile_count = UserProfile::where('mobile', $mobile)->where('id', '!=', $user_id)->count();
            }

            if ($email_count > 0) {
                $user = 1;
            } elseif ($mobile_count > 0) {
                $user = 2;
            } else {
                // email and mobile not changed only update profile fields
                if ($email_count === -1 && $mobile_count === -1) {
                    UserProfile::where('id', $user_id)->update($data);
                // email changed but not found in db
                } elseif ($email_count === 0 && $mobile_count === -1) {
                    $data['is_email_verified'] = 0;
                    $data['email_code']        = Str::random(6);
                    UserProfile::where('id', $user_id)->update($data);
                // mobile changed but not found in db
                } elseif ($email_count === -1 && $mobile_count === 0) {
                    $data['is_mobile_verified'] = 0;
                    $data['sms_code']           = Str::random(6);
                    UserProfile::where('id', $user_id)->update($data);
                // email and mobile changed and not found in db
                } elseif ($email_count === 0 && $mobile_count === 0) {
                    $data['is_mobile_verified'] = 0;
                    $data['sms_code']           = Str::random(6);
                    $data['is_email_verified']  = 0;
                    $data['email_code']         = Str::random(6);
                    UserProfile::where('id', $user_id)->update($data);
                }
                $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'is_email_verified', 'is_mobile_verified', 'loc_lat', 'loc_lng', 'apartment_no', 'street_name', 'city', 'notes', 'location_label', 'social_media_type')
                    ->where('id', $user_id)->get();
            }
        }
        return response()->json(['user' => $user]);
    }
	
	
    
    public function send_code(Request $request)
    {
        $user_id   = $request->input('user_id');
        $code_type = $request->input('code_type');

        try {
            if ($code_type === 'email') {
                UserProfile::where('id', $user_id)->update(['email_code' => Str::random(6)]);
                //send email
            } elseif ($code_type === 'sms') {
                UserProfile::where('id', $user_id)->update(['sms_code' => Str::random(6)]);
                // send sms
            }
            return response()->json(['code' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['code' => -1]);
        }
    }
	
    public function verify_user(Request $request)
    {
        $user_id = $request->input('user_id');

        try {
            UserProfile::where('id', $user_id)->update(['is_mobile_verified' => 1, 'sms_code' => '']);
            return response()->json(['verify' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['verify' => -1]);
        }
    }
	
    public function verify_code(Request $request)
    {
        $user_id   = $request->input('user_id');
        $code      = $request->input('code');
        $code_type = $request->input('code_type');

        $user = UserProfile::select('id', 'email_code', 'sms_code')->findOrFail($user_id);

        if ($code_type === 'email') {
            if ($code === $user->email_code) {
                UserProfile::where('id', $user_id)->update(['is_email_verified' => 1, 'email_code' => '']);
                return response()->json(['verify' => 1]);
            }
            return response()->json(['verify' => -1]);
        } elseif ($code_type === 'sms') {
            if ($code === $user->sms_code) {
                UserProfile::where('id', $user_id)->update(['is_mobile_verified' => 1, 'sms_code' => '']);
                return response()->json(['verify' => 1]);
            }
            return response()->json(['verify' => -1]);
        }
    }
	
    public function login(Request $request)
    {
        $account_password  = $request->input('account_password', '');
        $email             = strtolower($request->input('email'));
        $fcm               = $request->input('fcm_token', '');
        $device_type       = $request->input('device_type', '');
        $social_media_type = $request->input('social_media_type');
        $social_media_id   = $request->input('social_media_id');

        if (!empty($social_media_type)) {
            $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'is_email_verified', 'is_mobile_verified', 'loc_lat', 'loc_lng', 'apartment_no', 'street_name', 'city', 'notes', 'location_label', 'social_media_type')
                ->where('email', $email)->where('social_media_id', $social_media_id)->get();
        } else {
            $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'is_email_verified', 'is_mobile_verified', 'loc_lat', 'loc_lng', 'apartment_no', 'street_name', 'city', 'notes', 'location_label', 'social_media_type', 'account_password')
                ->where('email', $email)->get();
            if (!$user->isEmpty() && !Hash::check($account_password, $user[0]->account_password)) {
                $user = collect();
            }
            $user = $user->map(fn($u) => (object) array_diff_key($u->getAttributes(), ['account_password' => '']));
        }

        if (!$user->isEmpty()) {
            $user_id = $user[0]->id;
            if (DB::table('fcm_token')->where('fcm-token', $fcm)->where('user_id', $user_id)->count() === 0) {
                DB::table('fcm_token')->insertGetId([
                    'user_id'    => $user_id,
                    'fcm-token'  => $fcm,
                    'devicetype' => $device_type,
                ]);
            }
            DB::table('login_history')->insertGetId([
                'user_id'   => $user_id,
                'logindate' => date('Y-m-d H:i:s'),
                'device'    => $device_type,
            ]);
        }
        return response()->json(['user' => $user]);
    }
	
    
    public function update_location(Request $request)
    {
        $user_id = $request->input('user_id');

        try {
            UserProfile::where('id', $user_id)->update([
                'updated_at'     => date('Y-m-d H:i:s'),
                'loc_lat'        => $request->input('loc_lat'),
                'loc_lng'        => $request->input('loc_lng'),
                'apartment_no'   => $request->input('apartment_no'),
                'street_name'    => $request->input('street_name'),
                'city'           => $request->input('city'),
                'notes'          => $request->input('notes'),
                'location_label' => $request->input('loc_label'),
            ]);
            $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'is_email_verified', 'is_mobile_verified', 'loc_lat', 'loc_lng', 'apartment_no', 'street_name', 'city', 'notes', 'location_label')
                ->where('id', $user_id)->get();
        } catch (\Illuminate\Database\QueryException $e) {
            $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'is_email_verified', 'is_mobile_verified', 'loc_lat', 'loc_lng', 'apartment_no', 'street_name', 'city', 'notes', 'location_label')
                ->where('id', -1)->get();
        }
        return response()->json(['user' => $user]);
    }	
	
    public function update_password(Request $request)
    {
        $mobile = $request->input('mobile');

        try {
            UserProfile::where('mobile', $mobile)->update([
                'updated_at'       => date('Y-m-d H:i:s'),
                'account_password' => Hash::make($request->input('new_password')),
            ]);
            $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'is_email_verified', 'is_mobile_verified', 'loc_lat', 'loc_lng', 'apartment_no', 'street_name', 'city', 'notes', 'location_label', 'social_media_type')
                ->where('mobile', $mobile)->get();
        } catch (\Illuminate\Database\QueryException $e) {
            $user = UserProfile::select('id', 'name', 'email', 'mobile', 'country_id', 'points', 'register_type', 'is_email_verified', 'is_mobile_verified', 'loc_lat', 'loc_lng', 'apartment_no', 'street_name', 'city', 'notes', 'location_label', 'social_media_type')
                ->where('id', -1)->get();
        }
        return response()->json(['user' => $user]);
    }		
	
    public function checkout_validity(Request $request)
    {
        $result = Product::select('id', 'quantity')
            ->whereIn('id', explode(',', $request->input('card_ids')))
            ->get();
        return response()->json(['cards_available' => $result]);
    }			
	
    public function reserve_order(Request $request)
    {
        $user_id  = $request->input('user_id');
        $card_ids = $request->input('card_ids'); //1,33#2,43 card_id,quantity#card_id,quantity

        // delete temp order for same user_id
        $t_order = DB::table('temp_order')->select('tmp_order_id')->where('user_id', $user_id)->get();
        if (!$t_order->isEmpty()) {
            DB::table('temp_order_items')->where('temp_order_id', $t_order[0]->tmp_order_id)->delete();
            DB::table('temp_order')->where('user_id', $user_id)->delete();
        }

        // insert into temp order table and return id
        $temp_order_id = DB::table('temp_order')->insertGetId(['user_id' => $user_id]);

        // loop over entries order and save it to temp table
        foreach (explode('@', $card_ids) as $v) {
            [$product_id, $qty] = explode(',', $v);

            // get product item and check again the quantity if its available or not
            $pp = Product::findOrFail($product_id);
            if (($pp->quantity - $qty) >= 0) {
                DB::table('temp_order_items')->insertGetId([
                    'temp_order_id' => $temp_order_id,
                    'product_id'    => $product_id,
                    'quantity'      => $qty,
                ]);
                Product::where('id', $product_id)->decrement('quantity', $qty);
            }
        }
        // if ($request->has('user_id')){
            // $user_id=$request->input('user_id');
            // if ($request->has('payment_method')){
                // $payment_method=$request->input('payment_method');
            // } else {
                // $payment_method='';
            // }
            // if ($request->has('payment_source')){
                // $payment_source=$request->input('payment_source');
            // } else {
                // $payment_source = '';
            // }
            // if ($request->has('total')){
                // $total=$request->input('total');
            // } else {
                // $total='';
            // }
            // checkout_final2($temp_order_id, $user_id, $payment_method, $payment_source, $total);
        // } else {
        return response()->json(['reservation' => $temp_order_id]);
        // }
    }
	
    public function checkout_final(Request $request)
    {
        $temp_order_id  = $request->input('temp_order_id');
        $user_id        = $request->input('user_id');
        $promo_id       = $request->input('promo_id', '');
        $payment_method = $request->input('payment_method');
        $payment_source = $request->input('payment_source');
        $total          = $request->input('total');

        //$promo_code=$request->input('promo_code');
        //$promo_type=$request->input('promo_type');
        //$promo_discount_amount=$request->input('promo_discount_amount');

        $t_order = DB::table('temp_order')->select('tmp_order_id')->where('tmp_order_id', $temp_order_id)->get();
        if (!$t_order->isEmpty()) {
            $order_id = DB::table('orders')->insertGetId([
                'user_id'        => $user_id,
                'source'         => $payment_source,
                'status'         => 'Pending',
                'payment_method' => $payment_method,
                'total'          => $total,
            ]);

            DB::insert(
                'insert into order_items (order_id, product_id, quantity) select :order_idp, product_id, quantity from temp_order_items where temp_order_id = :tmp_order_idp',
                ['order_idp' => $order_id, 'tmp_order_idp' => $temp_order_id]
            );

            DB::table('temp_order')->where('tmp_order_id', $temp_order_id)->delete();
            DB::table('temp_order_items')->where('temp_order_id', $temp_order_id)->delete();
            return response()->json(['reservation' => $order_id]);
        }
        return response()->json(['reservation' => -1]);
    }	
	
    public function order_history(Request $request)
    {
        $user_id = $request->input('user_id');
        $orders  = DB::table('orders')
            ->select('id', 'user_id', 'source', 'status', 'order_date', 'total', 'payment_method', 'promo_id')
            ->where('user_id', $user_id)->get();

        $arr = [];
        foreach ($orders as $r) {
            $tmp1 = [
                'id'             => $r->id,
                'user_id'        => $r->user_id,
                'source'         => $r->source,
                'status'         => $r->status,
                'order_date'     => $r->order_date,
                'total'          => $r->total,
                'payment_method' => $r->payment_method,
                'order_items'    => DB::table('products')
                    ->join('order_items', 'order_items.product_id', '=', 'products.id')
                    ->join('stores', 'stores.id', '=', 'products.store_id')
                    ->select('order_items.id', 'order_items.product_id', 'order_items.quantity', 'products.name', 'products.img_logo', 'products.img_back', 'products.price', 'products.currency', 'stores.name AS card_name')
                    ->where('order_items.order_id', $r->id)->get(),
            ];
            if (!empty($r->promo_id)) {
                $row_array = [];
                $promos    = DB::table('promos')->where('promo_id', $r->promo_id)->get();
                if ($promos->count() !== 0) {
                    $row_array['type']           = $promos[0]->promo_type;
                    $row_array['discount_value'] = $promos[0]->discount_amount;
                    $row_array['promo_code']     = $promos[0]->promo_code;
                    //$row_array['promo_id'] = $promos[0]->promo_id;
                    //$row_array['cardid'] = array();
                } else {
                    $row_array['type']           = 0;
                    $row_array['discount_value'] = 0;
                    $row_array['promo_code']     = '';
                    //$row_array['promo_id'] = 0;
                    //$row_array['cardid'] = array();
                }
                $tmp1['promo'] = $row_array;
            }
            $arr[] = $tmp1;
        }
        return response()->json(['history' => $arr]);
    }		
	
	
    public function delete_user(Request $request)
    {
        $user_id = $request->input('user_id');

        try {
            UserProfile::where('id', $user_id)->delete();
            return response()->json(['user' => 1]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['user' => -1]);
        }
    }		
	
    public function promo_validity(Request $request)
    {
        $row_array  = [];
        $promo_code = strtolower($request->input('promo_code'));

        $promos = DB::table('promos')
            ->whereRaw('now() between start_date and end_date')
            ->where('quantity', '!=', 0)
            ->whereRaw('lower(promo_code) = ?', [$promo_code])
            ->get();

        if ($promos->count() !== 0) {
            $row_array['type']           = $promos[0]->promo_type;
            $row_array['discount_value'] = $promos[0]->discount_amount;
            $row_array['promo_id']       = $promos[0]->promo_id;
            $row_array['cardid']         = ($promos[0]->promo_type == 1 || $promos[0]->promo_type == 3)
                ? []
                : DB::table('promo_cards')->select('product_id')->where('promo_id', $promos[0]->promo_id)->get();
        } else {
            $row_array['type']           = 0;
            $row_array['discount_value'] = 0;
            $row_array['promo_id']       = 0;
            $row_array['cardid']         = [];
        }
        return response()->json(['promos' => $row_array]);
    }	













    // cron job to check expired temp orders and remove them from database and get the quantity back to products
    public function check_expired_tmp_orders()
    {
        $items = DB::table('temp_order_items')
            ->select('id', 'temp_order_id', 'product_id', 'quantity')
            ->whereRaw('TIMESTAMPDIFF(MINUTE, created_at, NOW()) > 10')
            ->get();

        foreach ($items as $r) {
            Product::where('id', $r->product_id)->increment('quantity', $r->quantity);
            DB::table('temp_order_items')->where('id', $r->id)->delete();
            DB::table('temp_order')->where('tmp_order_id', $r->temp_order_id)->delete();
        }
    }	
		
}
