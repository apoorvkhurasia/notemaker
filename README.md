# notemaker
The note making application that allows you to take notes with mathematics in it easily.

This application is web-based and needs to be deployed on a server that supports PHP. It is highly recommended to secure the admin functionality using .htaccess. The deploy script provides that functionality.

To start, get a copy of this repository to your computer. Run the prep_deploy.sh script to prepare a zipped tarball of files that can be sent to your server. Once on the server, untar and unzip the zipped tarball and run the deploy.sh script with a deploy.properties which must contain the properties defined in the two sample deploy.properties files checked-in with this code.

Once again --remember to secure your admin page. If left unsecure, a malicious actor can fill up your disk space (friendlier outcome) or do much more worse things.
