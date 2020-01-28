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
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_gerar(){
  js_gerar_consrel();
  qry = "?" + document.form1.valores_campos_rel.value;
  if(document.form1.formaimpr.value == "s"){
    jan = window.open("pes3_conspessoal_impressao.php" + qry + "&consulta=false","","width="+(screen.availWidth-5)+",height="+(screen.availHeight-40)+",scrollbars=1,location=0");
  }else{
    jan = window.open("pes2_funclocaltrab002.php" + qry,"","width="+(screen.availWidth-5)+",height="+(screen.availHeight-40)+",scrollbars=1,location=0");
  }
  jan.moveTo(0,0);
}
</script>
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
  if(!isset($tipo)){
    $tipo = "t";
  }
  if(!isset($filtro)){
    $filtro = "i";
  }
  if(!isset($anofolha) || (isset($anofolha) && trim($anofolha) == "")){
    $anofolha = db_anofolha();
  }
  if(!isset($mesfolha) || (isset($mesfolha) && trim($mesfolha) == "")){
    $mesfolha = db_mesfolha();
  }
  if(!isset($formaimpr)){
    $formaimpr = "n";
  }
  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->usaloca = true;                          // PERMITIR SELEÇÃO DE LOCAL DE TRABALHO
  $geraform->selecao = true;                          // PERMITIR SELEÇÃO DE LOCAL DE TRABALHO

  $geraform->tr1nome = "locali";                      // NOME DO CAMPO DO LOCAL INICIAL
  $geraform->tr2nome = "localf";                      // NOME DO CAMPO DO LOCAL FINAL
  $geraform->tr3nome = "selloc";                      // NOME DO CAMPO DE SELEÇÃO DE LOCAIS

  $geraform->trenome = "tipo";                        // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "filtro";                      // NOME DO CAMPO TIPO DE FILTRO
  $geraform->atinpen = true;

  $geraform->resumopadrao = "t";                      // NOME DO DAS LOTAÇÕES SELECIONADAS
  $geraform->filtropadrao = "i";                      // NOME DO DAS LOTAÇÕES SELECIONADAS

  $geraform->strngtipores = "gt";                     // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                      //                                       l - lotação,
                                                      //                                       o - órgão,
                                                      //                                       m - matrícula,
                                                      //                                       t - local de trabalho

  $geraform->campo_auxilio_loca = "faixa_local";      // NOME DO DOS LOCAIS SELECIONADOS
  $geraform->mbgerar = true;
  $geraform->onchpad = true;                          // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->qbrapag = true;
  $geraform->mostord = true;
  $geraform->tipordem = "Imprimir cadastro de funcionário";
  $geraform->mornome  = "formaimpr";
  $geraform->arr_mostord = Array("s"=>"Sim", "n"=>"Não");
  $geraform->jsgerar = "js_gerar()";
  $geraform->gera_form($anofolha,$mesfolha);
  ?>
  </form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>