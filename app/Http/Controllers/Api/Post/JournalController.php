<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\JournalResource;
use App\Models\Journal;
use App\Models\JournalType;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $is_filtered = $request->journal_type_id || $request->search ? true : false;

        $journals = $request->user()->journals()->with('journalType')
            ->when($request->journal_type_id, function ($query) use ($request) {
                $query->where('journal_type_id', $request->journal_type_id);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where('description', 'like', '%' . $request->search . '%')
                ->orWhere('title','like', '%' . $request->search . '%');
            })->latest()->take(6)->get();

        $data = [
            'journals'    => JournalResource::collection($journals),
        ];

        if (!$is_filtered) {
            $data['journal_types'] = JournalType::where('is_active', true)->get();
        }

        return $this->success($data, 'Journals fetched successfully');
    }
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'journal_type_id' => 'required|exists:journal_types,id',
            'title'           => 'required|string',
            'description'     => 'required|string',
            'mood_tag'        => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return $this->error(null, $validated->errors()->first(), 422);
        }

        $journal = $request->user()->journals()->create($validated->validated());

        $journal->load('journalType');

        return $this->success(new JournalResource($journal), 'Journal created successfully', 201);
    }

    public function show(Request $request, Journal $journal)
    {
        if ($journal->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $journal->load('journalType');

        return $this->success(new JournalResource($journal), 'Journal fetched successfully');
    }

    public function update(Request $request, Journal $journal)
    {
        if ($journal->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $validated = Validator::make($request->all(), [
            'journal_type_id' => 'sometimes|exists:journal_types,id',
            'title'           => 'required|string',
            'description'     => 'sometimes|string',
            'mood_tag'        => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return $this->error(null, $validated->errors()->first(), 422);
        }

        $journal->update($validated->validated());

        $journal->load('journalType');

        return $this->success(new JournalResource($journal), 'Journal updated successfully');
    }

    public function destroy(Request $request, Journal $journal)
    {
        if ($journal->user_id !== $request->user()->id) {
            return $this->error(null, 'Unauthorized', 403);
        }

        $journal->delete();

        return $this->success(null, 'Journal deleted successfully');
    }
}
