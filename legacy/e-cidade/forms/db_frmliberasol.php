<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$clrotulo->label("pc10_data");
$clrotulo->label("pc10_resumo");
$clrotulo->label("descrdepto");
$desabilita = true;
if(isset($solicita) && trim($solicita)!=""){

  $sql_solicita = $clsolicitem->sql_record($clsolicitem->sql_query_pcmater(null,"distinct pc10_numero,pc10_data,pc10_resumo,descrdepto","pc10_numero"," pc10_correto='t' and pc10_numero=$solicita "));
  
  if($clsolicitem->numrows>0){  	
    $desabilita = false;
    db_fieldsmemory($sql_solicita,0);
    $arr_data = split("-",$pc10_data);
    $pc10_data_dia = $arr_data[2];
    $pc10_data_mes = $arr_data[1];
    $pc10_data_ano = $arr_data[0];
  }
}
?>
<form name="form1" method="post" action="">
<center>


<fieldset style="width: 800px; margin-top: 50px;">
<legend>
  <strong>Dados da Solicitação</strong>
</legend>


<table border='0' width="100%">


  <tr>
    <td align='left' colspan='1' nowrap title="<?=$Tpc10_numero?>" width="150"> 
      <? db_ancora(@$Lpc10_numero,"js_pesquisapc10_numero(true);",1);?>
    </td>
    <td align="left"   nowrap>
      <? db_input('pc10_numero',8,$Ipc10_numero,true,"text",1,"onchange='js_pesquisapc10_numero(false);'"); ?>
    </td>
  </tr>
  
  <tr>
    <td align="left" nowrap title="<?=@$Tpc10_data?>">
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
    </tr>
    
    <tr>
    
    <td align="left" nowrap title="<?=@$Tdescrdepto?>">
      <strong>Departamento: </strong>      
    </td>
    <td align="left" nowrap>
    <?
      db_input('descrdepto',40,$Idescrdepto,true,'text',3);
    ?>
    </td>
  </tr>
  <tr>
    <td colspan="3">
  <fieldset>
  <legend><strong>Resumo</strong></legend>
      <table>
       <tr>
            <td colspan="2" nowrap>
              <?
                db_textarea('pc10_resumo',2,77,$Ipc10_resumo,true,'text',3,"")
              ?>
            </td>
       </tr>
      
      </table>
  </fieldset>
    </td>
  </tr>
  
  
  </table>
</fieldset>
  
  <br>
  
  
      <iframe name="iframe_solicitem" id="solicitem" marginwidth="0" marginheight="0" frameborder="0" src="com1_geralibsolicitem.php?solicita=<?=(@$solicita)?>" width="90%" height="350"></iframe>


</center>
<input name="incluir" type="submit" id="db_opcao" value="Liberar solicitação" <?=($db_botao==false?"disabled":"")?> onclick=' return js_enviardados();'>
<?
//<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
db_input('valores',50,0,true,'hidden',3);
?>
</form>
<script>
function js_enviardados(){
  
  var lVerifica = false;
  vir  = "";
  cont = 0;
  document.form1.valores.value = "";
  
  for(i=0;i<iframe_solicitem.document.form1.length;i++){
    if(iframe_solicitem.document.form1.elements[i].type=="checkbox"){
      if(iframe_solicitem.document.form1.elements[i].checked==true){
      	if(iframe_solicitem.document.form1.elements[i].disabled==false){
          document.form1.valores.value += vir+iframe_solicitem.document.form1.elements[i].name;
	      vir = ",";
	      lVerifica = true;
      	}
      }
    }
  }
  
  if (!lVerifica) {
  	alert("Nenhum registro selecionado!");
  	return false;
  }
  
}
function js_pesquisapc10_numero(mostra){
  qry = "&gerautori=true&ativas=1";
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrapcorcamitem1|pc10_numero'+qry,'Pesquisa',true);
  }else{
    if(document.form1.pc10_numero.value!=""){
      js_OpenJanelaIframe('top.corpo','db_iframe_solicita','func_solicita.php?funcao_js=parent.js_mostrapcorcamitem&pesquisa_chave='+document.form1.pc10_numero.value+qry,'Pesquisa',false);
    }else{
      document.form1.pc10_numero.value = "";
      location.href = "com1_liberasol001.php";
    }
  }
}
function js_mostrapcorcamitem1(chave1,chave2){
  document.form1.pc10_numero.value = chave1;
  db_iframe_solicita.hide();
  location.href = "com1_liberasol001.php?solicita="+chave1;
}
function js_mostrapcorcamitem(chave1,erro){
  if(erro==true){
    document.form1.pc10_numero.value = "";
    document.form1.pc10_numero.focus();
    location.href = "com1_liberasol001.php";
  }else{
    location.href = "com1_liberasol001.php?solicita="+document.form1.pc10_numero.value;
  }
}
<?
  if($desabilita==true){
  echo "
    numele = document.form1.length;
    for(i=0;i<numele;i++){
      if(document.form1.elements[i].type=='submit' || document.form1.elements[i].type=='button'){
        document.form1.elements[i].disabled=true;
      }
    }
    ";
  }else{
  echo "
    numele = document.form1.length;
    for(i=0;i<numele;i++){
      if(document.form1.elements[i].type=='submit' || document.form1.elements[i].type=='button'){
        document.form1.elements[i].disabled=false;
      }
    }
    ";
  }
  ?>
</script>