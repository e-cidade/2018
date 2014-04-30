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
$clrhvisavale->rotulo->label();
$clrhvisavalecad->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh27_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<td>

<fieldset><legend><b>Lançar no ponto</b></legend>
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
    <td nowrap title="<?=@$Trh47_rubric?>" align="right">
      <?
      db_ancora(@$Lrh47_rubric,"js_pesquisarh47_rubric(true);",3);
      ?>
    </td>
    <td> 
      <?
      db_input('rh47_rubric',4,$Irh47_rubric,true,'text',3," onchange='js_pesquisarh47_rubric(false);'")
      ?>
      <?
      db_input('rh27_descr',50,$Irh27_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
</table>
</fieldset>  
</td>  
</table>
</center>
<input name="incluir" type="submit" id="db_opcao" value="Lançar" <?=($db_botao==false?"disabled":"")?> onclick="return js_testacampos();">
</form>
<script>
function js_testacampos(){
  if(document.form1.rh47_rubric.value=='') {
    alert("Rubrica não definida para Lançar Vale Alimentação no Ponto!");
    return false;
  }

  if(confirm("Todos os registros atuais com a rubrica "+document.form1.rh47_rubric.value+" ("+document.form1.rh27_descr.value+") serão excluídos no ponto de salário.\n\nDeseja continuar?")){
    return true;
  }
  return false;
}
</script>