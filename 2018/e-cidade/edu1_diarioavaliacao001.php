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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("std/DBDate.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$resultedu               = eduparametros(db_getsession("DB_coddepto"));
$ed233_c_avalalternativa = ParamAvalAlternativa(db_getsession("DB_coddepto"));
$db_opcao                = 1;

$clregencia              = new cl_regencia;
$cldiario                = new cl_diario;
$clpareceraval           = new cl_pareceraval;
$cldiarioavaliacao       = new cl_diarioavaliacao;
$cldiarioresultado       = new cl_diarioresultado;
$clperiodoavaliacao      = new cl_periodoavaliacao;
$clconceito              = new cl_conceito;
$clmatricula             = new cl_matricula;
$clprocavaliacao         = new cl_procavaliacao;
$clperiodocalendario     = new cl_periodocalendario;
$clavalcompoeres         = new cl_avalcompoeres;

$codescola               = db_getsession("DB_coddepto");
$escola                  = db_getsession("DB_nomedepto");
$result                  = $clregencia->sql_record($clregencia->sql_query("","*","","ed59_i_codigo = $regencia"));
db_fieldsmemory( $result, 0 );

$oEduParametros = loadConfig('edu_parametros','ed233_i_escola ='.$codescola);

$sql_max    = " SELECT max(ed41_i_sequencia) as seqmax ";
$sql_max   .= "   FROM procavaliacao ";
$sql_max   .= "  WHERE ed41_i_procedimento = {$ed220_i_procedimento} ";
$result_max = db_query($sql_max);
db_fieldsmemory($result_max,0);

$sql_seq    = " SELECT ed41_i_sequencia as seqatual ";
$sql_seq   .= "   FROM procavaliacao ";
$sql_seq   .= "  WHERE ed41_i_codigo = {$ed41_i_codigo} ";
$result_seq = db_query($sql_seq);
db_fieldsmemory($result_seq,0);

$sql_nivel    = " SELECT ed37_c_descr as descr_verif, ed37_c_minimoaprov as min_verif,ed37_c_tipo as tip_verif ";
$sql_nivel   .= "   FROM procavaliacao ";
$sql_nivel   .= "        inner join formaavaliacao on ed37_i_codigo = ed41_i_formaavaliacao ";
$sql_nivel   .= "  WHERE ed41_i_codigo = {$ed41_i_codigo} ";
$result_nivel = db_query($sql_nivel);
db_fieldsmemory($result_nivel,0);

if ($tip_verif == "NIVEL" && $min_verif == "0") {

  $teste  = "<font color='red'><b>ATENÇÃO: Mínimo para aprovação desta forma de avaliação ($descr_verif) não foi ";
  $teste .= "informado (Cadastros -> Tabelas -> Formas de Avaliação).</b></font>";
  echo  $teste;
  $nivel0 = 0;

} else {
  $nivel0 = 1;
}

if (isset($salvartudo)) {

  $sCampos  = " ed60_i_aluno, ed60_i_turma, ed60_i_numaluno, ed60_d_datamatricula, ed60_c_situacao, ed60_c_parecer, ";
  $sCampos .= " ed60_c_ativa, ed47_v_nome, ed47_i_codigo, diario.*, diarioavaliacao.*, procavaliacao.*, formaavaliacao.*, ";
  $sCampos .= " periodoavaliacao.*, amparo.*, abonofalta.*";

  $sql  = " SELECT ".$sCampos ;
  $sql .= "   FROM matricula ";
  $sql .= "        inner join aluno            on ed47_i_codigo          = ed60_i_aluno ";
  $sql .= "        inner join diario           on ed95_i_aluno           = ed47_i_codigo ";
  $sql .= "        inner join matriculaserie   on ed221_i_matricula      = ed60_i_codigo ";
  $sql .= "        inner join regencia         on ed59_i_codigo          = ed95_i_regencia ";
  $sql .= "                                   and ed59_i_serie           = ed221_i_serie ";
  $sql .= "        inner join diarioavaliacao  on ed72_i_diario          = ed95_i_codigo ";
  $sql .= "        inner join procavaliacao    on ed41_i_codigo          = ed72_i_procavaliacao ";
  $sql .= "        inner join formaavaliacao   on ed37_i_codigo          = ed41_i_formaavaliacao ";
  $sql .= "        inner join periodoavaliacao on ed09_i_codigo          = ed41_i_periodoavaliacao ";
  $sql .= "        left  join amparo           on ed81_i_diario          = ed95_i_codigo ";
  $sql .= "        left  join abonofalta       on ed80_i_diarioavaliacao = ed72_i_codigo ";
  $sql .= "  WHERE ed95_i_regencia      = {$regencia} ";
  $sql .= "    AND ed72_i_procavaliacao = {$ed41_i_codigo} ";
  $sql .= "    AND ed60_i_turma         = {$ed59_i_turma} ";
  $sql .= "    AND ed221_c_origem       = 'S' ";

  if ( $iTrocaTurma == 1 ) {
    $sql .= "  AND ed60_c_situacao <> 'TROCA DE TURMA'";
  }

  $sql     .= "  ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa ";
  $result1  = db_query($sql);
  $linhas1  = pg_num_rows($result1);

  for ($x = 0; $x < $linhas1; $x++) {

    db_fieldsmemory($result1,$x);
    if (trim($ed60_c_ativa) == "S") {

      if ($ed60_c_parecer == "S") {
        $ed37_c_tipo = "PARECER";
      }
      $sCampos    = "ed72_i_diario as diario,ed72_i_procavaliacao as avalia,ed41_i_formaavaliacao,ed37_c_minimoaprov";
      $result_min = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("",
                                                                                 $sCampos,
                                                                                 "",
                                                                                 " ed72_i_codigo = $ed72_i_codigo"
                                                                                )
                                                  );
      db_fieldsmemory($result_min,0);
      $faltas = "ed72_i_numfaltasX".$x;
      if ($ed37_c_tipo == "NIVEL") {

        $aprov    = "ed72_c_valorconceitoX".$x;
        $conceito = $$aprov;
        $nota     = "";
        $parecer  = "";
        $nfaltas  = $$faltas;

        if ($conceito != "") {

          $sWhere     = "ed39_i_formaavaliacao = $ed41_i_formaavaliacao AND ed39_c_conceito = '$conceito'";
          $result_dig = $clconceito->sql_record($clconceito->sql_query("",
                                                                       "ed39_i_sequencia as dig",
                                                                       "",
                                                                       $sWhere
                                                                      )
                                               );
          db_fieldsmemory($result_dig,0);
          $sWhere_reg = "ed39_i_formaavaliacao = $ed41_i_formaavaliacao AND ed39_c_conceito = '$ed37_c_minimoaprov'";
          $result_reg = $clconceito->sql_record($clconceito->sql_query("",
                                                                       "ed39_i_sequencia as reg",
                                                                       "",
                                                                       $sWhere_reg
                                                                      )
                                               );
          if ($clconceito->numrows > 0) {
            db_fieldsmemory($result_reg,0);
          }
          if (@$dig >= @$reg) {
            $minimo = "S";
          } else {
            $minimo = "N";
          }
        } else {
          $minimo = "N";
        }

      } else if ($ed37_c_tipo == "NOTA") {

        $aprov    = "ed72_i_valornotaX".$x;
        $nota     = @$$aprov;
        $conceito = "";
        $parecer  = "";
        $nfaltas  = @$$faltas;

        if ($nota != "") {

          if ($nota >= $ed37_c_minimoaprov) {
            $minimo = "S";
          } else {
            $minimo = "N";
          }
        } else {
          $minimo = "N";
        }
      } else if ($ed37_c_tipo == "PARECER") {

        $nota     = "";
        $conceito = "";
        $nfaltas  = $$faltas;
        $minimo   = "S";

      }
      $nomediario = "ed72_i_diarioX".$x;
      $coddiario  = $$nomediario;
      $amparo     = "ed72_c_amparoX".$x;
      $amparo     = $$amparo;

      db_inicio_transacao();
      $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
      $cldiarioavaliacao->ed72_i_procavaliacao = $ed41_i_codigo;
      $cldiarioavaliacao->ed72_i_numfaltas     = $nfaltas;
      $cldiarioavaliacao->ed72_i_valornota     = $nota;
      $cldiarioavaliacao->ed72_t_parecer       = pg_escape_string($ed72_t_parecer);
      $cldiarioavaliacao->ed72_c_valorconceito = $conceito;
      $cldiarioavaliacao->ed72_c_aprovmin      = $minimo;
      $cldiarioavaliacao->alterar($ed72_i_codigo);

      if ( $conceito != '' || $nota != '' || $ed72_t_parecer != '') {


        if ( $ed41_i_procresultvinc != '') {

          $sWhereRecuperacao  = " (select ed73_i_codigo ";
          $sWhereRecuperacao .= "    from  diarioresultado " ;
          $sWhereRecuperacao .= "          inner join  diario on ed73_i_diario = ed95_i_codigo" ;
          $sWhereRecuperacao .= "    where ed73_i_procresultado = {$ed41_i_procresultvinc}";
          $sWhereRecuperacao .= "      and ed95_i_aluno         = {$ed95_i_aluno}";
          $sWhereRecuperacao .= "      and ed73_i_diario        = {$ed95_i_codigo}";
          $sWhereRecuperacao .= ")";

          $oDaoResultadoRecuperacao = new cl_diarioresultadorecuperacao();
          $oDaoResultadoRecuperacao->excluir(null, "ed116_diarioresultado in $sWhereRecuperacao");
        }
      }
       db_fim_transacao();

      if ($cldiarioavaliacao->erro_status == "0") {
        $cldiarioavaliacao->erro(true,false);
      }
    }
  }
  $dataatualiz    = date("Y-m-d");
  $sql            = " UPDATE regencia SET ";
  $sql           .= "        ed59_d_dataatualiz = '{$dataatualiz}' ";
  $sql           .= "  WHERE ed59_i_codigo = {$regencia} ";
  $result         = db_query($sql);
  $sWhere_result3 = "ed73_i_diario = $diario AND ed43_i_sequencia > $seqatual";
  $result3        = $cldiarioresultado->sql_record($cldiarioresultado->sql_query("",
                                                                                 "ed73_i_procresultado as resultaval",
                                                                                 "ed43_i_sequencia",
                                                                                  $sWhere_result3
                                                                                )
                                                  );
  for ($y = 0; $y < $cldiarioresultado->numrows; $y++) {

    db_fieldsmemory($result3,$y);
    ?>
    <script>
      parent.iframe_R<?=$resultaval?>.location.href = "edu1_diarioresultado001.php?regencia=<?=$regencia?>"+
    	                                              "&ed43_i_codigo=<?=$resultaval?>";
    </script>
   <?
  }
 ?>
  <script>
   parent.iframe_RF.location.href = "edu1_diariofinal001.php?regencia=<?=$regencia?>";
  </script>
  <?
  db_msgbox("Alteração efetuada com sucesso!");
}

