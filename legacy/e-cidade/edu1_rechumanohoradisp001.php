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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($_POST);

$cldiasemana         = new cl_diasemana;
$clperiodoescola     = new cl_periodoescola;
$clrechumanohoradisp = new cl_rechumanohoradisp;
$clrechumano         = new cl_rechumano;
$clrechumanoativ     = new cl_rechumanoativ;
$clrechumanoescola   = new cl_rechumanoescola;
$db_opcao            = 1;
$db_botao            = true;
$escola              = db_getsession("DB_coddepto");

$clrechumanoescola->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed75_c_simultaneo");
if (isset($atualizar)) {
	
  db_inicio_transacao();	
  $clrechumanoescola->ed75_c_simultaneo = $ed75_c_simultaneo;
  $clrechumanoescola->ed75_i_codigo     = $ed75_i_codigo;
  $clrechumanoescola->alterar($ed75_i_codigo);  
  db_fim_transacao();
}

$sCampos    = "min(ed17_h_inicio) as menorhorario,max(ed17_h_fim) as maiorhorario";
$result_per = $clperiodoescola->sql_record($clperiodoescola->sql_query( "", $sCampos, "", "" ) );

db_fieldsmemory( $result_per, 0 );

$hora1         = (int)substr($menorhorario,0,2);
$hora2         = (int)substr($maiorhorario,0,2)+1;
$horainicial   = $hora1*100;
$horafinal     = $hora2*100;
$tempo_ini     = mktime($hora1,0,0,date("m"),date("d"),date("Y"));
$tempo_fim     = mktime($hora2,0,0,date("m"),date("d"),date("Y"));
$difer_minutos = ($tempo_fim-$tempo_ini)/60;
$alt_tab_hora  = $difer_minutos/2;
$qtd_hora      = $difer_minutos/60;
$larg_tabela   = @$larg_obj;
$larg_coluna1  = 40;
$larg_coluna2  = 40;
$tabela1_top   = 27;
$tabela1_left  = 2;

$sCampos   = "case when ed20_i_tiposervidor = 1 ";
$sCampos  .= "     then ed284_i_rhpessoal ";
$sCampos  .= "     else ed285_i_cgm ";
$sCampos  .= " end as identificacao, ";
$sCampos  .= "case when ed20_i_tiposervidor = 1 ";
$sCampos  .= "     then cgmrh.z01_nome ";
$sCampos  .= "     else cgmcgm.z01_nome ";
$sCampos  .= " end as z01_nome, ";
$sCampos  .= "case when ed20_i_tiposervidor = 1 ";
$sCampos  .= "     then cgmrh.z01_numcgm ";
$sCampos  .= "     else cgmcgm.z01_numcgm ";
$sCampos  .= " end as z01_numcgm, ";
$sCampos  .= "ed20_i_tiposervidor ";
$result11  = $clrechumano->sql_record( $clrechumano->sql_query( "", $sCampos, "", "ed20_i_codigo = {$ed20_i_codigo}" ) );

db_fieldsmemory( $result11, 0 );

$oDaoRecHumanoEscola   = new cl_rechumanoescola();
$sWhereRecHumanoEscola = "ed75_i_rechumano = {$ed20_i_codigo} AND ed75_i_escola = {$escola} AND ed75_i_saidaescola is null";
$sSqlRecHumanoEscola   = $oDaoRecHumanoEscola->sql_query_file( null, "ed75_i_codigo", null, $sWhereRecHumanoEscola );
$rsRecHumanoEscola     = db_query( $sSqlRecHumanoEscola );

