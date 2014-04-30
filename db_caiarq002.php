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
<form name="form1" action="" method="post">
	<center>
          
    <table width="69%" border="0" cellspacing="0">
      <tr> 
        <td width="23%">Nome Arquivo:</td>
        <td width="77%"><input name="arqret" type="hidden" id="arqret" value="<?=$arq_tmpname?>" size="30" maxlength="30"> 
          <input name="arqname" type="text" id="arqname" value="<?=$arq_name?>" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td>Banco:</td>
        <td><input name="k15_codbco" type="text" id="k15_codbco" value="<?=$k15_codbco?>" size="4" maxlength="3"></td>
      </tr>
      <tr> 
        <td>Agencia:</td>
        <td><input name="k15_codage" type="text" id="k15_codage" value="<?=$k15_codage?>" size="6" maxlength="5"></td>
      </tr>
      <tr> 
        <td>Linhas:</td>
        <td><input name="totalproc" type="text" id="totalproc" value="<?=$totalproc?>" size="11" maxlength="10"></td>
      </tr>
      <tr> 
        <td>Valor total pago:</td>
        <td><input name="totalvalorpago" type="text" id="totalvalorpago" value="<?=$totalvalorpago?>" size="11" maxlength="10"></td>
      </tr>
      <tr>
        <td>Conta:</td>
        <td><input name="k15_conta" type="text" id="k15_conta" value="<?=$k15_conta?>" size="6" maxlength="5">
          <input name="c01_nome" type="text" id="c01_nome" value="<?=$k13_descr?>" size="41" maxlength="40"></td>
      </tr>
      <tr align="center"> 
        <td colspan="2"><input name="geradisbanco" type="submit" id="geradisbanco" value="Processar Arquivo"> 
          &nbsp;&nbsp; <input name="Cancela" type="button"  onclick="location.href='cai4_baixabanco.php'" id="Cancela" value="Cancela"></td>
      </tr>
    </table>
      </center>
</form>