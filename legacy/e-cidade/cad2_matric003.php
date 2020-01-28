<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_setor_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
require_once(modification("classes/db_sanitario_classe.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsetor = new cl_setor;
$cliframe_seleciona = new cl_iframe_seleciona;
$clsetor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<script>
  function js_imprime() {

    jan = window.open('','rel','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);
    document.form1.action = "cad2_matric002.php";
    document.form1.target = "rel";
    document.form1.submit();
  }

  function js_nome(obj){

    j34_setor = "";
    vir = "";
    x = 0;
    for(i=0;i<setor.document.form1.length;i++) {

      if(setor.document.form1.elements[i].type == "checkbox") {

        if(setor.document.form1.elements[i].checked == true) {

          valor      = setor.document.form1.elements[i].value.split("_")
          j34_setor += vir + valor[0];
          vir        = ",";
          x += 1;
        }
      }
    }

    parent.iframe_g2.location.href = '../cad2_matric004.php?j34_setor='+j34_setor;
    parent.iframe_g1.document.form1.setorParametro.value  = j34_setor;
    parent.iframe_g1.document.form1.quadraParametro.value = '';
    parent.iframe_g1.document.form1.loteParametro.value   = '';
  }

</script>

<body class="body-default">
  <div class="container">
    <form name="form1" method="post">
      <table border="0" align='center'>
        <tr>
          <td align="top" align='center' colspan="5">
            <?php
               $cliframe_seleciona->campos        = "j30_codi,j30_descr";
               $cliframe_seleciona->legenda       = "Setor";
               $cliframe_seleciona->sql=$clsetor->sql_query(""," * ","j30_codi");
               $cliframe_seleciona->textocabec    = "darkblue";
               $cliframe_seleciona->textocorpo    = "black";
               $cliframe_seleciona->fundocabec    = "#aacccc";
               $cliframe_seleciona->fundocorpo    = "#ccddcc";
               $cliframe_seleciona->iframe_height = "250";
               $cliframe_seleciona->iframe_width  = "700";
               $cliframe_seleciona->iframe_nome   = "setor";
               $cliframe_seleciona->chaves        = "j30_codi,j30_descr";
               $cliframe_seleciona->dbscript      = "onClick='parent.js_nome(this)'";
               $cliframe_seleciona->marcador      = true;
               $cliframe_seleciona->js_marcador   = "parent.js_nome()";
               $cliframe_seleciona->alignlegenda  = "left";
               $cliframe_seleciona->iframe_seleciona(@$db_opcao);
            ?>
          </td>
        </tr>
        </table >
          <?
            db_input('setorParametro',"",0,true,'hidden',3,"");
            db_input('quadraParametro',"",0,true,'hidden',3,"");
            db_input('loteParametro',"",0,true,'hidden',3,"");
          ?>
        <fieldset>
          <legend>Opções</legend>
        <table border="0" align='center'>
        <tr>
	        <td colspan=2 align='right' >
	          <strong>Tipo Imóvel:</strong>
	        </td>
          <td colspan=3 align='left' >
	          <?php

	            $tipo_t = array("T"=>"Todos","B"=>"Territorial","P"=>"Predial");
	            db_select("terreno",$tipo_t,true,2);
	          ?>
	        </td>
        </tr>
        <tr>
	        <td colspan=2 align='right' >
            <strong>Listar: </strong>
          </td>
          <td colspan=3 align='left' >
	          <select name="process" id="process">
	            <option value="T" selected>Todos</option>
	            <option value="S">Baixados</option>
	            <option value="N">Não baixados</option>
	          </select>
          </td>
        </tr>
        <tr>
	        <td colspan=2 align='right' >
	          <strong>Mostrar Endereço:</strong>
	        </td>
          <td colspan=3 align='left' >
	          <?php
	            $tipo_m = array("n"=>"Não","s"=>"Sim");
	            db_select("mostra",$tipo_m,true,2);
	          ?>
	        </td>
        </tr>
        </table>
        </fieldset>
        <table align="center">
        <tr>
          <td colspan='5' align='center'>
	          <input type="submit" name="relatorio1" value="Gerar relatório" onClick="return js_imprime();">
          </td>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>