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

$ano_folha          = db_anofolha();
$mes_folha          = db_mesfolha();
$arquivo_importacao = "";
?>
<form name="form1" id="form1" method="post" action="pes2_importaatestados002.php" enctype=multipart/form-data>
<center>

<table align=center style="margin-top:15px;">
<tr><td>

<fieldset>
<legend><b> Importação de Atestados </b></legend>

<table border="0">
<?
db_input('acao', 5, "",true,'hidden', 3,"")
?>
  <tr>
    <td nowrap title="Ano">
       <b>Ano:</b>
    </td>
    <td> 
			<?
			 db_input('ano_folha', 5, "",true,'text', 1,"")
			?>
    <td>
  <tr>
  
  <tr>
    <td nowrap title="Mês">
       <b>Mês:</b>
    </td>
    <td> 
			<?
			 db_input('mes_folha', 5, "",true,'text', 1,"")
			?>
    <td>
  <tr>
  <tr>
    <td nowrap title="Arquivo">
       <b>Arquivo:</b>
    </td>
    <td> 
			<?
			 db_input('arquivo_importacao',30, "",true,'file',$db_opcao,"")
			?>
    <td>
  <tr>
 </table>

</fieldset>
</td></tr>
</table>

  </center>
  
<input name="processar" type="button" id="processar" value="Processar" onclick="enviaDados(this)">
<input name="consistencia" type="button" id="consistencia" value="Consistência" onclick="enviaDados(this)">

</form>

<script>

function enviaDados(botao) {
  
  if (botao.id == 'processar') {
    
    $('acao').value = 'processar';
    $('form1').submit();
    
  } else if (botao.id == 'consistencia') {
    
    $('acao').value = 'consistencia';
    $('form1').submit();    
  }
}



</script>