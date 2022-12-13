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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str( $_SERVER["QUERY_STRING"] );

$datamatricula_dia = date( "d", db_getsession( "DB_datausu" ) );
$datamatricula_mes = date( "m", db_getsession( "DB_datausu" ) );
$datamatricula_ano = date( "Y", db_getsession( "DB_datausu" ) );

db_postmemory($_POST);
$oGet= db_utils::postMemory($_GET);

$resultedu = eduparametros(db_getsession("DB_coddepto"));

$escola                = db_getsession("DB_coddepto");
$sMascaraInstituicacao = str_replace("0", "9", ArredondamentoNota::getMascara($datamatricula_ano));

$oDaoAvaliacaoRegra          = new cl_avaliacaoestruturanota();
$clregencia                  = new cl_regencia;
$clturma                     = new cl_turma;
$clturmaserieregimemat       = new cl_turmaserieregimemat;
$clprocavaliacao             = new cl_procavaliacao;
$clmatricula                 = new cl_matricula;
$clmatriculaserie            = new cl_matriculaserie;
$clmatriculamov              = new cl_matriculamov;
$cldiario                    = new cl_diario;
$cldiarioavaliacao           = new cl_diarioavaliacao;
$cldiarioresultado           = new cl_diarioresultado;
$cldiariofinal               = new cl_diariofinal;
$clpareceraval               = new cl_pareceraval;
$clparecerresult             = new cl_parecerresult;
$clabonofalta                = new cl_abonofalta;
$clamparo                    = new cl_amparo;
$clalunocurso                = new cl_alunocurso;
$clalunopossib               = new cl_alunopossib;
$clperiodocalendario         = new cl_periodocalendario;
$cltransfaprov               = new cl_transfaprov;
$clserieequiv                = new cl_serieequiv;
$cltransfescolarede          = new cl_transfescolarede;
$oDaoTurmaTurnoReferente     = new cl_turmaturnoreferente();
$oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();
?>

<table width="300" height="100" id="tab_aguarde" style="border:2px solid #444444;position:absolute;top:100px;left:250px;" cellspacing="1" cellpading="2">
  <tr>
    <td bgcolor="#DEB887" align="center" style="border:1px solid #444444;">
      <b>Aguarde...Carregando.</b>
    </td>
  </tr>
</table>

