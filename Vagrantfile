# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANT_API_VERSION = 2

Vagrant.configure(VAGRANT_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.network :private_network, type: "dhcp"
  
  config.vm.hostname = 'test.dev'

  config.vm.provider :virtualbox do |v|
    v.customize ["modifyvm", :id, "--memory", 1024]
    v.customize ["modifyvm", :id, "--cpus", 2]
  end

  config.vm.synced_folder "./", "/var/www/html", id: "vagrant-root",nfs:true

  config.vm.provision :shell do |sh|
    sh.path = "vagrant/provision.sh"
  end
  
end
