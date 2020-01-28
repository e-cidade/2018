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

//MODULO: educação
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clformacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed20_i_codigo");
$clrotulo->label("ed29_i_codigo");
$clrotulo->label("ed21_i_codigo");
$clrotulo->label("ed20_c_posgraduacao");
$clrotulo->label("ed20_c_outroscursos");
$db_botao1 = false;
if(isset($opcao) && $opcao=="alterar"){
 $db_opcao = 2;
 $db_botao1 = true;
 if(trim($ed27_c_situacao)=="CONCLUÍDO"){
  $ed27_c_situacao = "CON";
 }elseif(trim($ed27_c_situacao)=="INTERROMPIDO"){
  $ed27_c_situacao = "INT";
 }else{
  $ed27_c_situacao = "CUR";
 }
 
 if(trim($ed27_i_licenciatura)=="SIM"){
  $ed27_i_licenciatura = "1";
 }else{
  $ed27_i_licenciatura = "0";
 } 
 
}elseif(isset($opcao) && $opcao=="excluir" || isset($db_opcao) && $db_opcao==3){
 $db_botao1 = true;
 $db_opcao = 3;
 if(trim($ed27_c_situacao)=="CONCLUÍDO"){
  $ed27_c_situacao = "CON";
 }elseif(trim($ed27_c_situacao)=="INTERROMPIDO"){
  $ed27_c_situacao = "INT";
 }else{
  $ed27_c_situacao = "CUR";
 }
 
}else{
 if(isset($alterar)){
  $db_opcao = 2;
  $db_botao1 = true;
 }else{
  $db_opcao = 1;
 }
}
?>
<form name="form1" method="post" action="">
<table border="0">
 <tr>
  <td valign="top">
   <fieldset style="height:100%"><legend><b>Cursos Superiores</b></legend>
   <table border="0">
    <tr>
     <td nowrap>
     </td>
     <td>
      <?db_input('ed27_i_codigo',15,@$Ied27_i_codigo,true,'hidden',3,"")?>
      <?db_input('ed27_i_rechumano',15,@$Ied27_i_rechumano,true,'hidden',3,"")?> 
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$ed20_i_tiposervidor=='1'?'Matrícula':'CGM'?>">
      <b><?=@$ed20_i_tiposervidor=='1'?'Matrícula:':'CGM:'?></b>
     </td>
     <td>
      <?db_input('identificacao',10,@$identificacao,true,'text',3,"")?>
      <?db_input('z01_nome',50,@$Iz01_nome,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted27_i_cursoformacao?>">
      <?db_ancora(@$Led27_i_cursoformacao,"js_pesquisaed27_i_cursoformacao(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('ed27_i_cursoformacao',10,$Ied27_i_cursoformacao,true,'hidden',3,"")?>
      <?db_input('ed94_c_codigocenso',10,@$Ied94_c_codigocenso,true,'text',3,"")?>
      <?db_input('ed94_c_descr',35,@$Ied94_c_descr,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted27_i_censoinstsuperior?>">
      <?db_ancora(@$Led27_i_censoinstsuperior,"js_pesquisaed27_i_censoinstsuperior(true);",$db_opcao);?>
     </td>
     <td>
      <?db_input('ed27_i_censoinstsuperior',10,$Ied27_i_censoinstsuperior,true,'text',$db_opcao," onchange='js_pesquisaed27_i_censoinstsuperior(false);'")?>
      <?db_input('ed257_c_nome',35,@$Ied257_c_nome,true,'text',3,'')?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted27_c_situacao?>">
      <?=@$Led27_c_situacao?>
     </td>
     <td>
      <?
      $x = array('CON'=>'CONCLUÍDO','CUR'=>'EM ANDAMENTO','INT'=>'INTERROMPIDO');
      db_select('ed27_c_situacao',$x,true,$db_opcao,"onchange = 'js_verificacao(this.value);'");
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted27_i_formacaopedag?>">
      <?=@$Led27_i_formacaopedag?>
     </td>
     <td>
      <?
      $x = array('0'=>'NÃO','1'=>'SIM');
      db_select('ed27_i_formacaopedag',$x,true,$db_opcao,"");
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Ted27_i_licenciatura?>">
      <?=@$Led27_i_licenciatura?>
     </td>
     <td>
      <?
      $xx = array("0"=>"NÃO","1"=>"SIM");
      db_select("ed27_i_licenciatura",$xx,true,$db_opcao,"");
      ?>
      <?=@$Led27_i_anoinicio?>
      <?db_input('ed27_i_anoinicio',4,$Ied27_i_anoinicio,true,'text',$db_opcao,"")?>
      <?=@$Led27_i_anoconclusao?>
      <?db_input('ed27_i_anoconclusao',4,$Ied27_i_anoconclusao,true,'text',$db_opcao,"")?>
     </td>
    </tr>
   </table>
   </fieldset>
  </td>
   </tr>
   <tr>
     <td colspan="2" align="center"><br>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=($ed20_i_escolaridade!=6?"disabled":"")?> onclick=" return js_validacao();">
      <input name='btnOutroDados' value="Outros Dados" type="button" id='btnOutrosDados'>
      <input name="cancelar" type="submit" value="Cancelar" <?=($db_botao1==false?"disabled":"")?> >
     </td>
    </tr>
  </table>
   </fieldset>
  </td>
 </tr>
<table width="100%">
 <tr>
  <td valign="top">
  <?
   $campos = "ed27_i_codigo,
              ed27_i_rechumano,
              ed27_i_cursoformacao,
              ed94_c_descr,
              ed94_c_codigocenso,
              ed27_i_censoinstsuperior,
						  ed27_i_anoinicio,
              ed27_i_anoconclusao,              
                    case
               when ed27_i_formacaopedag = '0'
                then 'NÃO'
               when ed27_i_formacaopedag = '1'
                then 'SIM'
              end as ed27_i_formacaopedag,              
              case
               when ed27_c_situacao = 'CON'
                then 'CONCLUÍDO'
               when ed27_c_situacao = 'CUR'
                then 'CURSANDO' else
                'INTERROMPIDO'
              end as ed27_c_situacao,
              case
               when ed27_i_licenciatura = 0
                then 'NÃO'
                else 'SIM'
              end as ed27_i_licenciatura,
              ed257_c_nome
             ";
   $chavepri= array("ed27_i_codigo"=>@$ed27_i_codigo,"ed27_i_rechumano"=>@$ed27_i_rechumano,"ed27_i_cursoformacao"=>@$ed27_i_cursoformacao,"ed94_c_descr"=>@$ed94_c_descr,"ed94_c_codigocenso"=>@$ed94_c_codigocenso,"ed27_i_censoinstsuperior"=>@$ed27_i_censoinstsuperior,"ed257_c_nome"=>@$ed257_c_nome,"ed27_c_situacao"=>@$ed27_c_situacao,"ed27_i_anoinicio"=>@ed27_i_anoinicio,"ed27_i_anoconclusao"=>@$ed27_i_anoconclusao,"ed27_i_licenciatura"=>@$ed27_i_licenciatura);
   $cliframe_alterar_excluir->chavepri=$chavepri;
   $cliframe_alterar_excluir->sql = $clformacao->sql_query("",$campos,""," ed27_i_rechumano = $ed27_i_rechumano");
   $cliframe_alterar_excluir->campos  ="ed27_i_codigo,ed94_c_codigocenso,ed94_c_descr,ed257_c_nome,ed27_i_licenciatura,ed27_i_anoinicio,ed27_i_anoconclusao,ed27_c_situacao,ed27_i_formacaopedag";
   $cliframe_alterar_excluir->legenda="Registros";
   $cliframe_alterar_excluir->msg_vazio ="Não foi encontrado nenhum registro.";
   $cliframe_alterar_excluir->textocabec ="#DEB887";
   $cliframe_alterar_excluir->textocorpo ="#444444";
   $cliframe_alterar_excluir->fundocabec ="#444444";
   $cliframe_alterar_excluir->fundocorpo ="#eaeaea";
   $cliframe_alterar_excluir->iframe_height ="200";
   $cliframe_alterar_excluir->iframe_width ="100%";
   $cliframe_alterar_excluir->tamfontecabec = 9;
   $cliframe_alterar_excluir->tamfontecorpo = 9;
   $cliframe_alterar_excluir->formulario = false;
   $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
  ?>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisaed27_i_cursoformacao(mostra){
 js_OpenJanelaIframe('','db_iframe_cursoformacao','func_cursoformacao.php?funcao_js=parent.js_mostracursoedu1|ed94_i_codigo|ed94_c_codigocenso|ed94_c_descr','Pesquisa de Cursos de Formação Superior',true);
}
function js_mostracursoedu1(chave1,chave2,chave3){
 document.form1.ed27_i_cursoformacao.value = chave1;
 document.form1.ed94_c_codigocenso.value = chave2;
 document.form1.ed94_c_descr.value = chave3;
 db_iframe_cursoformacao.hide();
}
function js_pesquisaed27_i_censoinstsuperior(mostra){
 if(document.form1.ed27_i_cursoformacao.value==""){
  alert("Informe o Curso!");
  document.form1.ed27_i_censoinstsuperior.value = "";
  document.form1.ed257_c_nome.value = "";
 }else{
  if(mostra==true){
   js_OpenJanelaIframe('','db_iframe_censoinstsuperior','func_censoinstsuperior.php?cursoformacao='+document.form1.ed27_i_cursoformacao.value+'&funcao_js=parent.js_mostracensoinstsuperior1|ed257_i_codigo|ed257_c_nome','Pesquisa de Instituições de Ensino Superior',true);
  }else{
   if(document.form1.ed27_i_censoinstsuperior.value != ''){
    js_OpenJanelaIframe('','db_iframe_censoinstsuperior','func_censoinstsuperior.php?cursoformacao='+document.form1.ed27_i_cursoformacao.value+'&pesquisa_chave='+document.form1.ed27_i_censoinstsuperior.value+'&funcao_js=parent.js_mostracensoinstsuperior','Pesquisa',false);
   }else{
    document.form1.ed257_c_nome.value = '';
   }
  }
 }
}
function js_mostracensoinstsuperior(chave,erro){
 document.form1.ed257_c_nome.value = chave;
 if(erro==true){
  document.form1.ed27_i_censoinstsuperior.focus();
  document.form1.ed27_i_censoinstsuperior.value = '';
 }
}
function js_mostracensoinstsuperior1(chave1,chave2){
 document.form1.ed27_i_censoinstsuperior.value = chave1;
 document.form1.ed257_c_nome.value = chave2;
 db_iframe_censoinstsuperior.hide();
}

function js_verificacao(valor){
 if(valor=='CUR'){
  alert("Informe o ano de inicio");
  document.form1.ed27_i_formacaopedag.disabled=true;
  document.form1.ed27_i_anoconclusao.value='';
  document.form1.ed27_i_anoconclusao.disabled=true;
 }else{
	 document.form1.ed27_i_anoconclusao.disabled=false;
	 document.form1.ed27_i_formacaopedag.disabled=false;
 }

 if(valor=='CON'){
	 alert("Informe o ano de conclusao");
	 document.form1.ed27_i_anoinicio.disabled=true;
	 document.form1.ed27_i_anoinicio.value='';
	 document.form1.ed27_i_formacaopedag.disabled=false;
 }else{
	 document.form1.ed27_i_formacaopedag.disabled=true;
	 document.form1.ed27_i_anoinicio.disabled=false;
 }
}

function js_validacao(){
		if(document.form1.ed27_c_situacao.value=='CUR'){
			if(document.form1.ed27_i_anoinicio.value==''){
			  alert("Informe o ano de início");
			  document.form1.ed27_i_anoinicio.focus();	 
			  document.form1.ed27_i_anoconclusao.value='';
			  return false;
			}

			if(document.form1.ed27_i_anoconclusao.value!=''){
				 alert("Ano de conclusão não deve ser informado");
				  document.form1.ed27_i_anoconclusao.value='';
				  document.form1.ed27_i_anoinicio.focus();	 
				  return false;
			}
		}else{
			document.form1.ed27_i_anoconclusao.disabled=false;
		}

		if(document.form1.ed27_c_situacao.value=='CON'){
			if(document.form1.ed27_i_anoconclusao.value==''){			
			  alert("Informe o ano de conclusao");			 
			  document.form1.ed27_i_anoinicio.value='';
			  document.form1.ed27_i_anoconclusao.focus();
			  document.form1.ed27_i_formacaopedag.disabled=false;
			  return false;
			}
			if(document.form1.ed27_i_anoinicio.value!=''){			
			  alert("Ano de início não deve ser informado");
			  document.form1.ed27_i_anoinicio.value='';
			  document.form1.ed27_i_anoinicio.focus();	 
			  return false;
			}
		}else{
			  document.form1.ed27_i_formacaopedag.disabled=true;
				document.form1.ed27_i_anoinicio.disabled=false;
		}
		
return true;
}

$('btnOutrosDados').observe("click", function() {
   
    var oParametro             = new Object();
     oParametro.exec           = "getAvaliacaoRecursoHumano";
     oParametro.iRecursoHumano = $F('ed27_i_rechumano');
     js_divCarregando('Aguarde, carregando dados da Avaliação', 'msgBox'); 
     var oAjax = new Ajax.Request('edu4_dadoscensoescola.RPC.php',
                                   {method:'post',
                                   parameters:'json='+Object.toJSON(oParametro),
                                   onComplete: js_montarAvaliacao
                                   });
     
   
});
 function js_montarAvaliacao (oResponse) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
    if (oRetorno.status == 1) {
       
       var iCodigoAvaliacao = '3000002';
       oAvaliacaoEscola     = new dbViewAvaliacao(iCodigoAvaliacao, oRetorno.iCodigoAvaliacao);
       oAvaliacaoEscola.show();
       $('btnSalvarPerguntas'+iCodigoAvaliacao).style.display = 'none';
       $('btnSalvarAvaliacao'+iCodigoAvaliacao).value         = 'Salvar';
    } else {
      alert('Dados da avaliação não disponíveis.');
    }
  }
</script>