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
    <table width="76%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="9%" height="25" nowrap><strong>C&oacute;digo:</strong></td>
        <td width="31%" height="25" nowrap><input class="campo" name="id" type="text" id="id" value="<?=@$id?>" size="5" maxlength="5" readonly></td>
        <td width="8%" height="25" nowrap>&nbsp;</td>
        <td width="52%" height="25" nowrap>&nbsp;</td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Organiza&ccedil;&atilde;o:</strong></td>
        <td height="25" nowrap><input name="organizacao" type="text" id="organizacao" value="<?=@$organizacao?>" size="47" maxlength="100"></td>
        <td height="25" nowrap><strong>Nome:</strong></td>
        <td height="25" nowrap><input name="nome" type="text" id="nome" value="<?=@$nome?>" size="47" maxlength="100"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Endere&ccedil;o:</strong></td>
        <td height="25" nowrap><input name="rua" type="text" id="rua" value="<?=@$rua?>" size="47" maxlength="100"></td>
        <td height="25" nowrap><strong>Bairro:</strong></td>
        <td height="25" nowrap><input name="bairro" type="text" id="bairro" value="<?=@$bairro?>" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Cidade:</strong></td>
        <td height="25" nowrap><input name="cidade" type="text" id="cidade" value="<?=@$cidade?>" size="30" maxlength="30"></td>
        <td height="25" nowrap><strong>UF:</strong></td>
        <td height="25" nowrap><input name="uf" type="text" id="uf" value="<?=@$uf?>" size="2" maxlength="2"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>CEP:</strong></td>
        <td height="25" nowrap><input name="cep" type="text" id="cep" value="<?=@$cep?>" size="10" maxlength="10"></td>
        <td height="25" nowrap><strong>Telefone:</strong></td>
        <td height="25" nowrap><input name="telefone" type="text" id="telefone" value="<?=@$telefone?>" size="47" maxlength="50"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Fax:</strong></td>
        <td height="25" nowrap><input name="fax" type="text" id="fax" value="<?=@$fax?>" size="47" maxlength="50"></td>
        <td height="25" nowrap><strong>Celular:</strong></td>
        <td height="25" nowrap><input name="celular" type="text" id="celular" value="<?=@$celular?>" size="47" maxlength="50"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Email:</strong></td>
        <td height="25" nowrap><input name="email" type="text" id="email" value="<?=@$email?>" size="47" maxlength="60"></td>
        <td height="25" nowrap><strong>P&aacute;gina:</strong></td>
        <td height="25" nowrap><input name="pagina" type="text" id="pagina" value="<?=@$pagina?>" size="47" maxlength="60"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Observa&ccedil;&otilde;es:</strong></td>
        <td height="25" colspan="3" nowrap><textarea name="obs" cols="80" rows="5" id="obs"><?=@$obs?></textarea></td>
      </tr>
      <tr>
        <td height="25" nowrap>&nbsp;</td>
        <td height="25" colspan="3" nowrap><input name="enviar" type="submit" id="enviar" value="Enviar"></td>
      </tr>
    </table>
  </form>
</center>