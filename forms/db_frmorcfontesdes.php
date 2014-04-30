<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clorcfontesdes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o57_fonte");
$clrotulo->label("o57_descr");
$clrotulo->label("o50_estrutreceita");
?>
<script>
<?       
if(isset($atualizar) || isset($chavepesquisa)){
  $fonte_full=str_replace('.','',$o50_estrutreceita); 
  /*rotina que traz somente o código*/
  $matriz= split("\.",$o50_estrutreceita);
  $inicia=false;//variavel que indica que o nivel não tem mais filhos
  $tam=(count($matriz)-1);
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
  /*fim*/
    $nivel=db_le_mae($fonte_full,true);
    switch($nivel){
      case 1:
            $dbwhere = " substr(o57_fonte,1,1)='".substr($fonte_full,0,1)."'  ";
            break;
      case 2:
            $dbwhere = " substr(o57_fonte,1,2)='".substr($fonte_full,0,2)."'  ";
            break;
      case 3:
            $dbwhere = " substr(o57_fonte,1,3)='".substr($fonte_full,0,3)."'  ";
            break;
      case 4:
            $dbwhere = " substr(o57_fonte,1,4)='".substr($fonte_full,0,4)."'  ";
            break;
      case 5:
            $dbwhere = " substr(o57_fonte,1,5)='".substr($fonte_full,0,5)."'  ";
            break;
      case 6:
            $dbwhere = " substr(o57_fonte,1,7)='".substr($fonte_full,0,7)."'  ";
            break;
      case 7:
            $dbwhere = " substr(o57_fonte,1,9)='".substr($fonte_full,0,9)."' ";
            break;
      case 8:
            $dbwhere = " substr(o57_fonte,1,11)='".substr($fonte_full,0,11)."'  ";
            break;
      case 9:
            $dbwhere = " substr(o57_fonte,1,13)='".substr($fonte_full,0,13)."' ";
            break;
      case 10:
            $dbwhere = " substr(o57_fonte,1,15)='".substr($fonte_full,0,15)."' ";
            break;
 
    }

  $dbwhere .= " and o57_anousu = ".db_getsession("DB_anousu");
  
  $taman=strlen($codigo);
  if(isset($chavepesquisa)){
     $result=$clorcfontes->sql_record($clorcfontes->sql_query(null,null,"o57_fonte as fonte,o57_codfon",'o57_fonte',"$dbwhere"));
  }else{
     $result=$clorcfontes->sql_record($clorcfontes->sql_query(null,null,"o57_fonte as fonte,o57_codfon",'o57_fonte',"$dbwhere and o57_fonte<>'$fonte_full'"));
  }  

  $numrows=$clorcfontes->numrows;

  if(isset($atualizar)){	 
    if ($numrows < 2) {
	   $testa='nops';
    } else { 	
	/*rotina que verifica se a fonte é válida*/  
	$nivelpai=db_le_mae_rec($fonte_full,true);
	$testa='ok';
	for($r=0; $r<$numrows; $r++){
	  db_fieldsmemory($result,$r);
	  $nivel=db_le_mae_rec($fonte,true);

 	  $nivel_estrut=db_le_mae_rec($fonte,false);
            
	    
	  if($nivel>$nivelpai+1){
  	    if($nivel_estrut!=$fonte_full){
	      $testa='nops';
	      break;
	    }  
	  }
	  
	}
	/*fim*/   
	if($testa=='ok'){
	  /*rotina que verifica se a fonte ainda não foi incluida, será executada somente quando o campo o50_estrutreceita trocar o valor*/  
	  $fonts='';  
	  $vir='\\n';
	  for($r=0; $r<$numrows; $r++){
	    db_fieldsmemory($result,$r);
	    $result05=$clorcfontesdes->sql_record($clorcfontesdes->sql_query_file(null,null,"o60_codfon as xc",''," o60_anousu = ".db_getsession("DB_anousu")." and o60_codfon=$o57_codfon "));	
	    if($clorcfontesdes->numrows>0){      
	      $fonts.=$vir.db_formatar($fonte,"receita");
	    }
	  }  	
	  /*fim*/   
	}  
    }	
  }else if(isset($chavepesquisa)){
    $testa='ok';
    if($numrows<1){
      $testa='nops';
    }
  }	
}   
if(isset($chavepesquisa)|| (isset($testa) && $testa=='ok')){   
  $testa_botao=true;
?>
function js_verifica(){
  obj=filhos.document.getElementsByTagName('INPUT');
  dados='';
  virg='';
  total=0;
  for(i=0; i<obj.length; i++){
    nome=obj[i].name;
   //alert(nome.substr(0,10));
   //alert(nome.substr(0,8));
     if(nome.substr(0,10)=="o60_codfon"){
       codigo=nome.substr(11);
       valor = new Number(filhos.document.getElementById('o60_perc_'+codigo).value);
       dados+=virg+codigo+'-'+valor;
       virg='#';
       total += valor;
       total  = js_round(total,2);
     }
  }
  <?
     if($db_opcao!=3){
  ?>
  if(total<100){
    alert('O total de percentual não atingiu 100%. Verifique!');
    return false;
  }
  <?
     }   
  ?>
  obj=document.createElement('input');
  obj.setAttribute('name','dados');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',dados);
  document.form1.appendChild(obj);
  return true;
}
<?
}
?>
function js_soma(){
  obj=filhos.document.getElementsByTagName('INPUT');
  valor=0;
  for(i=0; i<obj.length; i++){
    nome=obj[i].name;
     if(nome.substr(0,10)=="o60_codfon"){
       codigo=nome.substr(11);
       valor+= new Number(filhos.document.getElementById('o60_perc_'+codigo).value);
     }
  }
  total= new Number(valor); 
  document.form1.total.value=total.toFixed(2);
  restante= new Number(100-total);
  document.form1.restante.value=restante.toFixed(2);
}
function js_totaliza(camp){
  obj=filhos.document.getElementsByTagName('INPUT');
  valor=0;
  for(i=0; i<obj.length; i++){
    nome=obj[i].name;
     if(nome.substr(0,10)=="o60_codfon"){
       codigo=nome.substr(11);
       valor+= new Number(filhos.document.getElementById('o60_perc_'+codigo).value);
     }
  }
  valor=valor.toFixed(2);
  total= new Number(valor); 
  if(total>100){
    alert('O total de percentual ultrapassou 100%. Verifique!');
    codigo=camp.name.substr(9);
    filhos.document.getElementById('o60_perc_'+codigo).value='';
    filhos.document.getElementById('o60_perc_'+codigo).focus();
    return true;
  }
  document.form1.total.value=total.toFixed(2);
  restante= new Number(100-total);
  document.form1.restante.value=restante.toFixed(2);
  return true;
}
</script>
<form name="form1" method="post" action="">
<center>
<table width="70%" border="0">
  <tr>
    <td nowrap title="<?=@$To60_anousu?>">
       <?=@$Lo60_anousu?>
    </td>
    <td> 
        <? $o60_anousu = db_getsession('DB_anousu');
           db_input('o60_anousu',4,$Io60_anousu,true,'text',3) ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To60_codfon?>">
       <?
       if($db_opcao==1){
	 $db_opcao02=1;
       }else{
	 $db_opcao02=3;
       }
       db_ancora(@$Lo50_estrutreceita,"js_pesquisao60_codfon(true);",$db_opcao02);
       ?>
    </td>
    <td colspan='2'> 
   <?
     $clestrutura->funcao_onchange  = "js_pesquisao60_codfon(false);";
     $clestrutura->autocompletar = true;
     $clestrutura->mascara = false;
     $clestrutura->input   = true;
     $clestrutura->size    = 24;
     $clestrutura->db_opcao= $db_opcao02;
     $clestrutura->estrutura('o50_estrutreceita');
     
     db_input('o57_descr',70,$Io57_descr,true,'text',3,'');
    ?>
    </td>
  </tr>
  <tr><td>&nbsp;</td></tr>
  <tr>
    <td colspan='2' align='center' >
      <fieldset><Legend align='center'><b>Filhos</b></Legend>
