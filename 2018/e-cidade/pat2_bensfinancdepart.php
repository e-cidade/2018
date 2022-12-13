<?php
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
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, estilos.css, prototype.js, arrays.js");
?>
</head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <form name='form1'>
    <center>
      <table>
         <tr>
            <td>
              <fieldset>
                <legend>
                  <b>Departamentos</b>
                </legend>
                <table>
                  <tr>
                    <td>
                    </td>
                    <td>
                       <table>
                         <?
                         $oListaDepartamento = new cl_arquivo_auxiliar;
                         $oListaDepartamento->cabecalho = "<strong>Departamentos</strong>";
                         $oListaDepartamento->codigo = "coddepto"; //chave de retorno da func
                         $oListaDepartamento->descr  = "descrdepto"; //chave de retorno
                         $oListaDepartamento->nomeobjeto = 'Departamento';
                         $oListaDepartamento->funcao_js = 'js_mostraDepartamento';
                         $oListaDepartamento->funcao_js_hide = 'js_mostraDepartamento1';
                         $oListaDepartamento->sql_exec  = "";
                         $oListaDepartamento->func_arquivo = "func_db_departorg.php";  //func a executar
                         $oListaDepartamento->nomeiframe = "db_iframe_orcdepartamento";
                         $oListaDepartamento->nome_botao = "lancarDepartamento";
                         $oListaDepartamento->localjan = "";
                         $oListaDepartamento->onclick                     ="";
                         $oListaDepartamento->db_opcao = 2;
                         $oListaDepartamento->tipo = 2;
                         $oListaDepartamento->top = 0;
                         $oListaDepartamento->linhas = 5;
                         $oListaDepartamento->obrigarselecao = false;
                         $oListaDepartamento->vwhidth = '100%';
                         $oListaDepartamento->funcao_gera_formulario();
                        ?> 
                       </table>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <b>Opções:</b>
                    </td>
                    <td>
                        <?
                          $aOpcoes = array(
                                          "comdepartamento" => "Com os departamentos selecionados",
                                          "semdepartamento" => "sem os departamentos selecionados",
                                          ); 
                         db_select("opcoesdepartamento", $aOpcoes, true,1);                 
                        ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
         </tr>
      </table>      
    </center>
    </form>
  <table>
  </body>
</html>
<script>
function js_BuscaDadosArquivoDepartamento(chave){
  document.form1.lancarDepartamento.onclick = '';
  var sFiltrosOrgao   = parent.iframe_orgao.js_campo_recebe_valores();
  var sFiltrosUnidade = parent.iframe_unidade.js_campo_recebe_valores();
  if(chave){

    var sQuery        = 'funcao_js=parent.js_mostraDepartamento|coddepto|descrdepto'; 
    sQuery += '&orgaos='+sFiltrosOrgao;
    sQuery += '&unidades='+sFiltrosUnidade;
    js_OpenJanelaIframe('','db_iframe_orcdepartamento','func_db_departorg.php?'+sQuery,'Departamentos',true);
    
  }else{
    
    var sQuery = '&orgaos='+sFiltrosOrgao;
    sQuery    += '&unidades='+sFiltrosUnidade;
    js_OpenJanelaIframe('','db_iframe_orcdepartamento',
                        'func_db_departorg.php?pesquisa_chave='+document.form1.coddepto.value+
                        '&funcao_js=parent.js_mostraDepartamento1'+sQuery,
                        'Departamentos', false);
  }
}
</script>