set :application, "Janata Samachar Live"
set :domain, "139.59.62.98"
set :deploy_to, "/var/www/html/janatasamachar"
set :app_path, "app"

set :user, "deploy"

set :group, "www-data"
set :use_sudo, false
ssh_options[:forward_agent] = true
default_run_options[:pty] = true


set :repository, "git@gitlab.com:bidhee/janata.git"
set :scm, :git
set :deploy_via,  :remote_cache
set :branch, "master"
set :interactive_mode,  false

set :composer_bin, "composer"
set :use_composer, true
set :update_vendors, true
set :copy_vendors, false

set :shared_files, ["app/config/parameters.yml"]
set :shared_children, [app_path + "/logs", web_path + "/uploads", app_path + "/sessions", web_path + "/media"]
set :writable_dirs, ["app/cache", "app/logs", "app/sessions"]
set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true

set  :keep_releases, 3

set :model_manager, "doctrine"

role :web, domain
role :app, domain, :primary => true

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL

# Run migrations before warming the cache
before "symfony:cache:warmup", "symfony:doctrine:schema:update"

after "deploy:setup", "upload_parameters"
after "deploy:update", "deploy:cleanup"

namespace :customtasks do
    task :cleanup, :except => {:no_release => true} do
      count = fetch(:keep_releases, 3).to_i
      run "ls -1dt #{releases_path}/* | tail -n +#{count + 1} | sudo xargs rm -rf"
    end
end

task :upload_parameters do
  origin_file = "app/config/parameters_live.yml"
  destination_file = shared_path + "/app/config/parameters.yml"

  try_sudo "mkdir -p #{File.dirname(destination_file)}"
  top.upload(origin_file, destination_file)
end
