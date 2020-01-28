<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("m51_codordem");
$clrotulo->label("z01_nome");
$where="";
$and="";
$valor_liq="false";
if(isset($m51_codordem) && $m51_codordem!=''){
  $where = "m72_codordem=$m51_codordem";
  $and = "and";
}    
if(isset($e69_codnota) && $e69_codnota!=''){
  $where .= "$and m72_codnota=$e69_codnota";
}
if (isset($anula)){
}else{
  $result = $clempnotaord->sql_record($clempnotaord->sql_query(null,null,"*","","$where"));
  db_fieldsmemory($result,0);
}

?>
<style>
<?$cor="#999999"?>
.bordas02{
  border: 2px solid #cccccc;
  border-top-color: <?=$cor?>;
  border-right-color: <?=$cor?>;
  border-left-color: <?=$cor?>;
  border-bottom-color: <?=$cor?>;
  background-color: #999999;
}
.bordas{
  border: 1px solid #cccccc;
  border-top-color: <?=$cor?>;
  border-right-color: <?=$cor?>;
  border-left-color: <?=$cor?>;
  border-bottom-color: <?=$cor?>;
  background-color: #cccccc;
}
</style>
<form name="form1" method="post" action="mat1_entraordcom004.php" onsubmit='js_buscavalores();'>
<center>
<table border='0'>
<tr align = 'left'>
<td align="left">
<table align="center">
<tr><br><br>
<td nowrap title="<?=@$Tm51_codordem?>">
<b> <?db_ancora("Ordem :","js_consultaordem($m51_codordem);",1);?></b>
</td>
<td> 
<?
db_input('m51_codordem',5,$Im51_codordem,true,'text',3);
db_input('z01_nome',30,$Iz01_nome,true,'text',3,'');

?>
</td>
<td nowrap title="<?=@$Te69_numero?>">
<?=@$Le69_numero?>
</td>
<td> 
<?
db_input('e69_numero',20,$Ie69_numero,true,'text',3,"")
?>
</td>
</tr>
<tr>
<td nowrap title="<?=@$Te69_id_usuario?>">
<?=@$Le69_id_usuario?>
</td>
<td> 
<?
db_input('e69_id_usuario',5,$Ie69_id_usuario,true,'text',3);

