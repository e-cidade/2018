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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrechumanohoradisp->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed20_i_codigo");
$clrotulo->label("ed32_i_codigo");
$clrotulo->label("ed17_i_codigo");
$db_botao1 = false;
if( isset( $opcao ) && $opcao == "alterar" ) {

  $db_opcao  = 2;
  $db_botao1 = true;
} else if( isset( $opcao ) && $opcao == "excluir" || isset( $db_opcao ) && $db_opcao == 3 ) {

  $db_botao1 = true;
  $db_opcao  = 3;
} else {

  if( isset( $alterar ) ) {

    $db_opcao  = 2;
    $db_botao1 = true;
  } else {
    $db_opcao = 1;
  }
}
?>
<form name="form1" method="post" action="">
  <center>
    <table border="0">
      <tr>
        <td nowrap title="<?=@$Ted20_i_codigo?>">
          <?db_ancora( @$Led20_i_codigo, "", 3 );?>
        </td>
        <td>
          <?php
            db_input( 'ed20_i_codigo', 10, $Ied20_i_codigo, true, 'text', 3 );
            db_input( 'z01_nome',      50, @$Iz01_nome,     true, 'text', 3 );
          ?>
        </td>
      </tr>
    </table>
    <input name="ed33_i_codigo" type="hidden" value="">
    <input name="ed33_i_periodo" type="hidden" value="">
    <input name="ed33_i_diasemana" type="hidden" value="">
    <input name="periodoaula" type="hidden" value="">
    <input name="turno" type="hidden" value="">
    <input name="incluir" type="submit" id="db_opcao" value="Incluir" style="visibility:hidden;position:absolute">
    <input name="excluir" type="submit" id="db_opcao" value="Excluir" style="visibility:hidden;position:absolute">
    <input name="incluirtodos" type="submit" id="db_opcao" value="Incluir Todos" style="visibility:hidden;position:absolute">
    <input name="excluirtodos" type="submit" id="db_opcao" value="Excluir Todos" style="visibility:hidden;position:absolute">
    <input name="incluirdia" type="submit" id="db_opcao" value="Incluir Dia" style="visibility:hidden;position:absolute">
    <input name="excluirdia" type="submit" id="db_opcao" value="Excluir Dia" style="visibility:hidden;position:absolute">
  </center>
