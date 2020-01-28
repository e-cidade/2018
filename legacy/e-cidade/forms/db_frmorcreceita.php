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

//MODULO: orcamento
$clorcreceita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o57_fonte");
$clrotulo->label("o57_descr");
$clrotulo->label("o15_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("c58_descr");

$anousu = db_getsession("DB_anousu");

if(isset($chavepesquisa)){
  $o50_estrutreceita=db_formatar($o50_estrutreceita,"receita_int");
}  
if((isset($atualizar) || isset($o50_estrutreceita)) && empty($incluir)&& empty($alterar)&& empty($excluir) && empty($chavepesquisa)){
  $matriz= split("\.",$o50_estrutreceita);
  $inicia=false;//variavel que indica que o nivel não tem mais filhos
  $tam=(count($matriz)-1);
  $codigos='';
  for($i=$tam; $i>=0; $i--){
    $codigo='';//monta os codigos para a pesquisa
    if($matriz[$i]!="0" || $inicia==true){
      $inicia=true;
      for($x=$i; $x>=0; $x--){
	  $codigo=$matriz[$x].$codigo;
      } 	 
    }
    if($inicia==true){
      break;
    }  
  }
  if ($anousu > 2007){
    $campo_concarpeculiar = " and o70_concarpeculiar = '$o70_concarpeculiar'";
  } else {
    $campo_concarpeculiar = "";
  }
  $taman=strlen($codigo);
  $clorcfontes->sql_record($clorcfontes->sql_query(null,null,"o57_fonte",'',"substr(o57_fonte,1,$taman)='$codigo' and o57_anousu = $anousu"));
  $result01 = $clorcreceita->sql_record($clorcreceita->sql_query(null,null,"o70_codrec as codrec",'',
                  "o70_anousu=".db_getsession('DB_anousu')." 
                   and o57_fonte='".str_replace(".","",$o50_estrutreceita) ."'

                   $campo_concarpeculiar"));   
                   
  if($clorcfontes->numrows>1){
     $negado=true;    
  }else if($clorcreceita->numrows>0){
    db_fieldsmemory($result01,0);
    if(isset($o70_codrec) && $o70_codrec!=$codrec){
      $cadastrado = "O código da fonte já foi cadastrado!";
    }else if(empty($o70_codrec)){
      $cadastrado = "O código da fonte já foi cadastrado!";
    }
  } 
}  
?>
<style>
.cabec{
text-align: center;
font-size: 10px;
font-weight: bold;
background-color:#aacccc ;
color: darkblue;

}
.corpo {
background-color:#ccddcc;       
text-align: center;
}
</style>
<?
if($db_opcao==1){
  $pg="orc1_orcreceita001.php";
}else if($db_opcao==2 || $db_opcao==22){
  $pg="orc1_orcreceita002.php";
}else{
  $pg="orc1_orcreceita003.php";
}
?>
<form name="form1" method="post" action="<?=$pg?>">
<center>
<?
//  exit;
?>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To70_anousu?>">
       <?=@$Lo70_anousu?>
    </td>
    <td> 
<?
$o70_anousu = db_getsession('DB_anousu');
db_input('o70_anousu',4,$Io70_anousu,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To70_codrec?>">
       <?=@$Lo70_codrec?>
    </td>
    <td> 
<?
db_input('o70_codrec',6,$Io70_codrec,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To70_codfon?>">
       <?
       db_ancora(@$Lo70_codfon,"js_pesquisao70_codfon(true);",$db_opcao);
       ?>
    </td>
    <td colspan='2'> 
<?
   $clestrutura->funcao_onchange  = "js_pesquisao70_codfon(false);";
   $clestrutura->autocompletar = true;
   $clestrutura->mascara = false;
   $clestrutura->input   = true;
   $clestrutura->size    = 22;
   $clestrutura->db_opcao= $db_opcao;
   $clestrutura->estrutura('o50_estrutreceita');
       
  db_input('o57_descr',40,$Io57_descr,true,'text',3,'');
?>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
<?
if((isset($atualizar) || isset($o50_estrutreceita)) && empty($cadastrado)&& empty($negado)){
  $matriz= split("\.",$o50_estrutreceita);
  $inicia=false;//variavel que indica que o nivel não tem mais filhos
  $tam=(count($matriz)-1);
  $codigos='';
  for($i=$tam; $i>=0; $i--){
    $codigo='';//monta os codigos para a pesquisa
    if($matriz[$i]!="0" || $inicia==true){
      $inicia=true;
      for($x=$i; $x>=0; $x--){
	  $codigo=$matriz[$x].$codigo;
      } 	 
      for($y=strlen($codigo); $y<15; $y++){
	$codigo=$codigo."0";
      }
    }
    if($inicia==true){
      $codigos=$codigo."#".$codigos;
    }  
  }
  $matriz02= split("#",$codigos);
  $tam=count($matriz02);
  $espaco=3;   
  $esp='';
  for($i=0; $i<$tam; $i++){
      if($matriz02[$i]==''){
	continue;
      }
      
      for($s=0; $s<$espaco; $s++){
         $esp=$esp."&nbsp;"; 
      }  
      $result=$clorcfontes->sql_record($clorcfontes->sql_query_file(null,null,'o57_fonte,o57_descr','',"o57_fonte='".$matriz02[$i]."' and o57_anousu = ".db_getsession("DB_anousu")));
      if($clorcfontes->numrows>0){
	db_fieldsmemory($result,0);
        if(empty($prim)){    
  	  echo"  
	    <tr>
	      <td  align='left'><b>Detalhamento:</b></td>
	      <td><small>".db_formatar($o57_fonte,"receita_int")."</small></td>
	      <td><small>$esp $o57_descr</small></td>
	    </tr>
	   "; 
	   $prim="false";
        }else{	
	    echo "
		 <tr>
		  <td>&nbsp;</td>
		  <td><small>".db_formatar($o57_fonte,"receita_int")."</small></td>
		  <td><small>$esp $o57_descr</small></td>
		</tr> 
	    ";
	}    
      }else{
	$nops=true;
        if(empty($prim)){    
  	  echo"  
	    <tr>
	      <td  align='left'><b>Detalhamento:</b></td>
	      <td><small> ".db_formatar($matriz02[$i],"receita_int")."</small></td>
	      <td><small>$esp Não encontrado</small></td>
	    </tr>
	   "; 
	   $prim="false";
	}else{   
	  echo "
	       <tr>
		<td>&nbsp;</td>
		<td><small> ".db_formatar($matriz02[$i],"receita_int")."</small></td>
		<td><small>$esp Não encontrado</small></td>
	      </tr> 
	  ";
	}  
      }	
  }
}  
?>
  <tr>
    <td nowrap title="<?=@$To70_codigo?>">
       <?
       db_ancora(@$Lo70_codigo,"js_pesquisao70_codigo(true);",$db_opcao);
       ?>
    </td>
    <td colspan='2'> 
<?
db_input('o70_codigo',4,$Io70_codigo,true,'text',$db_opcao," onchange='js_pesquisao70_codigo(false);'");
db_input('o15_descr',30,$Io15_descr,true,'text',3,'');
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To70_valor?>">
       <?=@$Lo70_valor?>
    </td>
    <td> 
<?
db_input('o70_valor',15,$Io70_valor,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To70_reclan?>">
       <?=@$Lo70_reclan?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('o70_reclan',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
<? 
   if ($anousu > 2007){
?>
  <tr>
    <td nowrap title="<?=@$To70_concarpeculiar?>"><?
       db_ancora(@$Lo70_concarpeculiar,"js_pesquisao70_concarpeculiar(true);",$db_opcao);
    ?></td>
    <td colspan="2">
    <?
      db_input("o70_concarpeculiar",10,$Io70_concarpeculiar,true,"text",$db_opcao,"onChange='js_pesquisao70_concarpeculiar(false);'");
      db_input("c58_descr",50,0,true,"text",3);
    ?>
    </td>
  </tr>
<?
  } else {
    $o70_concarpeculiar = 0;
    db_input("o70_concarpeculiar",10,0,true,"hidden",3,"");
  }

$o70_instit=db_getsession('DB_instit');
db_input('o70_instit',2,$Io70_instit,true,'hidden',$db_opcao);
?>
   <tr>
     <td colspan='3' align='center'>
     <?
     $disa='';
     if(isset($nops)){
       $disa= " disabled ";
     }  
     ?>
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> <?=$disa?> >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
     </td>
   </tr>
  </table>
  </center>


</form>
<script>
<?
  if(isset($nops)){
?>
    alert('Inclusão não permitida. Pois existe um nivel que não foi encontrado!');
<?
  }
?>
function js_pesquisao70_concarpeculiar(mostra){
  if (mostra==true) {
  
    js_OpenJanelaIframe('top.corpo','db_iframe_concarpeculiar',
                        'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr',
                        'Pesquisa',true);
  }else{
     if(document.form1.o70_concarpeculiar.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_concarpeculiar',
                            'func_concarpeculiar.php?pesquisa_chave='+document.form1.o70_concarpeculiar.value+
                            '&funcao_js=parent.js_mostraconcarpeculiar',
                            'Pesquisa',false);
     }else{
       document.form1.c58_descr.value = ''; 
     }
  }
}
function js_mostraconcarpeculiar(chave,erro){
  document.form1.c58_descr.value = chave; 
  if(erro==true){ 
    document.form1.o70_concarpeculiar.focus(); 
    document.form1.o70_concarpeculiar.value = ''; 
  }
}
function js_mostraconcarpeculiar1(chave1,chave2){
  document.form1.o70_concarpeculiar.value = chave1;
  document.form1.c58_descr.value          = chave2;
  db_iframe_concarpeculiar.hide();
}
function js_pesquisao70_codfon(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes','func_orcfontes.php?funcao_js=parent.js_mostraorcfontes1|o57_fonte|o57_descr','Pesquisa',true);
  }else{
    fonte=document.form1.o50_estrutreceita.value;
    while(fonte.search(/\./)!='-1'){
	 fonte=fonte.replace(/\./,''); 
    }  
    if(fonte!=''){
      js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes','func_orcfontes.php?pesquisa_chave='+fonte+'&funcao_js=parent.js_mostraorcfontes','Pesquisa',false);
    }else{
      document.form1.o50_estrutreceita.value='';
    }  
  }
}
function js_atualiza(){
      obj=document.createElement('input');
      obj.setAttribute('name','atualizar');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value',"atualizar");
      document.form1.appendChild(obj);
      document.form1.submit();
}
function js_mostraorcfontes(chave,erro){
  document.form1.o57_descr.value = chave; 
  if(erro==true){ 
    document.form1.o50_estrutreceita.focus(); 
    //document.form1.o50_estrutreceita.value = ''; 
  js_atualiza();
  }else{
     js_atualiza();
  }
}
function js_mostraorcfontes1(chave1,chave2){
  db_iframe_orcfontes.hide();
  document.form1.o50_estrutreceita.value = chave1;
  document.form1.o57_descr.value = chave2;
  js_mascara02_o50_estrutreceita(chave1);
  js_atualiza();
}

function js_pesquisao70_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o70_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
  }
}
function js_mostraorctiporec(chave,erro){
  document.form1.o15_descr.value = chave; 
  if(erro==true){ 
    document.form1.o70_codigo.focus(); 
    document.form1.o70_codigo.value = ''; 
  }
}
function js_mostraorctiporec1(chave1,chave2){
  document.form1.o70_codigo.value = chave1;
  document.form1.o15_descr.value = chave2;
  db_iframe_orctiporec.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_preenchepesquisa|o70_anousu|o70_codrec','Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1){
  db_iframe_orcreceita.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
<?
if(isset($chavepesquisa)){
  
  echo "js_mascara02_o50_estrutreceita(document.form1.o50_estrutreceita.value);\n";
}  
if(isset($cadastrado)){
  echo "
   document.form1.o50_estrutreceita.value='';\n
   document.form1.o57_descr.value='';\n
   alert('Fonte já cadastrada!');\n
  ";
}  
if(isset($negado)){
  echo "
   document.form1.o50_estrutreceita.value='';
   document.form1.o57_descr.value='';
   alert('Selecione o último nível!');\n
   js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes','func_orcfontes.php?chave_o57_fonte=$codigo&funcao_js=parent.js_mostraorcfontes1|o57_fonte|o57_descr','Pesquisa',true);
  ";
}  
?>  
</script>