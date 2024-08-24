<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EquipmentBookingsExport implements FromCollection, WithHeadings, WithMapping
{
  protected $bookings;

  public function __construct($bookings)
  {
    $this->bookings = $bookings;
  }

  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return $this->bookings;
  }

  public function headings(): array
  {
    return [
      'Booking No.',
      'Customer Name',
      'Customer Contact Number',
      'Customer Email',
      'Equipment',
      'Start Date',
      'End Date',
      'Shipping Method',
      'Location',
      'Price',
      'Discount',
      'Shipping Cost',
      'Tax',
      'Grand Total',
      'Received Amount',
      'Commision',
      'Paid via',
      'Payment Status',
      'Shipping Status',
      'Booking Date'
    ];
  }

  /**
   * @var $booking
   */
  public function map($booking): array
  {
    // price
    if (is_null($booking->total)) {
      $price = 'Requested';
    } else {
      $price = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '') . $booking->total . ($booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '');
    }

    // discount
    if (is_null($booking->discount)) {
      $discount = '-';
    } else {
      $discount = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '') . $booking->discount . ($booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '');
    }

    // shipping cost
    if (is_null($booking->shipping_cost)) {
      $shippingCost = '-';
    } else {
      $shippingCost = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '') . $booking->shipping_cost . ($booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '');
    }

    // tax
    if (is_null($booking->tax)) {
      $tax = '-';
    } else {
      $tax = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '') . $booking->tax . ($booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '');
    }

    // grand total
    if (is_null($booking->grand_total)) {
      $grandTotal = '-';
    } else {
      $grandTotal = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '') . $booking->grand_total . ($booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '');
    }

    // total received
    if ($booking->vendor_id != NULL) {

      $received_amount = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '') . $booking->received_amount . ($booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '');
    } else {
      $received_amount = '-';
    }
    // comission
    if ($booking->vendor_id != NULL) {

      $comission = ($booking->currency_symbol_position == 'left' ? $booking->currency_symbol . ' ' : '') . $booking->comission . ($booking->currency_symbol_position == 'right' ? ' ' . $booking->currency_symbol : '');
    } else {
      $comission = '-';
    }

    return [
      '#' . $booking->booking_number,
      $booking->name,
      $booking->contact_number,
      $booking->email,
      $booking->equipmentTitle,
      $booking->startDate,
      $booking->endDate,
      ucwords($booking->shipping_method),
      $booking->location,
      $price,
      $discount,
      $shippingCost,
      $tax,
      $grandTotal,
      $received_amount,
      $comission,
      is_null($booking->payment_method) ? '-' : $booking->payment_method,
      ucwords($booking->payment_status),
      ucwords($booking->shipping_status),
      $booking->createdAt
    ];
  }
}
