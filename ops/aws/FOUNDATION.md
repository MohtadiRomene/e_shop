# AWS Foundation Checklist

## Accounts and environments
- Use separate AWS account for `production`.
- Use one shared account for `dev` and `staging` (or separate if required).
- Enforce MFA and SSO for all human access.

## IAM baseline
- Remove direct user access keys where possible.
- Use role assumption for CI/CD (GitHub OIDC).
- Apply least privilege policies per role and per namespace workload.

## Tagging policy
- Mandatory tags on every resource:
  - `Project`
  - `Environment`
  - `Owner`
  - `CostCenter`
  - `ManagedBy`

## Security baseline
- Enable CloudTrail in all regions.
- Enable GuardDuty and Security Hub.
- Encrypt data at rest with KMS.
- Store app secrets in Secrets Manager only.

## Cost governance
- Create AWS Budgets with alert thresholds at 50%, 80%, 100%.
- Activate Cost Explorer and monthly cost review.
- Track idle EKS nodes and old ECR images.
