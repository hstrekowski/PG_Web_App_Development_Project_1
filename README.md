# WAD Project — Web App Development

## Web Model: _Photo Gallery Application_

Project created for the **Web App Development** course. This repository presents a complete example of a full-stack web application built with **PHP** and **MongoDB**. 
The application allows users to manage a digital photo gallery, featuring user authentication, private/public image collections and a persistent selection system based on sessions.

---

## 📂 Repository Contents

- **`business.php`** – the business logic layer:
  - functions for MongoDB operations,
  - image processing (thumbnail creation),
  - user authentication and password hashing.

- **`controller.php`** – the controller layer:
  - functions handling specific user actions,
  - data preparation for views,
  - redirect logic and session management.

- **`routing.php`** – routing configuration:
  - mapping of URL paths to specific controller actions,
  - definitions for GET and POST requests.

- **`views/`** – a directory containing PHP view templates:
  - `gallery_view.php` – the main gallery display with pagination,
  - `login_view.php` / `register_view.php` – authentication forms,
  - `saved_view.php` – display of images stored in the user session.

- **`web/`** – the public directory (document root):
  - **`index.php`** – the Front Controller (entry point of the application),
  - **`static/`** – CSS stylesheets,
  - **`images/`** – storage for original photos and thumbnails,

---

## 🌐 Technology Stack and Persistence

The application utilizes a web stack focused on scalability and NoSQL data management:

- **PHP 8.x** – server-side logic, session management, and template rendering.
- **MongoDB** – NoSQL database used for:
  - Storing user credentials (hashed).
  - Managing image metadata (titles, authors, privacy status, file paths).
- **Session Management** – implementation of a "Select" feature, allowing users to keep track of favorite photos across different pages.
- **GD Library** – used for server-side image manipulation and thumbnail creation.

---

## 🧩 Project Features

- **Advanced Image Handling**:
  - Uploading images with automated thumbnail generation.
  - Validation of file types (JPG/PNG) and file sizes.
  - Access control: public images for all, private images only for registered authors.

- **User Authentication & Security**:
  - Secure registration with password hashing (`password_hash`).
  - Session-based authentication to protect private galleries.
  - Persistent selection of images (stored in session even after navigating away).

- **Dynamic Interaction**:
  - Pagination for large collections of images.
  - Ability to clear "saved" images and manage the session state.
