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
?>
<center>
<form name="form1" method="post">
    <table width="68%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
		<? if(isset($alterar)) { 
		     $conta = pg_exec("select saltes.k13_conta,c01_descr 
			                   from saltes
							        inner join saltesplan on saltesplan.k13_conta = saltes.k13_conta and saltesplan.c01_anousu = ".db_getsession("DB_anousu")."
					                inner join plano l on l.c01_anousu = ".db_getsession("DB_anousu")." and l.c01_reduz = saltesplan.c01_reduz
							   where saltes.k13_conta = $k13_conta");
			 db_fieldsmemory($conta,0);
		?>
		<td width="29%" nowrap style="font-size:12px">
		  <strong>Conta:&nbsp;&nbsp;</strong><?=$k13_conta?>
		</td>
        <td width="31%" nowrap style="font-size:12px">
		  <strong>Descrição:&nbsp;&nbsp;</strong><?=$c01_descr?>
		   <input type="hidden" name="k13_conta" value="<?=$k13_conta?>">
		</td>	  			          
		<? } else { ?>
		  
        <td width="12%" height="25"><strong>Conta:</strong></td>
          <td width="28%" height="25">
		  <?=db_contas("k13_conta")?>
		  </td>
		<? } ?>		
      </tr>
      <tr> 
        <td height="25"><input type="hidden" name="val_ant">
          <strong>Saldo Inicial:</strong></td>
        <td height="25"><input name="k13_saldo" type="text" id="k13_saldo" value="<?=@$k13_saldo?>" size="20"></td>
      </tr>
      <tr> 
        <td height="25"><strong>IP Terminal:</strong></td>
        <td height="25"><input name="k13_ident" type="text" id="k13_ident" value="<?=@$k13_ident?>" size="20" maxlength="15"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Valor Atualizado:</strong></td>
        <td height="25"><input name="k13_vlratu" type="text" id="k13_vlratu" value="<?=@$k13_vlratu?>" size="20"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Data do Valor:</strong></td>
        <td height="25"><?=db_data("k13_datvlr",@$k13_datvlr_dia,@$k13_datvlr_mes,@$k13_datvlr_ano)?></td>
      </tr>
      <tr>
        <td height="25">&nbsp;</td>
        <td height="25"><input name="enviar" type="submit" id="enviar" value="Enviar">
        <?
		 if(isset($alterar)) { 
		 ?>
	      <input name="cancela" type="button" id="cancela" value="Cancela" onclick="location.href='cai1_saltes002.php'"></td>
         <?
		 }
		?>
      </tr>
    </table>
</form>
</center>