$oTurma  = new Turma($ed59_i_turma);
$iAno    = $oTurma->getCalendario()->getAnoExecucao();
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 9;
}
</style>
</head>
<body bgcolor="#cccccc" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0">
<form name="form1" method="post" action="">
<?
  $sWhere  = "WHERE ed95_i_regencia      = {$regencia}";
  $sWhere .= "  AND ed72_i_procavaliacao = {$ed41_i_codigo}";
  $sWhere .= "  AND ed60_i_turma         = {$ed59_i_turma}";
  $sWhere .= "  AND ed221_c_origem       = 'S'";

  if ( $iTrocaTurma == 1 ) {
    $sWhere .= "  AND ed60_c_situacao <> 'TROCA DE TURMA'";
  }
  $sql  = "SELECT ed60_i_aluno, ed60_i_codigo, ed60_matricula, ed60_i_turma, ed60_i_numaluno, ed60_c_situacao";
  $sql .= "       , ed60_c_parecer, ed60_c_ativa, ed60_d_datamatricula, ed47_v_nome, ed47_i_codigo, diario.*";
  $sql .= "       , diarioavaliacao.*, procavaliacao.*, formaavaliacao.*, periodoavaliacao.*, amparo.*, convencaoamp.*";
  $sql .= "       , abonofalta.* ";
  $sql .= "       , case when ed72_c_tipo = 'M' ";
  $sql .= "              then escolaorigem.ed18_i_codigo ";
  $sql .= "              else escolaproc.ed82_i_codigo  ";
  $sql .= "          end as ed72_i_escola ";
  $sql .= "       , case when ed72_c_tipo = 'M' ";
  $sql .= "              then escolaorigem.ed18_c_nome ";
  $sql .= "              else escolaproc.ed82_c_nome  ";
  $sql .= "          end as nomeescola ";
  $sql .= "       , case when ed72_c_tipo = 'M' ";
  $sql .= "              then censomunic.ed261_c_nome ";
  $sql .= "              else bb.ed261_c_nome  ";
  $sql .= "          end as ed72_c_cidade ";
  $sql .= "       , case when ed72_c_tipo = 'M' ";
  $sql .= "              then censouf.ed260_c_sigla  ";
  $sql .= "              else aa.ed260_c_sigla  ";
  $sql .= "          end as ed72_c_estado ";
  $sql .= "       , case when ed72_c_tipo = 'M' ";
  $sql .= "              then 'ESCOLA DA REDE'  ";
  $sql .= "              else 'FORA DA REDE'  ";
  $sql .= "          end as ed72_c_tipoescola ";
  $sql .= "       , to_char(matricula.ed60_d_datasaida,'DD/MM/YYYY') as datasaida ";
  $sql .= "  FROM matricula ";
  $sql .= "       inner join aluno                  on ed47_i_codigo              = ed60_i_aluno ";
  $sql .= "       inner join diario                 on ed95_i_aluno               = ed47_i_codigo ";
  $sql .= "       inner join matriculaserie         on ed221_i_matricula          = ed60_i_codigo ";
  $sql .= "       inner join regencia               on ed59_i_codigo              = ed95_i_regencia ";
  $sql .= "                                        and ed59_i_serie               = ed221_i_serie ";
  $sql .= "       inner join diarioavaliacao        on ed72_i_diario              = ed95_i_codigo ";
  $sql .= "       inner join procavaliacao          on ed41_i_codigo              = ed72_i_procavaliacao ";
  $sql .= "       inner join formaavaliacao         on ed37_i_codigo              = ed41_i_formaavaliacao ";
  $sql .= "       inner join periodoavaliacao       on ed09_i_codigo              = ed41_i_periodoavaliacao ";
  $sql .= "       left  join amparo                 on ed81_i_diario              = ed95_i_codigo ";
  $sql .= "       left  join convencaoamp           on ed250_i_codigo             = ed81_i_convencaoamp ";
  $sql .= "       left  join abonofalta             on ed80_i_diarioavaliacao     = ed72_i_codigo ";
  $sql .= "       left  join escola as escolaorigem on escolaorigem.ed18_i_codigo = ed72_i_escola ";
  $sql .= "       left  join censouf                on censouf.ed260_i_codigo     = escolaorigem.ed18_i_censouf ";
  $sql .= "       left  join censomunic             on censomunic.ed261_i_codigo  = escolaorigem.ed18_i_censomunic ";
  $sql .= "       left  join escolaproc             on escolaproc.ed82_i_codigo   = ed72_i_escola ";
  $sql .= "       left  join censouf  as aa         on aa.ed260_i_codigo          = escolaproc.ed82_i_censouf ";
  $sql .= "       left  join censomunic as bb       on bb.ed261_i_codigo          = escolaproc.ed82_i_censomunic ";
  $sql .= " {$sWhere} ";
  $sql .= " ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome),ed60_c_ativa";

