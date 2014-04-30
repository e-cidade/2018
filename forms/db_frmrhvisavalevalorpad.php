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
$clrhvisavalecad->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh01_numcgm");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<td>
  <fieldset><legend><b>Lançar valor padrão</b></legend>
  <table>
  <tr>
    <td nowrap title="Ano / Mês de competência" align="right">
      <b>Ano / Mês:</b>
    </td>
    <td> 
      <?
      if(!isset($rh49_anousu)){
        $rh49_anousu = db_anofolha();
      }
      if(!isset($rh49_mesusu)){
        $rh49_mesusu = db_mesfolha();
      }
      db_input('rh49_anousu',4,$Irh49_anousu,true,'text',3,"")
      ?>
      &nbsp;/&nbsp;
      <?
      db_input('rh49_mesusu',2,$Irh49_mesusu,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Valor padrão para todos os funcionários" align="right">
      <b>Valor padrão:</b>
    </td>
    <td> 
      <?
      if(!isset($rh49_valor)){
        $rh49_valor = 0;
      }
      db_input('rh49_valor',10,$Irh49_valor,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>

</table>
</fieldset>
</td>

</table>
</center>
<input name="incluir" type="submit" id="db_opcao" value="Lançar valor" <?=($db_botao==false?"disabled":"")?> onclick="return js_testacampos();" onblur="document.form1.rh49_valor.focus();">
</form>
<script>
function js_testacampos(){
  if(document.form1.rh49_valor.value == ""){
    alert("Informe o valor.");
    document.form1.rh49_valor.focus();
    return false;
  }else{
    return true;
  }
}
</script>