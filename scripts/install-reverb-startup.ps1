# Tanpa admin: pasang Reverb di Startup folder (auto-start saat logon Windows).
#   powershell -ExecutionPolicy Bypass -File scripts\install-reverb-startup.ps1

$project = "C:\Users\Admin\Herd\sigercep"
$startup = [Environment]::GetFolderPath('Startup')
$bat = Join-Path $project "scripts\reverb.bat"
$destBat = Join-Path $startup "sigercep-reverb.bat"
$destVbs = Join-Path $startup "sigercep-reverb.vbs"

Copy-Item $bat $destBat -Force

$vbs = @"
Set WshShell = CreateObject("WScript.Shell")
WshShell.Run """$destBat"", 0, False
"@
Set-Content -Path $destVbs -Value $vbs -Encoding ASCII

Write-Host "Reverb terpasang di Startup folder. Akan auto-start tiap logon Windows (berjalan tersembunyi)."
Write-Host "Hapus dengan: Remove-Item (Join-Path ([Environment]::GetFolderPath('Startup')) 'sigercep-reverb*')"