<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: educa��o
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrechumanoescola->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed18_i_codigo");
$clrotulo->label("ed20_i_codigo");
$db_botao1 = false;
$cor = "#FFFFFF";
if (isset($opcao) && $opcao == "alterar") {
	
 $db_opcao  = 2;
 $sSql      = $clrechumanoescola->sql_query("","rechumanoescola.*",""," ed75_i_codigo = $ed75_i_codigo");
 $result2   = $clrechumanoescola->sql_record($sSql);
 $db_botao1 = true;
 db_fieldsmemory($result2,0);
} elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3) {
	
 $db_botao1 = true;
 $db_opcao = 3;
 $cor = "#DEB887";
 $anoatual = date("Y");
 $result_hora = $clregenciahorario->sql_record($clregenciahorario->sql_query("","DISTINCT ed57_c_descr,ed11_c_descr,ed10_c_descr,ed15_c_nome,ed15_i_sequencia","ed15_i_sequencia,ed57_c_descr"," ed58_i_rechumano = $ed75_i_rechumano AND ed52_i_ano = $anoatual AND ed57_i_escola = $ed75_i_escola and ed58_ativo is true  "));
 if($clregenciahorario->numrows>0){
  $mensagem = "ATEN��O!\\n\\n Esta matr�cula($ed75_i_rechumano) tem hor�rio(s) marcado(s) na(s) turma(s) abaixo relacionada(s) nesta escola neste ano de $anoatual (Veja Aba Hor�rios):\\n\\n";
  for($r=0;$r<$clregenciahorario->numrows;$r++){
   db_fieldsmemory($result_hora,$r);
   $mensagem .= " -> Turma $ed57_c_descr, S�rie $ed11_c_descr - $ed10_c_descr, Turno $ed15_c_nome\\n";
  }
  $mensagem .= "\\n\\nAntes de confirmar a exclus�o, certifique-se que esta matr�cula n�o ser� mais vinculada a nenhuma turma neste ano de $anoatual. Se estes hor�rios marcados n�o forem exclu�dos das turmas, esta matr�cula n�o poder� ser vinculada a outra turma em outra escola nos hor�rios referentes no ano de $anoatual.";
  db_msgbox($mensagem);
 }
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  
  $db_opcao = 1;
 }
}
$ed75_i_escola = db_getsession("DB_coddepto");
$result = $clescola->sql_record($clescola->sql_query($ed75_i_escola));
db_fieldsmemory($result,0);
?>
<form name="form1" method="post" action="">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<center>
<table border="0">
 <tr>
  <td nowrap>
  </td>
  <td>
   <?db_input('ed75_i_codigo',15,@$Ied75_i_codigo,true,'hidden',3,"")?>
   <?db_input('ed75_i_rechumano',15,@$Ied75_i_rechumano,true,'hidden',3,"")?> 
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$ed20_i_tiposervidor=='1'?'Matr�cula':'CGM'?>">
   <b><?=@$ed20_i_tiposervidor=='1'?'Matr�cula:':'CGM:'?></b>
  </td>
  <td>
   <?db_input('identificacao',10,@$identificacao,true,'text',3,"")?>
   <?db_input('z01_nome',50,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted75_i_escola?>">
   <?db_ancora(@$Led75_i_escola,"js_pesquisaed75_i_escola(true);",3);?>
  </td>
  <td>
   <?db_input('ed75_i_escola',15,$Ied75_i_escola,true,'text',3,"","",$cor)?>
   <?db_input('ed18_c_nome',50,@$Ied18_c_nome,true,'text',3,"","",$cor)?>
  </td>
 </tr>
  <tr>
    <td nowrap title="<?=@$Ted75_d_ingresso?>">
      <?=@$Led75_d_ingresso?>
    </td>
    <td nowrap>
      <?db_inputdata('ed75_d_ingresso',@$ed75_d_ingresso_dia,@$ed75_d_ingresso_mes,@$ed75_d_ingresso_ano,true,'text', $db_opcao,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ted75_i_saidaescola?>">
      <?=@$Led75_i_saidaescola?>
    </td>
    <td nowrap>
      <?db_inputdata('ed75_i_saidaescola',@$ed75_i_saidaescola_dia,@$ed75_i_saidaescola_mes,@$ed75_i_saidaescola_ano,true,'text', $db_opcao," onchange='js_validaData();'")?>
    </td>
  </tr>
 </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" id="db_opcao" 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> 
       <?=($db_opcao==3?" onclick=\"return confirm('Todos os v�nculos do recurso humano (Rela��o de Trabalho, Atividades, Disponibilidade) ligados � escola $ed18_c_nome ser�o apagados! Confirma?')\"":"onclick='return js_validaData();'")?> >
<input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $chavepri= array("ed75_i_codigo"=>@$ed75_i_codigo,"ed75_i_rechumano"=>@$ed75_i_rechumano,"z01_nome"=>@$z01_nome,"ed75_i_escola"=>@$ed75_i_escola,"ed18_c_nome"=>@$ed18_c_nome);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   @$cliframe_alterar_excluir->sql = $clrechumanoescola->sql_query("","*","ed18_c_nome, ed75_d_ingresso"," ed75_i_rechumano = $ed75_i_rechumano");
   @$cliframe_alterar_excluir->sql_disabled = $clrechumanoescola->sql_query("","*","ed18_c_nome"," ed75_i_rechumano = $ed75_i_rechumano AND ed75_i_escola != $ed75_i_escola");
   $cliframe_alterar_excluir->campos  ="ed18_c_nome,ed75_d_ingresso,ed75_i_saidaescola ";
   $cliframe_alterar_excluir->labels  ="ed75_i_escola,ed75_d_ingresso, ed75_i_saidaescola";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="N�o foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   //$cliframe_alterar_excluir->opcoes = 3;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
</center>
<script type="text/javascript">


function js_validaData() {

	if (js_comparadata($F('ed75_i_saidaescola'), $F('ed75_d_ingresso'), '<')) {

		alert('Data de sa�da menor que a data de ingresso.');
		$('ed75_i_saidaescola').value = '';
		return false;
	}
  return true;
}
</script>