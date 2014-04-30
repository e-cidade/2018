<?
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_liclicitem_classe.php");
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');
require_once("classes/solicitacaocompras.model.php");
$clliclicitem = new cl_liclicitem;
$clrotulo     = new rotulocampo;
$clrotulo->label("pc10_numero");

db_postmemory($HTTP_GET_VARS);

$oGet         = db_utils::postMemory($_GET);
$iSolicitacao = $oGet->pc10_numero;

//====================  instanciamos a classe da solicitação selecionada para retornar os itens

$oSolicitacaoCompras = new solicitacaoCompra($iSolicitacao);

try {
	
  $aItensSolicitacao   = $oSolicitacaoCompras->getItensSolicitacao();
  
} catch (Exception $oErro) {
	
	db_redireciona('db_erros.php?fechar=true&db_erro='. $oErro->getMessage());
	
}

//========================  escrevemos o arquivos com os itens encontrados para a solicitação

$clabre_arquivo = new cl_abre_arquivo("/tmp/Solicitacao_$iSolicitacao.csv");

if ($clabre_arquivo->arquivo != false) {
	
  $vir = $separador;
  $del = $delimitador;
  
  fputs($clabre_arquivo->arquivo, formatarCampo("CÓDIGO DO MATERIAL NA SOLICITAÇÃO" , $vir, $del));
  fputs($clabre_arquivo->arquivo, formatarCampo("POSIÇÃO DO MATERIAL NA SOLICITAÇÃO", $vir, $del));
  fputs($clabre_arquivo->arquivo, formatarCampo("CÓDIGO DO MATERIAL"                , $vir, $del));
  fputs($clabre_arquivo->arquivo, formatarCampo("DESCRIÇÃO"                         , $vir, $del));
  fputs($clabre_arquivo->arquivo, formatarCampo("QUANTIDADE."                       , $vir, $del));
  fputs($clabre_arquivo->arquivo, formatarCampo("UNIDADE"                           , $vir, $del));
  fputs($clabre_arquivo->arquivo, formatarCampo("VALOR UNITÁRIO (R$)"               , $vir, $del));
  fputs($clabre_arquivo->arquivo, formatarCampo("VALOR TOTAL (R$)"                  , $vir, $del));
  
  fputs($clabre_arquivo->arquivo, "\n");
  
  foreach ($aItensSolicitacao as $iItens => $oItens) {
  	
  	$iCodigoMaterial   = $oItens->pc01_codmater   ;
  	$iSeqSolicitem     = $oItens->pc11_codigo     ;
  	$iPosicaoSolicitem = $oItens->pc11_seq        ;
  	$iQuantidade       = $oItens->pc11_quant      ;
  	$sUnidade          = $oItens->m61_descr       ;
  	$nValor            = db_formatar($oItens->pc11_vlrun, "f") ;
  	$nValorTotal       = db_formatar(($oItens->pc11_quant * $oItens->pc11_vlrun), "f");
   	$sDescricao        = $oItens->pc01_descrmater ;
   	
  	if ($oItens->pc01_servico == 't') {
  		$sUnidade        = "SERVIÇO"; 
  	}
  	
  	fputs($clabre_arquivo->arquivo, formatarCampo($iSeqSolicitem    , $vir, $del));
  	fputs($clabre_arquivo->arquivo, formatarCampo($iPosicaoSolicitem, $vir, $del));
  	fputs($clabre_arquivo->arquivo, formatarCampo($iCodigoMaterial  , $vir, $del));
  	fputs($clabre_arquivo->arquivo, formatarCampo($sDescricao       , $vir, $del));
  	fputs($clabre_arquivo->arquivo, formatarCampo($iQuantidade      , $vir, $del));
  	fputs($clabre_arquivo->arquivo, formatarCampo($sUnidade         , $vir, $del));
  	fputs($clabre_arquivo->arquivo, formatarCampo($nValor           , $vir, $del));
  	fputs($clabre_arquivo->arquivo, formatarCampo($nValorTotal      , $vir, $del));
  	fputs($clabre_arquivo->arquivo, "\n");
  	
  }
  
  fclose($clabre_arquivo->arquivo);

  echo "<script>";
  echo "  jan = window.open('db_download.php?arquivo=".$clabre_arquivo->nomearq."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
  echo "  jan.moveTo(0,0);";
  echo "</script>";

}

// Funcao para formatar um campo
function formatarCampo($valor, $separador, $delimitador) {

	$del = "";
	if ($delimitador == "1") {
		 
		$del = "\"";

	} else if ($delimitador == "2") {
		 
		$del = "'";
	}

	$valor = str_replace("\n"," ",$valor);
	$valor = str_replace("\r"," ",$valor);
	
	return "{$del}{$valor}{$del}{$separador}";
}
?>