<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use App\Enums\ReservationStatus;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReservationResource;
use App\Http\Requests\Api\ReservationRequest;

class ReservationController extends Controller
{
    use ApiResponseTrait;

    /**
     * Get all reservations.
     * - Admins receive all reservations.
     * - Regular users receive only their own reservations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function AllReservation()
    {
        if (Auth::user()->is_admin) {
            $reservations = Reservation::all();
        } else {
            $reservations = Reservation::where('user_id', Auth::id())->get();
        }

        return $this->apiResponse('Reservations retrieved successfully.', 200, ReservationResource::collection($reservations));
    }

    /**
     * Create a new reservation for the authenticated user.
     * Validates input, handles edge cases (e.g. double booking, short notice),
     * and stores the reservation in the database.
     *
     * @param ReservationRequest $request Validated reservation input
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeReservation(ReservationRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Handle edge cases and stop if one fails
            $edgeCaseError = $this->handelEdgeCases($validated);
            
            if ($edgeCaseError) return $edgeCaseError;

            $reservationDate = Carbon::createFromFormat('d-m-Y h:i:s a', $validated['reservation_date'])
                                ->format('Y-m-d H:i:s');

            $reservation = Reservation::create([
                'user_id'          => Auth::id(),
                'service_id'       => $validated['service_id'],
                'reservation_date' => $reservationDate,
                'status'           => $validated['status'],
            ]);

            DB::commit();

            return $this->apiResponse('Reservation created successfully.', 201, data: new ReservationResource($reservation));
        
        } catch (Exception $e) {
            DB::rollBack();
            return $this->apiResponse($e->getMessage(), 500);
        }
    }
    
    /**
     * Retrieve upcoming reservations for the authenticated user.
     * Only returns future reservations that are not canceled.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upcomingReservations()
    {
        $reservations = Reservation::where('user_id', Auth::id())
            ->whereDate('reservation_date', '>=', Carbon::now()) 
            ->where('status', '!=', ReservationStatus::Canceled)
            ->get();

        return $this->apiResponse('Upcoming reservations fetched successfully.', 200, ReservationResource::collection($reservations));
    }

    /**
     * Cancel a reservation for the authenticated user by reservation ID.
     * Only allows canceling future reservations.
     *
     * @param string $id Reservation ID
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If reservation is not found or not eligible for cancellation
     */
    public function cancelReservation(string $id)
    {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereDate('reservation_date', '>', Carbon::now()) 
            ->firstOrFail();

        $reservation->update(['status' => ReservationStatus::Canceled]);

        return $this->apiResponse('Reservation canceled successfully.', 200);
    }

    /**
     * Check and handle edge cases for creating a reservation.
     * Ensures the time slot is available and booked at least 30 minutes in advance.
     *
     * @param array $data Validated reservation data
     * @return \Illuminate\Http\JsonResponse|null Error response if validation fails, otherwise null
     */
    private function handelEdgeCases(array $data)
    {
        $reservationDate = Carbon::createFromFormat('d-m-Y h:i:s a', $data['reservation_date'])
                                ->format('Y-m-d H:i:s');

        $alreadyBooked = Reservation::where('service_id', $data['service_id'])
            ->where('reservation_date', $reservationDate)
            ->where('status', '!=', ReservationStatus::Canceled)
            ->exists();

        if ($alreadyBooked) {
            return $this->apiResponse('This time slot is already booked.', 409);
        }

        if (Carbon::parse($data['reservation_date'])->lessThan(Carbon::now()->addMinutes(30))) {
            return $this->apiResponse('Reservations must be made at least 30 minutes in advance.', 422);
        }

        return null; 
    }
}
