Param(
  [switch]$Rebuild,
  [switch]$ResetDb
)

$ErrorActionPreference = "Stop"

$projectRoot = Split-Path -Parent $PSScriptRoot
Set-Location $projectRoot

if (-not (Test-Path ".env.container")) {
  Copy-Item "env.container.example" ".env.container"
  Write-Host "Created .env.container from env.container.example"
  Write-Host "Edit .env.container and update APP_SECRET / passwords if needed."
}

$compose = @(
  "docker", "compose",
  "--env-file", ".env.container",
  "-f", "compose.container.yaml"
)

if ($ResetDb) {
  & $compose[0] $compose[1..($compose.Length - 1)] down -v
}

$upArgs = @("up", "-d")
if ($Rebuild) { $upArgs += @("--build") }

& $compose[0] $compose[1..($compose.Length - 1)] @upArgs

Write-Host "Done. Open: http://localhost:8080"
