<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libsys.php");

$oGet = db_utils::postMemory($_GET,0);

$sFormat = $oGet->formato;

// declaração das váriaveis
$sWhere     = null;
$sWhereFrom = null;
$sOrderBy   = null;
$sAnd       = null;
$sImprimir  = "Ambos";

// Tratamento de dados para o filtro
switch ($oGet->filtro) {

  case "t":
    $sBaixa      = "Todas";
  break;

  case "b":
    $sBaixa      = "Baixadas";
    $sWhereFrom .= " {$sAnd} j01_baixa is not null and j01_baixa < now()";
    $sAnd        = " and ";
  break;

  case "nb":
    $sBaixa      = "Não Baixadas";
    $sWhereFrom .= " {$sAnd} ( j01_baixa is null or j01_baixa >= now())";
    $sAnd        = " and ";
  break;

}

// matricula
if ( isset($oGet->matricula) && !empty($oGet->matricula)) {
  $sWhereFrom .= " {$sAnd} j01_matric = {$oGet->matricula}";
  $sAnd        = " and ";
} 

// setor quadralote
if (isset($oGet->setor) && !empty($oGet->setor)) {
  $sWhereFrom .= " {$sAnd} j34_setor = '{$oGet->setor}'";
  $sAnd        = " and "; 
}

// monta a clausula where do sql
if (isset($sWhereFrom) && !empty($sWhereFrom)) {
  $sWhereFrom = " where {$sWhereFrom}";
}

 $sFrom  = " ( select j01_matric,                                                                                     ";
 $sFrom .= "          substr(fc_iptuender,001,40) as ender,                                                           ";
 $sFrom .= "          substr(fc_iptuender,042,10) as numero,                                                          ";
 $sFrom .= "          substr(fc_iptuender,053,20) as complemento,                                                     ";
 $sFrom .= "          substr(fc_iptuender,074,40) as bairro,                                                          ";
 $sFrom .= "          substr(fc_iptuender,115,40) as munic,                                                           ";
 $sFrom .= "          substr(fc_iptuender,156,02) as uf,                                                              ";
 $sFrom .= "          substr(fc_iptuender,159,08) as cep,                                                             ";
 $sFrom .= "          substr(fc_iptuender,168,20) as cxpostal,                                                        ";
 $sFrom .= "          substr(fc_iptuender,189,40) as destinatario,                                                    ";
 $sFrom .= "          referencia,                                                                                     ";
 $sFrom .= "          setor,                                                                                          ";
 $sFrom .= "          quadra,                                                                                         ";
 $sFrom .= "          lote                                                                                            ";
 $sFrom .= "     from ( select j01_matric,                                                                            ";
 $sFrom .= "                   j40_refant as referencia,                                                              ";
 $sFrom .= "                   j34_setor  as setor,                                                                   ";
 $sFrom .= "                   j34_quadra as quadra,                                                                  ";
 $sFrom .= "                   j34_lote   as lote,                                                                    ";
 $sFrom .= "                   fc_iptuender(j01_matric) as fc_iptuender                                               ";
 $sFrom .= "              from iptubase                                                                               ";
 $sFrom .= "                   inner join lote    on j34_idbql  = j01_idbql                                           ";
 $sFrom .= "                   left  join iptuant on j40_matric = j01_matric                                          ";
 $sFrom .= "     {$sWhereFrom} ) as x                                                                                 ";
 $sFrom .= " ) as y                                                                                                   ";
 
if ( isset($oGet->imprimir) && $oGet->imprimir == 'sem' ) {
  $sWhere    = "trim(ender)  = ''";
  $sImprimir = "Somente sem Endereço";
} else if ( isset($oGet->imprimir) && $oGet->imprimir == 'com' ) {
  $sWhere = "trim(ender) != ''";
  $sImprimir = "Somente com Endereço";
}
 
// ordem impressao
switch ($oGet->ordemimp) {
  
  case "cl":
    $sOrderBy   = "munic,ender";
    $sOrdemImpr = "Cidade/Logradouro";
    break;
    
  case "bl":
    $sOrderBy   = "bairro,ender";
    $sOrdemImpr = "Bairro/Logradouro";
    break;
    
  case "an":
    $sOrderBy   = "destinatario";
    $sOrdemImpr = "Alfabética/Nome";
    break;
    
  case "ze":
    $sOrderBy   = "cxpostal";
    $sOrdemImpr = "Zona de Entrega";
    break;
    
  case "ra":
    $sOrderBy   = "referencia";
    $sOrdemImpr = "Referência Anterior";
    break;
    
  case "sql":
    $sOrderBy   = "setor,quadra,lote";
    $sOrdemImpr = "Setor/Quadra/Lote";
    break;
    
  case "ba":
    $sOrderBy   = "bairro";
    $sOrdemImpr = "Bairro/Alfabética";
    break;
}

# Include AgataAPI class
include_once('dbagata/classes/core/AgataAPI.class');

# Instantiate AgataAPI
ini_set("error_reporting","E_ALL & ~NOTICE");
$clagata = new cl_dbagata("cadastro/cad1_reliptuender002.agt");
    
 $api = $clagata->api;
  
 $api->setParameter('$head1', "Relatório de Endereço de Entrega");
 $api->setParameter('$head2', "Filtro: {$sBaixa}");
 $api->setParameter('$head3', "Ordem de Impressão: {$sOrdemImpr}");
 $api->setParameter('$head4', "Imprimir: {$sImprimir}");
 

 // Modifica o SQL do arquivo XML (cad2_reliptuender.agt) gravado pelo agata
 $xml = $api->getReport();
 $xml["Report"]["DataSet"]["Query"]["From"]    = $sFrom;
 $xml["Report"]["DataSet"]["Query"]["Where"]   = $sWhere;
 $xml["Report"]["DataSet"]["Query"]["OrderBy"] = $sOrderBy;
 $api->setReport($xml);
 
 // define o formato que o arquivo terá
 $api->setFormat($sFormat);

 if ( $sFormat != "pdf" ) {
   $sNomeArq = "tmp/rel_iptuender_".date("Ymd_His",db_getsession("DB_datausu")).".".$sFormat;
   $api->setOutputPath($sNomeArq);
 }

	ob_start();
	$ok      = $api->generateReport();
	$sBuffer = ob_get_contents();
	ob_end_clean();
	
	if ( $oGet->formato == "pdf" ) {
	
		if ( $api->getRowNum() > 0 ) {
			
		  if ($ok){  
		  	
		    echo $api->getError();
		  } else {
		  	
		    db_redireciona($clagata->arquivo);    
		  }
		  
		  echo $sBuffer;
		} else {
			
		  header('Location: db_erros.php?fechar=true&db_erro=Não existem registros para os filtros selecionados.');
		}
		
	} else {
		
	  echo "<script>";
	  echo "  listagem = \"{$sNomeArq} # Download do Arquivo\";";
	  echo "  parent.js_montarlista(listagem,'form1');";
	  echo "</script>";
	}
?>