if( $rsRecHumanoEscola && pg_num_rows( $rsRecHumanoEscola ) > 0 ) {
  db_fieldsmemory( $rsRecHumanoEscola, 0 );
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
if (!isset($larg_obj)) {
  ?>
  <script>
   largura = document.body.clientWidth;
   location.href = "edu1_rechumanohoradisp001.php?ed20_i_codigo=<?=$ed20_i_codigo?>&larg_obj="+largura;
  </script>
  <?php
  exit;
}
unset($_SESSION["sess_cordisp"]);
$array_cores = array( "#CCCCFF", "#99FFCC", "#CCFF66", "#CC9933", "#FF99FF", "#996699", "#66CC99", "#FFCCCC", "#9999FF" );

$sess_cordisp          = array();
$sCampos               = "DISTINCT ed18_i_codigo, ed18_c_nome";
$sWhere                = "ed20_i_codigo = {$ed20_i_codigo} AND ed33_ativo = 't' AND ed75_i_saidaescola is null";
$sSqlRecHumanoHoraDisp = $clrechumanohoradisp->sql_query_disponibilidade("", $sCampos, "ed18_c_nome", $sWhere );
$result_cor            = $clrechumanohoradisp->sql_record( $sSqlRecHumanoHoraDisp );

if ($clrechumanohoradisp->numrows > 0) {

  $iPosicaoCores = 0;
  for ($y = 0; $y < $clrechumanohoradisp->numrows; $y++) {
  	
    db_fieldsmemory($result_cor,$y);

    if( $ed18_i_codigo == $escola ) {
      $sess_cordisp[$ed18_i_codigo] = "#FFCC99";
    } else {

      $sess_cordisp[$ed18_i_codigo] = $array_cores[$iPosicaoCores];
      $iPosicaoCores++;
    }
  }

  session_register("sess_cordisp");
}
?>
<form method="post" name="form2">
<table border="0">
<tr>
<td>
&nbsp;&nbsp;<?db_input('ed20_i_codigo',15,@$Ied20_i_codigo,true,'hidden',3,"")?> 
<b><?=@$ed20_i_tiposervidor=='1'?'Matrícula:':'CGM:'?></b>
<?php
db_input( 'identificacao', 10, @$identificacao, true, 'text', 3 );
db_input( 'z01_nome',      50, @$Iz01_nome,     true, 'text', 3 );

$sWhereRecHumanoAtiv  = "     ed20_i_codigo = {$ed20_i_codigo} and ed01_c_regencia='S' and ed75_i_escola = {$escola}";
$sWhereRecHumanoAtiv .= " and ed75_i_saidaescola is null";
$sSqlRecHumanoAtiv    = $clrechumanoativ->sql_query("", "ed75_c_simultaneo, ed75_i_codigo", "", $sWhereRecHumanoAtiv );
$result_atividade     = $clrechumanoativ->sql_record( $sSqlRecHumanoAtiv );

if ($clrechumanoativ->numrows > 0) {
	
  db_fieldsmemory($result_atividade,0);
	?>
   <td nowrap title="<?=@$Ted75_c_simultaneo?>">
    <?=@$Led75_c_simultaneo?>
   </td>
   <td>
    <?
      $x = array('N'=>'NÃO','S'=>'SIM');
      db_select('ed75_c_simultaneo',$x,true,$db_opcao,"");
    ?>
   </td>
   
<?}?> 
  <td>
<?db_input('ed75_i_codigo',15,@$Ied75_i_codigo,true,'hidden',3,"")?> 
<input type="button" name="atualizar" value="Atualizar" onclick="js_atualizar();">  
</td>
</tr>
</table>
</form>
<table border='0' id="id_dia" style="position:absolute;top:<?=$tabela1_top?>px;left:<?=$tabela1_left?>px;" 
       cellspacing="1" cellpadding="0">
 <tr height="20">
  <td align="center" width="<?=$larg_coluna1?>" style="background:#444444;color:#DEB887">
      <a style="color:#DEB887;"><b>Horas</b>
  </td>
  <td align="center" width="<?=$larg_coluna2?>" style="background:#444444;color:#DEB887">
      <a style="color:#DEB887;">&nbsp;
  </td>
  <?
  $result = $cldiasemana->sql_record($cldiasemana->sql_query_rh("",
                                                                "ed32_i_codigo,ed32_c_abrev,ed32_c_descr",
                                                                "ed32_i_codigo",
                                                                " ed04_i_escola = $escola AND ed04_c_letivo = 'S'"
                                                               )
                                    );
  $larg_dia = floor(($larg_tabela-$larg_coluna1-$larg_coluna2)/$cldiasemana->numrows);
  for( $x = 0; $x < $cldiasemana->numrows; $x++) {
  	
    db_fieldsmemory($result,$x)
    ?>
     <td align="center" width="<?=$larg_dia?>" style="background:#444444;color:#DEB887">
      <a style="color:#DEB887;" href="javascript:js_incluir('<?=$ed32_i_codigo?>','<?=trim($ed32_c_descr)?>')">
         <b><?=$ed32_c_descr?></b>
      </a>
     </td>
    <?
  }
  ?>
 </tr>
</table>
<!--Tabela de fundo-->
<table border ='0' id="id_fundo" style=";position:absolute;top:<?=$tabela1_top+25?>px;left:<?=$tabela1_left+$larg_coluna1?>px;"
       cellspacing="0" cellpadding="0">
 <?for ($x = 0; $x < $qtd_hora; $x++) {?>

     <tr bgcolor="#f3f3f3">
      <td align="center" width="<?=$larg_coluna2?>" height="<?=$alt_tab_hora/$qtd_hora?>"
          style="border:1px solid #f3f3f3;">&nbsp;</td>
      <td width="<?=$larg_dia*$cldiasemana->numrows?>" height="<?=$alt_tab_hora/$qtd_hora?>"
          style="border:1px solid #f3f3f3;">&nbsp;</td>
     </tr>

 <?}?>
</table>
<?
////////Grade dos horários
$top_ini = $tabela1_top+20;
$tt      = 0;
for ($t = $horainicial; $t <= $horafinal; $t+=1) {

  $hora         = strlen($t)==3?"0".$t:$t;
  $hora         = substr($hora,0,2).":".substr($hora,2,2);
  $id_hora      = "H".$hora;
  $id_hora2     = "HH".$hora;
  $id_linhahora = "LH".$hora;

  if ($t != 2400) {

    if (($t%100) == 0) {
      $visible = "visible";
    }else{
      $visible = "hidden";
    }
    echo "<div id='$id_hora' style='visibility:$visible;position:absolute;top:".($top_ini).
               "px;left:".($larg_coluna1+$tabela1_left-38)."px;'><b>".$hora."</b></div>";
    echo "<div id='$id_hora2' style='color:#FF0000;visibility:hidden;position:absolute;top:".($top_ini).
               "px;left:".($larg_coluna1+$larg_coluna2+$tabela1_left-38)."px;'><b>".$hora."</b></div>";
    echo "<div id='$id_linhahora' style='width:".($larg_dia*$cldiasemana->numrows+$larg_coluna2).
               "px;height:1px;background:#FF0000;visibility:hidden;position:absolute;top:".($top_ini+5).
               "px;left:".($larg_coluna1+$tabela1_left)."px;'></div>";

  }

  $tt += 1;
  if ($tt == 60) {

    $t += 40;
    $tt = 0;

  }
  $top_ini += 0.5;
}
////////Linhas verticais
$left_ini = $tabela1_left+$larg_coluna1+$larg_coluna2;
for ($x = 0; $x < $cldiasemana->numrows; $x++) {

  ?>
  <table border="0" style=";position:absolute;top:<?=$tabela1_top+25?>px;left:<?=$left_ini?>px;"
         cellspacing="0" cellpadding="0">
   <tr>
    <td width="1" bgcolor="#000000" height="<?=$alt_tab_hora?>"></td>
   </tr>
  </table>
  <?
  $left_ini+=$larg_dia;

}
////////Disponibilidade do Docente
?>
<tbody id="disp_rechumano"><tr><td>
<?
$ini_left = $tabela1_left+$larg_coluna1+$larg_coluna2;
for ($x = 0; $x < $cldiasemana->numrows; $x++) {

  $ini_top = $tabela1_top+25;
  db_fieldsmemory($result,$x);
  $sCampos  = " ed20_i_codigo,";
  $sCampos .= "case ";
  $sCampos .= "     when ed20_i_tiposervidor = 1 ";
  $sCampos .= "     then 'Matrícula: '||rechumanopessoal.ed284_i_rhpessoal ";
  $sCampos .= "     else 'CGM: '||rechumanocgm.ed285_i_cgm ";
  $sCampos .= " end as identificacao, ";
  $sCampos .= "  ed33_i_codigo,ed08_c_descr,ed18_c_nome,ed15_c_nome,ed17_h_inicio,ed17_h_fim,ed18_i_codigo as ed17_i_escola, ";
  $sCampos .= "  ed128_abreviatura, ed128_descricao, ed33_horaatividade ";
  $sOrder   = "ed33_i_diasemana,ed17_h_inicio asc,ed17_h_fim asc";
  $sWhere   = " ed20_i_codigo = {$ed20_i_codigo} AND ed33_i_diasemana = {$ed32_i_codigo}";
  $sWhere  .= " AND ed33_ativo = 't' AND ed75_i_saidaescola is null";

  $sSqlRecHumanoHoraDisp = $clrechumanohoradisp->sql_query_tipohoratrabalho( "", $sCampos, $sOrder, $sWhere );
  $result1               = $clrechumanohoradisp->sql_record( $sSqlRecHumanoHoraDisp );
  $tt = 0;
  for ($t = $horainicial; $t <= $horafinal; $t += 1) {

    $hora = strlen( $t ) == 3 ? "0" . $t : $t;

    if( $hora < 0100 ) {

      if( $t < 10 ) {
        $hora = "00:0{$t}";
      } else {
        $hora = "00:{$t}";
      }
    } else {
      $hora = substr( $hora, 0, 2 ) . ":" . substr( $hora, 2, 2 );
    }

    if ($clrechumanohoradisp->numrows > 0) {

      for ($y = 0; $y < $clrechumanohoradisp->numrows; $y++) {

        db_fieldsmemory($result1,$y);
        if (trim($hora) == trim($ed17_h_inicio)) {

          $tempo_ini = mktime(substr($ed17_h_inicio,0,2),substr($ed17_h_inicio,3,2),0,1,1,1999);
          $tempo_fim = mktime(substr($ed17_h_fim,0,2),substr($ed17_h_fim,3,2),0,1,1,1999);
          $difermin  = ($tempo_fim-$tempo_ini)/60;
          $difer     = ceil($difermin/2);
         ?>
         <table id="tab<?=$ed33_i_codigo?>" width="<?=$larg_dia ?>" border="0" height="<?=$difer?>"
                style="background:<?=$_SESSION["sess_cordisp"][$ed17_i_escola]?>;border:1px outset #000000;position:absolute;
                       top:<?=$ini_top?>px;left:<?=$ini_left?>px;" cellspacing="0" cellpadding="0">
          <tr>
         <?php
         if ($ed17_i_escola == $escola) {

           $sHoraAtividade = $ed33_horaatividade == 't' ? 'SIM' : 'NÃO';
         ?>

             <td onclick="js_marca('<?=$ed33_i_codigo?>','<?=$_SESSION["sess_cordisp"][$ed17_i_escola]?>')"
                 style="cursor:pointer;font-size:10px;" align="center"
                 onmouseover="js_Mover('tab<?=$ed33_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>',
                                          '<?=$ed17_i_escola?>','<?=$ed18_c_nome?>','<?=$ed08_c_descr?>',
                                          '<?=$ed15_c_nome?>','<?=$_SESSION["sess_cordisp"][$ed17_i_escola]?>',
                                          '<?=$identificacao?>','<?=$ed128_abreviatura?>', '<?=$sHoraAtividade?>')"
                 onmouseout="js_Mout('tab<?=$ed33_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>')">

          <?} else {?>

              <td style="font-size:10px;" align="center"
                  onmouseover="js_Mover('tab<?=$ed33_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>',
                                            '<?=$ed17_i_escola?>','<?=$ed18_c_nome?>','<?=$ed08_c_descr?>',
                                            '<?=$ed15_c_nome?>','<?=$_SESSION["sess_cordisp"][$ed17_i_escola]?>',
                                            '<?=$identificacao?>','')"
                  onmouseout="js_Mout('tab<?=$ed33_i_codigo?>','<?=$ed17_h_inicio?>','<?=$ed17_h_fim?>')">

          <?}?>

             Escola: <?=$ed17_i_escola?> -> <?=$ed17_h_inicio?> às <?=$ed17_h_fim?>
             <input type="hidden" id="input<?=$ed33_i_codigo?>" value="" size="5">
           </td>
          </tr>
         </table>
        <?
        
        }
      }
    }
    $tt += 1;
    if ($tt == 60) {
    	
      $t += 40;
      $tt = 0;
      
    }
    $ini_top += 0.5;
  }
  $ini_left += $larg_dia;
}
?>
</td></tr></tbody>
<table width="150" style="position:absolute;top:<?=$tabela1_top+20?>px;left:<?=$larg_obj-150?>px;" 
       cellspacing="2" cellpadding="4">
 <tr>
  <td id="tab_descr" bgcolor="#FFFFCC" style="visibility:hidden;border:1px solid #000000;">  
  </td>
 </tr>
