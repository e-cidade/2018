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
require_once(modification("classes/db_lote_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllote							= new cl_lote;
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo						= new rotulocampo;
$cllote->rotulo->label();
$clrotulo->label("z01_nome");
if(isset($j37_quadra) && $j37_quadra != "") {

  $quadra = split(",",$j37_quadra);
  $vir = "";
  $qua = "";
  for($i=0;$i<count($quadra);$i++) {

    $qua .= $vir."'".$quadra[$i]."'";
    $vir = ",";
  }
}

if(isset($j34_setor) && $j34_setor != "") {

  $setor = split(",",$j34_setor);
  $vir = "";
  $set = "";
  for($i=0;$i<count($setor);$i++) {

    $set .= $vir."'".$setor[$i]."'";
    $vir = ",";
  }
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
<script>

  function js_loadVars(iInicio,iFinal) {

	  var oColection = lote.document.getElementsByTagName('input');
		for(var i = iInicio; i <  iFinal  ; i++ ) {

      if(oColection[i].type == "checkbox") {

			  if(oColection[i].checked == true) {

          var valor = lote.document.form1.elements[i].value.split("_");

          setorParametro  += vir + valor[0];
          quadraParametro += vir + valor[1];
		  		loteParametro   += vir + valor[2];
		  		vir = ",";
        }
      }
    }

	  if (i == aCol.length) {

		  parent.iframe_g1.document.form1.loteParametro.value   = loteParametro;

      if ( !empty(parent.iframe_g1.document.form1.setorParametro.value) ) {
        parent.iframe_g1.document.form1.setorParametro.value  = setorParametro;
      }

      if ( !empty(parent.iframe_g1.document.form1.quadraParametro.value) ) {
        parent.iframe_g1.document.form1.quadraParametro.value = quadraParametro;
      }

		  js_removeObj("msgbox");
	  }

    if ( empty(loteParametro) ) {
      parent.iframe_g2.js_nome(this);
    }
  }


  function js_nome(obj) {

    js_divCarregando('Aguarde, efetuando pesquisa ....','msgbox');
    setorParametro = "";
    quadraParametro= "";
	  loteParametro  = "";
    vir            = "";
	  iQuantidade		 = 0;
    aCol					 = lote.document.getElementsByTagName('input');
	  iTamanhoVoltas = ( aCol.length / 10);
	  iResto				 = ( aCol.length % 10);
	  iTamanhoVoltas = Math.floor(iTamanhoVoltas);
	  if ( iTamanhoVoltas > 500 ) {

		  for ( var ii = 0 ; ii < 10; ii++ ) {

		  	tmp = setTimeout("js_loadVars("+iQuantidade+","+( ii==9?eval(iQuantidade+iResto+iTamanhoVoltas):eval(iQuantidade+iTamanhoVoltas))+")",2000);
		  	iQuantidade = eval(iQuantidade+iTamanhoVoltas);
		  }
	  } else {

		  js_loadVars(0,aCol.length);
	  }
  }

</script>
<body class="body-default">
  <div class="container">
		<form name="form1" method="post" action="cad2_matric005.php" target="">
		  <table border="0">
				<tr>
					<td align="top" colspan="2">
            <?php
              if(isset($j37_quadra)&& $j37_quadra!="") {

                $sql = 	$cllote->sql_query("","distinct j34_setor,j34_quadra,j34_lote","j34_setor,j34_quadra,j34_lote","j34_quadra in ($qua) and j34_setor in ($set)");
								$cliframe_seleciona->campos  = "j34_setor,j34_quadra,j34_lote";
                $cliframe_seleciona->legenda="Lote";
                $cliframe_seleciona->sql=$sql;
                $cliframe_seleciona->textocabec ="darkblue";
                $cliframe_seleciona->textocorpo ="black";
                $cliframe_seleciona->fundocabec ="#aacccc";
                $cliframe_seleciona->fundocorpo ="#ccddcc";
                $cliframe_seleciona->iframe_height ="250";
                $cliframe_seleciona->iframe_width ="700";
                $cliframe_seleciona->iframe_nome ="lote";
                $cliframe_seleciona->chaves ="j34_setor,j34_quadra,j34_lote";
                $cliframe_seleciona->dbscript ="onClick='parent.js_nome(this)'";
                $cliframe_seleciona->js_marcador="parent.js_nome()";
                $cliframe_seleciona->alignlegenda  = "left";
                $cliframe_seleciona->iframe_seleciona(@$db_opcao);
							} else {

								echo "<br><strong>SELECIONE UMA QUADRA PARA ESCOLHER O(S) LOTES(S)</strong>";
						  }
					  ?>
				  </td>
			  </tr>
				<tr>
					<td>
						<input type="hidden" name="j37_quadra">
						<input type="hidden" name="j34_setor" value="<?@$j34_setor?>">
					</td>
			  </tr>
			</table>
	  </form>
  </div>
</body>
</html>