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

//MODULO: Habitacao
$clhabitinscricao->rotulo->label();
$clhabitinscricaocancelamento->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("z01_nome");
$clrotulo->label("ht01_descricao");
$clrotulo->label("ht20_candidato");
$clrotulo->label("ht13_habitprograma");

?>
<form name="form1" method="post" action="">
<fieldset>
<legend>
  <b>Inclusão de Desistência</b>
</legend>
<table border="0" cellpadding="1" cellspacing="1">
  <tr>
    <td nowrap title="<?=@$Tht15_sequencial?>">
      <b>Inscrição:</b>
    </td>
    <td colspan="3"> 
      <?
        db_input('ht15_sequencial', 10,'', true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tht20_habitcandidato?>">
      <b>Candidato:</b>
    </td>
    <td width="20px"> 
      <?
        db_input('ht20_habitcandidato', 10,'', true, 'text', 3);
      ?>
    </td>
    <td colspan="2">
      <?
        db_input('z01_nome', 40,'', true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tht13_habitprograma?>">
      <b>Programa:</b>
    </td>
    <td> 
      <?
        db_input('ht13_habitprograma', 10,'', true, 'text', 3);
      ?>
    </td>
    <td colspan="2">
      <?
        db_input('ht01_descricao', 40,'', true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tht15_datalancamento?>">
      <b>Data:</b>
    </td>
    <td colspan="3"> 
      <?
        db_input('ht15_datalancamento', 10,'', true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="4">
       <fieldset>
         <legend>
           <b>Motivo</b>
         </legend>
         <table border="0" cellpadding="0" cellspacing="0" width="100%">
           <tr valign="top">
             <td> 
               <?
                 db_textarea('ht22_motivo', 5, 70, $Iht22_motivo, true, 'text', $db_opcao);
               ?>
             </td>
           </tr>
         </table>
       </fieldset>
    </td>
  </tr>
  </table>
</fieldset>
<table align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>
      <input type="submit" id="incluir" name="incluir" value="Incluir" <?=($db_opcao==1?"":"disabled")?>>
      <input type="button" id="pesquisar" name="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    </td>
  </tr>
</table>
</form>
<script>
function js_pesquisa() {

  var sUrl = 'func_habitinscricao.php?desistencia=true&funcao_js=parent.js_preenchepesquisa|ht15_sequencial';
  js_OpenJanelaIframe('top.corpo', 'db_iframe_habitinscricao', sUrl, 'Pesquisa', true);
}

function js_preenchepesquisa(chave) {

  db_iframe_habitinscricao.hide();
  <?
	  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
</script>