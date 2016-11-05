steamcn-feed
======
An self made php program that fetches steamcn and transformed into rss format.

Usage
------
All you need is modify `config.php`, and upload to server.

You may need some website monitor on cloud to worked as cron.

index.php?install=init
------
For initializing SQL, if firstrun, please run this once.

index.php?reinit=true
------
Will truncate $tabletime and $tablelist set in `config.php`. Use it if you get codex error when using `Feed Notifier`.

index.php
------
You need to set the website monitor, 5~15 mins/visit will be good.

And set this as feed url in `Feed Notifier`.