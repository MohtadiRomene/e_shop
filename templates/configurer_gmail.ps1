# Script PowerShell pour configurer Gmail SMTP

Write-Host "=== Configuration Gmail SMTP pour l'envoi d'emails réels ===" -ForegroundColor Cyan
Write-Host ""

Write-Host "Pour utiliser Gmail, vous devez :" -ForegroundColor Yellow
Write-Host "1. Activer la validation en 2 étapes sur votre compte Google" -ForegroundColor White
Write-Host "2. Générer un mot de passe d'application" -ForegroundColor White
Write-Host ""

$email = Read-Host "Entrez votre adresse Gmail (ex: votre-email@gmail.com)"
$appPassword = Read-Host "Entrez votre mot de passe d'application (16 caractères)" -AsSecureString
$appPasswordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($appPassword))

if ([string]::IsNullOrWhiteSpace($email) -or [string]::IsNullOrWhiteSpace($appPasswordPlain)) {
    Write-Host "Erreur : Email et mot de passe sont requis" -ForegroundColor Red
    exit 1
}

$envFile = ".env"
if (-not (Test-Path $envFile)) {
    Write-Host "Erreur : Le fichier .env n'existe pas !" -ForegroundColor Red
    exit 1
}

# Construire le MAILER_DSN
$mailerDsn = "smtp://${email}:${appPasswordPlain}@smtp.gmail.com:587"

# Lire le contenu du fichier .env
$content = Get-Content $envFile -Raw

# Remplacer ou ajouter MAILER_DSN
if ($content -match "MAILER_DSN=") {
    $content = $content -replace "MAILER_DSN=.*", "MAILER_DSN=$mailerDsn"
    Write-Host "MAILER_DSN mis à jour" -ForegroundColor Green
} else {
    $content += "`n###> symfony/mailer ###`n"
    $content += "MAILER_DSN=$mailerDsn`n"
    $content += "###< symfony/mailer ###`n"
    Write-Host "MAILER_DSN ajouté" -ForegroundColor Green
}

# Écrire le contenu modifié
Set-Content -Path $envFile -Value $content -NoNewline

Write-Host ""
Write-Host "✓ Configuration terminée !" -ForegroundColor Green
Write-Host ""
Write-Host "Les emails seront maintenant envoyés à votre vraie boîte mail." -ForegroundColor Cyan
Write-Host ""
Write-Host "Pour tester, exécutez :" -ForegroundColor Yellow
Write-Host "  php bin/console cache:clear" -ForegroundColor White
Write-Host "  php bin/console app:test-reset-password --email=$email" -ForegroundColor White
Write-Host ""
