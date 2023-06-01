# Native PHP Blog

Native PHP Blog is a simple blog application built using PHP without any framework. This application utilizes a SQL database to store and manage blog content.

## Features

- User registration and login.
- Displaying a list of blog posts.
- Individual blog post pages.
- Adding new posts.
- Editing and deleting posts.
- User and post administration.

## Installation

1. Clone this repository: `https://github.com/Fujitime/php-blog.git`.
2. Navigate to the project directory: `php-blog`.
3. Import the SQL file into your database. The SQL file can be found at `blog.sql`.
4. Configure the database connection in the `koneksi.php` file.
5. Open the application in your browser.

## Requirements

- PHP 7.0 or newer.
- MySQL or any other SQL database.

## Tailwind CSS

This project utilizes Tailwind CSS for styling. To get started with Tailwind CSS, follow these steps:

1. Install Tailwind CSS using Yarn: `yarn add tailwindcss` or using npm: `npm install tailwindcss`.
2. Build the CSS file by running the following script: `yarn tail` or `npm run tail`. This command will compile the `input.css` file into `style.css` using Tailwind CSS.
3. Make sure to link the compiled `style.css` file in your HTML templates.

## Usage

1. Create a MySQL database and import the SQL file `blog.sql` to set up the required tables.
2. Configure the database connection in the `koneksi.php` file by providing the appropriate credentials.
3. Start your local development server or use a web server software to run the application.
4. Open the application in your web browser.

## Default Admin Account

The default admin account credentials are as follows:

- Username: admin
- Password: 12345

Please make sure to change the default username and password after installation for security purposes.

## Contributing

We welcome contributions to this project! If you would like to contribute, please follow these steps:

1. Fork this repository.
2. Create a new branch for your feature or fix: `git checkout -b new-feature`.
3. Make necessary changes.
4. Commit your changes: `git commit -m "Add new feature"`.
5. Push to your feature branch: `git push origin new-feature`.
6. Submit a pull request to this repository.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
