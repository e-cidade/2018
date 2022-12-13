<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

  //MODULO: escola
  require_once("dbforms/db_classesgenericas.php");

  $cliframe_alterar_excluir   = new cl_iframe_alterar_excluir;
  $oDaoRegraArredondamentoFaixa->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("ed316_descricao");
  if (isset($db_opcaoal)) {

    $db_opcao = 33;
    $db_botao = false;
  }
  if (isset($opcao) && $opcao == "alterar") {

    $db_botao = true;
    $db_opcao = 2;
  }else if (isset($opcao) && $opcao == "excluir") {

    $db_opcao = 3;
    $db_botao = true;
  } else {

    $db_opcao = 1;
    $db_botao = true;
  }
  if ((isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir)) && $sqlerro == false )) {

    $ed317_inicial    = "";
    $ed317_final      = "";
    $ed317_arredondar = "";
    $ed317_sequencial = "";
  }

  $sDisabled = isset($sDisabled) ? $sDisabled : '';
?>
<form name="form1" method="post" action="">
  <div style="display: table">
    <fieldset>
      <legend><b>Regras</b></legend>
      <center>
        <table border="0">
          <tr style="display: none">
            <td nowrap title="<?=@$Ted317_regraarredondamento?>">
              <?
                db_ancora(@$Led317_regraarredondamento, "js_pesquisaed317_regraarredondamento(true);", $db_opcao);
              ?>
            </td>
            <td>
              <?
                db_input('ed317_sequencial', 10, $Ied317_sequencial, true, 'hidden', 3, "");
                db_input('ed317_regraarredondamento', 10, $Ied317_regraarredondamento, true, 'text', $db_opcao, " onchange='js_pesquisaed317_regraarredondamento(false);'");
                db_input('ed316_descricao', 40, $Ied316_descricao, true, 'text', 3, $sDisabled);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted317_inicial?>">
              <?=@$Led317_inicial?>
            </td>
            <td>
              <?
                db_input('ed317_inicial', 10, $Ied317_inicial, true, 'text', $db_opcao, $sDisabled);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted317_final?>">
              <?=@$Led317_final?>
            </td>
            <td>
              <?
                db_input('ed317_final', 10, $Ied317_final, true, 'text', $db_opcao, $sDisabled);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Ted317_arredondar?>">
               <?=@$Led317_arredondar?>
            </td>
            <td>
              <?
                $x = array(1=>"BAIXO", 2=>"MEIO", 3=>"CIMA");
                db_select('ed317_arredondar', $x, true, $db_opcao, $sDisabled);
              ?>
            </td>
          </tr>
        </table>
        </center>
        </fieldset>
          <center>
          <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit"
                 id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
                 <?=($db_botao==false || $sDisabled != ''?"disabled":"")?>  >
          <input name="novo" type="button" id="cancelar" value="Novo"
                 onclick="js_cancelar();" <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
           </center>
        <table>
          <tr>
            <td valign="top"  align="center">
              <?
            	  $chavepri = array("ed317_sequencial" => @$ed317_sequencial);
            	  $cliframe_alterar_excluir->chavepri = $chavepri;
            	  $sWhere                             = " ed317_regraarredondamento = {$ed317_regraarredondamento}";
            	  $sCampos                            = " *, case when ed317_arredondar=cast(1 as varchar) then 'Baixo'";
            	  $sCampos                           .= "         when ed317_arredondar=cast(2 as varchar) then 'Meio'";
            	  $sCampos                           .= "         when ed317_arredondar=cast(3 as varchar) then 'Cima' end as ed317_arredondar ";
            	  $cliframe_alterar_excluir->sql      = $oDaoRegraArredondamentoFaixa->sql_query_file(null,
            	                                                                                      "{$sCampos}",
            	                                                                                      "ed317_inicial,
            	                                                                                       ed317_final",
            	                                                                                      $sWhere
            	                                                                                     );
								//die($cliframe_alterar_excluir->sql);
								if ($sDisabled != '') {

  								$cliframe_alterar_excluir->sql_disabled = $oDaoRegraArredondamentoFaixa->sql_query_file(null,
                                                                                                          "{$sCampos}",
                                                                                                          "ed317_inicial,
                                                                                                          ed317_final",
                                                                                                          $sWhere
                                                                                                         );
                }
            	  $cliframe_alterar_excluir->campos        = "ed317_sequencial,ed317_regraarredondamento,ed317_inicial,ed317_final,ed317_arredondar";
            	  $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
            	  $cliframe_alterar_excluir->iframe_height = "160";
            	  $cliframe_alterar_excluir->iframe_width  = "700";
            	  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
              ?>
            </td>
          </tr>
        </table>
      </center>
    </fieldset>
  </div>
</form>
<script>
  var oUrl     = js_urlToObject();

  function js_cancelar(){

    var opcao = document.createElement("input");
    opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","novo");
    opcao.setAttribute("value","true");
    document.form1.appendChild(opcao);
    document.form1.submit();
  }
  function js_pesquisaed317_regraarredondamento(mostra){

    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_regraarredondamentofaixa',
                          'db_iframe_regraarredondamento',
                          'func_regraarredondamento.php?funcao_js=parent.js_mostraregraarredondamento1|ed316_sequencial|ed316_descricao',
                          'Pesquisa',
                          true
                         );
    } else {
      if(document.form1.ed317_regraarredondamento.value != ''){
        js_OpenJanelaIframe('top.corpo.iframe_regraarredondamentofaixa',
                            'db_iframe_regraarredondamento',
                            'func_regraarredondamento.php?pesquisa_chave='+document.form1.ed317_regraarredondamento.value+'&funcao_js=parent.js_mostraregraarredondamento',
                            'Pesquisa',
                            false
                           );
      } else {
        document.form1.ed316_descricao.value = '';
      }
    }
  }
  function js_mostraregraarredondamento(chave,erro){

    document.form1.ed316_descricao.value = chave;
    if (erro == true) {

      document.form1.ed317_regraarredondamento.focus();
      document.form1.ed317_regraarredondamento.value = '';
    }
  }
  function js_mostraregraarredondamento1(chave1,chave2){

    document.form1.ed317_regraarredondamento.value = chave1;
    document.form1.ed316_descricao.value = chave2;
    db_iframe_regraarredondamento.hide();
  }

  document.form1.ed317_inicial.maxLength = oUrl.iCasasDecimais;
  document.form1.ed317_final.maxLength   = oUrl.iCasasDecimais;
</script>