$result1       = db_query($sql);
$linhas1       = pg_num_rows($result1);
$descr_periodo = pg_result($result1,0,'ed09_c_descr');
?>
<table border='0px' width="98%" bgcolor="#cccccc" style="" cellspacing="0px">
 <tr>
  <td class='titulo'>
   &nbsp;<?=$ed232_c_descr?> - <?=$descr_periodo?> | Turma <?=$ed57_c_descr?> - <?=$ed11_c_descr?> - Calendário <?=$ed52_c_descr?>
  </td>
  <td align="right" class='titulo'>
   <?if(trim($ed59_c_freqglob)!='A'){?>
    <input name="abono" value="Abonar Faltas" type="button"
           onclick="js_abono(<?=$ed41_i_codigo?>,<?=$regencia?>,'<?=$descr_periodo?>');"
           style="height:14px;border:1px outset #f3f3f3;font-size:11px;padding:0px;">
   <?}?>
  </td>
 </tr>
 <tr>
  <td colspan="2">
   <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
    <tr>
     <td colspan="6" align="center" class='cabec1'>Alunos</td>
     <td class='cabec1'>&nbsp;</td>
     <td colspan="<?=trim($ed59_c_freqglob)!='F'?'3':'2'?>" align="center"
         class='cabec1'><?=pg_result($result1,0,'ed09_c_descr')?>
     </td>
    </tr>
    <tr align="center">
     <td class="cabec1">N°</td>
     <td class="cabec1">Nome</td>
     <td class="cabec1">Situação</td>
     <td class="cabec1">Dt. Matrícula</td>
     <td class="cabec1">Dt. Saída</td>
     <td class="cabec1">Código</td>
     <td class="cabec1" width="5%">Origem</td>
     <?if (trim($ed59_c_freqglob) != "F") {?>
         <td class="cabec1"><?=pg_result($result1,0,'ed37_c_tipo')?></td>
     <?}?>
     <?if (trim($ed59_c_freqglob) != "A") {?>
         <td class="cabec1">Faltas</td>
     <?}?>
     <td class="cabec1">&nbsp;</td>
    </tr>
    <?
    if ( $linhas1 > 0 ) {

      $cor1    = "#f3f3f3";
      $cor2    = "#DBDBDB";
      $cor     = "";
      $somador = "";

      /* Define tabIndex */
      $iTabIndex = 0;

      for ( $x = 0; $x < $linhas1; $x++ ) {

        db_fieldsmemory($result1,$x);

        if ( $ed60_c_parecer == "S" ) {

         $ed37_c_tipo_ant = $ed37_c_tipo;
         $ed37_c_tipo     = "PARECER";
         $up_diario       = db_query("UPDATE diarioavaliacao SET ed72_i_valornota = null,ed72_c_valorconceito = '' WHERE ed72_i_codigo = {$ed72_i_codigo}");
        }

        if ( $cor == $cor1 ) {
          $cor = $cor2;
        } else {
          $cor = $cor1;
        }

        if ( trim( $ed60_c_situacao ) != "MATRICULADO" || trim( $ed72_c_amparo ) == "S" ) {

          if (    ( trim( $ed60_c_situacao ) == "TRANSFERIDO FORA" || trim( $ed60_c_situacao ) == "CANCELADO" || trim( $ed60_c_situacao ) == "EVADIDO" )
               && $ed60_c_ativa == "S" && $ed95_c_encerrado == "N" ) {

            $disabled         = "readonly";
            $cordisabled      = "#FFD5AA";
            $ed95_c_encerrado = "N";
          } else if ( trim( $ed60_c_situacao ) == "TRANSFERIDO FORA" && $ed60_c_ativa == "N" ) {

            $disabled             = "readonly";
            $cordisabled          = "#FFD5AA";
            $ed72_i_valornota     = "";
            $ed72_c_valorconceito = "";
            $ed72_t_parecer       = "";
            $ed72_i_numfaltas     = "";
          } else {

           $disabled    = "readonly";
           $cordisabled = "#FFD5AA";
          }
        } else {

         $disabled    = "";
         $cordisabled = "#FFFFFF";
         $somador++;
        }

        if ( ( $ed72_i_escola != $codescola && $ed72_c_tipo == "M" ) || ( $ed72_c_tipo == "F" ) ) {

          $disabled    = "readonly";
          $cordisabled = "#FFD5AA";
        }
      ?>
       <tr bgcolor="<?=$cor?>" >
        <td align="right" class='aluno'><?=$ed60_i_numaluno?></td>
        <td class='aluno'>
         <a class="aluno" href="javascript:js_movimentos(<?=$ed60_i_codigo?>)"><?=$ed47_v_nome?></a>
         <?=$ed60_c_parecer=="S"?"<b>&nbsp;&nbsp;&nbsp;(NEE - Parecer)</b>":""?>
         <input type="hidden" value="<?=$ed72_i_diario?>" name="ed72_i_diarioX<?=$x?>">
         <input type="hidden" value="<?=$ed72_c_amparo?>" name="ed72_c_amparoX<?=$x?>">
        </td>
        <td align="center" class='aluno'>
         <?
         if ( trim( $ed81_c_todoperiodo ) == "S" && $ed60_c_ativa == "S" ) {

           if ( $ed81_i_justificativa != "" ) {
             echo "AMPARADO";
           } else {
             echo "$ed250_c_abrev";
           }
         } else {
           echo Situacao( $ed60_c_situacao, $ed60_i_codigo );
         }
         $corlink = $ed72_i_escola!=$codescola||$ed72_c_tipo=="F"?"#FF0000":"#000000";
         ?>
        </td>
        <td align="center" class='aluno'><?=db_formatar($ed60_d_datamatricula,'d')?></td>
        <td align="center" class='aluno'><?=$datasaida==""?"&nbsp;":$datasaida?></td>
        <td align="right" class='aluno'><b><?=$ed47_i_codigo?></b></td>
        <td align="center" <?=$ed72_i_escola!=$codescola||$ed72_c_tipo=="F"?"onmouseover=\"js_mostra('tiponota$x')\" onmouseout=\"js_oculta('tiponota$x')\" ":""?>>
         <?
         if ( trim( $ed60_c_situacao ) != "MATRICULADO" || trim( $ed72_c_amparo ) == "S" || $ed81_c_todoperiodo == "S" ) {

          if (    ( trim( $ed60_c_situacao ) == "TRANSFERIDO FORA" || trim( $ed60_c_situacao ) == "CANCELADO" || trim( $ed60_c_situacao ) == "EVADIDO")
               && $ed60_c_ativa == "S" ) {

            if ( trim( $ed37_c_tipo ) == "NOTA" ) {
              $aprovperiodo = $ed72_i_valornota;
            } else if ( trim( $ed37_c_tipo ) == "NIVEL" ) {
              $aprovperiodo = $ed72_c_valorconceito;
            } else {
              $aprovperiodo = $ed72_t_parecer;
            }
           ?>
           <a title="Alterar Origem" href="javascript:js_tiponota('<?=$ed60_d_datamatricula?>',<?=$ed72_i_codigo?>,<?=$regencia?>,<?=$ed41_i_codigo?>,'<?=$ed09_c_descr?>','<?=$aprovperiodo?>')" style="color:<?=$corlink?>;font-weight:bold;"><?=$ed72_i_escola!=$codescola||$ed72_c_tipo=="F"?"NE":"NI"?></a>
           <table id="tiponota<?=$x?>" style="position:absolute;border:2px solid #444444;visibility:hidden;" cellspacing="1" cellpadding="5" bgcolor="#FF0000">
            <tr>
             <td bgcolor="#FFCC00" style="border:1px solid #444444">
              <b>Escola:</b> <?=$ed72_i_escola." - ".$nomeescola?><br>
              <b>Cidade:</b> <?=$ed72_c_cidade." - ".$ed72_c_estado?><br>
              <b>Tipo:</b> <?=$ed72_c_tipoescola?>
             </td>
            </tr>
           </table>
          <?
           } else {
             echo "&nbsp";
           }
         } else {

           if ( trim( $ed37_c_tipo ) == "NOTA" ) {
             $aprovperiodo = $ed72_i_valornota;
           } else if ( trim( $ed37_c_tipo ) == "NIVEL" ) {
             $aprovperiodo = $ed72_c_valorconceito;
           } else {
             $aprovperiodo = $ed72_t_parecer;
           }
          ?>
          <a title="Alterar Origem" href="javascript:js_tiponota('<?=$ed60_d_datamatricula?>',<?=$ed72_i_codigo?>,<?=$regencia?>,<?=$ed41_i_codigo?>,'<?=$ed09_c_descr?>','<?=$aprovperiodo?>')" style="color:<?=$corlink?>;font-weight:bold;"><?=$ed72_i_escola!=$codescola||$ed72_c_tipo=="F"?"NE":"NI"?></a>
          <table id="tiponota<?=$x?>" style="position:absolute;border:2px solid #444444;visibility:hidden;" cellspacing="1" cellpadding="5" bgcolor="#FF0000">
           <tr>
            <td bgcolor="#FFCC00" style="border:1px solid #444444">
             <b>Escola:</b> <?=$ed72_i_escola." - ".$nomeescola?><br>
             <b>Cidade:</b> <?=$ed72_c_cidade." - ".$ed72_c_estado?><br>
             <b>Tipo:</b> <?=$ed72_c_tipoescola?>
            </td>
           </tr>
          </table>
         <?}?>
        </td>
        <?
        $vinculo = false;
        $semfoco = false;

        if ( trim( $ed59_c_freqglob ) != "F" ) {

         if ( trim( $ed72_c_amparo ) == "S" ) {

           $semfoco = true;
           if ( $ed60_c_ativa == "S" ) {

             if ( $ed81_i_justificativa != "" ) {
               ?><td align="center" class='aluno'>Amparo - Justif. n° <?=trim($ed81_i_justificativa)?></td><?
             } else if ( $ed81_i_convencaoamp != "" ) {
              ?><td align="center" class='aluno'><?=$ed250_c_abrev?></td><?
             }
           } else {
            ?><td align="center" class='aluno'>&nbsp;</td><?
           }
         } else {

          if ( $ed41_i_procavalvinc != 0 ) {

           $result_aval = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed09_c_abrev,ed72_c_aprovmin as aprovminaval,ed72_i_valornota as v_nota,ed72_c_valorconceito as v_conc,ed72_t_parecer as v_par",""," ed72_i_procavaliacao = $ed41_i_procavalvinc AND ed72_i_diario = $ed72_i_diario AND ed95_i_regencia = $regencia"));
           db_fieldsmemory($result_aval,0);

           if ( $v_nota == "" && $v_conc == "" && $v_par == "" ) {

             $semfoco = true;
             if ( trim( $ed60_c_situacao ) != "MATRICULADO" ) {

               ?>
               <td class='aluno' align="center">
                <table class='aluno' cellspacing="0" cellpading="0">
                 <tr>
                  <td width="50">
                   <input name="nulo" value="" type="text" size="6" maxlength="6" style="background:<?=$cordisabled?>;width:45px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;" <?=$disabled?>>
                  </td>
                  <td width="60">
                  </td>
                 </tr>
                </table>
               </td><?
             } else {
              ?><td class='aluno' align="center"><?=$ed09_c_abrev?> ainda não foi concluído.</td><?
             }
             $vinculo = true;

             db_inicio_transacao();
             $cldiarioavaliacao->ed72_t_obs           = "";
             $cldiarioavaliacao->ed72_c_valorconceito = "";
             $cldiarioavaliacao->ed72_i_valornota     = "";
             $cldiarioavaliacao->ed72_t_parecer       = "";
             $cldiarioavaliacao->ed72_i_numfaltas     = "";
             $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
             $cldiarioavaliacao->alterar($ed72_i_codigo);
             db_fim_transacao();
           } else {

             if ( $aprovminaval == "S" ) {

               $semfoco = true;
               if ( trim( $ed60_c_situacao ) != "MATRICULADO" ) {

                ?>
                <td class='aluno' align="center">
                <table class='aluno' cellspacing="0" cellpading="0">
                 <tr>
                  <td width="50">
                   <input name="nulo" value="" type="text" size="6" maxlength="6" style="background:<?=$cordisabled?>;width:45px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;" <?=$disabled?>>
                  </td>
                  <td width="60">
                  </td>
                 </tr>
                </table>
                </td><?
               } else {
                ?><td class='aluno' align="center">Aluno atingiu mínimo para aprovação em <?=$ed09_c_abrev?></td><?
               }

               $vinculo = true;
               db_inicio_transacao();
               $cldiarioavaliacao->ed72_t_obs           = "";
               $cldiarioavaliacao->ed72_c_valorconceito = "";
               $cldiarioavaliacao->ed72_i_valornota     = "";
               $cldiarioavaliacao->ed72_t_parecer       = "";
               $cldiarioavaliacao->ed72_i_numfaltas     = "";
               $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
               $cldiarioavaliacao->alterar($ed72_i_codigo);
               db_fim_transacao();
             }
           }
          }

          if ( $ed41_i_procresultvinc != 0 ) {

            $sWhereResultado = " ed73_i_procresultado = {$ed41_i_procresultvinc} ";

            $sSqlResultadoVinculado = $cldiarioresultado->sql_query("",
                                                                    "ed42_c_abrev,
                                                                    ed73_c_aprovmin as aprovminres,
                                                                    ed73_i_valornota as v_nota,
                                                                    ed73_c_valorconceito as v_conc,
                                                                    ed73_t_parecer as v_par",
                                                                    "",
                                                                    $sWhereResultado." AND ed73_i_diario = {$ed72_i_diario}"
            );
            $result_res = $cldiarioresultado->sql_record($sSqlResultadoVinculado);

            db_fieldsmemory($result_res,0);
            $iTotalReprovacoes = 0;
            $sWhereResultado .= " and ed95_i_aluno = {$ed95_i_aluno} and ed73_c_aprovmin = 'N'";
            $sWhereResultado .= " and ed95_i_serie = {$ed95_i_serie} and ed95_c_encerrado = 'N'";

            if ( trim($ed37_c_tipo) == 'NOTA' ) {
              $sWhereResultado .= " and ed73_i_valornota is not null ";
            } else if ( trim($ed37_c_tipo) == 'NIVEL' ) {
              $sWhereResultado .= " and ed73_c_valorconceito is not null ";
            }else {
              $sWhereResultado .= " and ed73_t_parecer is not null ";
            }

            $sCampos = "diarioresultado.*";
            $sSqlOutrasDisciplinas = $cldiarioresultado->sql_query_resultado_outras_disciplina_aluno(null,
                                                                                                     $sCampos,
                                                                                                     null,
                                                                                                     $sWhereResultado
            );

            $rsOutrasDisciplinas = $cldiarioresultado->sql_record($sSqlOutrasDisciplinas);
            $iTotalReprovacoes   = $cldiarioresultado->numrows;

            $aOutrasDisciplinasNaRecuperacao  = array();
            $sSqlOutrasDisciplinasRecuperacao = $cldiarioavaliacao->sql_query_diario(null,
                                                                                  "diarioavaliacao.*",
                                                                                   null,
                                                                                  "ed72_i_procavaliacao = {$ed72_i_procavaliacao}
                                                                                   and ed95_i_aluno = {$ed95_i_aluno}"
                                                                                   );
            $rsOutrasDisciplinasNaRecuperacao = $cldiarioavaliacao->sql_record($sSqlOutrasDisciplinasRecuperacao);
            $aOutrasDisciplinasNaRecuperacao  = db_utils::getCollectionByRecord($rsOutrasDisciplinasNaRecuperacao);

            if ( $v_nota == "" && $v_conc == "" && (trim( $ed37_c_tipo ) == "PARECER"  && ($aprovminres == 'S'))) {

              $semfoco = true;

              if ( trim( $ed60_c_situacao ) != "MATRICULADO" ) {

                ?>
                <td class='aluno' align="center">
                <table class='aluno' cellspacing="0" cellpading="0">
                 <tr>
                  <td width="50">
                   <input name="nulo" value="" type="text" size="6" maxlength="6" style="background:<?=$cordisabled?>;width:45px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;" <?=$disabled?>>
                  </td>
                  <td width="60">
                  </td>
                 </tr>
                </table>
                </td><?
              } else {
                ?><td class='aluno' align="center"><?=$ed42_c_abrev?> ainda não foi concluído</td><?
              }

              $vinculo = true;
              db_inicio_transacao();
              $cldiarioavaliacao->ed72_t_obs           = "";
              $cldiarioavaliacao->ed72_c_valorconceito = "";
              $cldiarioavaliacao->ed72_i_valornota     = "";
              $cldiarioavaliacao->ed72_t_parecer       = "";
              $cldiarioavaliacao->ed72_c_aprovmin      = "N";
              $cldiarioavaliacao->ed72_i_numfaltas     = "";
              $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
              $cldiarioavaliacao->alterar($ed72_i_codigo);
              db_fim_transacao();
            } else {

              if (empty($ed41_numerodisciplinasrecuperacao)) {
                $ed41_numerodisciplinasrecuperacao = 999;
              }
              $sMensagem = "Aluno atingiu mínimo para aprovação em {$ed42_c_abrev}.";

              if (    $iTotalReprovacoes > 0
                   && $iTotalReprovacoes > $ed41_numerodisciplinasrecuperacao) {

                $sMensagem   = "Aluno possui reprovação em {$iTotalReprovacoes} Disciplinas. Limite {$ed41_numerodisciplinasrecuperacao}.";
                $aprovminres = 'S';
                db_inicio_transacao();
                foreach ($aOutrasDisciplinasNaRecuperacao as $oDisciplina) {

                  $cldiarioavaliacao->ed72_t_obs           = "";
                  $cldiarioavaliacao->ed72_c_valorconceito = "";
                  $cldiarioavaliacao->ed72_i_valornota     = "";
                  $cldiarioavaliacao->ed72_t_parecer       = "";
                  $cldiarioavaliacao->ed72_c_aprovmin      = "N";
                  $cldiarioavaliacao->ed72_i_numfaltas     = "";
                  $cldiarioavaliacao->ed72_i_codigo        = $oDisciplina->ed72_i_codigo;
                  $cldiarioavaliacao->alterar($oDisciplina->ed72_i_codigo);

                }

                db_fim_transacao();
                if (!empty($ed41_i_procresultvinc)) {

                  $sWhereRecuperacao  = " (select ed73_i_codigo ";
                  $sWhereRecuperacao .= "    from  diarioresultado " ;
                  $sWhereRecuperacao .= "          inner join  diario on ed73_i_diario = ed95_i_codigo" ;
                  $sWhereRecuperacao .= "    where ed73_i_procresultado = {$ed41_i_procresultvinc}";
                  $sWhereRecuperacao .= "      and ed95_i_aluno         = {$ed95_i_aluno}";
                  $sWhereRecuperacao .= ")";

                  $oDaoResultadoRecuperacao = new cl_diarioresultadorecuperacao();
                  $oDaoResultadoRecuperacao->excluir(null, "ed116_diarioresultado in $sWhereRecuperacao");
                }
              }
              if ( $aprovminres == "S" ) {

                $semfoco = true;
                if ( trim( $ed60_c_situacao ) != "MATRICULADO" ) {
                  ?><td class='aluno' align="center"><input name="nulo" value="" type="text" size="6" maxlength="6" style="background:<?=$cordisabled?>;width:45px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;" <?=$disabled?>></td><?
                } else {
                  ?><td class='aluno' align="center"><?=$sMensagem?></td><?
                }

                $vinculo = true;
                db_inicio_transacao();
                $cldiarioavaliacao->ed72_t_obs           = "";
                $cldiarioavaliacao->ed72_c_valorconceito = "";
                $cldiarioavaliacao->ed72_i_valornota     = "";
                $cldiarioavaliacao->ed72_t_parecer       = "";
                $cldiarioavaliacao->ed72_i_numfaltas     = "";
                $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
                $cldiarioavaliacao->alterar($ed72_i_codigo);
                db_fim_transacao();
              }
            }
          }

          $campo       = trim($ed37_c_tipo)=="NOTA"?"ed73_i_valornota":trim($ed37_c_tipo)=="NIVEL"?"ed73_c_valorconceito":"ed73_t_parecer";
          $result_ante = $cldiarioresultado->sql_record($cldiarioresultado->sql_query("","ed42_c_abrev,ed73_c_aprovmin as apvmin,$campo as tipovalor",""," ed43_i_sequencia < $ed41_i_sequencia AND ed73_i_diario = $ed72_i_diario"));

          if ( $cldiarioresultado->numrows > 0 && $ed41_i_procresultvinc == 0 && $ed41_i_procavalvinc == 0 ) {

            $semfoco = true;
            db_fieldsmemory($result_ante,0);

            if ( $apvmin == "S" && ( $seqatual >= $seqmax ) ) {

              if ( trim( $ed60_c_situacao ) != "MATRICULADO" ) {
                ?><td class='aluno' align="center"><input name="nulo" value="" type="text" size="6" maxlength="6" style="background:<?=$cordisabled?>;width:45px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;" <?=$disabled?>></td><?
              } else {
                ?><td class='aluno' align="center">Aluno atingiu mínimo para aprovação em  <?=$ed42_c_abrev?>.</td><?
              }

              $vinculo = true;
              db_inicio_transacao();
              $cldiarioavaliacao->ed72_t_obs           = "";
              $cldiarioavaliacao->ed72_c_valorconceito = "";
              $cldiarioavaliacao->ed72_i_valornota     = "";
              $cldiarioavaliacao->ed72_t_parecer       = "";
              $cldiarioavaliacao->ed72_i_numfaltas     = "";
              $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
              $cldiarioavaliacao->alterar($ed72_i_codigo);
              db_fim_transacao();
            } else if ( $apvmin == "N" && $tipovalor == "" && ( $seqatual >= $seqmax ) ) {

              if ( trim( $ed60_c_situacao ) != "MATRICULADO" ) {
                ?><td class='aluno' align="center"><input name="nulo" value="" type="text" size="6" maxlength="6" style="background:<?=$cordisabled?>;width:45px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;" <?=$disabled?>></td><?
              } else {
                ?><td class='aluno' align="center"><?=$ed42_c_abrev?> ainda não foi concluído.</td><?
              }

              db_inicio_transacao();
              $cldiarioavaliacao->ed72_t_obs           = "";
              $cldiarioavaliacao->ed72_c_valorconceito = "";
              $cldiarioavaliacao->ed72_i_valornota     = "";
              $cldiarioavaliacao->ed72_t_parecer       = "";
              $cldiarioavaliacao->ed72_i_numfaltas     = "";
              $cldiarioavaliacao->ed72_i_codigo        = $ed72_i_codigo;
              $cldiarioavaliacao->alterar($ed72_i_codigo);
              db_fim_transacao();
              $vinculo = true;
            }
          }

          if ( $vinculo == false ) {

           $sql_cpr     = "SELECT ed44_i_procresultado as codprocres,ed43_c_obtencao as frmobtencao ";
           $sql_cpr    .= "  FROM avalcompoeres ";
           $sql_cpr    .= "       inner join procresultado on ed43_i_codigo = ed44_i_procresultado ";
           $sql_cpr    .= " WHERE ed44_i_procavaliacao = {$ed41_i_codigo}";
           $result_cpr  = db_query($sql_cpr);

           if ( pg_num_rows( $result_cpr ) > 0 ) {

             db_fieldsmemory($result_cpr,0);
             if ( $ed233_c_avalalternativa == "S" && $frmobtencao == "SO" ) {

               $sql_alter     = "select array(SELECT ed41_i_codigo ";
               $sql_alter    .= "  FROM diarioavaliacao ";
               $sql_alter    .= "       inner join diario            on ed95_i_codigo           = ed72_i_diario ";
               $sql_alter    .= "       inner join procavaliacao     on ed41_i_codigo           = ed72_i_procavaliacao ";
               $sql_alter    .= "       inner join periodoavaliacao  on ed09_i_codigo           = ed41_i_periodoavaliacao ";
               $sql_alter    .= "       inner join periodocalendario on ed53_i_periodoavaliacao = ed09_i_codigo ";
               $sql_alter    .= "       inner join avalcompoeres     on ed44_i_procavaliacao    = ed41_i_codigo ";
               $sql_alter    .= " WHERE ed53_i_calendario    = {$ed57_i_calendario} ";
               $sql_alter    .= "   AND ed53_d_fim           < '{$ed60_d_datamatricula}' ";
               $sql_alter    .= "   AND ed41_i_procedimento  = {$ed220_i_procedimento} ";
               $sql_alter    .= "   AND ed95_i_aluno         = {$ed60_i_aluno} ";
               $sql_alter    .= "   AND ed95_i_regencia      = {$regencia} ";
               $sql_alter    .= "   AND ed72_i_valornota     is null ";
               $sql_alter    .= "   AND ed44_i_procresultado = {$codprocres}) as codavalproc";
               $result_alter  = db_query($sql_alter);

               if ( pg_num_rows( $result_alter ) > 0 ) {

                 db_fieldsmemory($result_alter,0);
                 $codavalproc = str_replace("{","",$codavalproc);
                 $codavalproc = str_replace("}","",$codavalproc);

                 if ( $codavalproc != "" ) {

                   $sql_alter1     = "SELECT count(*),ed282_i_procavalalternativa ";
                   $sql_alter1    .= "  FROM procavalalternativaregra ";
                   $sql_alter1    .= "       inner join procavalalternativa on ed281_i_codigo = ed282_i_procavalalternativa ";
                   $sql_alter1    .= "       inner join procavaliacao       on ed41_i_codigo  = ed282_i_codavaliacao ";
                   $sql_alter1    .= "       left  join formaavaliacao      on ed37_i_codigo  = ed282_i_formaavaliacao ";
                   $sql_alter1    .= " WHERE ed281_i_procresultado = {$codprocres} ";
                   $sql_alter1    .= "   AND ed282_i_tipoaval = 'A' ";
                   $sql_alter1    .= "   AND ((ed282_i_codavaliacao in ({$codavalproc}) and ed37_i_menorvalor is null) ";
                   $sql_alter1    .= "         OR ";
                   $sql_alter1    .= "        (ed282_i_codavaliacao not in ({$codavalproc}) and ed37_i_menorvalor is not null)) ";
                   $sql_alter1    .= "   AND exists(select * from procavalalternativaregra as b ";
                   $sql_alter1    .= "              where b.ed282_i_codavaliacao in ({$codavalproc})) ";
                   $sql_alter1    .= " GROUP BY ed282_i_procavalalternativa ";
                   $sql_alter1    .= " ORDER BY count desc ";
                   $sql_alter1    .= " LIMIT 1";
                   $result_alter1  = db_query( $sql_alter1 );

                   if ( pg_num_rows( $result_alter1 ) > 0 ) {

                     db_fieldsmemory( $result_alter1, 0 );
                     $sql_alter2     = "SELECT coalesce(ed37_i_menorvalor,0) as ed37_i_menorvalor, coalesce(ed37_i_maiorvalor,0) as ed37_i_maiorvalor,coalesce(ed37_i_variacao,0) as ed37_i_variacao ";
                     $sql_alter2    .= "  FROM procavalalternativaregra ";
                     $sql_alter2    .= "       inner join procavalalternativa on ed281_i_codigo = ed282_i_procavalalternativa ";
                     $sql_alter2    .= "       inner join procavaliacao       on ed41_i_codigo  = ed282_i_codavaliacao ";
                     $sql_alter2    .= "       left  join formaavaliacao      on ed37_i_codigo  = ed282_i_formaavaliacao ";
                     $sql_alter2    .= " WHERE ed281_i_procresultado = {$codprocres} ";
                     $sql_alter2    .= "   AND ed282_i_tipoaval = 'A' ";
                     $sql_alter2    .= "   AND ed282_i_codavaliacao = {$ed41_i_codigo} ";
                     $sql_alter2    .= "   AND ed282_i_procavalalternativa = {$ed282_i_procavalalternativa}";
                     $result_alter2  = db_query( $sql_alter2 );

                     if ( pg_result( $result_alter2, 0, 1) > 0 ) {
                       db_fieldsmemory( $result_alter2, 0);
                     }
                   }
                 }
               }
             }
           }

           $sSqlParecerAval = $clpareceraval->sql_query( "", "ed93_i_codigo", "", "ed93_i_diarioavaliacao = {$ed72_i_codigo}");
           $resultparecer   = $clpareceraval->sql_record( $sSqlParecerAval );

           if ( trim( $ed37_c_tipo ) == "NIVEL" ) {

             $sSqlConceito = $clconceito->sql_query( "", "ed39_c_conceito", "ed39_i_sequencia", "ed39_i_formaavaliacao = {$ed41_i_formaavaliacao}");
             $result3      = $clconceito->sql_record( $sSqlConceito );
            ?>
            <td class='aluno' align="center">
             <table class='aluno' cellspacing="0" cellpading="0">
              <tr>
               <td width="50">
                <select name="ed72_c_valorconceitoX<?=$x?>" style="background:<?=$cordisabled?>;width:50px;height:17px;font-size:10px;text-align:center;padding:0px;" <?=trim($ed95_c_encerrado)=="S"?"onclick=\"alert('Aluno já possui avaliações encerradas para esta disciplina!')\"":""?> <?=trim($ed95_c_encerrado)=="S"?"readonly":$disabled?> >
                 <option value=""></option>
                 <?php
                   for ( $z = 0; $z < $clconceito->numrows; $z++ ) {

                     db_fieldsmemory( $result3, $z );
                 ?>
                  <option value="<?=trim($ed39_c_conceito)?>" <?=trim($ed39_c_conceito)==trim($ed72_c_valorconceito)?"selected":""?>><?=trim($ed39_c_conceito)?></option>
                 <?}?>
                </select>
               </td>
               <td width="60">
                <?php
                  if ( trim( $ed72_t_obs != "" ) && $ed81_c_todoperiodo != "S" && $ed60_c_ativa == "S" ) {
                ?>
                 <b><a style="color:green;text-decoration:none;"
                       href="javascript:js_observacoes(<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=$ed47_v_nome?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>,'<?=$ed72_i_valornota?>','<?=$ed72_c_valorconceito?>','<?=$ed72_t_parecer?>');"
                       title="Aluno <?=$ed47_v_nome?> possui observações cadastradas neste período.">O</a></b>
                <?}

                  if ( ( trim($ed72_t_parecer) != "" or $clpareceraval->numrows > 0 ) && $ed81_c_todoperiodo != "S" && $ed60_c_ativa == "S" ) {?>
                    &nbsp;&nbsp;<b><a style="color:green;text-decoration:none;" href="javascript:js_parecerindividual(<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=$ed47_v_nome?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>,'<?=$ed72_i_valornota?>','<?=$ed72_c_valorconceito?>');" title="Aluno <?=$ed47_v_nome?> possui parecer descritivo cadastrado neste período.">P</a></b>
                <?}?>
               </td>
              </tr>
             </table>
            </td>
           <?} else if ( trim( $ed37_c_tipo ) == "PARECER" ) { ?>

            <td align="center">
             <table class='aluno' cellspacing="0" cellpading="0">
              <tr>
               <td width="50">
                <input name="ed72_t_parecerX<?=$x?>" value="<?=@$ed72_t_parecer!=''?htmlspecialchars(substr(@$ed72_t_parecer,0,20).'...'):''?>" type="text" size="20" maxlength="20" style="background:<?=$cordisabled?>;height:14px;text-align:left;border: 1px solid #000000;font-size:11px;padding:0px;" onclick="js_parecer(this,<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=str_replace("'","",$ed47_v_nome);?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>);" readonly <?=$disabled?> >
               </td>
               <td width="60">
                <?php
                  if ( trim( $ed72_t_obs != "" ) && $ed81_c_todoperiodo != "S" && $ed60_c_ativa == "S" ) { ?>
                    &nbsp;<b><a style="color:green;text-decoration:none;"href="javascript:js_observacoes(<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=$ed47_v_nome?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>,'<?=$ed72_i_valornota?>','<?=$ed72_c_valorconceito?>','<?=$ed72_t_parecer?>');" title="Aluno <?=$ed47_v_nome?> possui observações cadastradas neste período.">O</a></b>
                <?}

                  if ( $clpareceraval->numrows > 0 && $ed81_c_todoperiodo != "S" && $ed60_c_ativa == "S" ) { ?>
                    &nbsp;&nbsp;<b><a style="color:green;text-decoration:none;" href="javascript:js_parecer(this,<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=str_replace("'","",$ed47_v_nome);?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>);" title="Aluno <?=$ed47_v_nome?> possui parecer descritivo cadastrado neste período.">P</a></b>
                <?}?>
               </td>
              </tr>
             </table>
            </td>
           <?} else if ( trim( $ed37_c_tipo ) == "NOTA" ) { ?>

            <td class='aluno' align="center">
             <table class='aluno' cellspacing="0" cellpading="0">
              <tr>
               <td width="50">
                <? if ( $resultedu == "S" ) { ?>
                 <input name="ed72_i_valornotaX<?=$x?>"
                        id="ed72_i_valornotaX<?=$x?>"
                        value="<?=@$ed72_i_valornota?>"
                        type="text"
                        size="6"
                        class='inputNotas'
                        maxlength="6"
                        style="background:<?=$cordisabled?>;width:45px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;"
                        <?=trim($ed95_c_encerrado)=="S"?"onclick=\"alert('Aluno já possui avaliações encerradas para esta disciplina!')\"":"onChange=\"js_formatavalor(this,$ed37_i_variacao,$ed37_i_menorvalor,$ed37_i_maiorvalor,$ed72_i_codigo,'$ed72_i_numfaltas','NOTA','$ed59_c_freqglob',$linhas1);\""?>
                        <?=trim($ed95_c_encerrado)=="S"?"readonly":$disabled?>
                        <?
                          if (trim($ed95_c_encerrado) != "S") {

                            if ($disabled == '') {

                              $iTabIndex++;
                              echo ' tabindex="'.$iTabIndex.'" ';
                            }
                          }
                        ?>
                 >
                <?} else {?>
                 <input name="ed72_i_valornotaX<?=$x?>"
                        id="ed72_i_valornotaX<?=$x?>"
                        value="<?=@$ed72_i_valornota?>"
                        type="text"
                        class='inputNotas'
                        size="6"
                        maxlength="6"
                        style="background:<?=$cordisabled?>;width:45px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;"
                        <?=trim($ed95_c_encerrado)=="S"?"onclick=\"alert('Aluno já possui avaliações encerradas para esta disciplina!')\"":"onChange=\"js_formatavalor(this,$ed37_i_variacao,$ed37_i_menorvalor,$ed37_i_maiorvalor,$ed72_i_codigo,'$ed72_i_numfaltas','NOTA','$ed59_c_freqglob',$linhas1);\""?>
                        <?=trim($ed95_c_encerrado)=="S"?"readonly":$disabled?>
                        <?
                          if (trim($ed95_c_encerrado) != "S") {

                            if ($disabled == '') {

                              $iTabIndex++;
                              echo ' tabindex="'.$iTabIndex.'" ';
                            }
                          }
                        ?>
                 ></input>
                <?}?>
               </td>
               <td width="60">
                <?php
                  if ( trim( $ed72_t_obs != "" )  && $ed81_c_todoperiodo != "S" && $ed60_c_ativa == "S" ) { ?>
                    <b><a style="color:green;text-decoration:none;"href="javascript:js_observacoes(<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=$ed47_v_nome?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>,'<?=$ed72_i_valornota?>','<?=$ed72_c_valorconceito?>','<?=$ed72_t_parecer?>');" title="Aluno <?=$ed47_v_nome?> possui observações cadastradas neste período.">O</a></b>
                <?}
                  if ( ( trim( $ed72_t_parecer ) != "" or $clpareceraval->numrows > 0 ) && $ed81_c_todoperiodo != "S" && $ed60_c_ativa == "S" ) { ?>
                  &nbsp;&nbsp;<b><a style="color:green;text-decoration:none;" href="javascript:js_parecerindividual(<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=str_replace("'","",$ed47_v_nome);?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>,'<?=$ed72_i_valornota?>','<?=$ed72_c_valorconceito?>');" title="Aluno <?=$ed47_v_nome?> possui parecer descritivo cadastrado neste período.">P</a></b>
                <?}?>
               </td>
              </tr>
             </table>
            </td>
           <?}
          }
         }
        }

        if ( trim( $ed59_c_freqglob ) != "A" ) {

          $disabled    = $vinculo == true || trim( $ed60_c_situacao ) != "MATRICULADO" || trim( $ed72_c_amparo ) == "S" ? "readonly" : "";
          $cordisabled = $vinculo == true || trim( $ed60_c_situacao ) != "MATRICULADO" || trim( $ed72_c_amparo ) == "S" ? "#FFD5AA" : "";

          if ( ( $ed72_i_escola != $codescola && $ed72_c_tipo == "M" ) || $ed81_c_todoperiodo == "S" ) {

           $disabled    = "readonly";
           $cordisabled = "#FFD5AA";
          }

          if (    ( trim( $ed60_c_situacao ) == "TRANSFERIDO FORA" || trim( $ed60_c_situacao ) == "CANCELADO" || trim( $ed60_c_situacao ) == "EVADIDO")
               && $ed60_c_ativa == "S" && $ed95_c_encerrado == "N" ) {

           $disabled         = "readonly";
           $cordisabled      = "#FFD5AA";
           $ed95_c_encerrado = "N";
          }
         ?>
         <td class='aluno' align="center">
          <table cellspacing="0" cellpading="0">
           <tr>
            <td class='aluno'>
             <?php
               if ( trim( $ed72_c_amparo ) == "S" ) {

                 $semfoco = true;
                 if ( $ed60_c_ativa == "S" ) {

                   if ( $ed81_i_justificativa != "" ) {
                     ?><td align="center" class='aluno'>Amparo - Justif. n° <?=trim($ed81_i_justificativa)?></td><?
                   } else if ( $ed81_i_convencaoamp != "" ) {
                     ?><td align="center" class='aluno'><?=$ed250_c_abrev?></td><?
                   }
                 } else {
                   ?><td align="center" class='aluno'>&nbsp;</td><?
                 }
               } else {?>
              <input name="ed72_i_numfaltasX<?=$x?>"
                     id="ed72_i_numfaltasX<?=$x?>"
                     value="<?=@$ed72_i_numfaltas?>"
                     type="text"
                     size="4"
                     maxlength="3"
                     style="background:<?=$cordisabled?>;width:25px;height:14px;border: 1px solid #000000;font-size:11px;text-align:right;padding:0px;"
                     <?=trim($ed95_c_encerrado)=="S"?"onclick=\"alert('Aluno já possui avaliações encerradas para esta disciplina!')\"":"onchange=\"js_faltas(this,$ed72_i_procavaliacao,'$ed09_c_descr',$ed72_i_codigo,'$ed72_i_valornota','$ed72_c_valorconceito','$ed80_i_numfaltas','$ed59_c_freqglob',$linhas1,'$ed37_c_tipo');\""?>
                     <?=trim($ed95_c_encerrado)=="S"?"readonly":$disabled?>
                     <?
                       if (trim($ed95_c_encerrado) != "S") {

                         if ($disabled == '') {

                           if ($oEduParametros->ed233_deslocamentocursor == 1) {

                             $iTabIndex++;
                             echo ' tabindex="'.$iTabIndex.'" ';

                           } else {
                             echo ' tabindex="'.($iTabIndex+$linhas1).'" ';
                           }
                         }
                       }
                     ?>
              >
             <?}?>
            </td>
            <td class='aluno' width="20" align="center">
             <?=$ed80_i_numfaltas!=""?"<b>A</b>":"&nbsp;"?>
            </td>
           </tr>
          </table>
         </td>
         <td width="5%" align="center">
          <?php
            if ( trim( $ed60_c_situacao ) == "MATRICULADO" && trim( $ed72_c_amparo ) == "N") {

              if ( trim( $ed72_t_obs ) == "" && $ed81_c_todoperiodo != "S" && $ed60_c_ativa == "S" ) { ?>
                <b><a href="javascript:js_observacoes(<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=$ed47_v_nome?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>,'<?=$ed72_i_valornota?>','<?=$ed72_c_valorconceito?>','<?=$ed72_t_parecer?>');" title="Incluir observações neste período de avaliação para <?=$ed47_v_nome?>">O</a></b>
            <?} else {
                echo"&nbsp;";
              }

              if ( trim( $ed37_c_tipo ) != "PARECER" ) {

                if ( ( trim( $ed72_t_parecer ) == "" && $clpareceraval->numrows == 0 ) && $ed81_c_todoperiodo != "S" && $ed60_c_ativa == "S" ) {?>

                  &nbsp;&nbsp;
                  <b><a href="javascript:js_parecerindividual(<?=$ed72_i_codigo?>,'<?=$ed09_c_descr?>','<?=$ed47_v_nome?>','<?=$ed72_i_numfaltas?>','<?=$ed95_c_encerrado?>',<?=$ed59_i_turma?>,<?=$ed47_i_codigo?>,<?=$ed72_i_procavaliacao?>,'<?=$ed72_i_valornota?>','<?=$ed72_c_valorconceito?>');" title="Incluir parecer descritivo neste período de avaliação para <?=$ed47_v_nome?>">P</a></b>
              <?} else {
                  echo"&nbsp;";
                }
              }
            } else {
              echo"&nbsp;";
            }?>
         </td>
        <?}?>
       </tr>
      <?php
        if ( $ed60_c_parecer == "S" ) {
          $ed37_c_tipo = $ed37_c_tipo_ant;
        }
      }?>
      <tr>
       <td align="center" colspan="10">

        <?if ($oEduParametros->ed233_deslocamentocursor == 1) {?>
          <input type="submit" name="salvartudo" value="Salvar"  <?=$somador==0||$nivel0==0?"disabled":""?> tabindex =<?=$iTabIndex++?>>
        <?} else {?>
          <input type="submit" name="salvartudo" value="Salvar"  <?=$somador==0||$nivel0==0?"disabled":""?> tabindex =<?=($linhas1+$iTabIndex+1)?>>
        <?}?>
        <input type="button" name="mensagens" value="Ver Mensagens" style="visibility:hidden;" onclick="location.href='edu1_diarioavaliacao001.php?regencia=<?=$regencia?>&ed41_i_codigo=<?=$ed41_i_codigo?>'">
       </td>
      </tr>
     <?} else {?>

        <tr>
          <td colspan="3" class='aluno' align="center">NENHUM ALUNO MATRICULADO NESTA TURMA.</td>
        </tr>
     <?}?>
    </table>
   <input name="ed41_i_codigo" type="hidden" value="<?=$ed41_i_codigo?>">
   <input name="regencia" type="hidden" value="<?=$regencia?>">
   <input name="tipo" type="hidden" value="<?=$ed37_c_tipo?>">
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
function js_focoinicial() {

  focoinicial = "";

  for ( i = 0; i < <?=$linhas1?>; i++ ) {

  <?php
    if ( trim( $ed59_c_freqglob ) != "F" && $semfoco == false ) {

      if (trim( $ed37_c_tipo ) == "NOTA" ) { ?>

        nomecampo = eval("document.form1.ed72_i_valornotaX"+i);
        if ( nomecampo.readOnly == false ) {

          focoinicial = "ed72_i_valornotaX"+i;
          break;
        }
   <?php
      } else if ( trim( $ed37_c_tipo ) == "NIVEL" ) { ?>

        nomecampo = eval("document.form1.ed72_c_valorconceitoX"+i);
        if ( nomecampo.disabled == false ) {

          focoinicial = "ed72_c_valorconceitoX"+i;
          break;
        }
   <?php
      } else { ?>

        nomecampo = eval("document.form1.ed72_t_parecerX"+i);
        if ( nomecampo.disabled == false ) {

          focoinicial = "ed72_t_parecerX"+i;
          break;
        }
    <?}
    } else if ( trim( $ed59_c_freqglob ) == "F" && $semfoco == false ) { ?>

      nomecampo = eval("document.form1.ed72_i_numfaltasX"+i);
      if ( nomecampo.disabled == false ) {

        focoinicial = "ed72_i_numfaltasX"+i;
        break;
      }
  <?}?>
  }

  if ( focoinicial != "" ) {
    camporetorno = focoinicial;
  } else {
    camporetorno = "null";
  }

  return camporetorno;
}

