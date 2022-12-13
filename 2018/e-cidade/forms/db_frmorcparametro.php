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

//MODULO: orcamento
$clorcparametro->rotulo->label();
?>
<form name="form1" method="post" action="">
<fieldset style="width: 500px;">  
  <legend>
    <b>Parâmetros - Orçamento</b>
  </legend>
  <table>
    <tr>
      <td>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$To50_anousu?>"> <?=@$Lo50_anousu?> </td>
            <td>
              <? 
                 $o50_anousu = db_getsession('DB_anousu'); 
                 db_input('o50_anousu',6,$Io50_anousu,true,'text',$db_opcao,"") ;
               ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To50_coddot?>"> <?=@$Lo50_coddot?> </td>
            <td><? db_input('o50_coddot',6,$Io50_coddot,true,'text',$db_opcao,"") ?> </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To50_subelem?>"><?=@$Lo50_subelem?> </td>
            <td>
             <?
               $x = array("f"=>"NAO","t"=>"SIM"); 
               db_select('o50_subelem',$x,true,$db_opcao,"style='width:65px'"); 
             ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To50_programa?>"><?=@$Lo50_programa?></td>
            <td><? db_input('o50_programa',6,$Io50_programa,true,'text',$db_opcao,"") ?></td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To50_estrutdespesa?>"><?=@$Lo50_estrutdespesa?></td>
            <td><? db_input('o50_estrutdespesa',50,$Io50_estrutdespesa,true,'text',$db_opcao,"") ?></td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To50_estrutreceita?>"><?=@$Lo50_estrutreceita?></td>
            <td><? db_input('o50_estrutreceita',50,$Io50_estrutreceita,true,'text',$db_opcao,"") ?></td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To50_estrutelemento?>"><?=@$Lo50_estrutelemento?></td>
            <td><? db_input('o50_estrutelemento',50,$Io50_estrutelemento,true,'text',$db_opcao,"") ?></td>
          </tr>
        
          <tr>
            <td nowrap title="<?=@$To50_tipoproj?>"><?=@$Lo50_tipoproj?> </td>
            <td>
              <? 
                $x = array("1"=>"1-Com Timbre","2"=>"2-Dotação Sintética","3"=>"3-Com Dot","4"=>"4-Sem Timbre"); 
                db_select('o50_tipoproj',$x,true,$db_opcao,"style='width:200px'"); 
              ?>
            </td>
          </tr>
         <tr>
            <td nowrap title="<?=@$To50_utilizapacto?>"><?=@$Lo50_utilizapacto?> </td>
            <td><? $x = array("t"=>"Sim","f"=>"Não");
             db_select('o50_utilizapacto',$x,true,$db_opcao,"style='width:200px'"); ?></td>
          </tr>
          <tr>
            <td nowrap title="<?=@$To50_liberadecimalppa?>">
              <?=@$Lo50_liberadecimalppa?> </td>
            <td><? $x = array("t"=>"Sim","f"=>"Não");
             db_select('o50_liberadecimalppa',$x,true,$db_opcao,"style='width:200px'"); ?></td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <br>  
  <fieldset class="fieldBorder">
    <legend><strong>C. Peculiar / C. Aplicação</strong></legend>
    <table width="100%">
      <tr>
        <td title="<?=$To50_estruturacp;?>" width="120px">
          <b><? db_ancora("Código Estrutura:", 'js_pesquisaEstruturaCP(true)', $db_opcao); ?></b>
        </td>
        <td>
          <?
            db_input("o50_estruturacp", 10, $Io50_estruturacp, true, "text", $db_opcao, "onchange='js_pesquisaEstruturaCP(false);'");
            db_input("db77_descr_1", 40, "", true, "text", 3);
          ?>
        </td>
      </tr>
    </table>     
  </fieldset>
  <br>
  <fieldset class="fieldBorder">
    <legend><strong>Recurso</strong></legend>
    <table width="100%">
      <tr>
        <td title="<?=$To50_estruturarecurso;?>" width="120px">
          <b><? db_ancora("Código Estrutura:", 'js_pesquisaEstruturaRecurso(true)', $db_opcao); ?></b>
        </td>
        <td>
          <?
            db_input("o50_estruturarecurso", 10, $Io50_estruturarecurso, true, "text", $db_opcao, "onchange='js_pesquisaEstruturaRecurso(false);'");
            db_input("db77_descr_2", 40, "", true, "text", 3);
          ?>
        </td>
      </tr>
    </table>     
  </fieldset>
