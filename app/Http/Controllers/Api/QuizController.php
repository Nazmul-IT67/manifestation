<?php

namespace App\Http\Controllers\Api;

use App\Models\Quiz;
use App\Models\AngelNumber;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    use ApiResponse;

    // getQuiz
    public function getQuiz(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $user = auth()->user();
        $quiz = Quiz::with('angelNumber')
            ->where('user_id', $user->id)
            ->latest()->first();

        if (!$quiz) {
            return $this->error('Quiz record not found', 404);
        }

        return $this->success($quiz, 'Quiz data retrieved successfully.');
    }

    // showQuiz
    public function showQuiz(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $query = Quiz::with('angelNumber');
        if ($request->has('id')) {
            $query->where('id', $request->id);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $quiz = $query->first();
        if (!$quiz) {
            return $this->error('No quiz record found with provided criteria.', 404);
        }

        return $this->success($quiz, 'Quiz data retrieved successfully.');
    }

    // storeQuiz
    public function storeQuiz(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $user = Auth::user();
        $alreadySubmitted = Quiz::where('user_id', $user->id)->exists();
        if ($alreadySubmitted) {
            return $this->error('You have already submitted the quiz.', 400);
        }

        $validator = Validator::make($request->all(), [
            'experience_level'    => 'required|string',
            'improvement_areas'   => 'required|string',
            'practice_preference' => 'required|string',
            'selected_paths'      => 'required|array|min:2',
            'focus_area'          => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $angelNumber = AngelNumber::where('is_active', 1)
            ->where('tags', 'LIKE', '%' . $request->focus_area . '%')
            ->inRandomOrder()
            ->first();

        if (!$angelNumber) {
            $angelNumber = AngelNumber::where('is_active', 1)
                ->inRandomOrder()
                ->first();
        }

        $quiz = Quiz::create([
            'user_id'             => $user->id,
            'experience_level'    => $request->experience_level,
            'improvement_areas'   => $request->improvement_areas,
            'practice_preference' => $request->practice_preference,
            'selected_paths'      => $request->selected_paths,
            'focus_area'          => $request->focus_area,
            'is_quiz_completed'   => true,
            'angel_number_id'     => $angelNumber->id,
        ]);

        return $this->success([
            'quiz' => $quiz,
            'assigned_angel_number' => $angelNumber
        ], 'Quiz finished successfully and you received a divine number.', 201);
    }
}