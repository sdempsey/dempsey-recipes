# Skeleton

A boilerplate WordPress installation for easy version control. Still under development…

## Layout
* Follows the practice of separating the application from custom code by adding WordPress as a submodule in `/wp/`
* Content directory moved outside of WordPress root
* Configuration files moved outside of WordPress root and ready for both local and remote environments
* Uploads excluded from version control, so we use htaccess + mod_rewrite to fetch missing images from the remote server
* Use [SyncDB](https://github.com/jplew/SyncDB) to handle the database