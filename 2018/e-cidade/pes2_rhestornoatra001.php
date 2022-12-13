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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_libpessoal.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table width="60%" border="0" cellspacing="4" cellpadding="0">
  <tr><td colspan="2">&nbsp;</td></tr>
  <form name="form1" method="post">
  <?
  if(!isset($opcao)){
    $opcao = "m";
  }
  if(!isset($filtro)){
    $filtro = "s";
  }
  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS
  $geraform->uniregi = true;                      // SELECIONA A MATRÍCULA
  $geraform->valpadr = false;                     // TRAZER ANO / MÊS EM BRANCO
  $geraform->re1nome = "regis";                  // NOME DO CAMPO DA MATRÍCULA INICIAL
  $geraform->gera_form(null,null);
  ?>
  <tr>
    <td colspan='2' align='center'>
      <input type="button" name="processar" value="Processar" onclick="js_verificar_dados();">
    </td>
  </tr>
  </form>
</table>
</center>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_enviar_dados(movimento){
  jan = window.open('pes2_rhreciboatra002.php?movimento='+movimento,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_verificar_dados(){
  if(document.form1.anofolha.value == ""){
    alert("Informe o ano do pagamento.");
    document.form1.anofolha.focus();
  }else if(document.form1.mesfolha.value == ""){
    alert("Informe o mês do pagamento.");
    document.form1.mesfolha.focus();
  }else if(document.form1.regis.value == ""){
    alert("Informe a matrícula.");
    document.form1.regis.focus();
  }else{
    qry = "&chave_rh57_ano="+document.form1.anofolha.value;
    qry+= "&chave_rh57_mes="+document.form1.mesfolha.value;
    qry+= "&chave_rh57_regist="+document.form1.regis.value;
    qry+= "&seleciona=S";
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpagocor','func_rhpagocor.php?funcao_js=parent.js_enviar_dados|rh58_codigo'+qry,'Foto do funcionário',true,20);
  }
}
js_trocacordeselect();
js_tabulacaoforms("form1","anofolha",true,1,"anofolha",true);
</script>
</html>