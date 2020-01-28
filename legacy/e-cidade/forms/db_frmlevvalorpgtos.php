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

//MODULO: fiscal
$cllevvalorpgtos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("y63_codlev");
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir_html = new cl_iframe_alterar_excluir_html;
?>
<script>
function js_alterar(valor,data){
  var obj    = document.form1;
  valor= new Number(valor);
  valor=valor.toFixed(2);
  obj.y68_valor.value= valor;
  matriz=data.split('/');
  obj.y68_pgto_dia.value=matriz[0];
  obj.y68_pgto_mes.value=matriz[1];
  obj.y68_pgto_ano.value=matriz[2];
}
function js_incluir(){
  var obj    = document.form1;
  var valor  = new Number(obj.y68_valor.value);
  if(isNaN(valor) || valor==""){
    alert("Verifique o valor.");
    document.form1.y68_valor.focus();
    return false;
  }
  d=obj.y68_pgto_dia.value;
  m=obj.y68_pgto_mes.value;
  a=obj.y68_pgto_ano.value;
  if(d=="" || m=="" || a==""){
    alert("Verifique a data do pagamento.");
    obj.y68_pgto_dia.focus();
    return false;
  }
  
  valor=valor.toFixed(2);
  data=d+'/'+m+'/'+a;
  js_incluirlinhas(valor,data);
  obj.y68_pgto_dia.value='';
  obj.y68_pgto_mes.value='';
  obj.y68_pgto_ano.value='';
  document.form1.y68_valor.value="";
}
function js_fechar(){
    js_dados();
    var inputs  = document.getElementsByTagName("INPUT");
    valores='';
    pago=new Number(0);
    espa='';
    for(i=0; i<inputs.length; i++){
        nome=inputs[i].name.substr(0,5);
	if(nome=='linha' && inputs[i].value!=''){
	   matriz=inputs[i].value.split('#');
           valores+= espa+matriz[0]+'-'+matriz[1];
	   espa='HHH';
	   str=new Number(matriz[0]);
	   pago=(pago+str); 
	   inputs[i].value='';
	}
    }
	  pago=pago.toFixed(2)
	  parent.document.form1.y63_pago.value=pago;
	  parent.document.form1.valores.value=valores;
          parent.js_fecha();
}
</script>
<center>
<table>
<tr>
<td align='center'>
<form name="form1" method="post" action="">
<table border="0">
    <tr>
    <td nowrap title="<?=@$Ty68_valor?>">
       <?=@$Ly68_valor?>
    </td>
    <td> 
<?
db_input('y68_valor',10,$Iy68_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ty68_pgto?>">
       <?=@$Ly68_pgto?>
    </td>
    <td> 
<?
db_inputdata('y68_pgto',@$y68_pgto_dia,@$y68_pgto_mes,@$y68_pgto_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan='2' align="center">
     <input name="lancar" type="button" id="db_opcao" value="Lançar" onclick='js_incluir();' <?=($db_botao==false?"disabled":"")?>>
     <input name="fechar" type="button" value="atualizar" onclick='js_fechar();' <?=($db_botao==false?"disabled":"")?>>
     <br>
     <small><b style="color:darkblue;">Para que os dados sejam salvos, clique em atualizar!</b></small>
    </td>
  </tr>
  </table>
</form>
  </center>
  </td>
</tr>  
<tr>
  <td valign="top">  
   <?
	   $cliframe_alterar_excluir_html->colunas =array("y68_valor"=>$Ly68_valor,"y68_pgto"=>$Ly68_pgto);
	   $cliframe_alterar_excluir_html->iframe_width ="350";
	   $cliframe_alterar_excluir_html->iframe_nome ="criatabela";
	   $cliframe_alterar_excluir_html->iframe_height="185";
	   $cliframe_alterar_excluir_html->load="parent.js_monta();";
           if($db_opcao==3){ 
  	     $cliframe_alterar_excluir_html->db_opcao="3";
           } 
           if(isset($sql)){ 
  	     $cliframe_alterar_excluir_html->sql=$sql;
           }
	   $cliframe_alterar_excluir_html->iframe_alterar_excluir_html();

   ?>
   </td>
  </tr>
</table>  
<script>
function js_monta(){
<?
if(isset($valores)){
  $matriz01=split('HHH',$valores);
  for($i=0; $i<count($matriz01); $i++){
    $matriz=split('-',$matriz01[$i]);
    if($db_opcao==33){
      echo " js_incluirlinhas_disabled('".$matriz[0]."','".$matriz[1]."');\n\n  ";
    }else{
      echo " js_incluirlinhas('".$matriz[0]."','".$matriz[1]."');\n\n  ";
    }  
     
  }
  
}  
?>
}
</script>