<?       
   if(isset($testa) && $testa=='ok'){   
     if(isset($chavepesquisa)){
?>
     <iframe id="filhos"  frameborder="0" name="filhos" src="orc1_orcfontesdes004.php?db_opcao=<?=$db_opcao?>&o50_estrutreceita=<?=$o50_estrutreceita?>&chavepesquisa=<?=$chavepesquisa?>"   scrolling="auto"  width='100%'>
<?
     }else{ 
?>     

    <iframe id="filhos"  frameborder="0" name="filhos" src="orc1_orcfontesdes004.php?db_opcao=<?=$db_opcao?>&o50_estrutreceita=<?=$o50_estrutreceita?>"   scrolling="auto"  width='100%'>
     
<?
     }
?>     
     </iframe>
     <br>
     <b>Total da soma(%)</b>
<?   
     if(empty($total)){
       $total='0.00';
     }
     db_input('total',5,$Io60_perc,true,'text',3);
   
?>
     <b>Restante(%)</b>
<?   
     if(empty($total)){
       $total='0.00';
     }
     db_input('restante',5,$Io60_perc,true,'text',3);
   }
?>
     </fieldset>
   </td>
  </tr> 
  <tr>
     <td colspan='2' align='center'>
<input <?=(isset($testa_botao)?"onclick=\"return js_verifica();\"":" ")?> name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="<?=(isset($testa_botao)?"submit":"button")?>" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
     </td>
  </tr>
  
  </table>
  </center>
</form>
<script>
function js_atualiza(){
      obj=document.createElement('input');
      obj.setAttribute('name','atualizar');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value',"atualizar");
      document.form1.appendChild(obj);
      document.form1.submit();
}
function js_pesquisao60_codfon(mostra){
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

function js_mostraorcfontes(chave,erro){
  document.form1.o57_descr.value = chave; 
  if(erro==true){ 
    document.form1.o50_estrutreceita.focus(); 
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
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_orcfontesdes','func_orcfontesdes.php?funcao_js=parent.js_preenchepesquisa|o60_codfon|o60_anousu','Pesquisa',true);
}
function js_preenchepesquisa(chave1,chave){
  db_iframe_orcfontesdes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1";
  }
  ?>
}
</script>
<?
if(isset($fonts) && $fonts!=''){
  echo "
       <script>
          alert('As seguintes fontes já foram incluídas:$fonts ');
          location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."';
       </script>
  ";
}else  if(isset($testa) && $testa=='nops'){
    db_msgbox("A fonte selecionada está incorreta!");
    echo "<script>js_OpenJanelaIframe('top.corpo','db_iframe_orcfontes','func_orcfontes.php?funcao_js=parent.js_mostraorcfontes1|o57_fonte|o57_descr','Pesquisa',true);</script>";
}    
?>