campofoco = js_focoinicial();
$(campofoco).focus();

function js_cent( amount ) {

  //retorna o valor com 2 casas decimais
  return amount;
  <? if ( $resultedu == "S" ) { ?>
       return(amount == Math.floor(amount)) ? amount + '.00' : ( (amount*10 == Math.floor(amount*10)) ? amount + '0' : amount);
  <? } else { ?>
       return(amount == Math.floor(amount)) ? Math.floor(amount) : ( (amount*10 == Math.floor(amount*10)) ? Math.floor(amount) : Math.floor(amount));
  <? } ?>
}

function js_dec( cantidad, decimales ) {

  //arredonda o valor
  var cantidad  = parseFloat(cantidad);
  var decimales = parseFloat(decimales);
      decimales = (!decimales ? 2 : decimales);

  return Math.round(cantidad * Math.pow(10, decimales)) / Math.pow(10, decimales);
}

function js_formatavalor( campo, variacao, menor, maior, codigo, faltas, tipo, regencia, qtdlinha ) {

  if ( campo.value!= "" ) {

    valor     = campo.value.replace(",",".");
    var expre = new RegExp("[^0-9\.]+");

    if ( !valor.match( expre ) ) {

      if ( valor<menor || valor>maior ) {

        alert("Nota deve ser entre "+menor+" e "+maior+"!");
        campo.value = "";
        campo.focus();
      } else {

        variacaoant = variacao;
        valorant    = valor;

        if ( variacao<1 ) {

          partevariacao = variacao.toString();
          partevariacao = partevariacao.split(".");

          if ( partevariacao[1].length == 1 ) {
            variacao = partevariacao[1]+"0";
          } else {
            variacao = partevariacao[1];
          }

          partevalor = valor.toString();
          partevalor = partevalor.split(".");

          if ( partevalor[1] != undefined ) {

            if ( partevalor[1].length == 1 ) {
              valor = partevalor[1]+"0";
            } else {
              valor = partevalor[1];
            }
          } else {
            valor = "00";
          }

          valor    = parseInt(valor);
          variacao = parseInt(variacao);
        }

        if ( ( valor % variacao ) == 0 ) {

          variacao    = variacaoant;
          valor       = valorant;
          valor       = parseFloat(valor);
          campo.value = js_cent(valor);
          adiante     = js_cent(valor);
        } else {

          variacao = variacaoant;
          alert("Intervalos da Nota devem ser de "+variacao+"");
          campo.value = "";
          campo.focus();
        }
      }
    } else {

      alert("Nota deve ser um número!");
      campo.value = "";
      campo.focus();
    }
  }
}

