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

set_time_limit(0);
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_lista_classe.php");
require_once("classes/db_listadeb_classe.php");
require_once("classes/db_listanotifica_classe.php");
require_once("libs/db_sql.php");
require_once("classes/db_termo_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_listacda_classe.php");
require_once("libs/db_utils.php");
require_once("classes/db_certidarqretorno_classe.php");
require_once("classes/db_processoforo_classe.php");
require_once("classes/db_processoforoinicial_classe.php");
require_once("model/dbLayoutReader.model.php");
require_once("model/dbLayoutLinha.model.php");


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clcgm                = new cl_cgm;
$cllista              = new cl_lista;
$cllistadeb           = new cl_listadeb;
$cllistanotifica      = new cl_listanotifica;
$clListaCda           = new cl_listacda;
$oCertidArqRetorno    = new cl_certidarqretorno;
$oProcessoForo        = new cl_processoforo;
$oProcessoForoInicial = new cl_processoforoinicial;
$clrotulo             = new rotulocampo;

$oGet            = db_utils::postMemory($_GET);
$oPost           = db_utils::postMemory($_POST);
$oFile           = db_utils::postMemory($_FILES);
$instit          = db_getsession("DB_instit");

$aDados          = array();

$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k51_procede');
$clrotulo->label('k51_descr');

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");
if (isset($oPost->iArqRemessa) && isset($oFile->arquivo)) {

	try {
	
		/*
	   * Verificamos se o arquivo de remessa já teve arquivo de retorno processado
	   */
	  $oCertidArqRetorno->sql_record($oCertidArqRetorno->sql_query_file(null,"v84_sequencial", null, " v84_certidarqremessa = {$oPost->iArqRemessa}"));
	  if ($oCertidArqRetorno->numrows > 0) {
	    throw new Exception(_M('tributario.juridico.jur4_processaretornoremessa001.arquivo_processado'));
	  } 
	
	  $sNomeArq           = $oFile->arquivo['name'];
	  $dDataArquivo       = implode("-", array_reverse(explode("/",trim($oPost->v84_dtarquivo))));
	  $dDataProcessamento = date("Y-m-d",db_getsession('DB_datausu'));
		
	  $oDBLayoutReader = new DBLayoutReader(97,$oFile->arquivo['tmp_name'],true,true,true);   
	  $aLinhasArquivo  = $oDBLayoutReader->getLines();
	
	  foreach ( $aLinhasArquivo as $iIndLinha => $oLinha ) {
			
		  $aRegistros    = new stdClass();
		  
			$sNomeLinha  = $oLinha->getNomeLinha();
		  if ($sNomeLinha == 'fixo_1') {
	      
	      $iDtCitacaoDia = substr($oLinha->data_citacao,0,2);
        $iDtCitacaoMes = substr($oLinha->data_citacao,2,2);
        $iDtCitacaoAno = substr($oLinha->data_citacao,4,4);
        $dData_citacao =  $iDtCitacaoAno . "-" . $iDtCitacaoMes . "-" . $iDtCitacaoDia;
	      
	    }
	    		
			if ($sNomeLinha == 'fixo_2') {
				
				$iDtDistribuicaoDia = substr($oLinha->data_distribuicao,0,2);
        $iDtDistribuicaoMes = substr($oLinha->data_distribuicao,2,2);
        $iDtDistribuicaoAno = substr($oLinha->data_distribuicao,4,4);
        $dDataDistribuicao  =  $iDtDistribuicaoAno . "-" . $iDtDistribuicaoMes . "-" . $iDtDistribuicaoDia;
				
				$aRegistros->v71_data         = $dData_citacao;  
				$aRegistros->v70_codforo      = $oLinha->numero_processo_tj;
				$aRegistros->v70_vara         = $oLinha->vara;
				$aRegistros->v70_data         = $dDataDistribuicao;
				$aRegistros->v70_valorinicial = str_replace(",",".",$oLinha->valor_total_causa);
				$aRegistros->v70_cartorio     = $oLinha->cartorio;
	      $aRegistros->v70_observacao   = $oLinha->mensagem;
	      			
				$aCodigoProcesso              = explode('/', $oLinha->codigo_processo);
	      $aRegistros->v71_inicial      = $aCodigoProcesso[0];
	
				$aDados[] = $aRegistros;
			}
			
		}
		 
    db_inicio_transacao();
		foreach ($aDados as $oDados) {
			
			/**
			 * Verificamos se o numero do processo existe cadastrado
			 */
			$sSqlProcessoForo = $oProcessoForo->sql_query_file(null, "v70_sequencial,v70_codforo", "v70_sequencial", " trim(v70_codforo) = '{$oDados->v70_codforo}' ");
			$rsProcessoForo   = $oProcessoForo->sql_record($sSqlProcessoForo);
			if ($oProcessoForo->numrows == 0) {
				
				// proximo sequencial da processoforo
	      $oProcessoForo->v70_codforo             = $oDados->v70_codforo;
	      $oProcessoForo->v70_processoforomov     = "null";
	      $oProcessoForo->v70_id_usuario          = db_getsession("DB_id_usuario");
	      $oProcessoForo->v70_vara                = $oDados->v70_vara;
	      $oProcessoForo->v70_data                = $oDados->v70_data;
	      $oProcessoForo->v70_valorinicial        = $oDados->v70_valorinicial;
	      $oProcessoForo->v70_observacao          = $oDados->v70_observacao;
	      $oProcessoForo->v70_anulado             = 'false';
	      $oProcessoForo->v70_instit              = db_getsession('DB_instit');
	      $oProcessoForo->v70_cartorio            = $oDados->v70_cartorio;
			  $oProcessoForo->incluir(null);
	      if ($oProcessoForo->erro_status=="0"){
	        throw new Exception($oProcessoForo->erro_msg);
	      }
	      
			}	else {
				
				$oProcessoForo->v70_sequencial   = db_utils::fieldsMemory($rsProcessoForo,0)->v70_sequencial;
				$oProcessoForo->v70_valorinicial = "(v70_valorinicial+$oDados->v70_valorinicial)";
				$oProcessoForo->alterar($oProcessoForo->v70_sequencial);
			  if ($oProcessoForo->erro_status=="0"){
          throw new Exception($oProcessoForo->erro_msg);
        }
				
			}
			
	    $oProcessoForoInicial->v71_id_usuario   = db_getsession("DB_id_usuario");
	    $oProcessoForoInicial->v71_inicial      = $oDados->v71_inicial;
		  $oProcessoForoInicial->v71_processoforo = $oProcessoForo->v70_sequencial;
		  $oProcessoForoInicial->v71_data         = $oDados->v71_data;
		  $oProcessoForoInicial->v71_anulado      = 'false';
		  $oProcessoForoInicial->incluir(null);
		  if ($oProcessoForoInicial->erro_status=="0"){
		    throw new Exception($oProcessoForoInicial->erro_msg);
		  }
		
		}
		
	  // Dados para incluir  na certidarqretorno o arquivo processado
	  $oCertidArqRetorno->v84_certidarqremessa = $oPost->iArqRemessa;
	  $oCertidArqRetorno->v84_nomearq          = $sNomeArq;
	  $oCertidArqRetorno->v84_dtarquivo        = $dDataArquivo;
	  $oCertidArqRetorno->v84_dtprocessamento  = $dDataProcessamento;
	  $oCertidArqRetorno->incluir(null);
	  if ($oCertidArqRetorno->erro_status=="0") {
	    throw new Exception($oCertidArqRetorno->erro_msg);
	  }

	  db_msgbox(_M('tributario.juridico.jur4_processaretornoremessa001.retorno_processado_sucesso'));
	  db_fim_transacao(false);
	        
	} catch ( Exception $eException ) {
	  db_msgbox($eException->getMessage());
		db_fim_transacao(true);
	}

}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC onload="js_habilita();" >
  <form class="container" name="form1" method="post"  enctype="multipart/form-data">
    <fieldset>
      <legend>Processar Arquivo de Retorno</legend>
      <table class="form-container">
        <tr>
          <td title="<?=@$Tk60_codigo?>" >
            <?db_ancora("Arquivo de Remessa:", "js_pesquisaremessa(true);", 4);?>
          </td>
          <td>
            <?
              db_input("iArqRemessa",  4, $Ik60_codigo, true, "text", 3, "onchange='js_pesquisaremessa(false);'");
              db_input("v83_nomearq",  38, $Ik60_descr,  true, "text", 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            Arquivo de Retorno:
          </td>
          <td>
           <? db_input("arquivo",  40, null,  true, "file", 3, "onchange='js_habilita();'"); ?>
          </td>
        </tr>        
      </table>
    </fieldset> 
    <input type="submit" id="sArqRetorno"  value="Procesar" >
    <? db_input("v84_dtarquivo",  10, "",  true, "hidden", 3, "");?>
  </form>
<? 
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

var sUrlRPC = "jur4_certidarqremessa.RPC.php";

function js_habilita(){

  if(($F('iArqRemessa') == null || $F('iArqRemessa') == '') || ( $F('arquivo') == null || $F('arquivo') == '' )) {
    $('sArqRetorno').disabled = true;
  } else {
    $('sArqRetorno').disabled = false;
  }

}

function js_pesquisaremessa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_arqremessa','func_arqremessa.php?funcao_js=parent.js_mostraremessa1|iArqRemessa|v83_nomearq','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_arqremessa','func_arqremessa.php?pesquisa_chave='+document.form1.iArqRemessa.value+'&funcao_js=parent.js_mostraremessa','Pesquisa','false');
  }
}

function js_mostraremessa(chave,erro){
  document.form1.v83_nomearq.value = chave;
  if(erro==true){
    document.form1.v83_nomearq.focus();
    document.form1.v83_nomearq.value = '';
  }
  db_iframe_arqremessa.hide();
  js_habilita();
}

function js_mostraremessa1(chave1,chave2){
  document.form1.iArqRemessa.value = chave1;
  document.form1.v83_nomearq.value = chave2;
  db_iframe_arqremessa.hide();
  js_habilita();
}

</script>
<script>

$("iArqRemessa").addClassName("field-size2");
$("v83_nomearq").addClassName("field-size7");

</script>