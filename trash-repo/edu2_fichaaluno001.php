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

//MODULO: educa��o
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_aluno_classe.php");
include("classes/db_serie_classe.php");
include("classes/db_alunocurso_classe.php");
include("classes/db_alunopossib_classe.php");
include("classes/db_cursoescola_classe.php");
include("libs/db_jsplibwebseller.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$claluno = new cl_aluno;
$clserie = new cl_serie;
$clalunocurso = new cl_alunocurso;
$clalunopossib = new cl_alunopossib;
$clcursoescola = new cl_cursoescola;
$clrotulo = new rotulocampo;
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed60_i_codigo");
$clrotulo->label("ed47_v_nome");
$clrotulo->label("ed47_v_pai");
$clrotulo->label("ed47_v_mae");
$clrotulo->label("ed56_c_situacao");
$clrotulo->label("ed223_i_serie");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed56_i_escola");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<script>
nextfield = "campo1"; // nome do primeiro campo
netscape = "";
ver = navigator.appVersion;
len = ver.length;
for(iln = 0; iln < len; iln++)
 if (ver.charAt(iln) == "(")
 break;
netscape = (ver.charAt(iln+1).toUpperCase() != "C");
function keyDown(DnEvents){
 k = (netscape) ? DnEvents.which : window.event.keyCode;
 if(k == 13){ // pressiona tecla enter
  if(nextfield == 'done'){
   return true; // envia quando termina os campos
  }else {
   eval(" document.getElementById('"+nextfield+"').focus()" );
   return false;
  }
 }
}
document.onkeydown = keyDown;
if(netscape)
 document.captureEvents(Event.KEYDOWN|Event.KEYUP);

function js_redireciona(chave){
 imp_matricula = document.getElementById("imp_matricula").checked==true?"yes":"no";
 imp_historico = document.getElementById("imp_historico").checked==true?"yes":"no";
 imp_movimentacao = document.getElementById("imp_movimentacao").checked==true?"yes":"no";
 jan = window.open('edu2_fichaaluno002.php?alunos='+chave+'&imp_matricula='+imp_matricula+'&imp_historico='+imp_historico+'&imp_movimentacao='+imp_movimentacao,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
 location.href = "edu2_fichaaluno001.php";
}
</script>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" action="">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<br>
<center>
<fieldset style="width:95%"><legend><b>Relat�rio Ficha do Aluno</b></legend>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC">
 <tr>
  <td valign="top">
   <table width="100%" border="0" cellspacing="0">
    <tr>
     <td nowrap title="<?=$Ted47_i_codigo?>">
      <?=$Led47_i_codigo?>
     </td>
     <td nowrap>
      <?db_input("ed47_i_codigo",10,@$Ied47_i_codigo,true,"text",1,"onFocus=\"nextfield='pesquisar'\"");?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=$Ted47_v_nome?>">
      <?=$Led47_v_nome?>
     </td>
     <td nowrap>
      <?db_input("ed47_v_nome",50,@$ed47_v_nome,true,"text",1,"onFocus=\"nextfield='pesquisar'\"");?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=$Ted47_v_pai?>">
      <?=$Led47_v_pai?>
     </td>
     <td nowrap>
      <?db_input("ed47_v_pai",50,@$ed47_v_pai,true,"text",1,"onFocus=\"nextfield='pesquisar'\"");?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=$Ted47_v_mae?>">
      <?=$Led47_v_mae?>
     </td>
     <td nowrap>
      <?db_input("ed47_v_mae",50,@$ed47_v_mae,true,"text",1,"onFocus=\"nextfield='pesquisar'\"");?>
     </td>
    </tr>
   </table>
  </td>
  <td valign="top">
   <table border="0" cellspacing="0">
    <tr>
     <td nowrap title="<?=$Ted56_i_escola?>">
      <?=$Led56_i_escola?>
     </td>
     <td>
      <?
      $result_escola = $clalunocurso->sql_record($clalunocurso->sql_query("","DISTINCT ed18_i_codigo,ed18_c_nome"," ed18_c_nome",""));
      if($clalunocurso->numrows==0){
       $x = array(''=>'NENHUM REGISTRO');
       db_select('ed56_i_escola',$x,true,1,"style='width:300px;'");
      }else{
       ?>
       <select name="ed56_i_escola" id="ed56_i_escola" onchange="js_escola(this.value);" style="width:300px;">
        <option value=""></option>
        <?
        for($x=0;$x<$clalunocurso->numrows;$x++){
         db_fieldsmemory($result_escola,$x);
         ?>
         <option value="<?=$ed18_i_codigo?>" <?=@$codescola==$ed18_i_codigo?"selected":""?>><?=$ed18_c_nome?></option>
         <?
        }
        ?>
       </select>
       <?
      }
      ?>
     </td>
    </tr>
    <tr>
     <td>
      <?=$Led56_c_situacao?>
     </td>
     <td>
      <?
      $codescola       = isset($codescola)?(empty($codescola)?0:$codescola):0;
      $disabled        = $codescola!=0?"":"disabled";
      $result_situacao = $clalunocurso->sql_record($clalunocurso->sql_query("","DISTINCT ed56_c_situacao as sit","ed56_c_situacao"," ed56_i_escola = $codescola"));
      if($clalunocurso->numrows==0){
       $x = array(''=>'');
       db_select('ed56_c_situacao',$x,true,1," $disabled style='width:300px;'");
      }else{
       ?>
       <select name="ed56_c_situacao" id="ed56_c_situacao" <?=$disabled?> style='width:300px;'>
        <option value=""></option>
        <?
        for($x=0;$x<$clalunocurso->numrows;$x++){
         db_fieldsmemory($result_situacao,$x);
         ?>
         <option value="<?=$sit?>" <?=@$sit==@$ed56_c_situacao?"selected":""?>><?=$sit?></option>
         <?
        }
        ?>
       </select>
       <?
      }
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=$Ted31_i_curso?>">
      <?=$Led31_i_curso?>
     </td>
     <td>
      <?
      $codescola    = isset($codescola)?(empty($codescola)?0:$codescola):0;
      $result_curso = $clcursoescola->sql_record($clcursoescola->sql_query("","DISTINCT ed29_i_codigo,ed29_c_descr"," ed29_c_descr"," ed71_i_escola = $codescola"));
      if($clcursoescola->numrows==0){
       $x = array(''=>'');
       db_select('ed31_i_curso',$x,true,1," $disabled style='width:300px;'");
      }else{
       ?>
       <select name="ed31_i_curso" id="ed31_i_curso" onchange="js_curso(this.value,document.form1.ed56_i_escola.value);" style="width:300px;" <?=$disabled?>>
        <option value=""></option>
        <?
        for($x=0;$x<$clcursoescola->numrows;$x++){
         db_fieldsmemory($result_curso,$x);
         ?>
         <option value="<?=$ed29_i_codigo?>" <?=@$codcurso==$ed29_i_codigo?"selected":""?>><?=$ed29_c_descr?></option>
         <?
        }
        ?>
       </select>
       <?
      }
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap>
      <?=$Led223_i_serie?>
     </td>
     <td>
      <?
      $codcurso     = isset($codcurso)?(empty($codcurso)?0:$codcurso):0;
      $codescola    = isset($codescola)?(empty($codescola)?0:$codescola):0;
      $disabled1 = $codcurso!=0?"":"disabled";
      $result_serie = $clalunopossib->sql_record($clalunopossib->sql_query("","DISTINCT ed11_i_codigo,ed11_c_descr,ed11_i_sequencia",""," ed31_i_curso = $codcurso AND ed56_i_escola = $codescola"));
      if($clalunopossib->numrows==0){
       $x = array(''=>'');
       db_select('ed223_i_serie',$x,true,1," $disabled1 style='width:300px;'");
      }else{
       ?>
       <select name="ed223_i_serie" id="ed223_i_serie" <?=$disabled1?> style='width:300px;'>
        <option value=""></option>
        <?
        for($x=0;$x<$clalunopossib->numrows;$x++){
         db_fieldsmemory($result_serie,$x);
         ?>
         <option value="<?=$ed11_i_codigo?>" <?=@$ed223_i_serie==$ed11_i_codigo?"selected":""?>><?=$ed11_c_descr?></option>
         <?
        }
        ?>
       </select>
       <?
      }
      ?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td colspan="2" align="center">
   <br>
   <input name="pesquisar" id="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisar();" onFocus="nextfield='done'">
   <input name="limpar" type="button" value="Limpar" onclick="location.href='edu2_fichaaluno001.php'">
  </td>
 </tr>
</table>
</fieldset>
</form>
<table width="100%">
 <tr>
  <td valign="top" align="center" >
   <?
   if(isset($pesquisar)){
    ?>
    <fieldset style="width:95%;"><legend><b>Filtros</b></legend>
     <table>
      <tr>
       <td valign="top">
        <input type="checkbox" id="imp_matricula" value="" checked> Mostrar informa��es de Matr�culas<br>
        <input type="checkbox" id="imp_historico" value="" checked> Mostrar informa��es de Hist�rico<br>
        <input type="checkbox" id="imp_movimentacao" value="" checked> Mostrar informa��es de Movimenta��es<br>
       </td>
      </tr>
     </table>
    </fieldset>
    <fieldset style="width:95%"><legend><b>Selecione o aluno</b></legend><?
    $sql = "SELECT *
            FROM (
                  SELECT distinct on (aluno.ed47_i_codigo) aluno.ed47_i_codigo,
                         aluno.ed47_v_nome,
                         alunocurso.ed56_c_situacao,
                         serie.ed11_c_descr as dl_serie,
                         case when alunocurso.ed56_i_codigo is not null
                          then
                           case when alunocurso.ed56_c_situacao = 'TRANSFERIDO REDE'
                            then
                             (select ed18_c_nome
                              from transfescolarede
                               inner join matricula on ed60_i_codigo = ed103_i_matricula
                               inner join turma on ed57_i_codigo = ed60_i_turma
                               inner join escola on ed18_i_codigo = ed57_i_escola
                              where ed60_i_aluno = ed56_i_aluno
                              and ed57_i_base = ed56_i_base
                              and ed57_i_calendario = ed56_i_calendario
                              order by ed103_d_data desc limit 1)
                            else
                             escola.ed18_c_nome
                            end
                          else null
                         end as dl_escola,
                         calendario.ed52_c_descr as dl_calendario
                  FROM aluno
                   left join alunocurso on alunocurso.ed56_i_aluno = aluno.ed47_i_codigo
                   left join escola on escola.ed18_i_codigo = alunocurso.ed56_i_escola
                   left join calendario on  calendario.ed52_i_codigo = alunocurso.ed56_i_calendario
                   left join base on  base.ed31_i_codigo = alunocurso.ed56_i_base
                   left join cursoedu on  cursoedu.ed29_i_codigo = base.ed31_i_curso
                   left join alunopossib on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo
                   left join serie on  serie.ed11_i_codigo = alunopossib.ed79_i_serie
            ";
    if(isset($ed47_i_codigo)){
     $repassa = array("ed47_i_codigo"=>$ed47_i_codigo);
    }
    $sql .= " WHERE ed47_i_codigo > 0 ";
    if(isset($ed47_i_codigo) && (trim($ed47_i_codigo)!="") ){
     $sql .= " AND ed47_i_codigo = $ed47_i_codigo ";
    }
    if(isset($ed60_i_codigo) && (trim($ed60_i_codigo)!="") ){
     $sql .= " AND ed60_i_codigo = $ed60_i_codigo ";
    }
    if(isset($ed47_v_nome) && (trim($ed47_v_nome)!="") ){
     $sql .= " AND ed47_v_nome like '".strtoupper($ed47_v_nome)."%' ";
    }
    if(isset($ed47_v_pai) && (trim($ed47_v_pai)!="") ){
     $sql .= " AND ed47_v_pai like '".strtoupper($ed47_v_pai)."%' ";
    }
    if(isset($ed47_v_mae) && (trim($ed47_v_mae)!="") ){
     $sql .= " AND ed47_v_mae like '".strtoupper($ed47_v_mae)."%' ";
    }
    if(isset($ed56_i_escola) && (trim($ed56_i_escola)!="") ){
     $sql .= " AND ed56_i_escola = $ed56_i_escola ";
    }
    if(isset($ed56_c_situacao) && (trim($ed56_c_situacao)!="")){
     $sql .= " AND trim(ed56_c_situacao) = '$ed56_c_situacao' ";
    }
    if(isset($ed31_i_curso) && (trim($ed31_i_curso)!="")){
     $sql .= " AND ed31_i_curso = $ed31_i_curso ";
    }
    if(isset($ed223_i_serie) && (trim($ed223_i_serie)!="") ){
     $sql .= " AND ed79_i_serie = $ed223_i_serie ";
    }
    $sql .= "  ) as x ORDER BY to_ascii(ed47_v_nome)";  // <- To ascii ADD
    db_lovrot(@$sql,5,"()","","js_redireciona|ed47_i_codigo","","NoMe",$repassa,false);
    ?></fieldset><?
   }
   ?>
  </td>
 </tr>
</table>
</center>
</body>
</html>
<script>
document.getElementById("ed47_i_codigo").focus();
function js_escola(valor){
 codigo = document.getElementById("ed47_i_codigo").value;
 nome = document.getElementById("ed47_v_nome").value;
 pai = document.getElementById("ed47_v_pai").value;
 mae = document.getElementById("ed47_v_mae").value;
 if(valor==""){
  location.href = "edu2_fichaaluno001.php?loc&ed47_i_codigo="+codigo+"&ed47_v_nome="+nome+"&ed47_v_pai="+pai+"&ed47_v_mae="+mae;
 }else{
  location.href = "edu2_fichaaluno001.php?loc&codescola="+valor+"&ed47_i_codigo="+codigo+"&ed47_v_nome="+nome+"&ed47_v_pai="+pai+"&ed47_v_mae="+mae;
 }
}
function js_curso(valor,escola){
 codigo = document.getElementById("ed47_i_codigo").value;
 nome = document.getElementById("ed47_v_nome").value;
 pai = document.getElementById("ed47_v_pai").value;
 mae = document.getElementById("ed47_v_mae").value;
 situacao = document.getElementById("ed56_c_situacao").value;
 if(valor==""){
  location.href = "edu2_fichaaluno001.php?loc&codescola="+escola+"&ed47_i_codigo="+codigo+"&ed47_v_nome="+nome+"&ed47_v_pai="+pai+"&ed47_v_mae="+mae;
 }else{
  location.href = "edu2_fichaaluno001.php?loc&codcurso="+valor+"&codescola="+escola+"&ed47_i_codigo="+codigo+"&ed47_v_nome="+nome+"&ed47_v_pai="+pai+"&ed56_c_situacao="+situacao+"&ed47_v_mae="+mae;
 }
}
function js_pesquisar(){
 codigo = document.getElementById("ed47_i_codigo").value;
 nome = document.getElementById("ed47_v_nome").value;
 pai = document.getElementById("ed47_v_pai").value;
 mae = document.getElementById("ed47_v_mae").value;
 escola = document.getElementById("ed56_i_escola").value;
 curso = document.getElementById("ed31_i_curso").value;
 serie = document.getElementById("ed223_i_serie").value;
 situacao = document.getElementById("ed56_c_situacao").value;
 location.href = "edu2_fichaaluno001.php?pesquisar&codcurso="+curso+"&codescola="+escola+"&ed47_i_codigo="+codigo+"&ed47_v_nome="+nome+"&ed47_v_pai="+pai+"&ed47_v_mae="+mae+"&ed56_i_escola="+escola+"&ed223_i_serie="+serie+"&ed56_c_situacao="+situacao+"&ed31_i_curso="+curso;
}
<?
if(isset($loc)){
 ?>document.getElementById("pesquisar").click();<?
}
?>
</script>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>