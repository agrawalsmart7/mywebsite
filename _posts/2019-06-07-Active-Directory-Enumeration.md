Hello all,

Welcome to this post, this post covers How to enumerate Active Directory Environment. I just want to share what I learned #SharingisCaring. 

This post skips the part of "Installing Active directory in Windows 2008 server."

Always practice in Enviroment, so for that I have created a Virtual Playground in which there is a domain controller and client. 

So our domain is "ad.lab"

This is my Domain Controller (DC) --> 192.168.0.8

<img src="../../../ad_front.png">


This is my client which registers in DC as "utkarsh123" --> 

<img src="../../../Capture.PNG">

Image client 



So let's get started,

The first stage would be is to get into the network of the target. 
So for that you will have to find some way to get in for ex, getting Password hash with Responder, 
any service vulnerable for exploiting, Social Engineering etc 
So for the sake of simplicity I already uploaded a EXE file onto the target machine. 
The file was created with meterpreter payload with msfvenom.

So we have all set to exploit, so lets exploit it.

After opening the file, as you can see the meterpreter connection to our machine hence we are in.

Image


Now first thing we should check the domain name, 

command:- wmic computersystem get domain

And here it is

Image

Now we know the Domain name of domain controller, so its time to enumerate the information from Domain controller.

I use Powerview for enumeration. It was written by HarmJoy. [Correction]


This command will list out the domain groups in ADDS 

Command: Get-NetGroup

Image


After we know the domain groups, then try to enumerate the users who are live in them.

Command: Get-NetGroupMember 

Image

By default it get the "Domain Admins" but you can change it if you see any interesting Group like this.

Command: Get-NetGroupMember Groupname

Image


So we now know who is Admin. Its worth to check if any misconfiguration is in their profile.

Command: Get-NetUser username

Image

Why I said Misconfiguration is because they often allow to see the email, Phone number etc which can be helpful for Social Engineering.











