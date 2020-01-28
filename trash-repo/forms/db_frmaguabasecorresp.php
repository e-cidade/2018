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
  include("dbforms/db_classesgenericas.php");
  
  $cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
  $claguabasecorresp->rotulo->label();
  $clrotulo = new rotulocampo;
  $clrotulo->label("x02_codcorresp");
  $clrotulo->label("x01_numcgm");
  
  if (isset($db_opcaoal)) {
    $db_opcao = 33;
    $db_botao = false;
  
  } else if(isset($opcao) && $opcao == "alterar") {
    $db_botao = true;
    $db_opcao = 2;
  
  } else if(isset($opcao) && $opcao == "excluir") {
    $db_opcao = 3;
    $db_botao = true;
  
  } else {  
    $db_opcao = 1;
    $db_botao = true;
    
    if (isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro == false)){
      $x32_codcorresp = "";
    }
  } 
?>

<fieldset style="margin-top: 20px;">
  <legend><b>Cadastro de Imóveis/Terrenos - Entrega</b></legend>
  <form name="form1" method="post" action="">
    <center>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tx32_codcorresp?>">
            <?
              db_ancora(@$Lx32_codcorresp, "js_pesquisax32_codcorresp(true);", $db_opcao);
            ?>
          </td>
          <td> 
            <?
              db_input('x32_codcorresp', 10, $Ix32_codcorresp, true, 'text',
                       $db_opcao, " onchange='js_pesquisax32_codcorresp(false);'");
                       
              db_input('x02_codcorresp', 10, $Ix02_codcorresp, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tx32_matric?>">
            <?=@$Lx32_matric
              //db_ancora(@$Lx32_matric,"js_pesquisax32_matric(true);",$db_opcao);
            ?>
          </td>
          <td> 
            <?
              //db_input('x32_matric', 10, $Ix32_matric, true, 'text',
              //         $db_opcao, " onchange='js_pesquisax32_matric(false);'");
              
              db_input('x32_matric', 10, $Ix32_matric, true, 'text', 3, " onchange='js_pesquisax32_matric(false);'");
              
              //db_input('x01_numcgm', 40, $Ix01_numcgm, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="center">
            <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
                   type="submit" id="db_opcao"
                   value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
                     <?=($db_botao == false ? "disabled" : "")?>
            />
            
            <input name="novo" type="button" id="cancelar" value="Novo"
                   onclick="js_cancelar();" <?=($db_opcao == 1 || isset($db_opcaoal) ? "style='visibility:hidden;'" : "")?>
            />
          </td>
        </tr>
      </table>
      <table>
        <tr>
          <td valign="top" align="center">  
            <?
	            $chavepri= array("x32_matric"=>@$x32_matric);
	            
	            $cliframe_alterar_excluir->chavepri      = $chavepri;
	            //$cliframe_alterar_excluir->sql         = $claguabasecorresp->sql_query_file($x32_matric);
	            $cliframe_alterar_excluir->sql           = $claguabasecorresp->sql_query($x32_matric);
	            $cliframe_alterar_excluir->campos        = "x02_codrua,j14_nome,j13_descr,x02_numero,x02_complemento,x02_rota,x02_orientacao";
	            $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
	            $cliframe_alterar_excluir->iframe_height = "160";
	            $cliframe_alterar_excluir->iframe_width  = "700";
	            
	            $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
            ?>
          </td>
        </tr>
      </table>
    </center>
  </form>
</fieldset>

<script>
  function js_cancelar() {

	  var opcao = document.createElement("input");

	  opcao.setAttribute("type","hidden");
    opcao.setAttribute("name","novo");
    opcao.setAttribute("value","true");
    document.form1.appendChild(opcao);
    document.form1.submit();
  }

  
  function js_pesquisax32_codcorresp(mostra) {

	  if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabasecorresp', 'db_iframe_aguacorresp',
    	                    'func_aguacorresp.php?funcao_js=parent.js_mostraaguacorresp1|x02_codcorresp|x02_codcorresp',
    	                    'Pesquisa', true, '0', '1', '775', '390');
    } else {

      if (document.form1.x32_codcorresp.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguabasecorresp', 'db_iframe_aguacorresp',
                            'func_aguacorresp.php?pesquisa_chave=' + document.form1.x32_codcorresp.value + 
                              '&funcao_js=parent.js_mostraaguacorresp',
                            'Pesquisa', false);
      } else {
        document.form1.x02_codcorresp.value = ''; 
      }
    }
  }

  
  function js_mostraaguacorresp(chave, erro) {

	  document.form1.x02_codcorresp.value = chave; 

	  if (erro == true) { 
      document.form1.x32_codcorresp.focus(); 
      document.form1.x32_codcorresp.value = ''; 
    }
  }

  
  function js_mostraaguacorresp1(chave1, chave2) {
    document.form1.x32_codcorresp.value = chave1;
    document.form1.x02_codcorresp.value = chave2;
    db_iframe_aguacorresp.hide();
  }

  
  function js_pesquisax32_matric(mostra) {
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabasecorresp', 'db_iframe_aguabase',
    	                    'func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm',
    	                    'Pesquisa', true, '0', '1', '775', '390');
    } else {
      if (document.form1.x32_matric.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguabasecorresp', 'db_iframe_aguabase',
                            'func_aguabase.php?pesquisa_chave=' + document.form1.x32_matric.value +
                              '&funcao_js=parent.js_mostraaguabase',
                            'Pesquisa', false);
       } else {
         document.form1.x01_numcgm.value = '';
       }
    }
  }

  
  function js_mostraaguabase(chave, erro) {

	  document.form1.x01_numcgm.value = chave; 

	  if (erro == true) { 
      document.form1.x32_matric.focus(); 
      document.form1.x32_matric.value = ''; 
    }
  }

  
  function js_mostraaguabase1(chave1, chave2) {
    document.form1.x32_matric.value = chave1;
    document.form1.x01_numcgm.value = chave2;
    db_iframe_aguabase.hide();
  }
  
</script>