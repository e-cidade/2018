<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_pcorcamjulg_classe.php");
require_once("classes/db_pcorcamtroca_classe.php");
require_once("classes/db_liclicitaata_classe.php");
require_once("classes/db_liclicitasituacao_classe.php");
require_once("model/compilacaoRegistroPreco.model.php");
require_once("model/licitacao.model.php");
require_once("classes/db_registroprecojulgamento_classe.php");

$clliclicita          = new cl_liclicita;
$clpcorcamjulg        = new cl_pcorcamjulg;
$clpcorcamtroca       = new cl_pcorcamtroca;
$clliclicitaata       = new cl_liclicitaata;
$clliclicitasituacao  = new cl_liclicitasituacao;
$oDaoRegistPrecoJulg  = new cl_registroprecojulgamento;

$clrotulo = new rotulocampo;
$clrotulo->label("l20_codigo");


$oDaoLogJulgamento     = db_utils::getDao('pcorcamjulgamentolog');
$oDaoLogJulgamentoItem = db_utils::getDao('pcorcamjulgamentologitem');

db_postmemory($HTTP_POST_VARS);


$action   = "lic1_liclicitacancjulg001.php";
$erro_msg = "";

if (isset($l20_codigo) && trim($l20_codigo) != "") {
  
  $sqlerro = false;
  
  db_inicio_transacao();
  $oLicitacao = new licitacao($l20_codigo);
  
  try {
    
    $oRegistroPreco = $oLicitacao->getCompilacaoRegistroPreco();
    $lRegistroPreco = true;
  } catch (Exception $eErro) {
    $lRegistroPreco = false;
  }
  
  /**
   * Verificamos se o existem algum contrato ativo para a licitacao.
   * caso exista, não devemos  
   */
  $oDaoAcordoliclicitem = db_utils::getDao("acordoliclicitem");
  $sSqlAcordoWhere      = "l21_codliclicita = {$l20_codigo} and (ac16_acordosituacao  not in (2,3))";
  $sSqlDadosAcordo      = $oDaoAcordoliclicitem->sql_query_acordo(null, "ac26_acordo", null, $sSqlAcordoWhere);
  $rsDadosAcordo = $oDaoAcordoliclicitem->sql_record($sSqlDadosAcordo);
  if ($oDaoAcordoliclicitem->numrows > 0) {

    $iNumeroAcordo = db_utils::fieldsMemory($rsDadosAcordo, 0)->ac26_acordo;
    $sqlerro       = true;
    $erro_msg      = "a Licitação está vinculada ao acordo {$iNumeroAcordo}, ";
    $erro_msg     .= "Não será possivel cancelar o julgamento da Solicitação";
  } 
  $sql = "select pcorcamjulg.pc24_orcamitem,  
                 pcorcamjulg.pc24_orcamforne
            from pcorcamjulg
                 inner join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamjulg.pc24_orcamitem
                 inner join liclicitem     on liclicitem.l21_codigo         = pcorcamitemlic.pc26_liclicitem
           where liclicitem.l21_codliclicita = ".$l20_codigo;
      
    

    $res_pcorcamjulg = $clpcorcamjulg->sql_record($sql);

    
    if ($lRegistroPreco) {

      $aRegistroPreco = $oRegistroPreco->getRegistrosdePreco();
      if (count($aRegistroPreco) > 0) {
        
        $sSolicitacaoGeradas = "";
        $sVirgula            = "";
        foreach ($aRegistroPreco as $oDadosRegistroPreco) {

          $sSolicitacaoGeradas .= $sVirgula.$oDadosRegistroPreco->getCodigo();
          $sVirgula             = ", ";
        }
        
        $sqlerro  = true;
        $erro_msg  = "Não é possível cancelar o julgamento. Licitação {$l20_codigo} ";
        $erro_msg .= "possui vinculo com as seguintes solicitações: {$sSolicitacaoGeradas}.";
      }
      
      if (!$sqlerro) {
        
        $aReequilibrios = $oRegistroPreco->getReequilibrios();
        if (count($aReequilibrios) > 0) {

          $erro_msg = "Não é possível cancelar o julgamento, Licitação {$l20_codigo}, possui reequilíbrios.";
          $sqlerro  = true;
        }
      }
    }
    
    if ($sqlerro == false) {
       
      if ($clpcorcamjulg->numrows > 0) {
        
        $numrows = $clpcorcamjulg->numrows;
        for($i = 0; $i < $numrows; $i++) {
          
          db_fieldsmemory($res_pcorcamjulg,$i);
          $clpcorcamjulg->excluir($pc24_orcamitem, $pc24_orcamforne);

          if ($clpcorcamjulg->erro_status == 0){
            $erro_msg = $clpcorcamjulg->erro_msg;
            $sqlerro  = true;
            break;
          }
          
          $sWhereLogJulgamento = "pc93_pcorcamitem = {$pc24_orcamitem} and pc93_pcorcamforne = {$pc24_orcamforne}"; 
          $sSqlLogJulgamento   = $oDaoLogJulgamentoItem->sql_query_file(null, "pc93_pcorcamjulgamentolog", 
                                                                        null, $sWhereLogJulgamento);
          
          $rsLogJulgamento     = $oDaoLogJulgamentoItem->sql_record($sSqlLogJulgamento);
          if ($oDaoLogJulgamentoItem->numrows > 0) {

            $pc93_pcorcamjulgamentolog = db_utils::fieldsMemory($rsLogJulgamento, 0)->pc93_pcorcamjulgamentolog;
            
            $oDaoLogJulgamento->pc92_sequencial = $pc93_pcorcamjulgamentolog;
            $oDaoLogJulgamento->pc92_ativo      = 'false';
            $oDaoLogJulgamento->alterar($pc93_pcorcamjulgamentolog);
            if ($oDaoLogJulgamento->erro_status == 0) {
              
              $erro_msg = $oDaoLogJulgamento->erro_msg;
              $sqlerro  = true;
              break;
            }
          }

          /**
           * Verifica se a licitação usa Registro de Preco. Caso use, é excluido também os registros 
           * da tabela registroprecojulgamento.
           */
          if ($lRegistroPreco) {
            
            $sWhereExcluir = " pc65_orcamitem = {$pc24_orcamitem} and pc65_orcamforne = {$pc24_orcamforne} ";
            $oDaoRegistPrecoJulg->excluir(null, $sWhereExcluir);
            if ($oDaoRegistPrecoJulg->erro_status == "0") {
  
              $erro_msg = $clpcorcamjulg->erro_msg;
              $sqlerro  = true;
              break;
            }
          }
        }
      }
    }
    
    if ($sqlerro == false) {
      
      $sql = "select pcorcamtroca.pc25_codtroca
                from pcorcamtroca
                     inner join pcorcamitemlic on pcorcamitemlic.pc26_orcamitem = pcorcamtroca.pc25_orcamitem
                     inner join liclicitem     on liclicitem.l21_codigo         = pcorcamitemlic.pc26_liclicitem
               where liclicitem.l21_codliclicita = ".$l20_codigo;
      
      $res_pcorcamtroca = $clpcorcamtroca->sql_record($sql);
      
      if ($clpcorcamtroca->numrows > 0){
        
        $numrows = $clpcorcamtroca->numrows;
        for($i = 0; $i < $numrows; $i++){
          
          db_fieldsmemory($res_pcorcamtroca,$i);
          $clpcorcamtroca->excluir($pc25_codtroca);
          if ($clpcorcamtroca->erro_status == 0){
            
            $erro_msg = $clpcorcamtroca->erro_msg;
            $sqlerro  = true;
            break;
          }
        }
      }
    }

    if ($sqlerro == false){
      
      $clliclicita->l20_codigo  = $l20_codigo;
      $clliclicita->l20_licsituacao = '0';
      $clliclicita->alterar($l20_codigo);
      
      if ($clliclicita->erro_status == 0) {

        $erro_msg = $clliclicita->erro_msg;
        $sqlerro  = true;
      }
    }
     
    if ($sqlerro == false) {
    
		  $l11_sequencial = '';
      $clliclicitasituacao->l11_id_usuario  = DB_getSession("DB_id_usuario");
      $clliclicitasituacao->l11_licsituacao = '0';
      $clliclicitasituacao->l11_liclicita   = $clliclicita->l20_codigo;
		  $clliclicitasituacao->l11_obs         = "Julgamento da licitação cancelado.";
      $clliclicitasituacao->l11_data        = date("Y-m-d",DB_getSession("DB_datausu"));
      $clliclicitasituacao->l11_hora        = DB_hora();
	    $clliclicitasituacao->incluir($l11_sequencial);
      if ($clliclicitasituacao->erro_status == 0){
		  	$erro_msg = $clliclicitasituacao->erro_msg;
        $sqlerro  = true;
	    }

  }

  if ($sqlerro == false) {
  	
  	if (isset($_SESSION["modeloataselecionadojulgamento"])) {
  		unset($_SESSION["modeloataselecionadojulgamento"]);
  	}

  	$sWhere = "l39_liclicita = {$l20_codigo} and l39_posicaoinicial is true";
  	$clliclicitaata->excluir(null, $sWhere);
  	if ($clliclicitaata->erro_status == 0) {
  		
  		$erro_msg = $clliclicitaata->erro_msg;
      $sqlerro  = true;
  	}
  }  
  
  if ($sqlerro == false){
    $erro_msg = "Exclusao feita com sucessos.";
  }

  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_confirmar(){
  if(document.form1.l20_codigo.value == ""){
    document.form1.l20_codigo.focus();
    alert("Informe o código da Licitação");
  }else{
    if (confirm("Todos os itens julgados e trocados serao excluidos. Tem certeza?")){ 
         document.form1.submit();    
    }
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.l20_codigo.focus();" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post" action="<?=($action)?>">
<table border='0'>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Tl20_codigo?>">
    <b>
    <?db_ancora('Licitação',"js_pesquisa_liclicita(true);",1);?>&nbsp;:
    </b> 
    </td>
    <td align="left" nowrap>
      <? 
        db_input("l20_codigo",6,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
      ?>
    </td>
  </tr>
  <tr height="20px">
    <td ></td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="confirmar" type="button" onclick="js_confirmar();" value="Confirmar">
    </td>
  </tr>
</table>
</form>
</center>
<? 
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

   if (strlen($erro_msg) > 0){
        db_msgbox($erro_msg);
   }
?>
<script>
function js_pesquisa_liclicita(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicitacancjulg.php?funcao_js=parent.js_mostraliclicita1|l20_codigo','Pesquisa',true);
  }else{
     if(document.form1.l20_codigo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_liclicita','func_liclicitacancjulg.php?pesquisa_chave='+document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita','Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro){
  if(erro==true){ 
      alert("Licitacao ja julgada,revogada ou com autorizacao ativa.");
      document.form1.l20_codigo.value = ''; 
      document.form1.l20_codigo.focus(); 
  } else {
      document.form1.l20_codigo.value = chave; 
  }
}
function js_mostraliclicita1(chave1){
   document.form1.l20_codigo.value = chave1;  
   db_iframe_liclicita.hide();
   js_confirmar();
}

js_pesquisa_liclicita(true);
</script>
</body>
</html>