</table>
<table width="300" height="20" style="position:absolute;top:<?=$tabela1_top+20?>px;left:<?=($larg_obj/2)-150?>px;" 
       cellspacing="2" cellpadding="4">
 <tr>
  <td align="center" id="tab_excluir" bgcolor="#FF8080" style="visibility:hidden;border:1px solid #000000;">
   Excluir registros marcados -> <input type="button" id="excluir" value="Excluir" onclick="js_excluir();">
  </td>
 </tr>
</table>
<input type="hidden" id="cod_horario" value="" style="position:absolute;top:600px;">
<form method="post" name="form1">
<table width="<?=$larg_dia*$cldiasemana->numrows+$larg_coluna2?>" height="<?=$alt_tab_hora?>" id="inc_periodo" 
       style="visibility:hidden;position:absolute;border:2px outset #000000;top:<?=$tabela1_top+25?>px;
       left:<?=$tabela1_left+$larg_coluna1?>px;" bgcolor="#CCCCCC" cellspacing="2" cellpadding="2">
 <tr>
  <td valign="top" height="20">
   <table width="100%" cellspacing="0" cellpadding="0" style="border:2px outset #000000;">
    <tr bgcolor="blue" >
     <td style="color:#FFFFFF;font-weight:bold;">
      &nbsp;&nbsp;Incluir Disponibilidade:
     </td>
     <td width="10%" align="right" style="color:#FFFFFF;font-weight:bold;">
      <img src="imagens/jan_fechar_off.jpg" align="center" 
           onclick="document.getElementById('inc_periodo').style.visibility='hidden';
                    document.getElementById('corpo_diasemana').innerHTML='';
                    document.getElementById('corpo_periodo').innerHTML='';
                    document.getElementById('corpo_turno').innerHTML='';">
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td valign="top">
   <table border="0">
    <tbody id="corpo_diasemana">
    </tbody>
    <tbody id="corpo_turno">
    </tbody>
   </table>
  </td>
 </tr>