function js_faltas( campo, codperiodo, periodo, codigo, nota, conceito, abono, regencia, qtdlinha, tipo ) {

  aulas = eval('parent.iframe_G.document.form1.ed78_i_aulasdadas'+codperiodo+'.value');
  aulas = aulas == "" ? "" : parseInt(aulas);

  var expr = new RegExp("[^0-9]+");

  if ( campo.value.match( expr ) ) {

    alert("Falta deve ser um número inteiro!");
    campo.value = "";
    campo.focus();
  } else {

    if ( aulas == "" ) {

      alert("Informe as aulas dadas no período "+periodo+" (Geral)!");
      campo.value = "";
      campo.focus();
    } else if ( campo.value > aulas ) {

      alert("Número de faltas é maior que as aulas dadas!");
      campo.value = "";
      campo.focus();
    } else {

      if ( abono != undefined && abono != "" ) {

        if ( campo.value == "" || campo.value == 0 ) {

          alert("Existe abono de faltas cadastrado para este aluno!");
          document.form1.salvartudo.disabled =true;
          campo.value = "";
          campo.focus();
        } else if ( campo.value<parseFloat(abono) ) {

          alert("Número de faltas é menor que as faltas abonadas para este período!");
          document.form1.salvartudo.disabled =true;
          campo.value = "";
          campo.focus();
        } else {
        	document.form1.salvartudo.disabled =false;
        }
      }
    }
  }
}

