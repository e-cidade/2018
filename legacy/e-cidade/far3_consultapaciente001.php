<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: educação
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_far_retirada_classe.php");


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfar_retirada = new cl_far_retirada;
$clfar_retirada->rotulo->label();



@$dia1 = substr($data1,0,2);
@$mes1 = substr($data1,3,2);
@$ano1 = substr($data1,6,4);
@$dia2 = substr($data2,0,2);
@$mes2 = substr($data2,3,2);
@$ano2 = substr($data2,6,4);


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<br>
<center>

<table width="100%" border="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
  	<fieldset style="width:67%"><legend><b>Consulta por Paciente</b></legend>
   <table width="100%" border="0" align="center" cellspacing="0">
    <form name="form1" method="post" action="" >
    <tr>
        <td>
       <b>Consulta:</b>
    </td>
    <td> 
       <?
           $sex = array("R"=>"RECEITA","D"=>"DEVOLUÇÃO");
          db_select('',$sex,true,"");
                  
          // $result_tprec = $clfar_tiporeceita->sql_record($clfar_tiporeceita->sql_query_file("","fa03_i_codigo,fa03_c_descr","fa03_c_descr"));
      //db_selectrecord("fa04_i_tiporeceita",$result_tprec,"","","","","","  ","",1);
          ?>
    </td>
        </tr>
         <tr>
     <td >
       <b> Período:</b>
     </td>
     <td>
       <? db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"");?>
       <? db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"");?>
     </td>
     </tr>
  <tr>
    <td nowrap title="<?=@$Tfa04_i_cgsund?>">
       <?
       db_ancora(@$Lfa04_i_cgsund,"js_pesquisafa04_i_cgsund(true);","");
       ?>
    </td>
    <td colspan="3"> 
    <?
       db_input('fa04_i_cgsund',10,@$Ifa04_i_cgsund,true,'text',""," onchange='js_pesquisafa04_i_cgsund(false);'");
       db_input('z01_v_nome',63,@$Iz01_v_nome,true,'text',3,'');
    ?>
    </td>
  </tr>
   </table>
   </fieldset>
   <table>
	<tr>
     <td align="center" colspan="3">
      <input name="consultar" type="submit" id="consultar" value="Consultar" onClick= 'js_botao();'>
      <input name="cancelar" type="button" id="cancelar" value="Cancelar" onClick="location.href='far3_consultapaciente001.php'">
     </td>
    </tr>
   </table>
   </form>
  </td>
 </tr>
</table>

<table width="100%">
 <tr>
  <td align="center" valign="top">
  	<? if(isset($consultar) &&  $fa04_i_cgsund==""){
		echo "<script>alert('Pesquise um CGS');</script>";	
	  }else if(!isset($fa04_i_cgsund) && @$fa04_i_cgsund==""){
	   echo "";
	  }else if(($data1!="") && $data2!=""){?>
	  <iframe frameborder="0" name="consulta" id="consulta" src="far3_consultapaciente002.php?consultar&fa04_i_cgsund=<?=$fa04_i_cgsund?>&z01_v_nome=<?=$z01_v_nome?>&data1=<?=$data1?>&data2=<?=$data2?>" width="740" height="500" scrolling="no"></iframe>				   
	<?}else{?>
		<iframe frameborder="0" name="consulta" id="consulta" src="far3_consultapaciente002.php?consultar&fa04_i_cgsund=<?=$fa04_i_cgsund?>&z01_v_nome=<?=$z01_v_nome?>" width="740" height="500" scrolling="no"></iframe>	
	<?}?>
   </td>
  </tr>
</table>
</center>
</body>
</html>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_pesquisafa04_i_cgsund(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs_und1|z01_i_cgsund|z01_v_nome','Pesquisa',true);
  }else{
     if(document.form1.fa04_i_cgsund.value != ''){
        js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.fa04_i_cgsund.value+'&funcao_js=parent.js_mostracgs_und','Pesquisa',false);
     }else{
       document.form1.z01_i_cgsund.value = ''; 
     }
  }
}
function js_mostracgs_und(chave,erro){
  document.form1.z01_v_nome.value = chave; 
  if(erro==true){ 
    document.form1.fa04_i_cgsund.focus(); 
    document.form1.fa04_i_cgsund.value = ''; 
  }
}
function js_mostracgs_und1(chave1,chave2){
  document.form1.fa04_i_cgsund.value = chave1;
  document.form1.z01_v_nome.value = chave2;
  db_iframe_cgs_und.hide();
}  
function js_botao(){                                 
	 location.href='far3_consultapaciente001.php?fa04_i_cgsund=<?=@$fa04_i_cgsund?>&z01_v_nome=<?=@$z01_v_nome?>&data1=<?=@$data1?>&data2=<?=@$data2?>';	
} 
</script>