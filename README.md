## Quickstart (From Pre-Configured Site Repository)

### Fill in project settings
Setup project name, hostname and any other settings that might be specific to your site

```
vim ./.ddev/config.yaml
```

### Turn this thing on
```
ddev start
ddev composer install
ddev drush site:install --existing-config --account-name=admin --account-pass=admin -y
```

## Installing DDev and DDev Services (ONE TIME TASKS)

If you already have Docker and Ddev setup (including mkcert), you can skip to "Quickstart of Drupal Site"

1. **Setup Colima/Docker for Desktop**  
    Colima requires a bit more oversight, but is slightly more efficient due to it's focus on the command line interface. If you already have Docker installed, you need not install Colima at this time.

1. **Setup Ddev**  
    ```bash
    brew install ddev/ddev/ddev
    ```
    [More Info](https://ddev.readthedocs.io/en/latest/users/install/ddev-installation/#ddev-installation)

1. **Install mkcert**  
This will require that you enter your password to allow ops with sudo. After you do so, you'll need to use the admin user to authenticate to unlock your keychain(s). Depending on how many keychains you have, you will need to enter your admin user credentials for each keychaing that you have on your Mac.  
    ```zsh
    mkcert -install
    ```

## Quickstart of Drupal Site from Nothing (this will evolve)
  Note that you will be required to use your password to use sudo to edit your /etc/hosts file and add the project url. 

  ```bash
  ddev config --project-type=drupal10 --docroot=web --create-docroot --database=mysql:8.0 --php-version=8.2 --project-tld=psul.localhost --default-container-timeout=600 && \
  ddev start && \
  ddev drush site:install --account-name=admin --account-pass=admin -y
  ```

[More Info](https://ddev.readthedocs.io/en/latest/users/quickstart/#drupal)

## Some Basic Commands

Start A Project:  
`ddev start`

Stop A Project:  
`ddev stop`

Stop A Project (**removing all data**):  
`ddev stop --remove-data --omit-snapshot`  
Don't be afraid to use this command to clean-up misbehaving containers! Do remember that it will empty the database and the web container.

Operate Composer with:  
`ddev composer <composer command>`

Operate Drush with:  
`ddev drush <drush command>`

Checkout Ddev logs with:  
`ddev logs -f`  
[More Logs Info](https://ddev.readthedocs.io/en/latest/users/usage/commands/#logs)

Monitor Mutagen's Actions:  
`ddev mutagen monitor`

## Setting Environment Variables

Non-sensitive Environment Variables:  
`ddev config --web-environment-add variable=value`  
Security sensitive values that you do not want committed (such as keys or tokens) should _NOT_ be added like this. Instead, they should be added to a file at `.ddev/.env` in the format:

```bash
# .ddev/.env
NAME_OF_VARIABLE='some value'
NAME_OF_VARIABLE_INT=25
```

## Notes

This sets up quite a bit of infrastructure. One of the key components that makes this setup different is the use of Mutagen to synchronize the extremely large number of files and directories that Drupal uses. The setup uses a project subdirectory that maintains the non-public files (composer vendored files, etc.) and syncs the files from the container to the host. Initial startup is painful, but the sync works well and quickly after the sync is done.

Sometimes the long tail of the initial sync for mutagen will cause the container start to timeout. Just run `ddev start` again.

## Caveats

* Avoid changing files outside of the container when the containers are not running.  
This will cause Mutagen to invalidate it's index and force it to recreate the entire index for syncing between the container and the host. The only time doing this is a good idea is if you are massively changing the structure of what is syncronized.
