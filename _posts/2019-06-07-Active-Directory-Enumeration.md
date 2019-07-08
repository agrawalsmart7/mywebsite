Hello all,

Welcome to this post, this post covers How to enumerate Active Directory Environment. I just want to share what I learned #SharingisCaring. 

So What is Active Directory?

Active directory is a service which stores the objects into a centralised way. Objects like users, Computers, Groups etc. All these info are stored in Domain Controller. AD uses LDAP to retrieve the data from the Domain controller. It also uses DNS for let the client to locate the DC. More info is [here](https://searchwindowsserver.techtarget.com/definition/Active-Directory).

This post skips the part of "Installing Active directory in Windows 2008 server."

Always practice in Enviroment, so for that Virtual Playground includes a Domain Controller (windows server 2008) and Client (Windows 7). 

So our domain is "**ad.lab**"

This is my Domain Controller (DC) IP is 192.168.0.8

<img src="../../../ad_front.png" height='50%' width="100%">


This is my client where IP is 192.168.0.10 

<img src="../../../Capture.PNG" height='50%' width="100%">


So let's get started,

The first stage would be is to get into the network of the target. 
And it can be in any way like through the use of Responder, Exploiting any service vulnerability, Social Engineering etc.

So for the sake of simplicity what I have done is  I already uploaded a EXE file onto the target machine which is a part of domain.
The file was created with meterpreter payload with msfvenom.

So what will happen if user opens up the exe file? The target machine will send a GET request to our machine because that how the meterpreter payload works, 

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











