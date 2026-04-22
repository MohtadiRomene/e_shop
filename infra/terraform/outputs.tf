output "cluster_name" {
  description = "EKS cluster name."
  value       = aws_eks_cluster.main.name
}

output "cluster_endpoint" {
  description = "EKS API endpoint."
  value       = aws_eks_cluster.main.endpoint
}

output "ecr_repository_urls" {
  description = "ECR repository URLs."
  value = {
    for name, repo in aws_ecr_repository.services :
    name => repo.repository_url
  }
}

output "rds_endpoint" {
  description = "RDS MySQL endpoint."
  value       = aws_db_instance.main.endpoint
}

output "rds_master_secret_arn" {
  description = "Master user password secret ARN (managed by AWS)."
  value       = aws_db_instance.main.master_user_secret[0].secret_arn
}
