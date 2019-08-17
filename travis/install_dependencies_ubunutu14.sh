# Borrowed from Peachpie

# Install .NET Core # https://www.microsoft.com/net/download/linux-package-manager/ubuntu14-04/sdk-current

# Register Microsoft key and feed
wget -q packages-microsoft-prod.deb https://packages.microsoft.com/config/ubuntu/14.04/packages-microsoft-prod.deb
sudo dpkg -i packages-microsoft-prod.deb

# Install .NET Core SDK
sudo apt-get install apt-transport-https
sudo apt-get update
sudo apt-get install dotnet-sdk-2.1 -y