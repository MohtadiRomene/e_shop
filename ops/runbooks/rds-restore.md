# Runbook - RDS MySQL Restore

## Purpose
Restore production database safely with a predictable procedure.

## Preconditions
- Incident declared and approved by on-call lead.
- Latest valid snapshot identified.
- Application set to maintenance mode (or traffic drained).

## Steps
1. Identify snapshot:
   - Automated latest snapshot
   - Or point-in-time restore target timestamp
2. Restore into new instance (do not overwrite existing immediately).
3. Apply the same parameter group and security group.
4. Run data integrity checks (row counts, critical tables, login flow).
5. Update application secret (`database_url`) to new endpoint.
6. Restart application deployment:
   - `kubectl rollout restart deploy/e-shop -n e-shop`
7. Monitor errors, latency and business KPIs for 30 minutes.

## Rollback
- If restore validation fails, switch `database_url` back to previous endpoint.
- Restart deployment and keep incident bridge open.

## Evidence to capture
- Snapshot ID used
- Restore start/end time
- Validation checklist results
- User impact and resolution time
