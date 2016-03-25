# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANT_API_VERSION = 2

Vagrant.configure(VAGRANT_API_VERSION) do |config|
  config.vm.box = "ubuntu/trusty64"
  config.vm.network :private_network, type: "dhcp"

  if !Vagrant.has_plugin?('vagrant-hostmanager')
    puts 'Required plugin vagrant-hostmanager is not installed! Install with "vagrant plugin install vagrant-hostmanager"'
  else
    config.hostmanager.enabled = true
    config.hostmanager.manage_host = true
    config.hostmanager.ip_resolver = proc do |vm, resolving_vm|
      if hostname = (vm.ssh_info && vm.ssh_info[:host])
        `vagrant ssh -c "hostname -I"`.split()[1]
      end
    end  
  end
  
  config.vm.hostname = 'sawazon.com'

  config.vm.provider :virtualbox do |v|
    v.customize ["modifyvm", :id, "--memory", 1024]
    v.customize ["modifyvm", :id, "--cpus", 2]
  end

  config.vm.synced_folder "./", "/var/www/html", id: "vagrant-root",nfs:true

  config.vm.provision :shell do |sh|
    sh.path = "vagrant/provision.sh"
  end
  
end
