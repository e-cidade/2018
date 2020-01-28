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

//MODULO: 
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr colspan=2>
    <td align="center"><iframe name="procs" id="procs" src="com4_soltransfandsol.php" width="1000" height="130" marginwidth="0" marginheight="0" frameborder="0">
	</iframe></td>
  </tr>
  <tr colspan=2 >
    <td align="center">
    <?/*
    if (isset($licitacao)&&$licitacao!=""){
      $result_cods=$clliclicitem->sql_record($clliclicitem->sql_query_file(null,"*",null,"l21_codliclicita=$licitacao"));
      if ($clliclicitem->numrows>0){
      	if (!isset($cods)){
      		$cods="";
      	}
    	$vir="";
    	for ($w=0;$w<$clliclicitem->numrows;$w++){
    	  db_fieldsmemory($result_cods,$w);
    	  $cods.=$vir.$l21_codpcprocitem;
    	  $vir=",";
    	}
      }
    }*/
     ?>
    <iframe name="itens" id="itens" src="com4_soltransfitens.php?cods=<?=@$cods?>" width="1000" height="230" marginwidth="0" marginheight="0" frameborder="0">
	</iframe>
    </td>
  </tr>
  <td align='center' colspan=2>
    <b>Despacho:</b><?db_textarea("despacho",0,90,'',true,"text",1) ?>
  <td/>
  <tr>
  </tr>
  <tr>
   <?
    db_input('cods',10,'',true,'hidden',3);    
    ?>
    <td align='center' colspan=2><input name='incluir' type='button' value='Incluir' onclick='js_inclui();'></td>
  </tr>
</table>
</center>
</form>
<script>
function js_inclui(){	
	itens.js_submit_form();
	js_buscavalores();
	itens.document.form1.incluir.value='incluir';
	itens.document.form1.submit();
}
function js_buscavalores(){
   	obj= itens.document.form1;
   	valor="";   	
   	for (i=0;i<obj.elements.length;i++){
     	if (obj.elements[i].name.substr(0,6)=="depto_"){
       		cheke=obj.elements[i].name.split("_");
       		if (eval("obj.CHECK_"+cheke[1]+".checked")==true){
	 			var objvalor=new Number(obj.elements[i].value);
	 			if (objvalor!=0){
	   				valor+=obj.elements[i].name+"_"+obj.elements[i].value+"#";
	 			} 
     		}else{
	 			continue;
       		}
     	}
    }
    obj.valores.value = valor;       
}
</script>