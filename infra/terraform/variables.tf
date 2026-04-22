variable "project_name" {
  description = "Project short name."
  type        = string
  default     = "e-shop"
}

variable "environment" {
  description = "Environment name (dev, staging, prod)."
  type        = string
  default     = "prod"
}

variable "aws_region" {
  description = "AWS region."
  type        = string
  default     = "eu-west-1"
}

variable "vpc_cidr" {
  description = "Primary VPC CIDR."
  type        = string
  default     = "10.30.0.0/16"
}

variable "availability_zones" {
  description = "AZs used by VPC, EKS and RDS."
  type        = list(string)
  default     = ["eu-west-1a", "eu-west-1b", "eu-west-1c"]
}

variable "public_subnet_cidrs" {
  description = "Public subnet CIDRs."
  type        = list(string)
  default     = ["10.30.0.0/20", "10.30.16.0/20", "10.30.32.0/20"]
}

variable "private_subnet_cidrs" {
  description = "Private subnet CIDRs for EKS nodes."
  type        = list(string)
  default     = ["10.30.64.0/20", "10.30.80.0/20", "10.30.96.0/20"]
}

variable "db_subnet_cidrs" {
  description = "Private subnet CIDRs for RDS."
  type        = list(string)
  default     = ["10.30.128.0/24", "10.30.129.0/24", "10.30.130.0/24"]
}

variable "rds_instance_class" {
  description = "RDS instance class."
  type        = string
  default     = "db.t4g.medium"
}

variable "rds_allocated_storage" {
  description = "RDS allocated storage in GB."
  type        = number
  default     = 100
}

variable "rds_max_allocated_storage" {
  description = "RDS autoscaling max storage in GB."
  type        = number
  default     = 500
}

variable "rds_db_name" {
  description = "MySQL database name."
  type        = string
  default     = "e_shop"
}

variable "rds_username" {
  description = "Master username for RDS."
  type        = string
  default     = "eshop_admin"
}

variable "rds_backup_retention_days" {
  description = "Backup retention period for RDS."
  type        = number
  default     = 14
}

variable "node_instance_types" {
  description = "EKS managed node instance types."
  type        = list(string)
  default     = ["t3.large"]
}

variable "node_desired_size" {
  description = "Desired node count."
  type        = number
  default     = 3
}

variable "node_min_size" {
  description = "Minimum node count."
  type        = number
  default     = 3
}

variable "node_max_size" {
  description = "Maximum node count."
  type        = number
  default     = 8
}

variable "github_oidc_provider_arn" {
  description = "GitHub OIDC provider ARN for CI role assumption."
  type        = string
  default     = ""
}

variable "github_repo" {
  description = "GitHub repository in org/repo format."
  type        = string
  default     = ""
}
