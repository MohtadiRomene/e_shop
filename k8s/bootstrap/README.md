# EKS Bootstrap Addons

Install these components right after creating the EKS cluster:

1. `metrics-server`
2. `aws-load-balancer-controller`
3. `cluster-autoscaler` (or Karpenter)
4. `external-secrets`

## Recommended install order
1. Associate IAM OIDC provider for EKS.
2. Create IRSA roles for each controller.
3. Install controllers with Helm.
4. Validate pods in `kube-system` and `external-secrets` namespaces.

## Mandatory checks
- `kubectl get nodes` returns all nodes `Ready`
- `kubectl get pods -A` has no failing critical addon pods
- ALB controller has IAM permissions for `elasticloadbalancing:*` required actions
- External Secrets can read from AWS Secrets Manager
