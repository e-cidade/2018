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
  <form action="" method="post" enctype="multipart/form-data" name="form1" onSubmit="return js_submeter()">
<table width="55%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="26%" height="25" nowrap><strong>N&uacute;mero da Lei:</strong></td>
        <td width="74%" height="25">
		<input name="numerolei" type="text" id="numerolei" value="<?=@$numerolei?>" size="20" maxlength="20"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Data da Lei:</strong></td>
        <td height="25"><input name="datalei_dia" type="text" id="datalei_dia" value="<?=@$datalei_dia?>" onkeyUp="js_digitadata(this.name)" size="2" maxlength="2">
          / 
          <input name="datalei_mes" type="text" id="datalei_mes" value="<?=@$datalei_mes?>" onkeyUp="js_digitadata(this.name)" size="2" maxlength="2">
          /
          <input name="datalei_ano" type="text" id="datalei_ano" value="<?=@$datalei_ano?>" size="4" maxlength="4"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Ementa:</strong></td>
        <td height="25"><input name="ementa" type="text" id="ementa" value="<?=@$ementa?>" size="50" maxlength="200"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Arquivo:</strong></td>
        <td height="25"><input name="arq" type="file" id="arq" size="35"></td>
      </tr>
      <tr> 
        <td height="25" nowrap>&nbsp;</td>
        <td height="25"><input name="incluir" type="submit" id="incluir" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>>
          &nbsp; 
          <input name="alterar" type="submit" id="alterar" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>>
          &nbsp; 
          <input name="excluir" type="submit" id="excluir" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>></td>
      </tr>
    </table></form>
	</center>