<?
if (!isset($incluir) && !isset($incluir2)) {

  $result_cod = $clmatricula->sql_record($clmatricula->sql_query("","turma.ed57_i_escola as escola, ed60_i_aluno,ed60_c_concluida,ed221_i_serie as etapaorigem",""," ed60_i_codigo = $matricula"));
  db_fieldsmemory($result_cod,0);

?>
  <html>
    <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <meta http-equiv="Expires" CONTENT="0">
      <script type="text/javascript" src="scripts/scripts.js"></script>
      <script type="text/javascript" src="scripts/prototype.js"></script>
      <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
      <form name="form1" METHOD="POST" action="">
      <input type="hidden" id="sTurnoReferente" name="sTurnoReferente" value=<?php echo $oGet->sTurnoReferente;?>>
      <? if ($ed60_c_concluida == "S") {?>
        <br>
        <b>Data da Matrícula:</b>
        <?db_inputdata('datamatricula',@$datamatricula_dia,@$datamatricula_mes,@$datamatricula_ano,true,'text',1,"")?><br>
        <?
          $campos               = "atestvaga.ed102_i_serie as codseriedestino";
          $sSqlTransfescolarede = $cltransfescolarede->sql_query("",$campos,""," ed103_i_codigo = $ed103_i_codigo");
          $result111            = $cltransfescolarede->sql_record($sSqlTransfescolarede);
          db_fieldsmemory($result111,0);
          $etapaorigem = $codseriedestino;
          $result_etp  = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("","ed223_i_serie,ed11_c_descr as descretapa","ed223_i_ordenacao"," ed220_i_turma = $turmadestino"));
          if ($clturmaserieregimemat->numrows > 1) {
        ?>
          <tr>
            <td colspan="4">Escola:<?=$ed57_i_escola?>
              <?
                $tem = false;
                for ($c=0; $c < $clturmaserieregimemat->numrows; $c++) {

                  db_fieldsmemory($result_etp,$c);
                  if ($ed223_i_serie == $etapaorigem) {

                    $tem = true;
                    break;
                  }
                }
                if ($tem == true) {
              ?>
                <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
              <?
                } else {
              ?>
                <b>Informe a Etapa na turma de destino:</b>
                <select name="codetapadestino">
                <?
                  $result_equiv = $clserieequiv->sql_record($clserieequiv->sql_query("","ed234_i_serieequiv",""," ed234_i_serie = $etapaorigem"));
                  for ($r=0; $r < $clturmaserieregimemat->numrows; $r++) {

                    db_fieldsmemory($result_etp,$r);
                    $selected = "";
                    $disabled = "disabled";
                    if ($clserieequiv->numrows > 0) {
                      for($w=0;$w<$clserieequiv->numrows;$w++){

                        db_fieldsmemory($result_equiv,$w);
                        if( $ed234_i_serieequiv == $ed223_i_serie) {
                          $selected = "selected";
                          $disabled = "";
                          break;
                        }
                      }
                    }
                ?>
                  <option value="<?=$ed223_i_serie?>" <?=$selected?> <?=$disabled?>><?=$descretapa?></option>
                <?
                   }
                ?>
                </select>
              <?
                }
              ?>
            </td>
          </tr>
        <?
          } else {
            db_fieldsmemory($result_etp,0);
        ?>
          <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
        <?
          }
        ?>
        <br><br>
        <input name="incluir2" type="button" value="Confirmar Matrícula" onclick="return js_processar2();"<?=isset($incluir2)?"style='visibility:hidden;'":""?>>
      <? } else {?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="4" valign="top" bgcolor="#CCCCCC">
              <br>
              <b>
              Caso as turmas de origem e destino tenham disciplinas e/ou períodos de avaliação diferentes,
              informe abaixo quais disciplinas e períodos de avaliação da turma de destino que vão receber
              as informações do aluno.
              </b>
              <br><br>
            </td>
          </tr>
          <tr>
            <td width="28%" valign="top" bgcolor="#CCCCCC">
              <b>Disciplinas TURMA DE ORIGEM:</b>
            </td>
            <td width="20"></td>
            <td width="28%" valign="top" bgcolor="#CCCCCC">
              <b>Disciplinas TURMA DE DESTINO:</b>
            </td>
            <td valign="top" bgcolor="#CCCCCC">
              <b>Aproveitamento na TURMA DE ORIGEM:</b>
            </td>
          </tr>
          <?
            $result_equivorig = $clserieequiv->sql_record($clserieequiv->sql_query("","ed234_i_serieequiv as equivorig",""," ed234_i_serie = $etapaorigem"));
            $codequivorig     = "";
            $seporig          = "";

            for ($ww=0; $ww < $clserieequiv->numrows; $ww++) {

             db_fieldsmemory($result_equivorig,$ww);
             $codequivorig .= $seporig.$equivorig;
             $seporig       = ",";
            }

            $codequivorig = ($codequivorig==""?0:$codequivorig).",".$etapaorigem;
            $result       = $clregencia->sql_record($clregencia->sql_query("","ed59_i_codigo,ed232_i_codigo,ed232_c_descr,ed232_c_abrev,ed220_i_procedimento as procorigem,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_turma = $turmaorigem AND ed59_i_serie in ($etapaorigem)"));
            $procorigem   = pg_result($result,0,'procorigem');
            $linhas       = $clregencia->numrows;
            $result1      = $clregencia->sql_record($clregencia->sql_query("","ed59_i_codigo as regdestino,ed232_i_codigo as coddestino,ed232_c_descr as descrdestino,ed220_i_procedimento as procdestino,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_turma = $turmadestino AND ed59_i_serie in ($codequivorig)"));
            $procdestino  = pg_result($result1,0,'procdestino');
            $linhas1      = $clregencia->numrows;
            $regmarcadas  = "";
            $veraprovnulo = "";

            for ($t=0; $t < $linhas; $t++) {
              db_fieldsmemory($result,$t);
          ?>
              <tr>
                <td valign="top" bgcolor="#CCCCCC">
                  <input name="regenciaorigem" type="text" value="<?=$ed59_i_codigo?>" size="10" readonly style="width:75px">
                  <input name="regorigemdescr" type="text" value="<?=$ed232_c_descr?>" size="30" readonly style="width:180px">
                </td>
                <td align="center">--></td>
                <td>
                  <?
                    $temreg = false;

                    for ($w=0; $w < $linhas1; $w++) {

                      db_fieldsmemory($result1,$w);
                      if ($ed232_i_codigo == $coddestino) {

                        $temreg          = true;
                        $regenciadestino = $regdestino;
                        $regdestinodescr = $descrdestino;
                        $regmarcadas    .= "#".$regdestino."#";
                      }
                    }

                    if ($temreg == true) {
                  ?>
                    <input name="regenciadestino" type="text" value="<?=$regenciadestino?>" size="10" readonly style="width:75px">
                    <input name="regdestinodescr" type="text" value="<?=$regdestinodescr?>" size="30" readonly style="width:180px">
                  <?
                    }else{
                    $sql2 = "select ed59_i_codigo as regsobra,trim(ed232_c_descr) as descrsobra
                             from regencia
                             inner join disciplina on ed12_i_codigo = ed59_i_disciplina
                             inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
                             where ed59_i_turma = $turmadestino
                             and ed59_i_serie in ($codequivorig)
                             and ed232_i_codigo not in(select ed232_i_codigo from regencia
                                                       inner join disciplina on ed12_i_codigo = ed59_i_disciplina
                                                       inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
                                                       where ed59_i_turma = $turmaorigem
                                                       and ed59_i_serie = $etapaorigem
                                                      )";
                    $result2 = db_query($sql2);
                    $linhas2 = pg_num_rows($result2);
                  ?>
                    <select name="regenciadestino" style="padding:0px;width:75px;height:16px;font-size:12px;" onchange="js_eliminareg(this.value,<?=$t?>)">
                    <option value=""></option>
                    <?
                      if ($linhas == 1) {
                        echo "<option value='0'>TODAS</option>";
                      }

                      for ($w=0; $w < $linhas2; $w++) {

                        db_fieldsmemory($result2,$w);
                        echo "<option value='$regsobra'>$regsobra</option>";
                      }
                    ?>
                    </select>
                    <select name="regdestinodescr" style="padding:0px;width:180px;height:16px;font-size:12px;" onchange="js_eliminareg(this.value,<?=$t?>)">
                      <option value=""></option>
                      <?
                        if ($linhas == 1) {
                          echo "<option value='0'>TODAS</option>";
                        }
                        for ($w=0;$w < $linhas2; $w++) {
                          db_fieldsmemory($result2,$w);
                          echo "<option value='$regsobra'>$descrsobra</option>";
                        }
                      ?>
                    </select>
                    <input type="hidden" name="combo" value="<?=$t?>">
                    <input type="hidden" name="comboselect<?=$t?>" value="">
                  <?
                    }
                  ?>
                </td>
                <td>
                  <table border="1" cellspacing="0" cellpadding="0">
                    <tr>
                      <?
                        $result_diario = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed09_c_abrev,ed72_i_valornota,ed72_c_valorconceito,ed72_t_parecer,ed37_c_tipo","ed41_i_sequencia ASC"," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed09_c_somach = 'S'"));
                        echo "<td width='50px' style='background:#444444;color:#DEB887'><b>$ed232_c_abrev</b></td>";

                        if ($cldiarioavaliacao->numrows == 0) {
                          echo "<td width='160px' style='background:#f3f3f3;'>Nenhum registro.</td>";
                        } else {

                          for ($v=0; $v < $cldiarioavaliacao->numrows; $v++) {

                            db_fieldsmemory($result_diario,$v);
                            if (trim($ed37_c_tipo) == "NOTA") {

                              if ($resultedu == 'S') {
                                $aproveitamento = $ed72_i_valornota!=""?number_format($ed72_i_valornota,2,",","."):"";
                              } else {
                                $aproveitamento = $ed72_i_valornota!=""?number_format($ed72_i_valornota,0):"";
                              }
                            } elseif(trim($ed37_c_tipo) == "NIVEL") {
                              $aproveitamento = $ed72_c_valorconceito;
                            } else {
                              $aproveitamento = $ed72_t_parecer!=""?"Parecer":"";
                            }

                           $veraprovnulo .= $aproveitamento;
                           echo "<td width='50px' style='background:#f3f3f3;'><b>$ed09_c_abrev:</b></td>
                                 <td width='50px' align='center'>".($aproveitamento==""?"&nbsp;":$aproveitamento)."</td>";
                          }
                        }
                      ?>
                    </tr>
                  </table>
                </td>
              </tr>
          <?
            }
          ?>
          <tr>
            <td valign="top" bgcolor="#CC CCCC">
              <b>Períodos de Avaliação TURMA DE ORIGEM:</b>
            </td>
            <td width="10"></td>
             <td valign="top" bgcolor="#CCCCCC">
             <b>Períodos de Avaliação  TURMA DE DESTINO:</b>
            </td>
            <td></td>
          </tr>
          <?
            $result  = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_codigo,ed09_i_codigo,ed09_c_descr,ed37_c_tipo,ed37_i_menorvalor,ed37_i_maiorvalor","ed41_i_sequencia"," ed41_i_procedimento = $procorigem"));
            $linhas  = $clprocavaliacao->numrows;
            $result1 = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_codigo as codaval,ed09_i_codigo as codperaval,ed09_c_descr as descraval,ed37_c_tipo as tipodest,ed37_i_menorvalor as menordest,ed37_i_maiorvalor as maiordest","ed41_i_sequencia"," ed41_i_procedimento = $procdestino"));
            $linhas1 = $clprocavaliacao->numrows;

            for ($t=0; $t < $linhas; $t++) {
              db_fieldsmemory($result,$t);
              $tipoavaliacao = $ed37_c_tipo.($ed37_c_tipo=='NOTA'?' ('.$ed37_i_menorvalor.' a '.$ed37_i_maiorvalor.')':'');
           ?>
              <tr>
                <td valign="top" bgcolor="#CCCCCC">
                  <input name="periodoorigem" type="text" value="<?=$ed41_i_codigo?>" size="10" readonly style="width:75px">
                  <input name="perorigemdescr" type="text" value="<?=$ed09_c_descr.' - '.$tipoavaliacao?>" size="30" readonly style="width:180px">
                </td>
                <td align="center">--></td>
                <td>
                  <?
                    $temper = false;
                    for ($w=0; $w < $linhas1; $w++) {

                      db_fieldsmemory($result1,$w);
                      if ($ed09_i_codigo == $codperaval) {

                        $temper          = true;
                        $periododestino  = $codaval;
                        $tipoavaliacao1  = $tipodest.($tipodest=='NOTA'?' ('.$menordest.' a '.$maiordest.')':'');
                        $perdestinodescr = $descraval.' - '.$tipoavaliacao1;
                      }
                    }
                    if ($temper == true) {
                  ?>
                    <input name="periododestino" type="text" value="<?=$periododestino?>" size="10" readonly style="width:75px">
                    <input name="perdestinodescr" type="text" value="<?=$perdestinodescr?>" size="30" readonly style="width:180px">
                  <?
                      } else {
                        $sql2 = "select ed41_i_codigo as persobra,ed09_c_descr as descrsobra,ed37_c_tipo as tipodest,ed37_i_menorvalor as menordest,ed37_i_maiorvalor as maiordest
                                 from procavaliacao
                                  inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
                                  inner join formaavaliacao on ed37_i_codigo = ed41_i_formaavaliacao
                                  inner join procedimento on ed40_i_codigo = ed41_i_procedimento
                                  inner join turmaserieregimemat on ed220_i_procedimento = ed40_i_codigo
                                  inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                                  inner join turma on ed57_i_codigo = ed220_i_turma
                                 where ed57_i_codigo = $turmadestino
                                 and ed223_i_serie = $etapaorigem
                                 and ed09_i_codigo not in(select ed09_i_codigo from procavaliacao
                                                          inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
                                                          inner join procedimento on ed40_i_codigo = ed41_i_procedimento
                                                          inner join turmaserieregimemat on ed220_i_procedimento = ed40_i_codigo
                                                          inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                                                          inner join turma on ed57_i_codigo = ed220_i_turma
                                                          where ed57_i_codigo = $turmaorigem
                                                          and ed223_i_serie = $etapaorigem)
                                 order by ed41_i_sequencia";
                        $result2 = db_query($sql2);
                        $linhas2 = pg_num_rows($result2);
                  ?>
                      <select name="periododestino" style="padding:0px;width:75px;height:16px;font-size:12px;" onchange="js_eliminaper(this.value,<?=$t?>)">
                        <option value=""></option>
                        <?
                          for ($w=0; $w < $linhas2; $w++) {

                            db_fieldsmemory($result2,$w);
                            echo "<option value='$persobra'>$persobra</option>";
                          }
                        ?>
                       </select>
                       <select name="perdestinodescr" style="padding:0px;width:180px;height:16px;font-size:12px;" onchange="js_eliminaper(this.value,<?=$t?>)">
                         <option value=""></option>
                         <?
                           for ($w=0; $w < $linhas2; $w++) {

                             db_fieldsmemory($result2,$w);
                             $tipoavaliacao2 = $tipodest.($tipodest=='NOTA'?' ('.$menordest.' a '.$maiordest.')':'');
                             echo "<option value='$persobra'>$descrsobra - $tipoavaliacao2</option>";
                           }
                         ?>
                       </select>
                       <input type="hidden" name="pcombo" value="<?=$t?>">
                       <input type="hidden" name="pcomboselect<?=$t?>" value="">
                    <?
                      }
                    ?>
                </td>
                <td></td>
              </tr>
            <?
              }
            ?>
            <tr>
              <td nowrap colspan="4">
                <b>Data da Matrícula:</b>
                <?db_inputdata('datamatricula',@$datamatricula_dia,@$datamatricula_mes,@$datamatricula_ano,true,'text',1,"")?>
              </td>
            </tr>
            <tr>
              <td colspan="4">
                <b>Importar aproveitamento da turma de origem:</b>
                <select name="import" onchange="js_importar(this.value);">
                  <option value="S">SIM</option>
                  <option value="N" selected>NÃO</option>
                </select>
              </td>
            </tr>
            <?
              $result_etp = $clturmaserieregimemat->sql_record($clturmaserieregimemat->sql_query("","ed223_i_serie,ed11_c_descr as descretapa","ed223_i_ordenacao"," ed220_i_turma = $turmadestino"));

              if ($clturmaserieregimemat->numrows > 1) {
            ?>
              <tr>
                <td colspan="4">
                  <?
                    $tem = false;
                    for ($c=0; $c < $clturmaserieregimemat->numrows; $c++) {

                      db_fieldsmemory($result_etp,$c);
                      if ($ed223_i_serie == $etapaorigem) {

                        $tem = true;
                        break;
                      }
                    }
                    if ($tem == true) {
                  ?>
                   <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
                  <?
                    } else {
                  ?>
                    <b>Informe a Etapa na turma de destino:</b>
                    <select name="codetapadestino">
                      <?
                        $result_equiv = $clserieequiv->sql_record($clserieequiv->sql_query("","ed234_i_serieequiv",""," ed234_i_serie = $etapaorigem"));
                        for ($r=0; $r < $clturmaserieregimemat->numrows; $r++) {

                          db_fieldsmemory($result_etp,$r);
                          $selected = "";
                          $disabled = "disabled";
                          if ($clserieequiv->numrows > 0) {

                            for ($w=0; $w < $clserieequiv->numrows; $w++) {

                              db_fieldsmemory($result_equiv,$w);
                              if ($ed234_i_serieequiv == $ed223_i_serie) {
                                $selected = "selected";
                                $disabled = "";
                                break;
                              }
                            }
                          }
                      ?>
                        <option value="<?=$ed223_i_serie?>" <?=$selected?> <?=$disabled?>><?=$descretapa?></option>
                      <?
                        }
                      ?>
                    </select>
                  <?
                    }
                  ?>
                </td>
              </tr>
              <?
                } else {

                db_fieldsmemory($result_etp,0);
              ?>
                <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
              <?
                }
              ?>
            <tr>
              <td height="10" colspan="4"></td>
            </tr>
            <tr>
              <td colspan="4">
                <? if (isset($matriculaante)) {?>
                  <input name="novamatricula" type="button" value="Gerar Nova Matrícula" onclick="js_processar(2);" <?=isset($incluir)?"style='visibility:hidden;'":""?>>
                <? }else{ ?>
                  <input name="incluir" type="button" value="Confirmar Matrícula" onclick="js_processar(1);" <?=isset($incluir)?"style='visibility:hidden;'":""?>>
                <? } ?>
              </td>
            </tr>
          </table>
          <script>
            <?if ($veraprovnulo == "") {?>
             document.form1.import[0] = null;
            <? } else {?>
             document.form1.import[0].selected = true;
            <? } ?>
          </script>
        <?}?>
      <form>
    </body>
  </html>
  <script>
  function js_eliminareg(valor,seq){

    if (valor == "0" && document.form1.import.value == "S") {
    	alert("Aproveitamento da disciplina de origem será transportado para todas as disciplinas da turma de destino!");
    }

    C     = document.form1.combo;
    RD    = document.form1.regenciadestino;
    RDC   = document.form1.regdestinodescr;
    tamC  = C.length;
    tamC  = tamC==undefined?1:tamC;
    campo = "comboselect"+seq;
    valorant = eval("document.form1."+campo+".value");

    if(tamC==1){
      tamRD = RD.length;
      for (r=0; r < tamRD; r++) {

        if (parseInt(RD.options[r].value) == parseInt(valor) || parseInt(RDC.options[r].value) == parseInt(valor)) {

          RD.options[r].selected  = true;
          RDC.options[r].selected = true;
        }

        if (parseInt(RD.options[r].value) == parseInt(valorant) || parseInt(RDC.options[r].value) == parseInt(valorant)) {

          RD.options[r].selected  = false;
          RDC.options[r].selected = false;
        }
      }
    } else {

      for (i=0; i < tamC; i++) {

        tamRD = RD[C[i].value].length;
        if (parseInt(C[i].value) != parseInt(seq)) {

          for (r=0; r < tamRD; r++) {

            if (parseInt(RD[C[i].value].options[r].value) == parseInt(valor) || parseInt(RDC[C[i].value].options[r].value) == parseInt(valor)) {

              RD[C[i].value].options[r].disabled  = true;
              RDC[C[i].value].options[r].disabled = true;
            }

            if (parseInt(RD[C[i].value].options[r].value) == parseInt(valorant) || parseInt(RDC[C[i].value].options[r].value) == parseInt(valorant)) {

              RD[C[i].value].options[r].disabled  = false;
              RDC[C[i].value].options[r].disabled = false;
            }
          }
        } else {
          for (r=0; r < tamRD; r++) {

            if (parseInt(RD[C[i].value].options[r].value) == parseInt(valor) || parseInt(RDC[C[i].value].options[r].value) == parseInt(valor)) {

              RD[C[i].value].options[r].selected  = true;
              RDC[C[i].value].options[r].selected = true;
            }

            if (parseInt(RD[C[i].value].options[r].value) == parseInt(valorant) || parseInt(RDC[C[i].value].options[r].value) == parseInt(valorant)) {

              RD[C[i].value].options[r].selected  = false;
              RDC[C[i].value].options[r].selected = false;
            }
          }
        }
      }
    }
    eval("document.form1."+campo+".value = valor");
  }

  function js_eliminaper(valor,seq) {

    C        = document.form1.pcombo;
    PD       = document.form1.periododestino;
    PDC      = document.form1.perdestinodescr;
    tamC     = C.length;
    tamC     = tamC==undefined?1:tamC;
    campo    = "pcomboselect"+seq;
    valorant = eval("document.form1."+campo+".value");

    if (tamC == 1) {

      tamPD = PD.length;
      for (r=0; r < tamPD; r++) {

        if (parseInt(PD.options[r].value) == parseInt(valor) || parseInt(PDC.options[r].value) == parseInt(valor)) {

          PD.options[r].selected  = true;
          PDC.options[r].selected = true;
        }

        if (parseInt(PD.options[r].value) == parseInt(valorant) || parseInt(PDC.options[r].value) == parseInt(valorant)) {

          PD.options[r].selected  = false;
          PDC.options[r].selected = false;
        }
      }
    } else {

      for (i=0; i < tamC; i++) {

        tamPD = PD[C[i].value].length;
        if (parseInt(C[i].value) != parseInt(seq)) {

          for (r=0; r < tamPD;r++) {

            if (parseInt(PD[C[i].value].options[r].value) == parseInt(valor) || parseInt(PDC[C[i].value].options[r].value) == parseInt(valor)) {

              PD[C[i].value].options[r].disabled  = true;
              PDC[C[i].value].options[r].disabled = true;
            }

            if (parseInt(PD[C[i].value].options[r].value) == parseInt(valorant) || parseInt(PDC[C[i].value].options[r].value) == parseInt(valorant)) {

              PD[C[i].value].options[r].disabled  = false;
              PDC[C[i].value].options[r].disabled = false;
            }
          }
        }else{

          for (r=0; r < tamPD; r++) {

            if (parseInt(PD[C[i].value].options[r].value) == parseInt(valor) || parseInt(PDC[C[i].value].options[r].value) == parseInt(valor)) {

              PD[C[i].value].options[r].selected  = true;
              PDC[C[i].value].options[r].selected = true;
            }

            if (parseInt(PD[C[i].value].options[r].value) == parseInt(valorant) || parseInt(PDC[C[i].value].options[r].value) == parseInt(valorant)) {

              PD[C[i].value].options[r].selected  = false;
              PDC[C[i].value].options[r].selected = false;
            }
          }
        }
      }
    }
   eval("document.form1."+campo+".value = valor");
  }

  function js_processar(botao) {

    if (document.form1.codetapadestino.value == "") {

      alert("Informe a Etapa na turma de destino!");
      return false;
    }

    var lTurnoSelecionado = false;
    var aTurnosReferentes = parent.document.querySelectorAll('.TurmaTurnoReferente');
    var aTurnosSelecionados = [];

    for( var iContador = 0; iContador < aTurnosReferentes.length; iContador++ ) {

      if ( aTurnosReferentes[iContador].checked ) {

        aTurnosSelecionados.push( aTurnosReferentes[iContador].value );
        lTurnoSelecionado = true;
      }
    }

    if ( !lTurnoSelecionado ) {

      alert('Selecione ao menos um turno.');
      return;
    }

    if (document.form1.datamatricula.value == "") {

      alert("Informe a data para matricular o aluno!");
      document.form1.datamatricula.focus();
      document.form1.datamatricula.style.backgroundColor='#99A9AE';
      return false;
    } else {

      datamat = document.form1.datamatricula_ano.value+"-"+document.form1.datamatricula_mes.value+"-"+document.form1.datamatricula_dia.value;
      dataini = parent.document.form1.ed52_d_inicio.value;
      datafim = parent.document.form1.ed52_d_fim.value;
      check   = js_validata(datamat,dataini,datafim);

      if (check == false) {

        data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
        data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);
        alert("Data da matrícula fora do periodo do calendario ( "+data_ini+" a "+data_fim+" ).");
        document.form1.datamatricula.focus();
        document.form1.datamatricula.style.backgroundColor='#99A9AE';
        return false;
      }

      datamatriculaorig = parent.document.form1.datamatriculaorig.value.substr(6,4)+""+parent.document.form1.datamatriculaorig.value.substr(3,2)+""+parent.document.form1.datamatriculaorig.value.substr(0,2);
      datasaidaorig = parent.document.form1.datasaidaorig.value.substr(6,4)+""+parent.document.form1.datasaidaorig.value.substr(3,2)+""+parent.document.form1.datasaidaorig.value.substr(0,2);
      datamat  = datamat.substr(0,4)+''+datamat.substr(5,2)+''+datamat.substr(8,2);

      if (datamatriculaorig != "") {

        if (parseInt(datamatriculaorig) > parseInt(datamat)) {

          alert("Data da Matrícula menor que a data da matrícula anterior do aluno!");
          document.form1.datamatricula.focus();
          document.form1.datamatricula.style.backgroundColor='#99A9AE';
          return false;
        }
      }

      if (datasaidaorig != "") {
        if (parseInt(datasaidaorig) > parseInt(datamat)) {
          alert("Data da Matrícula menor que a data de saída da matrícula anterior do aluno!");
          document.form1.datamatricula.focus();
          document.form1.datamatricula.style.backgroundColor='#99A9AE';
          return false;
        }
      }
    }

    RO       = document.form1.regenciaorigem;
    RD       = document.form1.regenciadestino;
    RC       = document.form1.regorigemdescr;
    PO       = document.form1.periodoorigem;
    PD       = document.form1.periododestino;
    PC       = document.form1.perorigemdescr;
    tamRO    = RO.length;
    tamRO    = tamRO==undefined?1:tamRO;
    regequiv = "";
    sepreg   = "";
    msgreg   = "Atenção:\nAs informações das seguintes disciplinas não serão transportadas, pois as mesmas não contém disciplinas equivalentes na turma de destino:\n\n";
    regnull  = false;

    for (i=0; i < tamRO; i++) {

      if (tamRO == 1) {

        if (RD.value != "") {

          if (RD.value != 0) {

            regequiv += sepreg+RO.value+"|"+RD.value;
            sepreg    = "X";
          } else {

            tamRD = document.form1.regenciadestino.options.length;
            for (t=2; t < tamRD;t++) {

             regequiv += sepreg+RO.value+"|"+RD.options[t].value;
             sepreg    = "X";
            }
          }
        } else {

          msgreg += RC.value+"\n";
          regnull = true;
        }
      }else{

        if (RD[i].value != "") {

          regequiv += sepreg+RO[i].value+"|"+RD[i].value;
          sepreg    = "X";
        } else {

          msgreg += RC[i].value+"\n";
          regnull = true;
        }
      }
    }

    tamPO    = PO.length;
    tamPO    = tamPO==undefined?1:tamPO;
    perequiv = "";
    sepper   = "";
    msgper   = "Atenção:\nAs informações dos seguintes períodos de avaliação não serão transportadas, pois os mesmos não contém períodos de avaliação equivalentes na turma de destino:\n\n";
    pernull  = false;

    for (i=0; i < tamPO;i++) {

      if (tamPO == 1) {

        if (PD.value != "") {

          perequiv += sepper+PO.value+"|"+PD.value;
          sepper    = "X";
        } else {

          msgper += PC.value+"\n";
          pernull = true;
        }
      } else {

        if (PD[i].value != "") {

          perequiv += sepper+PO[i].value+"|"+PD[i].value;
          sepper    = "X";
        } else {

          msgper += PC[i].value+"\n";
          pernull = true;
        }
      }
    }

    msggeral = "";

    if (regnull == true) {
      msggeral += msgreg+"\n";
    }

    if (pernull == true) {
      msggeral += msgper;
    }

    tamRO    = RO.length;
    tamRO    = tamRO==undefined?1:tamRO;
    regselec = false;

    for (t=0; t < tamRO; t++) {

      if (tamRO == 1) {

        if (RD.value != "") {

          regselec = true;
          break;
        }
      } else {

        if (RD[t].value != "") {

          regselec = true;
          break;
        }
      }
    }

    if (regselec == false && document.form1.import.value == "S") {

     alert("Informe alguma disciplina da turma de destino para receber as informações da origem!");
     return false;
    }

    tamPO    = PO.length;
    tamPO    = tamPO==undefined?1:tamPO;
    perselec = false;

    for (t=0; t < tamPO; t++) {

      if (tamPO == 1) {

        if (PD.value != "") {

          perselec = true;
          break;
        }
      } else {

        if (PD[t].value != "") {
          perselec = true;
          break;
        }
      }
    }

    if (perselec == false && document.form1.import.value == "S") {

      alert("Informe algum período de avaliação da turma de destino para receber as informações da origem!");
      return false;
    }

    var sGetMatricula  = "edu1_matriculatransf002.php?incluir&regequiv=" + regequiv + "&perequiv=" + perequiv;
        sGetMatricula += "&turmaorigem=<?=$turmaorigem?>&turmadestino=<?=$turmadestino?>&ed103_i_codigo=<?=$ed103_i_codigo?>";
        sGetMatricula += "&importaprov=" + document.form1.import.value + "&data=" + document.form1.datamatricula.value;
        sGetMatricula += "&codetapadestino=" + document.form1.codetapadestino.value + "&matricula=<?=$matricula?>";
        sGetMatricula += "&aTurnosSelecionados=" + aTurnosSelecionados;

    if (botao == 2) {
      sGetMatricula += "&novamatricula=<?php echo isset($matriculaante) ? $matriculaante : '' ?>";
    } else if (botao == 3) {
      sGetMatricula += sGetMatricula + "&reativar=<?php echo isset($matriculaante) ? $matriculaante : '' ?>";
    }

    if (msggeral != "" && document.form1.import.value == "S") {

      if (confirm(msggeral+"\n\nConfirmar Matrícula do aluno?")) {

        <? if (isset($matriculaante)){ ?>
          document.form1.novamatricula.style.visibility = "hidden";
        <? } else {?>
          document.form1.incluir.style.visibility = "hidden";
        <? } ?>

        location.href = sGetMatricula;
      }
    } else {

      <? if (isset($matriculaante)) { ?>
       document.form1.novamatricula.style.visibility = "hidden";
      <? } else { ?>
       document.form1.incluir.style.visibility = "hidden";
      <? } ?>

      location.href = sGetMatricula;
    }
  }

  function js_processar2(){

    if (document.form1.codetapadestino.value == "") {

      alert("Informe a Etapa na turma de destino!");
      return false;
    }

    if (document.form1.datamatricula.value == "") {

      alert("Informe a data para matricular o aluno!");
      document.form1.datamatricula.focus();
      document.form1.datamatricula.style.backgroundColor='#99A9AE';
      return false;
    } else {

      datamat = document.form1.datamatricula_ano.value+"-"+document.form1.datamatricula_mes.value+"-"+document.form1.datamatricula_dia.value;
      dataini = parent.document.form1.ed52_d_inicio.value;
      datafim = parent.document.form1.ed52_d_fim.value;
      check   = js_validata(datamat,dataini,datafim);

      if (check == false) {

        data_ini = dataini.substr(8,2)+"/"+dataini.substr(5,2)+"/"+dataini.substr(0,4);
        data_fim = datafim.substr(8,2)+"/"+datafim.substr(5,2)+"/"+datafim.substr(0,4);
        alert("Data da matrícula fora do periodo do calendario ( "+data_ini+" a "+data_fim+" ).");
        document.form1.datamatricula.focus();
        document.form1.datamatricula.style.backgroundColor='#99A9AE';
        return false;
      }

      datamatriculaorig = parent.document.form1.datamatriculaorig.value.substr(6,4)+""+parent.document.form1.datamatriculaorig.value.substr(3,2)+""+parent.document.form1.datamatriculaorig.value.substr(0,2);
      datasaidaorig     = parent.document.form1.datasaidaorig.value.substr(6,4)+""+parent.document.form1.datasaidaorig.value.substr(3,2)+""+parent.document.form1.datasaidaorig.value.substr(0,2);
      datamat           = datamat.substr(0,4)+''+datamat.substr(5,2)+''+datamat.substr(8,2);

      if (datamatriculaorig != "") {

        if (parseInt(datamatriculaorig) > parseInt(datamat)) {

          alert("Data da Matrícula menor que a data da matrícula anterior do aluno!");
          document.form1.datamatricula.focus();
          document.form1.datamatricula.style.backgroundColor='#99A9AE';
          return false;
        }
      }

      if (datasaidaorig != "") {

        if (parseInt(datasaidaorig) > parseInt(datamat)) {

          alert("Data da Matrícula menor que a data de saída da matrícula anterior do aluno!");
          document.form1.datamatricula.focus();
          document.form1.datamatricula.style.backgroundColor='#99A9AE';
          return false;
        }
      }
    }
    document.form1.incluir2.style.visibility = "hidden";

    var sGet  = "incluir2";
        sGet += "&matricula=<?=$matricula?>";
        sGet += "&turmaorigem=<?=$turmaorigem?>";
        sGet += "&turmadestino=<?=$turmadestino?>";
        sGet += "&ed103_i_codigo=<?=$ed103_i_codigo?>";
        sGet += "&data="            + document.form1.datamatricula.value;
        sGet += "&codetapadestino=" + document.form1.codetapadestino.value;
        sGet += "&sTurnoReferente=" + document.form1.sTurnoReferente.value;

    location.href = "edu1_matriculatransf002.php?"+sGet;
  }

  function js_importar(valor) {

    if (valor == "N") {
      alert("Importar aproveitamento da turma de origem está marcado como NÃO. Caso este aluno tenha algum aproveitamento na turma de origem, este terá quer ser digitado manualmente!");
    }
  }
  </script>

  <?
    }

  if (isset($incluir)) {

    $lErroTransacao = false;
    db_inicio_transacao();

    $sCamposMatricula = "turma.ed57_i_escola as escola_origem, ed60_i_aluno";
    $sSqlMatricula    = $clmatricula->sql_query( "", $sCamposMatricula, "", "ed60_i_codigo = {$matricula}" );
    $result           = $clmatricula->sql_record( $sSqlMatricula );
    db_fieldsmemory($result, 0);

    /**
     * Pesquisamos os dados da mascara da nota da escola destino.
     * caso as duas mascaras (escola destino, e escola origem sejam diferentes, devemos
     * obrigar o usuário a convertar a nota para o padrao da escola.
    */
    $sWhereMascara         = "ed315_escola = {$escola_origem}";
    $sMascaraOrigem        = '';
    $sSqlMascaraNotaOrigem = $oDaoAvaliacaoRegra->sql_query(null, "db77_estrut", null, $sWhereMascara);
    $rsMascaraOrigem       = $oDaoAvaliacaoRegra->Sql_record($sSqlMascaraNotaOrigem);

    if ($oDaoAvaliacaoRegra->numrows > 0) {

      $sMascaraOrigem = db_utils::fieldsMemory($rsMascaraOrigem, 0)->db77_estrut;
      $sMascaraOrigem = str_replace("0", "9",$sMascaraOrigem);
    }

    $sWhereMatricula0 = "ed60_i_turma = {$turmadestino} AND ed60_i_aluno = {$ed60_i_aluno} AND ed60_c_ativa ='S'";
    $sSqlMatricula0   = $clmatricula->sql_query_file( "", "ed60_i_codigo as codmatrjatem", "", $sWhereMatricula0 );
    $result0          = $clmatricula->sql_record( $sSqlMatricula0 );

    if ($clmatricula->numrows > 0) {
      db_fieldsmemory($result0, 0);
    } else {
      $codmatrjatem = "";
    }

    $sSqlTurma = $clturma->sql_query( "", "ed57_i_calendario, ed57_i_escola", "", "ed57_i_codigo = {$turmadestino}" );
    $result    = $clturma->sql_record( $sSqlTurma );
    db_fieldsmemory($result, 0);

    if ($importaprov == "S") {

      $periodos                     = explode("X",$perequiv);
      $msg_conversao                = "";
      $sep_conversao                = "";
      $aDisciplinasValidadas        = array();
      $aPeriodosDestinoSelecionados = array();

      for ($x = 0; $x < count($periodos); $x++) {

        $divideperiodos                 = explode("|",$periodos[$x]);
        $periodoorigem                  = $divideperiodos[0];
        $periododestino                 = $divideperiodos[1];
        $aPeriodosDestinoSelecionados[] = $periododestino;

        $sCamposProcAvaliacao1 = "ed37_c_tipo as tipoorigem,ed37_i_maiorvalor as mvorigem";
        $sSqlProcAvaliacao1    = $clprocavaliacao->sql_query( "", $sCamposProcAvaliacao1,"", "ed41_i_codigo = {$periodoorigem}" );
        $result_per1           = $clprocavaliacao->sql_record( $sSqlProcAvaliacao1 );
        db_fieldsmemory($result_per1, 0);

        $regencias = explode("X", $regequiv);

        for ($r = 0; $r < count($regencias); $r++) {

          $divideregencias = explode("|",$regencias[$r]);
          $regenciaorigem  = $divideregencias[0];
          $regenciadestino = $divideregencias[1];
          $sWhereDiario    = "ed95_i_regencia = {$regenciaorigem} AND ed95_i_aluno = {$ed60_i_aluno}";
          $sSqlDiario      = $cldiario->sql_query_file( "", "ed95_i_codigo as coddiarioorigem", "", $sWhereDiario );
          $result11        = $cldiario->sql_record( $sSqlDiario );

          $oRegenciaOrigem  = RegenciaRepository::getRegenciaByCodigo( $regenciaorigem );
          $oRegenciaDestino = RegenciaRepository::getRegenciaByCodigo( $regenciadestino );

          $aElementosOrigem      = $oRegenciaOrigem->getProcedimentoAvaliacao()->getElementos();
          $oElementoPeriodoAtual = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo( $periodoorigem );
          $oElementoAvaliacao    = null;

          foreach( $aElementosOrigem as $oAvaliacaoPeriodicaOrigem ) {

            $iPeriodoAvaliacaoAtual = $oElementoPeriodoAtual->getPeriodoAvaliacao()->getCodigo();
            if (    $oAvaliacaoPeriodicaOrigem instanceof AvaliacaoPeriodica
                 && $oAvaliacaoPeriodicaOrigem->getPeriodoAvaliacao()->getCodigo() == $iPeriodoAvaliacaoAtual
               ) {
              $oElementoAvaliacao = $oAvaliacaoPeriodicaOrigem;
            }
          }

          /**
           * Identifica o código do periodo de avaliação do procedimento de avaliação da regencia
           */
          $aElementosRegencia       = $oRegenciaDestino->getProcedimentoAvaliacao()->getElementos();
          $oPeriodoAvaliacaoDestino = AvaliacaoPeriodicaRepository::getAvaliacaoPeriodicaByCodigo( $periododestino );
          $iOrdemSequencial         = $oPeriodoAvaliacaoDestino->getOrdemSequencia();
          foreach( $aElementosRegencia as $oAvaliacaoPeriodicaRegencia ) {

            if (    $oAvaliacaoPeriodicaRegencia instanceof AvaliacaoPeriodica
                 && $oAvaliacaoPeriodicaRegencia->getOrdemSequencia() == $iOrdemSequencial ) {
              $periododestino = $oAvaliacaoPeriodicaRegencia->getCodigo();
            }
          }

          /**
           * Tive que descer a busca dos dados da forma de avaliação vinculáda ao período de avaliação
           * para verificar do período de avaliação vinculádo a regencia.
           */
          $sCamposProcAvaliacao  = "ed09_i_codigo, ed09_c_descr as perdestdescricao, ed37_c_tipo as tipodestino";
          $sCamposProcAvaliacao .= ", ed37_i_maiorvalor as mvdestino";
          $sWhereProcAvaliacao   = "ed41_i_codigo = {$periododestino}";
          $sSqlProcAvalicao      = $clprocavaliacao->sql_query( "", $sCamposProcAvaliacao, "", $sWhereProcAvaliacao );
          $result_per            = $clprocavaliacao->sql_record( $sSqlProcAvalicao );
          db_fieldsmemory($result_per, 0);

          $sWherePeriodoCalendario = "ed53_i_calendario = {$ed57_i_calendario} AND ed53_i_periodoavaliacao = {$ed09_i_codigo}";
          $sSqlPeriodoCalendario   = $clperiodocalendario->sql_query_file( "", "ed53_d_fim, ed53_d_inicio", "", $sWherePeriodoCalendario );
          $result_fimper           = $clperiodocalendario->sql_record( $sSqlPeriodoCalendario );

          if ($clperiodocalendario->numrows > 0) {
            db_fieldsmemory($result_fimper, 0);
          }

          if ($cldiario->numrows > 0) {
            db_fieldsmemory($result11, 0);
          } else {
            $coddiarioorigem = 0;
          }

          $sWhereDiario2 = "ed95_i_regencia = {$regenciadestino} AND ed95_i_aluno = {$ed60_i_aluno}";
          $sSqlDiario2   = $cldiario->sql_query_file( "", "ed95_i_codigo", "", $sWhereDiario2 );
          $result2       = $cldiario->sql_record( $sSqlDiario2 );

          if ($cldiario->numrows == 0) {

            $cldiario->ed95_c_encerrado  = "N";
            $cldiario->ed95_i_escola     = $ed57_i_escola;
            $cldiario->ed95_i_calendario = $ed57_i_calendario;
            $cldiario->ed95_i_aluno      = $ed60_i_aluno;
            $cldiario->ed95_i_serie      = $codetapadestino;
            $cldiario->ed95_i_regencia   = $regenciadestino;
            $cldiario->incluir(null);

            $ed95_i_codigo               = $cldiario->ed95_i_codigo;
          } else {

            db_fieldsmemory($result2,0);
            $sql21    = "UPDATE diario SET
                                ed95_c_encerrado = 'N'
                          WHERE ed95_i_codigo = {$ed95_i_codigo}";
            $result21 = db_query($sql21);
          }

          $sCamposAmparo  = "ed81_i_codigo as codamparoorigem, ed81_i_justificativa, ed81_c_todoperiodo";
          $sCamposAmparo .= ", ed81_i_convencaoamp, ed81_c_aprovch";
          $sSqlAmparo     = $clamparo->sql_query_file( "", $sCamposAmparo, "", "ed81_i_diario = {$coddiarioorigem}" );
          $result6        = $clamparo->sql_record( $sSqlAmparo );

          if ($clamparo->numrows > 0) {

            db_fieldsmemory($result6, 0);

            $sSqlAmparo7 = $clamparo->sql_query_file( "", "ed81_i_codigo", "", "ed81_i_diario = {$ed95_i_codigo}" );
            $result7     = $clamparo->sql_record( $sSqlAmparo7 );

            if ($clamparo->numrows == 0) {

              $clamparo->ed81_i_diario        = $ed95_i_codigo;
              $clamparo->ed81_c_aprovch       = $ed81_c_aprovch;
              $clamparo->ed81_c_todoperiodo   = $ed81_c_todoperiodo;
              $clamparo->ed81_i_justificativa = $ed81_i_justificativa;
              $clamparo->ed81_i_convencaoamp  = $ed81_i_convencaoamp;
              $clamparo->incluir(null);
            } else {

              db_fieldsmemory($result7, 0);
              $clamparo->ed81_i_diario        = $ed95_i_codigo;
              $clamparo->ed81_c_aprovch       = $ed81_c_aprovch;
              $clamparo->ed81_c_todoperiodo   = $ed81_c_todoperiodo;
              $clamparo->ed81_i_justificativa = $ed81_i_justificativa;
              $clamparo->ed81_i_convencaoamp  = $ed81_i_convencaoamp;
              $clamparo->ed81_i_codigo        = $ed81_i_codigo;
              $clamparo->alterar($ed81_i_codigo);
            }
          }

          $sSqlDiarioFinal = $cldiariofinal->sql_query_file( "", "ed74_i_diario", "", "ed74_i_diario = {$ed95_i_codigo}" );
          $result9         = $cldiariofinal->sql_record( $sSqlDiarioFinal );

          if ($cldiariofinal->numrows == 0) {

            $cldiariofinal->ed74_i_diario = $ed95_i_codigo;
            $cldiariofinal->incluir(null);
          }

          $sCamposDiarioAvaliacao3  = "ed72_i_codigo as codavalorigem, ed72_i_numfaltas, ed72_i_valornota";
          $sCamposDiarioAvaliacao3 .= ", ed72_c_valorconceito, ed72_t_parecer, ed72_c_aprovmin, ed72_c_amparo";
          $sCamposDiarioAvaliacao3 .= ", ed72_t_obs, ed72_i_escola, ed72_c_tipo, ed72_c_convertido";
          $sWhereDiarioAvaliacao3   = "ed72_i_diario = {$coddiarioorigem} AND ed72_i_procavaliacao = {$oElementoAvaliacao->getCodigo()}";
          $sSqlDiarioAvaliacao3     = $cldiarioavaliacao->sql_query_file( "", $sCamposDiarioAvaliacao3, "", $sWhereDiarioAvaliacao3 );
          $result3                  = $cldiarioavaliacao->sql_record( $sSqlDiarioAvaliacao3 );

          if ($cldiarioavaliacao->numrows > 0) {
            db_fieldsmemory($result3, 0);
          } else {

            $codavalorigem        = "";
            $ed72_i_numfaltas     = null;
            $ed72_i_valornota     = null;
            $ed72_c_valorconceito = "";
            $ed72_t_parecer       = "";
            $ed72_c_aprovmin      = "N";
            $ed72_c_amparo        = "N";
            $ed72_t_obs           = "";
            $ed72_i_escola        = $escola;
            $ed72_c_tipo          = "M";
            $ed72_c_convertido    = "N";
          }

          if (($ed72_i_valornota == "" && $ed72_c_valorconceito == "" && $ed72_t_parecer == "")) {

            $ed72_i_escola = $escola;
            $ed72_c_tipo   = "M";
          }

          $sWhereTransfAprov = "ed251_i_diariodestino = ".($codavalorigem == "" ? 0 : $codavalorigem);
          $sSqlTransfAprov = $cltransfaprov->sql_query( "", "ed251_i_codigo as pranada", "", $sWhereTransfAprov );
          $result_tr       = $cltransfaprov->sql_record( $sSqlTransfAprov );

          $sTipoOrigem  = $oRegenciaOrigem->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo();
          $sTipoDestino = $oRegenciaDestino->getProcedimentoAvaliacao()->getFormaAvaliacao()->getTipo();

          $iMaiorValorOrigem  = $oRegenciaOrigem->getProcedimentoAvaliacao()->getFormaAvaliacao()->getMaiorValor();
          $iMaiorValorDestino = $oRegenciaDestino->getProcedimentoAvaliacao()->getFormaAvaliacao()->getMaiorValor();

          if(    $sTipoOrigem != $sTipoDestino
              || ( $sTipoOrigem == $sTipoDestino && $iMaiorValorOrigem != $iMaiorValorDestino )
              || ( (string) $sMascaraInstituicacao != (string) $sMascaraOrigem )
            ) {

            if( !in_array( $oRegenciaOrigem->getDisciplina()->getCodigoDisciplina(), $aDisciplinasValidadas ) ) {

              $aDisciplinasValidadas[] = $oRegenciaOrigem->getDisciplina()->getCodigoDisciplina();
              $msg_conversao          .= $sep_conversao . " " . $oRegenciaOrigem->getDisciplina()->getNomeDisciplina();
              $sep_conversao           = ",";
            }

            if (    ( $oRegenciaOrigem->getTurma()->getEscola()->getCodigo() != $escola && $ed72_c_tipo == "M" )
                 || $cltransfaprov->numrows > 0
                 || ( (string) $sMascaraInstituicacao != (string) $sMascaraOrigem && $ed72_i_valornota != "" ) ) {
              $ed72_c_convertido = "S";
            } else {
              $ed72_c_convertido = "N";
            }
          } else {
            $ed72_c_convertido = "N";
          }

          $sWhereDiarioAvaliacao4 = "ed72_i_diario = {$ed95_i_codigo} AND ed72_i_procavaliacao = {$periododestino}";
          $sSqlDiarioAvaliacao4   = $cldiarioavaliacao->sql_query_file( "", "ed72_i_codigo", "", $sWhereDiarioAvaliacao4 );
          $result4                = $cldiarioavaliacao->sql_record( $sSqlDiarioAvaliacao4 );

          if ($cldiarioavaliacao->numrows == 0) {

            $cldiarioavaliacao->ed72_i_diario        = $ed95_i_codigo;
            $cldiarioavaliacao->ed72_i_procavaliacao = $periododestino;
            $cldiarioavaliacao->ed72_i_numfaltas     = $ed72_i_numfaltas;
            $cldiarioavaliacao->ed72_i_valornota     = $ed72_i_valornota;
            $cldiarioavaliacao->ed72_c_valorconceito = $ed72_c_valorconceito;
            $cldiarioavaliacao->ed72_t_parecer       = $ed72_t_parecer;
            $cldiarioavaliacao->ed72_c_aprovmin      = $ed72_c_aprovmin;
            $cldiarioavaliacao->ed72_c_amparo        = $ed72_c_amparo;
            $cldiarioavaliacao->ed72_t_obs           = $ed72_t_obs;
            $cldiarioavaliacao->ed72_i_escola        = $ed72_i_escola;
            $cldiarioavaliacao->ed72_c_tipo          = $ed72_c_tipo;
            $cldiarioavaliacao->ed72_c_convertido    = $ed72_c_convertido;
            $cldiarioavaliacao->incluir(null);

            $ed72_i_codigo = $cldiarioavaliacao->ed72_i_codigo;
          } else {

            db_fieldsmemory($result4,0);
            $cldiarioavaliacao->ed72_i_diario        = $ed95_i_codigo;
            $cldiarioavaliacao->ed72_i_procavaliacao = $periododestino;
            $cldiarioavaliacao->ed72_i_numfaltas     = $ed72_i_numfaltas;
            $cldiarioavaliacao->ed72_i_valornota     = $ed72_i_valornota;
            $cldiarioavaliacao->ed72_c_valorconceito = $ed72_c_valorconceito;
            $cldiarioavaliacao->ed72_t_parecer       = $ed72_t_parecer;
            $cldiarioavaliacao->ed72_c_aprovmin      = $ed72_c_aprovmin;
            $cldiarioavaliacao->ed72_c_amparo        = $ed72_c_amparo;
            $cldiarioavaliacao->ed72_t_obs           = $ed72_t_obs;
            $cldiarioavaliacao->ed72_i_escola        = $ed72_i_escola;
            $cldiarioavaliacao->ed72_c_tipo          = $ed72_c_tipo;
            $cldiarioavaliacao->ed72_c_convertido    = $ed72_c_convertido;
            $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
            $cldiarioavaliacao->alterar($ed72_i_codigo);
          }

          if(    ($ed72_i_escola != $escola && $ed72_c_tipo == "M")
              || $cltransfaprov->numrows > 0
              || ((string)"$sMascaraInstituicacao" != (string)"$sMascaraOrigem")) {

            $cltransfaprov->ed251_i_diarioorigem  = $codavalorigem == "" ? null : $codavalorigem;
            $cltransfaprov->ed251_i_diariodestino = $ed72_i_codigo;
            $cltransfaprov->incluir(null);
          }

          if ($codavalorigem != "") {

            $sWhereParecerAval = "ed93_i_diarioavaliacao = {$codavalorigem}";
            $sSqlParecerAval   = $clpareceraval->sql_query_file( "", "ed93_t_parecer", "", $sWhereParecerAval );
            $result41          = $clpareceraval->sql_record( $sSqlParecerAval );
            $linhas41          = $clpareceraval->numrows;

            if ($linhas41 > 0) {

              $clpareceraval->excluir( "", "ed93_i_diarioavaliacao = {$ed72_i_codigo}" );
              for ($w = 0; $w < $linhas41; $w++) {

               db_fieldsmemory($result41, $w);
               $clpareceraval->ed93_i_diarioavaliacao = $ed72_i_codigo;
               $clpareceraval->ed93_t_parecer         = $ed93_t_parecer;
               $clpareceraval->incluir(null);
              }
            }

            $sWhereAbonoFalta = "ed80_i_diarioavaliacao = {$codavalorigem}";
            $sSqlAbonoFalta   = $clabonofalta->sql_query_file( "", "ed80_i_codigo", "", $sWhereAbonoFalta );
            $result42         = $clabonofalta->sql_record( $sSqlAbonoFalta );
            $linhas42         = $clabonofalta->numrows;

            if ($linhas42 > 0) {

              for ($w = 0; $w < $linhas42; $w++) {

                db_fieldsmemory($result42,$w);
                $clabonofalta->ed80_i_diarioavaliacao = $ed72_i_codigo;
                $clabonofalta->ed80_i_codigo          = $ed80_i_codigo;
                $clabonofalta->alterar($ed80_i_codigo);
              }
            }
          }
        }
      }
    }

    $sCamposTurma  = "ed57_c_descr as ed57_c_descrorig, ed57_i_base as baseorig, ed57_i_calendario as calorig";
    $sCamposTurma .= ", ed57_i_turno as turnoorig, escola.ed18_c_nome as nomeescolaorig, ed31_i_curso as cursoorig";
    $sCamposTurma .= ", ed57_i_escola as escolaorig";
    $sSqlTurma     = $clturma->sql_query( "", $sCamposTurma, "", "ed57_i_codigo = {$turmaorigem}" );
    $result_orig   = $clturma->sql_record( $sSqlTurma );
    db_fieldsmemory($result_orig, 0);

    $sCamposAlunoPossib = "ed56_i_codigo, ed79_c_resulant";
    $sSqlAlunoPossib    = $clalunopossib->sql_query( "", $sCamposAlunoPossib, "", "ed56_i_aluno = {$ed60_i_aluno}" );
    $result_alu         = $clalunopossib->sql_record( $sSqlAlunoPossib );
    db_fieldsmemory($result_alu, 0);

    $sCamposTurmaDestino  = "ed57_c_descr as ed57_c_descrdest, ed57_i_base as basedest, ed57_i_calendario as caldest";
    $sCamposTurmaDestino .= ", ed57_i_turno as turnodest, ed57_i_escola as escoladest";
    $sSqlTurmaDestino     = $clturma->sql_query_file( "", $sCamposTurmaDestino, "", "ed57_i_codigo = {$turmadestino}" );
    $result_dest          = $clturma->sql_record( $sSqlTurmaDestino );
    db_fieldsmemory($result_dest, 0);

    $sSqlTurmaSerieRegimeMat = $clturmaserieregimemat->sql_query( "", "ed223_i_serie", "ed223_i_ordenacao", "ed220_i_turma = {$turmadestino}" );
    $result_etp              = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );
    db_fieldsmemory($result_etp, 0);

    $sSqlMatricula = $clmatricula->sql_query_file( "", "max(ed60_i_numaluno)", "", "ed60_i_turma = {$turmadestino}" );
    $result1       = $clmatricula->sql_record( $sSqlMatricula );
    db_fieldsmemory($result1, 0);

    $max = $max == "" ? "null" : ( $max + 1 );

    if (isset($novamatricula)) {

      $sql3 = "UPDATE matricula SET
                      ed60_c_concluida = 'S',
                      ed60_c_ativa     = 'N'
                WHERE ed60_i_codigo = {$novamatricula}";
      $query3 = db_query($sql3);

      $clmatricula->ed60_d_datamodif     =  substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
      $clmatricula->ed60_d_datamatricula = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
      $clmatricula->ed60_d_datamodifant  = null;
      $clmatricula->ed60_d_datasaida     = "null";
      $clmatricula->ed60_t_obs           = "";
      $clmatricula->ed60_i_aluno         = $ed60_i_aluno;
      $clmatricula->ed60_i_turma         = $turmadestino;
      $clmatricula->ed60_i_turmaant      = $turmaorigem;
      $clmatricula->ed60_c_rfanterior    = $ed79_c_resulant;
      $clmatricula->ed60_i_numaluno      = $max;
      $clmatricula->ed60_c_situacao      = "MATRICULADO";
      $clmatricula->ed60_c_concluida     = "N";
      $clmatricula->ed60_c_ativa         = "S";
      $clmatricula->ed60_c_tipo          = "N";
      $clmatricula->ed60_c_parecer       = "N";
      $clmatricula->incluir(null);

      $matrmov = $clmatricula->ed60_i_codigo;

      for ($rr = 0; $rr < $clturmaserieregimemat->numrows; $rr++) {

        db_fieldsmemory($result_etp, $rr);

        if ($codetapadestino == $ed223_i_serie) {
          $origem = "S";
        } else {
          $origem = "N";
        }

        $clmatriculaserie->ed221_i_matricula = $matrmov;
        $clmatriculaserie->ed221_i_serie     = $ed223_i_serie;
        $clmatriculaserie->ed221_c_origem    = $origem;
        $clmatriculaserie->incluir(null);
      }

      $sql21    = "UPDATE diario SET
                          ed95_c_encerrado = 'N'
                    WHERE ed95_i_aluno = {$ed60_i_aluno}
                      AND ed95_i_regencia in (select ed59_i_codigo
                                                from regencia
                                               where ed59_i_turma = {$turmadestino})";
      $result21 = db_query($sql21);
    } else {

      if ($codmatrjatem != "") {

        $data_modif = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
        $sql        = "UPDATE matricula SET
                              ed60_i_turmaant      = {$turmaorigem},
                              ed60_c_rfanterior    = '{$ed79_c_resulant}',
                              ed60_d_datamodif     = '{$data_modif}',
                              ed60_d_datamatricula = '{$data_modif}',
                              ed60_d_datamodifant  = null,
                              ed60_d_datasaida     = null,
                              ed60_c_concluida     = 'N',
                              ed60_c_situacao      = 'MATRICULADO'
                        WHERE ed60_i_codigo = {$codmatrjatem}";
        $query      = db_query($sql);

        $matrmov    = $codmatrjatem;
        $sql21      = "UPDATE diario SET
                              ed95_c_encerrado = 'N'
                        WHERE ed95_i_aluno = {$ed60_i_aluno}
                          AND ed95_i_regencia in (select ed59_i_codigo
                                                    from regencia
                                                   where ed59_i_turma = {$turmadestino})";
        $result21   = db_query($sql21);
      } else {

        $clmatricula->ed60_d_datamodif     = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
        $clmatricula->ed60_d_datamatricula = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
        $clmatricula->ed60_d_datamodifant  = null;
        $clmatricula->ed60_d_datasaida     = "null";
        $clmatricula->ed60_t_obs           = "";
        $clmatricula->ed60_i_aluno         = $ed60_i_aluno;
        $clmatricula->ed60_i_turma         = $turmadestino;
        $clmatricula->ed60_i_turmaant      = $turmaorigem;
        $clmatricula->ed60_c_rfanterior    = $ed79_c_resulant;
        $clmatricula->ed60_i_numaluno      = $max;
        $clmatricula->ed60_c_situacao      = "MATRICULADO";
        $clmatricula->ed60_c_concluida     = "N";
        $clmatricula->ed60_c_ativa         = "S";
        $clmatricula->ed60_c_tipo          = "N";
        $clmatricula->ed60_c_parecer       = "N";
        $clmatricula->incluir(null);

        $matrmov = $clmatricula->ed60_i_codigo;

        for ($rr = 0; $rr < $clturmaserieregimemat->numrows; $rr++) {

          db_fieldsmemory($result_etp, $rr);

          if ($codetapadestino == $ed223_i_serie) {
            $origem = "S";
          } else {
            $origem = "N";
          }

          $clmatriculaserie->ed221_i_matricula = $matrmov;
          $clmatriculaserie->ed221_i_serie     = $ed223_i_serie;
          $clmatriculaserie->ed221_c_origem    = $origem;
          $clmatriculaserie->incluir(null);
        }
      }

      /**
       * Início da lógica para criação de diarioavaliacao do aluno, para os casos em que a turma de origem possui
       * menos períodos do que a turma de destino, quando selecionado para importar o aproveitamento
       */
      if( $importaprov == "S" ) {

        $oMatricula             = MatriculaRepository::getMatriculaByCodigo( $matrmov );
        $oDiario                = $oMatricula->getDiarioDeClasse();
        $aDisciplinas           = $oDiario->getDisciplinas();
        $oTurma                 = TurmaRepository::getTurmaByCodigo( $turmadestino );
        $oEtapa                 = EtapaRepository::getEtapaByCodigo( $codetapadestino );
        $oProcedimentoAvaliacao = $oTurma->getProcedimentoDeAvaliacaoDaEtapa( $oEtapa );
        $aPeriodosAvaliacao     = array();

        /**
         * Percorre os elementos da turma de destino, adicionando o código do período ao array, somente quando este for
         * uma instância de AvaliacaoPeriodica
         */
        foreach( $oProcedimentoAvaliacao->getElementos() as $oElementoAvaliacao ) {

          if( $oElementoAvaliacao instanceof ResultadoAvaliacao ) {
            continue;
          }

          $aPeriodosAvaliacao[] = $oElementoAvaliacao->getCodigo();
        }

        /**
         * Verifica a diferença entre os arrays, incrementando períodos que não tenham sido criados em diarioavaliacao
         */
        $aPeriodosFaltantes = array();
        $aPeriodosFaltantes = array_diff( $aPeriodosAvaliacao, $aPeriodosDestinoSelecionados );

        /**
         * Percorre os períodos não selecionados, e adiciona um registro em diarioavaliacao para cada disciplina existente
         * na turma de destino, vinculando ao novo diário
         */
        foreach( $aPeriodosFaltantes as $iPeriodo ) {

          foreach( $aDisciplinas as $oDiarioAvaliacaoDisciplina ) {

            $oDaoDiarioAvaliacao    = new cl_diarioavaliacao();
            $sWhereDiarioAvaliacao  = "     ed72_i_diario = {$oDiarioAvaliacaoDisciplina->getCodigoDiario()}";
            $sWhereDiarioAvaliacao .= " AND ed72_i_procavaliacao = {$iPeriodo}";
            $sSqlDiarioAvaliacao    = $oDaoDiarioAvaliacao->sql_query_file( null, "1", null, $sWhereDiarioAvaliacao );
            $rsDiarioAvaliacao      = db_query( $sSqlDiarioAvaliacao );

            if( $rsDiarioAvaliacao && pg_num_rows( $rsDiarioAvaliacao ) > 0 ) {
              continue;
            }

            $cldiarioavaliacao->ed72_i_diario        = $oDiarioAvaliacaoDisciplina->getCodigoDiario();
            $cldiarioavaliacao->ed72_i_procavaliacao = $iPeriodo;
            $cldiarioavaliacao->ed72_i_numfaltas     = null;
            $cldiarioavaliacao->ed72_i_valornota     = null;
            $cldiarioavaliacao->ed72_c_valorconceito = '';
            $cldiarioavaliacao->ed72_t_parecer       = '';
            $cldiarioavaliacao->ed72_c_aprovmin      = 'N';
            $cldiarioavaliacao->ed72_i_escola        = $escola;
            $cldiarioavaliacao->ed72_c_tipo          = 'M';
            $cldiarioavaliacao->ed72_c_convertido    = 'S';
            $cldiarioavaliacao->incluir(null);
          }
        }
      }
    }

    $oTurmaDestino    = TurmaRepository::getTurmaByCodigo( $turmadestino );
    $aVagasTurma      = $oTurmaDestino->getVagasDisponiveis();
    $lVagaDisponivel  = true;
    $aTurnosValidacao = explode( ',', $aTurnosSelecionados );

    foreach( $aTurnosValidacao as $iTurno ) {

      if( !array_key_exists( $iTurno, $aVagasTurma ) ) {
        continue;
      }

      if( $aVagasTurma[ $iTurno ] <= 0 ) {
        $lVagaDisponivel = false;
      }
    }

    if ( !$lVagaDisponivel ) {

      $lErroTransacao  = true;
      $sMensagem       = "Não há vagas para o(s) turno(s) selecionado(s).";
      db_msgbox( $sMensagem );
    }


    /**
     * Busca os vínculos da turma com os turnos referentes, com base nas opções selecionadas
     */
    $sWhereTurmaTurnoReferente = "ed336_turma = {$turmadestino} AND ed336_turnoreferente in( {$aTurnosSelecionados} )";
    $sSqlTurmaTurnoReferente   = $oDaoTurmaTurnoReferente->sql_query_file(
                                                                             null,
                                                                             "ed336_codigo",
                                                                             null,
                                                                             $sWhereTurmaTurnoReferente
                                                                         );
    $rsTurmaTurnoReferente     = db_query( $sSqlTurmaTurnoReferente );

    if( !is_resource( $rsTurmaTurnoReferente ) ) {

      $lErroTransacao  = true;
      $sMensagem       = "Erro ao buscar os turnos referentes vinculados a turma:\n";
      $sMensagem      .= $oDaoMatriculaTurnoReferente->erro_msg;

      db_msgbox( $sMensagem );
    }

    $iTotalTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );

    if ( $iTotalTurmaTurnoReferente > 0 ) {

      /**
       * Percorre os turnos vinculados e inclui um novo registro na tabela matriculaturnoreferente, vinculando a
       * matrícula ao turno da turma
       */
      for ( $iContador = 0; $iContador < $iTotalTurmaTurnoReferente; $iContador++ ) {

        $iTurmaTurnoReferente = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador )->ed336_codigo;

        $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iTurmaTurnoReferente;
        $oDaoMatriculaTurnoReferente->ed337_matricula           = $matrmov;
        $oDaoMatriculaTurnoReferente->incluir( null );

        if ( $oDaoMatriculaTurnoReferente->erro_status == "0" ) {

          $lErroTransacao  = true;
          $sMensagem       = "Erro ao salvar dados do vínculo da matrícula com o turno:\n";
          $sMensagem      .= $oDaoMatriculaTurnoReferente->erro_msg;
          db_msgbox( $sMensagem );
        }
      }
    }

    $clmatriculamov->ed229_i_matricula    = $matrmov;
    $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
    $clmatriculamov->ed229_c_procedimento = "MATRICULAR ALUNOS TRANSFERIDOS";
    $clmatriculamov->ed229_t_descr        = "ALUNO MATRICULADO NA TURMA ".trim($ed57_c_descrdest)." VINDO DA ESCOLA ".trim($nomeescolaorig);
    $clmatriculamov->ed229_d_dataevento   = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
    $clmatriculamov->ed229_c_horaevento   = date("H:i");
    $clmatriculamov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatriculamov->incluir(null);

    $sql1    = "UPDATE alunocurso SET
                       ed56_c_situacao      = 'MATRICULADO',
                       ed56_i_escola        = {$escoladest},
                       ed56_i_base          = {$basedest},
                       ed56_i_calendario    = {$caldest},
                       ed56_i_baseant       = null,
                       ed56_i_calendarioant = null,
                       ed56_c_situacaoant   = ''
                 WHERE ed56_i_codigo = {$ed56_i_codigo}";
    $result1 = db_query($sql1);

    //atualiza serie do curso
    $sql2    = "UPDATE alunopossib SET
                       ed79_i_serie    = {$codetapadestino},
                       ed79_i_turno    = {$turnodest},
                       ed79_i_turmaant = {$turmaorigem},
                       ed79_c_resulant = '{$ed79_c_resulant}',
                       ed79_c_situacao = 'A'
                 WHERE ed79_i_alunocurso = {$ed56_i_codigo}";
    $result2 = db_query($sql2);

    //atualiza historico e transfere para escola destino
    $sql3    = "UPDATE historico SET
                       ed61_i_escola = {$escoladest}
                 WHERE ed61_i_aluno = {$ed60_i_aluno}";
    $result3 = db_query($sql3);

    //atualiza situacao da transferencia para fechada(F)
    $sql4       = "UPDATE transfescolarede SET
                          ed103_c_situacao = 'F'
                    WHERE ed103_i_codigo = {$ed103_i_codigo}";
    $result4    = db_query($sql4);

    $sql_del    = "DELETE FROM docaluno
                    WHERE ed49_i_aluno = {$ed60_i_aluno}
                      AND ed49_i_escola = {$escolaorig}";
    $result_del = db_query($sql_del);

    $sql10      = "SELECT *
                     FROM escola_sequencias
                    WHERE ed129_i_escola = {$escola}";
    $result10   = db_query($sql10);
    $linhas10   = pg_num_rows($result10);

    if ($linhas10 > 0) {

      $result          = @db_query("select nextval('transflocal_ed131_i_codigo_seq')");
      $transflocal_cod = pg_result($result,0,0);
      $sql_tr          = "INSERT INTO transflocal
                          VALUES($transflocal_cod,
                                 $escoladest,
                                 $ed56_i_codigo,
                                 $cursoorig,
                                 $ed103_i_codigo,
                                 'A')";
      $result_tr       = db_query($sql_tr);
    }

    $oMatriculaAntiga = MatriculaRepository::getMatriculaByCodigo( $matricula );
    $oMatriculaAntiga->setConcluida(true);
    $oMatriculaAntiga->salvar();

    db_fim_transacao( $lErroTransacao );

    if (!$lErroTransacao && isset($msg_conversao) && @$msg_conversao != "") {

      $sMensagem  = "ATENÇÃO!\\n\\n Caso o aluno tenha algum aproveitamento nas disciplinas abaixo relacionadas";
      $sMensagem .= ", os mesmos deverão ser convertidos no Diário de Classe, devido a forma de avaliação da disciplina";
      $sMensagem .= " na turma de origem ser diferente da turma de destino:\\n\\n" . $msg_conversao;

      db_msgbox( $sMensagem );
    }

    $lProgressaoAtiva = false;

    $oAluno         = AlunoRepository::getAlunoByCodigo($ed60_i_aluno);
    $sMsgProgressao = "Aluno". $oAluno->getNome() ." possui as seguintes dependências:\n";
    $sMsgPadrao     = "Matrícula efetuada com sucesso!";

    foreach ($oAluno->getProgressaoParcial() as $oProgressaoParcial) {

      if ($oProgressaoParcial->isAtiva()) {

        $sMsgProgressao  .= "Etapa: " . $oProgressaoParcial->getEtapa()->getNome();
        $sMsgProgressao  .= " - Disciplina: " . $oProgressaoParcial->getDisciplina()->getNomeDisciplina() . ".";
        $sMsgProgressao  .= " - Ensino: " . $oProgressaoParcial->getEtapa()->getEnsino()->getNome() . ".\n";
        $lProgressaoAtiva = true;
      }
    }

    if ($lProgressaoAtiva) {

      $sMsgProgressao .= "\nAcesse: \n";
      $sMsgProgressao .= "Matrícula > Progressão Parcial > Ativar / Inativar: para inativar a progressão parcial.\n";
      $sMsgProgressao .= "Matrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno em uma turma";
      $sMsgPadrao     .= "\n{$sMsgProgressao}";

    }

    if ( !$lErroTransacao ) {
      db_msgbox($sMsgPadrao);
    }


    ?>
      <script>parent.location.href = "edu1_matriculatransf001.php";</script>;
    <?
  }

  if (isset($incluir2)) {

    $oParametrosGet = db_utils::postMemory($_GET);

    $lErroTransacao = false;
    db_inicio_transacao();

    $sSqlMatricula = $clmatricula->sql_query( "", "ed60_i_aluno, ed60_c_concluida", "", "ed60_i_codigo = {$matricula}" );
    $result        = $clmatricula->sql_record( $sSqlMatricula );
    db_fieldsmemory($result, 0);

    $sWhereMatricula0 = "ed60_i_turma = {$turmadestino} AND ed60_i_aluno = {$ed60_i_aluno} AND ed60_c_ativa = 'S'";
    $sSqlMatricula0   = $clmatricula->sql_query_file( "", "ed60_i_codigo as codmatrjatem", "", $sWhereMatricula0 );
    $result0          = $clmatricula->sql_record( $sSqlMatricula0 );

    if ($clmatricula->numrows > 0) {
      db_fieldsmemory($result0, 0);
    }else{
      $codmatrjatem = "";
    }

    $sSqlTurma = $clturma->sql_query( "", "ed57_i_calendario, ed57_i_escola", "", "ed57_i_codigo = {$turmadestino}" );
    $result    = $clturma->sql_record( $sSqlTurma );
    db_fieldsmemory($result, 0);

    $sCamposTurmaOrigem  = "ed57_c_descr as ed57_c_descrorig, ed57_i_base as baseorig, ed57_i_calendario as calorig";
    $sCamposTurmaOrigem .= ", ed57_i_turno as turnoorig, escola.ed18_c_nome as nomeescolaorig";
    $sCamposTurmaOrigem .= ", ed31_i_curso as cursoorig, ed57_i_escola as escolaorig";
    $sSqlTurmaOrigem     = $clturma->sql_query( "", $sCamposTurmaOrigem, "", "ed57_i_codigo = {$turmaorigem}" );
    $result_orig         = $clturma->sql_record( $sSqlTurmaOrigem );
    db_fieldsmemory($result_orig, 0);

    if ($ed60_c_concluida == "S") {

      $sql1    = "SELECT ed56_i_base as baseorig
                    FROM alunocurso
                   WHERE ed56_i_aluno = {$ed60_i_aluno}";
      $result1 = db_query($sql1);
      db_fieldsmemory($result1, 0);
    }

    $sSqlAlunoPossib = $clalunopossib->sql_query( "", "ed56_i_codigo, ed79_c_resulant", "", "ed56_i_aluno = {$ed60_i_aluno}" );
    $result_alu      = $clalunopossib->sql_record( $sSqlAlunoPossib );
    db_fieldsmemory($result_alu, 0);

    $sCamposTurmaDestino  = "ed57_c_descr as ed57_c_descrdest, ed57_i_base as basedest, ed57_i_calendario as caldest";
    $sCamposTurmaDestino .= ",ed57_i_turno as turnodest, ed57_i_escola as escoladest";
    $sSqlTurmaDestino     = $clturma->sql_query_file( "", $sCamposTurmaDestino, "", "ed57_i_codigo = {$turmadestino}" );
    $result_dest          = $clturma->sql_record( $sSqlTurmaDestino );
    db_fieldsmemory($result_dest, 0);

    $sSqlTurmaSerieRegimeMat = $clturmaserieregimemat->sql_query( "", "ed223_i_serie", "ed223_i_ordenacao", "ed220_i_turma = {$turmadestino}" );
    $result_etp              = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );
    db_fieldsmemory($result_etp, 0);

    $sSqlMatricula = $clmatricula->sql_query_file( "", "max(ed60_i_numaluno)", "", "ed60_i_turma = {$turmadestino}" );
    $result1       = $clmatricula->sql_record( $sSqlMatricula );
    db_fieldsmemory($result1, 0);

    $max = $max == "" ? "null": ( $max + 1 );

    if ($codmatrjatem != "") {

      $data_modif = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
      $sql     = "UPDATE matricula SET
                         ed60_i_turmaant     = {$turmaorigem},
                         ed60_c_rfanterior   = '{$ed79_c_resulant}',
                         ed60_d_datamodif    = '{$data_modif}',
                         ed60_d_datamodifant = null,
                         ed60_d_datasaida    = null,
                         ed60_c_concluida    = 'N',
                         ed60_c_situacao     = 'MATRICULADO'
                   WHERE ed60_i_codigo = {$codmatrjatem}";
      $query   = db_query($sql);
      $matrmov = $codmatrjatem;
    } else {

      $clmatricula->ed60_d_datamodif     = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
      $clmatricula->ed60_d_datamatricula = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
      $clmatricula->ed60_d_datamodifant  = null;
      $clmatricula->ed60_d_datasaida     = "null";
      $clmatricula->ed60_t_obs           = "";
      $clmatricula->ed60_i_aluno         = $ed60_i_aluno;
      $clmatricula->ed60_i_turma         = $turmadestino;
      $clmatricula->ed60_i_turmaant      = $turmaorigem;
      $clmatricula->ed60_c_rfanterior    = $ed79_c_resulant;
      $clmatricula->ed60_i_numaluno      = $max;
      $clmatricula->ed60_c_situacao      = "MATRICULADO";
      $clmatricula->ed60_c_concluida     = "N";
      $clmatricula->ed60_c_ativa         = "S";
      $clmatricula->ed60_c_tipo          = "N";
      $clmatricula->ed60_c_parecer       = "N";
      $clmatricula->incluir(null);

      $matrmov = $clmatricula->ed60_i_codigo;

      /**
       * Busca os turnos referentes vinculados a turma
       */
      $sWhereTurmaTurnoReferente = "ed336_turma = {$turmadestino}";
      $sWhereTurmaTurnoReferente .= " and ed336_turnoreferente in ( {$oParametrosGet->sTurnoReferente} )";
      $sSqlTurmaTurnoReferente   = $oDaoTurmaTurnoReferente->sql_query_file(
                                                                               null,
                                                                               "ed336_codigo",
                                                                               null,
                                                                               $sWhereTurmaTurnoReferente
                                                                           );
      $rsTurmaTurnoReferente     = db_query( $sSqlTurmaTurnoReferente );
      $iTotalTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );

      if ( $rsTurmaTurnoReferente && $iTotalTurmaTurnoReferente > 0 ) {

        /**
         * Percorre os turnos vinculados e inclui um novo registro na tabela matriculaturnoreferente, vinculando a
         * matrícula ao turno da turma
         */
        for ( $iContador = 0; $iContador < $iTotalTurmaTurnoReferente; $iContador++ ) {

          $iTurmaTurnoReferente = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador )->ed336_codigo;

          $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iTurmaTurnoReferente;
          $oDaoMatriculaTurnoReferente->ed337_matricula           = $matrmov;
          $oDaoMatriculaTurnoReferente->incluir( null );

          if ( $oDaoMatriculaTurnoReferente->erro_status == "0" ) {

            $lErroTransacao  = true;
            $sMensagem       = "Erro ao salvar dados do vínculo da matrícula com o turno:\n";
            $sMensagem      .= $oDaoMatriculaTurnoReferente->erro_msg;
            db_msgbox( $sMensagem );
          }
        }
      }

      for ($rr=0; $rr < $clturmaserieregimemat->numrows; $rr++) {

        db_fieldsmemory($result_etp,$rr);
        if ($codetapadestino == $ed223_i_serie) {
          $origem = "S";
        } else {
          $origem = "N";
        }

        $clmatriculaserie->ed221_i_matricula = $matrmov;
        $clmatriculaserie->ed221_i_serie     = $ed223_i_serie;
        $clmatriculaserie->ed221_c_origem    = $origem;
        $clmatriculaserie->incluir(null);
      }
    }

    $clmatriculamov->ed229_i_matricula    = $matrmov;
    $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
    $clmatriculamov->ed229_c_procedimento = "MATRICULAR ALUNOS TRANSFERIDOS";
    $clmatriculamov->ed229_t_descr        = "ALUNO MATRICULADO NA TURMA ".trim($ed57_c_descrdest)." VINDO DA ESCOLA ".trim($nomeescolaorig);
    $clmatriculamov->ed229_d_dataevento   = substr($data,6,4)."-".substr($data,3,2)."-".substr($data,0,2);
    $clmatriculamov->ed229_c_horaevento   = date("H:i");
    $clmatriculamov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatriculamov->incluir(null);

    $sql1    = "UPDATE alunocurso SET
                       ed56_c_situacao      = 'MATRICULADO',
                       ed56_i_escola        = {$escoladest},
                       ed56_i_base          = {$basedest},
                       ed56_i_calendario    = {$caldest},
                       ed56_i_baseant       = null,
                       ed56_i_calendarioant = null,
                       ed56_c_situacaoant   = ''
                 WHERE ed56_i_codigo = {$ed56_i_codigo}";
    $result1 = db_query($sql1);

    //atualiza serie do curso
    $sql2    = "UPDATE alunopossib SET
                       ed79_i_serie    = {$codetapadestino},
                       ed79_i_turno    = {$turnodest},
                       ed79_i_turmaant = {$turmaorigem},
                       ed79_c_resulant = '$ed79_c_resulant',
                       ed79_c_situacao = 'A'
                 WHERE ed79_i_alunocurso = {$ed56_i_codigo}";
    $result2 = db_query($sql2);

    //atualiza historico e transfere para escola destino
    $sql3    = "UPDATE historico SET
                       ed61_i_escola = {$escoladest}
                 WHERE ed61_i_aluno = {$ed60_i_aluno}";
    $result3 = db_query($sql3);

    //atualiza situacao da transferencia para fechada(F)
    $sql4       = "UPDATE transfescolarede SET
                          ed103_c_situacao = 'F'
                    WHERE ed103_i_codigo = {$ed103_i_codigo}";
    $result4    = db_query($sql4);

    $sql_del    = "DELETE FROM docaluno
                    WHERE ed49_i_aluno  = {$ed60_i_aluno}
                      AND ed49_i_escola = {$escolaorig}";
    $result_del = db_query($sql_del);

    $sql10      = "SELECT *
                     FROM escola_sequencias
                    WHERE ed129_i_escola = {$escola}";
    $result10   = db_query($sql10);
    $linhas10   = pg_num_rows($result10);

    if ($linhas10 > 0) {

      $result          = @db_query("select nextval('transflocal_ed131_i_codigo_seq')");
      $transflocal_cod = pg_result($result,0,0);
      $sql_tr          = "INSERT INTO transflocal
                          VALUES($transflocal_cod,
                                 $escoladest,
                                 $ed56_i_codigo,
                                 $cursoorig,
                                 $ed103_i_codigo,
                                 'A')";
      $result_tr       = db_query($sql_tr);
    }

    $oMatriculaAntiga = MatriculaRepository::getMatriculaByCodigo( $matricula );
    $oMatriculaAntiga->setConcluida(true);
    $oMatriculaAntiga->salvar();

    db_fim_transacao( $lErroTransacao );
    db_msgbox("Matrícula efetuada com sucesso!");

  ?>
    <script>parent.location.href = "edu1_matriculatransf001.php";</script>
  <?
  }
  ?>

  <script>document.getElementById("tab_aguarde").style.visibility = "hidden";</script>