function js_conceito( campo, codigo, faltas, tipo, regencia, qtdlinha ) {

  location.href = "edu1_diarioavaliacao001.php?regencia=<?=$regencia?>";
                                            +"&ed41_i_codigo=<?=$ed41_i_codigo?>";
                                            +"&tipo="+tipo
                                            +"&codigo="+codigo
                                            +"&valor="+campo.value
                                            +"&faltas="+faltas
                                            +"&alterar";
}

function js_parecer( campo, codigo, periodo, aluno, faltas, encerrado, turma, codaluno, codperiodo ) {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_parecer',
                       'edu1_pareceraval001.php?ed93_i_diarioavaliacao='+codigo
                                             +'&campo='+campo.name
                                             +'&periodo='+periodo
                                             +'&aluno='+aluno
                                             +'&faltas='+faltas
                                             +'&encerrado='+encerrado
                                             +'&turma='+turma
                                             +'&codaluno='+codaluno
                                             +'&codperiodo='+codperiodo,
                       'Parecer',
                       true,
                       0,
                       0,
                       screen.availWidth-50,
                       screen.availHeight
                     );
}

function js_movimentos( matricula ) {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_movimentos',
                       'edu1_matricula005.php?matricula='+matricula,
                       'Movimentação da Matrícula',
                       true,
                       0,
                       0,
                       screen.availWidth-35,
                       screen.availHeight
                     );
}

