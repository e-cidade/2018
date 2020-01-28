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
<p>&nbsp;</p>
<center>
  <form name="form1" method="post" >
    <table width="64%" border="1" cellspacing="0" cellpadding="0">
      <tr> 
        <td colspan="2" nowrap bgcolor="#CDCDFF"  style="font-size:13px" align="center"><div align="center"><strong> 
            Departamentos:</strong></div></td>
      </tr>
      <tr> 
        <td nowrap style="font-size:13px" align="left"><strong>Descri&ccedil;&atilde;o 
          : </strong></td>
        <td nowrap style="font-size:13px" align="left"><strong> 
          <input name="descrdepto" type="text" id="descrdepto2" size="40" maxlength="40" value="<?=@$retornoDescr?>">
          </strong></td>
      </tr>
      <tr> 
        <td nowrap style="font-size:13px" align="left"><strong>Nome do respons&aacute;vel 
          :</strong> </td>
        <td nowrap style="font-size:13px" align="left"><input name="nomeresponsavel" type="text" id="nomeresponsavel2" value="<?=@$retornonomeresponsavel?>" size="40" maxlength="40"></td>
      </tr>
      <tr> 
        <td width="32%" nowrap style="font-size:13px" align="left"><strong>Email 
            do respons&aacute;vel : </strong></td>
        <td width="68%" nowrap style="font-size:13px" align="left"> <input name="emailresponsavel" type="text" id="emailresponsavel2" value="<?=@$retornoemailresponsavel?>" size="50" maxlength="50"> 
        </td>
      </tr>
      <tr> 
        <td colspan="2" nowrap><div align="left"> 
            <input name="coddepto" type="hidden" id="coddepto" value="<?=@$retornoCod?>">
          </div></td>
      </tr>
      <tr> 
        <td colspan="2" nowrap>
          <div align="center"> 
            <input name="alterar" type="submit" id="alterar2"  value="Alterar" <? echo isset($retornoCod)?"":"disabled" ?>>
            &nbsp;&nbsp; 
            <input name="excluir" type="submit" id="excluir2" value="Excluir" <? echo isset($retornoCod)?"":"disabled" ?>>
            &nbsp;&nbsp; 
            <input name="cancelar" type="reset" id="cancelar2" value="Cancelar">
            &nbsp;&nbsp; 
            <input name="procurar" type="submit" id="procurar" value="Procurar">
          </div></td>
      </tr>
    </table>
  </form>
  </center>