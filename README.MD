# Admin Panel for Bookstore App

This is an admin panel built to manage and support the **Bookstore App**, a Flutter-based application. The admin panel provides tools for managing key resources such as users, books, authors, and more.  
With this panel, administrators can easily oversee and control app content, ensuring smooth operation and a seamless user experience in the Bookstore App.  

---

## Table of Contents

1. [Features](#features)
2. [Installation](#installation)
3. [Usage](#usage)
4. [Required Libraries](#required-libraries)

---

## Features

- **User Management**: Add, update, and delete user profiles with ease. Manage user permissions and view activity logs.  

- **Book Management**: Handle the addition, modification, and deletion of books. Organize books by category and manage inventory details.  

- **Author Profiles**: Maintain detailed author profiles, link books to their respective authors, and update author information seamlessly.  


---

## Installation

### Prerequisites

Ensure you have the following installed on your machine:
- PHP 8.0 or higher
- Composer
- [Any other requirements]

### Steps

1. Clone the repository:
    ```bash
    git clone https://github.com/Kunzete/Book-Store-App-Admin-Panel.git
    cd Book-Store-App-Admin-Panel
    ```

2. Write Composer.json
    ```json
    {
        "require": {
            "bensontrent/firestore-php": "^3.1",
            "kreait/firebase-php": "^7.16",
            "google/cloud-firestore": "^1.47"
        }
    }
    ```


3. Install dependencies:
    ```bash
    composer install
    ```

4. Set up environment variables:
   - Create a `.env` file in the project root.
   - Add your configuration details (e.g., Firebase credentials, API keys, etc.).

---

## Usage

Provide instructions for running and interacting with your project:

1. Start the application:
    ```bash
    php -S localhost:8000
    ```
2. Access the application at `http://localhost:8000`.

---

## Required Libraries

This project requires the following libraries:

1. **Bensontrent PHP SDK**  
   - [GitHub/Documentation Link](https://github.com/bensontrent/firestore-php)
   - A brief explanation of its role in the project.

2. **Kreait Firebase PHP SDK**  
   - [GitHub/Documentation Link](https://github.com/kreait/firebase-php)
   - Used for interacting with Firebase services.

3. **Google Cloud Firestore**  
   - [GitHub/Documentation Link](https://github.com/googleapis/google-cloud-php-firestore)
   - Handles Firestore database operations.

---

**Happy Coding!** 🎉
