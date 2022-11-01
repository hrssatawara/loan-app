
## Setup Guide

Clone this project on local.

1. Run `cd loan-app`

2. Run `cp .env.example .env`

3. Create database `loanapp` and set the credentials.

4. Run `composer install`

5. Run `php artisan migrate:fresh --seed`

I am using valet on my Mac, so my app url is `http://loan-app.test`

6. You need to run `php artisan serve` and your app url will be `http://127.0.0.1:8000`

7. You need to update app url in `.env` file key `APP_URL=http://127.0.0.1:8000`

## API Setup

You need to import my postman API collection from Postman -> file -> import

This application as already two roles defined. 1. Admin, 2. Customer

Default credentials for Admin.
email: admin@gmail.com
password: password

You can create customer using api Auth > Register

I have also added env variable {{token}} which is used throughout the application.

I have added details in every endpoint documentation.

## Testing

You need to create .env.testing and add testing database(create new) credentials into it.

`cp .env .env.testing`

To test run `php artisan test`

## Functionality Implemented
* Customer add the loan request
  * It will automatically generate the repayments based on amount and tenure.
  * Loan's default status is set to _PENDING_
* Admin can approve the loan and status will be changed to _APPROVED_
  * Customer can only see own loans.
* Customer add the repayment and status will be changed to _PAID_
  * Loan status automatically changed to _PAID_ after adding all repayments.

All The Requests has validation for status, amount etc. and it will return the error with proper message.

I Tried my best to complete this assignment.

## Thank you
