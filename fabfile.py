from fabric.api import env, local, cd, sudo

env.hosts = ['ip-address.ws']

def deploy():
    local('git push origin master')
    with cd('/var/www/ip-address.ws'):
        sudo('git pull origin master')
        sudo('service nginx restart')