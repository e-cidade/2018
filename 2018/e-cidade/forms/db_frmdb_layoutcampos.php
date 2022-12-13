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

//MODULO: configuracoes
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$cldb_layoutcampos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db51_descr");
$clrotulo->label("db53_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb52_codigo?>">
      <?=@$Ldb52_codigo?>
    </td>
    <td colspan="3"> 
<?
db_input('db52_codigo',8,$Idb52_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_layoutlinha?>">
       <?
       db_ancora(@$Ldb52_layoutlinha,"js_pesquisadb52_layoutlinha(true);",3);
       ?>
    </td>
    <td colspan="3" nowrap> 
<?
db_input('db52_layoutlinha',8,$Idb52_layoutlinha,true,'text',3," onchange='js_pesquisadb52_layoutlinha(false);'")
?>
       <?
db_input('db51_descr',40,$Idb51_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_nome?>">
      <?=@$Ldb52_nome?>
    </td>
    <td colspan="3"> 
<?
db_input('db52_nome',51,$Idb52_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_descr?>">
      <?=@$Ldb52_descr?>
    </td>
    <td colspan="3"> 
<?
db_input('db52_descr',51,$Idb52_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_layoutformat?>">
       <?
       db_ancora(@$Ldb52_layoutformat,"js_pesquisadb52_layoutformat(true);",$db_opcao);
       ?>
    </td>
    <td colspan="3" nowrap> 
<?
db_input('db52_layoutformat',8,$Idb52_layoutformat,true,'text',$db_opcao," onchange='js_pesquisadb52_layoutformat(false);'")
?>
       <?
db_input('db53_descr',40,$Idb53_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_posicao?>">
      <?=@$Ldb52_posicao?>
    </td>
    <td> 
<?
$sqlTamanho  = "select db52_posicao,db52_nome ";
$sqlTamanho .= "  from db_layoutcampos ";      
$sqlTamanho .= " where db52_layoutlinha = {$db52_layoutlinha}";
$sqlTamanho .= " union ";
$sqlTamanho .= "select (db52_posicao+db52_tamanho) as db52_posicao,'Posição livre' as db52_nome ";
$sqlTamanho .= "  from db_layoutcampos ";      
$sqlTamanho .= " where db52_layoutlinha = {$db52_layoutlinha}";
$sqlTamanho .= "   and db52_posicao     = (select max(db52_posicao) ";
$sqlTamanho .= "                             from db_layoutcampos where db52_layoutlinha = {$db52_layoutlinha})";
$sqlTamanho .= " order by db52_posicao";

$rsTamanho  = $cldb_layoutcampos->sql_record($sqlTamanho);
if ($cldb_layoutcampos->numrows > 0){
  
   if ($db_opcao == 1){
     
     $oLinha       = db_utils::fieldsMemory($rsTamanho, $cldb_layoutcampos->numrows -1);
     $db52_posicao = $oLinha->db52_posicao; 
     
   }
   db_selectrecord('db52_posicao',$rsTamanho,true, $db_opcao);
   
}else{
  $db52_posicao = 1;
  db_input('db52_posicao',6,$Idb52_posicao,true,'text',3,"");
} 
?>
    </td>
    <td align="right" nowrap title="<?=@$Tdb52_tamanho?>">
       <?=@$Ldb52_tamanho?>
    </td>
    <td> 
<?
db_input('db52_tamanho',6,$Idb52_tamanho,true,'text',$db_opcao,"");
db_input('db52_tamanho_old',6,null,true,'hidden',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_ident?>">
      <?=@$Ldb52_ident?>
    </td>
    <td> 
<?
$x = array('f'=>'Não','t'=>'Sim');
db_select('db52_ident',$x,true,$db_opcao,"");
?>
    </td>
    <td nowrap title="<?=@$Tdb52_imprimir?>" align="right">
      <?=@$Ldb52_imprimir?>
    </td>
    <td> 
<?
$x = array('t'=>'Sim','f'=>'Não');
db_select('db52_imprimir',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_default?>">
      <?=@$Ldb52_default?>
    </td>
    <td colspan="3" nowrap> 
<?
db_textarea('db52_default',2,48,$Idb52_default,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_obs?>">
       <?=@$Ldb52_obs?>
    </td>
    <td colspan="3" nowrap> 
<?
db_textarea('db52_obs',2,48,$Idb52_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb52_alinha?>">
       <?=@$Ldb52_alinha?>
    </td>
    <td> 
<?
$x = array('d'=>'Esquerda','e'=>'Direita');
db_select('db52_alinha',$x,true,$db_opcao,"");
?>
    </td>
    <td nowrap title="<?=@$Tdb52_quebraapos?>" align="right">
       <?=@$Ldb52_quebraapos?>
    </td>
    <td> 
<?
db_input('db52_quebraapos',2,$Idb52_quebraapos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
      <?if($db_opcao==1||isset($db_opcaoal)){?>
      <input name="importar" type="button" id="importar" value="Importar campo" onclick="js_importarcampo();" >
      <?}else{?>
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();">
      <?}?>
    </td>
  </tr>
</table>
<table width="90%">
  <tr>
    <td valign="top"  align="center">  
      <?
      $dbwhere = " db52_layoutlinha = ".@$db52_layoutlinha;
      if(isset($db52_codigo) && trim($db52_codigo) != ""){
	$dbwhere .= " and db52_codigo <> ".$db52_codigo;
      }
      $chavepri= array("db52_codigo"=>@$db52_codigo);
      $cliframe_alterar_excluir->chavepri=$chavepri;
      $cliframe_alterar_excluir->sql     = $cldb_layoutcampos->sql_query(null,"db52_codigo,db52_nome,db52_descr,db52_layoutformat,db53_descr,lpad(db52_posicao,4,'0')||'-'||lpad((db52_posicao - 1 + (case when db52_tamanho = 0 then db53_tamanho else db52_tamanho end)),4,'0') as db52_posicao,db52_ident,db52_default,case when db52_tamanho = 0 then db53_tamanho else db52_tamanho end as db52_tamanho,db52_alinha ","db52_posicao desc",$dbwhere);
      $cliframe_alterar_excluir->campos  ="db52_posicao,db52_tamanho,db52_nome,db52_descr,db53_descr,db52_ident,db52_default";
      $cliframe_alterar_excluir->legenda="ITENS LANÇADOS";
      $cliframe_alterar_excluir->iframe_height ="160";
      $cliframe_alterar_excluir->iframe_width ="100%";
      $val = 1;
      if($db_opcao==3){
        $val = 4;
      }
      $cliframe_alterar_excluir->opcoes = $val;
      $cliframe_alterar_excluir->iframe_alterar_excluir(1);
      ?>
      </td>
    </tr>
  </table>
</center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisadb52_layoutlinha(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_layoutcampos','db_iframe_db_layoutlinha','func_db_layoutlinha.php?funcao_js=parent.js_mostradb_layoutlinha1|db51_codigo|db51_descr','Pesquisa',true);
  }else{
     if(document.form1.db52_layoutlinha.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_layoutcampos','db_iframe_db_layoutlinha','func_db_layoutlinha.php?pesquisa_chave='+document.form1.db52_layoutlinha.value+'&funcao_js=parent.js_mostradb_layoutlinha','Pesquisa',false);
     }else{
       document.form1.db51_descr.value = ''; 
     }
  }
}
function js_mostradb_layoutlinha(chave,erro){
  document.form1.db51_descr.value = chave; 
  if(erro==true){ 
    document.form1.db52_layoutlinha.focus(); 
    document.form1.db52_layoutlinha.value = ''; 
  }
}
function js_mostradb_layoutlinha1(chave1,chave2){
  document.form1.db52_layoutlinha.value = chave1;
  document.form1.db51_descr.value = chave2;
  db_iframe_db_layoutlinha.hide();
}
function js_pesquisadb52_layoutformat(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_db_layoutcampos','db_iframe_db_layoutformat','func_db_layoutformat.php?funcao_js=parent.js_mostradb_layoutformat1|db53_codigo|db53_descr|db53_alinha','Pesquisa',true,'0');
  }else{
     if(document.form1.db52_layoutformat.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_db_layoutcampos','db_iframe_db_layoutformat','func_db_layoutformat.php?pesquisa_chave='+document.form1.db52_layoutformat.value+'&funcao_js=parent.js_mostradb_layoutformat','Pesquisa',false);
     }else{
       document.form1.db53_descr.value = ''; 
     }
  }
}
function js_mostradb_layoutformat(chave,chave2,erro){
  document.form1.db53_descr.value = chave; 
  if(erro==true){ 
    document.form1.db52_layoutformat.focus(); 
    document.form1.db52_layoutformat.value = ''; 
  }else{
    for(var i=0; i<document.form1.db52_alinha.length; i++){
      if(document.form1.db52_alinha.options[i].value == chave2){
        document.form1.db52_alinha.options[i].selected = true;
	break;
      }
    }
  }
}
function js_mostradb_layoutformat1(chave1,chave2,chave3){
  document.form1.db52_layoutformat.value = chave1;
  document.form1.db53_descr.value = chave2;
  for(var i=0; i<document.form1.db52_alinha.length; i++){
    if(document.form1.db52_alinha.options[i].value == chave3){
      document.form1.db52_alinha.options[i].selected = true;
      break;
    }
  }
  db_iframe_db_layoutformat.hide();
}
function js_importarcampo(){
  js_OpenJanelaIframe('top.corpo.iframe_db_layoutcampos','db_iframe_db_layoutcampos','func_db_layoutcampos.php?funcao_js=parent.js_mostradb_layoutcampos|db52_codigo','Pesquisa',true,0);
}
function js_mostradb_layoutcampos(chave){
  location.href = "con1_db_layoutcampos001.php?db52_layoutlinha=<?=$db52_layoutlinha?>&chave_pesquisa="+chave;
}
</script>