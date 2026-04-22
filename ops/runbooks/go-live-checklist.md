# Go-live Checklist (EKS/ECR/RDS)

## Infrastructure
- Terraform plan reviewed and approved.
- EKS cluster healthy (`kubectl get nodes` all `Ready`).
- RDS Multi-AZ available and backups active.
- ECR repositories configured with scan-on-push and immutable tags.

## Security
- IAM roles reviewed (no wildcard permissions without justification).
- Secrets present in AWS Secrets Manager.
- No plaintext secrets in Kubernetes manifests.
- TLS certificate issued in ACM and attached to ALB ingress.

## Application
- Helm deployment successful in staging with production-like settings.
- Probes (`readiness`, `liveness`, `startup`) all green.
- HPA and PDB applied and verified.
- Database migrations executed through controlled one-shot job.

## Operations
- Dashboards and alerts active (CloudWatch and/or Prometheus).
- On-call rotation defined.
- RDS restore test performed in last 30 days.
- Rollback procedure tested and documented.
