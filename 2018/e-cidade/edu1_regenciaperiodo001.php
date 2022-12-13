<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$clregenciaperiodo = new cl_regenciaperiodo;
$clregenciahorario = new cl_regenciahorario;
$clregencia        = new cl_regencia;
$clprocavaliacao   = new cl_procavaliacao;
$cldiario          = new cl_diario;
$db_botao          = true;

$campos  = "regencia.*, ";
$campos .= "turma.*, ";
$campos .= "escola.*, ";
$campos .= "calendario.*, ";
$campos .= "cursoedu.*, ";
$campos .= "base.*, ";
$campos .= "caddisciplina.*, ";
$campos .= "procedimento.*, ";
$campos .= "turno.*, ";
$campos .= "turmaserieregimemat.ed220_i_procedimento, ";
$campos .= "case when ed57_i_tipoturma = 2  ";
$campos .= "     then fc_nomeetapaturma(ed59_i_turma) "; 
$campos .= "     else serie.ed11_c_descr ";
$campos .= " end as ed11_c_descr ";

$sSqlRegencia = $clregencia->sql_query( "", $campos, "", "ed59_i_codigo = {$regencia}" );
$result       = $clregencia->sql_record( $sSqlRegencia );
db_fieldsmemory( $result, 0 );

$sWhereRegencia0 = "ed59_i_turma = {$ed59_i_turma} AND ed59_i_serie = {$ed59_i_serie}";
$sSqlRegencia0   = $clregencia->sql_query( "", "count(*) as qtdreg", "", $sWhereRegencia0 );
$result0         = $clregencia->sql_record( $sSqlRegencia0 );
db_fieldsmemory( $result0, 0 );

if ( trim( $ed57_c_medfreq ) == "PERÌODOS" ) {
  $tipofreq = "Aulas Dadas";
} else {
  $tipofreq = "Dias Letivos";
}

$sql  = "SELECT ed95_i_codigo, ed95_i_aluno ";
$sql .= "  FROM diario ";
$sql .= "       inner join matricula on ed60_i_aluno  = ed95_i_aluno ";
$sql .= "       inner join turma     on ed57_i_codigo = ed60_i_turma ";
$sql .= "       inner join regencia  on ed59_i_turma  = ed57_i_codigo ";
$sql .= "                           AND ed59_i_codigo = ed95_i_regencia ";
$sql .= " WHERE ed95_i_regencia  = {$regencia} ";
$sql .= "   AND ed95_c_encerrado = 'S' ";
$sql .= "   AND ed60_c_situacao  = 'MATRICULADO' ";
$sql .= "   AND exists ( select 1 ";
$sql .= "                  from regencia reg "; 
$sql .= "                 where reg.ed59_i_codigo    = ed95_i_regencia "; 
$sql .= "                   AND reg.ed59_c_encerrada = 'S' ) ";

