<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use App\Models\DynamicPage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class DynamicPageController extends Controller
{
    /**
     * Display all dynamic pages.
     */
    public function index(Request $request): View | JsonResponse
    {
        $page_title = 'All Pages';
        
        if ($request->ajax()) {
            $data = DynamicPage::latest();
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $data->where('title', 'LIKE', "%$searchTerm%");
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('content', function ($data) {
                    $content = $data->content;
                    $short_content = strlen($content) > 100 ? substr($content, 0, 100) . '...' : $content;
                    return '<p>' . $short_content . '</p>';
                })
                ->addColumn('status', function ($data) {
                    $checked = $data->status == "active" ? "checked" : "";

                    return '
                        <div class="form-check form-switch d-flex">
                            <input onclick="showStatusChangeAlert(' . $data->id . ')"
                                type="checkbox"
                                class="form-check-input status-toggle"
                                id="switch' . $data->id . '"
                                data-id="' . $data->id . '"
                                name="status" ' . $checked . '>
                            <label class="form-check-label ms-2" for="switch' . $data->id . '">

                            </label>
                        </div>
                    ';
                })

                ->addColumn('action', function ($data) {
                    return '<div class="d-flex gap-1" role="group" aria-label="Basic example">
                                <a href="' . route('admin.dynamic_page.edit', ['id' => $data->id]) . '" class="text-white btn btn-md btn-primary" title="Edit">
                                    <i class="nav-icon bi bi-pencil" aria-hidden="true"></i>
                                </a>
                                <a href="#" onclick="showDeleteConfirm(' . $data->id . ')" class="text-white btn btn-md btn-danger" title="Delete">
                                    <i class="nav-icon bi bi-trash" aria-hidden="true"></i>
                                </a>
                            </div>';
                })
                ->rawColumns(['content', 'status', 'action'])
                ->make();
        }
        return view('backend.layouts.settings.dynamic_page.index', compact('page_title'));
    }

    /**
     * Show the form for creating a new dynamic page.
     */
    public function create(): View | RedirectResponse
    {
        $page_title = 'Create Page';
        if (User::find(auth()->user()->id)) {
            return view('backend.layouts.settings.dynamic_page.create', compact('page_title'));
        }
        return redirect()->route('admin.dynamic_page.index');
    }

    /**
     * created dynamic page
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'title'   => 'required|string',
                    'content' => 'required|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $data          = new DynamicPage();
                $data->title   = $request->title;
                $data->slug    = Str::slug($request->title);
                $data->content = $request->content;
                $data->save();
            }
            return redirect()->route('admin.dynamic_page.index')->with('success', 'Dynamic Page created successfully.');
        } catch (Exception) {
            return redirect()->route('admin.dynamic_page.index')->with('error', 'Dynamic Page failed created.');
        }
    }

    /**
     * Show the form for editing the specified dynamic page.
     */
    public function edit(int $id): View | RedirectResponse
    {
        $page_title = 'Edit Page';
        if (User::find(auth()->user()->id)) {
            $data = DynamicPage::find($id);
            return view('backend.layouts.settings.dynamic_page.edit', compact('data', 'page_title'));
        }
        return redirect()->route('admin.dynamic_page.index');
    }

    /**
     * Update the specified dynamic page in the database.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        try {
            if (User::find(auth()->user()->id)) {
                $validator = Validator::make($request->all(), [
                    'title'   => 'nullable|string',
                    'content' => 'nullable|string',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $data = DynamicPage::findOrFail($id);
                $data->update([
                    'title'   => $request->title,
                    'slug'    => Str::slug($request->title),
                    'content' => $request->content,
                ]);

                return redirect()->route('admin.dynamic_page.index')->with('t-success', 'Dynamic Page Updated Successfully.');
            }
        } catch (Exception) {
            return redirect()->route('admin.dynamic_page.index')->with('t-error', 'Dynamic Page failed to update');
        }
        return redirect()->route('admin.dynamic_page.index');
    }

    /**
     * Change the status of the specified dynamic page.
     */
    public function status(int $id): JsonResponse
    {
        $data = DynamicPage::findOrFail($id);
        if ($data->status == 'active') {
            $data->status = 'inactive';
            $data->save();

            return response()->json([
                'success' => false,
                'message' => 'Unpublished Successfully.',
                'data'    => $data,
            ]);
        } else {
            $data->status = 'active';
            $data->save();

            return response()->json([
                'success' => true,
                'message' => 'Published Successfully.',
                'data'    => $data,
            ]);
        }
    }

    /**
     * Remove the specified dynamic page from the database.
     */
    public function destroy(int $id): JsonResponse
    {
        $page = DynamicPage::findOrFail($id);
        $page->delete();
        return response()->json([
            't-success' => true,
            'message'   => 'Deleted successfully.',
        ]);
    }
}
