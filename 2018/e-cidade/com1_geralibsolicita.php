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
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_solicitem_classe.php");
include("classes/db_pcparam_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clsolicitem = new cl_solicitem;
$clsolicitem1= new cl_solicitem;
$clpcparam = new cl_pcparam;
$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_data");
$clrotulo->label("pc10_resumo");
$clrotulo->label("pc80_codproc");
$clrotulo->label("descrdepto");
$val = false;
if(isset($pc10_numero) && trim($pc10_numero)!=""){
  $val = true;
  $cod = $pc10_numero;
}
$db_opcao=1;
$db_botao=true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<center>
<table border="0" cellspacing="0" cellpadding="0" height='10%'>
  <tr>
    <td align="right" nowrap title="<?=@$Tpc10_numero?>">
      <strong>Solicitação: </strong>
    </td>
    <td align="left" nowrap>
    <?
      $desabilita = false;
      $arr_numero = array();
      $arr_index  = array();
     // die($clsolicitem->sql_query_pcmater(null,"distinct pc10_numero,pc10_data,pc10_resumo,descrdepto","pc10_numero"," pc10_correto='t'"));
      $sql_solicita = $clsolicitem->sql_record($clsolicitem->sql_query_pcmater(null,"distinct pc10_numero,pc10_data,pc10_resumo,descrdepto","pc10_numero"," pc10_correto='t' and pc11_liberado='f'"));
      for($i=0;$i<$clsolicitem->numrows;$i++){
	db_fieldsmemory($sql_solicita,$i,true);
        $select_itens = $clsolicitem1->sql_record($clsolicitem1->sql_query_pcmater(null,"pc11_numero,pc11_codigo,pc11_liberado","pc11_codigo","pc11_numero=$pc10_numero"));
        for($ii=0;$ii<$clsolicitem1->numrows;$ii++){
          db_fieldsmemory($select_itens,$ii);
	  if(!isset($passou)){
          echo "<script>
                  cont=0;
                  contafor = top.corpo.arr_dados.length;
                  for(i=0;i<contafor;i++){
                    if(top.corpo.arr_dados[i]=='item_".$pc11_numero."_".$pc11_codigo."'){
                      cont++;                      
                    }
                  }";
		  if($pc11_liberado=='f'){
		    echo "cont++;";
		  }
		  echo "
                  if(cont==0){
		    top.corpo.arr_dados.push('item_".$pc11_numero."_".$pc11_codigo."');
		    top.corpo.document.form1.valores.value = top.corpo.arr_dados.valueOf();
                  }
                </script>";
	  }
        }
	$arr_numero[$pc10_numero] = $pc10_numero;
	$arr_index[$pc10_numero]  = $i;
	$arr_data = split("/",$pc10_data);
	$pc10_data_dia = $arr_data[0];
	$pc10_data_mes = $arr_data[1];
	$pc10_data_ano = $arr_data[2];
                
      }
      if($clsolicitem->numrows>0){
	db_fieldsmemory($sql_solicita,0,true);
      }else{
	$desabilita = true;
      }

      if(isset($codigo) && trim($codigo)!=""){	
        $pc10_numero = $codigo;
      }
      db_select('pc10_numero',$arr_numero,true,1,"onchange='js_mudasolicita();'");
      if($val==true){
	echo "<script>
                var_obj = document.getElementById('pc10_numero').length;
		for(i=0;i<var_obj;i++){
		  if(document.getElementById('pc10_numero').options[i].value==$cod){
		    document.getElementById('pc10_numero').options[i].selected = true;
		  }
		}
	      </script>";
	db_fieldsmemory($sql_solicita,$arr_index[$cod]);
	$arr_data = split("-",$pc10_data);
	$pc10_data_dia = $arr_data[2];
	$pc10_data_mes = $arr_data[1];
	$pc10_data_ano = $arr_data[0];
      }
    ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tpc10_data?>">
      <strong>Data: </strong>      
    </td>
    <td align="left" nowrap>
    <?
  //    db_inputdata('pc10_data',@$pc10_data_dia,@$pc10_data_mes,@$pc10_data_ano,true,'text',3);
      db_input('pc10_data_dia',2,0,true,'text',3);
      db_input('pc10_data_mes',2,0,true,'text',3);
      db_input('pc10_data_ano',4,0,true,'text',3);
    ?>
    </td>
    <td align="right" nowrap title="<?=@$Tdescrdepto?>">
      <strong>Departamento: </strong>      
    </td>
    <td align="left" nowrap>
    <?
      db_input('descrdepto',40,$Idescrdepto,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Tpc10_resumo?>">
      <strong>Resumo: </strong>      
    </td>
    <td colspan="3" nowrap>
    <?
      db_textarea('pc10_resumo',2,77,$Ipc10_resumo,true,'text',3,"")
    ?>
    </td>
  </tr>
</table>
</center>
</form>
</body>
</html>
<script>
function js_mudasolicita(){
  for(i=0;i<top.corpo.iframe_solicitem.document.form1.length;i++){
    if(top.corpo.iframe_solicitem.document.form1.elements[i].type == 'checkbox'){
      if(top.corpo.iframe_solicitem.document.form1.elements[i].checked == true){
        cont=0;
        for(ii=0;ii<top.corpo.arr_dados.length;ii++){
          if(top.corpo.arr_dados[ii]==top.corpo.iframe_solicitem.document.form1.elements[i].name){
            cont++;
          }    
        }
        if(cont==0){
	  top.corpo.arr_dados.push(top.corpo.iframe_solicitem.document.form1.elements[i].name);
        }
      }else if(top.corpo.iframe_solicitem.document.form1.elements[i].checked == false){
        for(ii=0;ii<top.corpo.arr_dados.length;ii++){
          if(top.corpo.arr_dados[ii]==top.corpo.iframe_solicitem.document.form1.elements[i].name){
	    top.corpo.arr_dados.splice(ii,1);
          }
        }
      }
    }
  }  
  top.corpo.document.form1.valores.value = top.corpo.arr_dados.valueOf();  
  top.corpo.iframe_solicita.location.href = 'com1_geralibsolicita.php?passou=true&pc10_numero='+document.form1.pc10_numero.value;  
}
if(document.form1.pc10_numero.value!=""){
  top.corpo.iframe_solicitem.location.href= 'com1_geralibsolicitem.php?solicita='+document.form1.pc10_numero.value;
}
<?
  if($desabilita==true){
  echo "
    numele = parent.document.form1.length;
    cont = 0;
    for(i=0;i<numele;i++){
      if(top.corpo.document.form1.elements[i].type=='submit' || top.corpo.document.form1.elements[i].type=='button'){
        top.corpo.document.form1.elements[i].disabled=true;
      }
    }
    ";
  }else{
  echo "
    numele = top.corpo.document.form1.length;
    cont = 0;
    for(i=0;i<numele;i++){
      if(top.corpo.document.form1.elements[i].type=='submit' || top.corpo.document.form1.elements[i].type=='button'){
        top.corpo.document.form1.elements[i].disabled=false;
      }
    }
    ";
  }
?>
</script>