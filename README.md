# Multilingual-Website-Sitemap-Checker
Multilingual Website Sitemap Checker is a tool designed to help website owners check the consistency and completeness of their website sitemaps across multiple languages. With this tool, you can easily identify missing URLs across different language versions of your website.


## Why you might need it

This PHP script retrieves all URLs from the sitemap(s) of a multilingual website and removes language-specific components from each URL, in order to get the direct URL of each page. It then checks for duplicates between the direct URLs and the language-specific URLs and sends an email notification with all the duplicates, using a separate send_email.php script. If there are no duplicates, the script does not do anything.

The script can be customized by changing the domain and TLD, as well as the sitemap URLs for each language.

## Use it as Cron job
0 */2 * * * path/of/php path/of/sitemap_checker.php
The sitemap_checker.php script will now be executed automatically every 2 hours.

