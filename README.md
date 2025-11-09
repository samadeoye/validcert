# Certificate Verification Web Application

A web application for verification of academic certificates with integration of cryptographic techniques.

## Main Features

### Issuers Area:
- Create new certificates (via form - for single entry, and import - for multiple certificates).
- Manage certificates (update and delete).
- View certificates verifications history (for their issued certificates).

### Admin Area:
- Approve or reject new issuers, manage users (issuers and verifiers).
- View certificates (for all issuers).
- View certificates verification history (for all issuers).
- View audit logs (on certificates and users profile).

### Verifiers Area:
- Not registered: Verify a certificate via a form or by scanning a QR code.
- When registered and logged-in: Verify a certificate via a form or by scanning a QR code, and view history of previous verifications.

## Technologies Used
- **Frontend**: Bootstrap, HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL