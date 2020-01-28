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
  
  require("libs/db_stdlib.php");
  require("libs/db_conecta.php");
  include("libs/db_sessoes.php");
  include("classes/db_issbase_classe.php");
  include("dbforms/db_classesgenericas.php");
  include("dbforms/db_funcoes.php");
  include("classes/db_iptuconstrdemo_classe.php");
  include("classes/db_iptuconstr_classe.php");
  $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
  db_postmemory($HTTP_POST_VARS);
  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

  $clrotulo = new rotulocampo;
  $cliptuconstrdemo = new cl_iptuconstrdemo;
  $cliptuconstr = new cl_iptuconstr;

  $clrotulo->label("j60_area");
  $clrotulo->label("j60_codproc");
  $clrotulo->label("j60_idcons");
  $clrotulo->label("j60_datademo");
  $clrotulo->label("j60_area");
  $clrotulo->label("j60_seq");
  $clrotulo->label("j39_matric");
  $clrotulo->label("j39_area");
  $clrotulo->label("z01_nome");
  $clrotulo->label("j39_idcons");
  $db_botao=true;
if(isset($pesq)){
  $result09=$cliptuconstr->sql_record($cliptuconstr->sql_query_file($j39_matric,$j39_idcons,"j39_area"));
  db_fieldsmemory($result09,0);
}  
$data=date("Y-m-d",db_getsession('DB_datausu'));
if(isset($opcao) && $opcao=="alterar"){
    $db_opcao = 2;
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
    $db_opcao = 3;
    if(isset($db_opcaoal)){
	$db_opcao=33;
    }
}else{  
    $db_opcao = 1;
    $matriz01=split("-",$data);
    $j60_datademo_ano=$matriz01[0];
    $j60_datademo_mes=$matriz01[1];
    $j60_datademo_dia=$matriz01[2];
} 
if(isset($incluir) || isset($alterar)){
    $sqlerro=false;
    db_inicio_transacao();
    if(isset($incluir)){
      $result01=$cliptuconstrdemo->sql_record($cliptuconstrdemo->sql_query_file($j39_matric,$j39_idcons,"",'max(j60_seq)+1 as seq'));
      db_fieldsmemory($result01,0);
      $j60_seq = $seq == ""?"1":$seq;
    }  
    
    $cliptuconstrdemo->j60_matric=$j39_matric;
    $cliptuconstrdemo->j60_idcons=$j39_idcons;
    $cliptuconstrdemo->j60_codproc=$j60_codproc;
    $cliptuconstrdemo->j60_area=$j60_area;
    $cliptuconstrdemo->j60_seq=$j60_seq;
    $cliptuconstrdemo->j60_hora=db_hora();
    $cliptuconstrdemo->j60_usuario=db_getsession('DB_id_usuario');;
    $cliptuconstrdemo->j60_data=$data;
    
    if(isset($incluir)){
      $cliptuconstrdemo->incluir($j39_matric,$j39_idcons,$j60_seq);
    }else{  
      $cliptuconstrdemo->alterar($j39_matric,$j39_idcons,$j60_seq);
    }  
    if($cliptuconstrdemo->erro_status==0){
      $sqlerro=true;
    }  
    if(isset($alterar)){
      $j39_area = $j39_area + $j60_area_ant;
    }  
    $j39_area=($j39_area-$j60_area);
    $cliptuconstr->j39_area=$j39_area;
    $cliptuconstr->alterar($j39_matric,$j39_idcons);
    if($cliptuconstr->erro_status==0){
      $sqlerro=true;
    }  
    db_fim_transacao($sqlerro);
}else if(isset($excluir)){
    $sqlerro=false;
    db_inicio_transacao();
    
    $cliptuconstrdemo->j60_matric=$j39_matric;
    $cliptuconstrdemo->j60_idcons=$j39_idcons;
    $cliptuconstrdemo->j60_seq=$j60_seq;
    $cliptuconstrdemo->excluir($j39_matric,$j39_idcons,$j60_seq);
    if($cliptuconstrdemo->erro_status==0){
      $sqlerro=true;
    }  
    
    $j39_area = ($j39_area + $j60_area);
    $cliptuconstr->j39_area = $j39_area;
    $cliptuconstr->alterar($j39_matric,$j39_idcons);
    if($cliptuconstr->erro_status==0){
      $sqlerro=true;
    }  
    db_fim_transacao($sqlerro);
}
if(isset($incluir) || isset($alterar) || isset($excluir)){
    $j60_codproc="";
    $j60_seq="";
    $j60_area="";
    $j60_datademo_ano="";
    $j60_datademo_mes="";
    $j60_datademo_dia="";
}    
  if(isset($opcao) && ($opcao=="alterar" || $opcao=="excluir")){
     $result05=$cliptuconstrdemo->sql_record($cliptuconstrdemo->sql_query_file($j39_matric,$j60_idcons,$j60_seq,"j60_idcons,j60_seq,j60_area,j60_codproc,j60_datademo"));
     db_fieldsmemory($result05,0);     
  }  
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
 function js_verifica(){
   obj=document.form1;
   if(obj.j60_codproc.value==""){
     alert('Processo inválido!');
     return false;
   }
   if(obj.j60_area.value==""){
     alert('Área inválida!');
     return false;
   }
   if(obj.j60_datademo_dia.value=="" || obj.j60_datademo_mes.value=="" || obj.j60_datademo_ano.value==""){
     alert("Data de demolição inválida!");
     return false;
   } 
   j60_area=new Number(obj.j60_area.value);
   j39_area=new Number(obj.j39_area.value);
   if(isNaN(j60_area)){
     alert("Área inválida!");
     return false;
   }
   if(j60_area  >= j39_area){
     alert('Area da demolição não pode ser maior nem igual a da area total!');
     obj.j60_area.focus();
     return false;
   } 
     
   return true;
 }
 function js_cancelar(){
    location.href='cad1_iptuconstrdemo001.php?j39_area=<?=$j39_area?>&j39_matric=<?=$j39_matric?>&z01_nome=<?=$z01_nome?>&j39_idcons=<?=$j39_idcons?>';
 }
