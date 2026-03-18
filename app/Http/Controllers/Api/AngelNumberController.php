<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AngelNumber;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AngelNumberController extends Controller
{
    use ApiResponse;

    // angel number + selected
    public function index(Request $request)
    {
        $user       = $request->user();
        $selectedId = $user->angelNumber()->value('angel_number_id');

        $angelNumbers = AngelNumber::where('is_active', true)->get()
            ->map(fn($a) => [
                'id'          => $a->id,
                'number'      => $a->number,
                'title'       => $a->title,
                'description' => $a->description,
                'tags'        => $a->tags,
                'is_selected' => $a->id === $selectedId,
            ]);

        return $this->success($angelNumbers, 'Angel numbers fetched successfully');
    }

    // selected angel number
    public function myAngelNumber(Request $request)
    {
        $user            = $request->user();
        $userAngelNumber = $user->angelNumber()
            ->where('expires_at', '>=', now())
            ->with('angelNumber')
            ->first();

        if (! $userAngelNumber) {
            return $this->success(null, 'Angel number expired or not selected yet');
        }

        $daysLeft = now()->diffInDays($userAngelNumber->expires_at);

        return $this->success([
             ...$userAngelNumber->angelNumber->toArray(),
            'expires_at' => $userAngelNumber->expires_at->format('d M Y'),
            'days_left'  => $daysLeft . ' days left',
        ], 'Angel number fetched successfully');
    }

    // Select angel number
    public function select(Request $request, AngelNumber $angelNumber)
    {
        $user = $request->user();

        if ($user->hasActiveSubscription()) {
            $user->angelNumber()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'angel_number_id' => $angelNumber->id,
                    'expires_at'      => now()->addDays(7),
                ]
            );
            return $this->success([], 'Angel number selected successfully');
        }

        if ($user->angelNumber()->exists()) {
            return $this->error('Upgrade to premium to change your angel number.', 403);
        }

        $user->angelNumber()->create([
            'angel_number_id' => $angelNumber->id,
            'expires_at'      => now()->addDays(7),
        ]);

        return $this->success([], 'Angel number selected successfully');
    }
}
