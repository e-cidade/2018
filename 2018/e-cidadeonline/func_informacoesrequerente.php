<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once("classes/db_ouvidoriaatendimento_classe.php");

$oGet = db_utils::postMemory($_GET);

$oDaoOuvidoriaAtendimento = db_utils::getDao('ouvidoriaatendimento');
$sSqlBuscaAtendimento     = $oDaoOuvidoriaAtendimento->sql_query_proc($oGet->iAtendimento);
$rsBuscaAtendimento       = $oDaoOuvidoriaAtendimento->sql_record($sSqlBuscaAtendimento);
$oDadosAtendimento        = db_utils::fieldsMemory($rsBuscaAtendimento, 0);

$oStdDadosRequerente = new stdClass();
$oStdDadosRequerente->codigo         = "";
$oStdDadosRequerente->nomerequerente = "Anônimo";
$oStdDadosRequerente->cpfcnpj        = "";
$oStdDadosRequerente->municipio      = "";
$oStdDadosRequerente->cep            = "";
$oStdDadosRequerente->estado         = "";
$oStdDadosRequerente->endereco       = "";
$oStdDadosRequerente->complemento    = "";
$oStdDadosRequerente->numero         = "";
$oStdDadosRequerente->bairro         = "";
$oStdDadosRequerente->telefone       = "";

