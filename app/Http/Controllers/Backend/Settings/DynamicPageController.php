<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Models\DynamicPage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DynamicPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view page')->only('index', 'getPages');
        $this->middleware('permission:create page')->only('create', 'store');
        $this->middleware('permission:edit page')->only('edit', 'update');
        $this->middleware('permission:delete page')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page_title = 'All Pages';
        return view('backend.layouts.settings.dynamic_page.index', compact('page_title'));
    }

    /**
     * Datatable.
     */
    public function getPages(Request $request)
    {
        if ($request->ajax()) {
            $data = DynamicPage::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                    ->editColumn('content', function ($row) {
                    return strip_tags($row->content);
                })
                ->addColumn('action', function($row){
                    return '<a href="/pages/edit'.$row->id.'" class="btn btn-md btn-primary me-2">Edit</a>'.'<a href="/users'.$row->id.'" class="btn btn-md btn-danger">Delete</a>';
                })
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $page_title = 'Add Page';
        return view('backend.layouts.settings.dynamic_page.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'   => 'required|string',
            'content' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $page          = new DynamicPage();
        $page->title   = $request->title;
        $page->slug    = Str::slug($request->title);
        $page->content = $request->content;
        $page->save();

        return redirect()->route('pages.index')->with('success', 'Page created successfully.');
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
