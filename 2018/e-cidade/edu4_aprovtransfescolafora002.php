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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
include("classes/db_regencia_classe.php");
include("classes/db_turma_classe.php");
include("classes/db_procavaliacao_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_matriculamov_classe.php");
include("classes/db_diario_classe.php");
include("classes/db_diarioavaliacao_classe.php");
include("classes/db_diarioresultado_classe.php");
include("classes/db_diariofinal_classe.php");
include("classes/db_pareceraval_classe.php");
include("classes/db_parecerresult_classe.php");
include("classes/db_abonofalta_classe.php");
include("classes/db_amparo_classe.php");
include("classes/db_alunocurso_classe.php");
include("classes/db_alunopossib_classe.php");
include("classes/db_periodocalendario_classe.php");
include("classes/db_transfaprov_classe.php");
include("classes/db_serieequiv_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$resultedu= eduparametros(db_getsession("DB_coddepto"));
$escola = db_getsession("DB_coddepto");
$clregencia = new cl_regencia;
$clturma = new cl_turma;
$clprocavaliacao = new cl_procavaliacao;
$clmatricula = new cl_matricula;
$clmatriculamov = new cl_matriculamov;
$cldiario = new cl_diario;
$cldiarioavaliacao = new cl_diarioavaliacao;
$cldiarioresultado = new cl_diarioresultado;
$cldiariofinal = new cl_diariofinal;
$clpareceraval = new cl_pareceraval;
$clparecerresult = new cl_parecerresult;
$clabonofalta = new cl_abonofalta;
$clamparo = new cl_amparo;
$clalunocurso = new cl_alunocurso;
$clalunopossib = new cl_alunopossib;
$clperiodocalendario = new cl_periodocalendario;
$cltransfaprov = new cl_transfaprov;
$clserieequiv = new cl_serieequiv;
?>
<table width="300" height="100" id="tab_aguarde" style="border:2px solid #444444;position:absolute;top:100px;left:250px;" cellspacing="1" cellpading="2">
 <tr>
  <td bgcolor="#DEB887" align="center" style="border:1px solid #444444;">
   <b>Aguarde...Carregando.</b>
  </td>
 </tr>
</table>
<?
if(!isset($incluir)){
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name="form1" METHOD="POST" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td colspan="5" valign="top" bgcolor="#CCCCCC">
   <br>
   <b>
    ATENÇÃO! O sistema irá importar da origem e transportar o aproveitamento do aluno para a turma atual, somente dos períodos que não estejam em branco na turma de origem. Caso já exista aproveitamento na turma atual referente ao mesmo período da origem, o mesmo será substituido.
   </b>
   <br><br>
  </td>
 </tr>
 <?
 $result_cod = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_aluno,ed221_i_serie as etapaorigem",""," ed60_i_codigo = $matricula"));
 db_fieldsmemory($result_cod,0);
 $result_equivorig = $clserieequiv->sql_record($clserieequiv->sql_query("","ed234_i_serieequiv as equivorig",""," ed234_i_serie = $etapaorigem"));
 $codequivorig = "";;
 $seporig = "";
 for($ww=0;$ww<$clserieequiv->numrows;$ww++){
  db_fieldsmemory($result_equivorig,$ww);
  $codequivorig .= $seporig.$equivorig;
  $seporig = ",";
 }
 $codequivorig = ($codequivorig==""?0:$codequivorig).",".$etapaorigem;
 $result = $clregencia->sql_record($clregencia->sql_query("","ed59_i_codigo,ed232_i_codigo,ed232_c_descr,ed232_c_abrev,ed220_i_procedimento as procorigem,ed57_c_descr,ed57_i_escola,fc_nomeetapaturma(ed59_i_turma) as ed11_c_descr,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_turma = $turmaorigem AND ed59_i_serie in ($etapaorigem)"));
 $procorigem = pg_result($result,0,'procorigem');
 $linhas = $clregencia->numrows;
 $result1 = $clregencia->sql_record($clregencia->sql_query("","ed59_i_codigo as regdestino,ed232_i_codigo as coddestino,ed232_c_descr as descrdestino,ed220_i_procedimento as procdestino,ed57_c_descr as ed57_c_descrdest,ed57_i_escola as ed57_i_escoladest,fc_nomeetapaturma(ed59_i_turma) as ed11_c_descrdest,ed59_i_ordenacao","ed59_i_ordenacao"," ed59_i_turma = $turmadestino AND ed59_i_serie in ($codequivorig)"));
 $procdestino = pg_result($result1,0,'procdestino');
 $linhas1 = $clregencia->numrows;
 ?>
 <tr>
  <td colspan="2" width="48%" valign="top" bgcolor="#CCCCCC">
   <b>TURMA DE ORIGEM: ( <?=pg_result($result,0,'ed57_c_descr')?>&nbsp;&nbsp;&nbsp;<?=pg_result($result,0,'ed11_c_descr')?> - Escola: <?=pg_result($result,0,'ed57_i_escola')?> )</b>
  </td>
  <td></td>
  <td colspan="2" width="48%" valign="top" bgcolor="#CCCCCC">
   <b>TURMA ATUAL: ( <?=pg_result($result1,0,'ed57_c_descrdest')?>&nbsp;&nbsp;&nbsp;<?=pg_result($result1,0,'ed11_c_descrdest')?> - Escola: <?=pg_result($result1,0,'ed57_i_escoladest')?> )</b>
  </td>
 </tr>
 <?
 $regmarcadas = "";
 $veraprovnulo = "";
 for($t=0;$t<$linhas;$t++){
  db_fieldsmemory($result,$t);
  ?>
  <tr>
   <td width="15%" valign="top" bgcolor="#CCCCCC">
    <input name="regenciaorigem" type="hidden" value="<?=$ed59_i_codigo?>" size="10" readonly style="width:75px">
    <input name="regorigemdescr" type="text" value="<?=$ed232_c_descr?>" size="30" readonly style="width:140px">
   </td>
   <td width="33%">
   <table border="1" cellspacing="0" cellpadding="0">
    <tr>
     <?
     $result_diario = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed09_c_abrev,ed72_i_valornota,ed72_c_valorconceito,ed72_t_parecer,ed37_c_tipo","ed41_i_sequencia ASC"," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = $ed59_i_codigo AND ed09_c_somach = 'S'"));
     if($cldiarioavaliacao->numrows==0){
      echo "<td width='160px' style='background:#f3f3f3;'>Nenhum registro.</td>";
     }else{
      for($v=0;$v<$cldiarioavaliacao->numrows;$v++){
       db_fieldsmemory($result_diario,$v);
       if(trim($ed37_c_tipo)=="NOTA"){
        if($resultedu=='S'){
         $aproveitamento = $ed72_i_valornota!=""?number_format($ed72_i_valornota,2,",","."):"";
        }else{
         $aproveitamento = $ed72_i_valornota!=""?number_format($ed72_i_valornota,0):"";
        }
       }elseif(trim($ed37_c_tipo)=="NIVEL"){
        $aproveitamento = $ed72_c_valorconceito;
       }else{
        $aproveitamento = "";
       }
       $veraprovnulo .= $aproveitamento;
       echo "<td width='60px' style='background:#f3f3f3;'><b>$ed09_c_abrev:</b></td>
             <td width='60px' align='center'>".($aproveitamento==""?"&nbsp;":$aproveitamento)."</td>";
      }
     }
     ?>
    </tr>
   </table>
   </td>
   <td align="center">--></td>
   <td width="15%">
    <?
    $temreg = false;
    for($w=0;$w<$linhas1;$w++){
     db_fieldsmemory($result1,$w);
     if($ed232_i_codigo==$coddestino){
      $temreg = true;
      $regenciadestino = $regdestino;
      $regdestinodescr = $descrdestino;
      $regmarcadas .= "#".$regdestino."#";
     }
    }
    if($temreg==true){
     ?>
      <input name="regenciadestino" type="hidden" value="<?=$regenciadestino?>" size="10" readonly style="width:75px">
      <input name="regdestinodescr" type="text" value="<?=$regdestinodescr?>" size="30" readonly style="width:140px">
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
     <select name="regenciadestino" style="visibility:hidden;position:absolute;padding:0px;width:75px;height:16px;font-size:12px;" onchange="js_eliminareg(this.value,<?=$t?>)">
     <option value=""></option>
     <?
     if($linhas==1){
      echo "<option value='0'>TODAS</option>";
     }
     for($w=0;$w<$linhas2;$w++){
      db_fieldsmemory($result2,$w);
      echo "<option value='$regsobra'>$regsobra</option>";
     }
     ?>
     </select>
     <select name="regdestinodescr" style="padding:0px;width:140px;height:16px;font-size:12px;" onchange="js_eliminareg(this.value,<?=$t?>)">
     <option value=""></option>
     <?
     if($linhas==1){
      echo "<option value='0'>TODAS</option>";
     }
     for($w=0;$w<$linhas2;$w++){
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
  <td width="33%">
   <table border="1" cellspacing="0" cellpadding="0">
    <tr>
     <?
     $result_diario = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query("","ed09_c_abrev,ed72_i_valornota,ed72_c_valorconceito,ed72_t_parecer,ed37_c_tipo","ed41_i_sequencia ASC"," ed95_i_aluno = $ed60_i_aluno AND ed95_i_regencia = ".(!isset($regenciadestino)?0:$regenciadestino)." AND ed09_c_somach = 'S'"));
     if($cldiarioavaliacao->numrows==0){
      echo "<td width='160px' style='background:#f3f3f3;'>Nenhum registro.</td>";
     }else{
      for($v=0;$v<$cldiarioavaliacao->numrows;$v++){
       db_fieldsmemory($result_diario,$v);
       if(trim($ed37_c_tipo)=="NOTA"){
        if($resultedu=='S'){
         $aproveitamento = $ed72_i_valornota!=""?number_format($ed72_i_valornota,2,",","."):"";
        }else{
         $aproveitamento = $ed72_i_valornota!=""?number_format($ed72_i_valornota,0):"";
        }
       }elseif(trim($ed37_c_tipo)=="NIVEL"){
        $aproveitamento = $ed72_c_valorconceito;
       }else{
        $aproveitamento = "";
       }
       echo "<td width='60px' style='background:#f3f3f3;'><b>$ed09_c_abrev:</b></td>
             <td width='60px' align='center'>".($aproveitamento==""?"&nbsp;":$aproveitamento)."</td>";
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
 </table>
 <table>
 <tr>
  <td valign="top" bgcolor="#CC CCCC">
   <b>Períodos de Avaliação TURMA DE ORIGEM:</b>
  </td>
  <td></td>
  <td valign="top" bgcolor="#CCCCCC">
   <b>Períodos de Avaliação  TURMA DE DESTINO:</b>
  </td>
  <td></td>
 </tr>
 <?
 $result = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_codigo,ed09_i_codigo,ed09_c_descr,ed37_c_tipo,ed37_i_menorvalor,ed37_i_maiorvalor","ed41_i_sequencia"," ed41_i_procedimento = $procorigem"));
 $linhas = $clprocavaliacao->numrows;
 $result1 = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed41_i_codigo as codaval,ed09_i_codigo as codperaval,ed09_c_descr as descraval,ed37_c_tipo as tipodest,ed37_i_menorvalor as menordest,ed37_i_maiorvalor as maiordest","ed41_i_sequencia"," ed41_i_procedimento = $procdestino"));
 $linhas1 = $clprocavaliacao->numrows;
 for($t=0;$t<$linhas;$t++){
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
    for($w=0;$w<$linhas1;$w++){
     db_fieldsmemory($result1,$w);
     if($ed09_i_codigo==$codperaval){
      $temper = true;
      $periododestino = $codaval;
      $tipoavaliacao1 = $tipodest.($tipodest=='NOTA'?' ('.$menordest.' a '.$maiordest.')':'');
      $perdestinodescr = $descraval.' - '.$tipoavaliacao1;
     }
    }
    if($temper==true){
     ?>
      <input name="periododestino" type="text" value="<?=$periododestino?>" size="10" readonly style="width:75px">
      <input name="perdestinodescr" type="text" value="<?=$perdestinodescr?>" size="30" readonly style="width:180px">
     <?
    }else{
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
     for($w=0;$w<$linhas2;$w++){
      db_fieldsmemory($result2,$w);
       echo "<option value='$persobra'>$persobra</option>";
     }
     ?>
     </select>
     <select name="perdestinodescr" style="padding:0px;width:180px;height:16px;font-size:12px;" onchange="js_eliminaper(this.value,<?=$t?>)">
     <option value=""></option>
     <?
     for($w=0;$w<$linhas2;$w++){
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
  <td height="10" colspan="5"></td>
 </tr>
 <tr>
  <td>
   <input type="button" id="db_opcao" name="incluir" value="Confirmar Importação" onclick="js_processar();" <?=isset($incluir)?"style='visibility:hidden;'":""?>>
   <input type="hidden" name="etapaorigem" value="<?=$etapaorigem?>">
  </td>
 </tr>
</table>
<form>
</body>
</html>
<?
if($veraprovnulo==""){
 ?>
 <script>
  document.form1.incluir.disabled = true;
  alert("Aluno não contém nenhum aproveitamento na turma de origem.\nImportação não permitida.");
 </script>
 <?
}
?>
<script>
function js_eliminareg(valor,seq){
 if(valor=="0"){
  alert("Aproveitamento da disciplina de origem será transportado para todas as disciplinas da turma de destino!");
 }
 C = document.form1.combo;
 RD = document.form1.regenciadestino;
 RDC = document.form1.regdestinodescr;
 tamC = C.length;
 tamC = tamC==undefined?1:tamC;
 campo = "comboselect"+seq;
 valorant = eval("document.form1."+campo+".value");
 if(tamC==1){
  tamRD = RD.length;
  for(r=0;r<tamRD;r++){
   if(parseInt(RD.options[r].value)==parseInt(valor) || parseInt(RDC.options[r].value)==parseInt(valor)){
    RD.options[r].selected = true;
    RDC.options[r].selected = true;
   }
   if(parseInt(RD.options[r].value)==parseInt(valorant) || parseInt(RDC.options[r].value)==parseInt(valorant)){
    RD.options[r].selected = false;
    RDC.options[r].selected = false;
   }
  }
 }else{
  for(i=0;i<tamC;i++){
   tamRD = RD[C[i].value].length;
   if(parseInt(C[i].value)!=parseInt(seq)){
    for(r=0;r<tamRD;r++){
     if(parseInt(RD[C[i].value].options[r].value)==parseInt(valor) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valor)){
      RD[C[i].value].options[r].disabled = true;
      RDC[C[i].value].options[r].disabled = true;
     }
     if(parseInt(RD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valorant)){
      RD[C[i].value].options[r].disabled = false;
      RDC[C[i].value].options[r].disabled = false;
     }
    }
   }else{
    for(r=0;r<tamRD;r++){
     if(parseInt(RD[C[i].value].options[r].value)==parseInt(valor) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valor)){
      RD[C[i].value].options[r].selected = true;
      RDC[C[i].value].options[r].selected = true;
     }
     if(parseInt(RD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(RDC[C[i].value].options[r].value)==parseInt(valorant)){
      RD[C[i].value].options[r].selected = false;
      RDC[C[i].value].options[r].selected = false;
     }
    }
   }
  }
 }
 eval("document.form1."+campo+".value = valor");
}

function js_eliminaper(valor,seq){
 C = document.form1.pcombo;
 PD = document.form1.periododestino;
 PDC = document.form1.perdestinodescr;
 tamC = C.length;
 tamC = tamC==undefined?1:tamC;
 campo = "pcomboselect"+seq;
 valorant = eval("document.form1."+campo+".value");
 if(tamC==1){
  tamPD = PD.length;
  for(r=0;r<tamPD;r++){
   if(parseInt(PD.options[r].value)==parseInt(valor) || parseInt(PDC.options[r].value)==parseInt(valor)){
    PD.options[r].selected = true;
    PDC.options[r].selected = true;
   }
   if(parseInt(PD.options[r].value)==parseInt(valorant) || parseInt(PDC.options[r].value)==parseInt(valorant)){
    PD.options[r].selected = false;
    PDC.options[r].selected = false;
   }
  }
 }else{
  for(i=0;i<tamC;i++){
   tamPD = PD[C[i].value].length;
   if(parseInt(C[i].value)!=parseInt(seq)){
    for(r=0;r<tamPD;r++){
     if(parseInt(PD[C[i].value].options[r].value)==parseInt(valor) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valor)){
      PD[C[i].value].options[r].disabled = true;
      PDC[C[i].value].options[r].disabled = true;
     }
     if(parseInt(PD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valorant)){
      PD[C[i].value].options[r].disabled = false;
      PDC[C[i].value].options[r].disabled = false;
     }
    }
   }else{
     for(r=0;r<tamPD;r++){
     if(parseInt(PD[C[i].value].options[r].value)==parseInt(valor) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valor)){
      PD[C[i].value].options[r].selected = true;
      PDC[C[i].value].options[r].selected = true;
     }
     if(parseInt(PD[C[i].value].options[r].value)==parseInt(valorant) || parseInt(PDC[C[i].value].options[r].value)==parseInt(valorant)){
      PD[C[i].value].options[r].selected = false;
      PDC[C[i].value].options[r].selected = false;
     }
    }
   }
  }
 }
 eval("document.form1."+campo+".value = valor");
}

function js_processar(){
 RO = document.form1.regenciaorigem;
 RD = document.form1.regenciadestino;
 RC = document.form1.regorigemdescr;
 PO = document.form1.periodoorigem;
 PD = document.form1.periododestino;
 PC = document.form1.perorigemdescr;
 tamRO = RO.length;
 tamRO = tamRO==undefined?1:tamRO;
 regequiv = "";
 sepreg = "";
 msgreg = "Atenção:\nAs informações das seguintes disciplinas não serão transportadas, pois as mesmas não contém disciplinas equivalentes na turma de destino:\n\n";
 regnull = false;
 for(i=0;i<tamRO;i++){
  if(tamRO==1){
   if(RD.value!=""){
    if(RD.value!=0){
     regequiv += sepreg+RO.value+"|"+RD.value;
     sepreg = "X";
    }else{
     tamRD = document.form1.regenciadestino.options.length;
     for(t=2;t<tamRD;t++){
      regequiv += sepreg+RO.value+"|"+RD.options[t].value;
      sepreg = "X";
     }
    }
   }else{
    msgreg += RC.value+"\n";
    regnull = true;
   }
  }else{
   if(RD[i].value!=""){
    regequiv += sepreg+RO[i].value+"|"+RD[i].value;
    sepreg = "X";
   }else{
    msgreg += RC[i].value+"\n";
    regnull = true;
   }
  }
 }
 tamPO = PO.length;
 tamPO = tamPO==undefined?1:tamPO;
 perequiv = "";
 sepper = "";
 msgper = "Atenção:\nAs informações dos seguintes períodos de avaliação não serão transportadas, pois os mesmos não contém períodos de avaliação equivalentes na turma de destino:\n\n";
 pernull = false;
 for(i=0;i<tamPO;i++){
  if(tamPO==1){
   if(PD.value!=""){
    perequiv += sepper+PO.value+"|"+PD.value;
    sepper = "X";
   }else{
    msgper += PC.value+"\n";
    pernull = true;
   }
  }else{
   if(PD[i].value!=""){
    perequiv += sepper+PO[i].value+"|"+PD[i].value;
    sepper = "X";
   }else{
    msgper += PC[i].value+"\n";
    pernull = true;
   }
  }
 }
 msggeral = "";
 if(regnull==true){
  msggeral += msgreg+"\n";
 }
 if(pernull==true){
  msggeral += msgper;
 }
 tamRO = RO.length;
 tamRO = tamRO==undefined?1:tamRO;
 regselec = false;
 for(t=0;t<tamRO;t++){
  if(tamRO==1){
   if(RD.value!=""){
    regselec = true;
    break;
   }
  }else{
   if(RD[t].value!=""){
    regselec = true;
    break;
   }
  }
 }
 if(regselec==false){
  alert("Informe alguma disciplina da turma de destino para receber as informações da origem!");
  return false;
 }
 tamPO = PO.length;
 tamPO = tamPO==undefined?1:tamPO;
 perselec = false;
 for(t=0;t<tamPO;t++){
  if(tamPO==1){
   if(PD.value!=""){
    perselec = true;
    break;
   }
  }else{
   if(PD[t].value!=""){
    perselec = true;
    break;
   }
  }
 }
 if(perselec==false){
  alert("Informe algum período de avaliação da turma de destino para receber as informações da origem!");
  return false;
 }
 if(msggeral!=""){
  if(confirm(msggeral+"\n\nConfirmar Transferência do aluno?")){
   document.form1.incluir.style.visibility = "hidden";
   location.href = "edu4_aprovtransfescolafora002.php?incluir&regequiv="+regequiv+"&perequiv="+perequiv+"&etapaorigem="+document.form1.etapaorigem.value+"&matricula=<?=$matricula?>&turmaorigem=<?=$turmaorigem?>&turmadestino=<?=$turmadestino?>";
  }
 }else{
  document.form1.incluir.style.visibility = "hidden";
  location.href = "edu4_aprovtransfescolafora002.php?incluir&regequiv="+regequiv+"&perequiv="+perequiv+"&etapaorigem="+document.form1.etapaorigem.value+"&matricula=<?=$matricula?>&turmaorigem=<?=$turmaorigem?>&turmadestino=<?=$turmadestino?>";
 }
}
</script>
<?
}
if(isset($incluir)){

  db_inicio_transacao();
  $result = $clmatricula->sql_record($clmatricula->sql_query("","ed60_i_aluno,turma.ed57_i_escola as codescoladeorigem",""," ed60_i_codigo = $matricula"));
  db_fieldsmemory($result,0);
  $result0 = $clmatricula->sql_record($clmatricula->sql_query_file("","ed60_i_codigo as codmatrjatem",""," ed60_i_turma = $turmadestino AND ed60_i_aluno = $ed60_i_aluno"));
  $iEtapaDestino = -1;
  if ($clmatricula->numrows > 0) {
 
    $iMatriculaDest = db_utils::fieldsmemory($result0, 0)->codmatrjatem;
    /* Obtenho o codigo da etapa na qual o aluno esta matriculado. Ordeno pelo campo ed221_c_origem porque
       quando a turma for multietapa, o registro da matriculaserie que deve ser considerado neste caso
       eh o que possui o campo ed221_c_origem com 'S'. (como os valores possiveis sao S e N, ordeno 
       decrescente)
    */
    $oDaoMatriculaSerie = db_utils::getdao('matriculaserie');
    $sSql = $oDaoMatriculaSerie->sql_query_file(null, 'ed221_i_serie', 'ed221_c_origem desc',
                                                'ed221_i_matricula = '.$iMatriculaDest
                                               );
    $rs   = $oDaoMatriculaSerie->sql_record($sSql);
    if ($oDaoMatriculaSerie->numrows <= 0) {
      
      db_msgbox('Matrícula não vinculada a nenhuma série. Importação não pode ser realizada.');
      echo '<script>parent.location.href = "edu4_aprovtransfescolafora001.php";</script>';
      exit;
      
    } else {
      $iEtapaDestino = db_utils::fieldsmemory($rs, 0)->ed221_i_serie;
    }
 
  } else {

    db_msgbox('Não encontrada matrícula para o aluno '.$ed60_i_aluno.' na turma '.$turmadestino.'.'.
              'Impossível reaizar importação.'
             );
    echo '<script>parent.location.href = "edu4_aprovtransfescolafora001.php";</script>';
    exit;

  }

  $result = $clturma->sql_record($clturma->sql_query("","ed57_i_calendario,ed57_i_escola",""," ed57_i_codigo = $turmadestino"));
  db_fieldsmemory($result,0);
 
  $periodos = explode("X",$perequiv);
  $msg_conversao = "";
  $sep_conversao = "";
  for($x=0;$x<count($periodos);$x++){
   $divideperiodos = explode("|",$periodos[$x]);
   $periodoorigem = $divideperiodos[0];
   $periododestino = $divideperiodos[1];
   $result_per = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed09_i_codigo,ed09_c_descr as perdestdescricao,ed37_c_tipo as tipodestino,ed37_i_maiorvalor as mvdestino",""," ed41_i_codigo = $periododestino"));
   db_fieldsmemory($result_per,0);
   $result_per1 = $clprocavaliacao->sql_record($clprocavaliacao->sql_query("","ed37_c_tipo as tipoorigem,ed37_i_maiorvalor as mvorigem",""," ed41_i_codigo = $periodoorigem"));
   db_fieldsmemory($result_per1,0);
   if(trim($tipoorigem)!=trim($tipodestino) || (trim($tipoorigem)==trim($tipodestino) && $mvorigem!=$mvdestino) ){
    $msg_conversao .= $sep_conversao." ".$perdestdescricao;
    $sep_conversao  = ",";
   }
   $result_fimper = $clperiodocalendario->sql_record($clperiodocalendario->sql_query_file("","ed53_d_fim,ed53_d_inicio",""," ed53_i_calendario = $ed57_i_calendario AND ed53_i_periodoavaliacao = $ed09_i_codigo"));
   if($clperiodocalendario->numrows>0){
    db_fieldsmemory($result_fimper,0);
   }
   $regencias = explode("X",$regequiv);
   for($r=0;$r<count($regencias);$r++){
    $divideregencias = explode("|",$regencias[$r]);
    $regenciaorigem = $divideregencias[0];
    $regenciadestino = $divideregencias[1];
    $result11 = $cldiario->sql_record($cldiario->sql_query_file("","ed95_i_codigo as coddiarioorigem",""," ed95_i_regencia = $regenciaorigem AND ed95_i_aluno = $ed60_i_aluno"));
    if($cldiario->numrows>0){
     db_fieldsmemory($result11,0);
    }else{
     $coddiarioorigem = 0;
    }
    $result2 = $cldiario->sql_record($cldiario->sql_query_file("","ed95_i_codigo",""," ed95_i_regencia = $regenciadestino AND ed95_i_aluno = $ed60_i_aluno"));
    if($cldiario->numrows==0){
     $cldiario->ed95_c_encerrado = "N";
     $cldiario->ed95_i_escola = $ed57_i_escola;
     $cldiario->ed95_i_calendario = $ed57_i_calendario;
     $cldiario->ed95_i_aluno = $ed60_i_aluno;
     $cldiario->ed95_i_serie = $iEtapaDestino;
     $cldiario->ed95_i_regencia = $regenciadestino;
     $cldiario->incluir(null);
     $ed95_i_codigo = $cldiario->ed95_i_codigo;
    }else{
     db_fieldsmemory($result2,0);
     $sql21 = "UPDATE diario SET
                ed95_c_encerrado = 'N'
               WHERE ed95_i_codigo = $ed95_i_codigo
              ";
     $result21 = db_query($sql21);
    }
    $result9 = $cldiariofinal->sql_record($cldiariofinal->sql_query_file("","ed74_i_diario",""," ed74_i_diario = $ed95_i_codigo"));
    if($cldiariofinal->numrows==0){
     $cldiariofinal->ed74_i_diario = $ed95_i_codigo;
     $cldiariofinal->incluir(null);
    }
    $result3 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query_file("","ed72_i_codigo as codavalorigem,ed72_i_numfaltas,ed72_i_valornota,ed72_c_valorconceito,ed72_t_parecer,ed72_c_aprovmin,ed72_c_amparo,ed72_t_obs,ed72_i_escola,ed72_c_tipo,ed72_c_convertido",""," ed72_i_diario = $coddiarioorigem AND ed72_i_procavaliacao = $periodoorigem"));
    if($cldiarioavaliacao->numrows>0){
     db_fieldsmemory($result3,0);
    }else{
     $codavalorigem = "";
     $ed72_i_numfaltas = null;
     $ed72_i_valornota = null;
     $ed72_c_valorconceito = "";
     $ed72_t_parecer = "";
     $ed72_c_aprovmin = "N";
     $ed72_c_amparo = "N";
     $ed72_t_obs = "";
     $ed72_i_escola = $escola;
     $ed72_c_tipo = "M";
     $ed72_c_convertido = "N";
    }
    if($ed72_i_valornota!="" || $ed72_c_valorconceito!="" || $ed72_t_parecer!=""){
     $ed72_i_escola = $codescoladeorigem;
     $ed72_c_tipo = "M";
     if(trim($tipoorigem)!=trim($tipodestino) || (trim($tipoorigem)==trim($tipodestino) && $mvorigem!=$mvdestino) ){
      $ed72_c_convertido = "S";
     }else{
      $ed72_c_convertido = "N";
     }
    }else{
     $ed72_i_escola = $escola;
     $ed72_c_tipo = "M";
     $ed72_c_convertido = "N";
    }
    $result4 = $cldiarioavaliacao->sql_record($cldiarioavaliacao->sql_query_file("","ed72_i_codigo",""," ed72_i_diario = $ed95_i_codigo AND ed72_i_procavaliacao = $periododestino"));
    if($cldiarioavaliacao->numrows==0){
     $cldiarioavaliacao->ed72_i_diario = $ed95_i_codigo;
     $cldiarioavaliacao->ed72_i_procavaliacao = $periododestino;
     $cldiarioavaliacao->ed72_i_numfaltas = $ed72_i_numfaltas;
     $cldiarioavaliacao->ed72_i_valornota = $ed72_i_valornota;
     $cldiarioavaliacao->ed72_c_valorconceito = $ed72_c_valorconceito;
     $cldiarioavaliacao->ed72_t_parecer = $ed72_t_parecer;
     $cldiarioavaliacao->ed72_c_aprovmin = $ed72_c_aprovmin;
     $cldiarioavaliacao->ed72_c_amparo = $ed72_c_amparo;
     $cldiarioavaliacao->ed72_t_obs = $ed72_t_obs;
     $cldiarioavaliacao->ed72_i_escola = $ed72_i_escola;
     $cldiarioavaliacao->ed72_c_tipo = $ed72_c_tipo;
     $cldiarioavaliacao->ed72_c_convertido = $ed72_c_convertido;
     $cldiarioavaliacao->incluir(null);
     $ed72_i_codigo = $cldiarioavaliacao->ed72_i_codigo;
    }else{
     db_fieldsmemory($result4,0);
     $cldiarioavaliacao->ed72_i_diario = $ed95_i_codigo;
     $cldiarioavaliacao->ed72_i_procavaliacao = $periododestino;
     $cldiarioavaliacao->ed72_i_numfaltas = $ed72_i_numfaltas;
     $cldiarioavaliacao->ed72_i_valornota = $ed72_i_valornota;
     $cldiarioavaliacao->ed72_c_valorconceito = $ed72_c_valorconceito;
     $cldiarioavaliacao->ed72_t_parecer = $ed72_t_parecer;
     $cldiarioavaliacao->ed72_c_aprovmin = $ed72_c_aprovmin;
     $cldiarioavaliacao->ed72_c_amparo = $ed72_c_amparo;
     $cldiarioavaliacao->ed72_t_obs = $ed72_t_obs;
     $cldiarioavaliacao->ed72_i_escola = $ed72_i_escola;
     $cldiarioavaliacao->ed72_c_tipo = $ed72_c_tipo;
     $cldiarioavaliacao->ed72_c_convertido = $ed72_c_convertido;
     $cldiarioavaliacao->ed72_i_codigo = $ed72_i_codigo;
     $cldiarioavaliacao->alterar($ed72_i_codigo);
    }
    if($ed72_i_valornota!="" || $ed72_c_valorconceito!="" || $ed72_t_parecer!=""){
     if($ed72_i_escola!=$escola && $ed72_c_tipo=="M"){
      $cltransfaprov->ed251_i_diarioorigem = $codavalorigem==""?null:$codavalorigem;
      $cltransfaprov->ed251_i_diariodestino = $ed72_i_codigo;
      $cltransfaprov->incluir(null);
     }
     if($codavalorigem!=""){
      $result41 = $clpareceraval->sql_record($clpareceraval->sql_query_file("","ed93_t_parecer",""," ed93_i_diarioavaliacao = $codavalorigem"));
      $linhas41 = $clpareceraval->numrows;
      if($linhas41>0){
       $clpareceraval->excluir(""," ed93_i_diarioavaliacao = $ed72_i_codigo");
       for($w=0;$w<$linhas41;$w++){
        db_fieldsmemory($result41,$w);
        $clpareceraval->ed93_i_diarioavaliacao = $ed72_i_codigo;
         $clpareceraval->ed93_t_parecer = $ed93_t_parecer;
        $clpareceraval->incluir(null);
       }
      }
      $result42 = $clabonofalta->sql_record($clabonofalta->sql_query_file("","ed80_i_codigo",""," ed80_i_diarioavaliacao = $codavalorigem"));
      $linhas42 = $clabonofalta->numrows;
      if($linhas42>0){
       for($w=0;$w<$linhas42;$w++){
        db_fieldsmemory($result42,$w);
        $clabonofalta->ed80_i_diarioavaliacao = $ed72_i_codigo;
        $clabonofalta->ed80_i_codigo = $ed80_i_codigo;
        $clabonofalta->alterar($ed80_i_codigo);
       }
      }
     }
    }
   }
  }
  db_fim_transacao();
  ?><script>document.getElementById("tab_aguarde").style.visibility = "hidden";</script><?
  if($msg_conversao!=""){
   $mensagem = "ATENÇÃO!\\n\\n Caso o aluno tenha algum aproveitamento nos períodos abaixo relacionados, os mesmos deverão ser convertidos no Diário de Classe, devido a forma de avaliação da turma de origem ser diferente da turma de destino:\\n\\n$msg_conversao";
   db_msgbox($mensagem);
  }
  db_msgbox("Importação realizada com sucesso!");
  ?><script>parent.location.href = "edu4_aprovtransfescolafora001.php";</script><?
}
?>
<script>document.getElementById("tab_aguarde").style.visibility = "hidden";</script>