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

/**
 *  Criado por Matheus Felini
 * 
 *  Este arquivo tem como objetivo único ser apresentado como iframe dentro da busca de rubricas
 *  na rotina abaixo.
 * 
 *  RECURSOS HUMANOS > PESSOAL > PROCEDIMENTOS > DIFERENÇAS > PROCESSA DIFERENÇA DE SALARIO
 * 
 *  Opção: Processar Diferenças Por: (Rubricas) -> Botão Configurar 
 */


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_rhrubricas_classe.php");
?>

<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
   
    <?
      db_app::load("scripts.js, prototype.js, widgets/dbtextField.widget.js");
      db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js, widgets/dbtextField.widget.js");
      db_app::load("widgets/dbcomboBox.widget.js, estilos.css, grid.style.css");
    ?>
  </head>
  
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="" >
<?php


$oFormRhRubrica = new cl_arquivo_auxiliar();

$oFormRhRubrica->cabecalho      = "<strong>Configurar Rubricas</strong>";
$oFormRhRubrica->codigo         = "rh27_rubric"; //chave de retorno da func
$oFormRhRubrica->descr          = "rh27_descr";   //chave de retorno
$oFormRhRubrica->nomeobjeto     = 'rhrubricas';
$oFormRhRubrica->funcao_js      = 'js_mostra_rhRubricas';
$oFormRhRubrica->funcao_js_hide = 'js_mostra_rhRubricas1';
$oFormRhRubrica->sql_exec       = "";
$oFormRhRubrica->func_arquivo   = "func_rhrubricas.php";  //func a executar
$oFormRhRubrica->nomeiframe     = "db_iframe_rhrubricas";
$oFormRhRubrica->localjan       = "";
$oFormRhRubrica->db_opcao       = 2;
$oFormRhRubrica->tipo           = 2;
$oFormRhRubrica->top            = 0;
$oFormRhRubrica->linhas         = 10;
$oFormRhRubrica->vwidth         = 400;
$oFormRhRubrica->nome_botao     = 'db_lanca';
$oFormRhRubrica->fieldset       = false;

echo "<form name='form1'>";
echo "<table align='center'>";
$oFormRhRubrica->funcao_gera_formulario();   
echo "</table>";
echo "</form>";
?>
<p align="center"><input type="button" onClick='js_retornoRhRubricas();' name="btnConfigRhRubricas" id="btnConfigRhRubricas" value="Selecionar Rubricas" ></p>

</body>
</html>

<script>

function js_retornoRhRubricas() {
  
  var iTotalRhRubricas   = $('rhrubricas').length;
  var sValueRhRubricas   = $('rhrubricas');
  var sRetornoRhRubricas = "";
  
  for (var i = 0; i < iTotalRhRubricas ; i++) {
    
    if ( sRetornoRhRubricas == '' ) {
      sRetornoRhRubricas += sValueRhRubricas[i].value;
    } else {
      sRetornoRhRubricas += ","+sValueRhRubricas[i].value;
    }
    
  }
  
  parent.$('aConfRubrica').value = sRetornoRhRubricas;
  alert("Rubricas configuradas!");
  parent.oWindowConfRubrica.hide();
}

</script>