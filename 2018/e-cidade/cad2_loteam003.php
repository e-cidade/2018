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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_usuariosonline.php");
include("classes/db_lote_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clpostgresqlutils  = new PostgreSQLUtils;
$cllote             = new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo           = new rotulocampo;

$cllote->rotulo->label();
$clrotulo->label("z01_nome");

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {

  db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
  $db_botao = false;
  $db_opcao = 3;
} else {

  $db_botao = true;
  $db_opcao = 4;
}
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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
<form name="form1" method="post" action="cad2_loteam002.php" target="rel">
<center>
<table border="0">
  <tr><br>
    <td align="center">
      <strong>Opções:</strong>
      <?
        $aTipos = array("t" => "Com os tipos de débitos abaixo",
                        "f" => "Sem os tipos de débitos abaixo");
        db_select("tipos", $aTipos, true, $db_opcao);
      ?>
    </td>
  </tr>
  <tr>
    <td>
      <?
        $aux1 = new cl_arquivo_auxiliar;
        $aux1->cabecalho      = "<strong>Tipos de Débitos</strong>";
        $aux1->codigo         = "k00_tipo";
        $aux1->descr          = "k00_descr";
        $aux1->nomeobjeto     = 'arretipo';
        $aux1->funcao_js      = 'js_funcaotipo';
        $aux1->funcao_js_hide = 'js_funcaotipo1';
        $aux1->sql_exec       = "";
        $aux1->func_arquivo   = "func_arretipo.php";
        $aux1->nomeiframe     = "iframe_arretipo";
        $aux1->localjan       = "";
        $aux1->db_opcao       = $db_opcao;
        $aux1->top            = 0;
        $aux1->linhas         = 5;
        $aux1->vwhidth        = 600;
        $aux1->funcao_gera_formulario();
      ?>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input type="button" name="relat" value="Relatório" onClick="return imprime();"
             <?=($db_botao ? '' : 'disabled')?>>
    </td>
    <input type='hidden' name='quadra'>
    <input type='hidden' name='setor'>
    <input name='j14_comruas' type='hidden'>
    <input name='debit' type='hidden'>
    <input name='loteam' type='hidden'>
    <input name='qruas' type='hidden'>
    <input name='j34_loteam'  type='hidden'>
    <script>

      function imprime() {

        k00_tipo = ""
        vir = "";

        for(y=0;y<parent.iframe_g1.document.getElementById('arretipo').length;y++){
          k00_tipo += vir + parent.iframe_g1.document.getElementById('arretipo').options[y].value;
          vir = ",";
        }

        document.form1.debit.value = k00_tipo;
        j14_comruas = ""
        vir = "";

        for(y=0;y<parent.iframe_g5.document.getElementById('ruas').length;y++){
          j14_comruas += vir + parent.iframe_g5.document.getElementById('ruas').options[y].value;
          vir = ",";
        }

        document.form1.j14_comruas.value = j14_comruas;
        j34_loteam = ""
        vir = "";

        for(y=0;y<parent.iframe_g2.document.getElementById('loteam').length;y++){
          j34_loteam += vir + parent.iframe_g2.document.getElementById('loteam').options[y].value;
          vir = ",";
        }

        document.form1.j34_loteam.value = j34_loteam;

        jan = window.open('','rel',
                            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
        jan.moveTo(0,0);
        document.form1.submit();

        return false;
      }
    </script>
    </tr>
  <tr>
    <td align="center" colspan="4">
    <table border="0">
    <tr>
    <td nowrap width="50">
      <fieldset>
      <legend>
        <strong>Ordem: </strong>
      </legend>
      <?
        $aOrdem = array("matric"  => "Matrícula",
                        "nome"    => "Nome",
                        "tipodeb" => "Tipo de Débito");
        db_select("ordem", $aOrdem, true, $db_opcao);
      ?>
      </fieldset>
    </td>
    <td nowrap width="50">
      <fieldset>
      <legend>
        <strong>Tipo: </strong>
      </legend>
      <?
        $aResumido = array("f" => "Completo",
                           "t" => "Resumido");
        db_select("resumido", $aResumido, true, $db_opcao);
      ?>
      </fieldset>
    </td>
    <td nowrap width="50">
      <fieldset>
      <legend>
        <strong>Modo:</strong>
      </legend>
      <?
        $aOrder = array("asc"  => "Ascendente",
                        "desc" => "Descendente");
        db_select("order", $aOrder, true, $db_opcao);
      ?>
      </fieldset>
    </td>
    <td nowrap width="50">
      <fieldset>
      <legend>
        <strong>Débitos:</strong>
      </legend>
      <?
        $aDebito = array("td"  => "Todos os Débitos",
                         "dv"  => "Somente Débitos Vencidos",
                         "nv"  => "Somente Débitos Não Vencidos",
                         "sd"  => "Somente Sem Débitos");
        db_select("com_deb", $aDebito, true, $db_opcao);
      ?>
      </fieldset>
    </td>
  </tr>
  </table>
  </td>
</tr>
  </table>
  </center>
</form>
<script>
</script>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
function js_limpacampos(){
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type == 'text'){
      document.form1.elements[i].value = '';
    }
  }
}
</script>