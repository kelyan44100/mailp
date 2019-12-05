select PARTICIPANT_id_participant
from assignment_participant_enterprise as aspe
inner join participant ON participant.id_participant = aspe.PARTICIPANT_id_participant
inner join enterprise on enterprise.id_enterprise = aspe.ENTERPRISE_id_enterprise
WHERE aspe.ENTERPRISE_id_enterprise > 50
group by 1
HAVING COUNT(*) > 1

DELETE FROM assignment_sp_store_old where assignment_sp_store_old.PARTICIPANT_id_participant IN (
    select PARTICIPANT_id_participant
from assignment_participant_enterprise as aspe
inner join participant ON participant.id_participant = aspe.PARTICIPANT_id_participant
inner join enterprise on enterprise.id_enterprise = aspe.ENTERPRISE_id_enterprise
WHERE aspe.ENTERPRISE_id_enterprise > 50
group by 1
HAVING COUNT(*) > 1 )
    
	
	DELETE FROM assignment_sp_store_old where assignment_sp_store_old.PURCHASING_FAIR_id_purchasing_fair = 1
	
	
	SELECT aspo.PARTICIPANT_id_participant, aspo.ENTERPRISE_id_enterprise as 'store', ent.id_enterprise as 'provider', aspo.PURCHASING_FAIR_id_purchasing_fair
FROM assignment_sp_store_old as aspo
INNER JOIN assignment_participant_enterprise as aspe ON aspe.PARTICIPANT_id_participant = aspo.PARTICIPANT_id_participant
INNER JOIN enterprise as ent ON ent.id_enterprise = aspe.ENTERPRISE_id_enterprise

INSERT INTO assignment_sp_store (assignment_sp_store.PARTICIPANT_id_participant, assignment_sp_store.ENTERPRISE_STORE_id_enterprise, assignment_sp_store.ENTERPRISE_PROVIDER_id_enterprise, assignment_sp_store.PURCHASING_FAIR_id_purchasing_fair)
SELECT aspo.PARTICIPANT_id_participant, aspo.ENTERPRISE_id_enterprise as 'store', ent.id_enterprise as 'provider', aspo.PURCHASING_FAIR_id_purchasing_fair
FROM assignment_sp_store_old as aspo
INNER JOIN assignment_participant_enterprise as aspe ON aspe.PARTICIPANT_id_participant = aspo.PARTICIPANT_id_participant
INNER JOIN enterprise as ent ON ent.id_enterprise = aspe.ENTERPRISE_id_enterprise