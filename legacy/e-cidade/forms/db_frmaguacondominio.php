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

  //MODULO: agua
  $claguacondominio->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("z01_nome");
  $clrotulo->label("x01_numcgm");
  
  if ($db_opcao == 1) {
    
    $db_action="agu1_aguacondominio004.php";
  } else if ($db_opcao == 2 || $db_opcao == 22) {
    
    $db_action="agu1_aguacondominio005.php";
  } else if ($db_opcao == 3 || $db_opcao == 33) {
    
    $db_action="agu1_aguacondominio006.php";
  }
?>
<fieldset style="margin-top: 20px;">
  <legend><b>Cadastro de Codominios - Condominio</b></legend>
  <form name="form1" method="post" action="<?=$db_action?>">
    <center>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tx31_codcondominio?>">
            <?=@$Lx31_codcondominio?>
          </td>
          <td>
            <?
              db_input('x31_codcondominio', 10, $Ix31_codcondominio, true, 'text', 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tx31_matric?>">
            <?
              db_ancora(@$Lx31_matric, "js_pesquisax31_matric(true);", $db_opcao);
            ?>
          </td>
          <td>
            <?
              db_input('x31_matric', 10, $Ix31_matric, true, 'text', $db_opcao, " onchange='js_pesquisax31_matric(false);'");
              
              global $x01_numcgm;
              
              if (isset($z01_nome)) {
                
                $x01_numcgm = $z01_nome;
              }
              
              db_input('x01_numcgm', 40, $Ix01_numcgm, true, 'text', 3, '');
            ?>
          </td>
        </tr>
      </table>
    </center>
    <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
           type="submit" id="db_opcao"
           value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
           <?=($db_botao == false ? "disabled" : "")?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
</fieldset>

<script>
  function js_pesquisax31_matric(mostra) {
    
    if (mostra == true) {
      
      js_OpenJanelaIframe('top.corpo.iframe_aguacondominio', 'db_iframe_aguabase', 
        'func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|z01_nome', 'Pesquisa', true);
    } else {
      
      if (document.form1.x31_matric.value != '') {
        
        js_OpenJanelaIframe('top.corpo.iframe_aguacondominio', 'db_iframe_aguabase', 
          'func_aguabase.php?pesquisa_chave=' + document.form1.x31_matric.value + 
          '&funcao_js=parent.js_mostraaguabase', 'Pesquisa', false);
      } else {
        
        document.form1.x01_numcgm.value = '';
      }
    }
  }
  
  
  function js_mostraaguabase(chave, erro){
    
    document.form1.x01_numcgm.value = chave; 
    
    if (erro == true) {
      
      document.form1.x31_matric.focus(); 
      document.form1.x31_matric.value = '';
    }
  }
  
  
  function js_mostraaguabase1(chave1, chave2){
    
    document.form1.x31_matric.value = chave1;
    document.form1.x01_numcgm.value = chave2;
    db_iframe_aguabase.hide();
  }

  
  function js_pesquisa() {
    
    js_OpenJanelaIframe('top.corpo.iframe_aguacondominio', 'db_iframe_aguacondominio', 
      'func_aguacondominio.php?funcao_js=parent.js_preenchepesquisa|x31_codcondominio|x01_numcgm',
      'Pesquisa', true, '0', '1');
  }
  
  
  function js_preenchepesquisa(chave) {
    
    db_iframe_aguacondominio.hide();
    <?
      if ($db_opcao != 1) {
        echo " location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa='+chave";
      }
    ?>
  }
</script>