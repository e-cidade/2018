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

$clcalendario        = new cl_calendario;
$clferiado           = new cl_feriado;
$clevento            = new cl_evento;
$clperiodocalendario = new cl_periodocalendario;
$db_opcao            = 1;
$db_botao            = true;
$nomeescola          = db_getsession("DB_nomedepto");
$escola              = db_getsession("DB_coddepto");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
    .cabec{
    font-size: 11;
    font-weight: bold;
    color: #DEB887;
    background-color:#444444;
    border:1px solid #CCCCCC;
    }
    </style>
  </head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
   <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
   </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="center" valign="top" bgcolor="#CCCCCC">
     <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
     <br>
     <form name="form1" method="post" action="">
     <fieldset style="width:95%"><legend><b>Consulta de Calendário</b></legend>
      <?
      $result = $clcalendario->sql_record($clcalendario->sql_query_calturma("","ed52_i_codigo,ed52_c_descr,ed52_i_ano","ed52_i_ano desc"," ed38_i_escola = $escola AND ed52_c_passivo = 'N'"));?>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
       <tr>
        <td align="left" valign="top">
         <b>Selecione o Calendário:</b>
         <select name="calendario" style="font-size:9px;width:200px;height:18px;">
          <option value=""></option>
          <?
          for($i=0;$i<$clcalendario->numrows;$i++) {
           db_fieldsmemory($result,$i);
           $selected = isset( $calendario ) && $ed52_i_codigo == $calendario ? "selected" : "";
           echo "<option value='$ed52_i_codigo' $selected>$ed52_c_descr</option>\n";
          }
          ?>
         </select>
         <input type="button" value="Processar" onclick="js_processar(document.form1.calendario.value)">
        </td>
       </tr>
      </table>
     </fieldset>
     </form>
    </td>
   </tr>
  </table>
