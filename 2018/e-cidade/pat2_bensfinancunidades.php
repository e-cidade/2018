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
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
   <form name='form1'>
    <center>
      <table>
         <tr>
            <td>
              <fieldset>
                <legend>
                  <b>Unidade</b>
                </legend>
                <table>
                  <tr>
                    <td>
                    </td>
                    <td>
                       <table>
                         <?
                         $oListaUnidade = new cl_arquivo_auxiliar;
                         $oListaUnidade->cabecalho = "<strong>Unidades</strong>";
                         $oListaUnidade->codigo = "o41_unidade"; //chave de retorno da func
                         $oListaUnidade->descr  = "o41_descr"; //chave de retorno
                         $oListaUnidade->nomeobjeto = 'Unidade';
                         $oListaUnidade->funcao_js = 'js_mostraUnidade';
                         $oListaUnidade->funcao_js_hide = 'js_mostraUnidade1';
                         $oListaUnidade->sql_exec  = "";
                         $oListaUnidade->func_arquivo = "func_orcunidade_bens.php";  //func a executar
                         $oListaUnidade->nomeiframe = "db_iframe_orcunidade";
                         $oListaUnidade->localjan = "";
                         $oListaUnidade->nome_botao = "lancarunidade";
                         $oListaUnidade->onclick                     ="";
                         $oListaUnidade->db_opcao = 2;
                         $oListaUnidade->tipo = 2;
                         $oListaUnidade->top = 0;
                         $oListaUnidade->linhas = 5;
                         $oListaUnidade->obrigarselecao = false;
                         $oListaUnidade->vwhidth = '100%';
                         $oListaUnidade->funcao_gera_formulario();
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
                                          "comunidade" => "Com as Unidades selecionados",
                                          "semunidade" => "sem as Unidades selecionados",
                                          ); 
                         db_select("opcoesunidade", $aOpcoes, true,1);                 
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


function js_BuscaDadosArquivoUnidade(chave){
  document.form1.lancarunidade.onclick = '';
  var sFiltrosOrgao = parent.iframe_orgao.js_campo_recebe_valores();
  if(chave){
    var sQuery        = 'funcao_js=parent.js_mostraUnidade|o41_unidade|o41_descr'; 
    if (sFiltrosOrgao != "") {
      sQuery += '&orgaos='+sFiltrosOrgao;
    }
    js_OpenJanelaIframe('','db_iframe_orcunidade','func_orcunidade_bens.php?'+sQuery,'Orgaos',true);
  }else{
    
    var sQuery = '&orgaos='+sFiltrosOrgao;
    js_OpenJanelaIframe('','db_iframe_orcunidade',
                        'func_orcunidade_bens.php?pesquisa_chave='+document.form1.o41_unidade.value+
                        '&funcao_js=parent.js_mostraUnidade1'+sQuery,
                        'Pesquisa', false);
  }
}
  
</script>