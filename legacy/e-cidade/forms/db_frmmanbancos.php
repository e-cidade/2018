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
    <table width="52%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
	   <tr> 
        <td height="25"><strong>C&oacute;d. do Banco:</strong></td>
        <td height="25"><input name="k15_codbco" type="text" id="k15_codbco" value="<?=@$k15_codbco?>" <? echo isset($alterar)?"readonly":"" ?> size="5"></td>
      </tr>
      <tr> 
        <td height="25"><strong>C&oacute;d. da Ag&ecirc;ncia:</strong></td>
        <td height="25"><input name="k15_codage" type="text" id="k15_codage" value="<?=@$k15_codage?>" <? echo isset($alterar)?"readonly":"" ?> size="5" maxlength="5"></td>
      </tr>
        <td width="40%" height="25" nowrap>
		  <?
		    include("dbforms/db_funcoes.php");
		    db_label_blur('numcgm','Numcgm/Nome','nome','descrnome');
		  ?>
		</td>
        <td width="60%" height="25" nowrap>
		<?
		  db_text_blur('numcgm','nome','descrnome',10,20,@$k15_numcgm,@$k15_numcgm);
		  db_text_blur('numcgm','descrnome','nome',40,100,@$nomedescr,@$k15_numcgm);
	    ?>
		</td>
      </tr>
      <tr> 
        <td height="25"><strong>Contato:</strong></td>
        <td height="25"><input name="k15_contat" type="text" id="k15_contat" value="<?=@$k15_contat?>" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Gerente:</strong></td>
        <td height="25"><input name="k15_gerent" type="text" id="k15_gerent" value="<?=@$k15_gerent?>" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Nome da Ag&ecirc;ncia:</strong></td>
        <td height="25"><input name="k15_agenci" type="text" id="k15_agenci" value="<?=@$k15_agenci?>" size="11" maxlength="11"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Local:</strong></td>
        <td height="25"><input name="k15_local" type="text" id="k15_local" value="<?=@$k15_local?>" size="40" maxlength="40"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Carteira:</strong></td>
        <td height="25"><input name="k15_carte" type="text" id="k15_carte" value="<?=@$k15_carte?>" size="2" maxlength="2"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Esp&eacute;cie:</strong></td>
        <td height="25"><input name="k15_espec" type="text" id="k15_espec" value="<?=@$k15_espec?>" size="20" maxlength="20"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Aceite:</strong></td>
        <td height="25"><input name="k15_aceite" type="text" id="k15_aceite" value="<?=@$k15_aceite?>" size="10" maxlength="10"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Ag&ecirc;ncia Cedente:</strong></td>
        <td height="25"><input name="k15_ageced" type="text" id="k15_ageced" value="<?=@$k15_ageced?>" size="30" maxlength="30"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Tx. Banc&aacute;ria:</strong></td>
        <td height="25"><input name="k15_txban" type="text" id="k15_txban" value="<?=@$k15_txban?>" size="10"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Rec. Taxa Banc&aacute;ria:</strong></td>
        <td height="25"><input name="k15_rectxb" type="text" id="k15_rectxb" value="<?=@$k15_rectxb?>" size="10"></td>
      </tr>
      <tr> 
        <td height="25" nowrap><strong>Conta Padr&atilde;o p/ Baixa:&nbsp;&nbsp;</strong></td>
        <td height="25"><input name="k15_conta" type="text" id="k15_conta" value="<?=@$k15_conta?>" size="10"></td>
      </tr>
      <tr> 
        <td height="25">&nbsp;</td>
        <td height="30"><input name="enviar" type="submit" id="enviar" value="Enviar"></td>
      </tr>
    </table>
  </form>
</center>