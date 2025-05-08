# Laravel Stripe Integration

This project is a full-stack application that demonstrates the integration of Stripe payment processing with Laravel backend and React frontend. It provides a seamless e-commerce experience with product listing and secure payment processing.

## Project Structure

The project is divided into two main parts:
- `api/`: Laravel backend application
- `web/`: React frontend application

## Frontend-Backend Communication

The frontend (React) communicates with the backend (Laravel) through RESTful API endpoints. The communication is handled using the Fetch API, with the following endpoints:

### Base URL
```
http://localhost:8000/api
```

## API Documentation

### 1. List All Products
```http
GET /products
```

**Response**
```json
[
  {
    "name": "string",
    "image": "string",
    "description": "string",
    "currency": "string",
    "price": number,
    "price_id": "string"
  }
]
```

### 2. Create Checkout Session
```http
POST /checkout
```

**Request Body**
```json
{
  "price_id": "string"
}
```

**Response**
```json
{
  "url": "string",
  "session_id": "string"
}
```

### 3. Verify Checkout Session
```http
GET /checkout/session/{id}/verify
```

**Response**
```json
{
  "id": "string",
  "payment_status": "string",
  // ... other Stripe session details
}
```

## Frontend Implementation

The frontend is built with:
- React + TypeScript
- Vite as the build tool
- TailwindCSS for styling
- React Router for navigation

Key features:
1. Product listing page with responsive grid layout
2. Secure checkout process using Stripe
3. Success and cancel pages for payment status
4. Session verification middleware

## Backend Implementation

The backend is built with:
- Laravel PHP framework
- Stripe PHP SDK for payment processing
- RESTful API architecture

Key features:
1. Product management through Stripe
2. Secure checkout session creation
3. Payment verification
4. Error handling and validation

## Getting Started

1. Clone the repository
2. Set up the Laravel backend:
   ```bash
   cd api
   composer install
   cp .env.example .env
   # Configure your Stripe API keys in .env
   php artisan serve
   ```

3. Set up the React frontend:
   ```bash
   cd web
   npm install
   npm run dev
   ```

4. Access the application at `http://localhost:5173`

## Environment Variables

### Backend (.env)
```
STRIPE_SECRET=your_stripe_secret_key
```

### Frontend
The frontend expects the backend to be running at `http://localhost:8000`. If you need to change this, update the API URLs in the frontend code.

## Security Considerations

1. All API endpoints are protected with proper validation
2. Stripe integration uses secure session-based checkout
3. Payment verification is implemented to prevent unauthorized access
4. Environment variables are used for sensitive data

## Error Handling

The application implements comprehensive error handling:
- Frontend: Error states for failed API calls
- Backend: Proper HTTP status codes and error messages
- Stripe integration: Exception handling for payment processing
