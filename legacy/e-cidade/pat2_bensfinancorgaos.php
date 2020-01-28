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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_app.utils.php");
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
                  <b>Orgãos</b>
                </legend>
                <table>
                  <tr>
                    <td>
                    </td>
                    <td>
                       <table>
                         <?
                         $oListaOrgao = new cl_arquivo_auxiliar;
                         $oListaOrgao->cabecalho = "<strong>Orgãos</strong>";
                         $oListaOrgao->codigo = "o40_orgao"; //chave de retorno da func
                         $oListaOrgao->descr  = "o40_descr"; //chave de retorno
                         $oListaOrgao->nomeobjeto = 'orgao';
                         $oListaOrgao->funcao_js = 'js_mostraorgao';
                         $oListaOrgao->funcao_js_hide = 'js_mostraorgao1';
                         $oListaOrgao->sql_exec  = "";
                         $oListaOrgao->func_arquivo = "func_orcorgao.php";  //func a executar
                         $oListaOrgao->nomeiframe = "db_iframe_orcorgao";
                         $oListaOrgao->nome_botao = "lancarorgao";
                         $oListaOrgao->localjan = "";
                         $oListaOrgao->onclick                     ="";
                         $oListaOrgao->db_opcao = 2;
                         $oListaOrgao->tipo = 2;
                         $oListaOrgao->top = 0;
                         $oListaOrgao->linhas = 5;
                         $oListaOrgao->vwhidth = '100%';
                         $oListaOrgao->obrigarselecao = false;
                         $oListaOrgao->funcao_gera_formulario();
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
                                          "comorgao" => "Com os Orgãos selecionados",
                                          "semorgao" => "sem os Orgãos selecionados",
                                          ); 
                         db_select("opcoesorgaos", $aOpcoes, true,1);                 
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