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

$clrhpessoal->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("r70_estrut");
$clrotulo->label("r70_descr");
$clrotulo->label("rh02_salari");
?>
<form name="form1" method="post" action="">
<table border="0" cellspacing="8" cellpadding="0" width="95%">
  <tr>
    <td colspan="2" align='center'>
      <fieldset>
        <Legend align="left">
          <b>Reajuste de Salários</b>
        </Legend>
        <center>
        <table cellspacing="8" cellpadding="0">
	  <?
	  $dbwhere = " rh02_anousu = ".$anofolha." and rh02_mesusu = ".$mesfolha;
	  if(isset($para) && $para == "s"){
	    $dbwhere .= " and rh02_salari > 0 ";
	  }
	  if(isset($matini) || isset($matfim)){
            if(trim($matini) != "" && trim($matfim) != ""){
              $dbwhere.= " and rh01_regist between ".$matini." and ".$matfim; 
            }else if(trim($matini) != ""){
	      $dbwhere.= " and rh01_regist >= ".$matini; 
            }else if(trim($matfim) != ""){
              $dbwhere.= " and rh01_regist <= ".$matfim; 
            }
	  }
          if(isset($selmatri) && count($selmatri) > 0){
	    $campo_auxilio_regi = "";
	    for($i=0; $i<count($selmatri); $i++){
	      $campo_auxilio_regi.= ($i==0?"":",").$selmatri[$i];
	    }
          }
	  if(isset($campo_auxilio_regi) && trim($campo_auxilio_regi) != ""){
            $dbwhere.= " and rh01_regist in (".$campo_auxilio_regi.") ";
	  }
	  if(isset($lotini) || isset($lotfim)){
	    if(trim($lotini) != "" && trim($lotfim) != ""){
	      $dbwhere.= " and r70_estrut between '".$lotini."' and '".$lotfim."' "; 
	    }else if(trim($lotini) != ""){
	      $dbwhere.= " and r70_estrut >= '".$lotini."' ";
	    }else if(trim($lotfim) != ""){
	      $dbwhere.= " and r70_estrut <= '".$lotfim."' ";
            }
	  }
	  if(isset($sellotac) && count($sellotac) > 0){
	    $campo_auxilio_lota = "";
	    for($i=0; $i<count($sellotac); $i++){
	      $campo_auxilio_lota.= ($i==0?"":",")."'".$sellotac[$i]."'";
	    }
	  }
	  if(isset($campo_auxilio_lota) && trim($campo_auxilio_lota) != ""){
            $dbwhere.= " and r70_estrut in (".$campo_auxilio_lota.") ";
	  }
          db_input('vallancar',10, 0, true, 'hidden', 3);
          db_input('anofolha',4, 0, true, 'hidden', 3);
          db_input('mesfolha',2, 0, true, 'hidden', 3);
          db_input('para',2, 0, true, 'hidden', 3);
          db_input('matini',2, 0, true, 'hidden', 3);
          db_input('matfim',2, 0, true, 'hidden', 3);
          db_input('lotini',2, 0, true, 'hidden', 3);
          db_input('lotfim',2, 0, true, 'hidden', 3);
          db_input('lotfim',2, 0, true, 'hidden', 3);
          db_input('campo_auxilio_regi',2, 0, true, 'hidden', 3);
          db_input('campo_auxilio_lota',2, 0, true, 'hidden', 3);
          $campofocar = "valor";
	  // die($clrhpessoal->sql_query_cgmmov(null,"rh01_regist,rh02_seqpes,rh01_numcgm,z01_nome,rh02_salari,r70_codigo,r70_estrut,r70_descr","",$dbwhere));
	  $result_rhpessoal = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgmmov(null,"rh01_regist,rh02_seqpes,rh01_numcgm,z01_nome,rh02_salari,r70_codigo,r70_estrut,r70_descr","",$dbwhere));
	  for($i=0;$i<$clrhpessoal->numrows;$i++){
	    db_fieldsmemory($result_rhpessoal, $i);
	    if($campofocar == ""){
	      $campofocar = 'valor_'.$rh01_regist;
            }
	    if($i==0){
	  ?>
          <thead>
            <tr>
              <td align='center' width="5%" ><b><?=$RLrh01_regist?></b></td>
              <td align='center' width="30%"><b><?=$RLz01_nome?></b></td>
              <td align='center' width="10%"><b><?=$RLr70_estrut?></b></td>
              <td align='center' width="30%"><b><?=$RLr70_descr?></b></td>
              <td align='center' width="5%" ><b><?=$RLrh02_salari?></b></td>
              <td align='center' width="10%"><b>Valor</b></td>
              <td align='center' width="10%"><b>(%)</b></td>
              <td align='center'>&nbsp;</td>
              <td align='center'>&nbsp;</td>
            </tr>
          </thead>
          <tbody style='max-height:35ex;max-width:90%;overflow:auto;'>
	    <?
	    }
	    ?>
            <tr>
              <td align='center' width="5%" ><?=$rh01_regist?></td>
              <td align='left'   width="30%"><?=$z01_nome?>   </td>
              <td align='center' width="10%"><?=$r70_estrut?> </td>
              <td align='left'   width="30%"><?=$r70_descr?>  </td>
              <td align='right'  width="5%" ><?=db_formatar($rh02_salari,"f")?></td>
              <td align='center' width="10%">
                <?
                db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, "onchange='js_desabcampos(\"".$rh02_seqpes."\",\"valor_\",\"perce_\");'", 'valor_'.$rh02_seqpes);
                ?>
              </td>
              <td align='center' width="10%">
                <?
                db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, "onchange='js_desabcampos(\"".$rh02_seqpes."\",\"valor_\",\"perce_\");'", 'perce_'.$rh02_seqpes);
                ?>
              </td>
              <td align='center'>&nbsp;</td>
              <td align='center'>&nbsp;</td>
	    </tr>
	    <?
	    }
	    ?>
          </tbody>
        </table>
        </center>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" align='center'>
      <fieldset>
        <Legend align="left">
          <b>Lançar valores</b>
        </Legend>
        <center>
        <table cellspacing="8" cellpadding="0">
	  <tr>
            <td align='right'>Valor padrão:</td>
            <td align='left'>
              <?
              db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, "onchange='js_lancarvalor(\"v\",this.value);'", 'valor');
              ?>
            </td>
            <td align='right'>Percentual padrão:</td>
            <td align='left'>
              <?
              db_input('rh02_salari',10, $Irh02_salari, true, 'text', 1, "onchange='js_lancarvalor(\"p\",this.value);'", 'perce');
              ?>
            </td>
	  </tr>
	</table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
      <input type="submit" name="processar" value="Processar" onclick="return js_testecampos();">
      <input type="button" name="limpar"    value="Limpar" onclick="js_limparcampos('');">
      <input type="button" name="voltar"    value="Voltar" onclick="location.href='pes1_reajustesal001.php'" onblur="js_chamafuncao('<?=@$campofocar?>',true);">
      <!-- <input type="button" name="zerar"     value="Zerar"  onclick="js_limparcampos('0');"> -->
    </td>
  </tr>
