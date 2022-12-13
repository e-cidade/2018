<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
  $clrhcadregime->rotulo->label();
  $clbaserhcadregime->rotulo->label();
  $clbases->rotulo->label();
?>
<form name="form1" id="form1" method="post" action="" class="container">
    <fieldset>
      <legend>Regime</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?=@$Trh52_regime?>" width="65">
             <?=@$Lrh52_regime?>
          </td>
          <td> 
            <?
            db_input('rh52_regime',2,$Irh52_regime,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Trh52_descr?>">
             <?=@$Lrh52_descr?>
          </td>
          <td> 
            <?
            db_input('rh52_descr',20,$Irh52_descr,true,'text',$db_opcao,"class='field-size-max'")
            ?>
          </td>
        </tr>
      </table>

      <fieldset class='separator'>
        <legend>Bases para o servidor</legend>
        <table>
          <tr>
            <td>
               <a href="javascript:void(0)" id="substituto" title="<?=$Trh158_basesubstituto?>"><?=$Lrh158_basesubstituto?></a>
            </td>
            <td> 
              <?php
                db_input('rh158_basesubstituto',20,$Irh158_basesubstituto,true,'text',1,"lang='r08_codigo'");
                db_input('descricao_substituto',20,$Ir08_descr,true,'text',3,"lang='r08_descr'");
              ?>
            </td>
          </tr>
          <tr>
            <td>
               <a href="javascript:void(0)" id="substituido" title="<?=$Trh158_basesubstituido?>"><?=$Lrh158_basesubstituido?></a>
            </td>
            <td> 
              <?php
                db_input('rh158_basesubstituido',20,$Irh158_basesubstituido,true,'text',1,"lang='r08_codigo'");
                db_input('descricao_substituido',20,$Ir08_descr,true,'text',3,"lang='r08_descr'");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>

    </fieldset>

  <input name="alterar" type="button" onClick="enviarDados()" id="db_opcao" value="Alterar">
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>

  (function(){

    var oParametros = { 
                        'sArquivo': 'func_bases.php', 
                        'sLabel': 'Pesquisa Bases'
                      }

    var oAncoraSubstituto = new DBLookUp($('substituto'), $('rh158_basesubstituto'), $('descricao_substituto'), oParametros);
    var oAncoraSubstituido = new DBLookUp($('substituido'), $('rh158_basesubstituido'), $('descricao_substituido'), oParametros);
  })()


  function enviarDados() {

    var reValidacaoBase = /^B([0-9]{3})$/;

    if ($F('rh158_basesubstituto') != '' || $F('rh158_basesubstituido') != '') {


      if ($F('rh158_basesubstituto') == '') {       
        alert('Campo Substituto deve ser preenchido.');
        $('rh158_basesubstituto').focus();
        return false;
      } 

      if ( !reValidacaoBase.test($F('rh158_basesubstituto').trim()) ) {

        alert("Código de Base para substituto inválido.");
        $('rh158_basesubstituto').value = '';
        $('rh158_basesubstituto').focus();
        return false;
      } 

      if ($F('rh158_basesubstituido') == '') {

        alert('Campo Substituído deve ser preenchido.');
        $('rh158_basesubstituido').focus();
        return false;
      }

      if ( !reValidacaoBase.test($F('rh158_basesubstituido').trim()) ) {

        alert("Código de Base para substituido inválido. ");
        $('rh158_basesubstituido').value = '';
        $('rh158_basesubstituido').focus();
        return false;
      } 
    }

    $('form1').submit();
  }

  function js_pesquisa() {
    js_OpenJanelaIframe('top.corpo','db_iframe_rhcadregime','func_rhcadregime.php?funcao_js=parent.js_preenchepesquisa|rh52_regime','Pesquisa',true);
  }

  function js_preenchepesquisa(chave) {

    db_iframe_rhcadregime.hide();
    <?
      if($db_opcao!=1){
        echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
      }
    ?>
  }
</script>
