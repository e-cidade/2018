<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("classes/db_bens_classe.php"));
require_once(modification("classes/db_benstransf_classe.php"));

$oPost = db_utils::postMemory($_POST);
$oJson = new services_json();

$clbens       = new cl_bens();
$clbenstransf = new cl_benstransf();


$rsConsultaTransf = $clbenstransf->sql_record($clbenstransf->sql_query($oPost->iCodTransf));

if ($clbenstransf->numrows > 0) {

	$oTransf = db_utils::fieldsMemory($rsConsultaTransf, 0);

	$sCampos = " distinct t52_bem,                                   ";
	$sCampos .= " t64_descr,                                          ";
	$sCampos .= " t52_descr,                                          ";
	$sCampos .= " t52_obs,                                            ";
	$sCampos .= " t52_ident,                                          ";
	$sCampos .= " t52_dtaqu,                                          ";
	$sCampos .= " ( select t56_situac                                 ";
	$sCampos .= "     from histbem                                    ";
	$sCampos .= "    where t56_codbem = t52_bem                       ";
	$sCampos .= " order by t56_histbem desc                           ";
	$sCampos .= "  limit 1                                            ";
	$sCampos .= " ) as situacao,                                      ";
	$sCampos .= " case                                                ";
	$sCampos .= "   when t95_codtran = {$oPost->iCodTransf} then true ";
	$sCampos .= "   else false                                        ";
	$sCampos .= " end as transf                                       ";

	$sWhere = " t52_depart  = {$oTransf->t93_depart}                                                               ";

	// Valida se o bem está em transferência que não seja a atual ($oPost->iCodTransf)

	$sWhere .= "    and not exists ( select *                                                                       ";
	$sWhere .= "                       from benstransfcodigo                                                        ";
	$sWhere .= "                            left  join benstransfconf on t96_codtran = benstransfcodigo.t95_codtran ";
	$sWhere .= "                      where t95_codbem = t52_bem                                                    ";
	$sWhere .= "                        and t96_codtran is null                                                     ";
	$sWhere .= "                        and t95_codtran != {$oPost->iCodTransf}                                     ";
	$sWhere .= "                   )                                                                                ";

	// Valida se o bem está na transferência atual eliminando as duplicidades

	$sWhere .= "    and case                                                                                        ";
	$sWhere .= "	         when t95_codtran != {$oPost->iCodTransf} then                                             ";
	$sWhere .= "	           case                                                                                    ";
	$sWhere .= "		           when  not exists ( select *                                                           ";
	$sWhere .= "		                                from benstransfcodigo                                            ";
	$sWhere .= "					                         where t95_codbem  = t52_bem                                       ";
	$sWhere .= "					                           and t95_codtran = {$oPost->iCodTransf} )  then true             ";
	$sWhere .= "						   else false                                                                            ";
	$sWhere .= "					   end                                                                                     ";
	$sWhere .= "					 else true                                                                                 ";
	$sWhere .= "			   end                                                                                         ";


	if (trim($oTransf->t93_clabens) != "" && $oTransf->t93_clabens != 0) {
		$sWhere .= " and t52_codcla  = {$oTransf->t93_clabens} ";
	}

	if (trim($oTransf->t93_divisao) != "" && $oTransf->t93_divisao != 0) {
		$sWhere .= " and t33_divisao = {$oTransf->t93_divisao} ";
	}

	$sWhere .= " and t55_codbem is null";
	$rsConsultaBens = $clbens->sql_record($clbens->sql_query_transf(null, $sCampos, "t52_bem", $sWhere));

	if ($clbens->numrows > 0) {
		$aRetornaBens = db_utils::getCollectionByRecord($rsConsultaBens, false, false, true);
	} else {
		$sMensagem    = "Nenhuma bem encontrado!";
		$aRetornaBens = array("lErro" => true, "sMensagem" => urlencode($sMensagem));
	}

} else {
	$sMensagem    = "Nenhuma bem encontrado!";
	$aRetornaBens = array("lErro" => true, "sMensagem" => urlencode($sMensagem));
}

echo $oJson->encode($aRetornaBens);