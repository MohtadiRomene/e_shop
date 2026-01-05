# Script interactif pour configurer Gmail SMTP

Write-Host "=== Configuration Gmail SMTP ===" -ForegroundColor Cyan
Write-Host ""

Write-Host "Ce script va vous aider à configurer l'envoi d'emails via Gmail." -ForegroundColor Yellow
Write-Host ""

# Vérifier si la validation en 2 étapes est activée
Write-Host "PREMIÈRE ÉTAPE : Générer un mot de passe d'application Gmail" -ForegroundColor Green
Write-Host ""
Write-Host "1. Ouvrez votre navigateur et allez sur :" -ForegroundColor White
Write-Host "   https://myaccount.google.com/apppasswords" -ForegroundColor Cyan
Write-Host ""
Write-Host "2. Si vous n'avez pas encore activé la validation en 2 étapes :" -ForegroundColor White
Write-Host "   - Allez sur : https://myaccount.google.com/security" -ForegroundColor Cyan
Write-Host "   - Activez 'Validation en deux étapes'" -ForegroundColor White
Write-Host ""
Write-Host "3. Sur la page des mots de passe d'application :" -ForegroundColor White
Write-Host "   - Sélectionnez 'Mail' et 'Autre (nom personnalisé)'" -ForegroundColor White
Write-Host "   - Entrez 'Kaira E-Shop' comme nom" -ForegroundColor White
Write-Host "   - Cliquez sur 'Générer'" -ForegroundColor White
Write-Host "   - COPIEZ le mot de passe (16 caractères)" -ForegroundColor Yellow
Write-Host ""

$continue = Read-Host "Avez-vous généré le mot de passe d'application ? (o/n)"
if ($continue -ne "o" -and $continue -ne "O" -and $continue -ne "oui") {
    Write-Host ""
    Write-Host "Générez d'abord le mot de passe d'application, puis relancez ce script." -ForegroundColor Red
    Write-Host "Lien direct : https://myaccount.google.com/apppasswords" -ForegroundColor Cyan
    exit
}

Write-Host ""
Write-Host "DEUXIÈME ÉTAPE : Configuration dans .env" -ForegroundColor Green
Write-Host ""

$email = Read-Host "Entrez votre adresse Gmail (ex: mohtadiromene00@gmail.com)"
if ([string]::IsNullOrWhiteSpace($email)) {
    Write-Host "Erreur : L'email est requis" -ForegroundColor Red
    exit 1
}

# Vérifier le format email
if ($email -notmatch '^[a-zA-Z0-9._%+-]+@gmail\.com$') {
    Write-Host "Attention : L'email doit être une adresse Gmail (@gmail.com)" -ForegroundColor Yellow
    $continue = Read-Host "Continuer quand même ? (o/n)"
    if ($continue -ne "o" -and $continue -ne "O") {
        exit
    }
}

$appPassword = Read-Host "Entrez le mot de passe d'application (16 caractères, sans espaces)" -AsSecureString
$appPasswordPlain = [Runtime.InteropServices.Marshal]::PtrToStringAuto([Runtime.InteropServices.Marshal]::SecureStringToBSTR($appPassword))

if ([string]::IsNullOrWhiteSpace($appPasswordPlain)) {
    Write-Host "Erreur : Le mot de passe d'application est requis" -ForegroundColor Red
    exit 1
}

# Nettoyer le mot de passe (supprimer les espaces et tirets)
$appPasswordPlain = $appPasswordPlain -replace '\s+', '' -replace '-', ''

if ($appPasswordPlain.Length -ne 16) {
    Write-Host "Attention : Le mot de passe d'application devrait faire 16 caractères" -ForegroundColor Yellow
    Write-Host "Longueur actuelle : $($appPasswordPlain.Length) caractères" -ForegroundColor Yellow
    $continue = Read-Host "Continuer quand même ? (o/n)"
    if ($continue -ne "o" -and $continue -ne "O") {
        exit
    }
}

# Construire le MAILER_DSN
$mailerDsn = "smtp://${email}:${appPasswordPlain}@smtp.gmail.com:587"

Write-Host ""
Write-Host "Configuration à appliquer :" -ForegroundColor Cyan
Write-Host "MAILER_DSN=$mailerDsn" -ForegroundColor White
Write-Host ""

$confirm = Read-Host "Confirmer cette configuration ? (o/n)"
if ($confirm -ne "o" -and $confirm -ne "O") {
    Write-Host "Configuration annulée" -ForegroundColor Yellow
    exit
}

# Modifier le fichier .env
$envFile = ".env"
if (-not (Test-Path $envFile)) {
    Write-Host "Erreur : Le fichier .env n'existe pas !" -ForegroundColor Red
    exit 1
}

$content = Get-Content $envFile -Raw

# Remplacer ou ajouter MAILER_DSN
if ($content -match "MAILER_DSN=") {
    $content = $content -replace "MAILER_DSN=.*", "MAILER_DSN=$mailerDsn"
    Write-Host "✓ MAILER_DSN mis à jour" -ForegroundColor Green
} else {
    $content += "`n###> symfony/mailer ###`n"
    $content += "MAILER_DSN=$mailerDsn`n"
    $content += "###< symfony/mailer ###`n"
    Write-Host "✓ MAILER_DSN ajouté" -ForegroundColor Green
}

Set-Content -Path $envFile -Value $content -NoNewline

Write-Host ""
Write-Host "=== Configuration terminée ! ===" -ForegroundColor Green
Write-Host ""
Write-Host "Prochaines étapes :" -ForegroundColor Yellow
Write-Host "1. Videz le cache : php bin/console cache:clear" -ForegroundColor White
Write-Host "2. Testez l'envoi : php bin/console app:test-reset-password --email=$email" -ForegroundColor White
Write-Host ""
Write-Host "Les emails seront maintenant envoyés à votre vraie boîte Gmail !" -ForegroundColor Cyan
Write-Host ""
