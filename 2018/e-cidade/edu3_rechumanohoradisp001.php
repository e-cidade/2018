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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($_POST);

$oGet = db_utils::postMemory( $_GET );

$cldiasemana         = new cl_diasemana;
$clperiodoescola     = new cl_periodoescola;
$clrechumanohoradisp = new cl_rechumanohoradisp;

$db_opcao = 1;
$db_botao = true;
$escola   = db_getsession("DB_coddepto");

$sCamposPeriodoEscola = "min(ed17_h_inicio) as menorhorario, max(ed17_h_fim) as maiorhorario";
$sSqlPeriodoEscola    = $clperiodoescola->sql_query( "", $sCamposPeriodoEscola, "", "" );
$result_per           = $clperiodoescola->sql_record( $sSqlPeriodoEscola );

db_fieldsmemory($result_per, 0);

$hora1         = (int) substr( $menorhorario, 0, 2 );
$hora2         = (int) substr( $maiorhorario, 0, 2 ) + 1;
$horainicial   = $hora1 * 100;
$horafinal     = $hora2 * 100;
$tempo_ini     = mktime( $hora1, 0, 0, date("m"), date("d"), date("Y") );
$tempo_fim     = mktime( $hora2, 0, 0, date("m"), date("d"), date("Y") );
$difer_minutos = ($tempo_fim - $tempo_ini) / 60;
$alt_tab_hora  = $difer_minutos / 2;
$qtd_hora      = $difer_minutos / 60;
$larg_tabela   = @$larg_obj;
$larg_coluna1  = 40;
$larg_coluna2  = 40;
$tabela1_top   = 5;
$tabela1_left  = 2;
$iRecHumano    = null;

