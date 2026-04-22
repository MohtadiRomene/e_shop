# AWS Infrastructure (Terraform)

This folder provisions a production-oriented AWS baseline for:
- `ECR` repositories (`e-shop/app`, `e-shop/web`)
- `EKS` cluster + managed node group
- `RDS MySQL` (Multi-AZ, encrypted, backups enabled)
- `VPC` with public, private and DB subnets

## Prerequisites
- Terraform `>= 1.6`
- AWS CLI authenticated with sufficient IAM permissions
- Optional: GitHub OIDC provider created in IAM

## Usage
1. Copy `terraform.tfvars.example` to `terraform.tfvars`
2. Update values (CIDRs, account specifics, repo)
3. Run:

```bash
terraform init
terraform fmt -recursive
terraform validate
terraform plan -out tfplan
terraform apply tfplan
```

## Security defaults included
- RDS encryption with KMS key rotation
- RDS private subnets only
- DB ingress restricted to EKS nodes security group
- ECR immutable tags + scan on push
- EKS control plane audit logs enabled
- Default tags for ownership and cost governance

## Post-provisioning manual steps
- Install EKS addons (`aws-load-balancer-controller`, autoscaler/Karpenter)
- Configure Route53 + ACM certificate for ingress host
- Configure External Secrets Operator and IAM roles via IRSA
- Populate Secrets Manager with application/runtime secrets