</table>
<table width="400" height="300" id="inc_variosperiodos" 
       style="visibility:hidden;position:absolute;border:2px outset #000000;top:100px;left:<?=($larg_obj/2)-200?>px;" 
       bgcolor="#CCCCCC" cellspacing="2" cellpadding="2">
 <tr>
  <td valign="top" height="20">
   <table width="100%" cellspacing="0" cellpadding="0" style="border:2px outset #000000;">
    <tr bgcolor="blue" >
     <td style="color:#FFFFFF;font-weight:bold;">
      &nbsp;&nbsp;Incluir Período(s) para outros dias da semana:
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td valign="top">
   <table border="0">
    <tbody id="corpo_outrosdias">
    </tbody>
   </table>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>

const MENSAGEM_RECHUMANOHORADISP001 = "educacao.escola.edu1_rechumanohoradisp001.";

function js_atualizar() {
  location.href='edu1_rechumanohoradisp001.php?ed20_i_codigo=<?=$ed20_i_codigo?>'+
                 '&larg_obj=<?=$larg_obj?>&ed75_c_simultaneo='+document.form2.ed75_c_simultaneo.value+
                 '&ed75_i_codigo=<?=@$ed75_i_codigo?>&atualizar=1';
}

function js_Mover(  quadro, horaini, horafim, escola, nomeescola, periodo, turno, cor, matricula
                   , sAbreviaturaTipoHora, sHoraAtividade ) {
	
  texto  = "<b>"+matricula+"</b><br>Turno: <b>"+turno+"</b><br>Período: <b>"+periodo;
  texto += "</b><br>Hora Inicial: <b>"+horaini+"</b><br>Hora Final: <b>" +horafim + "</b>";

  if ( !empty(sAbreviaturaTipoHora) ) {
    texto += "<br>Tipo de Hora: <b>"+ sAbreviaturaTipoHora;
  }

  if ( !empty( sHoraAtividade ) ) {
    texto += "</b><br>Hora Atividade: <b>"+ sHoraAtividade;
  }

  texto += "</b><br>Escola: <b>"+escola;
  texto += " - "+nomeescola+"</b><br>";

  document.getElementById("tab_descr").style.visibility = "visible";
  document.getElementById("tab_descr").style.background = cor;
  document.getElementById("tab_descr").innerHTML        = texto;
  document.getElementById(quadro).style.border          = "1px inset";
  tt = 0;
  
  for (t = <?=$horainicial?>; t <= <?=$horafinal?>; t += 1) {
	   
    hora         = new String(t);
    hora         = hora.length==3?"0"+hora:hora;
    hora         = hora.substr(0,2)+":"+hora.substr(2,2);
    id_hora      = "HH"+hora;
    id_linhahora = "LH"+hora;
    
    if (t != 2400) {
        
      if (horaini == hora || horafim == hora) {
          
        document.getElementById(id_hora).style.visibility = "visible";
        document.getElementById(id_hora).style.zIndex     = 10000;
      }
           
      if (hora >= horaini && hora <= horafim) {
          
        document.getElementById(id_linhahora).style.background = cor;
        document.getElementById(id_linhahora).style.visibility = "visible";
      }
    }

    tt += 1;
    if (tt == 60) {
        
      t += 40;
      tt = 0;
    }
  }
}

