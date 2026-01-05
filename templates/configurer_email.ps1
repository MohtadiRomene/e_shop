# Script PowerShell pour configurer MAILER_DSN en mode fichier

Write-Host "=== Configuration MAILER_DSN pour le développement ===" -ForegroundColor Cyan
Write-Host ""

$envFile = ".env"

if (-not (Test-Path $envFile)) {
    Write-Host "Erreur : Le fichier .env n'existe pas !" -ForegroundColor Red
    exit 1
}

# Lire le contenu du fichier .env
$content = Get-Content $envFile -Raw

# Vérifier si MAILER_DSN existe déjà
if ($content -match "MAILER_DSN=") {
    Write-Host "MAILER_DSN trouvé dans .env" -ForegroundColor Yellow
    
    # Remplacer la valeur existante
    $content = $content -replace "MAILER_DSN=.*", "MAILER_DSN=file://%kernel.project_dir%/var/mail"
    Write-Host "MAILER_DSN mis à jour en mode fichier" -ForegroundColor Green
} else {
    Write-Host "MAILER_DSN non trouvé, ajout de la configuration..." -ForegroundColor Yellow
    
    # Ajouter la configuration avant la fin du fichier
    $content += "`n###> symfony/mailer ###`n"
    $content += "MAILER_DSN=file://%kernel.project_dir%/var/mail`n"
    $content += "###< symfony/mailer ###`n"
    Write-Host "MAILER_DSN ajouté en mode fichier" -ForegroundColor Green
}

# Écrire le contenu modifié
Set-Content -Path $envFile -Value $content -NoNewline

Write-Host ""
Write-Host "✓ Configuration terminée !" -ForegroundColor Green
Write-Host ""
Write-Host "Les emails seront maintenant sauvegardés dans : var/mail/" -ForegroundColor Cyan
Write-Host ""
Write-Host "Pour tester, exécutez :" -ForegroundColor Yellow
Write-Host "  php bin/console app:test-reset-password" -ForegroundColor White
Write-Host ""
