"""rumpusAddUser.py

This program adds a user to the Rumpus Database.

Usage: /PATH/TO/python2.5 rumpusAddUser.py "Email Address" "USERNAME" "PASSWORD"
or
Usage: rumpusAddUser.py --check "USERNAME"

# Takes the following arguments:
# $1 = Your Email Address
# $2 = Username
# $3 = Password

# Error Codes
# 1 Argument Error
# 2 User already exists
# 3 Unable to reload Database

Version History:

1.2.3		Export the websiteLoginLink as well
1.2.2		Fix issue with symbols causing issue in the web autologin. It occurs sometimes with
			the FTP links as well, but copying and pasting will workaround that.
1.2.1		Fix issue for usernames that started with [A-Fa-f]
1.2.0		Emails now sent as HTML/Text to help alleviate issues with punctuation in links
1.1.6		Fix issue with submitting with a password would fail.
1.1.5		Add version output
			Fix spacing of email
			Fix login links for website (namely add it)
1.1.4		Remove phonetics
			Remove @, ? and &s from generated passwords
			Revise email (note: can not make auto login link - doesn't support GET)
1.1.3		Add printDetails for parsing in ftp.php
1.1.2		Fix password spacing
			Fix missing "/" at end of paths causing broken accounts
1.1.1		Fix bcc support
1.1.0		Add --check mechanism
1.0.1		Add bcc to support@joemedia.tv
1.0.0		Initial Release

"""
version = "1.2.3"
__author__ = "Micheal Jones (michealj@joemedia.tv)"
__version__ = "$Revision: 1.2.3 $"
__date__ = "$Date: 2010/03/18 $"
__copyright__ = "Copyright (c) 2010 Micheal Jones"
__license__ = "BSD"

import sys
import os
import httplib
import smtplib
import urllib
import random

########################

def displayVersion():
# Show version
	print version


########################

def usage(error):
# Show Usage
	print "Usage: rumpusAddUser.py \"CREATOR EMAIL\" \"USERNAME\" \"PASSWORD\"\n\n"
	
	if error == 1:
		print "Not Enough Arguments - Missing Username and Password?"
	else:
		print "User already exists - aborting."

########################

def readEntries(RUMPUS_PATH):
# READ ENTRIES INTO LIST
	#Open File with rU for universal line endings
	f = open(RUMPUS_PATH, 'rU')
	
	users = f.readlines()
		
	f.close()
	return users
	
########################

def checkEntries(users, username):
# Check entries to see if user exists

	for user in users:
		user = user.split()
		if user[0] == username:
			return False
	
	return True

########################

def createPassword():
# Create a random password - modified from passwordGen.py
	password = ""
	
	#Create Dictionary
	sub_alphabet = ["a", "b", "c", "d", "e", "f", "g", "h", "j", "k", "m", "n", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "2", "3", "4", "5", "6", "7", "8", "9", "~", "#", "$", "^", "*", "-", "_", "."]
	
	for count in range(0,8):
		#Choose a random character
		character = random.randint(0, len(sub_alphabet) - 1)
		
		password += sub_alphabet[character]
	
	return password

########################

def makeFolder(PATH):
# Create containing folder at PATH if it doesn't exist.
	if os.path.isdir(PATH) == True:
		print "WARNING: Folder already exists"
		return
	os.mkdir(PATH)

########################

