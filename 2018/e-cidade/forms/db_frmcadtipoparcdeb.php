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

//MODULO: caixa
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clcadtipoparcdeb->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k40_descr");
$clrotulo->label("k00_descr");
$clrotulo->label("k41_vencini");
if(isset($db_opcaoal)){
   $db_opcao=33;
    $db_botao=false;
}else if(isset($opcao) && $opcao=="alterar"){
    $db_botao=true;
    $db_opcao = 2;
}else if(isset($opcao) && $opcao=="excluir"){
    $db_opcao = 3;
    $db_botao=true;
}else{  
    $db_opcao = 1;
    $db_botao=true;
    if(isset($novo) || isset($_self) && $_self!=""){
     $k41_arretipo = "";
     $k00_descr = "";
   }
} 
?>
<center>
<form name="form1" method="post" action="">
<br>
<table border="0" >
  <tr>
    <td nowrap title="<?=@$Tk41_cadtipoparc?>">
       <?
       db_ancora(@$Lk41_cadtipoparc,"js_pesquisak41_cadtipoparc(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('k41_cadtipoparc',10,$Ik41_cadtipoparc,true,'text',3," onchange='js_pesquisak41_cadtipoparc(false);'")
?>
       <?
       if (isset($k41_cadtipoparc)&&$k41_cadtipoparc!=""){
        $Result_Descr=$clcadtipoparc->sql_record($clcadtipoparc->sql_query_file($k41_cadtipoparc,null,"k40_descr"));        
       	if ($clcadtipoparc->numrows>0){
       		db_fieldsmemory($Result_Descr,0);
       	}
       }
db_input('k40_descr',40,$Ik40_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk41_arretipo?>">
       <?
       db_ancora(@$Lk41_arretipo,"js_pesquisak41_arretipo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k41_arretipo',10,$Ik41_arretipo,true,'text',$db_opcao," onchange='js_pesquisak41_arretipo(false);'")
?>
       <?
db_input('k00_descr',40,$Ik00_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk41_vencini?>"><?=@$Lk41_vencini?>
    </td>
    <td> 
      <?
        db_inputdata('k41_vencini',@$k41_vencini_dia,@$k41_vencini_mes,@$k41_vencini_ano,true,'text',$db_opcao,"")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk41_vencfim?>"><?=@$Lk41_vencfim?>
    </td>
    <td> 
      <?
        db_inputdata('k41_vencfim',@$k41_vencfim_dia,@$k41_vencfim_mes,@$k41_vencfim_ano,true,'text',$db_opcao,"")
       ?>
    </td>
  </tr>
  </tr>
    <td colspan="2" align="center">
 <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="button" onclick="js_valida('<?=$db_opcao?>')" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
 <input name="_self" value="" type="hidden" id="db_opcao" > 
 <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("k41_cadtipoparc"=>@$k41_cadtipoparc,"k41_arretipo"=>@$k41_arretipo);
	 $cliframe_alterar_excluir->chavepri=$chavepri;
	 $cliframe_alterar_excluir->sql     = $clcadtipoparcdeb->sql_query($k41_cadtipoparc);
	 $cliframe_alterar_excluir->campos  ="k41_cadtipoparc,k40_descr,k41_arretipo,k00_descr,k41_vencini,k41_vencfim";
	 $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height ="200";
	 $cliframe_alterar_excluir->iframe_width ="750";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
</form>
</center>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisak41_cadtipoparc(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadtipoparcdeb','db_iframe_cadtipoparc','func_cadtipoparc.php?funcao_js=parent.js_mostracadtipoparc1|k40_codigo|k40_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.k41_cadtipoparc.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadtipoparcdeb','db_iframe_cadtipoparc','func_cadtipoparc.php?pesquisa_chave='+document.form1.k41_cadtipoparc.value+'&funcao_js=parent.js_mostracadtipoparc','Pesquisa',false);
     }else{
       document.form1.k40_descr.value = ''; 
     }
  }
}
function js_mostracadtipoparc(chave,erro){
  document.form1.k40_descr.value = chave; 
  if(erro==true){ 
    document.form1.k41_cadtipoparc.focus(); 
    document.form1.k41_cadtipoparc.value = ''; 
  }
}
function js_mostracadtipoparc1(chave1,chave2){
  document.form1.k41_cadtipoparc.value = chave1;
  document.form1.k40_descr.value = chave2;
  db_iframe_cadtipoparc.hide();
}
function js_pesquisak41_arretipo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_cadtipoparcdeb','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostraarretipo1|k00_tipo|k00_descr','Pesquisa',true,'0','1','775','390');
  }else{
     if(document.form1.k41_arretipo.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_cadtipoparcdeb','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k41_arretipo.value+'&funcao_js=parent.js_mostraarretipo','Pesquisa',false);
     }else{
       document.form1.k00_descr.value = ''; 
     }
  }
}
function js_mostraarretipo(chave,erro){
  document.form1.k00_descr.value = chave; 
  if(erro==true){ 
    document.form1.k41_arretipo.focus(); 
    document.form1.k41_arretipo.value = ''; 
  }
}
function js_mostraarretipo1(chave1,chave2){
  document.form1.k41_arretipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
} 

function js_valida(db_opcao){
 if(db_opcao == 1 || db_opcao == 2){
  obj = document.form1;	 	
  data1 = obj.k41_vencini_ano.value+obj.k41_vencini_mes.value+obj.k41_vencini_dia.value;
  data2 = obj.k41_vencfim_ano.value+obj.k41_vencfim_mes.value+obj.k41_vencfim_dia.value;	
  if(data1 > data2){
   alert('Vencimento Inicial maior que o Vencimento Final');	 	
   return false;
  }
 }
 document.form1._self.value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>";	
 document.form1.submit();
}
</script>