<?php
namespace Deployer;

require 'recipe/symfony.php';

// Project name
set('application', 'symfony');

// Project repository
set('repository', 'git@github.com:praswicaksono/symfony-demo.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);

// Shared files/dirs between deploys 
add('shared_files', []);
add('shared_dirs', ['vendor', 'var/log']);

// Writable dirs by web server 
add('writable_dirs', []);
set('allow_anonymous_stats', false);

// set symfony dir
set('bin_dir', 'bin');
set('var_dir', 'var');

// set http user
set('http_user', 'root');

// Hosts

host('prod')
    ->hostname(getenv('PRODUCTION_SERVER_IP'))
    ->user('root')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->set('deploy_path', '/var/www/{{application}}')
    ->addSshOption('StrictHostKeyChecking', 'no')
    ->identityFile('~/.ssh/id_rsa');


// Main Task
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:clear_paths',
    'deploy:create_cache_dir',
    'deploy:shared',
    'deploy:assets',
    'deploy:vendors',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:unlock',
    'deploy:symlink',
    'cleanup',
])->desc('Application deployed');

// Tasks

task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

