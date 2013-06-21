scuttle2owncloud
======
Converts [scuttle](http://sourceforge.net/projects/scuttle/ "scuttle on sourceforge") entries to owncloud bookmarking app.
Scuttle is not really activly maintained, plus I thought it might be nice to have everything at one place.

## Howto
Fill in the authentication details in *scuttle2owncloud_config.php* and upload the two files.
In case of semantic scuttle change the `$scuttleTagsTable`-variable.
Upload the two files and run *scuttle2owncloud.php*.

## Known bugs/issues
* tags don't work(it's in the code though, i have no idea why owncloud doesn't show them)
* only MySQL (sqlite planned)

## Contact
* http://repat.de
* email: repat[at]repat[dot]de
* XMPP: repat@jabber.ccc.de
* Twitter: [repat123](https://twitter.com/repat123 "repat123 on twitter")

[![Flattr this git repo](http://api.flattr.com/button/flattr-badge-large.png)](https://flattr.com/submit/auto?user_id=repat&url=https://github.com/repat/scuttle2owncloud&title=scuttle2owncloud&language=&tags=github&category=software) 

### Copyright
Pretty much do-whatever-you-want
* MIT-License, see [opensource.org](http://opensource.org/licenses/mit-license.php "opensource.org MIT License") for details
