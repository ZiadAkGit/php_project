# Watch Shop - PHP Shopping Cart System

This is a PHP-based shopping cart system built as a course project. It allows users to sign up, log in, browse products, manage their cart, and place orders. Admin users can manage the product inventory via a dashboard.

---

## 📁 Project Structure

```
├── index.php               # Landing page (login/signup)
├── signup.php              # User registration logic
├── dashboard.php           # Main user dashboard (product browsing)
├── Cart.php                # Cart management (add, remove, edit, order)
├── Product.php             # Admin panel for managing products
├── admin_dashboard.php     # Admin view for all products
├── connect_to_DB.php       # Database connection logic
├── User.php                # User session & authentication handler
├── styles.css              # Unified style for all pages
```

---

## 💡 Features

### ✅ General Users
- Register and log in securely
- Browse available products
- Add items to cart
- Edit or remove items from the cart
- Place orders (stock updates automatically)

### ✅ Admin
- View and manage all products
- Edit and delete product entries
- Real-time inventory control

---

## 🛠️ Technologies Used

- PHP (core logic)
- MySQL (database)
- HTML5 & CSS3 (frontend)
- JavaScript (for quantity editing)
- Sessions (for login management)

---

## 🧪 How to Run

1. Clone or download the project files.
2. Import the MySQL database (ask your instructor if not provided).
3. Update `connect_to_DB.php` with your local DB credentials.
4. Start a PHP server (e.g. using XAMPP, MAMP, or built-in PHP server).
5. Visit `http://localhost/index.php`.

---

## 📌 Notes

- Make sure your `cart` and `products` tables contain an `id` or `product_id` to uniquely identify items.
- Sessions are used to manage login states, ensure `session_start()` is active at the top of each PHP file that requires user data.


## 💻 Developers
- Ziad AK
- Nour Nimry
