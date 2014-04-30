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

//MODULO: Ambulatorial
$oSauConfig->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("s103_c_bpaorgao");
$oRotulo->label("s103_c_bpasigla");
$oRotulo->label("s103_c_bpaibge");
?>
<form name="form_bpa" method="post" action="">
  <fieldset style="width: 370px; margin-top: 40px;">
    <legend>
      <b>BPA</b>
    </legend>
    <table style="padding: 10px;">
      <tr>
        <td>
          <b>Secretaria Destino:</b>
        </td>
        <td>
          <?db_input('s103_c_bpasecrdestino', 25, @$Is103_c_bpaorgao, true, 'text', $db_opcao);?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Sigla:</b>
        </td>
        <td>
          <?db_input('s103_c_bpasigla', 25, @$Is103_c_bpasigla, true, 'text', $db_opcao);?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Código do IBGE:</b>
        </td>
        <td>
          <?db_input('s103_c_bpaibge', 25, @$Is103_c_bpaibge, true, 'text', $db_opcao);?>
        </td>
      </tr> 
      <tr>
        <td colspan="2" align="center">
          <input type="submit" value="<?=($db_opcao==1?'Incluir':'Alterar')?>" 
                 name="<?=($db_opcao==1?'incluir':'alterar')?>" style="margin-top: 10px;">
        </td>
      </tr>
    </table>
  </fieldset>
</form>