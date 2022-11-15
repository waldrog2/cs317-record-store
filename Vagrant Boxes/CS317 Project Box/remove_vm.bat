@ECHO OFF
vagrant destroy -f
del /f /s /q "C:\Users\gnations\VirtualBox VMs\CS317 Development Server 2\" 1>nul
rmdir /s /q "C:\Users\gnations\VirtualBox VMs\CS317 Development Server 2\"