<?php

namespace App\Http\Controllers\Web\Backend;

use App\Models\Booking;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
        $data = Booking::with('user', 'sessionType')->orderBy('id', 'desc');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->addColumn('user_email', function ($row) {
                    return $row->user->email ?? 'N/A';
                })
                ->addColumn('booking_info', function ($row) {
                    return '<strong>Date:</strong> ' . $row->booking_date;
                })
                ->addColumn('joined', function ($row) {
                    return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d M, Y') : 'N/A';
                })
                ->addColumn('status', function ($row) {
                    $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
                    $statusClasses = [
                        'pending'   => 'bg-warning text-dark',
                        'confirmed' => 'bg-info text-white',
                        'completed' => 'bg-success text-white',
                        'cancelled' => 'bg-danger text-white',
                    ];

                    $class = $statusClasses[$row->status] ?? 'bg-secondary';
                    
                    $html = '<select class="form-select form-select-sm status-change ' . $class . '" data-id="' . $row->id . '" style="width: 120px;">';
                    foreach ($statuses as $status) {
                        $selected = ($row->status == $status) ? 'selected' : '';
                        $html .= '<option value="' . $status . '" ' . $selected . '>' . ucfirst($status) . '</option>';
                    }
                    $html .= '</select>';
                    
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="d-flex gap-1">
                            <button onclick="showDeleteConfirm(' . $row->id . ')" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </div>';
                })
                ->rawColumns(['booking_info', 'status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.session.index');
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $session = Booking::findOrFail($id);
            $session->delete();

            return response()->json([
                'success' => true,
                'message' => 'Session deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    // updateStatus
    public function updateStatus(Request $request)
    {
        $booking = Booking::findOrFail($request->id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully!'
        ]);
    }
}
