Hi all,

This is a walkthrough of a vulnhub machine.

Index
=====

1. Arp-scanning
2. Nmap scanning
3. Exploring port 80
4. Finding the CMS 
5. Searchsploit.
6. Exploiting CMS Metasploit.
7. Privilege Escalation through SUID bit.

**Let's start this**

Strating from arp-scanning for find the IP addresses on the network.
Command:- `arp-scan -l`<br>

After finding the IP address, I started NMAP (a Powerfull Network Mapping Tool).<br>

	 `Command:- nmap -sC -sV <ip>`
	 `-sC: Run Nmap Common Scripts`
	 `-sV: For determining the service Versions.`
	
<br>Here are the results from NMAP,<br>

<img src="../../../nmap-scan.png" width="1200" height="600">

<br>Starting from 80 port, I saw Drupal was using. Tried to get the exact version of it to see the vulnerabilities if present on that version. Wappaulizer will work for us (if leaks). 

And yes, It says **Drupal 7.**

After searching vulnerable through searchsploit. I come to know that it was Vulnerable.

It was vulnerale to Drupalgeddon Exploit. I checked it if its present in Metasploit.

<img src="../../../drupal.png" width="1200" height="600">

And yes. So I quickly use that exploit, and run that. 

<br><img src="../../../meterpreter.png" width="1200" height="600">

<br>Now, we got a shell, after seeing the user id by `getuid` it was a normal user.

How to Escalate my Privileges?
=============================

<br>So, my next target was to escalate my privileges to root. I checked if the kernel is vulnerable by `uname -a` but it was not.
<br>So I move to the second way, I checked to what programs are have SETUID set. 
<br>Setuid is a special file permission in unix/Linux, which permits the user to run that program with higher privileges.<br>
	
	`Command:- find / -perm -u=s 2>/dev/null`
	`-perm is for permission`
	`-u=s means it definees if the file owner have setuid big set.`
	`2>/dev/null will throwout any error.`
	
<br>So This outputs all the programs which uses SETUID permission as you can see below.<br>

<img src="../../../privilege.png" width="1200" height="600">

<br>There are many programs which have SETUID permissions. Now How do we find our program which makes us privileged?
<br>Basically you need to find those programs which can allow you to escape to the shell OR in other words they have interactive mode. 

<br>So in find command -i allow us to escape to the shell.<br>
	Command: find /home -exec /bin/sh -i \;

<br>Now this find command will exec /bin/sh shell in root mode, hence we have the root privileges into the shell.

<br><img src=../../../"root.png" width="1200" height="600">

<br>And yes!. We got a root shell :)	

<br>This machine was so good, gives new things to learn. <br>

Thanks for your time for reading this. 

Have a good day. :)