</form>
<center>
<table cellspacing="0" cellpading="0" border="1" bordercolor="#000000">
  <?php
    $prof    = false;
    $turno   = "";
    $escola  = db_getsession("DB_coddepto");

    $sWhereRecHumanoAtiv = "ed75_i_rechumano = {$ed20_i_codigo} GROUP BY regencia";
    $sSqlRecHumanoAtiv   = $clrechumanoativ->sql_query( "", "ed01_c_regencia as regencia", "", $sWhereRecHumanoAtiv );
    $result2             = $clrechumanoativ->sql_record( $sSqlRecHumanoAtiv );
    for( $z = 0; $z < $clrechumanoativ->numrows; $z++ ) {

      db_fieldsmemory( $result2, $z );
      if( $regencia == "S" ) {

        $prof = true;
        break;
      }
    }

    if( $prof == true && $clrechumanoativ->numrows == 1 ) {
      $where = "";
    } else {
      $where = "";
    }

    $sql     = $clperiodoescola->sql_query( "", "*", "ed15_i_sequencia, ed08_i_sequencia", "ed17_i_escola = {$escola}" );
    $result1 = $clperiodoescola->sql_record($sql) or die (pg_errormessage());

    for($z=0;$z<$clperiodoescola->numrows;$z++){
    db_fieldsmemory($result1,$z);
    if($turno!=$ed15_c_nome){
      ?>
    <tr bgcolor="#444444">
    <td onmouseover="MostraTurno('turno<?=$ed17_i_codigo?>');" onmouseout="OcultaTurno('turno<?=$ed17_i_codigo?>');" align="center" width="50" style="font-weight: bold; color: #DEB887;">
     <?=pg_result($result1,$z,"ed15_c_nome");?>
     <table cellpading="2" cellspacing="0" bgcolor="#f3f3f3" id="turno<?=$ed17_i_codigo?>" style="visibility:hidden;position:absolute;border:1px solid #666666;">
      <tr>
       <td>
        <a href="javascript:js_incluirtodos(<?=$ed17_i_turno?>)" style="color:#000000"><?=$ed15_c_nome?>: Marcar tudo</a>
       </td>
      </tr>
      <tr>
       <td>
        <a href="javascript:js_excluirtodos(<?=$ed17_i_turno?>)" style="color:#000000"><?=$ed15_c_nome?>: Desmarcar tudo</a>
       </td>
      </tr>
     </table>
    </td><?
    $result = $cldiasemana->sql_record($cldiasemana->sql_query_rh("","ed32_i_codigo,ed32_c_abrev","ed32_i_codigo",$where));
    //db_criatabela($result);
    for($x=0;$x<$cldiasemana->numrows;$x++){
     db_fieldsmemory($result,$x);?>
     <td>
      <table cellspacing="0" cellpading="0" >
       <tr>
        <td onmouseover="MostraDia('dia<?=$ed17_i_codigo?><?=$ed32_i_codigo?>');" onmouseout="OcultaDia('dia<?=$ed17_i_codigo?><?=$ed32_i_codigo?>');" width="50" style="font-weight: bold; color: #DEB887;">
         <div align="center"><?=$ed32_c_abrev?></div>
         <table cellpading="2" cellspacing="0" bgcolor="#f3f3f3" id="dia<?=$ed17_i_codigo?><?=$ed32_i_codigo?>" style="visibility:hidden;position:absolute;border:1px solid #666666;">
          <tr>
           <td>
            <a href="javascript:js_incluirdia(<?=$ed17_i_turno?>,<?=$ed32_i_codigo?>)" style="color:#000000"><?=$ed32_c_abrev?>: Marcar tudo</a>
           </td>
          </tr>
          <tr>
           <td>
            <a href="javascript:js_excluirdia(<?=$ed17_i_turno?>,<?=$ed32_i_codigo?>)" style="color:#000000"><?=$ed32_c_abrev?>: Desmarcar tudo</a>
           </td>
          </tr>
         </table>
        </td>
       </tr>
      </table>
     </td>
    <?}?>
    </tr>
   <?}
   $turno = $ed15_c_nome?>
   <td align="center" width="30" style="font-weight: bold; background-color: #f3f3f3;"> <?=$ed08_c_descr?> - <?=$ed17_h_inicio?> / <?=$ed17_h_fim?></td><?
   for($x=0;$x<$cldiasemana->numrows;$x++){
    $quadro = "Q".$z.$x;
    db_fieldsmemory($result,$x);
    $sql2 = $clrechumanohoradisp->sql_query_disponibilidade("","ed33_i_codigo,ed33_i_periodo,ed33_i_diasemana,ed17_i_escola as escolamarcada,ed18_c_nome as nomeescola",""," ed75_i_rechumano = $ed20_i_codigo and ed33_ativo is true AND ed32_i_codigo = $ed32_i_codigo AND ed08_i_codigo = $ed08_i_codigo AND ed15_i_codigo = $ed15_i_codigo");
    $result2 = $clrechumanohoradisp->sql_record($sql2);
    if($clrechumanohoradisp->numrows>0){
     db_fieldsmemory($result2,0);
     $marcar = "OK";
     $escolacod = $escolamarcada;
     if(db_getsession("DB_coddepto")==$escolamarcada){
      $habilitar = true;
     }else{
      $habilitar = false;
     }
    }else{
     $marcar = "";
     $escolacod = "";
     $habilitar = true;
    }
    ?>
    <td>
     <table cellspacing="0" cellpading="0" marginwidth="0">
      <tr>
       <td>
        <table class="texto" bgcolor="#cccccc" id="<?=$quadro?>" cellspacing="0" cellpading="0" style="border: 2px outset #f3f3f3; border-bottom-color:#999999; border-right-color:#999999;">
         <tr>
          <?if($marcar==""){?>
           <td onclick="GravarHorario(<?=$ed32_i_codigo?>,<?=$ed17_i_codigo?>,<?=$ed17_i_periodoaula?>,<?=$ed17_i_turno?>)" width="50" height="15" onmouseover="InSet('<?=$quadro?>',false)" onmouseout="OutSet('<?=$quadro?>',false)">
          <?}elseif($marcar=="OK" && $habilitar==true){?>
           <td onclick="ExcluirHorario(<?=$ed33_i_codigo?>)" width="50" height="15" onmouseover="InSet('<?=$quadro?>',true)" onmouseout="OutSet('<?=$quadro?>',true)">
          <?}elseif($marcar=="OK" && $habilitar==false){?>
           <td width="50" height="15" onmouseover="InSet('<?=$quadro?>',true)" onmouseout="OutSet('<?=$quadro?>',true)">
          <?}?>
           <div align="center"><b><?=$escolacod?></b></div>
          </td>
         </tr>
        </table>
        <?
        if($marcar=="OK"){
         ?>
         <table id="escola<?=$quadro?>" style="position:absolute;visibility:hidden;" border="1" bgcolor="#f3f3f3">
          <tr>
           <td>
            Escola: <b><?=$escolacod." - ".$nomeescola?></b>
           </td>
          </tr>
         </table>
         <?
        }
        ?>
       </td>
      </tr>
     </table>
    </td>
   <?
   $marcar = "";
   }?>
   <tr>
  <?}?>
  </tr>
  </table>