</fieldset>
<p>
  <input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</p>
</form>
</center>


<script>
  
  /**
   *  Declara as variáveis e recebem o elemento HTML com o respectivo id.
   */
  var sEstruturaCP      = $('o50_estruturacp');
  var sDescrCP          = $('db77_descr_1');
  var sEstruturaRecurso = $('o50_estruturarecurso');
  var sDescrRecurso     = $('db77_descr_2');

  /**
   * Observa as alterações no campo sEstruturaCP e verifica se não é o mesmo no campo sEstruturaRecurso
   */
  sEstruturaCP.observe('change', function() {
    
    if ( sEstruturaCP.value == sEstruturaRecurso.value && sEstruturaCP.value != "" ) {

      alert("As estruturas não podem ser iguais.");
      sEstruturaCP.value = "";
      sDescrCP.value     = "";
    } else if ( sEstruturaCP.value == "" ) {
      sDescrCP.value     = "";
    }
  });
  
  /**
   * Observa as alterações no campo sEstruturaRecurso e verifica se não é o mesmo no campo sEstruturaCP
   */
  sEstruturaRecurso.observe('change', function() {
    
    if ( sEstruturaRecurso.value == sEstruturaCP.value && sEstruturaRecurso.value != "" ) {

      alert("As estruturas não podem ser iguais.");
      sEstruturaRecurso.value = "";
      sDescrRecurso.value     = "";
    } else if ( sEstruturaRecurso.value == "" ) {
      sDescrRecurso.value     = "";
    }
  });
  
  /**
   *  Função de Pesquisa Ancora o50_db_estruturacp
   */
  function js_pesquisaEstruturaCP( lMostra ) {
  
    if ( lMostra ) {
      js_OpenJanelaIframe('top.corpo','db_iframe_db_estrutura','func_db_estrutura.php?funcao_js=parent.js_preencheEstruturaCP|db77_codestrut|db77_descr','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('top.corpo','db_iframe_db_estrutura','func_db_estrutura.php?pesquisa_chave='+sEstruturaCP.value+'&funcao_js=parent.js_preencheEstruturaCP1','Pesquisa',false);
    }
  }
  
  function js_preencheEstruturaCP( sEstrutura, sDescEstrutura ) {
  
    sEstruturaCP.value = sEstrutura;
    sDescrCP.value     = sDescEstrutura;
    db_iframe_db_estrutura.hide();
  }
  function js_preencheEstruturaCP1( sDescricao, lErro ) {

    if ( !lErro ) {
      sDescrCP.value = sDescricao;
    } else {
      sEstruturaCP.value = "";
      sDescrCP.value     = sDescricao;
    }    
  }
  
  /**
   * Funções de Pesquisa Ancora o50_estruturarecurso
   */
  function js_pesquisaEstruturaRecurso( lMostra ) {
  
    if ( lMostra ) {
      js_OpenJanelaIframe('top.corpo','db_iframe_db_estruturaRec','func_db_estrutura.php?funcao_js=parent.js_preencheEstruturaRecurso|db77_codestrut|db77_descr','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('top.corpo','db_iframe_db_estruturaRec','func_db_estrutura.php?pesquisa_chave='+sEstruturaRecurso.value+'&funcao_js=parent.js_preencheEstruturaRecurso1','Pesquisa',false);
    }
  }
  
  function js_preencheEstruturaRecurso( sEstrutura, sDescEstrutura ) {
  
    sEstruturaRecurso.value = sEstrutura;
    sDescrRecurso.value     = sDescEstrutura;
    db_iframe_db_estruturaRec.hide();
  }
  function js_preencheEstruturaRecurso1( sDescricao, lErro ) {

    if ( !lErro ) {
      sDescrRecurso.value = sDescricao;
    } else {
      sEstruturaRecurso.value = "";
      sDescrRecurso.value     = sDescricao;
    }    
  }
  
  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcparametro','func_orcparametro.php?funcao_js=parent.js_preenchepesquisa|o50_anousu','Pesquisa',true);
  }
  function js_preenchepesquisa(chave){
    db_iframe_orcparametro.hide();
    <?
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
    ?>
  }
  
  /**
   * Executa as funções de busca de código estrutural depois do programa preencher com os
   * valores de descrição correspondentes os campos
   */
  js_pesquisaEstruturaCP(false);
  js_pesquisaEstruturaRecurso(false);
</script>