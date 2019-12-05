-- Supprime les données incorrectes effectifs magasins
DELETE FROM store_workforce;

-- Contenu table store_workforce revu et corrigé avec base de données prod SCA
INSERT INTO `store_workforce` (`ENTERPRISE_id_enterprise`, `outer_clothing`, `under_clothing`, `shoes`) VALUES
(1, 1, 2, 1),
(2, 2, 2, 2),
(3, 2, 2, 2),
(4, 0, 0, 0),
(5, 1, 1, 1),
(6, 1, 1, 1),
(7, 2, 2, 2),
(9, 2, 2, 2),
(10, 2, 2, 2),
(11, 2, 2, 2),
(13, 1, 1, 1),
(14, 2, 2, 2),
(15, 2, 2, 2),
(16, 2, 2, 2),
(17, 2, 2, 2),
(18, 1, 1, 1),
(19, 2, 2, 2),
(20, 2, 2, 2),
(22, 2, 2, 2),
(23, 0, 0, 0),
(24, 2, 2, 2),
(25, 1, 1, 1),
(26, 0, 1, 0),
(27, 2, 2, 2),
(29, 0, 0, 0),
(30, 1, 1, 1),
(31, 1, 1, 1),
(32, 2, 2, 2),
(33, 1, 1, 1),
(34, 2, 2, 2),
(35, 2, 2, 2),
(36, 2, 2, 2),
(37, 2, 2, 2),
(39, 0, 1, 0),
(40, 1, 1, 0),
(42, 1, 1, 1),
(43, 1, 1, 1),
(44, 1, 1, 1),
(45, 2, 2, 2),
(46, 1, 1, 1),
(47, 1, 1, 1),
(48, 2, 2, 2),
(115, 1, 0, 0),
(116, 1, 0, 2),
(117, 2, 1, 2),
(118, 0, 0, 0),
(119, 1, 1, 0),
(120, 0, 0, 1),
(128, 3, 2, 3),
(129, 2, 2, 2),
(130, 2, 2, 2);

--
-- Contraintes pour les tables manquantes suite backup foireux
--

--
-- Contraintes pour la table `assignment_participant_department`
--
ALTER TABLE `assignment_participant_department`
  ADD CONSTRAINT `fk_assignment_participant_department_A` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_assignment_participant_department_B` FOREIGN KEY (`DEPARTMENT_id_department`) REFERENCES `department` (`id_department`);

--
-- Contraintes pour la table `assignment_participant_enterprise`
--
ALTER TABLE `assignment_participant_enterprise`
  ADD CONSTRAINT `fk_assignment_participant_enterprise_A` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_assignment_participant_enterprise_B` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`);

--
-- Contraintes pour la table `assignment_sp_store`
--
ALTER TABLE `assignment_sp_store`
  ADD CONSTRAINT `fk_assignment_sp_store_participant` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_ass_enterprise_store` FOREIGN KEY (`ENTERPRISE_STORE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_ass_enterprise_provider` FOREIGN KEY (`ENTERPRISE_PROVIDER_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_assignment_sp_store_pf` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `enterprise`
--
ALTER TABLE `enterprise`
  ADD CONSTRAINT `fk_enterprise_department` FOREIGN KEY (`DEPARTMENT_id_department`) REFERENCES `department` (`id_department`),
  ADD CONSTRAINT `fk_enterprise_profile` FOREIGN KEY (`PROFILE_id_profile`) REFERENCES `profile` (`id_profile`),
  ADD CONSTRAINT `fk_ent_typeof_pr` FOREIGN KEY (`TYPEOF_PROVIDER_id_typeof_provider`) REFERENCES `typeof_provider` (`id_typeof_provider`);

--
-- Contraintes pour la table `enterprise_contact`
--
ALTER TABLE `enterprise_contact`
  ADD CONSTRAINT `fk_ec_enterprise` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`);

--
-- Contraintes pour la table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `fk_log_enterprise` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`);

--
-- Contraintes pour la table `lunch`
--
ALTER TABLE `lunch`
  ADD CONSTRAINT `fk_lunch_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`),
  ADD CONSTRAINT `fk_lunch_enterprise` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`);

--
-- Contraintes pour la table `participation`
--
ALTER TABLE `participation`
  ADD CONSTRAINT `fk_participation_participant` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_participation_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `provider_present`
--
ALTER TABLE `provider_present`
  ADD CONSTRAINT `fk_pp_pf` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`),
  ADD CONSTRAINT `fk_pp_ent` FOREIGN KEY (`PROVIDER_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`);

--
-- Contraintes pour la table `purchasing_fair`
--
ALTER TABLE `purchasing_fair`
  ADD CONSTRAINT `fk_purchasing_fair_typeof_pf` FOREIGN KEY (`TYPEOF_PF_id_typeof_pf`) REFERENCES `typeof_pf` (`id_typeof_pf`);

--
-- Contraintes pour la table `qrcode_scan`
--
ALTER TABLE `qrcode_scan`
  ADD CONSTRAINT `fk_qrcode_scan_participant` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_qrcode_scan_enterprise` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_qrcode_scan_pf` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `requirement`
--
ALTER TABLE `requirement`
  ADD CONSTRAINT `fk_requirement_enterprise_provider` FOREIGN KEY (`ENTERPRISE_PROVIDER_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_requirement_enterprise_store` FOREIGN KEY (`ENTERPRISE_STORE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_requirement_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `special_guest`
--
ALTER TABLE `special_guest`
  ADD CONSTRAINT `fk_sp_guest_pf` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`),
  ADD CONSTRAINT `fk_sp_guest_ent` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`);

--
-- Contraintes pour la table `unavailability`
--
ALTER TABLE `unavailability`
  ADD CONSTRAINT `fk_unavailability_enterprise` FOREIGN KEY (`ENTERPRISE_id_enterprise`) REFERENCES `enterprise` (`id_enterprise`),
  ADD CONSTRAINT `fk_unavailability_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);

--
-- Contraintes pour la table `unavailability_sp`
--
ALTER TABLE `unavailability_sp`
  ADD CONSTRAINT `fk_unavailability_sp_participant` FOREIGN KEY (`PARTICIPANT_id_participant`) REFERENCES `participant` (`id_participant`),
  ADD CONSTRAINT `fk_unavailability_sp_purchasing_fair` FOREIGN KEY (`PURCHASING_FAIR_id_purchasing_fair`) REFERENCES `purchasing_fair` (`id_purchasing_fair`);