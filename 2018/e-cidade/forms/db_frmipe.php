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
$clipe->rotulo->label();
?>
<form name="form1" method="post" action="" class="container">
<fieldset>
<legend>Processamento dos dados IPERGS</legend>
<table border="0" class="form-container">
  <tr>
    <td nowrap title="Ano / Mês de competência" align="right" width="49%">
      <b>Ano / Mês:</b>
    </td>
    <td> 
      <?
      $r36_anousu = db_anofolha('DB_anousu');
      db_input('r36_anousu',4,$Ir36_anousu,true,'text',1)
      ?>
      <b>/</b>
      <?
      $r36_mesusu = db_mesfolha('DB_mesusu');
      db_input('r36_mesusu',2,$Ir36_mesusu,true,'text',1)
      ?>
    </td>
  </tr>
  <?
  $result_cfpes = $clcfpess->sql_record($clcfpess->sql_query_file($r36_anousu,$r36_mesusu,db_getsession("DB_instit"),"r11_mes13"));
  if($clcfpess->numrows > 0){
    db_fieldsmemory($result_cfpes, 0);
  }
  if($r36_mesusu == $r11_mes13){
  ?>
    <tr>
      <td nowrap title="Gerar IPE sobre" align="right" width="49%">
        <b>Gerar IPE sobre:</b>
      </td>
      <td> 
        <?
        $arr_opcoes = array("1"=>"Salário","2"=>"13o Salário");
        db_select("xtipo",$arr_opcoes,true,1);
        ?>
      </td>
    </tr>
  <?
  }else{
    db_input('xtipo',4,0,true,'hidden',3);
  }
  ?>
</table>
</fieldset>
<input name="processar" type="submit" id="db_opcao" value="Processar" onclick="return js_confirma();">
</form>
<script>
function js_confirma(){
  if(confirm("Deseja gerar IPE?")){
    if(document.form1.xtipo.type != "select-one"){
      document.form1.xtipo.value = 1;
    }
    return true;
  }
  return false;
}
</script>
