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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rechumano_classe.php");
include("classes/db_rechumanoescola_classe.php");
include("classes/db_telefonerechumano_classe.php");
include("classes/db_db_uf_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesdoc_classe.php");
include("classes/db_rhraca_classe.php");
include("classes/db_rhinstrucao_classe.php");
include("classes/db_rhestcivil_classe.php");
include("classes/db_rhnacionalidade_classe.php");
include("classes/db_periodoescola_classe.php");
include("classes/db_diasemana_classe.php");
db_postmemory($HTTP_POST_VARS);
$escola=db_getsession("DB_coddepto");
$clrechumano = new cl_rechumano;
$clcgm = new cl_cgm;
$cltelefonerechumano = new cl_telefonerechumano;
$clrechumanoescola = new cl_rechumanoescola;
$cldb_uf = new cl_db_uf;
$clrhpessoal = new cl_rhpessoal;
$clrhpesdoc = new cl_rhpesdoc;
$clrhraca = new cl_rhraca;
$clrhinstrucao = new cl_rhinstrucao;
$clrhestcivil = new cl_rhestcivil;
$clrhnacionalidade = new cl_rhnacionalidade;
$cldiasemana = new cl_diasemana;
$clperiodoescola = new cl_periodoescola;
$clrechumano->rotulo->label();
$clcgm->rotulo->label();
$clrhpessoal->rotulo->label();
$clrechumanoescola->rotulo->label();
$clrhpesdoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("z01_nome");
$db_opcao = 1;
$db_botao = true;
if(isset($cod_matricula)){
 $where = " ed20_i_codigo = $cod_matricula";
 $destino = "chavepesquisa=$chavepesquisa&cod_matricula=$cod_matricula";
}else{
 $where = " case when ed20_i_tiposervidor = 1 then cgmrh.z01_numcgm else cgmcgm.z01_numcgm end = $chavepesquisa";
 $destino = "chavepesquisa=$chavepesquisa";
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
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
 font-size: 10;
 font-weight: bold;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 10;
}
</style>
</style>
</head>
<body bgcolor="#f3f3f3" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table bgcolor="#f3f3f3" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td valign="top" bgcolor="#CCCCCC">
   <table border="0" bgcolor="#f3f3f3" width="100%" cellspacing="0" cellpading="0" height="800" >
    <?if($evento==1){
    include("funcoes/db_func_rechumanonovo.php");
    $result = $clrechumano->sql_record($clrechumano->sql_query_escola("","distinct ".$camposrechumano,"",$where));
    ?>
    <tr>
     <td valign="top" >
      <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Dados Admissionais</b></legend>
      <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
       <?
       for($t=0;$t<$clrechumano->numrows;$t++){
        db_fieldsmemory($result,$t);
        ?>
        <tr>
         <td>
          <b><?=$ed20_i_tiposervidor==1?"Matrícula":"CGM"?>:</b> <?=$identificacao?>
         </td>
        </tr>
        <tr>
         <td>
          <b>Regime:</b> <?=$rh30_codreg." - ".$rh30_descr?>
         </td>
        </tr>
        <?if($ed20_i_tiposervidor==1){?>
        <tr>
         <td>
          <?=$Lrh01_funcao?> <?=$rh37_descr==""?"Não Informado":$rh37_descr?>
          &nbsp;&nbsp;
          <?=$Lrh01_lotac?> <?=$r70_descr==""?"Não Informado":$r70_descr?>
         </td>
        </tr>
        <tr>
         <td>
          <?=$Lrh01_admiss?> <?=$rh01_admiss==""?"Não Informado":db_formatar($rh01_admiss,'d')?>
          &nbsp;&nbsp;
          <?=$Lrh01_tipadm?>
          <?
          if($rh01_tipadm==1){
           echo "Admissao do 1o emprego";
          }elseif($rh01_tipadm==2){
           echo "Admissao c/ emprego anterior";
          }elseif($rh01_tipadm==3){
           echo "Transf de empreg s/ onus p/ a cedente";
          }elseif($rh01_tipadm==4){
           echo "Transf de empreg c/ onus p/ a cedente";
          }else{
           echo "Não Informado";
          }
          ?>
         </td>
        </tr>
        <?}?>
        <?if($t<$clrechumano->numrows-1){?>
        <tr>
         <td>&nbsp;
         </td>
        </tr>
        <?}?>
       <?}?>
      </table>
      </fieldset>
     </td>
    </tr>
    <?}?>
    <?if($evento==2){
    include("funcoes/db_func_rechumanonovo.php");
    $result = $clrechumano->sql_record($clrechumano->sql_query_escola("","distinct ".$camposrechumano,"",$where));
    db_fieldsmemory($result,0);
    ?>
    <tr>
     <td valign="top" >
      <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Documentos</b></legend>
      <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
       <tr>
        <td>
         <?=$Lz01_ident?> <?=$z01_ident==""?"Não Informado":$z01_ident?>
         &nbsp;&nbsp;
         <?=$Lz01_cgccpf?> <?=$z01_cgccpf==""?"Não Informado":$z01_cgccpf?>
        </td>
       </tr>
       <tr>
        <td>
         <?=$Lrh16_titele?> <?=$rh16_titele==""?"Não Informado":$rh16_titele?>
         &nbsp;&nbsp;
         <?=$Lrh16_zonael?> <?=$rh16_zonael==""?"Não Informado":$rh16_zonael?>
         &nbsp;&nbsp;
         <?=$Lrh16_secaoe?> <?=$rh16_secaoe==""?"Não Informado":$rh16_secaoe?>
        </td>
       </tr>
       <tr>
        <td>
         <?=$Lrh16_reserv?> <?=$rh16_reserv==""?"Não Informado":$rh16_reserv?>
         &nbsp;&nbsp;
         <?=$Lrh16_catres?> <?=$rh16_catres==""?"Não Informado":$rh16_catres?>
        </td>
       </tr>
       <tr>
        <td>
         <?=$Lrh16_ctps_n?> <?=$rh16_ctps_n==0?"Não Informado":$rh16_ctps_n?>
         &nbsp;&nbsp;
         <?=$Lrh16_ctps_s?> <?=$rh16_ctps_s==0?"Não Informado":$rh16_ctps_s?>
        </td>
       </tr>
       <tr>
        <td>
         <?=$Lrh16_ctps_d?> <?=$rh16_ctps_d==0?"Não Informado":$rh16_ctps_d?>
         &nbsp;&nbsp;
         <?=$Lrh16_ctps_uf?> <?=$rh16_ctps_uf==""?"Não Informado":$rh16_ctps_uf?>
        </td>
       </tr>
       <tr>
        <td>
         <?=$Lrh16_pis?> <?=$rh16_pis==""?"Não Informado":$rh16_pis?>
        </td>
       </tr>
       <tr>
        <td>
         <?=$Lrh16_carth_n?> <?=$rh16_carth_n==""?"Não Informado":$rh16_carth_n?>
         &nbsp;&nbsp;
         <?=$Lr16_carth_cat?> <?=$r16_carth_cat==""?"Não Informado":$r16_carth_cat?>
         &nbsp;&nbsp;
         <?=$Lrh16_carth_val?> <?=$rh16_carth_val==""?"Não Informado":db_formatar($rh16_carth_val,'d')?>
        </td>
       </tr>
      </table>
      </fieldset>
     </td>
    </tr>
    <?}?>
    <?if($evento==3){
    $result2 = $clrechumanoescola->sql_record($clrechumanoescola->sql_query("","ed20_i_tiposervidor,case when ed20_i_tiposervidor = 1 then rechumanopessoal.ed284_i_rhpessoal else rechumanocgm.ed285_i_cgm end as identificacao,ed20_i_codigo,ed18_i_codigo,ed18_c_nome,ed75_d_ingresso, ed75_i_saidaescola","ed20_i_codigo,ed75_d_ingresso desc",$where));
    ?>
    <tr>
     <td valign="top" >
      <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Escolas Atuais</b></legend>
      <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="4">
       <?
       if($clrechumanoescola->numrows>0){
        for($x=0;$x<$clrechumanoescola->numrows;$x++){
         db_fieldsmemory($result2,$x);
         ?>
         <tr>
          <td>
           <table border="0" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
            <tr>
             <td width="10%"><b><?=$ed20_i_tiposervidor==1?"Matrícula":"CGM"?></b>:<?=$identificacao?></td>
             <td width="60%"><?=$Led75_i_escola?> <?=$ed18_i_codigo?> - <?=trim($ed18_c_nome)?></td>
             <td width="15%"><?=$Led75_d_ingresso?> <?=db_formatar($ed75_d_ingresso,'d')?> </td>
             <td width="15%"><?=$Led75_i_saidaescola?> <?=db_formatar($ed75_i_saidaescola,'d')?></td>
            </tr>
           </table>
          </td>
         </tr>
         <?
        }
       }else{
        ?>
        <tr>
         <td>
          Nenhum registro.
         </td>
        </tr>
        <?
       }
       ?>
      </table>
      </fieldset>
     </td>
    </tr>
    <?}?>
    <?if($evento==4){?>
    <tr>
     <td valign="top">
      <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Horários</b></legend>
       <iframe name="frame_horario" src="edu3_rechumanohorario001.php?<?=$destino?>" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
      </fieldset>
    <?}?>
    <?if($evento==5){?>
    <tr>
     <td valign="top">
      <fieldset style="background:#f3f3f3;border:2px solid #000000"><legend class="cabec"><b>Disponibilidade</b></legend>
       <iframe name="frame_disponibilidade" src="edu3_rechumanohoradisp001.php?<?=$destino?>" width="100%" height="600" frameborder="0" scrolling="no"></iframe>
      </fieldset>
    <?}?>
  </td>
 </tr>
</table>
</body>
</html>
<script>
function js_botao(valor){
 if(valor!=""){
  document.form1.procurar.disabled = false;
  document.form1.imprimir.disabled = false;
 }else{
  document.form1.procurar.disabled = true;
  document.form1.imprimir.style.visibility = "hidden";
 }
}
function js_pesquisar(){
 location.href = "edu3_consultaprofessor003.php?chavepesquisa=<?=$chavepesquisa?>&evento=4&ano="+document.form1.ano.value;
}
function js_imprimir(){
 jan = window.open('edu2_horarioprofessor002.php?escola='+document.form1.grupo.value+'&professor=<?=$chavepesquisa?>&calendario='+document.form1.subgrupo.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}

</script>