![HELPeR](http://www.40digits.com/wp-content/uploads/2014/08/helperpress.png)

HelperPress
======================
A tool for automating much of the WordPress development workflow.

- **Environment Management** - Manages syncing data & wp-content between environments
- **Asset Optimization** - Comes loaded with Grunt tasks that optimize CSS, Javascript, and Images.
- **Dependency Management** - Automates working with WP-CLI, Bower, and Composer to minimize extraneous committed code and simplify update process
- **Automatically Configure Apache & Hosts*** Automagically configures Apache virtualhosts and hosts for local development
- **Deployment** - Build & Deploy via rsync or WPE Git Push
- **Automatic Versioning** - pushes and merges become versioned automatically.

## Installation

1. Run `npm install` in the repo directory.
2. Run `grunt setup_dev`

## Requirements
*This has only been tested on OS X. It will not work as-is on Windows.*

1. Node.js & npm (via [Homebrew](http://brew.sh/#install): `brew install node`)
2. [Composer](https://getcomposer.org/doc/00-intro.md#globally-on-osx-via-homebrew-)

## Grunt Tasks
### `grunt setup`
Called automatically after `npm install`, this is the first task that should be run to initalize every repository.

- Installs Composer components
- Installs Bower components
- Installs and configures WordPress 
- Migrates site data down if helperpress.json already exists in the repo
- Creates helperpress.local.json

### `grunt watch`

- Compiles SASS
- Starts LiveReload

### `grunt migrate_db:direction:environment`
Pulls down the database and runs a search and replace on it, overwriting the local database. Change `environment` to the ID of the environment from which you'd like to pull.

### `grunt migrate_uploads:direction:environment`
Pulls or pushes the wp-content/uploads directory.  If the setting is "rewrite", it will add a rewrite rule to the .htaccess file to load all uploads from the `db_master` environmnet. Change `environment` to the ID of the environment from which you'd like to pull.

## Configuration
Before Grunt is initialized, four configuration files are loaded and combined into one giant config JSON object. The files in order of precedence (i.e. early files' settings will override latter ones):

1. helperpress.local.json
2. helperpress.json
3. ~/.helperpress
4. package.json

### Guide for Settings Definition Placement

*Where should the setting apply?*          | This Repo              | All Repos
-------------------------------------------|------------------------|-------------------
  **This Environment**                     | helperpress.local.json | ~/.helperpress
  **All Environments**                     | helperpress.json       | package.json*

######* *package.json "config" object should only be edited in the boilerplate.*

### Site Configuration Properties
See the example commented JSON object below for information about each property. These are set in the "config" object in package.json and as root properties in all other config files. *Note that actual JSON files may not have comments in them.*


#### Environments
These settings will come from **helperpress.json**, **helperpress.local.json**, &  **~/.helperpress**.

General rules:

- If the information is unique to this repository, it'll go in helperpress.json or helperpress.local.json
- Never commit usernames and passwords (i.e. keep them out of package.json and helperpress.json)
- If a setting gets reused across multiple sites, such as your local environment setup or development environment creds, it'll go in ~/.helperpress

```js
{
	"environments": {

		// environment ID
		"development": {

			// human-readable title
			"title": "Development (Dev01)",

			// Server SSH (and SFTP) info
			"ssh": {
				"host": "dev01.40digits.net",
				"port": 22,							// (optional) defaults to 22
				"user": "kewld00d69",				// (optional) defaults to current shell user
				"pass": "password1234",				// (optional) Note: won't work for SSH
				"keyfile": "~/.ssh/customkey.rsa"	// (optional) uses typical default when omitted
				"passphrase": "myKeysPassphrase"	// (optional) specify key's passphrase if needed
			},

			// Database settings (using SSH)
			// DO NOT COMMIT CREDENTIALS - use ~/.helperpress or helperpress.local.json
			"db": {

				"host": "127.0.0.1",
				"pass": "password",
				"user": "db-user"
				"database": "my-wp"

			},

			// path to WP install directory
			"wp_path": "/var/www/vhosts/my-wp",

			// base URL of site without protocol
			"home_url": "my-wp.dev01.40digits.net",

			/*
			how HP should deploy source
			"rsync": rsync over SSH
			"wpe": WPEngine's Git Deploy. (builds, grabs remote's .git folder, rsyncs files, commits, and pushes)
			"none": don't allow deploy to run on this environment (e.g. it's handled elsewhere)
			*/
			"deploy_method": "rsync"

			// Git remote for when using 'wpe' as deploy_method
			"remote": "git@git.wpengine.com:production/sitename.git"

			/*
			how HP should migrate wp-uploads
			"rsync": rsync over SSH
			"sftp": connect via SFTP and upload/download
			"none": don't allow automatic uploads migration on this environment (e.g. it's handled elsewhere)
			*/
			"migrate_uploads_method": "rsync"
		}

	},

	// ID of environment w/ master database. Used for syncing database and uploads
    "db_master": "development",

    /*
    method for syncing uploads.
    "rewrite" uses Apache rewrites via .htaccess (default)
    "copy" copies files to local machine
    */
    "uploads_sync": "rsync"
}
```

#### WordPress
```js
{
    "wp": {

    	/*
	    	Theme Settings

	    	Leave a property blank or undefined to infer settings from package.json
	    	(see http://codex.wordpress.org/Theme_Development#Theme_Stylesheet)
    	*/
		"theme" {

			"slug": "wp-theme", 		// Text Domain
			"name": "Client's Site",	// Theme Name
			"decription": "" 			// Description
			"author": "", 				// Author
			"author_uri": "", 			// Author URI
			"version": "" 				// Version
			"uri": "", 					// Theme URI
			"tags": "", 				// Tags
			"license": "",              // License
			"license_uri": "",          // License URI

		},

		// Plugins - A plugin slug, the path to a local zip file, or URL to a remote zip file.
		"plugins": [
			"akismet",
			"advanced-custom-fields",
	    ]

    }
}
```

#### Apache Config
Everyone's Apache setup is unique, so you can describe the local environment's setup here.

This onel  definitely go in the ***~/.helperpress** file.
```js
{
	"apache": {
		"scheme": "vhost",							// vhost or subdir.
		"sites_dir": "/User/me/Sites"				// your localhost folder. subdir only.
		"vhost_dir": "/etc/apache2/extra/vhosts",	// where your vhost confs go. MUST BE WRITEABLE
		"logs_dir": "/my-sites/logs",				// vhost only
		"a2ensite": false,							// do we need to a2ensite? vhost only
		"as_service": false,						// is Apache running as a service? vhost only
		"url_scheme": "*.local"						// how you do local URLs. use "*" for slug placement.
	}
}
```
#### Browser Support
Currently just used for autoprefixer in CSS files
```js
{

    // Which browsers should the code be expected to support?
    // autoprefixer syntax
    "browser_support": ["last 2 version", "ie 9"] 

}
```


### package.json
This is a standard file required by NPM ([see Grunt documentation](http://gruntjs.com/getting-started#package.json)). This should not need to be changed unless the Gruntfile itself is edited.