db_input('nome',30,$Inome,true,'text',3,'');
?>
</td>
<td nowrap title="<?=@$Te69_dtnota?>">
<?=@$Le69_dtnota?>
</td>
<td  > 
<?
if(empty($e69_dtnota_dia)){
  $e69_dtnota_dia =  date("d",db_getsession("DB_datausu"));
  $e69_dtnota_mes =  date("m",db_getsession("DB_datausu"));
  $e69_dtnota_ano =  date("Y",db_getsession("DB_datausu"));
  
}
db_inputdata('e69_dtnota',@$e69_dtnota_dia,@$e69_dtnota_mes,@$e69_dtnota_ano,true,'text',3,"");
?>
</td>
</tr>
<?
if (isset($continue)){
  $result_liquidado=$clempnotaele->sql_record($clempnotaele->sql_query_file(null,null,"e70_vlrliq,e70_valor",null,"e70_codnota=$m72_codnota"));
  for ($i=0; $clempnotaele->numrows>$i; $i++ ){
    db_fieldsmemory($result_liquidado,$i);
    if ($e70_vlrliq>0){
      $valor_liq="true";
    }
  }
}
?>
<tr>
<td nowrap title="<?=@$Te69_dtrecebe?>">
<?=@$Le69_dtrecebe?>
</td>
<td> 
<?
$ano=substr($e69_dtrecebe,0,4);
$mes=substr($e69_dtrecebe,5,2);
$dia=substr($e69_dtrecebe,8,2);
db_inputdata('e69_dtrecebe',"$dia","$mes","$ano",true,'text',3);
?>
</td>
<td nowrap title="<?=@$e70_valor?>"  >
<?=@$Le70_valor ?>
</td>
<td>
<?
if (isset($continue)){
  $op=3;
}else{
  $op=1;
}
db_input('e70_valor',20,$Ie70_valor,true,'text',$op,"");
?>
</td>
</tr>
</table><?
$m51_depto=$m51_depto;
db_input("m51_depto",5,"",true,"hidden",3);
?>
</td>
</tr>
<tr>
<td>
</td>
</tr>
<tr align = "center">
<td>
<?
$result_liquidado=$clempnotaele->sql_record($clempnotaele->sql_query_file(null,null,"e70_vlrliq",null,"e70_codnota=$m72_codnota"));
for ($i=0; $clempnotaele->numrows>$i; $i++ ){
  db_fieldsmemory($result_liquidado,$i);
  if ($e70_vlrliq>0){
    $valor_liq="true";
  }
}
if ($valor_liq=="true"){
  
  ?>
  <input name="anula" type="submit"  value="Alterar" disabled >
  <input name="voltar" type="button" value="Voltar" onclick="location.href='mat1_entraordcom003.php';" >
  <?
  echo "<script>alert('Não é possivel anular este lançamento, valor ja liquidado!!');</script>";
}else{
  if (isset($continue)){
    ?>
    <input name="anula" type="submit"  value="Alterar" >
    <?
  }else{
    ?>
    <input name="continue" type="submit"  value="Continuar" >
  <?}?>
  <input name="voltar" type="button" value="Voltar" onclick="location.href='mat1_entraordcom003.php';" >
<?}	 
$m51_codordem=$m51_codordem;
db_input("m51_codordem",5,"",true,"hidden",3);
db_input("e69_codnota",5,"",true,"hidden",3);
?>
</td>
</tr>
</table>
<table>
<?if (isset($anula)){
}else if (isset($continue)){?>
  <tr>
  <td>
  <iframe name="itens" id="itens" src="forms/db_frmentraordcomitemanu.php?m51_codordem=<?=$m51_codordem?>&m72_codnota=<?=$m72_codnota?>" width="720" height="220" marginwidth="0" marginheight="0" frameborder="0">
  </iframe>
  </td>
  </tr>
<?}?>
</table>
</center>
<?
db_input("valores",100,0,true,"hidden",3);
db_input("valmul",100,0,true,"hidden",3);
db_input("codunidade",100,0,true,"hidden",3);
db_input("qantigas",100,0,true,"hidden",3);
?>
</form>
</body>
<script>
function js_consultaordem(codordem){
  js_OpenJanelaIframe('top.corpo','db_iframe_ordemcompra002','com3_ordemdecompra002.php?m51_codordem='+codordem,'Consulta Ordem de Compra',true);
}
function js_buscavalores(){
  <?if (isset($continue)){ ?>
    obj= itens.document.form1;
    valor="";
    valormul="";
    coduni="";
    qantigas="";
    altera='';
    for (i=0;i<obj.elements.length;i++){
      if (obj.elements[i].name.substr(0,6)=="quant_"){
        if (obj.elements[i].value!=""){
          var objvalor=new Number(obj.elements[i].value);
          valor+=obj.elements[i].name+"_"+obj.elements[i].value;
          altera=true;
        }else{
          altera=false;
        }
      }
			//alert(obj.elements[i].name);
			//alert(altera);
      if (obj.elements[i].name.substr(0,9)=="qantigas_"){
        if (altera==true){
          var objvalor=new Number(obj.elements[i].value);
          qantigas+=obj.elements[i].name+"_"+obj.elements[i].value;
        }
      }
      if (obj.elements[i].name.substr(0,7)=="qntmul_"){
        if (altera==true){
          var objvalor=new Number(obj.elements[i].value);
          valormul+=obj.elements[i].name+"_"+obj.elements[i].value;
        }
      }
      if (obj.elements[i].name.substr(0,8)=="codunid_"){
        if (altera==true){
          var objvalor=new Number(obj.elements[i].value);
          coduni+=obj.elements[i].name+"_"+obj.elements[i].value;
        }
      }
    }
    document.form1.valores.value = valor;
    document.form1.valmul.value = valormul;
    document.form1.codunidade.value = coduni;
    document.form1.qantigas.value = qantigas;
  <?
	}
	?>
}
</script>
</html>