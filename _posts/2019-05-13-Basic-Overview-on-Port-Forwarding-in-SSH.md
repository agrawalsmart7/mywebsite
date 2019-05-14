<h1><b>SSH Port Forwarding</b></h1>

This blog post is just to give you a basic overview on Port Forwardings in SSH.

So, Port forwarding are three types in SSH.

1. Local Port Forwarding
2. Remote Port Forwarding
3. Dynamic Port Forwarding

<h2><b>Local Port Forwarding</b></h2>

<b>Syntax:-</b> `ssh -L sourceip:sourceport:remotelocalhost:remoteport user@example.com`

So let's understand this.

Sourceip can be your ip or can be your localhost address.

For ex in case of ip:- `ssh -L 192.168.0.9:990:localhost:80 user@example.com`<br>
In case of locahost:- `ssh -L localhost:990:localhost:80 user@example.com` or `ssh -L 990:localhost:80 user@example.com`

<h4>So How this works?</h4>

First example.com will identify you, authenticate you as 'user'. Now you are providing that -L that means you are telling locally (client host machine) to forwards the traffic of port you specified (990) to
the remote host that you specified(localhost) on that port(80).

So you go to 192.168.0.9:990 on your local machine's browser, then all the traffic will automatically transfer to the remote local host. Now you will see the data of remote local host web application which was not for public.

This tricks helps when you found a local website, or database which was not for publically access, through this you can access them locally.

You can also do multiple port forwarding with this command.

`ssh -L 9990:localhost:80 9991:localhost:80 user@example.com`

This allows you to do multiple port forwarding. Move on to next.

<h2><b>Remote Port Forwarding</b></h2>

<b>Syntax:-</b> `ssh -R remotehost:remoteport:sourceip:sourceport user@example.com`

So let's take a example of this.

`ssh -R localhost:80:192.168.0.9:990 user@example.com`

Now what will happen here is when ever if any one access the localhost on the remote server then they all the traffic will forwards to our server running on the host 192.168.0.9 at port 990.
Now, we can force the user to type the credentials of their accounts, which user can easily be in the trap. Right?

<h2><b>Dynamic Port Forwarding</b></h2>

<b>Syntax:-</b> `ssh -D 990 user@example.com`

The Original command will be.

`ssh -D 9990 user@example.com`

So this one is very interesting, what it do, when you run that command it **Creates your servers as a Socks Proxy**. Now any data is passed to internet will be transfered through your socks proxy or in this case your server.

So, let's say you go to "https://google.com" now the request will be gone through your proxy. Remember you have to edit your socks proxy config in your browser. Right?. 

<h4>So when it is helpfull?</h4>

For ex<br>
Your-IP --> `192.168.0.9`<br>
Server-ip--> `192.168.0.10` and `192.168.217.103`<br>
Workstation --> `192.168.217.105` 

Here you can see that there is your server which have two network cards. Out of one was same as the workstation. But it was non-routable from our PC but **not** for the server.

So you provide this:- `ssh -D 990 user@example.com`

You set your SSH server as Proxy. Now when you load the page of workstation from your local computer then you will see that you can access it easily. For checking, you can try to scan the workstation with Scanner tools like Nmap from your local computer. Isn't it great? 

For say, you have creds which can work on the workstation too, then you can ssh'ed to the workstation. So this is another way of lateral movement in the network.

So, that's it. As I said, my idea was to give you a basic overview of the ssh port forwardings in simple way. Hope it helps you in anyway.

Thanks for your time.

Have a good hacking day.