function js_Mout( quadro, horaini, horafim ) {
	
  document.getElementById("tab_descr").style.visibility = "hidden";
  document.getElementById("tab_descr").innerHTML        = "";
  document.getElementById(quadro).style.border          = "1px outset";
  tt = 0;
  
  for (t = <?=$horainicial?>; t <= <?=$horafinal?>; t += 1) {
	  
    hora         = new String(t);
    hora         = hora.length==3?"0"+hora:hora;
    hora         = hora.substr(0,2)+":"+hora.substr(2,2);
    id_hora      = "HH"+hora;
    id_linhahora = "LH"+hora;
    
    if (t != 2400) {
        
      if (horaini == hora || horafim == hora) {
          
        document.getElementById(id_hora).style.visibility = "hidden";
        document.getElementById(id_hora).style.zIndex     = 1000;
        
      }      
    }
    
    if(hora >= horaini && hora <= horafim) {
      document.getElementById(id_linhahora).style.visibility = "hidden";
    }
    tt += 1;
    if (tt == 60) {
        
      t += 40;
      tt = 0;
      
    }
  }
}

function js_marca(quadro,cor) {
	
  if (document.getElementById("input"+quadro).value == "") {
	  
    document.getElementById("tab"+quadro).style.background = "#FF8080";
    document.getElementById("input"+quadro).value          = "OK";
    document.getElementById("cod_horario").value           = document.getElementById("cod_horario").value+",tab"+quadro;
    
  } else {
	  
    document.getElementById("tab"+quadro).style.background = cor;
    document.getElementById("input"+quadro).value          = "";
    valor                                                  = document.getElementById("cod_horario").value;
    valor                                                  = valor.replace(",tab"+quadro,"");
    document.getElementById("cod_horario").value           = valor;
    
  }
  if (document.getElementById("cod_horario").value != "") {
    document.getElementById("tab_excluir").style.visibility = "visible";
  } else {
    document.getElementById("tab_excluir").style.visibility = "hidden";
  }
}

