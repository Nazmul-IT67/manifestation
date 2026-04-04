<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Affirmation;
use App\Models\Category;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AffirmationController extends Controller
{
    use ApiResponse;
    public function index(Request $request)
    {
        $categories = Category::select('id', 'name')->get();
        if ($request->has('category_id')) {
            $affirmations = Affirmation::where('category_id', $request->category_id)->with('category')->get();
        } else {
            $affirmations = Affirmation::with('category')->get();
        }

        $response = [
            'categories'   => $categories,
            'affirmations' => $affirmations,
        ];
        return $this->success($response, 'Affirmations fetched successfully');
    }

    public function show(Request $request, Affirmation $affirmation)
    {
        $request->user()->activities()->create([
            'affirmation_id' => $affirmation->id,
        ]);
        return $this->success($affirmation->load('category'), 'Affirmation fetched successfully');
    }
}