<?
if (isset($calendario)) {

 $array_mes = array("1"=>"JAN","2"=>"FEV","3"=>"MAR","4"=>"ABR","5"=>"MAI","6"=>"JUN","7"=>"JUL","8"=>"AGO","9"=>"SET","10"=>"OUT","11"=>"NOV","12"=>"DEZ",);
 $array_cor = array("0"=>"#FFE1C4","1"=>"#DFFFDF","2"=>"#D2FFFF","3"=>"#FFD7D7");
 $result = $clcalendario->sql_record($clcalendario->sql_query("","extract(month from ed52_d_inicio) as minimo,extract(month from ed52_d_fim) as maximo, ed52_i_ano, ed52_d_inicio, ed52_d_fim, ed52_i_diasletivos",""," ed52_i_codigo = $calendario"));
 db_fieldsmemory($result,0);
 $result1 = $clperiodocalendario->sql_record($clperiodocalendario->sql_query("","ed09_c_abrev,ed53_d_inicio,ed53_d_fim","ed09_i_sequencia"," ed53_i_calendario = $calendario AND ed09_c_somach = 'S'"));
 ?>
 <table width="97%" border="1" cellspacing="0" cellpadding="0" align="center">
  <tr>
   <td width='40' class="cabec" align="center">MÊS</td>
   <?for($x=1;$x<=31;$x++){
    echo "<td align='center' class='cabec' width='20'>$x</td>";
   }?>
   <td class="cabec" align="center">T</td>
  </tr>
  <?

  for($x=$minimo;$x<=$maximo;$x++){
   $contmes = 0;
   ?>
   <tr height="35">
    <td class="cabec" align="center">
     <?=$array_mes[$x]?>
    </td>
    <?
     for($z=1;$z<=31;$z++){
      $data = $ed52_i_ano."-".(strlen($x)==1?"0".$x:$x)."-".(strlen($z)==1?"0".$z:$z) ;
      if(checkdate($x,$z,$ed52_i_ano)==true){
       $tem = false;
       if($data>=$ed52_d_inicio && $data<=$ed52_d_fim){
        for($c=0;$c<$clperiodocalendario->numrows;$c++){
         db_fieldsmemory($result1,$c);
         if($data>=$ed53_d_inicio && $data<=$ed53_d_fim){
          $cor = $array_cor[$c];
          $tem = true;
          break;
         }
        }
        if($tem==false){
         $cor = "#CCCCCC";
        }
       }else{
        $cor = "#CCCCCC";
       }
      }else{
       $cor = "#CCCCCC";
      }
      ?>
      <td align='center' bgcolor="<?=$cor?>" >
       <?php
        if(checkdate($x,$z,$ed52_i_ano)==true) {

          if (date("w",mktime(0,0,0,$x,$z,$ed52_i_ano))==0) {

            $result2 = $clferiado->sql_record($clferiado->sql_query("","*",""," ed54_i_calendario = $calendario AND ed54_d_data = '$data'"));
            if($clferiado->numrows>0) {

              for( $y = 0; $y < $clferiado->numrows;$y++) {

                db_fieldsmemory($result2,$y);
                echo $ed96_c_abrev."<br>";
                if($ed54_c_dialetivo=="S"){

                  echo "*";
                  $contmes++;
                }
              }

            } else {
              echo "D";
            }
          } elseif(date("w",mktime(0,0,0,$x,$z,$ed52_i_ano)) == 6) {

            $result2 = $clferiado->sql_record($clferiado->sql_query("","*",""," ed54_i_calendario = $calendario AND ed54_d_data = '$data'"));

            if($clferiado->numrows>0){

              for ($y = 0; $y < $clferiado->numrows;$y++) {

                db_fieldsmemory($result2,$y);
                echo $ed96_c_abrev."<br>";
                if($ed54_c_dialetivo=="S") {

                  echo "*";
                  $contmes++;
                }
              }
            } else {
             echo "S";
            }
          } else {

            $result2 = $clferiado->sql_record($clferiado->sql_query("","*",""," ed54_i_calendario = $calendario AND ed54_d_data = '$data'"));
            if ($clferiado->numrows > 0) {

              $lFeriadoLetivo = false;
              for($y=0;$y<$clferiado->numrows;$y++) {

                db_fieldsmemory($result2,$y);
                echo $ed96_c_abrev."<br>";
                if($ed54_c_dialetivo=="S"){

                  echo "*";
                  $contmes++;
                }
              }

            } else {

              if($data>=$ed52_d_inicio && $data<=$ed52_d_fim && $tem==true){

                echo "*";
                $contmes++;
              }else{
                echo "RE";
              }
            }
          }
        } else {
          echo "&nbsp;";
        }?>
      </td>
     <?
     }
     ?>

     <td width='40' bgcolor="#f3f3f3" align="center" style="font-size:16px;font-weight:bold;"><?=strlen($contmes)==1?"0".$contmes:$contmes?></td>
   </tr>
   <?
  }
  ?>
  <tr>
   <td colspan="9"><b>LEGENDA:</b><br></td>
   <td colspan="11"><b>SÁB e DOM LETIVOS:</b><br></td>
   <td colspan="9"><b>PERÍODOS:</b><br></td>
   <td colspan="4" bgcolor="#f3f3f3" align="right" style="font-size:16px;font-weight:bold;" valign="top">
     Total &nbsp;&nbsp;&nbsp;<?=$ed52_i_diasletivos?></td>
  </tr>
  <tr>
   <td valign="top" colspan="9">
    <?
    $result3 = $clevento->sql_record($clevento->sql_query("","*","ed96_c_abrev",""));
    for($e=0;$e<$clevento->numrows;$e++){
     db_fieldsmemory($result3,$e);
     echo $ed96_c_abrev." - ".$ed96_c_descr."<br>";
    }
    ?>
   </td>
   <td valign="top" colspan="11">
    <?
    $result3 = $clferiado->sql_record($clferiado->sql_query("","*","ed54_d_data"," ed54_i_calendario = $calendario AND ed54_c_dialetivo = 'S' AND extract(DOW FROM ed54_d_data) in ('6','0')"));
    for($e=0;$e<$clferiado->numrows;$e++) {

      db_fieldsmemory($result3,$e);
      echo db_formatar($ed54_d_data,'d')." - ".$ed54_c_descr."<br>";
    }

    ?>
   </td>
   <td valign="top" colspan="13">
    <?
    for($c=0;$c<$clperiodocalendario->numrows;$c++){
     db_fieldsmemory($result1,$c);
     ?>
     <table>
      <tr>
       <td width="30" bgcolor="<?=$array_cor[$c]?>" style="border:1px solid #000000;">
        &nbsp;
       </td>
       <td>
        <?=$ed09_c_abrev?> - <?=db_formatar($ed53_d_inicio,'d')?> até <?=db_formatar($ed53_d_fim,'d')?>
       </td>
      </tr>
     </table>
     <?
    }
    ?>
   </td>
  </tr>
 </table>
<?}?>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_processar(calendario){
 if(calendario!=""){
  location.href = "edu3_calendario001.php?calendario="+calendario;
 }
}
<?if(!isset($calendario) && pg_num_rows($result)>0){?>
 document.form1.calendario.options[1].selected = true;
<?}?>
</script>