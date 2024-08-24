<?php

namespace App\Http\Controllers\BackEnd\Instrument;

use App\Http\Controllers\Controller;
use App\Models\Instrument\EquipmentBooking;
use App\Models\Instrument\SecurityDepositRefund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SecurityDepositController extends Controller
{
    public function refund(Request $request)
    {
        $booking_id = null;
        $status = null;
        $r_status = null;
        $bookings_ids = [];
        if ($request->filled('booking_id')) {
            $booking_id = $request->booking_id;

            $bookings = EquipmentBooking::where('booking_number', 'like', '%' . $booking_id . '%')->get();
            foreach ($bookings as $booking) {
                if (!in_array($booking->id, $bookings_ids)) {
                    array_push($bookings_ids, $booking->id);
                }
            }
        }
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status == 0) {
                $r_status = 2;
            } elseif ($status == 1) {
                $r_status = 1;
            } else {
                $r_status = null;
            }
        }

        $collection = SecurityDepositRefund::with('booking')
            ->when($booking_id, function ($query) use ($bookings_ids) {
                return $query->whereIn('booking_id', $bookings_ids);
            })
            ->when($r_status, function ($query) use ($status) {
                return $query->where('refund_status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('backend.instrument.security-deposit.refund', compact('collection'));
    }

    public function dispute_request(Request $request)
    {
        $booking_id = null;
        $status = null;
        $r_status = null;
        $bookings_ids = [];
        if ($request->filled('booking_id')) {
            $booking_id = $request->booking_id;

            $bookings = EquipmentBooking::where('booking_number', 'like', '%' . $booking_id . '%')->get();
            foreach ($bookings as $booking) {
                if (!in_array($booking->id, $bookings_ids)) {
                    array_push($bookings_ids, $booking->id);
                }
            }
        }
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status == 0) {
                $r_status = 2;
            } elseif ($status == 1) {
                $r_status = 1;
            } else {
                $r_status = null;
            }
        }

        $collection = SecurityDepositRefund::with('booking')
            ->where('status', 2)
            ->when($booking_id, function ($query) use ($bookings_ids) {
                return $query->whereIn('booking_id', $bookings_ids);
            })
            ->when($r_status, function ($query) use ($status) {
                return $query->where('refund_status', $status);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('backend.instrument.security-deposit.dispute', compact('collection'));
    }
    //agree
    public function agree($id)
    {
        $refunds = SecurityDepositRefund::where('id', $id)->first();

        $refunds->status = 1;
        $refunds->save();
        Session::flash('success', 'You have raised a dispute successfully , Admin will contact you soon');
        return redirect()->route('index');
    }
    //raise_dispute
    public function raise_dispute($id)
    {
        $refunds = SecurityDepositRefund::where('id', $id)->first();
        $refunds->status = 2;
        $refunds->save();
        Session::flash('success', 'We have received your refund request successfully. It wll be processed soon');
        return redirect()->route('index');
    }

    public function refund_status(Request $request)
    {
        $data = SecurityDepositRefund::where('id', $request->id)->first();
        if ($data) {
            $data->refund_status = $request->refund_status;
            $data->save();
        }
        Session::flash('success', 'Update Refund Status Successfully...!');
        return back();
    }

    //update_status
    public function update_status(Request $request)
    {
        $refund = SecurityDepositRefund::where('id', $request->id)->first();
        if ($refund) {
            $booking = EquipmentBooking::where('id', $refund->booking_id)->first();
        } else {
            Session::flash('success', 'Something went wrong.!');
            return Response::json(['status' => 'success'], 200);
        }

        $amount = intval($booking->security_deposit_amount);
        $rules = [
            'id' => 'required',
            'amount' => "required|numeric|between:1, $amount"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json(
                [
                    'errors' => $validator->getMessageBag()
                ],
                400
            );
        }

        $refund->update([
            'refund_status' => $request->status,
            'partial_amount' => $request->amount,
        ]);

        if ($request->status == 1) {
            Session::flash('success', 'Change Refund Status Successfully..!');
        }
        return Response::json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $data = SecurityDepositRefund::where('id', $request->id)->first();
        if (!empty($data)) {
            $data->delete();
        }
        Session::flash('success', 'Delete Request Successfully...!');
        return back();
    }
    public function bulk_delete(Request $request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $data = SecurityDepositRefund::where('id', $id)->first();
            if (!empty($data)) {
                $data->delete();
            }
        }
        Session::flash('success', 'Delete Request Successfully...!');
        return Response::json(['status' => 'success'], 200);
    }
}
