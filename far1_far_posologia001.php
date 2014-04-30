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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
$db_opcao=1;
$db_botao=true;
$fa06_t_posologia=$posologiat;
?>
<form name="form2" method="post" action="">
<center>
<fieldset><legend><b>Posologia</b></legend>
	<table border="0">
			  <!--  CGS / Nome -->
			  <tr>
			    <td nowrap title="<?=@$Ts115_c_cartaosus?>">
			       <?=@$Ls115_c_cartaosus?>
			    </td>
			    <td>
			      <?
			       db_textarea('fa06_t_posologia',1,70,@$Ifa06_t_posologia,true,'text',$db_opcao,"")
			      ?>
			    </td>
			  </tr>
			  	</table>
</fieldset>	
</center>
<p>
<input name="salvar" 
       type="button" id="salvar" 
       value="Salvar"
       onFocus="nextfield='done'" 
       onclick="js_envia();";
>
<input name="fechar" type="button" id="fechar" value="Fechar" onclick="js_fechar();">

</form>

<script type="text/javascript">

//Tempo estimado para fechar janela para não demorar no agendamento
window.setInterval(js_fechar, 60000 );

function js_fechar(){
	parent.db_iframe_posologia.hide();
}
function js_envia(){
  <?if(!isset($iGrid)){?>
    parent.document.form1.posologia_edit.value=document.form2.fa06_t_posologia.value;
  <?}else{?>
    parent.js_atualizaPosologia(<?=$iGrid?>,document.form2.fa06_t_posologia.value);
  <?}?>
  js_fechar();
}



</script>