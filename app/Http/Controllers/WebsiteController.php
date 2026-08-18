<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterPopRequest;
use App\Http\Requests\FilterProductsRequest;
use App\Http\Requests\SendEmailRequest;
use App\Services\ProductParameterFilter;
use App\Services\WebsiteCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;

class WebsiteController extends Controller
{
    public function __construct(private readonly WebsiteCatalog $catalog)
    {
    }

    public function index(): View
    {
        $params = $this->catalog->parameterOptions();

        return view('web.home', [
            'about'         => $this->catalog->about(),
            'slider'        => $this->catalog->sliders(),
            'category'      => $this->catalog->categories(),
            'sub_category1' => $this->catalog->subCategories1(),
            'family'        => $this->catalog->families(),
            'Hertz'         => $this->distinctOptions($params, 'Hertz'),
            'dm'            => $this->distinctOptions($params, 'Discharge_diameter'),
            'material'      => $this->distinctOptions($params, 'Material'),
            'rpm'           => $this->distinctOptions($params, 'RPM'),
            'volt'          => $this->distinctOptions($params, 'v'),
        ]);
    }

    public function about(): View
    {
        return view('web.about', [
            'category' => $this->catalog->categories(),
            'about'    => $this->catalog->about(),
            'gallery'  => $this->catalog->gallery(),
            'team'     => $this->catalog->team(),
        ]);
    }

    public function contact(): View
    {
        return view('web.contact', [
            'category' => $this->catalog->categories(),
        ]);
    }

    public function categories(string $id, string $type, string $name): View
    {
        if (! ctype_digit($id) || ! ctype_digit($type)) {
            abort(404);
        }

        $id = (int) $id;
        $type = (int) $type;

        $category = $this->catalog->categoryTree();

        return match ($type) {
            1 => $this->categoryLevel($category, $type, $category),
            2 => $this->categoryLevel($category, $type, $this->catalog->subCategories1OfCategory($id)),
            3 => $this->categoryLevel($category, $type, $this->catalog->subCategories1OfSubCategory($id)),
            4 => view('web.series', ['category' => $category, 'products' => $this->catalog->seriesOfSubCategory1($id)]),
            5 => view('web.series', ['category' => $category, 'products' => $this->catalog->seriesOfFamily($id)]),
            6 => $this->productPage($category, $id),
            7 => view('web.Accessories', ['category' => $category, 'products' => $this->catalog->accessories()]),
            default => abort(404),
        };
    }

    public function get_category(): JsonResponse
    {
        return response()->json(['data' => $this->catalog->categories()]);
    }

    public function get_volt(): JsonResponse
    {
        return response()->json(['data' => $this->catalog->parameterValues('v')]);
    }

    public function get_hertz(): JsonResponse
    {
        return response()->json(['data' => $this->catalog->parameterValues('Hertz')]);
    }

    public function get_dm(): JsonResponse
    {
        return response()->json(['data' => $this->catalog->parameterValues('Discharge_diameter')]);
    }

    public function get_material(): JsonResponse
    {
        return response()->json(['data' => $this->catalog->parameterValues('Material')]);
    }

    public function get_rpm(): JsonResponse
    {
        return response()->json(['data' => $this->catalog->parameterValues('RPM')]);
    }

    public function send_email(SendEmailRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $body = 'Name:'    . $data['name'] . "\n"
              . 'Email:'   . $data['email'] . "\n"
              . 'Phone:'   . ($data['phone'] ?? '') . "\n"
              . 'Company:' . ($data['company'] ?? '') . "\n"
              . 'Enquiry:' . $data['inquiry'];

        Mail::raw($body, function ($message) use ($data): void {
            $message->to(config('contact.receiver'), config('contact.receiver_name'))
                ->subject('Fujika Contact Form')
                ->from(config('mail.from.address'), config('mail.from.name'))
                ->replyTo($data['email'], $data['name']);
        });

        return redirect()->back();
    }

    public function filterpop(FilterPopRequest $request): View
    {
        $keyword = $request->validated()['keyword'] ?? null;

        $res = $request->has('commercial')
            ? $this->catalog->searchFamilies($keyword)
            : $this->catalog->searchProducts($keyword);

        return view('web.filterpop', [
            'category' => $this->catalog->categories(),
            'res'      => $res,
        ]);
    }

    public function filter(FilterProductsRequest $request, ProductParameterFilter $filter): View
    {
        return view('web.filter', [
            'category' => $this->catalog->categories(),
            'products' => $filter->apply($request->validated()),
        ]);
    }

    private function categoryLevel(mixed $category, int $type, mixed $res): View
    {
        return view('web.category', [
            'category' => $category,
            'res'      => $res,
            'type'     => $type,
        ]);
    }

    private function productPage(mixed $category, int $id): View
    {
        $product = $this->catalog->product($id);

        return view('web.product', [
            'category' => $category,
            'product'  => collect([$product]),
            'gallery'  => $this->catalog->productGallery($product->id),
        ]);
    }

    private function distinctOptions(mixed $params, string $column): mixed
    {
        return $params->unique($column)
            ->filter(fn ($item) => ! empty($item[$column]))
            ->values();
    }
}
