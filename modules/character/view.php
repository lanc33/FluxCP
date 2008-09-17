<?php
if (!defined('FLUX_ROOT')) exit;

$charID = $params->get('id');

$col  = "ch.char_id, ch.account_id, ch.char_num, ch.name AS char_name, ch.class AS char_class, ch.base_level AS char_base_level, ";
$col .= "ch.job_level AS char_job_level, ch.base_exp AS char_base_exp, ch.job_exp AS char_job_exp, ch.zeny AS char_zeny, ";
$col .= "ch.str AS char_str, ch.agi AS char_agi, ch.vit AS char_vit, ";
$col .= "ch.int AS char_int, ch.dex AS char_dex, ch.luk AS char_luk, ch.max_hp AS char_max_hp, ch.hp AS char_hp, ";
$col .= "ch.max_sp AS char_max_sp, ch.sp AS char_sp, ch.status_point AS char_status_point, ";
$col .= "ch.skill_point AS char_skill_point, ch.online AS char_online, ";

$col .= "login.userid, login.account_id AS char_account_id, ";
$col .= "partner.name AS partner_name, partner.char_id AS partner_id, ";
$col .= "mother.name AS mother_name, mother.char_id AS mother_id, ";
$col .= "father.name AS father_name, father.char_id AS father_id, ";
$col .= "child.name AS child_name, child.char_id AS child_id, ";
$col .= "guild.guild_id, guild.name AS guild_name, ";
$col .= "guild_position.name AS guild_position, ";
$col .= "party.name AS party_name, party.leader_char AS party_leader_id, party_leader.name AS party_leader_name, ";

$col .= "homun.name AS homun_name, homun.class AS homun_class, homun.level AS homun_level, homun.exp AS homun_exp, ";
$col .= "homun.intimacy AS homun_intimacy, homun.hunger AS homun_hunger, homun.str AS homun_str, homun.agi As homun_agi, ";
$col .= "homun.vit AS homun_vit, homun.int AS homun_int, homun.dex AS homun_dex, homun.luk AS homun_luk, ";
$col .= "homun.hp AS homun_hp, homun.max_hp As homun_max_hp, homun.sp AS homun_sp, homun.max_sp AS homun_max_sp, ";
$col .= "homun.skill_point AS homun_skill_point, homun.alive AS homun_alive, ";

$col .= "pet.class AS pet_class, pet.name AS pet_name, pet.level AS pet_level, pet.intimate AS pet_intimacy, ";
$col .= "pet.hungry AS pet_hungry, pet_mob.kName AS pet_mob_name";

$sql  = "SELECT $col FROM {$server->charMapDatabase}.`char` AS ch ";
$sql .= "LEFT OUTER JOIN {$server->loginDatabase}.login ON login.account_id = ch.account_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS partner ON partner.char_id = ch.partner_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS mother ON mother.char_id = ch.mother ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS father ON father.char_id = ch.father ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS child ON child.char_id = ch.child ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.guild_member ON guild_member.char_id = ch.char_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.guild ON guild.guild_id = guild_member.guild_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.guild_position ON guild_member.position = guild_position.position ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.party ON ch.party_id = party.party_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.`char` AS party_leader ON party.leader_char = party_leader.char_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.homunculus AS homun ON ch.homun_id = homun.homun_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.pet ON ch.pet_id = pet.pet_id ";
$sql .= "LEFT OUTER JOIN {$server->charMapDatabase}.mob_db AS pet_mob ON pet_mob.ID = pet.class ";
$sql .= "WHERE ch.char_id = ?";

$sth  = $server->connection->getStatement($sql);
$sth->execute(array($charID));

$char = $sth->fetch();

if ($char && $char->char_account_id == $session->account->account_id) {
	$isMine = true;
}
else {
	$isMine = false;
}

if (!$isMine && !$auth->allowedToViewCharacter) {
	$this->deny();
}
?>