<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/db_utils.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_jsplibwebseller.php');

db_postmemory($HTTP_POST_VARS);

function formataData($dData, $iTipo = 1) {

  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/', $dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;

  }

 $dData = explode('-', $dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}

$iEscola                 = db_getsession('DB_coddepto');
$resultedu               = eduparametros(db_getsession('DB_coddepto'));
$oDaoRegencia            = db_utils::getdao('regencia');
$oDaoTurma               = db_utils::getdao('turma');
$oDaoTurmaSerieRegimeMat = db_utils::getdao('turmaserieregimemat');
$oDaoProcAvaliacao       = db_utils::getdao('procavaliacao');
$oDaoProcedimento        = db_utils::getdao('procedimento');
$oDaoMatricula           = db_utils::getdao('matricula');
$oDaoMatriculaMov        = db_utils::getdao('matriculamov');
$oDaoMatriculaSerie      = db_utils::getdao('matriculaserie');
$oDaoDiario              = db_utils::getdao('diario');
$oDaoDiarioAvaliacao     = db_utils::getdao('diarioavaliacao');
$oDaoDiarioFinal         = db_utils::getdao('diariofinal');
$oDaoParecerAval         = db_utils::getdao('pareceraval');
$oDaoAbonoFalta          = db_utils::getdao('abonofalta');
$oDaoAmparo              = db_utils::getdao('amparo');
$oDaoAlunoCurso          = db_utils::getdao('alunocurso');
$oDaoAlunoPossib         = db_utils::getdao('alunopossib');
$oDaoSerieEquiv          = db_utils::getdao('serieequiv');
$oDaoTrocaSerie          = db_utils::getdao('trocaserie');
$oDaoHistorico           = db_utils::getdao('historico');
$oDaoHistoricoMps        = db_utils::getdao('historicomps');
$oDaoHistMpsDisc         = db_utils::getdao('histmpsdisc');
$oDaoTransfAprov         = db_utils::getdao('transfaprov');
$db_opcao                = 22;
$db_botao                = false;

?>
<table width="300" height="100" id="tab_aguarde"
  style="border:2px solid #444444;position:absolute;top:100px;left:250px;" cellspacing="1" cellpading="2">
 <tr>
  <td bgcolor="#DEB887" align="center" style="border:1px solid #444444;">
   <b>Aguarde...Carregando.</b>
  </td>
 </tr>