if( isset( $cod_matricula ) ) {

  $where      = " ed20_i_codigo = {$cod_matricula}";
  $destino    = "chavepesquisa={$chavepesquisa}&cod_matricula={$cod_matricula}";
  $iRecHumano = $cod_matricula;
} else {

  $where      = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = {$chavepesquisa}";
  $destino    = "chavepesquisa={$chavepesquisa}";
  $iRecHumano = $chavepesquisa;
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?php
if( !isset( $larg_obj ) ) {

  if( isset( $cod_matricula ) ) {
    $destino = "chavepesquisa={$chavepesquisa}&cod_matricula={$cod_matricula}";
  } else {
    $destino = "chavepesquisa={$chavepesquisa}";
  }
  ?>
  <script>
    largura   = document.body.clientWidth;
    var sUrl  = "edu3_rechumanohoradisp001.php?<?=$destino?>&larg_obj="+largura;
  </script>
  <?
  if( isset( $oGet->iHorarioDisponivel ) && !empty( $oGet->iHorarioDisponivel ) ) {
  ?>
    <script>
      sUrl += "&iHorarioDisponivel=<?=$oGet->iHorarioDisponivel?>";
    </script>
  <?php
  }
  ?>
  <script>
    location.href = sUrl;
  </script>
  <?php
  exit;
}
?>
<table id="id_dia" style="position:absolute;top:<?=$tabela1_top?>px;left:<?=$tabela1_left?>px;" cellspacing="1" cellpadding="0">
  <tr height="20">
    <td align="center" width="<?=$larg_coluna1?>" style="background:#444444;color:#DEB887">
      <a style="color:#DEB887;"><b>Horas</b>
    </td>
    <td align="center" width="<?=$larg_coluna2?>" style="background:#444444;color:#DEB887">
      <a style="color:#DEB887;">&nbsp;
    </td>
    <?php
    $sCamposDiaSemana = "ed32_i_codigo, ed32_c_abrev, ed32_c_descr";
    $sWhereDiaSemana  = "ed04_i_escola = {$escola} AND ed04_c_letivo = 'S'";
    $sSqlDiaSemana    = $cldiasemana->sql_query_rh( "", $sCamposDiaSemana, "ed32_i_codigo", $sWhereDiaSemana );
    $result           = $cldiasemana->sql_record( $sSqlDiaSemana );
    $larg_dia         = floor( ( $larg_tabela - $larg_coluna1 - $larg_coluna2 ) / $cldiasemana->numrows );

    for( $x = 0; $x < $cldiasemana->numrows; $x++ ) {

      db_fieldsmemory( $result, $x );
      ?>
      <td align="center" width="<?=$larg_dia?>" style="background:#444444;color:#DEB887">
        <b><?=$ed32_c_descr?></b>
      </td>
    <?php
    }
    ?>
  </tr>
</table>

<!--Tabela de fundo-->
<table id="id_fundo"
       style=";position:absolute;top:<?=$tabela1_top+25?>px;left:<?=$tabela1_left+$larg_coluna1?>px;"
       cellspacing="0"
       cellpadding="0">
  <?php
  for( $x = 0; $x < $qtd_hora; $x++ ) {

    ?>
    <tr bgcolor="#f3f3f3">
      <td align="center"
          width="<?=$larg_coluna2?>"
          height="<?=$alt_tab_hora/$qtd_hora?>"
          style="border:1px solid #f3f3f3;">
        &nbsp;
      </td>
      <td width="<?=$larg_dia*$cldiasemana->numrows?>"
          height="<?=$alt_tab_hora/$qtd_hora?>"
          style="border:1px solid #f3f3f3;">
        &nbsp;
      </td>
    </tr>
  <?php
  }
  ?>
</table>
<?php
/**
 * Grade dos horários
 */
$top_ini = $tabela1_top + 20;
$tt      = 0;

for( $t = $horainicial; $t <= $horafinal; $t++ ) {

  $hora         = strlen($t) == 3 ? "0" . $t : $t;
  $hora         = substr( $hora, 0, 2 ) . ":" . substr( $hora, 2, 2 );
  $id_hora      = "H" . $hora;
  $id_hora2     = "HH" . $hora;
  $id_linhahora = "LH" . $hora;

  if( $t != 2400 ) {

    if( ( $t % 100 ) == 0 ) {
      $visible = "visible";
    } else {
      $visible = "hidden";
    }

    $sDiv1  = "<div id='{$id_hora}' style='visibility:{$visible}; position:absolute;";;
    $sDiv1 .= " top:".($top_ini)."px; left:".($larg_coluna1 + $tabela1_left - 38)."px;'><b>" . $hora . "</b></div>";
    echo $sDiv1;

    $sDiv2  = "<div id='{$id_hora2}' style='color:#FF0000; visibility:hidden; position:absolute;";
    $sDiv2 .= "top:".($top_ini)."px; left:".($larg_coluna1 + $larg_coluna2 + $tabela1_left - 38)."px;'><b>";
    $sDiv2 .= $hora."</b></div>";
    echo $sDiv2;

    $sDiv3  = "<div id='{$id_linhahora}' style='width:".($larg_dia * $cldiasemana->numrows + $larg_coluna2)."px;";
    $sDiv3 .= " height:1px; background:#FF0000; visibility:hidden; position:absolute; top:".($top_ini + 5)."px;";
    $sDiv3 .= " left:".($larg_coluna1 + $tabela1_left)."px;'></div>";
    echo $sDiv3;
  }

  $tt++;
  if( $tt == 60 ) {

    $t  += 40;
    $tt  = 0;
  }

  $top_ini += 0.5;
}

/**
 * Linhas verticais
 */
$left_ini = $tabela1_left + $larg_coluna1 + $larg_coluna2;
for( $x = 0; $x < $cldiasemana->numrows + 1; $x++ ) {

  ?>
  <table border="0"
         style=";position:absolute;top:<?=$tabela1_top+25?>px;left:<?=$left_ini?>px;"
         cellspacing="0"
         cellpadding="0">
    <tr>
      <td width="1" bgcolor="#000000" height="<?=$alt_tab_hora?>"></td>
    </tr>
  </table>
  <?php
  $left_ini += $larg_dia;
}
/**
 * Disponibilidade do Docente
 */
?>
<table style="top:<?=isset( $ini_top ) ? $ini_top : ""?>px;left:<?=isset( $ini_left ) ? $ini_left : ""?>px;"
       cellspacing="0"
       cellpadding="0">
  <tbody id="disp_rechumano">
  <tr>
    <td>
      <?php

      $ini_left = $tabela1_left + $larg_coluna1 + $larg_coluna2;
      for( $x = 0; $x < $cldiasemana->numrows; $x++ ) {

        $ini_top = $tabela1_top + 25;
        db_fieldsmemory( $result, $x );

        $sCamposRecHumanoHoraDisp     = "ed20_i_codigo, case when ed20_i_tiposervidor = 1";
        $sCamposRecHumanoHoraDisp    .= " then 'Matrícula: '||rechumanopessoal.ed284_i_rhpessoal";
        $sCamposRecHumanoHoraDisp    .= " else 'CGM: '||rechumanocgm.ed285_i_cgm end as identificacao";
        $sCamposRecHumanoHoraDisp    .= ", ed33_i_codigo, ed08_c_descr, ed18_c_nome, ed15_c_nome, ed17_h_inicio";
        $sCamposRecHumanoHoraDisp    .= ", ed17_h_fim, ed18_i_codigo as ed17_i_escola, ed128_abreviatura";
        $sCamposRecHumanoHoraDisp    .= ", ed33_horaatividade";
        $sOrdenacaoRecHumanoHoraDisp  = "ed75_i_codigo, ed33_i_diasemana, ed17_h_inicio asc, ed17_h_fim asc";
        $sWhereRecHumanoHoraDisp      = "{$where} AND ed33_i_diasemana = {$ed32_i_codigo}";

        if( isset( $oGet->iHorarioDisponivel ) && !empty( $oGet->iHorarioDisponivel ) ) {
          $sWhereRecHumanoHoraDisp .= " AND ed75_i_codigo = {$oGet->iHorarioDisponivel}";
        }

        $sSqlRecHumanoHoraDisp = $clrechumanohoradisp->sql_query_tipohoratrabalho(
                                                                                   "",
                                                                                   $sCamposRecHumanoHoraDisp,
                                                                                   $sOrdenacaoRecHumanoHoraDisp,
                                                                                   $sWhereRecHumanoHoraDisp
                                                                                 );
        $result1 = $clrechumanohoradisp->sql_record( $sSqlRecHumanoHoraDisp );
        $tt      = 0;

        for( $t = $horainicial; $t <= $horafinal; $t++ ) {

          $hora = strlen($t) == 3 ? "0" . $t : $t;
          $hora = substr( $hora, 0, 2 ) . ":" . substr( $hora, 2, 2 );

          if( $clrechumanohoradisp->numrows > 0 ) {

            for( $y = 0; $y < $clrechumanohoradisp->numrows; $y++ ) {

              db_fieldsmemory( $result1, $y );
              if( trim( $hora ) == trim( $ed17_h_inicio ) ) {

                $tempo_ini      = mktime( substr( $ed17_h_inicio, 0, 2), substr( $ed17_h_inicio, 3, 2 ), 0, 1, 1, 1999 );
                $tempo_fim      = mktime( substr( $ed17_h_fim, 0, 2 ), substr( $ed17_h_fim, 3, 2 ), 0, 1, 1, 1999 );
                $difermin       = ($tempo_fim - $tempo_ini) / 60;
                $difer          = ceil( $difermin / 2 );
                $sHoraAtividade = $ed33_horaatividade == 't' ? 'SIM' : 'NÃO';
                $sCor           = "none repeat scroll 0 0 #FFCC99";

                if( isset( $_SESSION["sess_cordisp"] ) && isset( $_SESSION["sess_cordisp"][$ed17_i_escola] ) ) {
                  $sCor = $_SESSION["sess_cordisp"][$ed17_i_escola];
                }

                ?>
                <table id="tab<?=$ed33_i_codigo?>"
                       width="<?=$larg_dia?>"
                       border="0"
                       height="<?=$difer?>"
                       style="border:1px outset #000000;position:absolute;top:<?=$ini_top?>px;left:<?=$ini_left?>px;"
                       cellspacing="0"
                       cellpadding="0">
                  <tr>
                    <td style="font-size:9px;background:<?=$sCor?>"
                        align="center"
                        onmouseover="js_Mover(
                                               'tab<?=$ed33_i_codigo?>',
                                               '<?=$ed17_h_inicio?>',
                                               '<?=$ed17_h_fim?>',
                                               '<?=$ed17_i_escola?>',
                                               '<?=$ed18_c_nome?>',
                                               '<?=$ed08_c_descr?>',
                                               '<?=$ed15_c_nome?>',
                                               '<?=$sCor?>',
                                               '<?=$identificacao?>',
                                               '<?=$ed128_abreviatura?>',
                                               '<?=$sHoraAtividade?>'
                                             )"
                        onmouseout="js_Mout( 'tab<?=$ed33_i_codigo?>', '<?=$ed17_h_inicio?>', '<?=$ed17_h_fim?>' )">
                      <?=$ed17_i_escola?> -> <?=$ed17_h_inicio?> às <?=$ed17_h_fim?>
                    </td>
                  </tr>
                </table>
              <?php
              }
            }
          }
          $tt++;
          if( $tt == 60 ) {

            $t  += 40;
            $tt  = 0;
          }

          $ini_top += 0.5;
        }

        $ini_left += $larg_dia;
      }
      ?>
    </td>
  </tr>
  </tbody>
