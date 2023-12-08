# Web Crawler

Welcome to the Web Crawler, a simple PHP-based web crawling application.

## Overview

The Web Crawler is designed to explore and fetch data from web pages, starting from a specified seed URL and crawling to a defined depth. It also includes functionality to search for specific keywords within the crawled data.

## Getting Started

### Prerequisites

- PHP installed on your server
- cURL extension enabled
- Write permissions for the `data` directory

### Installation

- Clone the repository:

   git clone https://github.com/mtahakhan07/WebAssignment2.git

### Usage

1. Open index.php in your web browser.
2. Enter the seed URL and the maximum depth for crawling.
3. Click on the "Start Crawling" button.
4. The crawler will fetch data from the specified URL and store it in the data directory.
5. After crawling, you can use the keyword search functionality by navigating to keywordSearch.php and entering the desired keyword.

### Files and Structure

- index.php: The main user interface for initiating the crawling process.
- crawler.php: Contains the PHP class (Crawler) responsible for crawling web pages and fetching data.
- keywordSearch.php: Provides a simple search interface to find URLs containing a specific keyword.
- data/: Directory to store the crawled data in JSON format.

### Notes

The crawler adheres to robots.txt files to respect website crawling policies.

### Contributing

Feel free to contribute to the project by submitting issues or pull requests.
