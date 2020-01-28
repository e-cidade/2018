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
  $claguabaseresp->rotulo->label();
  $clrotulo = new rotulocampo;
  
  $clrotulo->label("x01_numcgm");
  $clrotulo->label("z01_nome");

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
    
    if (isset($novo) || isset($alterar) ||   isset($excluir) || (isset($incluir) && $sqlerro == false )) {
      $x14_numcgm = "";
    }
  } 
?>
<fieldset style="margin-top: 20px;">
  <legend><b>Cadastro de Imóveis/Terrenos - Responsável</b></legend>
  <form name="form1" method="post" action="">
    <center>
      <table border="0">
        <tr>
          <td nowrap title="<?=@$Tx14_matric?>">
            <?=@$Lx14_matric
            //db_ancora(@$Lx14_matric,"js_pesquisax14_matric(true);",$db_opcao);
            ?>
          </td>
          <td> 
            <?
              //db_input('x14_matric',10,$Ix14_matric,true,'text',$db_opcao," onchange='js_pesquisax14_matric(false);'");
              db_input('x14_matric', 10, $Ix14_matric, true, 'text', 3, " onchange='js_pesquisax14_matric(false);'");
              //db_input('x01_numcgm',10,$Ix01_numcgm,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tx14_numcgm?>">
            <?
              db_ancora(@$Lx14_numcgm, "js_pesquisax14_numcgm(true);", $db_opcao);
            ?>
          </td>
          <td> 
            <?
              db_input('x14_numcgm', 10, $Ix14_numcgm, true, 'text', $db_opcao, " onchange='js_pesquisax14_numcgm(false);'");
              db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
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
          <td valign="top"  align="center">  
            <?
 						  $chavepri= array("x14_matric"=>@$x14_matric);
	 					  $cliframe_alterar_excluir->chavepri      = $chavepri;
		 				  $cliframe_alterar_excluir->sql           = $claguabaseresp->sql_query($x14_matric);
						  $cliframe_alterar_excluir->campos        = "x14_matric,x14_numcgm,z01_nome";
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

  
  function js_pesquisax14_matric(mostra) {

	  if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabaseresp', 'db_iframe_aguabase',
    	                    'func_aguabase.php?funcao_js=parent.js_mostraaguabase1|x01_matric|x01_numcgm',
    	                    'Pesquisa', true, '0', '1', '775', '390');
    } else {

      if (document.form1.x14_matric.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguabaseresp', 'db_iframe_aguabase', 
                            'func_aguabase.php?pesquisa_chave=' + document.form1.x14_matric.value + 
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
      document.form1.x14_matric.focus(); 
      document.form1.x14_matric.value = ''; 
    }
  }

  
  function js_mostraaguabase1(chave1, chave2) {
    document.form1.x14_matric.value = chave1;
    document.form1.x01_numcgm.value = chave2;
    db_iframe_aguabase.hide();
  }


  function js_pesquisax14_numcgm(mostra) {

	  if (mostra == true) {
      js_OpenJanelaIframe('top.corpo.iframe_aguabaseresp', 'db_iframe_cgm', 
    	                    'func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome',
    	                    'Pesquisa', true, '0', '1', '775', '390');
    } else {

      if (document.form1.x14_numcgm.value != '') { 
        js_OpenJanelaIframe('top.corpo.iframe_aguabaseresp', 'db_iframe_cgm', 
                            'func_nome.php?pesquisa_chave=' + document.form1.x14_numcgm.value +
                              '&funcao_js=parent.js_mostracgm',
                            'Pesquisa', false);
      } else {
        document.form1.z01_nome.value = ''; 
      }
    }
  }

  
  function js_mostracgm(erro, chave) {
    document.form1.z01_nome.value = chave; 

    if (erro == true) { 
      document.form1.x14_numcgm.focus(); 
      document.form1.x14_numcgm.value = ''; 
    }
  }

  
  function js_mostracgm1(chave1, chave2) {
    document.form1.x14_numcgm.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe_cgm.hide();
  }
  
</script>