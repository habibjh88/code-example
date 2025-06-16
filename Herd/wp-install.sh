#!/bin/bash

# Function to prompt for input and use default if none provided
prompt() {
    read -p "$1 [$2]: " input
    echo "${input:-$2}"
}

# Function to prompt for password input (hidden)
prompt_password() {
    read -s -p "$1: " input
    echo "$input"
}

# Dynamically get the home directory
HOME_DIR=$HOME
DEFAULT_ROOT_DIR="$HOME_DIR/DevSites"

# Prompt for user input
ROOT_DIR=$(prompt "Enter the the root directory for new WP installation: Default" "$DEFAULT_ROOT_DIR")
PROJECT_NAME=$(prompt "Enter the project directory name: Default" "wordpress")
DB_NAME=$(prompt "Enter the database name: Default" "wordpress")
DB_USER=$(prompt "Enter the database user: Default" "root")
DB_PASS=$(prompt_password "Enter the database password")
printf "\n"
SITE_URL=$(prompt "Enter the site URL: Default" "wordpress.test")
SITE_TITLE=$(prompt "Enter the site title: Default" "WordPress")
ADMIN_USER=$(prompt "Enter the admin username: Default" "admin")
ADMIN_PASS=$(prompt_password "Enter the admin password")
printf "\n"
ADMIN_EMAIL=$(prompt "Enter the admin email: Default" "admin@example.com")

read -p "Do you want to install a theme? (y/n): " INSTALL_THEME
if [[ "$INSTALL_THEME" == "y" ]]; then
    THEME=$(prompt "Enter the theme slug to install: Default" "astra")
fi

read -p "Do you want to install plugins? (y/n): " INSTALL_PLUGINS
if [[ "$INSTALL_PLUGINS" == "y" ]]; then
    PLUGINS=$(prompt "Enter the plugins to install (space-separated plugin slugs): Default" "elementor query-monitor")
fi

# Summary of inputs
echo -e "\n--- Installation Summary ---"
echo "Root Directory: $ROOT_DIR"
echo "Project Name: $PROJECT_NAME"
echo "Database Name: $DB_NAME"
echo "Database User: $DB_USER"
echo "Database Password: [Your given password]"
echo "Site URL: $SITE_URL"
echo "Site Title: $SITE_TITLE"
echo "Admin Username: $ADMIN_USER"
echo "Admin Password: [Your given password]"
echo "Admin Email: $ADMIN_EMAIL"

if [[ "$INSTALL_THEME" == "y" ]]; then
    echo "Theme to Install: $THEME"
fi

if [[ "$INSTALL_PLUGINS" == "y" ]]; then
    echo "Plugins to Install: $PLUGINS"
fi
echo -e "--------------------------\n"

read -p "Do you want to proceed with these settings? (y/n): " PROCEED
if [[ "$PROCEED" != "y" ]]; then
    echo "Installation aborted."
    exit 1
fi

# Navigate to the root sites folder
cd $ROOT_DIR || { echo "Failed to change directory to $ROOT_DIR"; exit 1; }

# Create the project directory and navigate to it
mkdir $PROJECT_NAME
cd $PROJECT_NAME || { echo "Failed to change directory to $PROJECT_NAME"; exit 1; }
echo "Project directory set to: $ROOT_DIR/$PROJECT_NAME"

# Download WordPress core
echo "Downloading WordPress core..."
wp core download

# Log in to MySQL and create the database
echo "Creating database: $DB_NAME..."
mysql -u $DB_USER -p$DB_PASS -e "CREATE DATABASE \`$DB_NAME\`;"

# Create WordPress configuration file
echo "Creating WordPress configuration file..."
wp config create --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASS

# Enable Debugging
echo "Enabling debugging.."
wp config set WP_DEBUG true --raw
wp config set WP_DEBUG_LOG true --raw
wp config set WP_DEBUG_DISPLAY true --raw

# Install WordPress with specified parameters
echo "Installing WordPress..."
wp core install --url=$SITE_URL --title="$SITE_TITLE" --admin_user=$ADMIN_USER --admin_password=$ADMIN_PASS --admin_email=$ADMIN_EMAIL

# Change permalink structure
echo "Changing permalink structure..."
wp rewrite structure '/%postname%/' --hard

# Deactivate & Uninstall all plugins
echo "Removing default plugins..."
wp plugin list --field=name | xargs wp plugin deactivate
wp plugin list --field=name | xargs wp plugin uninstall

# Install Developer Friendly theme (if selected)
if [[ "$INSTALL_THEME" == "y" ]]; then
    echo "Installing and activating the theme: $THEME..."
    wp theme install $THEME --activate
fi

# Install Developer Friendly plugins (if selected)
if [[ "$INSTALL_PLUGINS" == "y" ]]; then
    echo "Installing and activating the required plugins..."
    wp plugin install $PLUGINS --activate
fi

# Open the WordPress admin URL
echo "All Done. Have Fun!"
open http://$SITE_URL/wp-admin/
