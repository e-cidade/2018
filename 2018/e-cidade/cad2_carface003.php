<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_classesgenericas.php");

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$clcaracter         = new cl_caracter;
$cliframe_seleciona = new cl_iframe_seleciona;
$clcaracter->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("j39_dtlan");
$clrotulo->label("j32_grupo");
$clrotulo->label("j32_descr");
echo "<script>parent.iframe_g2.location.href = 'cad2_carface004.php'</script>";
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class='body-default' onLoad="a=1" >

<div class='container'>

  <form name="form1" method="post" action="cad2_carface002.php" target="rel">
    <fieldset>
      <legend>Face de Quadra</legend>

      <table class='form-container'>
        <tr>
          <td>
            <?php
               $cliframe_seleciona->campos        = "j31_codigo, j31_descr, j32_descr";
               $cliframe_seleciona->legenda       = "QUE CONTENHAM ESTAS CARACTERÍSTICAS DE FACE";
               $cliframe_seleciona->sql           = $clcaracter->sql_query( "", "*", "j32_descr", "j32_tipo = 'F'" );
               $cliframe_seleciona->iframe_height = "150";
               $cliframe_seleciona->iframe_width  = "500";
               $cliframe_seleciona->iframe_nome   = "caracteristicas";
               $cliframe_seleciona->chaves        = "j31_codigo,j31_descr";
               $cliframe_seleciona->dbscript      = "onClick='parent.js_nome(this)'";
               $cliframe_seleciona->marcador      = true;
               $cliframe_seleciona->iframe_seleciona(@$db_opcao);
            ?>
          </td>
          <script>

            var x = false;

            function imprime() {

              j14_comruas = "";
              vir         = "";

              for( y = 0 ; y < parent.iframe_g5.document.getElementById('ruas').length; y++ ) {

                j14_comruas += vir + parent.iframe_g5.document.getElementById('ruas').options[y].value;
                vir          = ",";
              }

              document.form1.ruas.value = j14_comruas;

              return js_gera_chaves();
              return false;
            }

            function js_nome(obj) {

              if (obj.checked == true) {
                eval('parent.iframe_g2.ncaracteristicas.document.form1.'+obj.name+'.disabled = true');
              } else {
                eval('parent.iframe_g2.ncaracteristicas.document.form1.'+obj.name+'.disabled = false');
              }
            }
          </script>
        </tr>

      </table>


      <table class='form-container'>
        <tr>
          <td nowrap title="<?=@$Tj32_grupo?>">
            <?php
             db_ancora($Lj32_grupo,"js_pesquisagrupo(true)",1);
            ?>
          </td>
          <td colspan='3'>
            <?php
              db_input( 'j32_grupo', 10, $Ij32_grupo, true, 'text', 1, "onChange='js_pesquisagrupo(false)'" );
              db_input( 'j32_descr', 40, $Ij32_descr, true, 'text', 1, "" );
            ?>
            <input type='hidden' name='chaves_caract'>
            <input type='hidden' name='quadra'>
            <input type='hidden' name='setor'>
            <input type='hidden' name='ruas'>
            <input type='hidden' name='temruas'>
          </td>
        </tr>
        <tr>
          <td>
            <strong>Pontuação entre:</strong>
          </td>
          <td>
            <input type="text" name="pontini" id="pontuacaoInicial" />
            <strong>&nbsp;e&nbsp;</strong>
            <input type="text" name="pontfim" id="pontuacaoFinal" />
          </td>
        </tr>
        <tr>
          <td >
            <strong>Ordem: </strong>
          </td>
          <td >
            <select name="ordem" id="ordem">
              <option value="codigo">Rua</option>
              <option value="setor">Setor</option>
              <option value="quadra">Quadra</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <strong>Modo: </strong>
          </td>
          <td colspan='3'>
            <input type='radio' id='asc' name='order' checked value='asc'>
            <strong><label for="asc">Ascendente</label></strong>
            <input type='radio' id='desc' name='order' value='desc'>
            <strong><label for="desc">Descendente</label></strong>

          </td>
        </tr>
      </table>
    </fieldset>
    <input type="submit" name="relatorio1" value="Relatório" onClick="return imprime();">
  </form>

</div>
<script>

$("j32_grupo").addClassName("field-size2");
$("j32_descr").addClassName("field-size7");
$("pontuacaoInicial").addClassName("field-size2");
$("pontuacaoFinal").addClassName("field-size2");
$("ordem").setAttribute("rel","ignore-css");
$("ordem").style.width = "184px";

function js_pesquisagrupo(mostra) {

  if (mostra == true) {

    db_iframe_cargrup.jan.location.href = 'func_cargrup_rel.php?grupo=F&funcao_js=parent.js_mostracargrup1|0|1';
    db_iframe_cargrup.mostraMsg();
    db_iframe_cargrup.show();
    db_iframe_cargrup.focus();
  } else if (document.form1.j32_grupo.value != '') {
    db_iframe_cargrup.jan.location.href = 'func_cargrup_rel.php?grupo=F'
                                                             +'&pesquisa_chave='+document.form1.j32_grupo.value
                                                             +'&funcao_js=parent.js_mostracargrup';
  } else {
    document.form1.j32_descr.value = '';
  }
}

function js_mostracargrup(chave,erro) {

  document.form1.j32_descr.value = chave;

  if (erro == true) {

    document.form1.j32_grupo.focus();
    document.form1.j32_grupo.value = '';
  }
}

function js_mostracargrup1( chave1, chave2 ) {

  document.form1.j32_grupo.value = chave1;
  document.form1.j32_descr.value = chave2;
  db_iframe_cargrup.hide();
}

</script>

</body>
</html>
<?
$func_iframe                 = new janela( 'db_iframe_cargrup', '' );
$func_iframe->posX           = 1;
$func_iframe->posY           = 1;
$func_iframe->largura        = 780;
$func_iframe->altura         = 430;
$func_iframe->titulo         = 'Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>