<? 
if(isset($incluir) || isset($alterar) || isset($excluir)){
  echo "parent.document.form1.j39_area.value='$j39_area';";
}  
?>  
 </script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%">
<tr>
<td align="center"   bgcolor="#CCCCCC">

<form name="form1" method="post" action="cad1_iptuconstrdemo001.php">
 <table border="0" >
        <tr> 
          <td>     
           <?=$Lj39_matric?>
          </td>
          <td> 
          <?
            db_input('j60_seq',5,$Ij60_seq,true,'hidden',3);
           db_input('j39_matric',5,0,true,'text',3,"onchange='js_matri(false)'");
           db_input('z01_nome',45,0,true,'text',3,"");
          ?>
          </td>
	 </tr> 
        <tr> 
          <td>     
           <?=$Lj39_idcons?>
          </td>
          <td> 
<?
  db_input('j39_idcons',5,$Ij39_idcons,true,'text',3);
?>
	 </td>
        
        </tr>
        <tr> 
          <td>     
           <?=$Lj39_area?>
          </td>
          <td> 
<?
  db_input('j39_area',5,$Ij39_area,true,'text',3);
?>
	 </td>
        
        </tr>
              <tr>
	        <td align="center" colspan="2"> 
 	  	 <br>
	      <table border="1" >
	        <tr>
                <td nowrap title="<?=@$Tj60_codproc?>" colspan="2">
                  <?=@$Lj60_codproc?>
                  <?
                   db_input('j60_codproc',5,$Ij60_codproc,true,'text',$db_opcao);
                  ?>
		 </td> 
		 <td> 
                  <?=@$Lj60_area?>
                  <?
		    if(isset($j60_area)){
		      $j60_area_ant=$j60_area;
		    }
                   db_input('j60_area',5,$Ij60_area,true,'text',$db_opcao," onKeyUp='this.value = this.value.replace(\",\",\".\")'");
                   db_input('j60_area',5,$Ij60_area,true,'hidden',$db_opcao,"","j60_area_ant");
                  ?>
                </td>
                <td nowrap title="<?=@$Tj60_datademo?>">
                  <?=@$Lj60_datademo?>
                  <?
		  if($db_opcao==1){
		    
		  }
		       db_inputdata('j60_datademo',@$j60_datademo_dia,@$j60_datademo_mes,@$j60_datademo_ano,true,'text',$db_opcao,"")
                  ?>
                </td>
		</tr>
		<tr>
		  <td colspan="4" align="center">
                     <input <?=($db_opcao==1||$db_opcao==2?'onclick="return js_verifica();"':"")?>  name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
                     <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
                     <input type="button" name="fechar" value="Fechar" onclick="parent.js_fechar_demo();">
		  </td>
		</tr>  
		</table>
		</td>
              </tr>
	      
              <tr>
	        <td align="center" colspan="2">
		 
<?		
if(isset($j39_idcons)){
    $chavepri= array("j60_idcons"=>$j39_idcons,"j60_seq"=>@$j60_seq);
    $cliframe_alterar_excluir->chavepri=$chavepri;
    $cliframe_alterar_excluir->sql     =$cliptuconstrdemo->sql_query($j39_matric,$j39_idcons,"","j60_idcons,j60_seq,j60_area,j60_codproc,j60_datademo,j60_hora,j60_data");
    $cliframe_alterar_excluir->campos  ="j60_codproc,j60_area,j60_datademo,j60_hora,j60_data";
    $cliframe_alterar_excluir->legenda ="DEMOLIÇÕES";
    $cliframe_alterar_excluir->iframe_width   ="550";
    $cliframe_alterar_excluir->iframe_height   ="180";
    $cliframe_alterar_excluir->msg_vazio ="<small>Não foi encontrado nenhum registro.</small>";
    $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);    
}    
?>
                </td>
              </tr>
  </table>	  
</form>  
</td>
</tr>
</table>
</body>
</html>
<?
if(isset($incluir) || isset($alterar) || isset($excluir)){
    $cliptuconstrdemo->erro(true,false);
}    
?>