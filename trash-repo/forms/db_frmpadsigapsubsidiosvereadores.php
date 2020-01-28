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

//MODULO: Contabilidade
$clpadsigapsubsidiosvereadores->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<center>
<form name="form1" method="post" action="">

  <table>
    <tr>
      <td>
      <fieldset>
        <legend><b>Subsídio para Vereadores</b></legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tc16_sequencial?>">
               <?=@$Lc16_sequencial?>
            </td>
            <td> 
            <?
            db_input('c16_sequencial',10,$Ic16_sequencial,true,'text', 3,"");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tc16_mes?>">
               <?=@$Lc16_mes?><b>/</b><?=@$Lc16_ano ?>
            </td>
            <td> 
            <?
            db_input('c16_mes',2,$Ic16_mes,true,'text',$db_opcao,"");
            echo "/";
            db_input('c16_ano',4,$Ic16_ano,true,'text',$db_opcao,"");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tc16_numcgm?>">
               <?
               db_ancora(@$Lc16_numcgm,"js_pesquisac16_numcgm(true);",$db_opcao);
               ?>
            </td>
            <td> 
              <?
              db_input('c16_numcgm',10,$Ic16_numcgm,true,'text',$db_opcao," onchange='js_pesquisac16_numcgm(false);'");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tc16_subsidiomensal?>">
               <?=@$Lc16_subsidiomensal?>
            </td>
            <td> 
            <?
            db_input('c16_subsidiomensal', 10, $Ic16_subsidiomensal,true,'text',$db_opcao,"");
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tc16_subsidioextraordinario?>">
               <?=@$Lc16_subsidioextraordinario?>
            </td>
            <td> 
            <?
            db_input('c16_subsidioextraordinario', 10, $Ic16_subsidioextraordinario,true,'text',$db_opcao,"");
            ?>
            </td>
          </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>  
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
</center>
<script>
function js_pesquisac16_numcgm(mostra) {

  if (mostra==true) {
  
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_cgm',
                        'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
                        'Pesquisar',
                        true);
  } else {
     if (document.form1.c16_numcgm.value != '') { 
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_cgm',
                            'func_nome.php?pesquisa_chave='+document.form1.c16_numcgm.value+
                            '&funcao_js=parent.js_mostracgm',
                            'Pesquisar',
                            false);
     } else {
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(chave,erro) {

  document.form1.z01_nome.value = chave; 
  if (erro == true) {
   
    document.form1.c16_numcgm.focus(); 
    document.form1.c16_numcgm.value = ''; 
  }
}

function js_mostracgm1(chave1, chave2) {

  document.form1.c16_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisa() {
  js_OpenJanelaIframe('top.corpo', 
                      'db_iframe_padsigapsubsidiosvereadores',
                      'func_padsigapsubsidiosvereadores.php?funcao_js=parent.js_preenchepesquisa|c16_sequencial',
                      'Pesquisar Subsídios',
                      true);
}
function js_preenchepesquisa(chave) {

  db_iframe_padsigapsubsidiosvereadores.hide();
  <?
  if ($db_opcao !=1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>