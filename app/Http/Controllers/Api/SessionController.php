<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Booking;
use Carbon\CarbonPeriod;
use App\Models\SessionType;
use App\Traits\ApiResponse;
use App\Models\SessionTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    use ApiResponse;

    // getSessionTypes
    public function getSessionTypes()
    {
        $types = SessionType::all();
        return $this->success($types, 'Type retrieved successfully!.');
    }

    // getAvailableSlots
    public function getAvailableSlots(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $expertId = 1;
        $date = Carbon::parse($request->date);
        $dayName = $date->format('l');

        $availability = SessionTime::where('expert_id', $expertId)
            ->where('day', $dayName)
            ->where('is_active', true)
            ->first();

        if (!$availability) {
            return $this->error('No sessions available on this day.', 404);
        }

        $bookedSlots = Booking::where('expert_id', $expertId)
            ->where('booking_date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('booking_time')
            ->map(fn($time) => Carbon::parse($time)->format('h:i A'))
            ->toArray();

        $designSlots = [
            '09:00 AM', '10:00 AM', '11:00 AM', 
            '02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM'
        ];

        $availableSlots = [];

        foreach ($designSlots as $slot) {
            if (!in_array($slot, $bookedSlots)) {
                $availableSlots[] = $slot;
            }
        }

        return $this->success($availableSlots, 'Available slots retrieved successfully!');
    }

    // storeBooking
    public function storeBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'session_type_id' => 'required|exists:session_types,id',
            'booking_date'    => 'required|date_format:Y-m-d|after_or_equal:today',
            'booking_time'    => 'required',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        $expertId = 1;
        $timeFormatted = Carbon::parse($request->booking_time)->format('H:i:s');
        $isBooked = Booking::where('expert_id', $expertId)
            ->where('booking_date', $request->booking_date)
            ->where('booking_time', $timeFormatted)
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($isBooked) {
            return $this->error('This slot is already taken.', 400);
        }

        $booking = Booking::create([
            'user_id'         => auth()->id(),
            'expert_id'       => $expertId,
            'session_type_id' => $request->session_type_id,
            'booking_date'    => $request->booking_date,
            'booking_time'    => $timeFormatted,
            'status'          => 'pending',
        ]);

        return $this->success($booking, 'Booking successful!.', 201);
    }

    // getBookingHistory
    public function getBookingHistory()
    {
        $userId = auth()->id();

        $bookings = Booking::with(['sessionType'])
            ->where('user_id', $userId)
            ->orderBy('booking_date', 'desc')
            ->orderBy('booking_time', 'desc')
            ->get();

        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'id'           => $booking->id,
                'session_name' => $booking->sessionType->name ?? 'N/A',
                'duration'     => ($booking->sessionType->duration ?? '0') . ' Min',
                'date'         => Carbon::parse($booking->booking_date)->format('d M, Y'),
                'time'         => Carbon::parse($booking->booking_time)->format('h:i A'),
                'status'       => ucfirst($booking->status),
                'is_upcoming'  => Carbon::parse($booking->booking_date . ' ' . $booking->booking_time)->isFuture(),
            ];
        });

        return $this->success($formattedBookings, 'Booking history retrieved successfully!.');
    }
}
