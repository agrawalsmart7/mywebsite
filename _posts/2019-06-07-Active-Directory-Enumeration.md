Hello all,

Welcome to this post, this post covers How to enumerate Active Directory Environment. I just want to share what I learned #SharingisCaring. 

**What is Active Directory?**

Active directory is a service which stores the objects into a centralised way. Objects like users, Computers, Groups etc. All these info are stored in Domain Controller. AD uses LDAP to retrieve the data from the Domain controller. It also uses DNS for let the client to locate the DC. More info is [here](https://searchwindowsserver.techtarget.com/definition/Active-Directory).

This post skips the part of "Installing Active directory in Windows 2008 server."

**What is the Target for enumerating Active Directory?**

We enumerate AD to see who have the higher privileges than our current user. We enumerate privileged users, Password policies, Groups, Group members, Misconfigurations. So that we can focus on targetting them. Make sense?

Always practice in enviroment, so for that our Virtual playground includes a Domain Controller (Windows Server 2008) and Client (Windows 7). 

This is my Domain Controller (DC) and its IP is `192.168.0.8`

<img src="../../../ad_front.png" height='50%' width="70%">


This is my client where its IP is `192.168.0.10 `

<img src="../../../Capture.PNG" height='50%' width="70%">


So let's get started,

The first stage would be is to get into the target network. 

There are multiple way to do it like LLMNR & NBTNS Poisoing, Exploiting network service, Social Engineering etc its depend on enumerating the target network as much as you can. But that's not the target for this post. ;)

So for the sake of simplicity I already uploaded a EXE file onto the target machine. The file was created with meterpreter reverse payload with **msfvenom**.

So what will happen if the user opens up that EXE file? 

When the binary loads, it will spawn a shell back to the listener and hence we get control over the shell.

Okay, lets exploit it.

<img src="../../../meterpreter.png" height="50%" width="70%">

So we got a meterpreter shell.

Is the target machine is on a domain? Let's check it,

command:- wmic computersystem get domain

And here it is

<img src="../../../getdomain.png" height="50%" width="70%">

Since this user is on domain, so its time to enumerate the information from DC.

I use Powerview for enumeration. It was written by Harmj0y. [Correction]

But for doing enumeration with powerview we need to import it in Powershell. 

So lets load powershell in meterpreter first. 

Command: `load powershell` <br>
Command2: `powershell_import /powerview_location_on_your_local_disk`

These two commands will import Powerview into the powershell, we can run its commands now.

This command will list out the domain groups in ADDS.

So our main target is to find out the privileged users. This command in PowerView will help to identify those users.

Command: Get-NetGroupMember

<img src="../../net_group_mem.png">

So we now know who is Admin. Its worth to check if any misconfiguration is in their profile. In AD there are lot of options for security so if those options are not set correctly then the attacker can get big amount of data.

This command of PowerView will help to find the information leakeges.

Command: Get-NetUser username

Image


So what exactly misconfiguration is that they often allow to see the email, Phone number etc which can be helpful for Social Engineering. Right?

So we got the admin information leakeges.




