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
                  <b>Unidade</b>
                </legend>
                <table>
                  <tr>
                    <td>
                    </td>
                    <td>
                       <table>
                         <?
                          $oListaDivisao = new cl_arquivo_auxiliar;
                           $oListaDivisao->cabecalho = "<strong>Divisoes</strong>";
                           $oListaDivisao->codigo = "t30_codigo"; //chave de retorno da func
                           $oListaDivisao->descr  = "t30_descr"; //chave de retorno
                           $oListaDivisao->nomeobjeto = 'Divisao';
                           $oListaDivisao->funcao_js = 'js_mostraDivisao';
                           $oListaDivisao->funcao_js_hide = 'js_mostraDivisao1';
                           $oListaDivisao->sql_exec  = "";
                           $oListaDivisao->func_arquivo = "func_departdiv.php";  //func a executar
                           $oListaDivisao->nomeiframe = "db_iframe_divisao";
                           $oListaDivisao->localjan = "";
                           $oListaDivisao->nome_botao = "lancarDivisao";
                           $oListaDivisao->onclick                     ="";
                           $oListaDivisao->db_opcao = 2;
                           $oListaDivisao->tipo = 2;
                           $oListaDivisao->top = 0;
                           $oListaDivisao->linhas = 5;
                           $oListaDivisao->vwhidth = '100%';
                           $oListaDivisao->obrigarselecao = false;
                           $oListaDivisao->funcao_gera_formulario();
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
                                          "comdivisao" => "Com as Divisões  selecionadas",
                                          "semdivisao" => "sem as Divisões selecionadas",
                                          ); 
                         db_select("opcoesdivisao", $aOpcoes, true,1);                 
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

  function js_BuscaDadosArquivoDivisao(chave){
  document.form1.lancarDivisao.onclick = '';
  var sFiltrosOrgao         = parent.iframe_orgao.js_campo_recebe_valores();
  var sFiltrosUnidade       = parent.iframe_unidade.js_campo_recebe_valores();
  var sFiltrosDepartamentos = parent.iframe_departamento.js_campo_recebe_valores();
  if(chave){

    var sQuery        = 'funcao_js=parent.js_mostraDivisao|t30_codigo|t30_descr'; 
    sQuery += '&orgaos='+sFiltrosOrgao;
    sQuery += '&unidades='+sFiltrosUnidade;
    sQuery += '&departamentos='+sFiltrosDepartamentos;
    js_OpenJanelaIframe('','db_iframe_divisao','func_departdiv.php?'+sQuery,'Departamentos',true);
    
  }else{
    
    var sQuery = '&orgaos='+sFiltrosOrgao;
    sQuery    += '&unidades='+sFiltrosUnidade;
    sQuery    += '&departamentos='+sFiltrosDepartamentos;
    js_OpenJanelaIframe('','db_iframe_divisao',
                        'func_departdiv.php?pesquisa_chave='+document.form1.t30_codigo.value+
                        '&funcao_js=parent.js_mostraDivisao1'+sQuery,
                        'Departamentos', false);
  }
}
</script>