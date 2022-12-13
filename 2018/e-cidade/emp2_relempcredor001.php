<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("classes/db_lote_classe.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("dbforms/db_classesgenericas.php"));
include(modification("classes/db_empempenho_classe.php"));

//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes
$clempempenho = new cl_empempenho;
$clselorcdotacao = new cl_selorcdotacao;
$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels
$clempempenho->rotulo->label();

//----
//----
$cllote = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;

$cllote->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

if (!isset($testdt)){
  $testdt='sem';
}

if (!isset($desdobramento)){
  $desdobramento = "true";
}

$anousu = db_getsession("DB_anousu");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>  &nbsp; </td>
  </tr>
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
       <form name="form1" method="post" action="">
       <table border="0" >
       <tr>
           <td align="center">
                <strong>Opções:</strong>
                <select name="ver">
                    <option name="condicao1" value="com">Com os credores selecionados</option>
                    <option name="condicao1" value="sem">Sem os credores selecionadas</option>
                </select>
          </td>
       </tr>
       <tr>
          <td nowrap width="50%">
               <?
                 // $aux = new cl_arquivo_auxiliar;
                 $aux->cabecalho = "<strong>Credores</strong>";
                 $aux->codigo = "e60_numcgm"; //chave de retorno da func
                 $aux->descr  = "z01_nome";   //chave de retorno
                 $aux->nomeobjeto = 'credor';
                 $aux->funcao_js = 'js_mostra';
                 $aux->funcao_js_hide = 'js_mostra1';
                 $aux->sql_exec  = "";
                 $aux->func_arquivo = "func_cgm_empenho.php";  //func a executar
                 $aux->nomeiframe = "db_iframe_cgm";
                 $aux->localjan = "";
                 $aux->onclick = "";
                 $aux->db_opcao = 2;
                 $aux->tipo = 2;
                 $aux->top = 1;
                 $aux->linhas = 4;
                 $aux->vwhidth = 400;
                 $aux->funcao_gera_formulario();
              ?>
          </td>
       </tr>
       </table>
   </center>
   </form>

    </td>
  </tr>
</table>
</div>
</body>
</html>