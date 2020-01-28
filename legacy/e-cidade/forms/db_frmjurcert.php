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
<table width="80%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td width="18%"><b>Código:</b></td>
    <td width="82%"> 
      <input type="text" name="v56_codigo" tabindex="1" value="<?=@$v56_codigo?>" size="6" maxlength="6" readonly>
    </td>
  </tr>
  <tr> 
    <td width="18%"><b>Certid&atilde;o:</b></td>
    <td width="82%"> 
      <input type="text" name="v56_certid" tabindex="2" value="<?=@$v56_certid?>" size="30" maxlength="30">
    </td>
  </tr>
  <tr> 
    <td width="18%"><b>Processo:</b></td>
    <td width="82%"> 
      <input type="text" name="v56_proces" tabindex="3" value="<?=$v56_proces?>" size="30" maxlength="30">
    </td>
  </tr>
  <tr> 
    <td width="18%"><b>Data:</b></td>
    <td width="82%"> 
	  <?
	   db_data("data",@$data_dia,@$data_mes,@$data_ano);  
	  ?>
              <b>at&eacute;</b> 
              <input type="text" name="data_cons_dia" tabindex="7" size="2" maxlength="2" class="data_consulta">
              <b>/</b> 
              <input type="text" name="data_cons_mes" tabindex="8" size="2" maxlength="2" class="data_consulta">
              <b>/</b> 
              <input type="text" name="data_cons_ano" tabindex="9" size="4" maxlength="4" class="data_consulta">
    </td>
  </tr>
  <tr> 
    <td width="18%"><b>Executado:</b></td>
    <td width="82%"> 
      <input type="text" name="v56_execut" tabindex="10" value="<?=$v56_execut?>" size="40" maxlength="40">
    </td>
  </tr>
  <tr> 
    <td width="18%">
      <?
	    db_label_blur('vara','vara','vara','varadescr');	  
	  ?>
    </td>
    <td width="82%"> 
     <?
	  db_text_blur('vara','vara','varadescr',5,10,$v56_vara,$v56_vara);
	  db_text_blur('vara','varadescr','vara',15,15,$varadescr,$varadescr);
	?>	
    </td>
  </tr>
  <tr> 
    <td width="18%"><b>Endereço:</b></td>
    <td width="82%"> 
      <input type="text" name="v56_endere" tabindex="13" value="<?=@$v56_endere?>" size="40" maxlength="40">
    </td>
  </tr>
  <tr> 
        <td width="18%"> <strong>Movimenta&ccedil;&atilde;o:</strong></td>
    <td width="82%">&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="2" height="120"> 
      <textarea name="v56_movim" tabindex="15" rows="6" cols="92"><?=@$v56_movim?></textarea>
    </td>
  </tr>
  <tr>
        <td height="35" colspan="2" nowrap> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
          <input name="enviar" type="submit" id="enviar" value="Enviar"></td>
          </tr>
        </table>
	  </form>
</center>