</table>
</form>
<script>
var time;
var camposel;
function js_testecampos(){
  vallancar = "";
  virgula   = "";
  for(var i=0; i<document.form1.length;i++){
    if(document.form1.elements[i].type == "text"){
      arr = document.form1.elements[i].name.split("_");
      if(document.form1.elements[i].readOnly == false && arr[0] && arr[1]){
	if(document.form1.elements[i].value != ""){
          vallancar += virgula + arr[1] + "-" + arr[0] + "-" + document.form1.elements[i].value;
	  virgula = ",";
	}
      }
    }
  }
  document.form1.vallancar.value = "";
  if(vallancar != ""){
    document.form1.vallancar.value = vallancar;
    return true;
  }
  alert("Informe os valores a serem lançados.");
  return false;
}
function js_lancarvalor(PorV,valor){
  for(var i=0; i<document.form1.length;i++){
    if(document.form1.elements[i].type == "text"){
      valorcampo = new Number(document.form1.elements[i].value);
      valor = new Number(valor);
      if(((valorcampo == 0 && valor > 0) || valor == 0) && document.form1.elements[i].readOnly == false){
        arr = document.form1.elements[i].name.split("_");
        if((PorV == "v" && arr[0] == "valor") || (PorV == "p" && arr[0] == "perce")){
	  document.form1.elements[i].value = valor;
          js_desabcampos(arr[1],"valor_","perce_");
        }
      }
    }
  }
}
function js_limparcampos(valor){
  for(var i=0; i<document.form1.length;i++){
    if(document.form1.elements[i].type == "text"){
      arr = document.form1.elements[i].name.split("_");
      document.form1.elements[i].value = valor;
      i++;
      document.form1.elements[i].value = valor;
      js_desabcampos(arr[1],"valor_","perce_");
    }
  }
}
function js_chamafuncao(campo,focar){
  js_tabulacaoforms("form1",campo,focar,1,campo,focar);
}
function js_seleciona_campo_confirma(){
  if(document.form1.elements[camposel]){
    js_chamafuncao(document.form1.elements[camposel].name,true);
  }
  clearInterval(time);
}
function js_desabcampos(campo,opcao,receb){
  camposel = "";
  if(eval("document.form1."+opcao+campo) && eval("document.form1."+receb+campo)){
    eval("valorcampoop = new Number(document.form1."+opcao+campo+".value);");
    eval("valorcamporc = new Number(document.form1."+receb+campo+".value);");
    if(valorcampoop == 0 && valorcamporc == 0){
      eval("document.form1."+opcao+campo+".readOnly = false;");
      eval("document.form1."+opcao+campo+".style.backgroundColor = '';");
 
      eval("document.form1."+receb+campo+".readOnly = false;");
      eval("document.form1."+receb+campo+".style.backgroundColor = '';");
    }else if(valorcampoop > 0){
      eval("document.form1."+receb+campo+".value    = '';");
      eval("document.form1."+receb+campo+".readOnly = true;");
      eval("document.form1."+receb+campo+".style.backgroundColor = '#DEB887';");
      for(var i=0; i<document.form1.length;i++){
        if(document.form1.elements[i].name == opcao+campo){
          break;
        }
      }
      if(document.form1.elements[(i+2)]){
        camposel = document.form1.elements[(i+2)].name;
        time = setInterval(js_seleciona_campo_confirma,10);
      }
    }else if(valorcamporc > 0){
      eval("document.form1."+opcao+campo+".value    = '';");
      eval("document.form1."+opcao+campo+".readOnly = true;");
      eval("document.form1."+opcao+campo+".style.backgroundColor = '#DEB887';");
      for(var i=0; i<document.form1.length;i++){
        if(document.form1.elements[i].name == receb+campo){
          break;
        }
      }
      if(document.form1.elements[(i+2)]){
        camposel = document.form1.elements[(i+1)].name;
        time = setInterval(js_seleciona_campo_confirma,10);
      }
    }
  }
}
</script>