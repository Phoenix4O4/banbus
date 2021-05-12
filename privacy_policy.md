# Privacy Policy

Statbus (this application) does not record any data about its users, byond what is necessary in order to facilitate a secure and informative experience. Wherever possible, the application relies on the game server database (the external database) to look up information about players and other associated data. This data is only *displayed* by the application and never stored. Under no circumstances is personally identifiable information (PII) such as IP addresses and ComputerIDs retained by the application. Some functionality requires storing user information on a database used exclusively by the application (the internal database), or in ephemeral session files. This functionality will be listed and explained below.

## Session Data
When you authenticate to the application via the TGStation forums, some information is transmitted back to the application and securely stored as part of your PHP session. The data received from TGStation consists of: 
* Your PHPBB username used on the forums
* Your Byond key, which is your “full”, un-interpreted username from Byond
* Your Byond ckey, a simplified version of the Byond key with everything except letters, numbers, and the `@` character stripped out
* If you’ve previously authenticated to Github with your TGStation forum account, your GitHub username is also provided to the application. At this moment, nothing is done with your GitHub username and this data is discarded.

Session data is periodically purged by the webserver.

## Source Code
The application source code is publicly available on [GitHub](https://github.com/nfreader/banbus). Please feel free to contact me with any inconsistencies or questions. The greatest effort has been made to make this document as accurate as possible, but there may be omissions or incorrect information.

**1.1 Rev. 12-05-2021**