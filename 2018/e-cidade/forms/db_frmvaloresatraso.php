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

include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clrotulo = new rotulocampo;
$clrhpessoal->rotulo->label();
$clrhpagatra->rotulo->label();
$clrhpagocor->rotulo->label();
$clrotulo->label("rh59_descr");
?>
<form name="form1">
<center>
<table border="0">
  <tr>
    <td>
      <fieldset>
        <legend><b>Ocorrência</b></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Trh58_data?>">
              <?=@$Lrh58_data?>
            </td>
            <td> 
              <?
              db_inputdata("rh58_data",@$rh58_data_dia,@$rh58_data_mes,@$rh58_data_ano,true,'text',$db_opcao);
//function db_textarea($nome, $dbsizelinha = 1, $dbsizecoluna = 1, $dbvalidatipo, $dbcadastro = true, $dbhidden = 'text', $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "") {
              ?>
            </td>
	    <td nowrap title="<?=@$Trh58_valor?>">
	      <?=@$Lrh58_valor?>
	    </td>
	    <td> 
	      <?
              db_input('rh58_valor',10,$Irh58_valor,true,'text',3,"")
	      ?>
	    </td>
          </tr>
	  <tr>
            <td nowrap title="<?=@$Trh58_tipoocor?>">
              <?
              db_ancora(@$Lrh58_tipoocor,"js_pesquisarh58_tipoocor(true);",3);
              ?>
            </td>
            <td colspan="3" nowrap> 
              <?
              db_input('rh58_tipoocor',10,$Irh58_tipoocor,true,'text',3," onchange='js_pesquisarh58_tipoocor(false);'")
              ?>
              <?
              db_input('rh59_descr',48,$Irh59_descr,true,'text',3,'');
              db_input('rh58_seq',6,$Irh58_seq,true,'hidden',3,"");
              db_input('rh58_codigo',6,$Irh58_codigo,true,'hidden',3,"");
              ?>
            </td>
	  </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
<table>
  <tr>
    <td>
      <?
      /*
      $dbgroupby = "rh57_seq, rh57_ano, rh57_mes, rh57_regist, rh57_valorini, rh57_saldo, rh58_tipoocor"; 
      $dbhaving = " rh57_regist = $rh57_regist ";
      if(isset($rh57_seq) && trim($rh57_seq) != ""){
        $dbhaving.= " and rh57_seq <> $rh57_seq";
      }
      if(!isset($mostrarsaldo) || (isset($mostrarsaldo) && $mostrarsaldo == "s")){
        $dbhaving.= " and sum(rh58_valor) > 0";
      }
      */
      $sql = $clrhpagatra->sql_query_tipoatras(null," distinct rh57_seq, rh57_ano, rh57_mes, rh57_regist, rh57_valorini, rh57_saldo, rh58_tipoocor","rh57_seq limit 1","");

     // $result_rhpagatra = $clrhpagatra->sql_record($clrhpagatra->sql_query_file(null,"",""," rh57_seq = ".$seq." and rh57_ano = ".$ano." and rh57_mes = ".$mes));
      $chavepri= array("rh57_seq"=>@$rh57_seq,"rh57_regist"=>@$rh57_regist,"rh57_ano"=>@$rh57_ano,"rh57_mes"=>@$rh57_mes);
      $cliframe_alterar_excluir->chavepri = $chavepri;
      $cliframe_alterar_excluir->sql    = $sql;
      $cliframe_alterar_excluir->campos = "rh57_ano, rh57_mes, rh57_regist, rh57_valorini, rh57_saldo";
      $cliframe_alterar_excluir->legenda= "ATRASOS LANÇADOS";
      $cliframe_alterar_excluir->iframe_height = "400";
      $cliframe_alterar_excluir->iframe_width  = "100%";
      $cliframe_alterar_excluir->opcoes = 1;
      $cliframe_alterar_excluir->msg_vazio = "Sem atrasos para este funcionário";
      $cliframe_alterar_excluir->formulario = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
    </td>
  </tr>
</table>
<center>
</form>
<script>
function js_pesquisarh58_tipoocor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor?funcao_js=parent.js_mostrarhpagtipoocor1|rh59_codigo|rh59_descr','Pesquisa',true);
  }else{
     if(document.form1.rh58_tipoocor.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor?pesquisa_chave='+document.form1.rh58_tipoocor.value+'&funcao_js=parent.js_mostrarhpagtipoocor','Pesquisa',false);
     }else{
       document.form1.rh59_descr.value = '';
     }
  }
}
function js_mostrarhpagtipoocor(chave,erro){
  document.form1.rh59_descr.value = chave;
  if(erro==true){
    document.form1.rh58_tipoocor.focus();
    document.form1.rh58_tipoocor.value = '';
  }
}
function js_mostrarhpagtipoocor1(chave1,chave2){
  document.form1.rh58_tipoocor.value = chave1;
  document.form1.rh59_descr.value = chave2;
  db_iframe_rhpagtipoocor.hide();
}>
</script>