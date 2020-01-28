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
<form name="form1" method="post" action="">
	<center>

	<fieldset><legend><b>Triagem</b></legend>
	<table border="1">
		<tr><td colspan="2"><b>Motivo:</b><?=@$sd24_v_motivo?></td></tr>
		<tr><td><b>Pressão:</b><?=@$sd24_v_pressao?></td><td><b>Peso:</b><?=@$sd24_f_peso?></td></tr>
		<tr><td colspan="2"><b>Temperatura:</b><?=@$sd24_f_temperatura?></td></tr>
		<tr><td colspan="2"><b>Profissional:</b><?=@$profissional_triagem?></td></tr>
		<tr><td colspan="2"><b>CBO:</b><?=@$cbo_triagem?></td></tr>
	</table>
	</fieldset>

</center>
<p>
<input name="botao_ok" type="submit" id="botao_ok" value="Fechar" onclick="parent.db_iframe_triagem.hide();">
</form>