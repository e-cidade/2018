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

//MODULO: pessoal
$clrotulo = new rotulocampo;
?>
<form name="form1" method="post" action="">
<center>
<table border="0" style='padding-top:20px'>
<td>
      <fieldset><legend><b> Gera��o do C�lculo de Provis�o </b></legend>
<table>
  <tr>
    <td nowrap title="Ano / M�s de compet�ncia" align="left">
      <b>Ano / M�s:</b>
    </td>
    <td> 
      <?
      if(!isset($anousu)){
        $anousu = db_anofolha();
      }
      if(!isset($mesusu)){
        $mesusu = db_mesfolha();
      }
        db_input('anousu', 4, @$Irh49_anousu, true, 'text', 1, "")
      ?>
      &nbsp;/&nbsp;
      <?
        db_input('mesusu', 2, @$Irh49_mesusu, true, 'text', 1, "")
      ?>
    </td>
  </tr>
  <tr >
    <td align="left" nowrap title="Selecione o Tipo de Gera��o do C�lculo de Provis�o" >
    <strong>Tipo Gera��o:</strong>
    </td>
    <td>
      <?
        // necess�rio definir nome para o select
        $aTipos = array("F"=>"F�rias", "D"=>"13� Sal�rio");             
        db_select("tipoger", $aTipos, true, 1); 
      ?>
    </td>
  </tr>
</table>  
</fieldset>
</td>  
</table>
</center>
<input name="processar" type="submit" id="db_opcao" value="Processar" <?=($db_botao==false?"disabled":"")?> onclick="return js_testacampos();">
</form>
<script>
function js_testacampos(){
  if(confirm("Todos os registros ser�o processados. Deseja continuar?")){
    return true;
  }else{
  	return false;
  }
}
</script>