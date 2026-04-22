# Deployment Guide - AWS (ECR + EKS + RDS MySQL)

This guide implements the production deployment blueprint in this repository.

## 1) Provision AWS infrastructure
Use Terraform from `infra/terraform`:

```bash
cd infra/terraform
cp terraform.tfvars.example terraform.tfvars
terraform init
terraform plan -out tfplan
terraform apply tfplan
```

Important outputs:
- `cluster_name`
- `ecr_repository_urls`
- `rds_endpoint`
- `rds_master_secret_arn`

## 2) Bootstrap EKS addons
Follow `k8s/bootstrap/README.md` and install:
- AWS Load Balancer Controller
- External Secrets Operator
- Metrics Server
- Cluster Autoscaler (or Karpenter)

## 3) Create runtime secrets
Create one secret in AWS Secrets Manager:
- Name: `/e-shop/prod/app`
- JSON value:

```json
{
  "app_secret": "replace-with-long-random-secret",
  "database_url": "mysql://user:password@<rds-endpoint>:3306/e_shop?serverVersion=8.0.32&charset=utf8mb4"
}
```

## 4) Build and push images to ECR
Manual fallback commands:

```bash
aws ecr get-login-password --region eu-west-1 | docker login --username AWS --password-stdin <account>.dkr.ecr.eu-west-1.amazonaws.com
docker build -f Dockerfile -t <account>.dkr.ecr.eu-west-1.amazonaws.com/e-shop/app:sha-<git-sha> .
docker build -f docker/nginx/Dockerfile -t <account>.dkr.ecr.eu-west-1.amazonaws.com/e-shop/web:sha-<git-sha> .
docker push <account>.dkr.ecr.eu-west-1.amazonaws.com/e-shop/app:sha-<git-sha>
docker push <account>.dkr.ecr.eu-west-1.amazonaws.com/e-shop/web:sha-<git-sha>
```

## 5) Deploy with Helm

```bash
helm upgrade --install e-shop ./k8s/helm/e-shop \
  --namespace e-shop \
  --create-namespace \
  --set image.app.repository=<account>.dkr.ecr.eu-west-1.amazonaws.com/e-shop/app \
  --set image.web.repository=<account>.dkr.ecr.eu-west-1.amazonaws.com/e-shop/web \
  --set image.app.tag=sha-<git-sha> \
  --set image.web.tag=sha-<git-sha> \
  --set ingress.host=shop.example.com \
  --set ingress.certificateArn=arn:aws:acm:eu-west-1:<account>:certificate/<id>
```

## 6) Automate with GitHub Actions
Workflow file is already included:
- `.github/workflows/ci-cd-eks.yml`

Set required GitHub secrets:
- `AWS_GITHUB_ROLE_ARN`

Use GitHub environments for staged approvals:
- `staging`
- `production` (manual approval recommended)

## 7) Validation
- `kubectl get pods -n e-shop`
- `kubectl get ingress -n e-shop`
- Verify app home page and authentication flow.
- Verify application logs and error rates.

## 8) Operations and recovery
- Foundation controls: `ops/aws/FOUNDATION.md`
- Go-live process: `ops/runbooks/go-live-checklist.md`
- DB restore process: `ops/runbooks/rds-restore.md`
