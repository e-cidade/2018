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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table  align="center">
  <form name="form1" method="post">
  <?
  if(!isset($tipores) || (isset($tipores) && trim($tipores) == "")){
    $tipores = "l";
  }

  if(!isset($tipofil) || (isset($tipofil) && trim($tipofil) == "")){
    $tipofil = "s";
  }

  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->usalota = true;                      // PERMITIR SELE��O DE LOTA��ES
  $geraform->lo1nome = "lotai";                   // NOME DO CAMPO DA LOTA��O INICIAL
  $geraform->lo2nome = "lotaf";                   // NOME DO CAMPO DA LOTA��O FINAL
  $geraform->campo_auxilio_lota = "mlotac";       // NOME DO DAS LOTA��ES SELECIONADAS
  $geraform->resumopadrao = "l";                  // TIPO DE RESUMO PADR�O
  $geraform->filtropadrao = "s";                  // TIPO DE FILTRO PADR�O
  $geraform->strngtipores = "gl";                 // OP��ES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
  $geraform->onchpad      = true;                 // MUDAR AS OP��ES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
//  $geraform->mbgerar = true;
  $geraform->relarqu = "pes2_aleconfipe002.php";
  $geraform->gera_form(db_anofolha(),db_mesfolha());
  ?>
  <tr>
    <td align='center' nowrap >
      <input type='button' name='gerar' id='gerar' onclick='js_gerar_consrel();' value='Processar dados'>
      <input type='button' name='gerar1' id='gerar1' onclick='js_gerar_consrel1();' value='Dif. Valores'>
    </td>
  </tr>
  </form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

function js_gerar_consrel1(){
  qry = '';
  qry += 'anofolha='+document.form1.anofolha.value;
  qry += '&mesfolha='+document.form1.mesfolha.value;
  qry += '&dif=s';
  jan = window.open('pes2_aleconfipe002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');		             

}


</script>