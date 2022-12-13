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
include ("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
?>
<form name="form1" method="post" action="pes1_pontoRfx0011.php">
<center>
<BR><BR>
<table border="0" width="90%">
  <tr>
    <td align="center">
      <?
      if($ponto == "fx"){
      	$dponto = " Ponto Fixo";
      }else if($ponto == "fs"){
      	$dponto = " Ponto de Salário";
      }else if($ponto == "fa"){
      	$dponto = " Ponto de Adiantamento";
      }else if($ponto == "com"){
      	$dponto = " Ponto Complementar por Código";
      }else if($ponto == "f13"){
      	$dponto = " Ponto de Décimo Terceiro";
      }else if($ponto == "fe"){
      	$dponto = " Ponto de Férias";
      }else if($ponto == "fr"){
      	$dponto = " Ponto de Rescisão";
      }
      db_input('ponto', 10, 0, true, 'hidden', 3, '');
      db_input('rubricas_selecionadas_enviar', 10, 0, true, 'hidden', 3, '');
      $aux = new cl_arquivo_auxiliar;
      $aux->nomejanela = "Pesquisa Rubrica";
      $aux->cabecalho = "<strong>Seleção de Rubricas - $dponto</strong>";
      $aux->codigo = "rh27_rubric";
      $aux->descr  = "rh27_descr";
      $aux->nomeobjeto = 'rubricas_selecionadas';
      $aux->funcao_js = 'js_mostra';
      $aux->funcao_js_hide = 'js_mostra1';
      $aux->func_arquivo = "func_rhrubricas.php";
      $aux->nomeiframe = "db_iframe_rhrubricas";
      $aux->executa_script_apos_incluir = "document.form1.rh27_rubric.focus();";
      $aux->mostrar_botao_lancar = false;
      $aux->executa_script_lost_focus_campo = "js_insSelectrubricas_selecionadas()";
      $aux->completar_com_zeros_codigo = true;
      $aux->executa_script_change_focus = "document.form1.rh27_rubric.focus();";
      $aux->passar_query_string_para_func = "&instit=".db_getsession("DB_instit");
      $aux->concatenar_codigo = true;
      $aux->tabIndex = '1';
      $aux->localjan = "";
      $aux->db_opcao = 2;
      $aux->tipo = 2;
      $aux->top = 20;
      $aux->linhas = 20;
      $aux->vwidth = "360";
      $aux->funcao_gera_formulario();
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <BR>
      <input name="enviardados" type="submit" id="db_opcao" value="Enviar dados"  onclick="return js_vercampos();" tabIndex='2' onBlur="document.form1.rh27_rubric.focus();">
      <BR><BR>
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_vercampos(){
  // Verificar se alguma rubrica foi selecionada
  document.form1.rubricas_selecionadas_enviar.value = js_campo_recebe_valores();
  if(document.form1.rubricas_selecionadas_enviar.value == "" || document.form1.rubricas_selecionadas_enviar.value == "false"){  
    return false;
  }
  return true;
}
/*
function js_mudar_campo_formulario(obj,campo_foco,opcao){
  var evt = window.event;
  alert(evt.keyCode);
}
*/
</script>