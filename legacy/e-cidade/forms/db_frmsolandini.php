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

//MODULO: licitação
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr colspan=2>
    <td align="center"><iframe name="procs" id="procs" src="com4_solandinisol.php" width="1000" height="130" marginwidth="0" marginheight="0" frameborder="0">
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
    <iframe name="itens" id="itens" src="com4_solandiniitens.php?cods=<?=@$cods?>" width="1000" height="230" marginwidth="0" marginheight="0" frameborder="0">
	</iframe>
    </td>
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
	itens.document.form1.incluir.value='incluir';
	itens.document.form1.submit();
}
</script>