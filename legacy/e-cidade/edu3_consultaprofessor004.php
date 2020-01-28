<?
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

db_postmemory($HTTP_POST_VARS);
$cldiasemana         = new cl_diasemana;
$clperiodoescola     = new cl_periodoescola;
$clregenciahorario   = new cl_regenciahorario;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$clrechumanoativ     = new cl_rechumanoativ;
$clescola            = new cl_escola;

$db_opcao = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" method="post" action="" >
<table width="98%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Horários Recurso Humano</b></legend>
    <table border="0" width="100%">
     <tr>
      <td nowrap>
       <b>Escola:</b>
      </td>
      <td>
       <?
       $sql = "SELECT DISTINCT ed18_i_codigo,ed18_c_nome
                 FROM regenciahorario
                      inner join regencia on ed59_i_codigo = ed58_i_regencia
                      inner join turma    on ed57_i_codigo = ed59_i_turma
                      inner join escola   on ed18_i_codigo = ed57_i_escola
                WHERE ed58_i_rechumano = {$chavepesquisa}
                  and ed58_ativo is true
                ORDER BY ed18_c_nome DESC";
       $result = db_query($sql);
       $linhas = pg_num_rows($result);
       if(!isset($escola) && $linhas>0){
         $escola = pg_result( $result, 0, 'ed18_i_codigo' );
       }
       ?>
       <select name="escola"
               style="font-size:9px;width:300px;height:18px;"
               onchange="location.href='edu3_consultaprofessor004.php?chavepesquisa=<?=$chavepesquisa?>&escola='+document.form1.escola.value+'&ano='+document.form1.ano.value">
       <?
       for( $x = 0; $x < $linhas; $x++ ) {

         db_fieldsmemory( $result, $x );
         ?>
         <option value="<?=$ed18_i_codigo?>" <?=@$ed18_i_codigo == @$escola ? "selected" : ""?>><?=$ed18_c_nome?></option>
         <?
       }
       ?>
      </select>
      </td>
      <td nowrap>
       <b>Ano:</b>
      </td>
      <td>
      <select name="ano"
              style="font-size:9px;width:150px;height:18px;"
              onchange="location.href='edu3_consultaprofessor004.php?chavepesquisa=<?=$chavepesquisa?>&escola='+document.form1.escola.value+'&ano='+document.form1.ano.value">
       <?
       $sql1 = "SELECT DISTINCT ed52_i_ano
                  FROM regenciahorario
                       inner join regencia         on ed59_i_codigo     = ed58_i_regencia
                       inner join turma            on ed57_i_codigo     = ed59_i_turma
                       inner join calendario       on ed52_i_codigo     = ed57_i_calendario
                       inner join calendarioescola on ed38_i_calendario = ed52_i_codigo
                 WHERE ed58_i_rechumano = {$chavepesquisa}
                   and ed58_ativo is true
                 ORDER BY ed52_i_ano DESC";
       $result1 = db_query($sql1);
       $linhas1 = pg_num_rows($result1);
       if( !isset( $ano ) && $linhas1 > 0 ) {
         $ano = pg_result( $result1, 0, 'ed52_i_ano' );
       }

       for( $x = 0; $x < $linhas1; $x++ ) {

         db_fieldsmemory( $result1, $x );
         ?>
         <option value="<?=$ed52_i_ano?>" <?=@$ed52_i_ano == @$ano ? "selected" : ""?>><?=$ed52_i_ano?></option>
         <?
       }

       if( $linhas == 0 || $linhas1 == 0 ) {
         $escola = 0;
       }
       ?>
      </select>
      </td>
     </tr>
    </table>
    <table cellspacing="0" cellpading="0" border="1" bordercolor="#000000" width="100%">
      <?
      $turno   = "";
      $sql     = $clperiodoescola->sql_query( "", "*", "ed15_i_sequencia, ed08_i_sequencia", "ed17_i_escola = {$escola}");
      $result1 = $clperiodoescola->sql_record( $sql );
      $contp   = 0;
      $contd   = 0;

      if( $clperiodoescola->numrows == 0 ) {
        echo "<tr><td align='center'><br><b>Nenhum registro de horários para este professor.</b><br><br></td></tr>";
      } else {

        for( $z = 0; $z < $clperiodoescola->numrows; $z++ ) {

          db_fieldsmemory( $result1, $z );
          $contp++;

          if( $turno != $ed15_c_nome ) {

          ?>
            <tr bgcolor="#444444">
              <td width="40" align="center" style="font-weight: bold; color: #DEB887;">
                <?=pg_result($result1,$z,"ed15_c_nome");?>
              </td>
          <?

              $sWhereDiaSemana = "ed04_c_letivo = 'S' AND ed04_i_escola = {$escola}";
              $sSqlDiaSemana   = $cldiasemana->sql_query_rh( "", "*", "ed32_i_codigo", $sWhereDiaSemana );
              $result          = $cldiasemana->sql_record( $sSqlDiaSemana );
            if( $cldiasemana->numrows == 0 ) {
            ?>
              <tr>
                <td>
                  <a href="javascript:parent.location.href='edu1_diasemanaabas001.php'">
                    <b>Informe os dias lelivos desta escola</b>
                  </a>
                </td>
              </tr>
            <?
            }
            for( $x = 0; $x < $cldiasemana->numrows; $x++ ) {

              $contd++;
              db_fieldsmemory( $result, $x );
            ?>
              <td align="center">
                <table cellspacing="0" cellpading="0" >
                  <tr>
                    <td align="center" width="120" style="font-weight: bold; color: #DEB887;">
                      <div align="center"><?=$ed32_c_abrev?></div>
                    </td>
                  </tr>
                </table>
              </td>
            <?}?>
            </tr>
          <?php
          }

          $turno = $ed15_c_nome?>
          <td align="center" height="60" style="font-weight: bold; background-color: #f3f3f3;">
            <?=$ed17_h_inicio?>
          </td>
          <?php
            for( $x = 0; $x < $cldiasemana->numrows; $x++ ) {
              $quadro = "Q".$z.$x;
              db_fieldsmemory( $result, $x );
              $sql2 ="SELECT ed232_c_descr,ed11_c_descr,ed57_c_descr,ed20_i_codigo
                        FROM regenciahorario
                             inner join regencia          on regencia.ed59_i_codigo             = regenciahorario.ed58_i_regencia
                             inner join rechumano         on rechumano.ed20_i_codigo            = regenciahorario.ed58_i_rechumano
                             inner join disciplina        on disciplina.ed12_i_codigo           = regencia.ed59_i_disciplina
                             inner join caddisciplina     on ed232_i_codigo                     = ed12_i_caddisciplina
                             inner join turma             on turma.ed57_i_codigo                = regencia.ed59_i_turma
                             inner join calendario        on calendario.ed52_i_codigo           = turma.ed57_i_calendario
                             inner join serie             on serie.ed11_i_codigo                = turma.ed57_i_serie
                             left  join rechumanopessoal  on rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo
                             left  join rhpessoal         on rhpessoal.rh01_regist              = rechumanopessoal.ed284_i_rhpessoal
                             left  join cgm as cgmrh      on cgmrh.z01_numcgm                   = rhpessoal.rh01_numcgm
                             left  join rechumanocgm      on rechumanocgm.ed285_i_rechumano     = rechumano.ed20_i_codigo
                             left  join cgm as cgmcgm     on cgmcgm.z01_numcgm                  = rechumanocgm.ed285_i_cgm
                       WHERE ed58_i_diasemana = {$ed32_i_codigo}
                         and ed58_ativo is true
                         AND ed52_i_ano = {$ano}
                         AND ed58_i_periodo = {$ed17_i_codigo}
                         AND ed58_i_rechumano = {$chavepesquisa}
                         AND ed57_i_escola = {$escola}";

              $result2 = db_query($sql2);
              $linhas2 = pg_num_rows($result2);

              if( $linhas2 > 0 ) {

                db_fieldsmemory( $result2, 0 );
                $str_turma = "Turma: <font color='red'>".$ed57_c_descr."</font>";
                $str_serie = "Etapa: <font color='red'>".$ed11_c_descr."</font>";
                $str_disci = $ed232_c_descr;
                $cor       = "red";
              } else {

                $str_turma = "&nbsp";
                $str_disci = "&nbsp";
                $str_serie = "&nbsp";
                $cor       = "green";
              }
            ?>
              <td width="130" style="font-size:11px;background:#DBDBDB;" align="center">
                <b><?=$str_disci?></b><br>
                <?=$str_turma?><br>
                <?=$str_serie?>
              </td>
            <?
              $ed20_i_codigo = "";
            }
         ?>
         <tr>
        <?
        }
      }
      ?>
     </tr>
    </table>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</form>
</body>
</html>