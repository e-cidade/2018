<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once ("model/educacao/avaliacao/iElementoAvaliacao.interface.php");

db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");
db_app::import("exceptions.*");

$oGet          = db_utils::postMemory($_GET);
$oDaoRecHumano = db_utils::getDao("rechumano");
$oDaoRegencia  = db_utils::getDao("regencia");
$iEscola       = db_getsession("DB_coddepto");
$oTurma        = TurmaRepository::getTurmaByCodigo($iTurma);

$aCodigosDisciplinas = array();

foreach ($oTurma->getDisciplinas() as $oRegencia) {
  $aCodigosDisciplinas[] = $oRegencia->getDisciplina()->getCodigoDisciplina();
}

$sCodigosDisciplinas = implode(",", $aCodigosDisciplinas);

$sWhereBuscaRecHumano  = "     ed01_c_docencia    = 'S'";
$sWhereBuscaRecHumano .= " and ed25_i_ensino      = {$oTurma->getBaseCurricular()->getCurso()->getEnsino()->getCodigo()} ";
$sWhereBuscaRecHumano .= " and ed23_i_disciplina  in ({$sCodigosDisciplinas}) ";
$sWhereBuscaRecHumano .= " and ed75_i_escola      = {$iEscola} ";
$sWhereBuscaRecHumano .= " and ed17_i_turno       = {$oTurma->getTurno()->getCodigoTurno()} ";
$sWhereBuscaRecHumano .= " and ed75_i_saidaescola is null ";

if (isset($pesquisa_chave) && !empty($pesquisa_chave)) {
  $sWhereBuscaRecHumano .= " and ed20_i_codigo = {$oGet->pesquisa_chave}";
}

$sCampoBuscaRecHumano  = "distinct ed20_i_codigo ";

$oDaoHorarioDisp = db_utils::getDao('rechumanohoradisp');
$sSqlRecHumano   = $oDaoHorarioDisp->sql_query_disponivel_periodo(null, $sCampoBuscaRecHumano, null, $sWhereBuscaRecHumano);
$rsRecHumano     = $oDaoHorarioDisp->sql_record($sSqlRecHumano);

$iRegistrosRecHumano = $oDaoHorarioDisp->numrows;

$aRecHumano = array();

for ($i = 0; $i < $iRegistrosRecHumano; $i++) {
	$aRecHumano[] = db_utils::fieldsMemory($rsRecHumano, $i)->ed20_i_codigo;
} 

$sRecHumanos = implode(', ', $aRecHumano);

$sCampoDisponiveis  = " distinct                        ";
$sCampoDisponiveis .= " case                            ";
$sCampoDisponiveis .= "    when ed20_i_tiposervidor = 1 ";
$sCampoDisponiveis .= "      then cgmrh.z01_numcgm      ";
$sCampoDisponiveis .= "    else cgmcgm.z01_numcgm       ";
$sCampoDisponiveis .= " end as z01_numcgm,              ";
$sCampoDisponiveis .= " ed20_i_codigo,                  ";
$sCampoDisponiveis .= " case                            ";
$sCampoDisponiveis .= "    when ed20_i_tiposervidor = 1 ";
$sCampoDisponiveis .= "      then trim(cgmrh.z01_nome)  ";
$sCampoDisponiveis .= "    else trim(cgmcgm.z01_nome)   "; 
$sCampoDisponiveis .= " end as z01_nome                 ";

$sWhereDisponiveis  = "     ed20_i_codigo in ({$sRecHumanos})";
$sWhereDisponiveis .= " AND not exists (select 1 from docenteausencia where ed321_rechumano = ed20_i_codigo";
$sWhereDisponiveis .= "                                                 and ed321_escola = $iEscola)";

$sSqlRegenteDisponivel = $oDaoRecHumano->sql_query_rechumano_cgm(null, $sCampoDisponiveis, 'z01_nome', $sWhereDisponiveis);
$rsRegenteDisponivel   = $oDaoRecHumano->sql_record($sSqlRegenteDisponivel);
$iRegistrosDisponivel  = $oDaoRecHumano->numrows;
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<link href="estilos.css" rel="stylesheet" type="text/css">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
	</head>
	<body>
<center>
	<div>
	<?php 
		if (!isset($pesquisa_chave) && $iRegistrosDisponivel > 0) {
			db_lovrot($sSqlRegenteDisponivel, 15, "()", "", $oGet->funcao_js, "", "NoMe", array());
		} else {

      if (isset($pesquisa_chave) && !empty($pesquisa_chave)) {

        $rsRegente = pg_query($sSqlRegenteDisponivel);
        if (pg_num_rows($rsRegente) != 0) {
          
          db_fieldsmemory($rsRegente, 0);
          echo "<script>".$oGet->funcao_js."('$z01_nome',false);</script>";
        } else {
          echo "<script>".$oGet->funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
        }
      } else {
			  echo "<h3>Sem regente disponível para a regência selecionada</h3>";
			}
		}
	?>
	</div>
</center>
</body>
</html>