</table>
<?
if (!isset($incluir)) {
?>
  <html>
    <head>
      <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
      <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
      <meta http-equiv="Expires" CONTENT="0">
      <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
      <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
      <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
      <form name="form1" METHOD="POST" action="">
       <table border="0">
          <tr>
            <td>
              <b>Importar aproveitamento da turma de origem:</b>
               <select name="import" id="import" onchange="js_importar(this.value);">
                 <option value="N">N�O</option>
                 <option value="S">SIM</option>
              </select>
            </td>
          </tr>
        </table>

        <table border="0" cellspacing="0" cellpadding="0" id="tabelaDadosImportacao" style="display: none;">
          <tr>
            <td colspan="4" valign="top" bgcolor="#CCCCCC">
              <br>
              <b>
              Caso as turmas de origem e destino tenham disciplinas e/ou per�odos de avalia��o diferentes,
              informe abaixo quais disciplinas e per�odos de avalia��o da turma de destino que v�o receber
              as informa��es do aluno.
              </b>
              <br><br>
            </td>
          </tr>
          <tr>
            <td width="28%" valign="top" bgcolor="#CCCCCC" nowrap>
              <b>Disciplinas TURMA DE ORIGEM:</b>
            </td>
            <td width="20"></td>
            <td width="28%" valign="top" bgcolor="#CCCCCC" nowrap>
              <b>Disciplinas TURMA DE DESTINO:</b>
            </td>
            <td valign="top" bgcolor="#CCCCCC" nowrap>
              <b>Aproveitamento na TURMA DE ORIGEM:</b>
            </td>
          </tr>
          <?
          $sSql = $oDaoMatricula->sql_query('', 'ed60_i_aluno, ed221_i_serie as etapaorigem', '',
                                            " ed60_i_codigo = $matricula"
                                           );
          $rs   = $oDaoMatricula->sql_record($sSql);
          db_fieldsmemory($rs, 0);
          $sSql        = $oDaoRegencia->sql_query('', 'ed59_i_codigo, ed232_i_codigo, ed232_c_descr, ed232_c_abrev, '.
                                                  'ed220_i_procedimento as procorigem, ed59_i_ordenacao',
                                                  'ed59_i_ordenacao',
                                                  " ed59_i_turma = $turmaorigem and ed59_i_serie in ($etapaorigem)"
                                                 );
          $rs          = $oDaoRegencia->sql_record($sSql);
          $procorigem  = pg_result($rs, 0, 'procorigem');
          $linhas      = $oDaoRegencia->numrows;
          $sSql        = $oDaoRegencia->sql_query('', 'ed59_i_codigo as regdestino, ed232_i_codigo as coddestino, '.
                                                  'ed232_c_descr as descrdestino, '.
                                                  'ed220_i_procedimento as procdestino, ed59_i_ordenacao',
                                                  'ed59_i_ordenacao', " ed59_i_turma = $turmadestino"
                                                 );
          $rs2         = $oDaoRegencia->sql_record($sSql);
          $procdestino = pg_result($rs2, 0, 'procdestino');
          $linhas1     = $oDaoRegencia->numrows;
          $regmarcadas = '';
          for ($iCont = 0; $iCont < $linhas; $iCont++) {

            db_fieldsmemory($rs, $iCont);
          ?>
            <tr>
              <td valign="top" bgcolor="#CCCCCC" nowrap>
                <input name="regenciaorigem" type="text" value="<?=$ed59_i_codigo?>" size="10"
                  readonly style="width:75px">
                <input name="regorigemdescr" type="text" value="<?=$ed232_c_descr?>" size="30"
                  readonly style="width:180px">
              </td>
              <td align="center" nowrap>--></td>
              <td nowrap>
                <?
                $temreg = false;
                for ($iCont2 = 0; $iCont2 < $linhas1; $iCont2++) {

                  db_fieldsmemory($rs2, $iCont2);
                  if ($ed232_i_codigo == $coddestino) {

                    $temreg          = true;
                    $regenciadestino = $regdestino;
                    $regdestinodescr = $descrdestino;
                    $regmarcadas    .= '#'.$regdestino.'#';

                  }

                }
                if ($temreg == true) {
                ?>
                  <input name="regenciadestino" type="text" value="<?=$regenciadestino?>"
                    size="10" readonly style="width:75px">
                  <input name="regdestinodescr" type="text" value="<?=$regdestinodescr?>"
                    size="30" readonly style="width:180px">
                <?
                } else {

                  $sql2 = "select ed59_i_codigo as regsobra,trim(ed232_c_descr) as descrsobra
                           from regencia
                           inner join disciplina on ed12_i_codigo = ed59_i_disciplina
                           inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
                           where ed59_i_turma = $turmadestino
                           and ed232_i_codigo not in(select ed232_i_codigo from regencia
                                                     inner join disciplina on ed12_i_codigo = ed59_i_disciplina
                                                     inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina
                                                     where ed59_i_turma = $turmaorigem
                                                     and ed59_i_serie = $etapaorigem
                                                     )";
                  $rs3     = db_query($sql2);
                  $linhas2 = pg_num_rows($rs3);
                ?>
                  <select name="regenciadestino" style="padding:0px;width:75px;height:16px;font-size:12px;"
                    onchange="js_eliminareg(this.value,<?=$iCont?>)">
                  <option value=""></option>
                  <?
                  for ($iCont2 = 0; $iCont2 < $linhas2; $iCont2++) {

                    db_fieldsmemory($rs3, $iCont2);
                    echo "<option value='$regsobra'>$regsobra</option>";

                  }
                  ?>
                  </select>
                  <select name="regdestinodescr" style="padding:0px;width:180px;height:16px;font-size:12px;"
                    onchange="js_eliminareg(this.value,<?=$iCont?>)">
                  <option value=""></option>
                  <?
                  for ($iCont2 = 0; $iCont2 < $linhas2; $iCont2++) {

                    db_fieldsmemory($rs3, $iCont2);
                    echo "<option value='$regsobra'>$descrsobra</option>";

                  }
                  ?>
                  </select>
                  <input type="hidden" name="combo" value="<?=$iCont?>">
                  <input type="hidden" name="comboselect<?=$iCont?>" value="">
                <?
                }
                ?>
              </td>
              <td nowrap>
                <table border="1" cellspacing="0" cellpadding="0">
                  <tr>
                    <?
                    $sSql = $oDaoDiarioAvaliacao->sql_query('', 'ed09_c_abrev, ed72_i_valornota, '.
                                                            'ed72_c_valorconceito, ed72_t_parecer, '.
                                                            'ed37_c_tipo', 'ed41_i_sequencia asc',
                                                            " ed95_i_aluno = $ed60_i_aluno ".
                                                            "and ed95_i_regencia = $ed59_i_codigo ".
                                                            "and ed09_c_somach = 'S'"
                                                           );
                    $rs3  = $oDaoDiarioAvaliacao->sql_record($sSql);
                    echo "<td width='60px' style='background:#444444;color:#DEB887;font-size:9px;'>".
                         "<b>$ed232_c_abrev</b></td>";
                    if ($oDaoDiarioAvaliacao->numrows == 0) {
                      echo "<td width='160px' style='background:#f3f3f3;'>Nenhum registro.</td>";
                    } else {

                      for ($iCont2 = 0; $iCont2 < $oDaoDiarioAvaliacao->numrows; $iCont2++) {

                        db_fieldsmemory($rs3, $iCont2);
                        if (trim($ed37_c_tipo) == 'NOTA') {

                          if ($resultedu == 'S') {

                            $aproveitamento = $ed72_i_valornota != '' ?
                              number_format($ed72_i_valornota, 2, ',', '.') : '';

                          } else {
                            $aproveitamento = $ed72_i_valornota != '' ? number_format($ed72_i_valornota, 0) : '';
                          }

                        } elseif (trim($ed37_c_tipo) == 'NIVEL') {
                          $aproveitamento = $ed72_c_valorconceito;
                        } else {
                          $aproveitamento = $ed72_t_parecer != '' ? "<font size='1'>Parecer</font>" : '';
                        }
                        echo "<td width='80px' style='background:#f3f3f3;font-size:9px;'><b>".
                              $ed09_c_abrev.":</b></td>
                              <td width='80px' style='font-size:9px;' align='center'>".
                              ($aproveitamento == '' ? '&nbsp;' : $aproveitamento).'</td>';

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
            <td valign="top" bgcolor="#CCCCCC" nowrap>
              <b>Per�odos de Avalia��o TURMA DE ORIGEM:</b>
            </td>
            <td width="10"></td>
            <td valign="top" bgcolor="#CCCCCC" nowrap>
              <b>Per�odos de Avalia��o  TURMA DE DESTINO:</b>
            </td>
          </tr>
          <?
          $sSql    = $oDaoProcAvaliacao->sql_query('', 'ed41_i_codigo, ed09_i_codigo, ed09_c_descr, '.
                                                   'ed37_c_tipo, ed37_i_menorvalor, ed37_i_maiorvalor',
                                                   'ed41_i_sequencia', " ed41_i_procedimento = $procorigem"
                                                  );
          $rs      = $oDaoProcAvaliacao->sql_record($sSql);
          $linhas  = $oDaoProcAvaliacao->numrows;
          $sSql    = $oDaoProcAvaliacao->sql_query('', 'ed41_i_codigo as codaval, ed09_i_codigo as codperaval, '.
                                                   'ed09_c_descr as descraval, ed37_c_tipo as tipodest, '.
                                                   'ed37_i_menorvalor as menordest, ed37_i_maiorvalor as maiordest',
                                                   'ed41_i_sequencia', " ed41_i_procedimento = $procdestino"
                                                  );
          $rs2     = $oDaoProcAvaliacao->sql_record($sSql);
          $linhas1 = $oDaoProcAvaliacao->numrows;
          for ($iCont = 0; $iCont < $linhas; $iCont++) {

            db_fieldsmemory($rs, $iCont);
            $tipoavaliacao = $ed37_c_tipo.($ed37_c_tipo == 'NOTA' ?
                                           ' ('.$ed37_i_menorvalor.' a '.$ed37_i_maiorvalor.')' : ''
                                          );
            ?>
            <tr>
              <td valign="top" bgcolor="#CCCCCC" nowrap>
                <input name="periodoorigem" type="text" value="<?=$ed41_i_codigo?>" size="10"
                  readonly style="width:75px">
                <input name="perorigemdescr" type="text" value="<?=$ed09_c_descr.' - '.$tipoavaliacao?>"
                  size="30" readonly style="width:180px">
              </td>
              <td align="center" nowrap>--></td>
              <td nowrap>
                <?
                $temper = false;
                for ($iCont2 = 0; $iCont2 < $linhas1; $iCont2++) {

                  db_fieldsmemory($rs2, $iCont2);
                  if ($ed09_i_codigo == $codperaval) {

                    $temper          = true;
                    $periododestino  = $codaval;
                    $tipoavaliacao1  = $tipodest.($tipodest == 'NOTA' ? ' ('.$menordest.' a '.$maiordest.')':'');
                    $perdestinodescr = $descraval.' - '.$tipoavaliacao1;

                  }

                }
                if ($temper == true) {
                ?>
                  <input name="periododestino" type="text" value="<?=$periododestino?>" size="10" readonly
                    style="width:75px">
                  <input name="perdestinodescr" type="text" value="<?=$perdestinodescr?>" size="30"
                    readonly style="width:180px">
                <?
                } else {

                  $sql2 = "select ed41_i_codigo as persobra,ed09_c_descr as descrsobra,ed37_c_tipo as tipodest,
                           ed37_i_menorvalor as menordest,ed37_i_maiorvalor as maiordest
                           from procavaliacao
                            inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
                            inner join formaavaliacao on ed37_i_codigo = ed41_i_formaavaliacao
                            inner join procedimento on ed40_i_codigo = ed41_i_procedimento
                            inner join turmaserieregimemat on ed220_i_procedimento = ed40_i_codigo
                            inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                            inner join turma on ed57_i_codigo = ed220_i_turma
                           where ed57_i_codigo = $turmadestino
                           and ed223_i_serie = $etapaorigem
                           and ed09_i_codigoa
                             not in(select ed09_i_codigo from procavaliacao
                                    inner join periodoavaliacao on ed09_i_codigo = ed41_i_periodoavaliacao
                                    inner join procedimento on ed40_i_codigo = ed41_i_procedimento
                                    inner join turmaserieregimemat on ed220_i_procedimento = ed40_i_codigo
                                    inner join serieregimemat on ed223_i_codigo = ed220_i_serieregimemat
                                    inner join turma on ed57_i_codigo = ed220_i_turma
                                    where ed57_i_codigo = $turmaorigem
                                    and ed223_i_serie = $etapaorigem)
                           order by ed41_i_sequencia";
                  $rs3     = db_query($sql2);
                  $linhas2 = pg_num_rows($rs3);
                  ?>
                  <select name="periododestino" style="padding:0px;width:75px;height:16px;font-size:12px;"
                    onchange="js_eliminaper(this.value,<?=$iCont?>)">
                  <option value=""></option>
                  <?
                  for ($iCont2 = 0; $iCont2 < $linhas2; $iCont2++) {

                    db_fieldsmemory($rs3, $iCont2);
                    echo "<option value='$persobra'>$persobra</option>";

                  }
                  ?>
                  </select>
                  <select name="perdestinodescr" style="padding:0px;width:180px;height:16px;font-size:12px;"
                    onchange="js_eliminaper(this.value,<?=$iCont?>)">
                  <option value=""></option>
                  <?
                  for ($iCont2 = 0; $iCont < $linhas2; $iCont2++) {

                    db_fieldsmemory($rs3, $iCont2);
                    $tipoavaliacao2 = $tipodest.($tipodest == 'NOTA' ? ' ('.$menordest.' a '.$maiordest.')' : '');
                    echo "<option value='$persobra'>$descrsobra - $tipoavaliacao2</option>";

                  }
                  ?>
                  </select>
                  <input type="hidden" name="pcombo" value="<?=$iCont?>">
                  <input type="hidden" name="pcomboselect<?=$iCont?>" value="">
                <?
                }
                ?>
              </td>
              <td></td>
            </tr>
          <?
          }

          $sSql = $oDaoTurmaSerieRegimeMat->sql_query(null, 'ed223_i_serie, ed11_c_descr as descretapa',
                                                      'ed223_i_ordenacao', " ed220_i_turma = $turmadestino"
                                                     );
          $rs   = $oDaoTurmaSerieRegimeMat->sql_record($sSql);
          if ($oDaoTurmaSerieRegimeMat->numrows > 1) {
          ?>
            <tr>
              <td colspan="3">
                <?
                $tem = false;
                for ($iCont = 0; $iCont < $oDaoTurmaSerieRegimeMat->numrows; $iCont++) {

                  db_fieldsmemory($rs, $iCont);
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
                    $sSql = $oDaoSerieEquiv->sql_query(null, 'ed234_i_serieequiv', '',
                                                       " ed234_i_serie = $etapaorigem"
                                                      );
                    $rs2  = $oDaoSerieEquiv->sql_record($sSql);
                    for ($iCont = 0; $iCont < $oDaoTurmaSerieRegimeMat->numrows; $iCont++) {

                      db_fieldsmemory($rs, $iCont);
                      $selected = "";
                      $disabled = "disabled";
                      if ($oDaoSerieEquiv->numrows>0) {

                        for ($iCont2 = 0; $iCont2 < $oDaoSerieEquiv->numrows; $iCont2++) {

                          db_fieldsmemory($rs2, $iCont2);
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

            db_fieldsmemory($rs, 0);
            ?>
            <input name="codetapadestino" type="hidden" value="<?=$ed223_i_serie?>">
          <?
          }
          ?>
          <tr>
            <td height="10" colspan="3"></td>
          </tr>
        </table>

        <table border="0">
          <tr>
            <td>
              <input type="button" name="incluir" value="Incluir" onclick="js_processar();"
                <?=isset($incluir) ? "style='visibility:hidden;'" : "style='position:absolute;visibility:visible;'"?>>
            </td>
          </tr>
        </table>
      </form>

      <script>
      function js_eliminareg(valor,seq) {
       C = document.form1.combo;
       RD = document.form1.regenciadestino;
       RDC = document.form1.regdestinodescr;
       tamC = C.length;
       tamC = tamC==undefined?1:tamC;
       campo = "comboselect"+seq;
       valorant = eval("document.form1."+campo+".value");
       if (tamC==1) {
        tamRD = RD.length;
        for (r=0;r<tamRD;r++) {
         if (parseInt(RD.options[r].value)==parseInt(valor) || parseInt(RDC.options[r].value)==parseInt(valor)) {
          RD.options[r].selected = true;
          RDC.options[r].selected = true;
         }
         if (parseInt(RD.options[r].value)==parseInt(valorant) || parseInt(RDC.options[r].value)==parseInt(valorant)) {
          RD.options[r].selected = false;
          RDC.options[r].selected = false;
         }
        }
       } else {
        for (i=0;i<tamC;i++) {
         tamRD = RD[C[i].value].length;
         if (parseInt(C[i].value)!=parseInt(seq)) {
          for (r=0;r<tamRD;r++) {
           if (parseInt(RD[C[i].value].options[r].value)==parseInt(valor) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valor)) {
            RD[C[i].value].options[r].disabled = true;
            RDC[C[i].value].options[r].disabled = true;
           }
           if (parseInt(RD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valorant)) {
            RD[C[i].value].options[r].disabled = false;
            RDC[C[i].value].options[r].disabled = false;
           }
          }
         } else {
          for (r=0;r<tamRD;r++) {
           if (parseInt(RD[C[i].value].options[r].value)==parseInt(valor) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valor)) {
            RD[C[i].value].options[r].selected = true;
            RDC[C[i].value].options[r].selected = true;
           }
           if (parseInt(RD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valorant)) {
            RD[C[i].value].options[r].selected = false;
                    RDC[C[i].value].options[r].selected = false;
           }
          }
         }
        }
       }
       eval("document.form1."+campo+".value = valor");
      }
      function js_eliminaper(valor,seq) {
       C = document.form1.pcombo;
       PD = document.form1.periododestino;
       PDC = document.form1.perdestinodescr;
       tamC = C.length;
       tamC = tamC==undefined?1:tamC;
       campo = "pcomboselect"+seq;
       valorant = eval("document.form1."+campo+".value");
       if (tamC==1) {
        tamPD = PD.length;
        for (r=0;r<tamPD;r++) {
         if (parseInt(PD.options[r].value)==parseInt(valor) || parseInt(PDC.options[r].value)==parseInt(valor)) {
          PD.options[r].selected = true;
          PDC.options[r].selected = true;
         }
         if (parseInt(PD.options[r].value)==parseInt(valorant) || parseInt(PDC.options[r].value)==parseInt(valorant)) {
          PD.options[r].selected = false;
          PDC.options[r].selected = false;
         }
        }
       } else {
        for (i=0;i<tamC;i++) {
         tamPD = PD[C[i].value].length;
         if (parseInt(C[i].value)!=parseInt(seq)) {
          for (r=0;r<tamPD;r++) {
           if (parseInt(PD[C[i].value].options[r].value)==parseInt(valor) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valor)) {
            PD[C[i].value].options[r].disabled = true;
            PDC[C[i].value].options[r].disabled = true;
            }
           if (parseInt(PD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valorant)) {
            PD[C[i].value].options[r].disabled = false;
            PDC[C[i].value].options[r].disabled = false;
           }
          }
         } else {
           for (r=0;r<tamPD;r++) {
           if (parseInt(PD[C[i].value].options[r].value)==parseInt(valor) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valor)) {
            PD[C[i].value].options[r].selected = true;
            PDC[C[i].value].options[r].selected = true;
           }
           if (parseInt(PD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valorant)) {
            PD[C[i].value].options[r].selected = false;
            PDC[C[i].value].options[r].selected = false;
           }
          }
         }
        }
       }
       eval("document.form1."+campo+".value = valor");
      }

      function js_validaDadosPai() {


        if (parent.document.form1.ed101_i_aluno.value == '') {

          alert('Informe o aluno.');
          return false;

        }

        if (parent.document.form1.ed101_i_turmaorig.value == '') {

          alert('Informe a turma de origem do aluno.');
          return false;

        }

        if (parent.document.form1.ed57_c_descrorig.value == '') {

          alert('Informe o nome da turma de origem');
          return false;

        }

        if (parent.document.form1.ed11_i_codorigem.value == '') {

          alert('Informe a etapa de origem');
          return false;

        }

        if (parent.document.form1.ed11_c_origem.value == '') {

          alert('Informe o nome da etapa de origem');
          return false;

        }

        if (parent.document.form1.ed101_i_turmadest.value == '') {

          alert('Informe a turma de destino.');
          return false;

        }

        if (parent.document.form1.ed57_c_descrdest.value == '') {

          alert('Informe o nome da turma de destino.');
          return false;

        }

        if (parent.document.form1.ed11_i_coddestino.value == '') {

          alert('Informe a etapa de destino');
          return false;

        }

        if (parent.document.form1.ed11_c_destino.value == '') {

          alert('Informe o nome da etapa de destino.');
          return false;

        }

        return true;

      }

      function js_processar() {

        msggeral = "";
        regequiv = "";
        perequiv = "";

        if (!js_validaDadosPai()) {
          return false;
        }

        if (document.form1.import.value == 'S') { // Ser foi selecionado para importar aproveitamento

          RO       = document.form1.regenciaorigem;
          RD       = document.form1.regenciadestino;
          RC       = document.form1.regorigemdescr;
          PO       = document.form1.periodoorigem;
          PD       = document.form1.periododestino;
          PC       = document.form1.perorigemdescr;
          tamRO    = RO.length;
          tamRO    = tamRO == undefined ? 1 : tamRO;
          sepreg   = "";
          msgreg   = "Aten��o:\nAs informa��es das seguintes disciplinas n�o ser�o transportadas, ";
          msgreg  += "pois as mesmas n�o cont�m disciplinas equivalentes na turma de destino:\n\n";
          regnull  = false;
          for (i=0;i<tamRO;i++) {
           if (tamRO==1) {
            if (RD.value!="") {
             regequiv += sepreg+RO.value+"|"+RD.value;
             sepreg = "X";
            } else {
             msgreg += RC.value+"\n";
             regnull = true;
            }
           } else {
            if (RD[i].value!="") {
             regequiv += sepreg+RO[i].value+"|"+RD[i].value;
             sepreg = "X";
            } else {
             msgreg += RC[i].value+"\n";
             regnull = true;
            }
           }
          }
          tamPO    = PO.length;
          tamPO    = tamPO==undefined?1:tamPO;
          sepper   = "";
          msgper   = "Aten��o:\nAs informa��es dos seguintes per�odos de avalia��o n�o ser�o transportadas, ";
          msgper  += "pois os mesmos n�o cont�m per�odos de avalia��o equivalentes na turma de destino:\n\n";
          pernull  = false;
          for (i=0;i<tamPO;i++) {
           if (tamPO==1) {
            if (PD.value!="") {
             perequiv += sepper+PO.value+"|"+PD.value;
             sepper = "X";
            } else {
             msgper += PC.value+"\n";
             pernull = true;
            }
           } else {
            if (PD[i].value!="") {
             perequiv += sepper+PO[i].value+"|"+PD[i].value;
             sepper = "X";
            } else {
             msgper += PC[i].value+"\n";
             pernull = true;
            }
           }
          }
          if (regnull==true) {
           msggeral += msgreg+"\n";
          }
          if (pernull==true) {
           msggeral += msgper;
          }
          tamRO    = RO.length;
          tamRO    = tamRO == undefined ? 1 : tamRO;
          regselec = false;
          for (t=0;t<tamRO;t++) {
           if (tamRO==1) {
            if (RD.value!="") {
             regselec = true;
             break;
            }
           } else {
            if (RD[t].value!="") {
             regselec = true;
             break;
            }
           }
          }
          if (regselec==false) {

            alert("Informe alguma disciplina da turma de destino para receber as informa��es da origem!");
            return false;

          }
          tamPO = PO.length;
          tamPO = tamPO==undefined?1:tamPO;
          perselec = false;
          for (t=0;t<tamPO;t++) {
           if (tamPO==1) {
            if (PD.value!="") {
             perselec = true;
             break;
            }
           } else {
            if (PD[t].value!="") {
             perselec = true;
             break;
            }
           }
          }
          if (perselec == false) {

            alert('Informe algum per�odo de avalia��o da turma de destino para receber as informa��es da origem!');
            return false;

          }

        }

        var sGet = '';
        sGet    += 'incluir=true&regequiv='+regequiv+'&perequiv='+perequiv;
        sGet    += '&matricula=<?=$matricula?>&turmaorigem=<?=$turmaorigem?>&turmadestino=<?=$turmadestino?>';
        sGet    += '&import='+document.getElementById('import').value;
        sGet    += '&sUrlRetorno=edu1_trocaserieav001.php';
        sGet    += '&codetapadestino='+document.form1.codetapadestino.value;
        sGet    += '&ed101_i_aluno='+parent.document.form1.ed101_i_aluno.value;
        sGet    += '&ed101_i_turmaorig='+parent.document.form1.ed101_i_turmaorig.value;
        sGet    += '&ed57_c_descrorig='+parent.document.form1.ed57_c_descrorig.value.trim();
        sGet    += '&ed11_i_codorigem='+parent.document.form1.ed11_i_codorigem.value;
        sGet    += '&ed11_c_origem='+parent.document.form1.ed11_c_origem.value;
        sGet    += '&ed101_i_turmadest='+parent.document.form1.ed101_i_turmadest.value;
        sGet    += '&ed57_c_descrdest='+parent.document.form1.ed57_c_descrdest.value.trim();
        sGet    += '&ed11_i_coddestino='+parent.document.form1.ed11_i_coddestino.value;
        sGet    += '&ed11_c_destino='+parent.document.form1.ed11_c_destino.value;
        sGet    += '&ed101_d_data='+parent.document.form1.ed101_d_data.value;
        sGet    += '&ed101_t_obs='+parent.document.form1.ed101_t_obs.value;
        sGet    += '&sTipo=<?=$sTipo?>'; // A -> Avan�o, C -> Classifica��o

        if (msggeral != '') {

          if (confirm(msggeral+"\n\nConfirmar Troca de Turma para o aluno?")) {

            document.form1.incluir.style.visibility = 'hidden';
            location.href                           = 'edu4_trocaserieimportacao001.php?'+sGet;

          }

        } else {

          document.form1.incluir.style.visibility = 'hidden';
          location.href = 'edu4_trocaserieimportacao001.php?'+sGet;

        }

      }
      function js_importar(valor) {

        if (valor == 'N') {

          alert('Importar aproveitamento da turma de origem est� marcado como N�O. '+
                'Caso este aluno tenha algum aproveitamento na turma de origem, '+
                'este ter� quer ser digitado manualmente!'
               );
          document.getElementById('tabelaDadosImportacao').style.display = 'none';

        } else {
          document.getElementById('tabelaDadosImportacao').style.display = '';
        }

      }
      </script>
    </body>
  </html>
  <?
} else {

  $sPalavra1 = $sTipo == 'A' ? 'AVAN�O' : 'CLASSIFICA��O';
  $sPalavra2 = $sTipo == 'A' ? 'AVAN�ADO' : 'CLASSIFICADO';
  $sPalavra3 = $sTipo == 'A' ? 'AVAN�O' : 'CLASSIF';

  db_inicio_transacao();

  /********* IN�CIO DO BLOCO QUE RESPONS�VEL POR REALIZAR A PROGRESS�O DO ALUNO */
  $oDaoTrocaSerie->ed101_i_aluno     = $ed101_i_aluno;
  $oDaoTrocaSerie->ed101_i_turmaorig = $ed101_i_turmaorig;
  $oDaoTrocaSerie->ed101_i_turmadest = $ed101_i_turmadest;
  $oDaoTrocaSerie->ed101_t_obs       = $ed101_t_obs;
  $oDaoTrocaSerie->ed101_d_data      = formataData($ed101_d_data);
  $oDaoTrocaSerie->ed101_c_tipo      = $sTipo;
  $oDaoTrocaSerie->incluir(null);
  if ($oDaoTrocaSerie->erro_status != '0') { // Se n�o houve erro

    $sSql = $oDaoMatricula->sql_query('', 'ed60_i_codigo as codmatricula, ed29_i_codigo as codcurso, '.
                                      'turma.ed57_c_descr as nometurma,ed11_i_sequencia as seqorigem, '.
                                      'calendario.ed52_i_ano as anoref, calendario.ed52_i_semletivas as semanas, '.
                                      'turmaserieregimemat.ed220_i_procedimento as codproc, calendario.ed52_i_codigo, '.
                                      'turma.ed57_i_base as baseant, ed60_d_datamodif as datamodif, '.
                                      'ed60_c_tipo as tipomatricula, turma.ed57_i_tipoturma as tipoturma, '.
                                      'procedimento.ed40_i_codigo', '',
                                      " ed60_i_aluno = $ed101_i_aluno and ed60_i_turma = $ed101_i_turmaorig ".
                                      "and ed221_i_serie = $ed11_i_codorigem and ed60_c_ativa = 'S'"
                                     );
    $rs   = $oDaoMatricula->sql_record($sSql);
    if ($oDaoMatricula->numrows <= 0) {

      $oDaoTrocaSerie->erro_status = '0';
      $oDaoTrocaSerie->erro_msg    = 'N�o foi poss�vel encontrar a matr�cula do aluno para realizar a progress�o.';

    } else { // Encontrou a matr�cula

      $oDadosMatricula =  db_utils::fieldsmemory($rs, 0);

      /* Busco os procedimentos de avalia��o para caso tenha que incluir um di�rio por ainda n�o ter sido inclu�do */
      $sSql          = $oDaoProcAvaliacao->sql_query(null, 'ed41_i_codigo', '',
                                                     ' ed41_i_procedimento = '.$oDadosMatricula->ed40_i_codigo
                                                    );
      $rs           = $oDaoProcAvaliacao->sql_record($sSql);
      $iNumProcAval = $oDaoProcAvaliacao->numrows;
      $aProcAval    = array();
      for ($iCont = 0; $iCont < $iNumProcAval; $iCont++) {

        $aProcAval[$iCont] = db_utils::fieldsmemory($rs, $iCont)->ed41_i_codigo;

      }

      /* Busco todas as reg�ncias (disciplinas) em que o aluno estava matriculado e as encerro no di�rio,
         ou incluo j� como encerradas, se ainda nao havia registros no di�rio para elas */
      $sSql = $oDaoRegencia->sql_query_file(null, 'ed59_i_codigo', '',
                                            " ed59_i_turma = $ed101_i_turmaorig and".
                                            " ed59_i_serie = $ed11_i_codorigem"
                                           );
      $rs   = $oDaoRegencia->sql_record($sSql);
      for ($iCont = 0; $iCont < $oDaoRegencia->numrows; $iCont++) {

        $iCodRegencia = db_utils::fieldsmemory($rs, $iCont)->ed59_i_codigo;

        /* Obtenho os di�rios (acredito que sempre vai ser um s�) para a reg�ncia */
        $sSql  = $oDaoDiario->sql_query_file(null, 'ed95_i_codigo', '',
                                             ' ed95_i_aluno = '.$ed101_i_aluno.
                                             ' and ed95_i_regencia = '.$iCodRegencia.
                                             ' and ed95_i_serie = '.$ed11_i_codorigem
                                            );
        $rsTmp = $oDaoDiario->sql_record($sSql);
        if ($oDaoDiario->numrows > 0) {

          $iNumRowsDiario = $oDaoDiario->numrows;
          for ($iCont2 = 0; $iCont2 < $iNumRowsDiario; $iCont2++) {

            $iCodDiario                   = db_utils::fieldsmemory($rsTmp, $iCont2)->ed95_i_codigo;
            $oDaoDiario->ed95_i_codigo    = $iCodDiario;
            $oDaoDiario->ed95_c_encerrado = 'S';
            $oDaoDiario->alterar($iCodDiario);
            if ($oDaoDiario->erro_status == '0') {

              $oDaoTrocaSerie->erro_status = '0';
              $oDaoTrocaSerie->erro_msg    = $oDaoDiario->erro_msg;
              break 2;

            }

          }

        } else { // Incluo o di�rio j� encerrado


          $oDaoDiario->ed95_c_encerrado  = 'S';
          $oDaoDiario->ed95_i_escola     = $iEscola;
          $oDaoDiario->ed95_i_calendario = $oDadosMatricula->ed52_i_codigo;
          $oDaoDiario->ed95_i_aluno      = $ed101_i_aluno;
          $oDaoDiario->ed95_i_serie      = $ed11_i_codorigem;
          $oDaoDiario->ed95_i_regencia   = $iCodRegencia;
          $oDaoDiario->incluir(null);
          if ($oDaoDiario->erro_status == '0') {

            $oDaoTrocaSerie->erro_status = '0';
            $oDaoTrocaSerie->erro_msg    = $oDaoDiario->erro_msg;
            break;

          } else {

             for ($iCont2 = 0; $iCont2 < $iNumProcAval; $iCont2++) {


               $oDaoDiarioAvaliacao->ed72_i_diario        = $oDaoDiario->ed95_i_codigo;
               $oDaoDiarioAvaliacao->ed72_i_procavaliacao = $aProcAval[$iCont2];
               $oDaoDiarioAvaliacao->ed72_c_aprovmin      = 'N';
               $oDaoDiarioAvaliacao->ed72_c_amparo        = 'N';
               $oDaoDiarioAvaliacao->ed72_i_escola        = $iEscola;
               $oDaoDiarioAvaliacao->ed72_c_tipo          = 'M';
               $oDaoDiarioAvaliacao->ed72_c_convertido    = 'N';
               $oDaoDiarioAvaliacao->incluir(null);
               if ($oDaoDiarioAvaliacao->erro_status == '0') {

                 $oDaoTrocaSerie->erro_status = '0';
                 $oDaoTrocaSerie->erro_msg    = $oDaoDiarioAvaliacao->erro_msg;
                 break 2;

               }

             }

          }

        }

      }

      if ($oDaoTrocaSerie->erro_status != '0') { // Se n�o houve nenhum erro at� ent�o

        $sSql = $oDaoMatricula->sql_query_file(null, 'max(ed60_i_numaluno) as max', '',
                                               " ed60_i_turma = $ed101_i_turmadest"
                                              );
        $rs   = $oDaoMatricula->sql_record($sSql);
        $iMax = db_utils::fieldsmemory($rs, 0)->max;
        $iMax = $iMax == '' ? 'null' : ($iMax + 1);

        /* Data da Matr�cula */
        $aData          = explode('/', $ed101_d_data);
        $dDataMatricula = $aData[2].'-'.$aData[1].'-'.$aData[0];

        /* Observa��o da nova matr�cula */
        $sObs  = $sPalavra2.'(A) DA ETAPA '.(trim($ed11_c_origem)).' PARA ETAPA '.(trim($ed11_c_destino));
        $sObs .= ' EM '.$ed101_d_data.', CONFORME LEI FEDERAL N� 9394/96 - ARTIGO 23, � 1o , ';
        $sObs .= 'PARECER CEED N� 740/99 E REGIMENTO ESCOLAR';

        /* Matricula o aluno na nova turma */
        $oDaoMatricula->ed60_i_aluno         = $ed101_i_aluno;
        $oDaoMatricula->ed60_i_turma         = $ed101_i_turmadest;
        $oDaoMatricula->ed60_i_numaluno      = $iMax;
        $oDaoMatricula->ed60_c_situacao      = 'MATRICULADO';
        $oDaoMatricula->ed60_c_concluida     = 'N';
        $oDaoMatricula->ed60_i_turmaant      = $ed101_i_turmaorig;
        $oDaoMatricula->ed60_c_rfanterior    = 'A';
        $oDaoMatricula->ed60_d_datamatricula = $dDataMatricula;
        $oDaoMatricula->ed60_d_datamodif     = $dDataMatricula;
        $oDaoMatricula->ed60_d_datamodifant  = null;
        $oDaoMatricula->ed60_d_datasaida     = "null";
        $oDaoMatricula->ed60_t_obs           = $sObs;
        $oDaoMatricula->ed60_c_ativa         = 'S';
        $oDaoMatricula->ed60_c_tipo          = $oDadosMatricula->tipomatricula;
        $oDaoMatricula->ed60_c_parecer       = 'N';
        $oDaoMatricula->incluir(null);
        if ($oDaoMatricula->erro_status == '0') {

          $oDaoTrocaSerie->erro_status = '0';
          $oDaoTrocaSerie->erro_msg    = $oDaoMatricula->erro_msg;

        } else {

          /* Inser��o do registro de movimenta��o da nova matr�cula do aluno */
          $iNovaMatricula                         = $oDaoMatricula->ed60_i_codigo;
          $oDaoMatriculaMov->ed229_i_matricula    = $iNovaMatricula;
          $oDaoMatriculaMov->ed229_i_usuario      = db_getsession('DB_id_usuario');
          $oDaoMatriculaMov->ed229_c_procedimento = 'PROGRESS�O DE ALUNO -> '.$sPalavra1;
          $oDaoMatriculaMov->ed229_t_descr        = 'ALUNO '.$sPalavra2.' DA TURMA '.trim($ed57_c_descrorig).' / '.
                                                    trim($ed11_c_origem).' PARA A TURMA '.trim($ed57_c_descrdest).
                                                    ' / '.trim($ed11_c_destino);
          $oDaoMatriculaMov->ed229_d_dataevento   = $dDataMatricula;
          $oDaoMatriculaMov->ed229_c_horaevento   = date('H:i');
          $oDaoMatriculaMov->ed229_d_data         = date('Y-m-d', db_getsession('DB_datausu'));
          $oDaoMatriculaMov->incluir(null);
          if ($oDaoMatriculaMov->erro_status == '0') {

            $oDaoTrocaSerie->erro_status = '0';
            $oDaoTrocaSerie->erro_msg    = $oDaoMatriculaMov->erro_msg;

          } else {

            /* Obtenho todas as s�ries (etapas) da turma de destino para realizar a matr�cula nas mesmas */
            $sSql = $oDaoTurmaSerieRegimeMat->sql_query(null, 'ed223_i_serie', 'ed223_i_ordenacao',
                                                        " ed220_i_turma = $ed101_i_turmadest"
                                                       );
            $rs   = $oDaoTurmaSerieRegimeMat->sql_record($sSql);
            for ($iCont = 0; $iCont < $oDaoTurmaSerieRegimeMat->numrows; $iCont++) {

              $iSerie = db_utils::fieldsmemory($rs, $iCont)->ed223_i_serie;
              if ($iSerie == $ed11_i_coddestino) {
                $oDaoMatriculaSerie->ed221_c_origem = 'S';
              } else {
                $oDaoMatriculaSerie->ed221_c_origem = 'N';
              }
              $oDaoMatriculaSerie->ed221_i_matricula = $iNovaMatricula;
              $oDaoMatriculaSerie->ed221_i_serie = $iSerie;
              $oDaoMatriculaSerie->incluir(null);
              if ($oDaoMatriculaSerie->erro_status == '0') {

                $oDaoTrocaSerie->erro_status = '0';
                $oDaoTrocaSerie->erro_msg    = $oDaoMatriculaSerie->erro_msg;
                break;

              }

            }

            if ($oDaoTrocaSerie->erro_status != '0') {

              /* Atualizo matr�cula de origem */
              $oDaoMatricula                      = null;
              $oDaoMatricula                      = db_utils::getdao('matricula'); // Fa�o isso pra limpar os atr do obj
              $oDaoMatricula->ed60_c_situacao     = $sPalavra2;
              $oDaoMatricula->ed60_c_concluida    = 'S' ;
              $oDaoMatricula->ed60_t_obs          = $sObs;
              $oDaoMatricula->ed60_d_datasaida    = $dDataMatricula;
              $oDaoMatricula->ed60_d_datamodifant = $oDadosMatricula->datamodif;
              $oDaoMatricula->ed60_d_datamodif    = $dDataMatricula;
              $oDaoMatricula->ed60_i_codigo       = $oDadosMatricula->codmatricula;
              $oDaoMatricula->alterar($oDadosMatricula->codmatricula);
              if ($oDaoMatricula->erro_status == '0') {

                $oDaoTrocaSerie->erro_status = '0';
                $oDaoTrocaSerie->erro_msg    = $oDaoMatricula->erro_msg;

              } else {

                /* Inser��o do registro de movimenta��o da matr�cula antiga do aluno */
                $oDaoMatriculaMov->ed229_i_matricula    = $oDadosMatricula->codmatricula;
                $oDaoMatriculaMov->ed229_i_usuario      = db_getsession('DB_id_usuario');
                $oDaoMatriculaMov->ed229_c_procedimento = 'PROGRESS�O DE ALUNO -> '.$sPalavra1;
                $oDaoMatriculaMov->ed229_t_descr        = 'ALUNO '.$sPalavra2.' DA TURMA '.trim($ed57_c_descrorig).
                                                          ' / '.
                                                          trim($ed11_c_origem)." PARA A TURMA ".
                                                          trim($ed57_c_descrdest).' / '.trim($ed11_c_destino);
                $oDaoMatriculaMov->ed229_d_dataevento   = $dDataMatricula;
                $oDaoMatriculaMov->ed229_c_horaevento   = date('H:i');
                $oDaoMatriculaMov->ed229_d_data         = date('Y-m-d', db_getsession('DB_datausu'));
                $oDaoMatriculaMov->incluir(null);
                if ($oDaoMatriculaMov->erro_status == '0') {

                  $oDaoTrocaSerie->erro_status = '0';
                  $oDaoTrocaSerie->erro_msg    = $oDaoMatriculaMov->erro_msg;

                } else {

                  /* Atualizo a quantidade de matr�culas da turma de destino */
                  $sSql = $oDaoMatricula->sql_query_file(null, ' count(*) as qtdematricula', '',
                                                         " ed60_i_turma = $ed101_i_turmadest ".
                                                         "and ed60_c_situacao = 'MATRICULADO'"
                                                        );
                  $rs    = $oDaoMatricula->sql_record($sSql);
                  $iQtde = db_utils::fieldsmemory($rs, 0)->qtdematricula;
                  $iQtde = $iQtde == '' ? 0 : $iQtde;

                  $oDaoTurma->ed57_i_nummatr = $iQtde;
                  $oDaoTurma->ed57_i_codigo  = $ed101_i_turmadest;
                  $oDaoTurma->alterar($ed101_i_turmadest);
                  if ($oDaoTurma->erro_status == '0') {

                    $oDaoTrocaSerie->erro_status = '0';
                    $oDaoTrocaSerie->erro_msg    = $oDaoTurma->erro_msg;

                  } else {

                    /* Atualizo a quantidade de matr�culas da turma de origem */
                    $sSql = $oDaoMatricula->sql_query_file(null, ' count(*) as qtdematricula', '',
                                                           " ed60_i_turma = $ed101_i_turmaorig ".
                                                           "and ed60_c_situacao = 'MATRICULADO'"
                                                          );

                    $rs    = $oDaoMatricula->sql_record($sSql);
                    $iQtde = db_utils::fieldsmemory($rs, 0)->qtdematricula;
                    $iQtde = $iQtde == '' ? 0 : $iQtde;

                    $oDaoTurma->ed57_i_nummatr = $iQtde;
                    $oDaoTurma->ed57_i_codigo  = $ed101_i_turmaorig;
                    $oDaoTurma->alterar($ed101_i_turmaorig);
                    if ($oDaoTurma->erro_status == '0') {

                      $oDaoTrocaSerie->erro_status = '0';
                      $oDaoTrocaSerie->erro_msg    = $oDaoTurma->erro_msg;

                    } else {

                      /* Incluo dados da progress�o no hist�rico do aluno */
                      if ($oDadosMatricula->tipoturma == 2) {
                        $sCondicao = ' and ed11_i_sequencia >= '.$oDadosMatricula->seqorigem;
                      } else {
                        $sCondicao = ' and ed11_i_sequencia = '.$oDadosMatricula->seqorigem;
                      }
                      $sSql = $oDaoTurmaSerieRegimeMat->sql_query(null, 'ed223_i_serie', 'ed223_i_ordenacao',
                                                                  " ed220_i_turma = $ed101_i_turmaorig $sCondicao"
                                                                 );
                      $rs   = $oDaoTurmaSerieRegimeMat->sql_record($sSql);
                      for ($iCont = 0; $iCont < $oDaoTurmaSerieRegimeMat->numrows; $iCont++) {

                        /* Incluo hist�rico para todas as s�ries (etapas) da turma de origem */
                        $iSerie = db_utils::fieldsmemory($rs, $iCont)->ed223_i_serie;
                        $sSql   = $oDaoHistorico->sql_query_file(null, 'ed61_i_codigo', '',
                                                                 " ed61_i_aluno = $ed101_i_aluno ".
                                                                 "and ed61_i_curso = $oDadosMatricula->codcurso"
                                                                );
                        $rs2    = $oDaoHistorico->sql_record($sSql);
                        if ($oDaoHistorico->numrows == 0) {

                          $oDaoHistorico->ed61_i_escola = $iEscola;
                          $oDaoHistorico->ed61_i_aluno  = $ed101_i_aluno;
                          $oDaoHistorico->ed61_i_curso  = $oDadosMatricula->codcurso;
                          $oDaoHistorico->ed61_t_obs    = '';
                          $oDaoHistorico->incluir(null);
                          if ($oDaoHistorico->erro_status == '0') {

                            $oDaoTrocaSerie->erro_status = '0';
                            $oDaoTrocaSerie->erro_msg    = $oDaoHistorico->erro_msg;
                            break;

                          } else {
                            $iCodHistorico = $oDaoHistorico->ed61_i_codigo;
                          }

                        } else {
                          $iCodHistorico = db_utils::fieldsmemory($rs2, 0)->ed61_i_codigo;
                        }

                        if ($oDaoTrocaSerie->erro_status != '0') {

                          /* Incluo hist�rico mps */
                          $oDaoHistoricoMps->ed62_i_historico          = $iCodHistorico;
                          $oDaoHistoricoMps->ed62_i_escola             = $iEscola;
                          $oDaoHistoricoMps->ed62_i_serie              = $iSerie;
                          $oDaoHistoricoMps->ed62_i_turma              = $oDadosMatricula->nometurma;
                          $oDaoHistoricoMps->ed62_i_anoref             = $oDadosMatricula->anoref;
                          $oDaoHistoricoMps->ed62_i_justificativa      = null;
                          $oDaoHistoricoMps->ed62_i_periodoref         = '0';
                          $oDaoHistoricoMps->ed62_c_resultadofinal     = 'A';
                          $oDaoHistoricoMps->ed62_c_situacao           = 'CONCLU�DO';
                          $oDaoHistoricoMps->ed62_i_diasletivos        = 200;
                          $oDaoHistoricoMps->ed62_i_qtdch              = 0;
                          $oDaoHistoricoMps->ed62_lancamentoautomatico = 'true';
                          $oDaoHistoricoMps->incluir(null);
                          if ($oDaoHistoricoMps->erro_status == '0') {

                            $oDaoTrocaSerie->erro_status = '0';
                            $oDaoTrocaSerie->erro_msg    = "Etapa:{$oDaoHistoricoMps->erro_msg}";
                            break;

                          } else { // Incluo no historicompsdisc


                            $iCodMps   = $oDaoHistoricoMps->ed62_i_codigo;
                            $sSql      = $oDaoProcedimento->sql_query(null, 'substr(ed37_c_tipo,1,1) as ed37_c_tipo',
                                                                      '',
                                                                      ' ed40_i_codigo = '.$oDadosMatricula->codproc
                                                                     );
                            $rs2       = $oDaoProcedimento->sql_record($sSql);
                            $sTipoAval = db_utils::fieldsmemory($rs2, 0)->ed37_c_tipo;
                            $sSql      = $oDaoRegencia->sql_query('', 'ed59_i_codigo as regencia, '.
                                                                  'ed12_i_codigo as disciplina, '.
                                                                  'ed59_i_qtdperiodo', '',
                                                                  " ed59_i_turma = $ed101_i_turmaorig"
                                                                 );
                            $rs2       = $oDaoRegencia->sql_record($sSql);
                            for ($iCont2 = 0; $iCont2 < $oDaoRegencia->numrows; $iCont2++) {

                              /* Para cada registro no hist�rico, tenho uma s�rie (etapa) e para cada s�rie tenho
                                 v�rias disciplinas que devem ser inseridas no hist�rico */
                              $oDadosRegencia = db_utils::fieldsmemory($rs2, $iCont2);
                              $iAulas = $oDadosMatricula->semanas * $oDadosRegencia->ed59_i_qtdperiodo;
                              /* Incluo hist�rico de cada disciplina da etapa (s�rie) */
                              $oDaoHistMpsDisc->ed65_i_historicomps       = $iCodMps;
                              $oDaoHistMpsDisc->ed65_i_disciplina         = $oDadosRegencia->disciplina;
                              $oDaoHistMpsDisc->ed65_i_justificativa      = null;
                              $oDaoHistMpsDisc->ed65_i_qtdch              = $iAulas;
                              $oDaoHistMpsDisc->ed65_c_resultadofinal     = 'A';
                              $oDaoHistMpsDisc->ed65_t_resultobtido       = $sPalavra3;
                              $oDaoHistMpsDisc->ed65_c_situacao           = 'CONCLU�DO';
                              $oDaoHistMpsDisc->ed65_c_tiporesultado      = $sTipo;
                              $oDaoHistMpsDisc->ed65_lancamentoautomatico = "true";
                              $oDaoHistMpsDisc->incluir(null);
                              if ($oDaoHistMpsDisc->erro_status == '0') {

                                $oDaoTrocaSerie->erro_status = '0';
                                $oDaoTrocaSerie->erro_msg    = "serie:".$oDaoHistMpsDisc->erro_msg;
                                break 2;

                              }

                            }

                          }

                        }

                      }

                      if ($oDaoTrocaSerie->erro_status != '0') { // Se n�o houve erro at� ent�o

                        /* Atualizo alunocurso */
                        $sSql        = $oDaoTurma->sql_query_file('', '*', '', " ed57_i_codigo = $ed101_i_turmadest");
                        $rs          = $oDaoTurma->sql_record($sSql);
                        $oDadosTurma = db_utils::fieldsmemory($rs, 0);
                        $sSql        = $oDaoAlunoCurso->sql_query('', 'ed56_i_codigo', '',
                                                                  " ed56_i_aluno = $ed101_i_aluno"
                                                                 );
                        $rs          = $oDaoAlunoCurso->sql_record($sSql);
                        $iAlunoCurso = db_utils::fieldsmemory($rs, 0)->ed56_i_codigo;
                        $oDaoAlunoCurso->ed56_i_codigo   = $iAlunoCurso;
                        $oDaoAlunoCurso->ed56_c_situacao = 'MATRICULADO';
                        $oDaoAlunoCurso->ed56_i_base     = $oDadosTurma->ed57_i_base;
                        $oDaoAlunoCurso->ed56_i_baseant  = $oDadosMatricula->baseant;
                        $oDaoAlunoCurso->alterar($iAlunoCurso);
                        if ($oDaoAlunoCurso->erro_status == '0') {

                          $oDaoTrocaSerie->erro_status = '0';
                          $oDaoTrocaSerie->erro_msg    = $oDaoAlunoCurso->erro_msg;

                        } else {

                          $sSql       = $oDaoAlunoPossib->sql_query_file(null, 'ed79_i_codigo', '',
                                                                         " ed79_i_alunocurso = $iAlunoCurso"
                                                                        );
                          $rs         = $oDaoAlunoPossib->sql_record($sSql);
                          $iCodPossib = db_utils::fieldsmemory($rs, 0)->ed79_i_codigo;
                          $oDaoAlunoPossib->ed79_i_codigo     = $iCodPossib;
                          $oDaoAlunoPossib->ed79_i_alunocurso = $iAlunoCurso;
                          $oDaoAlunoPossib->ed79_i_serie      = $ed11_i_coddestino;
                          $oDaoAlunoPossib->ed79_i_turno      = $oDadosTurma->ed57_i_turno;
                          $oDaoAlunoPossib->ed79_i_turmaant   = $ed101_i_turmaorig;
                          $oDaoAlunoPossib->ed79_c_resulant   = 'A';
                          $oDaoAlunoPossib->ed79_c_situacao   = 'A';
                          $oDaoAlunoPossib->alterar($iCodPossib);
                          if ($oDaoAlunoPossib->erro_status == '0') {

                            $oDaoTrocaSerie->erro_status = '0';
                            $oDaoTrocaSerie->erro_msg    = $oDaoAlunoPossib->erro_msg;

                          }

                        }

                      }

                    }

                  }

                }

              }

            }

          }

        }

      }

    }

  }
  /* FIM DO BLOCO QUE FAZ A PROGRESS�O DO ALUNO *****************/

  /**************** IN�CIO DO BLOCO RESPONS�VEL PELA IMPORTA��O DE APROVEITAMENTO DO ALUNO */
  if ($import == 'S' && $oDaoTrocaSerie->erro_status != '0') {

    /* $perequiv cont�m os per�odos equivalentes informados pelo usu�rio no seguinte formato:
        per1|perequiv1Xper2|perequiv2...
    */
    $periodos      = explode('X', $perequiv);
    $msg_conversao = '';
    $sep_conversao = '';
    for ($iCont = 0; $iCont < count($periodos); $iCont++) {

      $divideperiodos = explode('|', $periodos[$iCont]);
      $periodoorigem  = $divideperiodos[0];
      $periododestino = $divideperiodos[1];

      /* Busco as informa��es do per�odo de avalia��o de destino */
      $sSql           = $oDaoProcAvaliacao->sql_query(null, 'ed09_i_codigo, ed09_c_descr as perdestdescricao, '.
                                                      'ed37_c_tipo as tipodestino, '.
                                                      'ed37_i_maiorvalor as mvdestino', '',
                                                      " ed41_i_codigo = $periododestino"
                                                     );
      $rs             = $oDaoProcAvaliacao->sql_record($sSql);
      if ($oDaoProcAvaliacao->erro_status == '0' || $oDaoProcAvaliacao->numrows == 0) {

        $oDaoTrocaSerie->erro_status = '0';
        $oDaoTrocaSerie->erro_msg    = 'N�o foi poss�vel obter as informa��es dos per�odos de avali��o. ';
        $oDaoTrocaSerie->erro_msg   .= 'Opera��o cancelada.';
        break;

      }

      /* Busco as informa��es do per�odo de avalia��o de orgiem */
      $oDadosPerDest  = db_utils::fieldsmemory($rs, 0);
      $sSql           = $oDaoProcAvaliacao->sql_query('', 'ed37_c_tipo as tipoorigem, '.
                                                      'ed37_i_maiorvalor as mvorigem', '',
                                                      " ed41_i_codigo = $periodoorigem"
                                                     );
      $rs             = $oDaoProcAvaliacao->sql_record($sSql);
      if ($oDaoProcAvaliacao->erro_status == '0' || $oDaoProcAvaliacao->numrows == 0) {

        $oDaoTrocaSerie->erro_status = '0';
        $oDaoTrocaSerie->erro_msg    = 'N�o foi poss�vel obter as informa��es dos per�odos de avali��o. ';
        $oDaoTrocaSerie->erro_msg   .= 'Opera��o cancelada.';
        break;

      }

      $oDadosPerOrig = db_utils::fieldsmemory($rs, 0);

      /* Verifico as diferen�as nas formas de avalia��o dos procedimentos de avalia��o dos dois per�odos
         verificando a compatibilidade
      */
      if (trim($oDadosPerOrig->tipoorigem) != trim($oDadosPerDest->tipodestino)
          || (trim($oDadosPerOrig->tipoorigem) == trim($oDadosPerDest->tipodestino)
              && $oDadosPerOrig->mvorigem != $oDadosPerDest->mvdestino)) {

        $msg_conversao .= $sep_conversao." ".$perdestdescricao;
        $sep_conversao  = ',';

      }

      /* $regequiv cont�m as reg�ncias (disciplinas nas s�ries) equivalentes informadas no seguinte formato:
          reg1|regequiv1Xreg2|regequiv2...
      */
      $regencias = explode('X', $regequiv);
      for ($iCont2 = 0; $iCont2<count($regencias);$iCont2++) {

        $divideregencias = explode('|', $regencias[$iCont2]);
        $regenciaorigem  = $divideregencias[0];
        $regenciadestino = $divideregencias[1];

        /* Busco o Di�rio do aluno para a regencia de origem. Se n�o existir, n�o poder� ser importado (�bvio) */
        $sSql            = $oDaoDiario->sql_query_file(null, 'ed95_i_codigo as coddiarioorigem', '',
                                                       ' ed95_i_regencia = '.$regenciaorigem.
                                                       ' and ed95_i_aluno = '.$ed101_i_aluno
                                                      );
        $rs              = $oDaoDiario->sql_record($sSql);
        if ($oDaoDiario->numrows > 0) {
          $coddiarioorigem = db_utils::fieldsmemory($rs, 0)->coddiarioorigem;
        } else {
          $coddiarioorigem = -1; // N�o vai existir di�rio com este c�digo, logo, nas querys vir� record vazio
        }

        /* Busco o Di�rio do aluno para a regencia de destinho. Se n�o existir, ent�o insiro um novo di�rio */
        $sSql = $oDaoDiario->sql_query_file(null, 'ed95_i_codigo', '',
                                            ' ed95_i_regencia = '.$regenciadestino.
                                            ' and ed95_i_aluno = '.$ed101_i_aluno
                                           );
        $rs   = $oDaoDiario->sql_record($sSql);
        if ($oDaoDiario->numrows == 0) {

          $oDaoDiario->ed95_c_encerrado  = 'N';
          $oDaoDiario->ed95_i_escola     = $oDadosTurma->ed57_i_escola;
          $oDaoDiario->ed95_i_calendario = $oDadosTurma->ed57_i_calendario;
          $oDaoDiario->ed95_i_aluno      = $ed101_i_aluno;
          $oDaoDiario->ed95_i_serie      = $codetapadestino;
          $oDaoDiario->ed95_i_regencia   = $regenciadestino;
          $oDaoDiario->incluir(null);
          if ($oDaoDiario->erro_status == '0') {

            echo 'Sim!';

            $oDaoTrocaSerie->erro_status = '0';
            $oDaoTrocaSerie->erro_msg    = $oDaoDiario->erro_msg;
            break 2;

          }
          $iDiarioDest = $oDaoDiario->ed95_i_codigo;

        } else {

          $iDiarioDest                  = db_utils::fieldsmemory($rs, 0)->ed95_i_codigo;
          $oDaoDiario                   = db_utils::getdao('diario'); // Fa�o isso para limpar o valor dos atributos
          $oDaoDiario->ed95_i_codigo    = $iDiarioDest;
          $oDaoDiario->ed95_c_encerrado = 'N'; // Abro o di�rio, se estava encerrado
          $oDaoDiario->alterar($iDiarioDest);
          if ($oDaoDiario->erro_status == '0') {

            $oDaoTrocaSerie->erro_status = '0';
            $oDaoTrocaSerie->erro_msg    = $oDaoDiario->erro_msg;
            break 2;

          }

        }

        /* Importa��o dos amparos */
        $sSql = $oDaoAmparo->sql_query_file(null, 'ed81_i_codigo as codamparoorigem, '.
                                            'ed81_i_justificativa, ed81_i_convencaoamp, '.
                                            'ed81_c_todoperiodo, ed81_c_aprovch', '',
                                            "ed81_i_diario = $coddiarioorigem"
                                           );
        $rs   = $oDaoAmparo->sql_record($sSql);
        if ($oDaoAmparo->numrows > 0) { // Se tinha amparo, importa

          $oDadosAmparoOrig = db_utils::fieldsmemory($rs, 0);
          $sSql             = $oDaoAmparo->sql_query_file(null, 'ed81_i_codigo', '',
                                                          " ed81_i_diario = $iDiarioDest"
                                                         );
          $rs               = $oDaoAmparo->sql_record($sSql);
          if ($oDaoAmparo->numrows == 0) {

            $oDaoAmparo->ed81_i_diario        = $iDiarioDest;
            $oDaoAmparo->ed81_c_aprovch       = $oDadosAmparoOrig->ed81_c_aprovch;
            $oDaoAmparo->ed81_c_todoperiodo   = $oDadosAmparoOrig->ed81_c_todoperiodo;
            $oDaoAmparo->ed81_i_justificativa = $oDadosAmparoOrig->ed81_i_justificativa;
            $oDaoAmparo->ed81_i_convencaoamp  = $oDadosAmparoOrig->ed81_i_convencaoamp;
            $oDaoAmparo->incluir(null);
            if ($oDaoAmparo->erro_status == '0') {

              $oDaoTrocaSerie->erro_status = '0';
              $oDaoTrocaSerie->erro_msg    = $oDaoAmparo->erro_msg;
              break 2;

            }

          } else {

            $iAmparoDest                      = db_fieldsmemory($rs, 0)->ed81_i_codigo;
            $oDaoAmparo->ed81_i_diario        = $iDiarioDest;
            $oDaoAmparo->ed81_c_aprovch       = $oDadosAmparoOrig->ed81_c_aprovch;
            $oDaoAmparo->ed81_c_todoperiodo   = $oDadosAmparoOrig->ed81_c_todoperiodo;
            $oDaoAmparo->ed81_i_justificativa = $oDadosAmparoOrig->ed81_i_justificativa;
            $oDaoAmparo->ed81_i_convencaoamp  = $oDadosAmparoOrig->ed81_i_convencaoamp;
            $oDaoAmparo->ed81_i_codigo        = $iAmparoDest;
            $oDaoAmparo->alterar($iAmparoDest);
            if ($oDaoAmparo->erro_status == '0') {

              $oDaoTrocaSerie->erro_status = '0';
              $oDaoTrocaSerie->erro_msg    = $oDaoAmparo->erro_msg;
              break 2;

            }

          }

        }

        /* Verifico se j� foi gerado registro no diariofinal para o di�rio de destino. Se ainda n�o foi, incluo. */
        $sSql = $oDaoDiarioFinal->sql_query_file(null, 'ed74_i_diario', '',
                                                 " ed74_i_diario = $iDiarioDest"
                                                );
        $rs   = $oDaoDiarioFinal->sql_record($sSql);
        if ($oDaoDiarioFinal->numrows == 0) {

          $oDaoDiarioFinal->ed74_i_diario = $iDiarioDest;
          $oDaoDiarioFinal->incluir(null);
          if ($oDaoDiarioFinal->erro_status == '0') {

            $oDaoTrocaSerie->erro_status = '0';
            $oDaoTrocaSerie->erro_msg    = $oDaoDiarioFinal->erro_msg;
            break 2;

          }

        }

        /* Busco os dados do di�rio de avalia��o de origem para importar para o de destino */
        $sSql = $oDaoDiarioAvaliacao->sql_query_file(null, 'ed72_i_codigo as codavalorigem, '.
                                                     'ed72_i_numfaltas, ed72_i_valornota, '.
                                                     'ed72_c_valorconceito, ed72_t_parecer, '.
                                                     'ed72_c_aprovmin, ed72_c_amparo, '.
                                                     'ed72_t_obs, ed72_i_escola, ed72_c_tipo', '',
                                                     " ed72_i_diario = $coddiarioorigem ".
                                                     "and ed72_i_procavaliacao = $periodoorigem"
                                                    );
        $rs   = $oDaoDiarioAvaliacao->sql_record($sSql);

        if ($oDaoDiarioAvaliacao->numrows > 0) {
          $oDadosDiarioAval = db_utils::fieldsmemory($rs, 0);
        } else {

          $oDadosDiarioAval = new stdClass();
          $oDadosDiarioAval->codavalorigem        = '';
          $oDadosDiarioAval->ed72_i_numfaltas     = null;
          $oDadosDiarioAval->ed72_i_valornota     = null;
          $oDadosDiarioAval->ed72_c_valorconceito = '';
          $oDadosDiarioAval->ed72_t_parecer       = '';
          $oDadosDiarioAval->ed72_c_aprovmin      = 'N';
          $oDadosDiarioAval->ed72_c_amparo        = 'N';
          $oDadosDiarioAval->ed72_t_obs           = '';
          $oDadosDiarioAval->ed72_i_escola        = db_getsession('DB_coddepto');
          $oDadosDiarioAval->ed72_c_tipo          = 'M';

        }

        if (trim($oDadosPerOrig->tipoorigem) != trim($oDadosPerDest->tipodestino)
            || (trim($oDadosPerOrig->tipoorigem) == trim($oDadosPerDest->tipodestino)
                && $oDadosPerOrig->mvorigem != $oDadosPerDest->mvdestino)) {

          if ($oDadosDiarioAval->ed72_i_valornota == ''
              && $oDadosDiarioAval->ed72_c_valorconceito == ''
              && $oDadosDiarioAval->ed72_t_parecer =='') {
            $oDadosDiarioAval->ed72_c_convertido = 'N';
          } else {
            $oDadosDiarioAval->ed72_c_convertido = 'S';
          }

        } else {
          $oDadosDiarioAval->ed72_c_convertido = 'N';
        }

        /* Verifico se j� tem um diario de avalia��o de destino para importar os dados para ele */
        $sSql = $oDaoDiarioAvaliacao->sql_query_file(null, 'ed72_i_codigo', '',
                                                     " ed72_i_diario = $iDiarioDest ".
                                                     "and ed72_i_procavaliacao = $periododestino"
                                                    );
        $rs   = $oDaoDiarioAvaliacao->sql_record($sSql);
        if ($oDaoDiarioAvaliacao->numrows == 0) {

          $oDaoDiarioAvaliacao->ed72_i_diario        = $iDiarioDest;
          $oDaoDiarioAvaliacao->ed72_i_procavaliacao = $periododestino;
          $oDaoDiarioAvaliacao->ed72_i_numfaltas     = $oDadosDiarioAval->ed72_i_numfaltas;
          $oDaoDiarioAvaliacao->ed72_i_valornota     = $oDadosDiarioAval->ed72_i_valornota;
          $oDaoDiarioAvaliacao->ed72_c_valorconceito = $oDadosDiarioAval->ed72_c_valorconceito;
          $oDaoDiarioAvaliacao->ed72_t_parecer       = $oDadosDiarioAval->ed72_t_parecer;
          $oDaoDiarioAvaliacao->ed72_c_aprovmin      = $oDadosDiarioAval->ed72_c_aprovmin;
          $oDaoDiarioAvaliacao->ed72_c_amparo        = $oDadosDiarioAval->ed72_c_amparo;
          $oDaoDiarioAvaliacao->ed72_t_obs           = $oDadosDiarioAval->ed72_t_obs;
          $oDaoDiarioAvaliacao->ed72_i_escola        = $oDadosDiarioAval->ed72_i_escola;
          $oDaoDiarioAvaliacao->ed72_c_tipo          = $oDadosDiarioAval->ed72_c_tipo;
          $oDaoDiarioAvaliacao->ed72_c_convertido    = $oDadosDiarioAval->ed72_c_convertido;
          $oDaoDiarioAvaliacao->incluir(null);
          if ($oDaoDiarioAvaliacao->erro_status == '0') {

             $oDaoTrocaSerie->erro_status = '0';
             $oDaoTrocaSerie->erro_msg    = $oDaoDiarioAvaliacao->erro_msg;
             break 2;

          }
          $iDiarioAvalDest = $oDaoDiarioAvaliacao->ed72_i_codigo;

        } else {

          $iDiarioAvalDest                           = db_utils::fieldsmemory($rs, 0)->ed72_i_codigo;
          $oDaoDiarioAvaliacao->ed72_i_diario        = $iDiarioDest;
          $oDaoDiarioAvaliacao->ed72_i_procavaliacao = $periododestino;
          $oDaoDiarioAvaliacao->ed72_i_numfaltas     = $oDadosDiarioAval->ed72_i_numfaltas;
          $oDaoDiarioAvaliacao->ed72_i_valornota     = $oDadosDiarioAval->ed72_i_valornota;
          $oDaoDiarioAvaliacao->ed72_c_valorconceito = $oDadosDiarioAval->ed72_c_valorconceito;
          $oDaoDiarioAvaliacao->ed72_t_parecer       = $oDadosDiarioAval->ed72_t_parecer;
          $oDaoDiarioAvaliacao->ed72_c_aprovmin      = $oDadosDiarioAval->ed72_c_aprovmin;
          $oDaoDiarioAvaliacao->ed72_c_amparo        = $oDadosDiarioAval->ed72_c_amparo;
          $oDaoDiarioAvaliacao->ed72_t_obs           = $oDadosDiarioAval->ed72_t_obs;
          $oDaoDiarioAvaliacao->ed72_i_escola        = $oDadosDiarioAval->ed72_i_escola;
          $oDaoDiarioAvaliacao->ed72_c_tipo          = $oDadosDiarioAval->ed72_c_tipo;
          $oDaoDiarioAvaliacao->ed72_c_convertido    = $oDadosDiarioAval->ed72_c_convertido;
          $oDaoDiarioAvaliacao->ed72_i_codigo        = $iDiarioAvalDest;
          $oDaoDiarioAvaliacao->alterar($iDiarioAvalDest);
          if ($oDaoDiarioAvaliacao->erro_status == '0') {

             $oDaoTrocaSerie->erro_status = '0';
             $oDaoTrocaSerie->erro_msg    = $oDaoDiarioAvaliacao->erro_msg;
             break 2;

          }

        }

        /* Se os dados foram importados do di�rio de avalia��o de origem, tenho que registrar na transfaprov.
           Importo tamb�m os dados da pareceraval da abonofalta
        */
        if ($oDadosDiarioAval->codavalorigem != '') {

          $sSql = $oDaoTransfAprov->sql_query_file(null, 'ed251_i_codigo', '',
                                                   ' ed251_i_diariodestino = '.$oDadosDiarioAval->codavalorigem
                                                  );
          $rs   = $oDaoTransfAprov->sql_record($sSql);
          if ($oDaoTransfAprov->numrows > 0) {

            $iTransfAprov                           = db_utils::fieldsmemory($rs, 0)->ed251_i_codigo;
            $oDaoTransfAprov->ed251_i_diariodestino = $ed72_i_codigo;
            $oDaoTransfAprov->ed251_i_codigo        = $iTransfAprov;
            $oDaoTransfAprov->alterar($iTransfAprov);
            if ($oDaoTransfAprov->erro_status == '0') {

               $oDaoTrocaSerie->erro_status = '0';
               $oDaoTrocaSerie->erro_msg    = $oDaoTransfAprov->erro_msg;
               break 2;

            }

          } else {

            if ($oDadosDiarioAval->ed72_c_convertido == 'S') {

              $oDaoTransfAprov->ed251_i_diariodestino = $iDiarioAvalDest;
              $oDaoTransfAprov->ed251_i_diarioorigem  = $oDadosDiarioAval->codavalorigem;
              $oDaoTransfAprov->incluir(null);
              if ($oDaoTransfAprov->erro_status == '0') {

                $oDaoTrocaSerie->erro_status = '0';
                $oDaoTrocaSerie->erro_msg    = $oDaoTransfAprov->erro_msg;
                break 2;

              }

            }

          }

          /* Importo os dados da tabela pareceraval */

          $sSql    = $oDaoParecerAval->sql_query_file('', 'ed93_t_parecer', '',
                                                      ' ed93_i_diarioavaliacao = '.
                                                      $oDadosDiarioAval->codavalorigem
                                                     );
          $rs      = $oDaoParecerAval->sql_record($sSql);
          $iLinhas = $oDaoParecerAval->numrows;
          if ($iLinhas > 0) {

            for ($iCont3 = 0; $iCont3 < $iLinhas; $iCont3++) {

              $sParecer                                = db_utils::fieldsmemory($rs, $iCont3)->ed93_t_parecer;
              $oDaoParecerAval->ed93_i_diarioavaliacao = $iDiarioAvalDest;
              $oDaoParecerAval->ed93_t_parecer         = $sParecer;
              $oDaoParecerAval->incluir(null);
              if ($oDaoParecerAval->erro_status == '0') {

                $oDaoTrocaSerie->erro_status = '0';
                $oDaoTrocaSerie->erro_msg    = $oDaoParecerAval->erro_msg;
                break 3;

              }

            }

          }

          /* A importa��o dos abonos de faltas */
          $sSql    = $oDaoAbonoFalta->sql_query_file(null, '*', '',
                                                     ' ed80_i_diarioavaliacao = '.
                                                     $oDadosDiarioAval->codavalorigem
                                                    );
          $rs      = $oDaoAbonoFalta->sql_record($sSql);
          $iLinhas = $oDaoAbonoFalta->numrows;
          if ($iLinhas > 0) {

            for ($iCont3 = 0; $iCont3 < $iLinhas; $iCont3++) {

              $oDadosAbono                            = db_utils::fieldsmemory($rs, $iCont3);
              $oDaoAbonoFalta->ed80_i_diarioavaliacao = $iDiarioAvalDest;
              $oDaoAbonoFalta->ed80_i_justificativa   = $oDadosAbono->ed80_i_justificativa;
              $oDaoAbonoFalta->ed80_i_numfaltas       = $oDadosAbono->ed80_i_numfaltas;
              $oDaoAbonoFalta->incluir(null);
              if ($oDaoAbonoFalta->erro_status == '0') {

                $oDaoTrocaSerie->erro_status = '0';
                $oDaoTrocaSerie->erro_msg    = $oDaoAbonoFalta->erro_msg;
                break 3;

              }

            }

          }

        }

      } // Fim do for das reg�ncias

    } // Fim do for dos per�odos de avalia��o

  } // Fim do if que verifica se � para realizar a importa��o do aproveitamento escolar

  //db_fim_transacao(true);
  db_fim_transacao($oDaoTrocaSerie->erro_status == '0');

  if ($oDaoTrocaSerie->erro_status != '0') {

    if (isset($msg_conversao) && $msg_conversao != '') {

      $mensagem  = "ATEN��O!\\n\\n Caso o aluno tenha algum aproveitamento nos per�odos abaixo relacionados, ";
      $mensagem .= 'os mesmos dever�o ser convertidos no Di�rio de Classe, devido a forma de avalia��o da turma ';
      $mensagem .= "de origem ser diferente da turma de destino:\\n\\n$msg_conversao";
      db_msgbox($mensagem);

    }

    db_msgbox('Progress�o do aluno realizada com sucesso!');
    echo "<script>parent.window.location = '$sUrlRetorno';</script>"; // $sUrlRetorno vem por GET

  } else {

    db_msgbox('Progress�o do aluno N�O realizada.');
    $oDaoTrocaSerie->erro(true, false);
    echo "<script>parent.window.location = '$sUrlRetorno';</script>"; // $sUrlRetorno vem por GET

  }

}
?>
<script>document.getElementById("tab_aguarde").style.visibility = "hidden";</script>