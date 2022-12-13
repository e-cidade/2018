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
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if (file_exists(base64_decode($arquivo))) {

  require(modification(base64_decode($arquivo)));
} else {
  echo "<script>parent.document.form1.submit();</script>Redirecionando . . .";
  exit;
}
$clrotulo = new rotulocampo;
$sql=base64_decode($sql);
if (isset($sql_disabled)) {
  $sql_disabled=base64_decode($sql_disabled);
  $result01=db_query($sql_disabled);
  $numrows01=pg_numrows($result01);
}
$campos=base64_decode($campos);
$msg_vazio=base64_decode($msg_vazio);
$quais_chaves = split("#",$quais_chaves);
$sql_comparar     = base64_decode((isset($sql_comparar)?$sql_comparar:""));
$sql_servico      = base64_decode((isset($sql_servico)?$sql_servico:""));
$sql_reservasaldo = base64_decode((isset($sql_reservasaldo)?$sql_reservasaldo:""));
$campos_comparar  = base64_decode((isset($campos_comparar)?$campos_comparar:""));

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../estilos.css"/>
<style>
a:hover {
  color:blue;
}
a:visited {
  color: black;
  font-weight: bold;
}
a:active {
  color: black;
  font-weight: bold;
}
.cabec {
  text-align: left;
  font-size: <?=$tamfontecabec?>;
  color: <?=$textocabec?>;
  background-color:<?=$fundocabec?>;
  border:1px solid $FFFFFF;
}
<?
if (isset($sql_comparar) && $sql_comparar != "") {
  ?>

  .corpo_erro {
    font-size: <?=$tamfontecorpo?>;
    color: <?=$textocorpo?>;
    background-color:#CD5C5C;
  }
  <?
}
?>
.corpo {
  font-size: <?=$tamfontecorpo?>;
  color: <?=$textocorpo?>;
  background-color:<?=$fundocorpo?>;
}

</style>

