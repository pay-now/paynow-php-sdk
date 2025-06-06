## 3.0.0
- Replace outdated dependency

## 2.4.4
- Add status method to shop configuration service
- Added "Click To Pay" (C2P) payment method

## 2.4.3
- Replace dependency to older

## 2.4.2
- Set api v3 as default version
- Replace outdated dependency

## 2.4.1
- Added new payment method support

## 2.4.0
- Added PHP 8 support
- Added payment method list default value
- Added client external id calculator

## 2.3.0
- Added v3 support for API
- Added new features to payments methods

## 2.2.2
- Added applePayEnabled parameter in get payment methods functionality support

## 2.2.1
- Removed trailing slash from environment urls
- Added slash on first position to service urls

## 2.2.0
- Added v2 support for payment methods API
- Added support for GDPR clauses

**Breaking Changes:**
- Optional redirectUrl for Authorize

## 2.1.4
- Added abandoned status for payment

## 2.1.3
- Added new statuses for payment

## 2.1.2
- Added PSR17 client discovery support

## 2.1.1
- Added filters to retrieve payment methods
- Added Google Pay to payment method types
- Updated dependencies

## 2.1.0
- Added payment's refund support
- Added retrieve available payment methods

## 2.0.2
- Initialize `$errors` in `PaynowException` as empty list

## 2.0.1
- Fixed PHP version in composer.json
- Fixed typo in `Payment`

## 2.0.0
- Introduced PSR-17 and PSR-18 to HTTP Client
- Updated README

**Breaking Changes:**
- Changed type of `$errors` in `PaynowException` to `Error`
- Changed the name of method `getErrorType` for `Error`
- Changed type of `$data` to string for `SignatureCalculator`
- Changed `Payment::authorize` response to `Authorize`
- Changed `Payment::status` response to `Status`
- Required PHP since 7.1

## 1.0.6
- Marked `getErrorType` for `Error` as deprecated

## 1.0.5
- Fixed missing headers for payment status

## 1.0.4
- Added support for `signature` from headers

## 1.0.3
- Fixed dependencies
- Fixed examples for README file
- Added Travis CI support

## 1.0.2
- Changed Http Client

## 1.0.1
- Added implicit Idempotency Key for payment authorize
- Added Payment Status enums
- Removed User-agent version

## 1.0.0
- Initial release
