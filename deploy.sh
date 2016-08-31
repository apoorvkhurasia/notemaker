#!/bin/bash
set -o nounset
set -e

#Defaults
unset pass_protect_admin
unset use_ssl_for_admin
unset base_url
unset web_dest
unset note_base_dir

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

if [[ $pass_protect_admin = true ]]; then
	read -p "Enter user name for a user who can access the admin functionalities. Enter blank to skip. " admin_user_name
	if [[ ! -z "${admin_user_name// }" ]]; then
		htpasswd -c src/server/admin/.htpasswd $admin_user_name
		chmod 0644 src/server/admin/.htpasswd
		mv src/server/admin/.htaccess_sample src/server/admin/.htaccess
		chmod 0644 src/server/admin/.htaccess
	fi
fi

normal_base_url="http://"$base_url
admin_base_url="http://"$base_url
if [[ $pass_protect_admin = true && $use_ssl_for_admin = true ]]; then
    admin_base_url="https://"$base_url
fi

echo "Using normal: "$normal_base_url
echo "Using admin: "$admin_base_url

find src -type f -print0 | xargs -0 perl -i -pe'
   BEGIN { $user_input = shift(@ARGV); }
   s/__ADMIN_BASE_URL__/$user_input/g
' $admin_base_url

find src -type f -print0 | xargs -0 perl -i -pe'
   BEGIN { $user_input = shift(@ARGV); }
   s/__NORMAL_BASE_URL__/$user_input/g
' $normal_base_url

find src -type f -print0 | xargs -0 perl -i -pe'
   BEGIN { $user_input = shift(@ARGV); }
   s/__WEB_BASE_DIR__/$user_input/g
' $web_dest

find src -type f -print0 | xargs -0 perl -i -pe'
   BEGIN { $user_input = shift(@ARGV); }
   s/__CONTENT_BASE_DIR__/$user_input/g
' $note_base_dir

mv src $web_dest
