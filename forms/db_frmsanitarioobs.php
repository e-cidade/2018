<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clrotulo->label("y80_codsani");
$clrotulo->label("y80_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("y80_obs");
$clrotulo->label("y80_texto");

?>
<form name="form1" method="post" action="">
	
<center>
			<table border="0" >
		  	<tr>
			   	<td>
			   		<?=@$Ly80_codsani?>
			   	</td>
			   	<td>
			   	<?
			   		db_input('y80_codsani',10,$Iy80_codsani,true,'text',3);
			   	?>
			   	<br>
			   	</td>
			   </tr>
			   <tr>
			   	<td>
			   		<?=@$Ly80_numcgm ?>
			   	</td>
			   	<td>
			   		<?
			   		db_input('y80_numcgm',10,$Iy80_numcgm,true,'text',3);
			   		?>
			   		<?
			   		db_input('z01_nome',50,$Iz01_nome,true,'text',3);
			   		?>
			   	</td>
		   	</tr>	
		    <tr>
		      <td nowrap title="<?=@$Ty80_obs?>" valign="top">
						<?=@$Ly80_obs?>
		      </td>
		      <td > 
					  <?
					  db_textarea('y80_obs',10,60,$Iy80_obs,true,'text',$db_opcao,"")
					  ?>
					</td>
				</tr>
				<tr>
		     <td nowrap title="<?=@$Ty80_texto?>" valign="top">
		    	<?=$Ly80_texto?>
		     </td>
		     <td >
		     	<?
		      	db_textarea('y80_texto',10,60,$Iy80_texto,true,'text',$db_opcao,"")
		    	?>
		     </td>
		 		</tr>
  		</table>

</center>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2 || $db_opcao==22?"alterar":"excluir"))?>" type="<?=($db_opcao==1?"submit":($db_opcao==2 || $db_opcao==22?"submit":"submit"))?>" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2 || $db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_voltar();" >
</form>
<script type="text/javascript">
function js_voltar(){
 parent.mo_camada('issbase');
}
</script>