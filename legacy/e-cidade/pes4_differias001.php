<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("dbforms/db_funcoes.php"));
db_postmemory($HTTP_POST_VARS);
if(!isset($opcao_gml)){
  $opcao_gml = "m";
}
if(!isset($opcao_filtro)){
  $opcao_filtro = "s";
}

include(modification("dbforms/db_classesgenericas.php"));
$geraform = new cl_formulario_rel_pes;

$geraform->manomes = false;                     // PARA NÃO MOSTRAR ANO E MES DE COMPETÊNCIA DA FOLHA

$geraform->usaregi = true;                      // PERMITIR SELEÇÃO DE MATRÍCULAS

$geraform->re1nome = "r110_regisi";             // NOME DO CAMPO DA MATRÍCULA INICIAL
$geraform->re2nome = "r110_regisf";             // NOME DO CAMPO DA MATRÍCULA FINAL

$geraform->trenome = "opcao_gml";               // NOME DO CAMPO TIPO DE RESUMO
$geraform->tfinome = "opcao_filtro";            // NOME DO CAMPO TIPO DE FILTRO

$geraform->filtropadrao = "s";                  // TIPO DE FILTRO PADRÃO
$geraform->resumopadrao = "m";                  // TIPO DE RESUMO PADRÃO

$geraform->campo_auxilio_regi = "faixa_regis";  // NOME DO DAS MATRÍCULAS SELECIONADAS

$geraform->strngtipores = "gm";                // OPÇÕES PARA MOSTRAR NO TIPO DE RESUMO g - geral,
//                                       m - Matrícula,
//                                       r - Resumo
$geraform->testarescisaoregi = "ra";
$geraform->onchpad      = true;                // MUDAR AS OPÇÕES AO SELECIONAR OS TIPOS DE FILTRO OU RESUMO
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body >
    <form name="form1" method="post" action="" class="container">
      <fieldset>
        <legend>Diferença de Férias:</legend>
        <table width="60%" border="0" cellspacing="4" cellpadding="0" class="form-container">
          <?php $geraform->gera_form(null,null); ?>
        </table>
      </fieldset>
      <input type="submit" name="processar" value="Processar" onclick="return js_enviar_dados();">
    </form>
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
