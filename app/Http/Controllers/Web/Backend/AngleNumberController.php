<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\AngelNumber;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class AngleNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AngelNumber::latest();

            if ($request->has('search.value') && !empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where(function($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', "%$searchTerm%")
                        ->orWhere('number', 'LIKE', "%$searchTerm%");
                });
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('tags', function ($row) {
                    $tagHtml = '';
                    $tags = is_array($row->tags) ? $row->tags : json_decode($row->tags, true);

                    if (!empty($tags)) {
                        $colors = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-dark'];

                        foreach ($tags as $key => $tag) {
                            $colorClass = $colors[$key % count($colors)]; 
                            
                            $tagHtml .= '<span class="badge ' . $colorClass . ' me-1">' . htmlspecialchars($tag) . '</span>';
                        }
                    }
                    
                    return $tagHtml ?: '<span class="badge bg-light text-dark">No Tags</span>';
                })
                ->addColumn('status', function ($row) {
                    $checked = $row->is_active ? "checked" : "";
                    return '
                        <div class="form-check form-switch d-flex">
                            <input onclick="showStatusChangeAlert(' . $row->id . ')"
                                type="checkbox"
                                class="form-check-input status-toggle"
                                id="switch' . $row->id . '"
                                ' . $checked . '>
                            <label class="form-check-label ms-2" for="switch' . $row->id . '"></label>
                        </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="d-flex gap-1" role="group">
                                <a href="' . route('angle-number.edit', $row->id) . '" class="text-white btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>';
                })
                // Ekhane 'tags' column-ti add kora hoyeche
                ->rawColumns(['status', 'action', 'tags']) 
                ->make(true);
        }

        return view('backend.layouts.angle-number.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $angleNumber = AngelNumber::findOrFail($id);
        return view('backend.layouts.angle-number.edit', compact('angleNumber'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $request->validate([
            'title'       => 'required|max:255',
            'description' => 'nullable|string',
            'is_active'   => 'required|boolean',
            'tags'        => 'nullable|string', 
        ]);

        // Data Find kora
        $angleNumber = AngelNumber::findOrFail($id);
        $angleNumber->update([
            'title'       => $request->title,
            'description' => $request->description,
            'is_active'   => $request->is_active,
            'tags'        => $request->tags ? explode(',', $request->tags) : null,
        ]);

        return redirect()->route('angle-number.index')->with('success', 'Angel Number updated successfully!');
    }

    // updateStatus
    public function updateStatus($id)
    {
        $angle_number = AngelNumber::findOrFail($id);
        $angle_number->is_active = !$angle_number->is_active;
        $angle_number->save();

        $statusText = $angle_number->is_active ? 'Activated' : 'Deactivated';

        return response()->json([
            'success' => true,
            'message' => 'Angel Number has been ' . $statusText
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}