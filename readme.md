# Fujika Industries – Local Setup Guide (Windows + XAMPP Edition)

This guide explains how to set up the Fujika Industries project locally on a Windows machine using XAMPP.

---

## 🖥 Requirements

- PHP 5.6
- Composer 1.1
- XAMPP (Apache)

> This project uses legacy versions. Make sure you install the exact versions mentioned above.

---

## Step 1: Install PHP 5.6

Ensure your XAMPP installation is running **PHP 5.6**.

Verify your PHP version:

```bash
php -v
```

---

## Step 2: Install Composer 1.1

Install Composer version 1.1.

Verify the installed version:

```bash
composer --version
```

It should show Composer 1.1.x.

---

## Step 3: Place Project in htdocs

Move the project folder inside:

```
C:\xampp\htdocs\
```

Example:

```
C:\xampp\htdocs\fujika_industries
```

---

## Step 4: Install Dependencies

Navigate to the project directory and run:

```bash
composer install
```

---

## Step 5: Generate APP_KEY

Run the following command inside the project directory:

```bash
php artisan key:generate
```

If this fails:

- Ensure PHP 5.6 is active
- Ensure Composer dependencies are installed
- Ensure `.env` file exists

---

## Step 6: Configure Virtual Host (MANDATORY)

> Do NOT use `php artisan serve`.  
The project must run via Apache Virtual Host to match production.

### 6.1 Enable VHosts in Apache

Open:

```
C:\xampp\apache\conf\httpd.conf
```

Find this line and make sure it is **uncommented**:

```
Include conf/extra/httpd-vhosts.conf
```

---

### 6.2 Add Virtual Host Entry

Open:

```
C:\xampp\apache\conf\extra\httpd-vhosts.conf
```

Add the following configuration:

```apache
<VirtualHost *:80>
    ServerName fujika.local
    DocumentRoot "C:/xampp/htdocs/fujika_industries"

    <Directory "C:/xampp/htdocs/fujika_industries">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Save the file.

---

### 6.3 Update Windows Hosts File

Open the hosts file as Administrator:

```
C:\Windows\System32\drivers\etc\hosts
```

Add this line at the bottom:

```
127.0.0.1   fujika.local
```

Save the file.

---

## Step 7: Restart Apache

Restart Apache from the XAMPP Control Panel.

---

## Step 8: Access the Project

Open your browser and visit:

```
http://fujika.local
```

---

## Troubleshooting

### 500 Internal Server Error
- Ensure `AllowOverride All` is set
- Make sure `.htaccess` exists
- Restart Apache

### Composer Issues
- Confirm Composer 1.1 is installed
- Delete the `vendor` folder and run `composer install` again

### APP_KEY Missing
- Check that the `.env` file exists
- Run `php artisan key:generate`

---

## Important Notes

- Do NOT use `php artisan serve`
- PHP must be version 5.6
- Composer must be version 1.1
- Always restart Apache after changing VHost configuration

---

You should now have the project running locally.