<script>
function js_retorna(qtipo,<? $virgula = "";
reset($quais_chaves);
for ($ww=0; $ww<sizeof($quais_chaves); $ww++) {
  echo $virgula."par_$ww";
  $virgula = ",";
  next($quais_chaves);
}
?>){
  var opcao = parent.document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","opcao");
  opcao.setAttribute("value",qtipo);
  parent.document.form1.appendChild(opcao);

  <?
  reset($quais_chaves);
  for ($ww=0; $ww<sizeof($quais_chaves); $ww++) {
    ?>
    var chavepri = parent.document.createElement("input");
    chavepri.setAttribute("type","hidden");
    chavepri.setAttribute("name","<?=$quais_chaves[$ww]?>");
    chavepri.setAttribute("value",par_<?=$ww?>);
    parent.document.form1.appendChild(chavepri);
    <?
    next($quais_chaves);
  }
  ?>
  parent.document.form1.submit();
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post">
<center>
<table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
<tr>
<td align="center" valign="top">
<table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px" class="tabela_iframe_alterar_excluir">
<?
$result=@db_query($sql);
$numrows=@pg_numrows($result);
$numcols=@pg_numfields($result);
$flag_achou = false;
$id_reg     = -1;

if (isset($sql_comparar) && $sql_comparar != "") {
  $res_servico          = @db_query($sql_servico);
  $numrows_servico      = @pg_numrows($res_servico);
  $numcols_servico      = @pg_numfields($res_servico);

  $res_reservasaldo     = @db_query($sql_reservasaldo);
  $numrows_reservasaldo = @pg_numrows($res_reservasaldo);
  $numcols_reservasaldo = @pg_numfields($res_reservasaldo);

  $res_comparar         = @db_query($sql_comparar);
  $numrows_comparar     = @pg_numrows($res_comparar);
  $numcols_comparar     = @pg_numfields($res_comparar);

  $matriz_comparar      = split(",",$campos_comparar);
  $numcol_comparar      = sizeof($matriz_comparar);
  $linha = 0;
  for ($i = 0; $i < $numrows_servico; $i++) {

    db_fieldsmemory($res_servico,$i);
    $total_dotacao = 0;
    $total_reserva = 0;
    $lVerificar    = false;
    for ($ii = $linha; $ii < $numrows_reservasaldo; $ii++) {
      db_fieldsmemory($res_reservasaldo,$ii);

      if ($codsol == $pc11_codigo_reserva) {

        $total_dotacao += $valordotacao;
        $total_reserva += $valorreserva;
        $linha = $ii;
        $linha++;
        $lVerificar = true;
      } else {
        //$lVerificar = false;
        break;
      }
    }
    if (($total_reserva != $total_dotacao || $total_dotacao = 0)) {

      if ($total_reserva > 0 || $total_dotacao > 0) {
        if (count(@$matriz_errados) == 0) {
          $matriz_errados = array($matriz_comparar[0]."_".$i=>$codsol);
        } else {
          $matriz_errados[$matriz_comparar[0]."_".$i] = $codsol;
        }
      }

    }
  }

  $camp = "";
  for ($i = 0; $i < $numrows_comparar; $i++) {
    for ($j = 0; $j < $numcol_comparar; $j++) {
      $valores = pg_result($res_comparar,$i,$j);
      if ($camp == "") {
        $camp = trim(pg_fieldname($res_comparar,$j));
      }
      for ($ii = 0; $ii < $numcols_comparar; $ii++) {
        if ($camp == $matriz_comparar[$j]) {
          if (count(@$matriz_errados) == 0) {
            $matriz_errados = array($matriz_comparar[$j]."_".$i=>$valores);
          } else {
            $matriz_errados[$matriz_comparar[$j]."_".$i] = $valores;
          }
          $camp = trim(pg_fieldname($res_comparar,$j));
        }
      }
    }
  }

}

if ($db_opcao=="Incluir") {
  $db_opcao=1;
} else if ($db_opcao=="Alterar") {
  $db_opcao=2;
} else if ($db_opcao=="Excluir") {
  $db_opcao=3;
}
if ((($db_opcao==33  || $db_opcao==1) && $numrows>0) || (($db_opcao==33 || $db_opcao==3 || $db_opcao==2) && $numrows>1)) {
  $matriz_campos=split(",",$campos);
  $numcolunas=sizeof($matriz_campos);
  echo "   <tr class='cabec' >";

  for ($w=0; $w<$numcolunas; $w++) {

    $campo=str_replace(" ","",$matriz_campos[$w]);
    $clrotulo->label($campo);
    $Tlabel="T$campo";
    $Llabel="L$campo";

      if(substr($campo,0,3) == "db_"){
	      $nomcampo = "<b>".ucfirst(substr($campo,3))."<b>";
	      $$Tlabel  = ucfirst(substr($campo,3));
	    }else{
	      $nomcampo = $$Llabel;
	    }

//    echo "   <td  class='cabec' ".($cabecnowrap=="true"?"nowrap":"")." title='".$$Tlabel."'>".str_replace(":","",$$Llabel)." </td>\n";
	      echo " <td class='cabec' ".($corponowrap=="true"?"nowrap":"")." title='".$$Tlabel."'>".str_replace(":","",$nomcampo)."</td>\n";
  }

  echo  "    <td class='cabec' title='Alterar ou Excluir'><b>Opções</b></td>";
  echo "   </tr>";
  $cabec=true;

} else if (!$numrows>0) {
  echo "<tr><td  align='center' style='border:0px;'><b>".$msg_vazio."</b></td></tr>";
}
if (isset($cabec) && $cabec==true) {
  for ($i=0; $i<$numrows; $i++) {
    db_fieldsmemory($result,$i,$strFormatar);

    ///onMouseOut='parent.js_labelconta(false);' onMouseOver="parent.js_labelconta(true,event,
    echo "   <tr";
    if (isset($js_mouseover)) {

      echo " onMouseOver=\"$js_mouseover('";
      $virgula = "";
      reset($quais_chaves);
      for ($qw=0; $qw<sizeof($quais_chaves); $qw++) {
        $chave = key($quais_chaves);
        echo $virgula.$$quais_chaves[$chave];
        $virgula = "-";
        next($quais_chaves);
      }
      echo "');
      \"";
    }


    if (isset($js_mouseout)) {
      echo " onMouseOut=\"$js_mouseout('";
      $virgula = "";
      reset($quais_chaves);
      for ($qw=0; $qw<sizeof($quais_chaves); $qw++) {
        $chave = key($quais_chaves);
        echo $virgula.$$quais_chaves[$chave];
        $virgula = "-";
        next($quais_chaves);
      }
    }

    echo "');\">";
    $naomostra = false;

    $pode=false;
    if (isset($sql_disabled)) {
      for ($s=0; $s<$numrows01; $s++) {
        for ($w=0; $w<sizeof($quais_chaves); $w++) {
          $campo=pg_result($result01,$s,$quais_chaves[$w]);
          if (trim($campo)==trim($$quais_chaves[$w])) {
            $pode=true;
          } else {
            $pode=false;
            break;
          }
        }
        if ($pode==true) {
          break;
        }
      }
    }

    for ($w=0; $w<$numcolunas; $w++) {
      $campo = trim(pg_fieldname($result,$w));
      for ($ww=1; $ww<sizeof($quais_chaves); $ww++) {
        $valorchave = "x_".$quais_chaves[$ww];
        $nomechave = $quais_chaves[$ww];
        $valorchave = $$valorchave;
        if ($valorchave!=null && $valorchave!="") {
          if ($valorchave == $$campo && $nomechave==$campo && ($db_opcao==2 || $db_opcao==22 || $db_opcao==3 || $db_opcao==33)) {
            $naomostra = true;
          }
        }
      }
    }
    if ($naomostra==true) {
      continue;
    }
    for ($w=0; $w<$numcolunas; $w++) {
      $campo=(trim($matriz_campos[$w]));
      $TClabel = "TC".$campo;

      if (isset($sql_comparar) && $sql_comparar != "") {
        if (sizeof(@$matriz_errados) > 0) {
          for ($jj = 0; $jj < $numcol_comparar; $jj++) {
            if ($matriz_comparar[$jj] == $campo) {
              if (in_array($$campo,$matriz_errados)) {
                $flag_achou = true;
                $id_reg     = $i;
              }
            }
          }
        }
      }

      if ($id_reg >= 0) {
        if ($id_reg != $i) {
          $flag_achou = false;
        }
      }

      if ($flag_achou == false) {
        $classe = "corpo";
      } else {
        $classe = "corpo_erro";
      }

	    if(substr($campo,0,3) == "db_"){
	      $nomcampo = ucfirst(substr($campo,3));
	    }else{
	      $nomcampo = $$TClabel;
	    }

      echo " <td style=\"border:1px solid #AACCCC;\"  ".($corponowrap=="true"?"nowrap":"")." class='$classe' align=\"".(substr($nomcampo,0,5)=='float'?"right":(substr($nomcampo,0,7)=='varchar'||$nomcampo=='text'?"left":"center"))."\" >
      ".(substr(trim($nomcampo),0,4)=='bool'&&($$campo=="f"||$$campo=="t")?($$campo=="f"?"Não":"Sim"):$$campo)."&nbsp;
      </td>";
      if ($w+1==$numcolunas) {
        if ($db_opcao==33 || $pode==true) {
          if (isset($opcoes)) {
            if ($opcoes==2) {
              echo "<td class='corpo'><span >&nbsp;A&nbsp;</span></td>\n";
            } else if ($opcoes==3) {
              echo "<td class='corpo'><span >&nbsp;E&nbsp;</span></td>\n";
            }
          } else {
            echo "<td class='corpo'><span >&nbsp;A&nbsp;</span>&nbsp;&nbsp;&nbsp;<span class='x'>&nbsp;E&nbsp;</span></td>\n";
          }
        } else {
          echo "<td class='corpo' nowrap>";
          if ($pode == false) {

            $aBusca      = array( "\r", "\n", "'" );
            $aAlteracoes = array( '\\r', '\\n', '&#39;');
            $coluna      = "";

            if (empty($opcoes)||(isset($opcoes)&& $opcoes==2)) {

              $coluna.= "<a title='ALTERAR CONTEÚDO DA LINHA' href='#' onclick='js_retorna(\"alterar\"";
              for ($ww=0; $ww<sizeof($quais_chaves); $ww++) {
                $coluna .= ",\"".str_replace( $aBusca, $aAlteracoes, $$quais_chaves[$ww] )."\"";
              }
              $coluna.= ");return false;'>&nbsp;A&nbsp;</a>\n";
            }
            $coluna.="&nbsp;&nbsp;&nbsp;";

            if ( empty($opcoes) || (isset($opcoes) && $opcoes == 3) ) {

              $coluna.="<a title='EXCLUIR CONTEÚDO DA LINHA' href='#' onclick='js_retorna(\"excluir\"";
              for ( $ww=0; $ww<sizeof($quais_chaves); $ww++ ) {
                $coluna .= ",\"".str_replace( $aBusca, $aAlteracoes, $$quais_chaves[$ww] )."\"";
              }
              $coluna .= ");return false;'>&nbsp;E&nbsp;</a>";
            }
            echo $coluna."\n";
          }
          echo "</td>";
        }
      }
    }
    echo "   </tr>";
  }
}
?>    </table>
</td>
</tr>
</table>
</center>
</form>
</body>
</html>
<?
$retorno = @unlink(base64_decode($arquivo));
if ($retorno==false) {
  echo "<blink>Carregando...</blink>";
}
?>
