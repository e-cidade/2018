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
  <table width="34%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="27%" height="30"><strong>C&oacute;digo:</strong></td>
      <td width="73%" height="30"><input name="codigo" type="text" id="codigo" value="<?=@$codigo?>" size="10" <? echo isset($readonly)?"readonly":"" ?> autocomplete="off"></td>
    </tr>
    <tr>
      <td height="30" nowrap><strong>Descri&ccedil;&atilde;o:&nbsp;&nbsp;&nbsp;</strong></td>
      <td height="30"><input name="descr" type="text" id="descr" value="<?=@$descr?>" size="15" maxlength="15"  autocomplete="off"></td>
    </tr>
    <tr>
      <td height="30">&nbsp;</td>
      <td height="30"><input name="<?=$submit?>" type="submit" id="enviar" value="<?=ucfirst($submit)?>"></td>
    </tr>
  </table>
</form>
</center>