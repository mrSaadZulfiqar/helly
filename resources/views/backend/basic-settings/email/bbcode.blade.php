<div class="col-lg-5">
  <table class="table table-striped border">
    <thead>
      <tr>
        <th scope="col">{{ __('BB Code') }}</th>
        <th scope="col">{{ __('Meaning') }}</th>
      </tr>
    </thead>
    <tbody>
      @if ($templateInfo->mail_type == 'verify_email')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username of User') }}</td>
        </tr>
      @endif
      @if ($templateInfo->mail_type == 'balance_subtract' || $templateInfo->mail_type == 'balance_add')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Username of Vendor') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'verify_email')
        <tr>
          <td>{verification_link}</td>
          <td scope="row">{{ __('Email Verification Link') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'reset_password' ||
              $templateInfo->mail_type == 'product_order' ||
              $templateInfo->mail_type == 'equipment_booking')
        <tr>
          <td>{customer_name}</td>
          <td scope="row">{{ __('Name of The Customer') }}</td>
        </tr>
      @endif

      @if (
          $templateInfo->mail_type == 'equipment_booking' ||
              $templateInfo->mail_type == 'product_order' ||
              $templateInfo->mail_type == 'withdraw_approve' ||
              $templateInfo->mail_type == 'balance_add' ||
              $templateInfo->mail_type == 'balance_subtract')
        <tr>
          <td>{transaction_id}</td>
          <td>Transaction Id</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'reset_password')
        <tr>
          <td>{password_reset_link}</td>
          <td scope="row">{{ __('Password Reset Link') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'product_order')
        <tr>
          <td>{order_number}</td>
          <td scope="row">{{ __('Order Number') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'product_order')
        <tr>
          <td>{order_link}</td>
          <td scope="row">{{ __('Link to View Order Details') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'equipment_booking')
        <tr>
          <td>{booking_number}</td>
          <td scope="row">{{ __('Booking Number') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'equipment_booking')
        <tr>
          <td>{booking_date}</td>
          <td scope="row">{{ __('Booking Date') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'equipment_booking')
        <tr>
          <td>{equipment_name}</td>
          <td scope="row">{{ __('Name of The Equipment') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'equipment_booking')
        <tr>
          <td>{start_date}</td>
          <td scope="row">{{ __('Booking Start Date') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'equipment_booking')
        <tr>
          <td>{end_date}</td>
          <td scope="row">{{ __('Booking End Date') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'equipment_booking')
        <tr>
          <td>{booking_link}</td>
          <td scope="row">{{ __('Link to View Booking Details') }}</td>
        </tr>
      @endif
      @if ($templateInfo->mail_type == 'equipment_booking')
        <tr>
          <td>{vendor_details_link}</td>
          <td scope="row">{{ __('Link to View Vendor Details') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'withdraw_approve')
        <tr>
          <td>{withdraw_amount}</td>
          <td scope="row">{{ __('Total Withdraw Amount') }}</td>
        </tr>
        <tr>
          <td>{charge}</td>
          <td scope="row">{{ __('Total Charge of Withdraw') }}</td>
        </tr>
        <tr>
          <td>{payable_amount}</td>
          <td scope="row">{{ __('Total Payable Amount') }}</td>
        </tr>

        <tr>
          <td>{withdraw_method}</td>
          <td scope="row">{{ __('Method Name of Withdraw') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'withdraw_approve' || $templateInfo->mail_type == 'withdraw_rejected')
        <tr>
          <td>{vendor_username}</td>
          <td scope="row">{{ __('Username of the vendor') }}</td>
        </tr>
        <tr>
          <td>{withdraw_id}</td>
          <td scope="row">{{ __('Withdraw Id') }}</td>
        </tr>
      @endif
      @if (
          $templateInfo->mail_type == 'withdraw_approve' ||
              $templateInfo->mail_type == 'withdraw_rejected' ||
              $templateInfo->mail_type == 'balance_add' ||
              $templateInfo->mail_type == 'balance_subtract')
        <tr>
          <td>{current_balance}</td>
          <td scope="row">{{ __('Current Balance of Vendor') }}</td>
        </tr>
      @endif
      @if ($templateInfo->mail_type != 'security_deposit_refund')
        <tr>
          <td>{website_title}</td>
          <td scope="row">{{ __('Website Title') }}</td>
        </tr>
      @endif

      @if ($templateInfo->mail_type == 'security_deposit_refund')
        <tr>
          <td>{username}</td>
          <td scope="row">{{ __('Customer username') }}</td>
        </tr>
        <tr>
          <td>{equipment_name}</td>
          <td scope="row">{{ __('Equipment Title') }}</td>
        </tr>
        <tr>
          <td>{booking_number}</td>
          <td scope="row">{{ __('Booking Number of Equipment') }}</td>
        </tr>
        <tr>
          <td>{refund_type}</td>
          <td scope="row">{{ __('Refund Type of Security Deposit Amount') }}</td>
        </tr>
        <tr>
          <td>{amount}</td>
          <td scope="row">{{ __('Refund Amount') }}</td>
        </tr>
        <tr>
          <td>{actual_security_deposit_amount}</td>
          <td scope="row">{{ __('Value of actual security deposit amount') }}</td>
        </tr>
      @endif
    </tbody>
  </table>
</div>