function js_excluir() {
	
  if (confirm("Confirmar exclusão destes registros?")) {
	  
    js_divCarregando("Aguarde, excluindo registro(s)","msgBox");
    var sCodigos = $F('cod_horario');
    var sAction  = 'Excluir';
    var url      = 'edu1_rechumanohoradisp002.php';
    
    if (sCodigos == "") {
      return false;
    }
    var oAjax = new Ajax.Request(url,{method    : 'post',
                                      parameters: 'codhorario='+sCodigos+'&sAction='+sAction,
                                      onComplete: js_retornoExclusao
                                     });
  }
}

function js_retornoExclusao(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno  = eval("("+oAjax.responseText+")");
  var oRetorno1 = oRetorno.split(",");
  
  for (t = 1; t < oRetorno1.length; t++) {
    document.getElementById(oRetorno1[t]).style.visibility = "hidden";
  }
  
  document.getElementById("cod_horario").value            = "";
  document.getElementById("tab_excluir").style.visibility = "hidden";
  top.corpo.iframe_a7.location.href                       = 'edu1_rechumanohorario001.php?ed20_i_codigo='+
                                                            '<?=$ed20_i_codigo?>';
  
}

function js_incluir(codigo,diasemana) {
	
  document.getElementById("inc_periodo").style.visibility = "visible";
  sHtml  = '<tr>';
  sHtml += ' <td><b>Dia da Semana:</b>';
  sHtml += ' </td>';
  sHtml += ' <td>';
  sHtml += '  <select name="diasemana">';
  sHtml += '   <option value="'+codigo+'">'+diasemana+'</option>';
  sHtml += '  </select>';
  sHtml += ' </td>';
  sHtml += '</tr>';
  sHtml1 = '<tr>';
  sHtml1 += ' <td valign="top"><b>Turno(s):</b>';
  sHtml1 += ' </td>';
  sHtml1 += ' <td valign="top">';
  sHtml1 += '  <select name="turno" size="10" style="width:400px;" onclick="js_buscaPeriodo(this.value);">';
  <?
  $sql_tur  = " SELECT ed15_i_codigo,ed15_c_nome,ed15_i_sequencia ";
  $sql_tur .= "      FROM turno ";
  $sql_tur .= "           inner join periodoescola on periodoescola.ed17_i_turno = turno.ed15_i_codigo ";
  $sql_tur .= "      WHERE periodoescola.ed17_i_escola = $escola ";
  $sql_tur .= "      GROUP BY ed15_i_codigo,ed15_c_nome,ed15_i_sequencia ";
  $sql_tur .= "      ORDER BY ed15_i_sequencia ";
  $result_tur = db_query($sql_tur);
  $linhas_tur = pg_num_rows($result_tur);
  
  for ($x = 0; $x < $linhas_tur; $x++) {
  	
    db_fieldsmemory($result_tur,$x);
    ?>
    sHtml1 += '   <option value="<?=$ed15_i_codigo?>"><?=$ed15_c_nome?></option>';
    <?
    
  }
  ?>
  sHtml1 += '  </select>';
  sHtml1 += ' </td>';
  sHtml1 += ' <td>';
  sHtml1 += '  <div id="corpo_periodo"></div>';
  sHtml1 += ' </td>';
  sHtml1 += '</tr>'; 
  $('corpo_diasemana').innerHTML = sHtml;
  $('corpo_turno').innerHTML     = sHtml1;
  $('corpo_periodo').innerHTML   = '';
  
}

function js_buscaPeriodo(codturno) {
	
  $('corpo_periodo').innerHTML = '';
  var sAction                  = 'PesquisaPeriodo';
  var url                      = 'edu1_rechumanohoradisp002.php';
  js_divCarregando("Aguarde, buscando período(s)","msgBox");
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: 'diasemana='+document.form1.diasemana.value+'&turno='+codturno+
                                                '&rechumano=<?=$ed20_i_codigo?>&rechumanoescola=<?=$ed75_i_codigo?>&sAction='+sAction,
                                    onComplete: js_retornoPeriodo
                                   });
  
}