function js_parecerindividual( codigo, periodo, aluno, faltas, encerrado, turma, codaluno, codperiodo, nota, conceito ) {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_parecerindiv',
                       'edu1_pareceraval002.php?ed93_i_diarioavaliacao='+codigo
                                             +'&periodo='+periodo
                                             +'&nota='+nota
                                             +'&aluno='+aluno
                                             +'&faltas='+faltas
                                             +'&encerrado='+encerrado
                                             +'&turma='+turma
                                             +'&codaluno='+codaluno
                                             +'&codperiodo='+codperiodo
                                             +'&conceito='+conceito,
                       'Parecer',
                       true,
                       0,
                       0,
                       screen.availWidth-50,
                       screen.availHeight
                     );
}

function js_observacoes( codigo, periodo, aluno, faltas, encerrado, turma, codaluno, codperiodo, nota, conceito, parecer ) {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_obs',
                       'edu1_diarioavaliacaoobs001.php?ed93_i_diarioavaliacao='+codigo
                                                    +'&periodo='+periodo
                                                    +'&parecer='+parecer
                                                    +'&nota='+nota
                                                    +'&aluno='+aluno
                                                    +'&faltas='+faltas
                                                    +'&encerrado='+encerrado
                                                    +'&turma='+turma
                                                    +'&codaluno='+codaluno
                                                    +'&codperiodo='+codperiodo
                                                    +'&conceito='+conceito,
                       'Observações',
                       true,
                       0,
                       0,
                       screen.availWidth-50,
                       screen.availHeight
                     );
}

function js_abono( avaliacao, regencia, descrperiodo ) {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_abono',
                       'edu1_abonofalta001.php?avaliacao='+avaliacao+'&regencia='+regencia+'&descrperiodo='+descrperiodo,
                       'Abonar Faltas',
                       true,
                       0,
                       0,
                       screen.availWidth-50,
                       screen.availHeight
                     );
}

function js_tiponota( datamatricula, codigo, regencia, periodo, descrperiodo, aprovperiodo ) {

  titulo = "Modificar a Origem da Nota (NOTA EXTERNA(NE)-Origem em Outras Escolas  NOTA INTERNA(NI)-Origem na própria escola)";
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_tiponota',
                       'edu1_tiponota001.php?diarioavaliacao='+codigo
                                          +'&aprovperiodo='+aprovperiodo
                                          +'&datamatricula='+datamatricula
                                          +'&regencia='+regencia
                                          +'&ed41_i_codigo='+periodo
                                          +'&descrperiodo='+descrperiodo,
                       titulo,
                       true,
                       0,
                       0,
                       screen.availWidth-50,
                       screen.availHeight
                     );
}

function js_mostra( id ) {
  document.getElementById(id).style.visibility = "visible";
}

function js_oculta( id ) {
  document.getElementById(id).style.visibility = "hidden";
}
</script>
<?
$sql_r     = "SELECT DISTINCT max(ed09_i_sequencia) ";
$sql_r    .= "  FROM diarioavaliacao ";
$sql_r    .= "       inner join diario           on diario.ed95_i_codigo           = diarioavaliacao.ed72_i_diario ";
$sql_r    .= "       inner join procavaliacao    on procavaliacao.ed41_i_codigo    = diarioavaliacao.ed72_i_procavaliacao ";
$sql_r    .= "       inner join periodoavaliacao on periodoavaliacao.ed09_i_codigo = procavaliacao.ed41_i_periodoavaliacao ";
$sql_r    .= " WHERE diario.ed95_i_regencia = {$regencia} ";
$sql_r    .= "   AND (    diarioavaliacao.ed72_i_numfaltas is not null ";
$sql_r    .= "         OR diarioavaliacao.ed72_i_valornota is not null ";
$sql_r    .= "         OR diarioavaliacao.ed72_c_valorconceito != '' ";
$sql_r    .= "         OR diarioavaliacao.ed72_t_parecer != '')";
$result_r  = db_query( $sql_r );
$linhas    = pg_num_rows( $result_r );
db_fieldsmemory( $result_r, 0 );

if ( $max == "" ) {

  db_inicio_transacao();
  $clregencia->ed59_c_ultatualiz = "SI";
  $clregencia->ed59_i_codigo     = $regencia;
  $clregencia->alterar($regencia);
  db_fim_transacao();
} else {

  $sSqlPeriodoAvaliacao = $clperiodoavaliacao->sql_query_file( "", "ed09_c_abrev", "", "ed09_i_sequencia = {$max}");
  $result_p             = $clperiodoavaliacao->sql_record( $sSqlPeriodoAvaliacao );
  db_fieldsmemory( $result_p, 0 );

  db_inicio_transacao();
  $clregencia->ed59_c_ultatualiz = $ed09_c_abrev;
  $clregencia->ed59_i_codigo     = $regencia;
  $clregencia->alterar($regencia);
  db_fim_transacao();
}

$sSqlProcAvaliacao = $clprocavaliacao->sql_query("","ed09_c_somach",""," ed41_i_codigo = $ed41_i_codigo");
$result_per        = $clprocavaliacao->sql_record( $sSqlProcAvaliacao );
db_fieldsmemory( $result_per, 0 );

