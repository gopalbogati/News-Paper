set :stages,        %w(live dev new)
set :default_stage, "dev"
set :stage_dir,     "app/config/deploy"
require 'capistrano/ext/multistage'