function js_retornoPeriodo(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '<table>';
  sHtml += '<tr>'; 
  sHtml += ' <td valign="top"><b>Período(s):</b>';
  sHtml += ' </td>';
  sHtml += ' <td>';
  sHtml += '  <select name="periodo" id="periodo" multiple size="6" style="width:400px;">';
  if (oRetorno.length == 0) {
    sHtml += '  <option value="">Todos períodos preenchidos para estas opções.</option>';
  } else {
	  
    for (var i = 0;i < oRetorno.length; i++) {
        
      with (oRetorno[i]) {
          
        sHtml += '  <option value="'+ed17_i_codigo+'">'+ed15_c_nome.urlDecode()+' - '+ed08_c_descr.urlDecode();
        sHtml += ' período : '+ed17_h_inicio.urlDecode()+' às '+ed17_h_fim.urlDecode()+'</option>';
        
      }
    }    
  }
  
  sHtml += '  </select>';
  sHtml += '</tr>'; 
  sHtml += '<tr>'; 
  sHtml += ' <td valign="top"><b>Tipo de hora:</b>';
  sHtml += ' </td>';
  sHtml += ' <td>';
  sHtml += '  <select name="ed23_tipohoratrabalho" id="ed23_tipohoratrabalho" style="width:150px;">';
  sHtml += '   <option value="">Selecione...</option>';

  <?php
    $sCamposTipoHora = "distinct (ed128_codigo), ed128_descricao";
    $sWhereTipoHora  = " ed22_i_rechumanoescola = {$ed75_i_codigo} and ed22_ativo = TRUE and ed128_ativo = TRUE";
    $sWhereTipoHora .= " and ed01_c_regencia = 'S'";
    $sSqlTipoHora    = $clrechumanoativ->sql_query_tipohoratrabalho( "", $sCamposTipoHora, "", $sWhereTipoHora );
    $rsTipoHora      = db_query( $sSqlTipoHora );

    $iLinhas  = pg_num_rows($rsTipoHora);
    if ( $iLinhas > 0 ) {

      for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) { 

        $oDadosTipoHoraTrabalho = db_utils::fieldsMemory( $rsTipoHora, $iContador );
        ?>
          sHtml += ' <option value="<?=$oDadosTipoHoraTrabalho->ed128_codigo?>"><?=$oDadosTipoHoraTrabalho->ed128_descricao?></option>';
        <?php
      }
    }
  ?>

  sHtml += '  </select>';
  sHtml += ' </td>';
  sHtml += '</tr>';

  sHtml += "<tr>";
  sHtml += "  <td>";
  sHtml += "    <label for='horaAtividade' class='bold'>Hora Atividade:</label>";
  sHtml += "  </td>";
  sHtml += "  <td>";
  sHtml += "    <select id='horaAtividade' style='width:150px;'>";
  sHtml += "      <option value='f' selected='selected'>NÃO</option>";
  sHtml += "      <option value='t'>SIM</option>";
  sHtml += "    </select>";

  if (oRetorno.length > 0) {
    sHtml += ' <input type="button" name="incluirperiodo" id="incluirperiodo" value="Incluir" onclick="js_incluirPeriodo();">';
  }

  sHtml += "  </td>";
  sHtml += "</tr>";

  sHtml += '</table>';
  $('corpo_periodo').innerHTML = sHtml;
  
}

function js_incluirPeriodo() {
	
  var iTipoHora      = $F("ed23_tipohoratrabalho");
  var sHoraAtividade = $F('horaAtividade');

  tam      = document.form1.periodo.length;
  periodos = '';
  sep      = '';

  for(var i = 0; i < tam; i++ ) {
	  
    if( document.form1.periodo[i].selected == true ) {
        
      periodos += sep + document.form1.periodo[i].value;
      sep       = ',';
    }
  }

  if ( periodos == '' ) {

    alert( _M(MENSAGEM_RECHUMANOHORADISP001 + "informe_periodo") );
    return;
  }

  if ( iTipoHora == '' ) {

    alert( _M(MENSAGEM_RECHUMANOHORADISP001 + "informe_tipohora") );
    return;
  }
	  
  js_divCarregando("Aguarde, incluindo período(s)","msgBox");

  var sAction = 'IncluirPeriodo';
  var url     = 'edu1_rechumanohoradisp002.php';
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: 'diasemana='+document.form1.diasemana.value+'&periodos='+periodos+
                                                 '&rechumano=<?=$ed20_i_codigo?>&z01_numcgm='+
                                                 '<?=$z01_numcgm?>&rechumanoescola=<?=$ed75_i_codigo?>&sAction='+sAction
                                                 +'&iTipoHora='+iTipoHora+'&sHoraAtividade='+sHoraAtividade,
                                    onComplete: js_retornoInclusaoPeriodo
                                   });
}

function js_retornoInclusaoPeriodo(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.aRetorno[0].urlDecode() == "0") {
	  
    alert(oRetorno.aRetorno[1].urlDecode());
    return false;
    
  } else {
	  
    if (oRetorno.aRetorno[1].urlDecode() != '') {
      alert(oRetorno.aRetorno[1].urlDecode());
    }
  }

  if( !oRetorno.lVinculoAtivo ) {
    return;
  }

  $('incluirperiodo').disabled = true;
  periodos                     = oRetorno.aRetorno[0].urlDecode();
  js_divCarregando("Aguarde, buscando outros dias da semana","msgBox");
  var sAction = 'BuscaOutrosDias';
  var url     = 'edu1_rechumanohoradisp002.php';
  var oAjax   = new Ajax.Request(url,{method    : 'post',
                                      parameters: 'diasemana='+document.form1.diasemana.value+'&sAction='+sAction,
                                      onComplete: js_retornaBuscaOutrosDias
                                     });
}

