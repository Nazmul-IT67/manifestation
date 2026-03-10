<?php

namespace App\Http\Controllers\Backend;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page_title = "All Countries";
        if ($request->ajax()) {

            $data = Country::select(['id', 'name', 'code', 'status']);

            return DataTables::of($data)
                ->addIndexColumn()

                ->editColumn('name', function ($row) {
                    return $row->name ?? 'N/A';
                })

                ->editColumn('code', function ($row) {
                    return $row->code ?? 'N/A';
                })

                ->addColumn('status', function ($row) {
                    $checked = $row->status == 1 ? 'checked' : '';

                    return '
                        <div class="form-check form-switch">
                            <input class="form-check-input toggleStatus"
                                type="checkbox"
                                data-id="'.$row->id.'"
                                '.$checked.'>
                        </div>
                    ';
                })

                ->addColumn('action', function ($row) {
                    return '
                    <div class="d-flex gap-2">
                        <a href="'.route('countries.edit', $row->id).'" 
                        class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-pen-fill"></i>
                        </a>

                        <button class="btn btn-sm btn-outline-danger deleteCountry"
                                data-id="'.$row->id.'">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                ';
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.location.country.index', compact('page_title'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page_title = "Add Country";
        return view('backend.layouts.location.country.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:countries',
            'code' => 'required|unique:countries',
        ]);

        $country = new Country;
        $country->name = $request->name;
        $country->code = $request->code;
        $country->save();

        return redirect()->route('countries.index')->with('success', 'Country add successfully');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
