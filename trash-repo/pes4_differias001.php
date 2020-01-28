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
  <form name="form1" method="post" action="">
  <?

  if(!isset($opcao_gml)){
    $opcao_gml = "m";
  }
  if(!isset($opcao_filtro)){
    $opcao_filtro = "s";
  }

  include("dbforms/db_classesgenericas.php");
  $geraform = new cl_formulario_rel_pes;

  $geraform->manomes = false;                     // PARA N�O MOSTRAR ANO E MES DE COMPET�NCIA DA FOLHA

  $geraform->usaregi = true;                      // PERMITIR SELE��O DE MATR�CULAS

  $geraform->re1nome = "r110_regisi";             // NOME DO CAMPO DA MATR�CULA INICIAL
  $geraform->re2nome = "r110_regisf";             // NOME DO CAMPO DA MATR�CULA FINAL
	
  $geraform->trenome = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
  $geraform->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO

  $geraform->filtropadrao = "s";                  // TIPO DE FILTRO PADR�O
  $geraform->resumopadrao = "m";                  // TIPO DE RESUMO PADR�O

  $geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATR�CULAS SELECIONADAS

  $geraform->strngtipores = "gm";                // OP��ES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
                                                  //                                       m - Matr�cula,
                                                  //                                       r - Resumo
  $geraform->testarescisaoregi = "ra";
  $geraform->onchpad      = true;                // MUDAR AS OP��ES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
  $geraform->gera_form(null,null);
  ?>
  <tr>
    <td colspan='2' align='center'>
      <input type="submit" name="processar" value="Processar" onclick="return js_enviar_dados();">
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
    document.form1.faixa_regis.value = valores;
    document.form1.selregist.selected = 0;
  }  
  document.form1.action = 'pes4_differias002.php';
  return true;

}
js_trocacordeselect();
</script>
</html>