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
include("classes/db_cgm_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <center>
   <form name="form1" method="post">
    <table style="margin-top: 20px;">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Selecionar Contratado</b>
            </legend>
            <table>
              <tr>
                <td>
                   <?
                     $aux = new cl_arquivo_auxiliar;
                     $aux->cabecalho = "<strong>Contratado Selecionados</strong>";
                     $aux->codigo = "z01_numcgm";
                     $aux->descr  = "z01_nome";
                     $aux->nomeobjeto = 'listacontratado';
                     $aux->funcao_js = 'js_mostra';
                     $aux->funcao_js_hide = 'js_mostra1';
                     $aux->sql_exec  = "";
                     $aux->func_arquivo = "func_nome.php";
                     $aux->nomeiframe = "db_iframe_nome";
                     $aux->localjan = "";
                     $aux->onclick = "";
                     $aux->db_opcao = 2;
                     $aux->tipo = 2;
                     $aux->top = 1;
                     $aux->linhas = 10;
                     $aux->vwhidth = 200;
                     $aux->funcao_gera_formulario();
                   ?> 
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
    </table>
   </form>
  </center>
</body>
</html>
<script>
function js_mostra(chave,chave1) {

  document.form1.z01_numcgm.value = chave;
  document.form1.z01_nome.value   = chave1;
  
  db_iframe_nome.hide();
  document.form1.db_lanca.onclick = js_insSelectlistacontratado;
}

function js_mostra1(chave,chave1) {

  document.form1.z01_nome.value = chave1;
  if (chave) {
  
    document.form1.z01_numcgm.value = '';
    document.form1.z01_numcgm.focus();
  } else {
    document.form1.db_lanca.onclick = js_insSelectlistacontratado;
  }
  
  db_iframe_nome.hide();
}
</script>