</table>
<table width="200"
       style="position:absolute;top:<?=$tabela1_top+20?>px;left:<?=$larg_obj-200?>px;"
       cellspacing="2"
       cellpadding="4">
  <tr>
    <td id="tab_descr" bgcolor="#FFFFCC" style="visibility:hidden;border:1px solid #000000;">
    </td>
  </tr>
</table>
</body>
</html>
<script>
  function js_Mover( quadro, horaini, horafim, escola, nomeescola, periodo, turno, cor, matricula, tipohora, horaatividade ) {

    texto  = "<b>" + matricula + "</b><br>Turno: <b>" + turno + "</b><br>Período: <b>" + periodo;
    texto += "</b><br>Hora Inicial: <b>" + horaini + "</b><br>Hora Final: <b>" + horafim + "</b><br>Escola: <b>" + escola;
    texto += " - " + nomeescola + "</b><br>";
    texto += "Tipo de Hora: <b>" + tipohora + "</b><br>";
    texto += "Hora Atividade: <b>" + horaatividade + "</b>";

    document.getElementById("tab_descr").style.visibility = "visible";
    document.getElementById("tab_descr").style.background = cor;
    document.getElementById("tab_descr").innerHTML        = texto;
    document.getElementById(quadro).style.border          = "1px inset";

    tt = 0;
    for( t = <?=$horainicial?>; t <= <?=$horafinal?>; t++ ) {

      hora         = new String(t);
      hora         = hora.length == 3 ? "0" + hora : hora;
      hora         = hora.substr(0, 2) + ":" + hora.substr(2, 2);
      id_hora      = "HH" + hora;
      id_linhahora = "LH" + hora;

      if( t != 2400 ) {

        if( horaini == hora || horafim == hora ) {

          document.getElementById(id_hora).style.visibility = "visible";
          document.getElementById(id_hora).style.zIndex     = 10000;
        }

        if( hora >= horaini && hora <= horafim ) {

          document.getElementById(id_linhahora).style.background = cor;
          document.getElementById(id_linhahora).style.visibility = "visible";
        }
      }

      tt++;
      if( tt == 60 ) {

        t  += 40;
        tt  = 0;
      }
    }
  }

  function js_Mout( quadro, horaini, horafim ) {

    document.getElementById("tab_descr").style.visibility = "hidden";
    document.getElementById("tab_descr").innerHTML        = "";
    document.getElementById(quadro).style.border          = "1px outset";

    tt = 0;
    for( t = <?=$horainicial?>; t <= <?=$horafinal?>; t++ ) {

      hora         = new String(t);
      hora         = hora.length == 3 ? "0" + hora : hora;
      hora         = hora.substr(0, 2) + ":" + hora.substr(2, 2);
      id_hora      = "HH" + hora;
      id_linhahora = "LH" + hora;

      if( t != 2400 ) {

        if( horaini == hora || horafim == hora ) {

          document.getElementById(id_hora).style.visibility = "hidden";
          document.getElementById(id_hora).style.zIndex     = 1000;
        }
      }

      if( hora >= horaini && hora <= horafim ) {
        document.getElementById(id_linhahora).style.visibility = "hidden";
      }

      tt++;
      if( tt == 60 ) {

        t  += 40;
        tt  = 0;
      }
    }
  }
</script>