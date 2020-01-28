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
$clrotulo = new rotulocampo;
$clrotulo->label("pc01_descrmater");
$clrotulo->label("");
$opcao=1;
if (isset($pc01_descrmater)&&$pc01_descrmater!=""){
	$opcao=3;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
  
  <td align='center' colspan=2>
    <strong>Descrição de Material :</strong>
   <?  db_input('pc01_descrmater',80,$Ipc01_descrmater,true,'text',$opcao,"") ?> 
   
  </td>
  </tr>
  <?if (isset($pc01_descrmater)&&$pc01_descrmater!=""){?>
  <tr>
    <td align="center" colspan=2>
      <iframe name="itens" id="itens" src="com1_desatmat002.php?descrmater=<?=@$pc01_descrmater?>" width="1000" height="400" marginwidth="0" marginheight="0" frameborder="0">
	  </iframe>
    </td>
  </tr>
  <tr>
  <td align='center' colspan=2><input name='incluir' type='button' value='Desativar Marcados' onclick='js_inclui();'></td>
  </tr>
  <?}else{?>
  	<tr>
  <td align='center' colspan=2><input name='Enviar' type='submit' value='Enviar'></td>
  </tr>
  <?}?>
  
</table>
</center>
</form>
<script>
function js_inclui(){
	itens.document.form1.incluir.value='incluir';
	itens.document.form1.submit();
}
</script>