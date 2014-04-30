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

$clrhpagatra->rotulo->label();
$clrhpagocor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
if(!isset($pagar)){
  $pagar = 1;
}
if(!isset($rh58_data_dia) && !isset($rh58_data_mes) && !isset($rh58_data_ano)){
  $rh58_data_dia = date("d",db_getsession("DB_datausu"));
  $rh58_data_mes = date("m",db_getsession("DB_datausu"));
  $rh58_data_ano = date("Y",db_getsession("DB_datausu"));
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center" colspan="2">
      <table>
        <tr>
          <td nowrap title="<?=@$Trh58_data?>" align="right">
            <?
            db_ancora(@$Lrh58_data,"",3);
            ?>
          </td>
          <td nowrap> 
            <?
            db_inputdata("rh58_data", $rh58_data_dia, $rh58_data_mes, $rh58_data_ano, true, 'text', 1);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Trh58_tipoocor?>" align="right">
            <?
            db_ancora(@$Lrh58_tipoocor,"js_pesquisarh58_tipoocor(true);",1);
            ?>
          </td>
          <td nowrap> 
            <?
            db_input('rh58_tipoocor',8,$Irh58_tipoocor,true,'text',1," onchange='js_pesquisarh58_tipoocor(false);'")
            ?>
            <?
            db_input('rh59_descr',40,$Irh59_descr,true,'text',3,'');
            db_input('rh59_tipo',2,$Irh59_tipo,true,'hidden',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td align="center" colspan="2">
            <?
            $dbwhere = "";
	    $dbwhere_sselecionados = "";
	    $dbwhere_nselecionados = "";

            $usar_justica = false;

            if($pagar == 1){

              $dbwhere = "
                          and (
                               rh61_regist is null
                               or (
                                       rh61_regist is not null
                                   and rh61_datafim is not null
                                   and
                                      (
                                          '".date("Y-m-d",db_getsession("DB_datausu"))."' < rh61_dataini
                                       or '".date("Y-m-d",db_getsession("DB_datausu"))."' > rh61_datafim
                                      )
                                  )
                              )
	                 ";
              $usar_justica = true;
              $dbwhere = " and registro is null ";
            }

            $result_dados_ssel = array();
            if(isset($selecionados) && count($selecionados) > 0){
              $sselecionados_where = "";
              $nselecionados_where = "";
              $selecionados_igual = "";
              $selecionados_difer = "";
              for($i=0; $i<count($selecionados); $i++){
                $ano_where = substr($selecionados[$i],0,4);
                $mes_where = substr($selecionados[$i],4,2);
                $sselecionados_where .= $selecionados_igual." rh57_ano = ".$ano_where." and rh57_mes = ".$mes_where;
                $nselecionados_where .= $selecionados_difer." (rh57_ano <> ".$ano_where." or rh57_mes <> ".$mes_where.") ";

                $selecionados_igual = " or ";
		$selecionados_difer = " and ";
              }
	      $dbwhere_sselecionados = " and ".$sselecionados_where;
	      $dbwhere_nselecionados = " and ".$nselecionados_where;
              $result_dados_ssel = $clrhpagatra->sql_record($clrhpagatra->sql_query_notjustica(null,"distinct rh57_ano, rh57_mes, rh57_ano || lpad(rh57_mes,2,'0') as value, rh57_ano || '/' || lpad(rh57_mes,2,'0') as descr "," rh57_ano, rh57_mes"," rh57_saldo > 0 ".$dbwhere.$dbwhere_sselecionados,$usar_justica));
	    }

            $result_dados_nsel = $clrhpagatra->sql_record($clrhpagatra->sql_query_notjustica(null,"distinct rh57_ano, rh57_mes, rh57_ano || lpad(rh57_mes,2,'0') as value, rh57_ano || '/' || lpad(rh57_mes,2,'0') as descr "," rh57_ano, rh57_mes"," rh57_saldo > 0 ".$dbwhere.$dbwhere_nselecionados,$usar_justica));
            db_multiploselect("value","descr", "naoselecionados", "selecionados", $result_dados_nsel, $result_dados_ssel, 10, 150, "", "", true);
            ?>
          </td>
        </tr>
        <tr>
          <td align="right"><b>Pagar:</b></td>
          <td>
            <?
            $arr_pagar = Array(0=>"Todos",1=>"Funcionários que não estão na justiça");
            db_select("pagar", $arr_pagar, true, 1, "onchange='js_seleciona_combo(document.form1.selecionados);document.form1.submit();'");
            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="right" nowrap title="<?=@$Trh58_obs?>">
      <?=@$Lrh58_obs?>
    </td>
    <td> 
      <?
      db_textarea('rh58_obs',4,48,$Irh58_obs,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="incluir" type="submit" id="db_opcao" value="Gerar pagamento" onclick="return js_seleciona_campo()">
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_seleciona_campo(){
  if(document.form1.rh58_data_dia.value == "" || document.form1.rh58_data_mes.value == "" || document.form1.rh58_data_ano.value == ""){
    alert("Informe a data para pagamento!");
    document.form1.rh58_data_dia.select();
    document.form1.rh58_data_dia.focus();
  }else if(document.form1.rh58_tipoocor.value == ""){
    alert("Informe o tipo de ocorrência!");
    document.form1.rh58_tipoocor.focus();
  }else{
    if(document.form1.selecionados.length > 0){
      js_seleciona_combo(document.form1.selecionados);
      return true;
    }else{
      alert("Informe, pelo menos, um período!");
    }
  }
  return false;
}
function js_pesquisarh58_tipoocor(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?funcao_js=parent.js_mostrarhpagtipoocor1|rh59_codigo|rh59_descr|rh59_tipo','Pesquisa',true);
  }else{
     if(document.form1.rh58_tipoocor.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_rhpagtipoocor','func_rhpagtipoocor.php?pesquisa_chave='+document.form1.rh58_tipoocor.value+'&funcao_js=parent.js_mostrarhpagtipoocor','Pesquisa',false);
     }else{
       document.form1.rh59_descr.value = '';
     }
  }
}
function js_mostrarhpagtipoocor(chave,chave2,erro){
  document.form1.rh59_descr.value = chave;
  if(erro==true){
    document.form1.rh58_tipoocor.focus();
    document.form1.rh58_tipoocor.value = '';
    document.form1.rh59_tipo.value = '';
  }else{
    document.form1.rh59_tipo.value = chave2;
  }
}
function js_mostrarhpagtipoocor1(chave1,chave2,chave3){
  document.form1.rh58_tipoocor.value = chave1;
  document.form1.rh59_descr.value = chave2;
  document.form1.rh59_tipo.value = chave3;
  db_iframe_rhpagtipoocor.hide();
}
</script>