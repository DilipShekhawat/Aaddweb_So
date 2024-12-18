## Installation

Follow these steps to set up the project locally:

1. **Clone the repository:**

``git clone https://github.com/DilipShekhawat/Aaddweb_So.git``
> $ cd your-repo-name

2. **Copy the example environment file:**
``cp .env.example .env``

3. **Install the dependencies:**
``composer install``

4. **Generate the application key:**
``php artisan key:generate``

5. **Start the local development server:**
``php artisan serve``

5. **API Endpoint:**
``http://127.0.0.1:8000/api/data-stream/analyze``

```Payload
{
  "stream": "AAABBBCCCAAABBBCCC",
  "k": 3,
  "top": 3,
  "exclude": ["AAA"]
}



