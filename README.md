#Wiki Loves Jurytool
This Jurytool was used und developed in Austria for photo competitions like Wiki Loves Monuments, Wiki Loves Public Art and Wiki Loves Earth.

It is used in a 3-step procedure:

1. prejury: Everyone who is in the prejury gets a login and can give 1-5 Stars for every picture in the database.
2. jury-select: The jury members get a login and can select from ~500 photos 12 (or whatever you number you want to) for the jury meeting.
3. jury-meeting: The members can give 0-5 points (or whatever you decide) which are written in a text field. (The + sign can be used - so don't worry about math.) The pictures are rearranged and this process can be repeated as often as necessary.


##Features
These features were borne in mind for the wiki community in Austria after many (offline and online) discussions. They may change in later versions as to fit the needs of the prejury and jury-members. However feel free to use this version and adapt it for your needs.

* Update of the database in the background during the whole process (with a cronjob).
* Exclusion of the pictures of the prejury members (same username) for themself.
* Possibility to click through the pictures without voting.
* Rearrangement of the order of the pictures in the prejury phase after every vote. So pictures with less votes are shown first.
* Possibility to 'ignore' pictures if a prejury member doesn't want to vote for them.
* Possibility to see the pictures in the categories 'ignored', '1 star',... '5 stars' and even change the vote.
* Countdown for the prejury so voting is only possible for a limited time.
* Show/hide information about the progress of the prejury.
* Change the size of the images in prejury and jury-select.
* Hide/show the menu in prejury and jury-select.
* user level `user` (can vote), `manager` (+ can manage jury meeting), `admin` (+ logfiles, user creation, move  pictures for the jury-select, export results)

removed

* ~~More random order of pictures~~


##Requirements
This Version was written and last tested on one specific server with:

* php 5.5.24
* MySQL 5.6.24

There is a good chance that this will also work with older versions... However, make sure that *Magic
Quotes* are off.


##Installation

1. download and unpack it on your server
2. open url in a web browser and fill out install form
3. **DELETE** *install.php* you can edit *config/config.php* if you want to change something
4. fix user rights - adjust **PATH/ON/SERVER** (install directory), **www-data** (www user) and **www-data** (www group) if you want to use this little script
 
  ```
  #!/bin/bash

  WLJ_path='/PATH/ON/SERVER'
  htuser='www-data'
  htgroup='www-data'

  find ${WLJ_path}/ -type f -print0 | xargs -0 chmod 0640
  find ${WLJ_path}/ -type d -print0 | xargs -0 chmod 0750
  
  chown -R root:${htuser} ${WLJ_path}/
  chown -R ${htuser}:${htgroup} ${WLJ_path}/log/

  chown root:${htuser} ${WLJ_path}/log/.htaccess
  chown root:${htuser} ${WLJ_path}/config/.htaccess

  chmod 0644 ${WLJ_path}/log/.htaccess
  chmod 0644 ${WLJ_path}/config/.htaccess
  ```
  
5. create a cronjob (this has to be done manually on your system). On *nix see `man crontab` how to call `php /PATH/ON/SERVER/cron.php` regularly. Most probably you can add cronjobs with `crontab -e` and have to add a line like `0 4 * * * cd /PATH/ON/SERVER/; php cron.php`. First populating the database might take some time. Please be patient ;-)


##Administration

* Watch the logfiles in *log* directory or `URL/admin/index.php`
* Create the prejury-users (`URL/admin/index.php`) and send a wikimail to the prejury with `URL/prejury/index.php`
* After the prejury phase select the pictures for the jury-select. `URL/admin/index.php`   
* Create the jury-users (`URL/admin/index.php`) and send a (wiki)mail to the jury `URL/jury/index.php`
* Use `URL/admin/meeting.php` as admin for the jury meeting.
* Export the winners after jury-meeting. In `URL/admin/index.php` you get the order of the last saved round.


##Bugs/todo
Nice to have stuff...

- improve administration stuff (password reset, delete users)
- improve installer (install.php)
- translations (help pages)
- in cronjob distinguish deleted and moved files (however seems to be impossible if forwarding on Commons is deleted...)
- user settings (changing the password)
- test more php & mysql versions
- script for cleaning up after contest (however deleting files an db needs no script)

Bugs

- feel free to contact me <http://pixelhaufen.info>


##License
Copyright (c) 2011 - 2015 [Ruben Demus](http://pixelhaufen.at) on behalf of [Wikimedia Austria - WMAT](https://wikimedia.at).

Wiki Loves Jurytool is licensed under the **GNU Affero General Public License** version 3 or later. See the COPYING-AGPL file.

Because of the limitations of the usage of Wikimedia Foundation, Inc. trademarks (Wikipedia, Wikimedia, Wikimedia Commons logos) <https://wikimediafoundation.org/wiki/Trademark_policy> they are not used in this tool.
