rex_ftpmirror
=========

Redaxo FTPMirror AddOn

How to install:
Download the package, _rename the folder to **"rex_ftpmirror"**_ (without quotes) and upload it into your REDAXO AddOn dir.

--


Notes:
- PUSH AND GET all your data from your local server <-> remote server trough ftp/sftp (recursively)
- automatic feature detection (trough shell)
- For all those without shell access can use the FTP Push php version instead of FTP Push console (sadly quite slow and need a high max_execution_time)

Supported:
- LFTP (default at many hosting services)

ToDo:
- SCP
- RSYNC
