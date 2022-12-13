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

  $geraform->manomes = false;
  $geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS

  $geraform->re1nome = "regisi";                  // NOME DO CAMPO DA MATRÍCULA INICIAL
  $geraform->re2nome = "regisf";                  // NOME DO CAMPO DA MATRÍCULA FINAL

  $geraform->trenome = "opcao";                   // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tipresumo = "Seleção";
  $geraform->tfinome = "filtro";                  // NOME DO CAMPO TIPO DE FILTRO

  $geraform->campo_auxilio_regi = "regiss";       // NOME DO DAS MATRÍCULAS SELECIONADAS

  $geraform->filtropadrao = "s";
  $geraform->resumopadrao = "m";

  $geraform->strngtipores = "m";                  // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO m - Matrícula,
  $geraform->onchpad      = true;                 // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form(null,null);
  ?>
  <tr>
    <td colspan='2' align='center'>
      <input type="button" name="processar" value="Processar" onclick="js_enviar_dados();">
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
function js_enviar_dados(){

  if(document.form1.selregist){
    valores = '';
    virgula = '';
    for(i=0; i < document.form1.selregist.length; i++){
      valores+= virgula+document.form1.selregist.options[i].value;
      virgula = ',';
    }
    document.form1.regiss.value = valores;
  }

  document.form1.action = "pes2_rhdeclaramatric002.php";
  document.form1.target = "safo";
  jan = window.open("","safo","width="+(screen.availWidth-5)+",height="+(screen.availHeight-40)+",scrollbars=0,location=0 ");
  setTimeout("document.form1.submit()",1000);

}
js_trocacordeselect();
</script>
</html>