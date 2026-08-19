# Jalankan PowerShell sebagai Administrator, lalu:
#   powershell -ExecutionPolicy Bypass -File scripts\install-reverb-task.ps1

$project = "C:\Users\Admin\Herd\sigercep"
$bat = Join-Path $project "scripts\reverb.bat"

$action = New-ScheduledTaskAction -Execute "C:\Windows\System32\cmd.exe" -Argument "/c `"$bat`""
$trigger = New-ScheduledTaskTrigger -AtStartup
$principal = New-ScheduledTaskPrincipal -UserId "$env:USERDOMAIN\$env:USERNAME" -LogonType S4U -RunLevel Highest
$settings = New-ScheduledTaskSettingsSet `
    -RestartCount 999 `
    -RestartInterval (New-TimeSpan -Minutes 1) `
    -ExecutionTimeLimit (New-TimeSpan -Days 3650) `
    -AllowStartIfOnBatteries -DontStopIfGoingOnBatteries

Register-ScheduledTask -TaskName "SIGERCEP-Reverb" -Action $action -Trigger $trigger -Principal $principal -Settings $settings -Force
Start-ScheduledTask -TaskName "SIGERCEP-Reverb"
Write-Host "Task 'SIGERCEP-Reverb' terdaftar & dijalankan (auto-start saat Windows boot, restart otomatis tiap 1 menit bila mati)."
Write-Host "Cek status: Get-ScheduledTask -TaskName SIGERCEP-Reverb | Get-ScheduledTaskInfo"