if ( $ed09_c_somach == "S" ) {

 $contador                = 0;
 $sWherePeriodoCalendario = " ed53_i_calendario = {$ed57_i_calendario} AND ed53_i_periodoavaliacao = {$ed09_i_codigo}";
 $sSqlPeriodoCalendario   = $clperiodocalendario->sql_query_file( "", "ed53_d_fim,ed53_d_inicio", "", $sWherePeriodoCalendario);
 $rsPeriodoCalendario     = db_query($sSqlPeriodoCalendario);

 if ($rsPeriodoCalendario && pg_num_rows($rsPeriodoCalendario) > 0) {

   db_fieldsmemory( $rsPeriodoCalendario, 0 );
   $sql     = "SELECT ed60_i_aluno, ed60_i_codigo, ed60_matricula, ed60_i_numaluno, ed60_c_situacao, ed47_v_nome";
   $sql    .= "       , ed72_i_codigo, ed60_d_datamatricula, ed09_c_abrev, ed72_i_valornota, ed72_c_valorconceito";
   $sql    .= "       ,ed72_t_parecer";
   $sql    .= "  FROM matricula ";
   $sql    .= "       inner join aluno            on ed47_i_codigo     = ed60_i_aluno ";
   $sql    .= "       inner join diario           on ed95_i_aluno      = ed47_i_codigo ";
   $sql    .= "       inner join matriculaserie   on ed221_i_matricula = ed60_i_codigo ";
   $sql    .= "       inner join regencia         on ed59_i_codigo     = ed95_i_regencia ";
   $sql    .= "                                  and ed59_i_serie      = ed221_i_serie ";
   $sql    .= "       inner join diarioavaliacao  on ed72_i_diario     = ed95_i_codigo ";
   $sql    .= "       inner join procavaliacao    on ed41_i_codigo     = ed72_i_procavaliacao ";
   $sql    .= "       inner join formaavaliacao   on ed37_i_codigo     = ed41_i_formaavaliacao ";
   $sql    .= "       inner join periodoavaliacao on ed09_i_codigo     = ed41_i_periodoavaliacao ";
   $sql    .= " WHERE ed95_i_regencia      = {$regencia} ";
   $sql    .= "   AND ed72_i_procavaliacao = {$ed41_i_codigo} ";
   $sql    .= "   AND ed60_i_turma         = {$ed59_i_turma} ";
   $sql    .= "   AND ed60_d_datamatricula > '{$ed53_d_fim}' ";
   $sql    .= "   AND ed72_i_escola        = ed95_i_escola ";
   $sql    .= "   AND ed60_c_situacao      = 'MATRICULADO' ";
   $sql    .= "   AND ed221_c_origem       = 'S' ";
   $sql    .= " ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome) ";
   $result  = db_query( $sql );
   $linhas  = pg_num_rows( $result );

   if ( $linhas > 0 ) {
    ?>
    <table id="tb_aviso" style="top:80px;left:50px;visibility:visible;position:absolute;width:700px;border:3px solid #444444 " cellspacing="0" cellpading="2" bgcolor="#f3f3f3">
     <tr>
      <td class="titulo" colspan="6" align="center">
       Período: <?=$ed09_c_descr?> <?=db_formatar($ed53_d_inicio,'d')?> até <?=db_formatar($ed53_d_fim,'d')?>
      </td>
     </tr>
     <tr>
      <td colspan="6" align="center">
       <b>ATENÇÃO:</b>
      </td>
     <tr>
      <td colspan="6">
      Os alunos abaixo relacionados tem a data de sua matrícula posterior ao término deste período.<br>
      Seu aproveitamento neste período deve constar como NOTA EXTERNA.<br><br>
      </td>
     </tr>
     <tr class="cabec1" style="border:1px solid #444444">
      <td>Código</td>
      <td>N°</td>
      <td>Nome</td>
      <td>Situação</td>
      <td>Data Matrícula</td>
      <td>&nbsp;</td>
     </tr>
     <?
     for ( $x = 0; $x < $linhas; $x++ ) {

      db_fieldsmemory( $result, $x );
      $sql1    = "SELECT * FROM trocaserie WHERE ed101_i_aluno = {$ed60_i_aluno} AND extract(year from ed101_d_data) = '{$ed52_i_ano}'";
      $result1 = db_query( $sql1 );
      $linhas1 = pg_num_rows( $result1 );

      $sql2     = "SELECT * ";
      $sql2    .= "  FROM diarioavaliacao ";
      $sql2    .= "       inner join diario     on ed95_i_codigo = ed72_i_diario ";
      $sql2    .= "       inner join calendario on ed52_i_codigo = ed95_i_calendario ";
      $sql2    .= " WHERE ed95_i_aluno     = {$ed60_i_aluno} ";
      $sql2    .= "   AND ed52_i_ano       = {$ed52_i_ano} ";
      $sql2    .= "   AND ed95_i_escola   != {$codescola} ";
      $sql2    .= "   AND ed95_i_regencia  = {$regencia}";
      $result2  = db_query( $sql2 );
      $linhas2  = pg_num_rows( $result2 );

      $sql3     = "SELECT * ";
      $sql3    .= "  FROM matricula ";
      $sql3    .= "       inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
      $sql3    .= " WHERE ed60_i_aluno   = {$ed60_i_aluno} ";
      $sql3    .= "   AND ed60_c_ativa   = 'N' ";
      $sql3    .= "   AND ed60_i_turma   = {$ed57_i_codigo} ";
      $sql3    .= "   AND ed221_i_serie  = {$ed59_i_serie} ";
      $sql3    .= "   AND ed221_c_origem = 'S'";
      $result3  = db_query( $sql3 );
      $linhas3  = pg_num_rows( $result3 );

      if ( $linhas1 == 0 && $linhas2 == 0  && $linhas3 == 0 ) {

        if ( trim( $ed37_c_tipo ) == "NOTA" ) {
          $aprovperiodo = $ed72_i_valornota;
        } else if ( trim( $ed37_c_tipo ) == "NIVEL" ) {
          $aprovperiodo = $ed72_c_valorconceito;
        } else {
          $aprovperiodo = $ed72_t_parecer;
        }
        $contador++;
       ?>
       <tr>
        <td><?=$ed60_i_aluno?></td>
        <td><?=$ed60_i_numaluno?></td>
        <td><?=$ed47_v_nome?></td>
        <td><?=$ed60_c_situacao?></td>
        <td><?=db_formatar($ed60_d_datamatricula,'d')?></td>
        <td><input type="button" value="Modificar" onclick="document.getElementById('tb_aviso').style.visibility = 'hidden';js_tiponota('<?=$ed60_d_datamatricula?>',<?=$ed72_i_codigo?>,<?=$regencia?>,<?=$ed41_i_codigo?>,'<?=$ed09_c_descr?>','<?=$aprovperiodo?>')"></td>
       </tr>
       <?
      }
     }
     ?>
     <tr>
      <td colspan="6" align="center">
       <br>
       <input type="button" value="Fechar" onclick="document.getElementById('tb_aviso').style.visibility = 'hidden';$(campofoco).focus();">
       <br><br>
      </td>
     <tr>
     <tr>
      <td colspan="6" align="right" class="aluno">
       <b>Para ver esta janela novamente, clique na aba <?=$ed09_c_abrev?>.</b>
      </td>
     <tr>
    </table>
    <?
   }
 }

 if ( $contador == 0 && $linhas > 0 ) {
   ?><script>document.getElementById('tb_aviso').style.visibility = 'hidden';</script><?
 }

 $sql1     = "SELECT ed60_i_aluno, ed60_i_codigo, ed60_matricula, ed60_i_numaluno, ed60_c_situacao, ed47_v_nome";
 $sql1    .= "       , ed72_i_codigo, ed60_d_datamatricula, ed09_c_abrev, ed72_i_valornota, ed72_c_valorconceito";
 $sql1    .= "       , ed72_t_parecer ";
 $sql1    .= "  FROM matricula ";
 $sql1    .= "       inner join aluno            on ed47_i_codigo     = ed60_i_aluno ";
 $sql1    .= "       inner join diario           on ed95_i_aluno      = ed47_i_codigo ";
 $sql1    .= "       inner join matriculaserie   on ed221_i_matricula = ed60_i_codigo ";
 $sql1    .= "       inner join regencia         on ed59_i_codigo     = ed95_i_regencia ";
 $sql1    .= "                                  and ed59_i_serie      = ed221_i_serie ";
 $sql1    .= "       inner join diarioavaliacao  on ed72_i_diario     = ed95_i_codigo ";
 $sql1    .= "       inner join procavaliacao    on ed41_i_codigo     = ed72_i_procavaliacao ";
 $sql1    .= "       inner join formaavaliacao   on ed37_i_codigo     = ed41_i_formaavaliacao ";
 $sql1    .= "       inner join periodoavaliacao on ed09_i_codigo     = ed41_i_periodoavaliacao ";
 $sql1    .= " WHERE ed95_i_regencia      = {$regencia} ";
 $sql1    .= "   AND ed72_i_procavaliacao = {$ed41_i_codigo} ";
 $sql1    .= "   AND ed60_i_turma         = {$ed59_i_turma} ";
 $sql1    .= "   AND ed72_c_convertido    = 'S' ";
 $sql1    .= "   AND ed60_c_situacao      = 'MATRICULADO' ";
 $sql1    .= "   AND ed72_c_amparo        = 'N' ";
 $sql1    .= "   AND ed221_c_origem       = 'S' ";
 $sql1    .= " ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome)";
 $result1  = db_query( $sql1 );
 $linhas1  = pg_num_rows( $result1 );

 if ( $linhas1 > 0 ) {

  ?>
  <script>
   document.form1.salvartudo.disabled = true;
   <?if ( $contador > 0 ) {?>
       document.getElementById('tb_aviso').style.visibility = 'hidden';
   <?}?>
  </script>
  <table id="tb_aviso1" style="top:80px;left:50px;visibility:visible;position:absolute;width:700px;border:3px solid #444444 " cellspacing="0" cellpading="2" bgcolor="#f3f3f3">
   <tr>
    <td class="titulo" colspan="6" align="center">
     Período: <?=$ed09_c_descr?> <?=db_formatar($ed53_d_inicio,'d')?> até <?=db_formatar($ed53_d_fim,'d')?>
    </td>
   </tr>
   <tr>
    <td colspan="6" align="center">
     <b>ATENÇÃO:</b>
    </td>
   <tr>
    <td colspan="6">
    Os alunos abaixo relacionados devem ter o seu aproveitamento convertido,<br>
    devido a forma de avaliação da turma de origem ser diferente da forma de avaliação desta turma.<br><br>
    </td>
   </tr>
   <tr class="cabec1" style="border:1px solid #444444">
    <td>Código</td>
    <td>N°</td>
    <td>Nome</td>
    <td>Data Matrícula</td>
    <td>Aproveitamento</td>
    <td>&nbsp;</td>
   </tr>
   <?
   for ( $x = 0; $x < $linhas1; $x++ ) {

     db_fieldsmemory( $result1, $x );

     if ( trim( $ed37_c_tipo ) == "NOTA" ) {
       $aprovperiodo = $ed72_i_valornota;
     } else if ( trim( $ed37_c_tipo ) == "NIVEL" ) {
       $aprovperiodo = $ed72_c_valorconceito;
     } else {
       $aprovperiodo = $ed72_t_parecer;
     }

     $contador++;
    ?>
    <tr>
     <td><?=$ed60_i_aluno?></td>
     <td><?=$ed60_i_numaluno?></td>
     <td><?=$ed47_v_nome?></td>
     <td><?=db_formatar($ed60_d_datamatricula,'d')?></td>
     <td><?=$aprovperiodo?></td>
     <td>
       <input type="button"
              value="Converter"
              onclick="document.getElementById('tb_aviso1').style.visibility = 'hidden';js_tiponota('<?=$ed60_d_datamatricula?>',<?=$ed72_i_codigo?>,<?=$regencia?>,<?=$ed41_i_codigo?>,'<?=$ed09_c_descr?>','<?=$aprovperiodo?>')"></td>
    </tr>
    <?
   }
   ?>
   <tr>
    <td colspan="6" align="center">
     <br>
     <input type="button" value="Fechar" onclick="document.getElementById('tb_aviso1').style.visibility = 'hidden';document.form1.salvartudo.disabled = false;">
     <br><br>
    </td>
   <tr>
   <tr>
    <td colspan="6" align="right" class="aluno">
     <b>Para ver esta janela novamente, clique na aba <?=$ed09_c_abrev?>.</b>
    </td>
   <tr>
  </table>
  <?
 }

 if ( $contador > 0 || $linhas1 > 0 ) {

   ?>
   <script>
     document.form1.mensagens.style.visibility = "visible";
   </script>
   <?
 }
}
?>
<script>

  var aInputsNota = $$('input.inputNotas');
  for (var i = 0; i < aInputsNota.length; i++) {
    js_observeMascaraNota(aInputsNota[i], '<?=ArredondamentoNota::getMascara($iAno);?>');
  }
</script>