if ($oDadosAtendimento->ov05_sequencial == 2) {

	if (isset($oDadosAtendimento->ov11_cgm) && $oDadosAtendimento->ov11_cgm != "") {


		$sCamposCgm     = "z01_numcgm  as codigo,         ";
		$sCamposCgm    .= "z01_nome    as nomerequerente, ";
		$sCamposCgm    .= "z01_cgccpf  as cpfcnpj,        ";
		$sCamposCgm    .= "z01_munic   as municipio,      ";
		$sCamposCgm    .= "z01_cep     as cep,            ";
		$sCamposCgm    .= "z01_uf      as estado,         ";
		$sCamposCgm    .= "z01_ender   as endereco,       ";
		$sCamposCgm    .= "z01_numero  as numero,         ";
		$sCamposCgm    .= "z01_bairro  as bairro,         ";
		$sCamposCgm    .= "z01_compl   as complemento,    ";
		$sCamposCgm    .= "z01_telef   as telefone        ";

		$oDaoBuscaCgm           = db_utils::getDao('cgm');
		$sSqlBuscaDadosCGM      = $oDaoBuscaCgm->sql_query_file($oDadosAtendimento->ov11_cgm, $sCamposCgm);
		$rsBuscaDadosCgmCidadao = $oDaoBuscaCgm->sql_record($sSqlBuscaDadosCGM);
		 
	} else if (!empty($oDadosAtendimento->ov10_cidadao)) {

		$sCamposCidadao  = "ov02_sequencial as codigo,         ";
		$sCamposCidadao .= "ov02_nome       as nomerequerente, ";
		$sCamposCidadao .= "ov02_cnpjcpf    as cpfcnpj,        ";
		$sCamposCidadao .= "ov02_munic      as municipio,      ";
		$sCamposCidadao .= "ov02_cep        as cep,            ";
		$sCamposCidadao .= "ov02_uf         as estado,         ";
		$sCamposCidadao .= "ov02_endereco   as endereco,       ";
		$sCamposCidadao .= "ov02_numero     as numero,         ";
		$sCamposCidadao .= "ov02_bairro     as bairro,         ";
		$sCamposCidadao .= "ov02_compl      as complemento,    ";
		$sCamposCidadao .= "ov07_numero     as telefone        ";

		$oDaoBuscaCidadao       = db_utils::getDao('cidadao');
		$sSqlBuscaDadosCidadao  = $oDaoBuscaCidadao->sql_query_file($oDadosAtendimento->ov10_cidadao, null, $sCamposCidadao);
		$rsBuscaDadosCgmCidadao = $oDaoBuscaCidadao->sql_record($sSqlBuscaDadosCidadao);
		
		$oDaoBuscaCidadaoContato = db_utils::getDao('cidadaotelefone');
		$sSqlBuscaTelefones      = $oDaoBuscaCidadaoContato->sql_query_file(null, "*", null, "ov07_cidadao = {$oDadosAtendimento->ov10_cidadao}");
		$rsBuscaTelefones        = $oDaoBuscaCidadaoContato->sql_record($sSqlBuscaTelefones);
		$iTotalTelefones         = $oDaoBuscaCidadaoContato->numrows;
		
	  $aTelefones = array();
		if ($iTotalTelefones > 0) {
		  $aTelefones = db_utils::getCollectionByRecord($rsBuscaTelefones);
		}
	}

	/*
	 * dados pessoais do requerente
	 */
	if ( isset($rsBuscaDadosCgmCidadao) && pg_num_rows($rsBuscaDadosCgmCidadao) > 0 ) {
  	
  	$oResultadoRequerente = db_utils::fieldsMemory($rsBuscaDadosCgmCidadao, 0);
  	$oStdDadosRequerente->codigo         = $oResultadoRequerente->codigo;
  	$oStdDadosRequerente->nomerequerente = "{$oResultadoRequerente->codigo} - {$oResultadoRequerente->nomerequerente}";
  	$oStdDadosRequerente->cpfcnpj        = $oResultadoRequerente->cpfcnpj;
  	$oStdDadosRequerente->municipio      = $oResultadoRequerente->municipio;
  	$oStdDadosRequerente->cep            = $oResultadoRequerente->cep;
  	$oStdDadosRequerente->estado         = $oResultadoRequerente->estado;
  	$oStdDadosRequerente->endereco       = $oResultadoRequerente->endereco;
  	$oStdDadosRequerente->complemento    = $oResultadoRequerente->complemento;
  	$oStdDadosRequerente->numero         = $oResultadoRequerente->numero;
  	$oStdDadosRequerente->bairro         = $oResultadoRequerente->bairro;
  	$oStdDadosRequerente->telefone       = $oResultadoRequerente->telefone;
	}
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="include/estilodai.css" >
	<script language="JavaScript" src="scripts/db_script.js"></script>
	<script language="JavaScript" src="scripts/prototype.js"></script>
	<style type="text/css">
    <?php
      db_estilosite();
  	?>
  	
  	.valores {
  	  width: 100%;
  	}
	</style>
</head>
<body bgcolor="<?=$w01_corbody?>" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div align="center">
<fieldset style="width: 600px">
  <legend class="titulo"><b>Informações do Requerente</b></legend>
  <table style="width: 98%" class="texto">
    <tr>
      <td width="120px"><b>Requerente:</b></td>
      <td class="valores">
        <input class="valores" name="input" value="<?=$oStdDadosRequerente->nomerequerente;?>" readonly="readonly" />
      </td>
    </tr>
    <tr>
      <td><b>CPF/CNPJ:</b></td>
      <td class="valores">
        <input class="valores" name="input" value="<?=$oStdDadosRequerente->cpfcnpj;?>" readonly="readonly" />
      </td>
    </tr>
    
    <tr>
      <td><b>Telefone:</b></td>
      <td class="valores">
        <input class="valores" name="input" value="<?=$oStdDadosRequerente->telefone;?>" readonly="readonly" />
      </td>
    </tr>    
    
    <tr>
      <td nowrap="nowrap"><b>Município / Estado:</b></td>
      <td class="valores">
        <input class="valores" name="input" value="<?=$oStdDadosRequerente->municipio ." / ".$oStdDadosRequerente->estado;?>" readonly="readonly" />
      </td>
    </tr>
    <tr>
      <td><b>Bairro:</b></td>
      <td class="valores">
        <input class="valores" name="input" value="<?=$oStdDadosRequerente->bairro;?>" readonly="readonly" />
      </td>
    </tr>
    <tr>
      <td><b>Endereço:</b></td>
      <td class="valores">
        <input class="valores" name="input" value="<?=$oStdDadosRequerente->endereco;?>" readonly="readonly" />
      </td>
    </tr> 
    <tr>
      <td><b>Complemento:</b></td>
      <td class="valores">
        <input class="valores" name="input" value="<?=$oStdDadosRequerente->complemento;?>" readonly="readonly" />
      </td>
    </tr> 
  </table>
</fieldset>
</div>
</body>
</html>