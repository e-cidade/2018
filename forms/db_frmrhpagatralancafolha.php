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
          <td align="right"><b>Pagar:</b></td>
          <td>
            <?
            $arr_pagar = Array(0=>"Todos",1=>"Funcionários que não estão na justiça");
            db_select("pagar", $arr_pagar, true, 1, "");
            ?>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="incluir" type="submit" id="db_opcao" value="Gerar pagamento" onclick="return js_verifica_campos()">
    </td>
  </tr>
</table>
</center>
</form>
<script>
function js_verifica_campos(){
  if(document.form1.rh58_data_dia.value == "" || document.form1.rh58_data_mes.value == "" || document.form1.rh58_data_ano.value == ""){
    alert("Informe a data para pagamento!");
    document.form1.rh58_data_dia.select();
    document.form1.rh58_data_dia.focus();
  }else if(document.form1.rh58_tipoocor.value == ""){
    alert("Informe o tipo de ocorrência!");
    document.form1.rh58_tipoocor.focus();
  }else{
    return true;
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