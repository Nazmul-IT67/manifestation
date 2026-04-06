<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Journal;
use App\Models\JournalType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Journal::with('user')->latest(); 
            return $this->generateDataTable($data);
        }

        return view('backend.layouts.journal.index');
    }

    // generateDataTable
    private function generateDataTable($query)
    {
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_id', function ($row) {
                return $row->user ? $row->user->name : 'Unknown User';
            })
            ->addColumn('created_at', function ($row) {
                return $row->created_at->format('d M, Y');
            })
            ->addColumn('status', function ($row) {
                $checked = $row->is_active ? "checked" : "";
                return '
                    <div class="form-check form-switch d-flex">
                        <input onclick="showStatusChangeAlert(' . $row->id . ')"
                            type="checkbox" class="form-check-input status-toggle"
                            id="switch' . $row->id . '" ' . $checked . '>
                    </div>';
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex gap-1">
                            <button onclick="showDeleteConfirm(' . $row->id . ')" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $journal = Journal::findOrFail($id);
            $journal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Journal entry deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong!'
            ], 500);
        }
    }
}