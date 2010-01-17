#rumpusUserCreation.git

Web Page: None.

A faster method for users to create FTP accounts in a pre-existing setup: it will create the folder and add the user along with send an email containing the details so it can be forwarded to the client.

Requirements: Python 2.5+ (untested with Python 3.x), if you have Leopard or Snow Leopard you're good to go but if you're running Tiger you'll want to install it via say [MacPorts](http://macports.org), Xcode to build the client application.

-------------

## Installation Instructions

The client side application needs to be built within Xcode after modifying line 15 in createFTP.m with the URL of where your ftp.php application will live.

The server side contains two scripts and a web page that should all be placed in an "accessible" location. I recommend placing it in a separate folder that you are willing to lock down using .htaccess, Apache Realms or even just an internal site so that it's not wide open to the public. It's really not designed to be secure on it's own but that the admin installing it will secure it's access if necessary.

In Rumpus you'll need to edit your `Rumpus.conf` file in `/usr/local/Rumpus` and add the line `UserDBReload URL "reloadUserDB"` (you may change reloadUserDB to any other string - just ensure that you change the appropriate line in the Python script)

You will need to edit checkUser.php (line 6), ftp.php (lines 31, 46, 50), rumpusAddUser.py (anyhwere #CHANGE NEXT LINE is seen - roughly 7 places) - this is just values for your specific installation - eg. paths, your domain, etc. Ensure that rumpusAddUser.py has executable priveleges by your www user, and your area where you make folders has the ability for the www user (or whichever user you have Apache running under) has the ability to make folders. (eg. ACL for making folders or lenient permissions).

-------------

## File Listing
	- Client
		- Create FTP User - Xcode project to create the client application
	- README.markdown - Goes without saying
	- Server
		- checkUser.php - An AJAX call to this script checks if the user exists.
		- ftp.php - The web page that the user accesses - some JavaScript fun, and the processing code to actually call the python script.
		- rumpusAddUser.py - Actually the script that adds users, makes folders, etc.
		- indicator.gif - In case an AJAX call is a bit slow.

-------------

The MIT License

Copyright (c) 2010 Micheal Jones

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.