function js_retornaBuscaOutrosDias(oAjax) {
	
  $('inc_variosperiodos').style.visibility = 'visible';
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  sHtml = '<tr>';
  sHtml += ' <td><b>Escolha outros dias da semana para incluir o(s) período(s) :</b><br>';
  sHtml += '  <select name="outrosdiasemana" style="width:200px;" multiple size="7">';
  
  for (var i = 0;i < oRetorno.length; i++) {
	  
    with (oRetorno[i]) {
      sHtml += '  <option value="'+ed32_i_codigo+'">'+ed32_c_descr.urlDecode()+'</option>';
    }
  }
  
  sHtml += '  </select>';
  sHtml += ' </td>';
  sHtml += '</tr>';
  sHtml += '<tr>';
  sHtml += ' <td>';
  sHtml += '  <input type="button" name="confirmavarios" value="Confirmar" onclick="js_incluirvariosdias();">';
  sHtml += '  <input type="button" name="cancelavarios" value="Cancelar" onclick="js_RemontaGrade();">'; 
  sHtml += ' </td>';
  sHtml += '</tr>';
  $('corpo_outrosdias').innerHTML = sHtml;
  
}

function js_incluirvariosdias() {
	
  var iTipoHora      = $F("ed23_tipohoratrabalho");
  var sHoraAtividade = $F('horaAtividade');

  tam           = document.form1.outrosdiasemana.length;
  outroscod     = "";
  sepoutros     = "";

  for (t = 0; t < tam; t++) {

    if (document.form1.outrosdiasemana[t].selected == true) {

      outroscod += sepoutros+document.form1.outrosdiasemana[t].value;
      sepoutros  = ",";

    }
  }
  if (outroscod == "") {
    alert("Informe algum dia da semana!");
  } else {

    tam      = document.form1.periodo.length;
    periodos = '';
    sep      = '';

    for (i = 0; i < tam; i++) {

      if (document.form1.periodo[i].selected == true) {

        periodos += sep+document.form1.periodo[i].value;
        sep = ',';

      }
    }
    if (periodos == '') {
      alert("Informe algum período para incluir!");
    } else {

      js_divCarregando("Aguarde, incluindo demais dias da semana","msgBox");
      var sAction = 'IncluirPeriodo';
      var url     = 'edu1_rechumanohoradisp002.php';
      var oAjax = new Ajax.Request(url,{method    : 'post',
                                        parameters: 'diasemana='+outroscod+'&periodos='+periodos+
                                                    '&rechumano=<?=$ed20_i_codigo?>&z01_numcgm='+
                                                    '<?=$z01_numcgm?>&rechumanoescola=<?=$ed75_i_codigo?>&sAction='+sAction+
                                                    '&iTipoHora='+iTipoHora+'&sHoraAtividade='+sHoraAtividade,
                                        onComplete: js_retornoIncluirOutrosPeriodos
                                       });

    }
  }
}

function js_retornoIncluirOutrosPeriodos(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.aRetorno[0].urlDecode() == "0") {
	  
    alert(oRetorno.aRetorno[1].urlDecode());
    return false;
    
  } else {
	  
    if (oRetorno.aRetorno[1].urlDecode() != '') {
      alert(oRetorno.aRetorno[1].urlDecode());
    }
    js_RemontaGrade();
  }
}

function js_RemontaGrade() {

  
  js_divCarregando("Aguarde, atualizando tela","msgBox");
  var sAction = 'MontaGrade';
  var url     = 'edu1_rechumanohoradisp002.php';
  var sParams = 'rechumano=<?=$ed20_i_codigo?>&larg_obj=<?=$larg_obj?>&rechumanoescola=<?=$ed75_i_codigo?>&sAction='+sAction;
  var oAjax = new Ajax.Request(url,{method    : 'post',
                                    parameters: sParams,
                                    onComplete: js_retornaGrade
                                   });
  $('inc_periodo').style.visibility        = 'hidden'; 
  $('inc_variosperiodos').style.visibility = 'hidden'; 
  location.href='edu1_rechumanohoradisp001.php?ed20_i_codigo=<?=$ed20_i_codigo?>&larg_obj=<?=$larg_obj?>&rechumanoescola=<?=$ed75_i_codigo?>';

}

function js_retornaGrade(oAjax) {
	
  js_removeObj("msgBox");
  var oRetorno                             = eval("("+oAjax.responseText+")");
  $('disp_rechumano').innerHTML            = "";
  $('disp_rechumano').innerHTML            = oRetorno.urlDecode(); 
  top.corpo.iframe_a7.location.href='edu1_rechumanohorario001.php?ed20_i_codigo=<?=$ed20_i_codigo?>';
  
}
</script>