</center>
<script>
function InSet(id,tem){
 T = document.getElementById(id);
 T.style.border = "2px inset #f3f3f3";
 if(tem==true){
  document.getElementById("escola"+id).style.visibility = "visible";
 }
}
function OutSet(id,tem){
 T = document.getElementById(id);
 T.style.border = "2px outset #f3f3f3";
 T.style.borderBottomColor = "#999999";
 T.style.borderRightColor = "#999999";
 if(tem==true){
  document.getElementById("escola"+id).style.visibility = "hidden";
 }
}
function GravarHorario(diasemana,periodo,periodoaula,turno){
 document.form1.ed33_i_periodo.value = periodo;
 document.form1.ed33_i_diasemana.value = diasemana;
 document.form1.periodoaula.value = periodoaula;
 document.form1.turno.value = turno;
 document.form1.incluir.click();
}
function ExcluirHorario(codigo){
 document.form1.ed33_i_codigo.value = codigo;
 document.form1.excluir.click();
}
function js_incluirtodos(turno){
 var opcao = document.createElement("input");
 opcao.setAttribute("type","hidden");
 opcao.setAttribute("name","turnotodos");
 opcao.setAttribute("value",turno);
 document.form1.appendChild(opcao);
 document.form1.incluirtodos.click();
}
function js_excluirtodos(turno){
 var opcao = document.createElement("input");
 opcao.setAttribute("type","hidden");
 opcao.setAttribute("name","turnotodos");
 opcao.setAttribute("value",turno);
 document.form1.appendChild(opcao);
 document.form1.excluirtodos.click();
}
function js_incluirdia(turno,dia){
 var opcao = document.createElement("input");
 opcao.setAttribute("type","hidden");
 opcao.setAttribute("name","turno");
 opcao.setAttribute("value",turno);
 document.form1.appendChild(opcao);
 var opcao = document.createElement("input");
 opcao.setAttribute("type","hidden");
 opcao.setAttribute("name","diasemana");
 opcao.setAttribute("value",dia);
 document.form1.appendChild(opcao);
 document.form1.incluirdia.click();
}
function js_excluirdia(turno,dia){
 var opcao = document.createElement("input");
 opcao.setAttribute("type","hidden");
 opcao.setAttribute("name","turno");
 opcao.setAttribute("value",turno);
 document.form1.appendChild(opcao);
 var opcao = document.createElement("input");
 opcao.setAttribute("type","hidden");
 opcao.setAttribute("name","diasemana");
 opcao.setAttribute("value",dia);
 document.form1.appendChild(opcao);
 document.form1.excluirdia.click();
}
function MostraTurno(id){
 document.getElementById(id).style.visibility = "visible";
}
function OcultaTurno(id){
 document.getElementById(id).style.visibility = "hidden";
}
function MostraDia(id){
 document.getElementById(id).style.visibility = "visible";
}
function OcultaDia(id){
 document.getElementById(id).style.visibility = "hidden";
}
</script>