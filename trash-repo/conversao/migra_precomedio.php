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

//require_once("../libs/db_conn.php");
require_once("../libs/db_utils.php");
require_once("../libs/db_stdlib.php");

$DB_SERVIDOR = 'fin07';
$DB_BASE     = 't68561_canela_patrimonial_v2_3_2';
$DB_PORTA    = '5433';
$DB_USUARIO  = 'postgres';
$DB_SENHA    = '';

if (!($conn = @pg_connect("host = '$DB_SERVIDOR' dbname = '$DB_BASE' port = '$DB_PORTA' user = '$DB_USUARIO' password = $DB_SENHA"))) {
  echo "Erro ao conectar com a base de dados";
  exit;
}

pg_query("select fc_startsession()");

$sSqlInstit = "select codigo from db_config where prefeitura is true limit 1";
$rsInstit   = pg_query($sSqlInstit);
$aInstit    = db_utils::getColectionByRecord($rsInstit);
// codigo da instituição prefeitura.
$iCodigoInstit = "";
foreach ($aInstit as $indiceInstit => $valorInstit) {
	$iCodigoInstit = $valorInstit->codigo;
}


$sSqlSetaInstit = "select fc_putsession('DB_instit', {$iCodigoInstit}::text );";
pg_query($sSqlSetaInstit);

db_sel_instit($iCodigoInstit);

$sDeletePrecoMedioMovimentacao  = 'delete from matestoqueinimeipm';
$sDeletePrecoMedio              = 'delete from matmaterprecomedio';
$sDeletePrecoMedioLigacao       = 'delete from matmaterprecomedioini';
if (isset($argv[1]) && $argv[1] != "") {

  $sDeletePrecoMedioMovimentacao  = 'delete from matestoqueinimeipm using matestoqueinimei, matestoqueitem, matestoque';
  $sDeletePrecoMedioMovimentacao .= ' where m89_matestoqueinimei = m82_codigo and m82_matestoqueitem = m71_codlanc';
  $sDeletePrecoMedioMovimentacao .= '   and m71_codmatestoque = m70_codigo';
  $sDeletePrecoMedioMovimentacao .= "   and m70_codmatmater in({$argv[1]})";
  $sDeletePrecoMedio             .= " where m85_matmater in({$argv[1]}) ";
}
pg_query($sDeletePrecoMedioLigacao);
pg_query($sDeletePrecoMedioMovimentacao);
pg_query($sDeletePrecoMedio);
$sSql  = "SELECT m70_codmatmater, m80_codigo, m82_codigo, m82_quant                ";
$sSql .= " from matestoqueinimei                                                      ";
$sSql .= "        inner join  matestoqueitem on m71_codlanc           = m82_matestoqueitem ";
$sSql .= "        inner join  matestoque     on m71_codmatestoque     = m70_codigo         ";
$sSql .= "        inner join  matestoqueini  on m82_matestoqueini  = m80_codigo         ";
if (isset($argv[1]) && $argv[1] != "") {
  $sSql .= "  where m70_codmatmater = {$argv[1]}";
}

$sSql .= "  order by m70_codmatmater, to_timestamp(m80_data || ' ' || m80_hora, 'YYYY-MM-DD HH24:MI:SS'), m80_codigo, m82_codigo";
echo "Inicio Script:".date("d/m/Y - H:i:s")."\n";
$iInicio = time();
$rsDados         = pg_query($sSql);
$iTotaLinhas     = pg_num_rows($rsDados);
$iMaterial       = '';
for ($i = 0; $i < $iTotaLinhas; $i++) {


	    $oValor = db_utils::fieldsMemory($rsDados, $i);
      pg_query("select fc_startsession()");
      $sSqlSetaInstit = "select fc_putsession('DB_instit', {$iCodigoInstit}::text );";
      pg_query($sSqlSetaInstit);
			$sSqlUpdate = "select fc_calculaprecomedio({$oValor->m82_codigo}::integer, {$oValor->m80_codigo}::integer, {$oValor->m82_quant}, false); ";
			$sErro = "OK";
			if (!pg_query($sSqlUpdate)) {
				$sErro = "ERRO";
			}
      if ($oValor->m70_codmatmater != $iMaterial) {

			  echo "Atualizando Item {{$oValor->m70_codmatmater}}\r";
      }
      $iMaterial = $oValor->m70_codmatmater;
	}
 echo "\nFim:".date("d/m/Y - H:i:s")."\n";
 $iFim = time();
 $tempoTotal = ($iFim - $iInicio);
 echo "Tempo total: {$tempoTotal} Segundos\n";
?>