# EVENT-MANAGEMENT-AND-ATTENDANCE-SYSTEM-WITH-QRCODE

An innovative event management and attendance tracking system that utilizes QR code technology for efficient and accurate participant registration and attendance monitoring.

## Features

- Event creation and management
- Participant registration and management
- QR code generation for each registered participant
- Mobile app for QR code scanning and attendance tracking
- Real-time attendance updates
- Reporting and analytics dashboard
- User roles (Admin, Event Organizer, Participant)
- Email notifications for event updates and reminders

## Tech Stack

- **Backend:**
  - PHP
  - MYSQL for database
  - Mangoose as ODM
  - JSON Web Tokens (JWT) for authentication

- **Frontend (Web):**
  - PHP

- **QR Code:**
  - qrcode.js for QR code generation


## Installation

1. Clone the repository:
   ```
   git clone https://github.com/yourusername/event-management-qr-system.git
   cd event-management-qr-system
   ```

2. Install backend dependencies:
   ```
   cd backend
   npm install
   ```

3. Install frontend dependencies:
   ```
   cd ../frontend
   npm install
   ```

4. Install mobile app dependencies:
   ```
   cd ../mobile
   npm install
   ```

5. Set up environment variables:
   - Create `.env` files in the `backend`, `frontend`, and `mobile` directories
   - Add necessary environment variables (e.g., MongoDB URI, JWT secret, API endpoints)

## Running the Application

1. Start the backend server:
   ```
   cd backend
   npm start
   ```

2. Start the frontend development server:
   ```
   cd frontend
   npm start
   ```

3. Run the mobile app:
   ```
   cd mobile
   expo start
   ```

## Usage

1. **Creating an Event:**
   - Log in as an admin or event organizer
   - Navigate to the "Create Event" page
   - Fill in event details and save

2. **Registering Participants:**
   - Go to the event page
   - Add participant details
   - System generates a unique QR code for each participant

3. **Tracking Attendance:**
   - Use the mobile app to scan participant QR codes at the event
   - Attendance is updated in real-time on the dashboard

## Contributing

We welcome contributions! Please see our [Contributing Guidelines](CONTRIBUTING.md) for more details.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

Streamline your event management and attendance tracking with QR code technology!
