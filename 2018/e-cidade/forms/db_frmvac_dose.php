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

//MODULO: Vacinas
$clvac_dose->rotulo->label();
?>
<fieldset style='width: 75%;'> <legend><b>Dose</b></legend>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tvc03_i_codigo?>">
       <?=@$Lvc03_i_codigo?>
    </td>
    <td> 
     <?db_input('vc03_i_codigo',10,$Ivc03_i_codigo,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc03_c_codpni?>">
       <?=@$Lvc03_c_codpni?>
    </td>
    <td> 
     <?db_input('vc03_c_codpni',10,$Ivc03_c_codpni,true,'text',$db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvc03_c_descr?>">
       <?=@$Lvc03_c_descr?>
    </td>
    <td> 
     <?db_input('vc03_c_descr',10,$Ivc03_c_descr,true,'text',$db_opcao,"")?>
    </td>
  </tr>
<?if ($db_opcao==2) {?>

  <tr>
    <td nowrap title="<?=@$Tvc03_i_ordem?>" colspan="2">
        <table border="0">
          <tr>
            <td rowspan="3">
              <select name="vc03_i_ordem" size="5" style="width: 200px">
                <?
                  $sSql=$clvac_dose->sql_query("","vc03_i_codigo,vc03_c_descr","vc03_i_ordem");
                  $rsResult=$clvac_dose->sql_record($sSql);
                  for ($iX=0;$iX<$clvac_dose->numrows;$iX++) {

                    $oDose=db_utils::fieldsmemory($rsResult,$iX);
                    echo"<option value=\"$oDose->vc03_i_codigo\">$oDose->vc03_c_descr</option>";

                  }
                ?>
              </select>
            </td>
            <td>
              <input type="button" value="Cima" onclick="js_troca('up');">
            </td>
          <tr>
          <tr>
            <td>
              <input type="button" Value="Baixo" onclick="js_troca('down');">
            </td>
          </tr>
        </table>
        <?db_input('sLista',10,"",true,'hidden',$db_opcao,"")?>
    </td>
  </tr>
<?}?>
  </table>
  </center>
<input name  = "<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type  = "submit" 
       id    = "db_opcao" 
       value = "<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
              <?=($db_botao==false?"disabled":"")?> 
       onclick="return js_listar();" >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
</fieldset>

<script>

function js_troca(direcao) {

  iTam      = document.form1.vc03_i_ordem.length;
  iSelected = document.form1.vc03_i_ordem.selectedIndex;
  iFator    = 0;
  if ((iSelected > 0) && (iSelected != -1) && (direcao == 'up')) {
    iFator = -1;
  }
  if ((iSelected < (iTam - 1)) && (iSelected != -1) && (direcao == 'down')) {
    iFator = 1;
  }
  if (iFator != 0) {

    iAlvo                                                = iSelected+iFator;
    iOptionTemp                                          = new Option('0', '0');
    iOptionTemp.text                                     = document.form1.vc03_i_ordem.options[iSelected].text;
    iOptionTemp.value                                    = document.form1.vc03_i_ordem.options[iSelected].value;
    iOptionTemp2                                         = new Option('0', '0');
    iOptionTemp2.text                                    = document.form1.vc03_i_ordem.options[iAlvo].text;
    iOptionTemp2.value                                   = document.form1.vc03_i_ordem.options[iAlvo].value;
    document.form1.vc03_i_ordem.options[iSelected].text  = iOptionTemp2.text;
    document.form1.vc03_i_ordem.options[iSelected].value = iOptionTemp2.value;
    document.form1.vc03_i_ordem.options[iAlvo].text      = iOptionTemp.text;
    document.form1.vc03_i_ordem.options[iAlvo].value     = iOptionTemp.value;
    document.form1.vc03_i_ordem.selectedIndex            = iAlvo;

  }

}

function js_listar() {

  iTam=document.form1.vc03_i_ordem.length;
  if (iTam == 0) {
    alert('Isso não pode estar acontecendo!');
  }
  document.form1.sLista.value='';
  sSep = '';
  for (iX = 0; iX < iTam; iX++) {

    document.form1.sLista.value += sSep+document.form1.vc03_i_ordem.options[iX].value;
    sSep                         = ',';

  }
  return true;

}

function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_vac_dose',
                      'func_vac_dose.php?funcao_js=parent.js_preenchepesquisa|vc03_i_codigo',
                      'Pesquisa',
                      true
                     );

}

function js_preenchepesquisa(chave) {

  db_iframe_vac_dose.hide();
  <?
  if ($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>

}
</script>