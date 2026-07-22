<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductParameterRequest;
use App\Http\Requests\UpdateProductParameterRequest;
use App\Models\Product;
use App\Models\ProductParameter;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ParameterController extends Controller
{
    /**
     * The product comes from the URL rather than the session, so parameters
     * can no longer be filed against whichever product a different tab
     * happened to open last.
     */
    public function index(Product $product): View
    {
        return view('Pages.product.parameter.parameter')->with('id', $product->id);
    }

    public function data(Product $product): JsonResponse
    {
        $parameters = Cache::remember(
            $this->cacheKey($product),
            now()->addHour(),
            fn () => ProductParameter::where('product_id', $product->id)->get()->all()
        );

        return response()->json(['data' => $parameters]);
    }

    /**
     * The Kendo grid drives create/update inline and expects the saved row
     * back as JSON, so these return the model rather than redirecting.
     */
    public function store(StoreProductParameterRequest $request, Product $product): JsonResponse
    {
        $parameter = ProductParameter::create(
            $request->validated() + ['product_id' => $product->id]
        );

        Cache::forget($this->cacheKey($product));

        return response()->json(['data' => [$parameter]]);
    }

    public function update(UpdateProductParameterRequest $request, ProductParameter $parameter): JsonResponse
    {
        $parameter->update($request->validated());

        Cache::forget('parameter_' . $parameter->product_id);

        return response()->json(['data' => [$parameter]]);
    }

    public function destroy(ProductParameter $parameter): JsonResponse
    {
        $productId = $parameter->product_id;
        $parameter->delete();

        Cache::forget('parameter_' . $productId);

        return response()->json(['data' => []]);
    }

    private function cacheKey(Product $product): string
    {
        return 'parameter_' . $product->id;
    }
}