def emailUser(email, username, password):
# Email whomever made the account with the account details
	
	#Get login link
	#CHANGE NEXT LINE
	websiteLoginLink = "http://www.DOMAIN HERE:8080/?login=" + username + ":" + urllib.quote(password)
	#CHANGE NEXT LINE
	ftpLoginLink = "ftp://" + username + ":" + password + "@FTP_SITE_HERE"
	
	#Set up headers first
	#CHANGE NEXT LINE
	message = "Content-Type: text/html\r\nFrom: SENDING@ADDRESS\r\nSubject: FTP Account Created\r\nTo: " + email + "\r\n\r\n"
	
	#CHANGE NEXT LINE: Obviously read the following text for the email and change accordingly. eg. Change FTP_SERVER to your FTP_SERVER's address (eg. ftp.example.edu)
	message += """
<p>Hi,<br><br>

A new FTP account has been created on the COMPANY_NAME FTP server for you.</p>

<p>Your Username is: """ + username + """<br>
Your Password is: """ + password + """</p>

<p>To access the files available for this account you may use one of the following options:</p>

<ol style="margin: 0; padding-left: 15px">
<li>Use our website<br>
	Use this link to access the Client Site and be logged in automatically: < <a href=\"""" + websiteLoginLink + "\">" + websiteLoginLink + """</a> ><br>
	Or<br>
	Go to http://WEBSITE/ and click on the client login section on the top menu bar where you may enter your username and password.</li><br>

<li>Use a FTP client<br>
	Use this link to log you in directly: < <a href=\"""" + ftpLoginLink + "\">" + ftpLoginLink + """</a> ><br><br>
 
	Or manually enter the following details:<br>
		Server: FTP_SERVER<br>
		Username and password from above</li>
</ol>

Thanks!
	"""
	
	# Send the email - we are not handling any exceptions because of our static environment
	conn = smtplib.SMTP('MAIL SERVER HERE', '25')
	conn.sendmail('SENDING ADDRESS', [email, 'BCC EMAIL ADDRESS'], message)
	conn.quit()

########################

def printDetails(username, password):
# Write out details

 	print username + "\n" + password + "\n" + "\n afp://AFP_SERVEr" + username + "\n" + "http://DOMAIN:PORT/?login=" + username + ":" + urllib.quote(password)

########################

def addEntry(CLIENT_PATH, username, password):
# ADD NEW ENTRY
	
	if password == "":
		password = createPassword()

	path = CLIENT_PATH + username + "/"

	user = username + "\t" + password + "\t" + path + "	YYYYYYYYNNN	0	0		N1	N16	N10	NBRR	P	N16	N-				.	\n"
	
	return user, password

########################

def sortEntries(users):
# SORT ENTRIES

	#Sort Users
	users.sort(key=lambda x: x.lower())
	return users

########################

def writeEntries(users, RUMPUS_PATH):
# WRITE ENTRIES INTO FILE
	f = open(RUMPUS_PATH, 'w')
	for userline in users:
		f.write(str(userline))
	
	f.close()

########################

def reloadURL(SERVER, PORT, RELOAD_URL):
# RELOAD THE RUMPUS DATABASE
	conn = httplib.HTTPConnection(SERVER, PORT)
	conn.request('GET', RELOAD_URL)
	
	reloadSuccess = conn.getresponse()
	
	if reloadSuccess.read() != "User Database Reloaded":
		print "Unable to reload database"
		exit(3)
	
	conn.close()

########################

def main(argv):
	RUMPUS_PATH = "/usr/local/Rumpus/Rumpus.users"
	#CHANGE THE FOLLOWING NEXT 4 LINES AS THEY APPLY.
	SERVER = "SERVER_DOMAIN"
	PORT = "8080"
	RELOAD_URL = "/reloadUserDB" #SERVER_DOMAIN/RELOAD_URL - UserDBReloadURL in Rumpus.conf
	CLIENT_PATH = "/PATH/TO/WHERE/THE/FOLDERS/ARE/STORED"
	
	#Check if arguments are valid
	if len(argv) < 3:
		if len(argv) > 1:
			if argv[1] == "--version":
				displayVersion()
				exit(0)
		usage(1)
		exit(1)
	elif len(argv) < 4:
		argv.append("")
	
	#CHANGE NEXT LINE (DOMAIN to your DOMAIN eg. example.edu for user@example.edu)
	EMAIL = argv[1] + "@DOMAIN"
	username = argv[2]
	password = argv[3]

	os.chdir(CLIENT_PATH)
	users = []
	users = readEntries(RUMPUS_PATH)
	
	#Checking mechanism
	if argv[1] == "--check":
		if checkEntries(users, argv[2]) == False:
			print "User Exists"
			exit(2)
		else:
			print "Valid Username"
			exit(0)

	if checkEntries(users, username) == False:
		usage(2)
		exit(2)
	makeFolder(CLIENT_PATH + username)
	user = addEntry(CLIENT_PATH, username, password)
	users.append(user[0])
	password = user[1]
	users = sortEntries(users)
	writeEntries(users, RUMPUS_PATH)
	reloadURL(SERVER, PORT, RELOAD_URL)
	emailUser(EMAIL, username, password)
	printDetails(username, password)

########################

if __name__ == '__main__':
	main(sys.argv)
