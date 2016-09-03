#!/bin/bash
set -o nounset
set -e

#Defaults
unset pass_protect_admin
unset use_ssl
unset base_url
unset web_dest
unset note_base_dir
unset ht_passwd_file

if [[ -z ${1+x} ]] ; then
	echo "Usage: deploy.sh <path-to-deploy.properties-file>"
	exit 1
fi

while IFS="=" read -r key value; do
    case "$key" in
        '#'*) ;;
        *)
            eval "$key=\"$value\""
    esac
done < "$1"

echo "Proceeding with deployment of" src.
if [[ -d $web_dest ]]; then
	rm -rf $web_dest".bak"
	mv $web_dest $web_dest".bak"
fi

find src -type f -name *DS_Store* -delete
rm -rf src/.git

chmod -R 0755 src

if [[ $pass_protect_admin = true && $use_ssl = true ]]; then
    base_url="https://"$base_url
else
    base_url="http://"$base_url
fi

find src -type f -print0 | xargs -0 perl -i -pe'
   BEGIN { $user_input = shift(@ARGV); }
   s/__BASE_URL__/$user_input/g
' $base_url

find src -type f -print0 | xargs -0 perl -i -pe'
   BEGIN { $user_input = shift(@ARGV); }
   s/__WEB_BASE_DIR__/$user_input/g
' $web_dest

find src -type f -print0 | xargs -0 perl -i -pe'
   BEGIN { $user_input = shift(@ARGV); }
   s/__CONTENT_BASE_DIR__/$user_input/g
' $note_base_dir

mv src $web_dest

if [[ $pass_protect_admin = true && ! -z ${ht_passwd_file+x} ]]; then
	mv $ht_passwd_file $web_dest/server/admin/.htpasswd
	chmod 0644 $web_dest/server/admin/.htpasswd
	mv $web_dest/server/admin/.htaccess_sample $web_dest/server/admin/.htaccess
	chmod 0644 $web_dest/server/admin/.htaccess
fi
