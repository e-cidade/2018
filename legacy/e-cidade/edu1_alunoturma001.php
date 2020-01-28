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
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_matricula_classe.php"));
require_once(modification("classes/db_edu_parametros_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("model/educacao/DBEducacaoTermo.model.php"));

$trocaTurma  = 1;

db_postmemory($_GET);
db_postmemory($_POST);

$clmatricula      = new cl_matricula;
$cledu_parametros = new cl_edu_parametros;
$escola           = db_getsession("DB_coddepto");

/**
 * Adicionado para corrigir variáveis não definadas na aba alunos rotina:
 * Matricula > Rematricular
 */
if ( (!isset($ed52_c_descr) || !isset($ed57_c_descr)) && isset($ed60_i_turma) ) {

  $oTurma = TurmaRepository::getTurmaByCodigo($ed60_i_turma);
  $ed52_c_descr = $oTurma->getCalendario()->getDescricao();
  $ed57_c_descr = $oTurma->getDescricao();
}

function montaStringTurnoMatriculado ($sTurnoReferente) {

  $aTurnos      = array(1 => "Manhã", 2 => "Tarde", 3 => "Noite");
  $aCodigoTurno = explode(",", $sTurnoReferente);
  $aStringTurno = array();

  foreach ($aCodigoTurno as $iCodigoTurnoReferente) {
    $aStringTurno[] = $aTurnos[$iCodigoTurnoReferente];
  }

  return implode(" / ", $aStringTurno);
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
<script language="JavaScript" type="text/javascript" src="scripts/DBFormCache.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/DBFormSelectCache.js"></script>
<style>
a:link{
 color: #444444;
 font-weight: bold;
 text-decoration: none;
}
a:hover{
 color: #FF9900;
 text-decoration: none;
}
a:visited{
 color: #444444;
 font-weight: bold;
 text-decoration: none;
}
a:active{
 color: #444444;
 font-weight: bold;
 text-decoration: none;
}
.cabec{
 text-align: left;
 font-size: 10;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 11;
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css"/>
</head>
<body class="body-default">
<?php

$sCampos  = " aluno.ed47_v_nome,                                                                                                                ";
$sCampos .= " aluno.ed47_i_codigo,                                                                                                              ";
$sCampos .= " matricula.ed60_i_numaluno,                                                                                                        ";
$sCampos .= " matricula.ed60_c_situacao,                                                                                                        ";
$sCampos .= " matricula.ed60_c_concluida,                                                                                                       ";
$sCampos .= " matricula.ed60_c_rfanterior,                                                                                                      ";
$sCampos .= " matricula.ed60_i_turmaant,                                                                                                        ";
$sCampos .= " matricula.ed60_d_datamatricula,                                                                                                   ";
$sCampos .= " matricula.ed60_matricula,                                                                                                         ";
$sCampos .= " matricula.ed60_i_codigo,                                                                                                          ";
$sCampos .= " matricula.ed60_c_parecer, ed60_tipoingresso,                                                                                      ";
$sCampos .= " serie.ed11_c_descr,                                                                                                               ";
$sCampos .= " to_char(alunotransfturma.ed69_d_datatransf,'DD/MM/YYYY') as datasaida,                                                            ";
$sCampos .= " (select array_to_string(array_accum(ed336_turnoreferente), ',')                                                                   ";
$sCampos .= "    from matriculaturnoreferente                                                                                                   ";
$sCampos .= "         inner join matricula as matturno on matturno.ed60_i_codigo = matriculaturnoreferente.ed337_matricula                      ";
$sCampos .= "                                         and matturno.ed60_i_turma  = turma.ed57_i_codigo                                          ";
$sCampos .= "         inner join turmaturnoreferente   on turmaturnoreferente.ed336_codigo = matriculaturnoreferente.ed337_turmaturnoreferente  ";
$sCampos .= "                                   and turmaturnoreferente.ed336_turma  = turma.ed57_i_codigo                                      ";
$sCampos .= "   where matturno.ed60_i_codigo = matricula.ed60_i_codigo) as turnoreferente,                                                      ";
$sCampos .= " matricula.ed60_d_datasaida                                                                                                        ";
$sWhereTrocaTurma = '';
if (isset($trocaTurma) && $trocaTurma == 1) {
  $sWhereTrocaTurma = " and ed60_c_situacao <> 'TROCA DE TURMA'";
}

$sSql   = $clmatricula->sql_query_aluno_transferido("",$sCampos,"matricula.ed60_i_numaluno, to_ascii(ed47_v_nome)"," ed60_i_turma = $ed60_i_turma {$sWhereTrocaTurma} ");
$result = $clmatricula->sql_record($sSql);

if (isset($classificar)) {

   $numaluno = "";
   if (pg_result($result,0,"ed60_i_numaluno") != "") {
     $numaluno = pg_result($result,0,"ed60_i_numaluno");
   }
  ?>
    <script type="text/javascript">
       js_OpenJanelaIframe('','db_iframe_classificacao',
    	                   'edu1_alunoturma002.php?ed60_i_turma=<?=$ed60_i_turma?>&ed57_c_descr=<?=$ed57_c_descr?>'+
    	                   '&ed52_c_descr=<?=$ed52_c_descr?>&numeroaluno=<?=$numaluno?>&trocaTurma=<?=$trocaTurma?>',
    	                   'Editar Classificação da Turma <?=$ed57_c_descr?> - Calendário: <?=$ed52_c_descr?>',true
    	                  );
    </script>
  <?php
}
?>
<table align="left" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top">
   <br>
   <center>
   <fieldset style="width:95%"><legend><strong>Alunos Matriculados na Turma <?=$ed57_c_descr?> - Calendário: <?=$ed52_c_descr?></strong></legend>
   <table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
   <tr>
    <td>
     <form name="form1" id='form1' method="POST">
      <input name="classificar" type="submit" value="Classificar Turma" <?=$clmatricula->numrows == 0?" disabled":""?>
             onClick="location.href = 'edu1_alunoturma002.php?ed60_i_turma=<?=$ed60_i_turma?>&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>'" >
      <input name="ed60_i_turma" type="hidden" value="<?=$ed60_i_turma?>">
      <input name="ed52_c_descr" type="hidden" value="<?=$ed52_c_descr?>">
      <input name="ed57_c_descr" type="hidden" value="<?=$ed57_c_descr?>">
      <label for="trocaTurma"><strong>Exibir Trocas de Turma:</strong></label>
      <?=db_select('trocaTurma', array(1 => "Não", 2 => "Sim"), true, 1); ?>
    </td>
     </form>
   </tr>
   <tr>
    <td align="center" valign="top">
     <table border='1px' width="100%" style="" cellspacing="0px">
      <tr class='cabec'>
       <td align='center'><strong>Código</strong></td>
       <td><strong>Nome</strong></td>
       <td align='center'><strong>N°</strong></td>
       <td align='center'><strong>Situação</strong></td>
       <td align='center'><strong>Turma Anterior</strong></td>
       <td align='center'><strong>RF Anterior</strong></td>
       <td align='center'><strong>Data Matrícula</strong></td>
       <td align='center'><strong>Data Saída</strong></td>
       <td align='center'><strong>Matrícula N°</strong></td>
       <td align='center'><strong>Turno</strong></td>
       <td align='center'><strong>Etapa</strong></td>
      </tr>
      <?php
      if ($clmatricula->numrows > 0) {

        $cor1 = "#DBDBDB";
        $cor2 = "#f3f3f3";
        $cor  = "";

        for ($c = 0; $c < $clmatricula->numrows; $c++) {

          db_fieldsmemory($result,$c);
          if ($cor == $cor1) {
            $cor = $cor2;
          } else {
            $cor = $cor1;
          }
          $turmaant = "";
          $rfant    = "";

          /**
           * $ed60_tipoingresso == 3 é uma matrícula de reclassificação
           */
          if ($ed60_tipoingresso != 3) {

            $inf_ant  = explode("|",RFanterior($ed60_i_codigo));
            $turmaant = $inf_ant[0];
            $rfant    = $inf_ant[1];
          }

          /**
           * Verificamos se a matricula do aluno possui 'rfanterior' e 'turmaant'
           * Caso sim, buscamos o ano e o ensino referente a turma anterior
           */
          if (!empty($ed60_c_rfanterior) && !empty($ed60_i_turmaant)) {

            $oDaoTurma    = db_utils::getDao('turma');
            $sCamposTurma = "ed52_i_ano, ed29_i_ensino, ed57_c_descr";
            $sWhereTurma  = "ed57_i_codigo = {$ed60_i_turmaant}";
            $sSqlTurma    = $oDaoTurma->sql_query(null, $sCamposTurma, null, $sWhereTurma);
            $rsTurma      = $oDaoTurma->sql_record($sSqlTurma);

            /**
             * Caso retorne algum resultado, buscamos o termo relacionado ao 'rfanterior'
             */
            if ($oDaoTurma->numrows > 0) {

              $iEnsino  = db_utils::fieldsMemory($rsTurma, 0)->ed29_i_ensino;
              $sAno     = db_utils::fieldsMemory($rsTurma, 0)->ed52_i_ano;
              $turmaant = db_utils::fieldsMemory($rsTurma, 0)->ed57_c_descr;

              if ($ed60_c_rfanterior == 'A' || $ed60_c_rfanterior == 'R') {

                $aDadosTermo = DBEducacaoTermo::getTermoEncerramento($iEnsino, $ed60_c_rfanterior, $sAno);
                if (isset($aDadosTermo[0])) {
                  $rfant = $aDadosTermo[0]->sDescricao;
                }
              }
            }
          }

          if($datasaida == "" && $ed60_d_datasaida == ""){
            $datasaida = "&nbsp;";
          }elseif ($datasaida == "") {
            $datasaida = db_formatar($ed60_d_datasaida,'d');
          }
          ?>
          <tr bgcolor="<?=$cor?>" onclick="js_observacoes(<?=$ed60_i_codigo?>,'<?=$ed47_v_nome?>',<?=$ed60_matricula;?>);"
              style="Cursor='hand';" onmouseover="bgColor='#DEB887'" onmouseout="bgColor='<?=$cor?>'">
           <td class="aluno" align="center"><?=$ed47_i_codigo?></td>
           <td class="aluno"><?=$ed47_v_nome?> <?=$ed60_c_parecer == "S"?"&nbsp;&nbsp;&nbsp; <strong>(NEE - Parecer)</strong>":""?></td>
           <td class="aluno" align="center"><?=$ed60_i_numaluno == ""?"&nbsp;":$ed60_i_numaluno?></td>
           <td class="aluno" align="center"><?=Situacao($ed60_c_situacao,$ed60_i_codigo)?></td>
           <td class="aluno" align="center"><?=$turmaant == ""?"&nbsp;":$turmaant?></td>
           <td class="aluno" align="center"><?=$rfant == ""?"&nbsp;":$rfant?></td>
           <td class="aluno" align="center"><?=db_formatar($ed60_d_datamatricula,'d')?></td>
           <td class="aluno" align="center"><?=$datasaida?></td>
           <td class="aluno" align="center"><?=$ed60_matricula?></td>

           <?php
            $sTurnosMatriculados = "";
            if ( !empty($turnoreferente) ) {
              $sTurnosMatriculados = montaStringTurnoMatriculado($turnoreferente);
            }
           ?>

           <td class='aluno' align='center'><?=$sTurnosMatriculados?></td>
           <td class='aluno' align='center'><?=$ed11_c_descr?></td>
          </tr>
         <?
        }
      } else {

        ?>
        <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
         <tr bgcolor="#EAEAEA">
          <td class='aluno'>NENHUM ALUNO MATRICULADO.</td>
         </tr>
        </table>
        <?php
      }
      ?>
     </table>
    </td>
   </tr>
   </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
var oDBFormCache = new DBFormCache('oDBFormCache', 'edu1_alunoturma001.php');
oDBFormCache.setElements(new Array($('trocaTurma')));
oDBFormCache.load();

var oUrl       = js_urlToObject();
if (oUrl["loaded"] == null) {
  location.href = location.href+'&loaded=1&trocaTurma='+$F('trocaTurma');
}
function js_observacoes(iCodigoMatricula, nome, iNumeroMatricula) {

  js_OpenJanelaIframe('','db_iframe_observacoes','edu1_matricula004.php?matricula='+iCodigoMatricula+
                     "&iNumeroMatricula="+iNumeroMatricula,
		              'Observações da Matrícula N° '+iNumeroMatricula+' - '+nome,true
		             );
}
$('trocaTurma').observe("change", function() {

  oDBFormCache.save();
  $('form1').submit();
});
</script>