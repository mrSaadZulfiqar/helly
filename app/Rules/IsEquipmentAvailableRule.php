<?php

namespace App\Rules;

use App\Models\Instrument\Equipment;
use App\Models\Instrument\EquipmentBooking;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class IsEquipmentAvailableRule implements Rule
{
  private $id;
  private $equipmentBookedDates;

  /**
   * Create a new rule instance.
   *
   * @return void
   */
  public function __construct($equipmentId)
  {
    $this->id = $equipmentId;
  }

  /**
   * Determine if the validation rule passes.
   *
   * @param  string  $attribute
   * @param  mixed  $value
   * @return bool
   */
  public function passes($attribute, $value)
  {
    // get start & end date from the string
    $arrOfDate = explode(' ', $value);
    $date_1 = $arrOfDate[0];
    $date_2 = $arrOfDate[2];

    // get all the dates between the start & end date
    $allDates = $this->getAllDates($date_1, $date_2, 'Y-m-d');

    // get quantity of the equipment
    $equipment = Equipment::query()->findOrFail($this->id);
    $quantity = $equipment->quantity;

    // get all the bookings of the equipment
    $bookings = EquipmentBooking::query()->where('equipment_id', '=', $this->id)
      ->where('payment_status', '=', 'completed')
      ->select('start_date', 'end_date')
      ->get();

    $bookedDates = [];

    // loop through the list of dates, which we have found from the start & end date
    foreach ($allDates as $date) {
      $bookingCount = 0;

      // loop through all the bookings
      foreach ($bookings as $currentBooking) {
        $bookingStartDate = Carbon::parse($currentBooking->start_date);
        $bookingEndDate = Carbon::parse($currentBooking->end_date);
        $currentDate = Carbon::parse($date);

        // check for each date, whether the date is present or not in any of the booking date range
        if ($currentDate->betweenIncluded($bookingStartDate, $bookingEndDate)) {
          $bookingCount++;
        }
      }

      // if the number of booking of a specific date is same as the equipment quantity, then mark that date as unavailable
      if ($bookingCount >= $quantity && !in_array($date, $bookedDates)) {
        array_push($bookedDates, $date);
      }
    }

    // if 'bookedDates' array has any data, then return validation failed
    if (count($bookedDates) > 0) {
      $this->equipmentBookedDates = $bookedDates;

      return false;
    } else {
      return true;
    }
  }

  /**
   * Get the validation error message.
   *
   * @return string
   */
  public function message()
  {
    $allBookedDates = '';

    // get the array length
    $arrLen = count($this->equipmentBookedDates);

    foreach ($this->equipmentBookedDates as $key => $bookedDate) {
      // checking whether the current index is the last position of the array
      if (($arrLen - 1) == $key) {
        $allBookedDates .= $bookedDate;
      } else {
        $allBookedDates .= $bookedDate . ', ';
      }
    }

    return 'The equipment is booked on these following dates: ' . $allBookedDates . '.';
  }

  /**
   * Get all the dates between the start & end date.
   *
   * @param  string  $startDate
   * @param  string  $endDate
   * @param  string  $format
   * @return array
   */
  public function getAllDates($startDate, $endDate, $format)
  {
    $dates = [];

    // convert string to timestamps
    $currentTimestamps = strtotime($startDate);
    $endTimestamps = strtotime($endDate);

    // set an increment value
    $stepValue = '+1 day';

    // push all the timestamps to the 'dates' array by formatting those timestamps into date
    while ($currentTimestamps <= $endTimestamps) {
      $formattedDate = date($format, $currentTimestamps);
      array_push($dates, $formattedDate);
      $currentTimestamps = strtotime($stepValue, $currentTimestamps);
    }

    return $dates;
  }
}