$result1     = db_query( $sql );
$jaencerrado = pg_num_rows( $result1 );
?>
<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style>
    .titulo{
     font-size: 11;
     color: #DEB887;
     background-color:#444444;
     font-weight: bold;
     border: 1px solid #CCCCCC;
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<?
if ( isset( $alterar ) ) {

  $sql  = "SELECT ed72_i_numfaltas as faltasreg ";
  $sql .= "  FROM diario ";
  $sql .= "       inner join diarioavaliacao on ed72_i_diario = ed95_i_codigo ";
  $sql .= " WHERE ed95_i_regencia      = {$regencia} ";
  $sql .= "   AND ed72_i_procavaliacao = {$avaliacao} ";
  $sql .= "   AND ed72_i_numfaltas is not null ";
  
  $result1 = db_query( $sql );
  $linhas1 = pg_num_rows( $result1 );
  
  if ( $linhas1 > 0 && $aulasdadas == '' ) {
    db_msgbox( "Existem alunos com faltas neste período!" );
  } else {

    $erro = false;
    
    for ( $y = 0; $y < $linhas1; $y++ ) {

      db_fieldsmemory( $result1, $y );
      
      if ( $faltasreg > $aulasdadas ) {

        db_msgbox( "Existem alunos com n° de faltas superior ao n° de aulas dadas informado neste período!" );
        $erro = true;
      }
    }
    
    if ( $erro == false ) {

      db_inicio_transacao();
      $clregenciaperiodo->ed78_i_aulasdadas = $aulasdadas;
      $clregenciaperiodo->ed78_i_codigo     = $codigo;
      $clregenciaperiodo->alterar( $codigo );
      db_fim_transacao();
      ?>
      <script>
        parent.iframe_RF.location.href = "edu1_diariofinal001.php?regencia=<?=$regencia?>";
      </script>
      <?
      if ( $qtdreg > 1 && $ed59_c_freqglob != "FA" && $ed59_c_freqglob != "F" ) {
        ?>
        <script>
          js_OpenJanelaIframe(
                               '',
                               'db_iframe_outrareg',
                               'func_outrareg.php?regencia=<?=$regencia?>'
                                               +'&nabas=<?=$nabas?>'
                                               +'&avaliacao=<?=$avaliacao?>'
                                               +'&aulasdadas=<?=$aulasdadas?>',
                               'Alterar <?=$tipofreq?> em outras Disciplinas',
                               true
                             );
        </script>
        <?
      }
    }
  }
}
?>
<div class="center">
  <table align="left" width="98%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td align="left" valign="top" bgcolor="#CCCCCC">
        <fieldset style="width:99%; background:#EAEAEA;">
          <legend><b>Turma <?=$ed57_c_descr?></b></legend>
          <table border="0" cellspacing="0" width="100%" cellpadding="2">
            <tr>
              <td align="center" valign="top">
                <table border='0' width="100%" bgcolor="#EAEAEA" cellspacing="0px">
                  <tr>
                    <td width="15%"><b>Escola:</b></td>
                    <td><?=$ed18_c_nome?></td>
                    <td><b>Calendário:</b></td>
                    <td><?=$ed52_c_descr?></td>
                  </tr>
                  <tr>
                    <td><b>Curso:</b></td>
                    <td><?=$ed29_c_descr?></td>
                    <td><b>Base Curricular:</b></td>
                    <td><?=$ed31_c_descr?></td>
                  </tr>
                  <tr>
                    <td><b>Turma:</b></td>
                    <td><?=$ed57_c_descr?></td>
                    <td><b>Etapa:</b></td>
                    <td><?=$ed11_c_descr?></td>
                  </tr>
                  <tr>
                    <td><b>Disciplina:</b></td>
                    <td><?=$ed232_c_descr?></td>
                    <td><b>Atualizada até:</b></td>
                    <td><?=$ed59_c_ultatualiz == "SI" ? "SEM INFORMAÇÕES" : $ed59_c_ultatualiz?></td>
                  </tr>
                  <tr>
                    <td><b>Proc. Avaliação:</b></td>
                    <td><?=$ed40_c_descr?></td>
                    <td><b>Turno:</b></td>
                    <td><?=$ed15_c_nome?></td>
                  </tr>
                  <?php
                    $sCamposRegenciaHorario = "case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end as z01_nome";
                    $sWhereRegenciaHorario  = "ed58_i_regencia = $regencia and ed58_ativo is true  ";
                    $sSqlRegenciaHorario = $clregenciahorario->sql_query( "", $sCamposRegenciaHorario , "", $sWhereRegenciaHorario );
                    $result_h            = $clregenciahorario->sql_record( $sSqlRegenciaHorario );
                    
                    if ( $clregenciahorario->numrows > 0 ) {
                      db_fieldsmemory( $result_h, 0 );
                    } else {
                      $z01_nome = "";
                    }
                  ?>
                  <tr>
                    <td><b>Regente:</b></td>
                    <td><?=$z01_nome?></td>
                    <td><b>Frequência por:</b></td>
                    <td><?=$ed57_c_medfreq?></td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td align="center">
                <form name="form1" method="post" action="">
                <br>
                <?php
                  if ( trim( $ed57_c_medfreq ) == "PERÌODOS" ) { ?>
                    Informe as aulas dadas desta discilpina em cada período de avaliação:
                <?} else {?>
                    Informe os dias letivos em cada período de avaliação:
                <?}?>
                <br>
                <table border="0" cellspacing="0" cellpadding="2">
                  <tr align="center">
                  <?php
                    $disabled            = trim( $ed59_c_freqglob ) == "A" ? "disabled" : "";
                    $sWhereProcAvaliacao = "ed41_i_procedimento = {$ed220_i_procedimento} AND ed78_i_regencia = {$regencia}";
                    $sSqlProcAvaliacao   = $clprocavaliacao->sql_query_regper( "", "*", "ed09_i_sequencia", $sWhereProcAvaliacao );
                    $result1             = $clprocavaliacao->sql_record( $sSqlProcAvaliacao );
                    
                    for ( $y = 0; $y < $clprocavaliacao->numrows; $y++ ) {

                      db_fieldsmemory( $result1, $y );
                      ?>
                      <td width="70" class='titulo'><?=$ed09_c_abrev?></td>
                      <?
                    }
                  ?>
                  </tr>
                  <tr>
                    <?php
                      for ( $y = 0; $y < $clprocavaliacao->numrows; $y++ ) {

                        db_fieldsmemory( $result1, $y );
                        if ( $jaencerrado > 0 ) {
                        ?>
                          <td>
                            <input type="text" 
                                   id="<?=$y?>" 
                                   name="ed78_i_aulasdadas<?=$ed41_i_codigo?>" 
                                   value="<?=@$ed78_i_aulasdadas?>" 
                                   size="15" 
                                   maxlength="3" 
                                   style="text-align:center;" 
                                   onclick="alert('Existem alunos com avaliações encerradas para esta disciplina!');" 
                                   <?=trim( $ed59_c_freqglob ) == "A" ? "disabled" : "readonly"?> >
                          </td>
                        <?
                        } else {
                        ?>
                          <td>
                            <input type="text" 
                                   id="<?=$y?>" 
                                   name="ed78_i_aulasdadas<?=$ed41_i_codigo?>" 
                                   value="<?=@$ed78_i_aulasdadas?>" 
                                   size="15" 
                                   maxlength="3" 
                                   style="text-align:center;" 
                                   onchange="js_verifica(this,<?=$ed78_i_codigo?>,<?=$ed78_i_procavaliacao?>,<?=$clprocavaliacao->numrows?>)" 
                                   <?=$disabled?>>
                          </td>
                        <?
                        }
                      }?>
                  </tr>
                </table>
                <input name="regencia" type="hidden" value="<?=$regencia?>">
                <input name="nabas" type="hidden" value="<?=$nabas?>">
                </form>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
</div>
</body>
</html>
<script>
<?php
  if ( isset( $alterar ) ) {?>

    document.getElementById("<?=$camposeguinte?>").select();
    document.getElementById("<?=$camposeguinte?>").focus();
<?} else {?>

    document.getElementById("0").select();
    document.getElementById("0").focus();
<?}?>

function js_verifica( campo, codigo, avaliacao, qtdlinha ) {
  
  proximo = parseFloat(campo.id)+1;
  if ( proximo < qtdlinha ) {
    camposeguinte = proximo;
  } else {
    camposeguinte = 0;
  }
  
  var expr = new RegExp("[^0-9]+");
  if ( campo.value.match( expr ) ) {
    
    alert("Aulas dadas deve ser um número inteiro!");
    campo.value = "";
    campo.focus();
  } else {
    
    document.getElementById("aguarde").style.visibility = "visible";
    location.href = "edu1_regenciaperiodo001.php?regencia=<?=$regencia?>"
                                              +"&nabas=<?=$nabas?>"
                                              +"&aulasdadas="+campo.value
                                              +"&codigo="+codigo
                                              +"&camposeguinte="+camposeguinte
                                              +"&avaliacao="+avaliacao
                                              +"&alterar";
  }
}
</script>
<div id="aguarde" style="visibility:hidden; position:absolute; left:300px; top:150px;">
  <table border="0" width="200" height="50">
    <tr>
      <td bgcolor="#DEB887" align="center" style="border:1px outset #666666; text-decoration:blink;">
        <b>Salvando...</b>
      </td>
    </tr>
  </table>
</div>