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

$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("q02_numcgm");
$clrotulo->label("q02_memo");
$clrotulo->label("q02_obs");
//MODULO: issqn

$acao="iss1_issbase017.php";
?>
<form name="form1" method="post" action="<?=$acao?>">
	
<input type="hidden" value="<?php echo $opcao; ?>" name="postback">	
<center>
  <table >
   <tr>
  	<td>
  	<br>
    	<fieldset><Legend align="left"><b>Outros Dados</b></Legend>
			<table border="0" cellspacing="5" cellpadding="0" width="100%" >
		  	<tr>
			   	<td>
			   		<b>Inscrição Municipal:</b>
			   	</td>
			   	<td>
			   	<?php
			   		db_input('q02_inscr',10,$q02_inscr,true,'text',3);
			   	?>
			   	<br>
			   	</td>
			   </tr>
			   <tr>
			   	<td>
			   		<b>Número do CGM:</b>
			   	</td>
			   	<td>
			   		<?php
			   		db_input('q02_numcgm',10,$q02_numcgm,true,'text',3);
			   		?>
			   		<?php
			   		db_input('z01_nome',50,$z01_nome,true,'text',3);
			   		?>
			   	</td>
		   	</tr>	
		    <tr>
		      <td nowrap title="<?=@$Tq02_memo?>" valign="top">
					<b>Texto:</b>
		      </td>
		      <td > 
					  <?
					  db_textarea('q02_memo',10,84,$Iq02_memo,true,'text',$db_opcao,"")
					  ?>
					</td>
				</tr>
				<tr>
		     <td nowrap title="<?=@$Tq02_obs?>" valign="top">
		    	<?=$Lq02_obs?>
		     </td>
		     <td >
		     	<?
		      	db_textarea('q02_obs',10,84,$q02_obs,true,'text',$db_opcao,"")
		    	?>
		     </td>
		 		</tr>
  		</table>
  		</fieldset>
 		</td>
 	</tr>
</table> 

</center>
<?    
if($db_opcao==22){
  $db_botao=false;
}
?>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2 || $db_opcao==22?"alterar":"excluir"))?>" type="<?=($db_opcao==1?"submit":($db_opcao==2 || $db_opcao==22?"submit":"submit"))?>" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2 || $db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_voltar();" >
</form>
<script type="text/javascript">
function js_voltar(){
 parent.mo_camada('issbase');
}
</script>