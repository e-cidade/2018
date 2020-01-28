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

//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("k13_conta");
$clrotulo->label("k12_cheque");
$clrotulo->label("k13_descr");
$clrotulo->label("e60_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlranu");
$clrotulo->label("e60_vlremp");
$clrotulo->label("e60_vlrpag");
$clrotulo->label("o56_elemento");
$clrotulo->label("e60_coddot");
$clrotulo->label("e50_codord");
$clorcdotacao->rotulo->label();
$clpagordemele->rotulo->label();

if(isset($pag_emp)){
  if(isset($e60_vlremp)){
    $vlrdis=($e60_vlrliq-$e60_vlrpag);  
    $vlrpag=$vlrdis;  
    if($vlrdis==0||$vlrdis==''){
      $db_opcao=33;
    }  
  }  
}else if(isset($pag_ord)){
       $result02 = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord,null,"sum(e53_valor) as total_valor, sum(e53_vlrpag) as total_vlrpag, sum(e53_vlranu) as total_vlranu  ")); 
       $numrows = $clpagordemele->numrows;
       if($numrows>0){
 	   db_fieldsmemory($result02,0);  
       }else{
	 die('nao tem elementos');
       }
       $vlrdis = ($total_valor - $total_vlranu - $total_vlrpag);
       
}  
?>
<style>
<?$cor="#999999"?>
.bordas02{
         border: 1px solid #cccccc;
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
<form name="form1" method="post" action="">
<center>
<table border='0' cellspacing='0' cellpadding='0' width='100%'>
  <tr>
  <td colspan='2' align='left' valign='top'>
<?
//rotina que cria o campo que indicará se o pagamento esta sendo feito por empenho ou por ordem de pagamento
      if(empty($modo)){
	if(isset($pag_ord)){
	  $modo = "pag_ord";
	}else{
	  $modo = "pag_emp";
	}
      }
      db_input($modo,6,0,true,'hidden',3);
//fim

db_input('dados',6,0,true,'hidden',3);
?>
<table border="0" cellspacing='0' cellpadding='1'>
  <tr>
    <td nowrap title="<?=@$Te60_numemp?>"  colspan='3' align='center'>
    <?if(isset($pag_ord)){?>
       <?=db_ancora($Le50_codord,"js_JanelaAutomatica('pagordem','".@$e50_codord."')",$db_opcao)?>        
         	<?
   		db_input('e50_codord',6,$Ie50_codord,true,'text',3)
		?>
    <?}?>	    
  
       <?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho','".@$e60_numemp."')",$db_opcao)?>        
<?
db_input('e60_numemp',13,$Ie60_numemp,true,'text',3)
?>

    </td>
    
  </tr>

  
  <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
    <?=db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm','".@$e60_numcgm."')",$db_opcao)?>        
    </td>
    <td> 
<?
db_input('e60_numcgm',10,$Ie60_numcgm,true,'text',3)
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
      <td nowrap title="<?=@$Te60_coddot?>">
         <?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao','".@$e60_coddot."','".db_getsession("DB_anousu")."')",$db_opcao)?>        
	 
      </td>
      <td>
          <? db_input('e60_coddot',8,$Ie60_coddot,true,'text',3); ?>
      </td>
  </tr>
     <?    /* busca dados da dotação  */
     if((isset($e60_coddot))){ 
          $instit=db_getsession("DB_instit");
          $clorcdotacao->sql_record($clorcdotacao->sql_query_file("","","*","","o58_coddot=$e60_coddot and o58_instit=$instit")); 	 
          if($clorcdotacao->numrows >0){
             $result= db_dotacaosaldo(8,2,2,"true","o58_coddot=$e60_coddot" ,$e60_anousu) ;
             db_fieldsmemory($result,0);
	     $atual=number_format($atual,2,",",".");
	     $reservado=number_format($reservado,2,",",".");
             $atudo=number_format($atual_menos_reservado,2,",",".");
	   }else{
	     $nops=" Dotação $e60_coddot  não encontrada ";
	   }
	   
      }	
     ?>
          <tr>
             <td nowrap title="<?=@$To58_orgao ?>"><?=@$Lo58_orgao ?> </td>
	     <td nowrap >
	       <? db_input('o58_orgao',8,"$Io58_orgao",true,'text',3,"");  ?>
	       <? db_input('o40_descr',40,"",true,'text',3,"");  ?> 
	     </td>     
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_unidade ?>"><?=@$Lo58_unidade ?> </td>
	     <td nowrap >
	       <? db_input('o58_unidade',8,"",true,'text',3,"");  ?>
	       <? db_input('o41_descr',40,"",true,'text',3,"");  ?> 
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_funcao ?>"><?=@$Lo58_funcao ?> </td>
	     <td nowrap >
	       <? db_input('o58_funcao',8,"",true,'text',3,"");  ?>
	       <? db_input('o52_descr',40,"",true,'text',3,"");  ?> 
	     </td>
	  </tr>
           <tr>
             <td nowrap title="<?=@$To58_subfuncao ?>" ><?=@$Lo58_subfuncao ?> </td>
	     <td nowrap >
	       <? db_input('o58_subfuncao',8,"",true,'text',3,"");  ?>
	       <? db_input('o53_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_programa ?>"    ><?=@$Lo58_programa ?> </td>
	     <td nowrap >
	       <? db_input('o58_programa',8,"",true,'text',3,"");  ?>
	       <? db_input('o54_descr',40,"",true,'text',3,"");  ?>      
             </td>
	  </tr>
           <tr>
             <td nowrap title="<?=@$To58_projativ ?>"><?=@$Lo58_projativ ?> </td>
	     <td nowrap >
	       <? db_input('o58_projativ',8,"",true,'text',3,"");  ?> 
	       <? db_input('o55_descr',40,"",true,'text',3,"");  ?>  
	     </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$To56_elemento ?>" ><?=@$Lo56_elemento ?> </td>
	     <td nowrap > 
	       <? db_input('o58_elemento',8,"",true,'text',3,"");  ?> 
	       <? db_input('o56_descr',40,"",true,'text',3,"");  ?>     
	     </td> 
	     	     
	  </tr>
	  <tr>
	     <td colspan=2> &nbsp; </td>	  
	  </tr>	  
      <tr>
          <td nowrap title="<?=@$To58_codigo ?>" ><?=@$Lo58_codigo ?> </td>
	      <td nowrap >
	       <? db_input('o58_codigo',8,"",true,'text',3,"");  ?>
	       <? db_input('o15_descr',40,"",true,'text',3,"");  ?>
	      </td>
	  </tr>
	  <tr>
	    <td nowrap title="<?=@$Tk13_conta?>">
           <span id='contanaobloqueada'>
	       <?
	       db_ancora(@$Lk13_conta,"js_pesquisak13_conta(true);",$db_opcao);
	       ?>
         </span>
         <span id='contabloqueada' style='display: none'>
         <?=$Lk13_conta?>
         </span>
	    </td>
	    <td nowrap > 
	<?
	db_input('k13_conta',8,$Ik13_conta,true,'text',$db_opcao," onchange='js_pesquisak13_conta(false);'")
	?>
	       <?
	db_input('k13_descr',40,$Ik13_descr,true,'text',3);
	       ?>
	    </td>
	  </tr>

    </table>	  
     </td>
     <td valign='bottom' nowrap >
       <table cellspacing='0' cellpadding='0' class='bordas'>	  
<?    
  if(isset($e60_anousu) && $e60_anousu <  db_getsession("DB_anousu")){
?>
	<tr class='bordas'>
	  <td  colspan='2' align='center'>
	    <b style='color:red'>RESTO À PAGAR</b>
	  </td>
	</tr>
<?
  }
?>	
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>EMPENHO</small></b>
	  </td>
	</tr>
	<tr class='bordas'>
	  <td class='bordas' nowrap title="<?=@$Te60_vlremp?>">
	     <?=@$Le60_vlremp?>
	  </td>
	  <td class='bordas'> 
      <?
	db_input('e60_vlremp',15,$Ie60_vlremp,true,'text',3,'')
      ?>
	  </td>
	</tr>
	<tr class='bordas'>
	  <td class='bordas' nowrap title="<?=@$Te60_vlranu?>">
	     <?=@$Le60_vlranu?>
	  </td>
	  <td class='bordas'> 
      <?
	db_input('e60_vlranu',15,$Ie60_vlranu,true,'text',3,'')
      ?>
	  </td>
	</tr>
	<tr>
	  <td class='bordas' nowrap title="<?=@$Te60_vlrliq?>">
	     <?=@$Le60_vlrliq?>
	  </td>
	  <td class='bordas'> 
      <?
	db_input('e60_vlrliq',15,$Ie60_vlrliq,true,'text',3,'')
      ?>
	  </td>
	</tr>
	<tr>
	  <td class='bordas' nowrap title="<?=@$Te60_vlrpag?>">
	     <?=@$Le60_vlrpag?>
	  </td>
	  <td class='bordas'> 
      <?
	db_input('e60_vlrpag',15,$Ie60_vlrpag,true,'text',3,'')
      ?>
	  </td>
	</tr>
<?
 if(isset($e60_numemp)){
   if(isset($e50_codord) && $e50_codord!=''){
     $result  = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e60_numemp=$e60_numemp and e50_codord=$e50_codord ")); 
   }else{  
     $result  = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e60_numemp=$e60_numemp")); 
   }      
     db_fieldsmemory($result,0,true);
   if($tot_valor!='0'){
?>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>ORDEM</small></b>
	  </td>
	</tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te60_vlranu?>">
	       <?=@$Le53_valor?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('tot_valor',15,$Ie60_vlranu,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te53_vlrpag?>">
	       <?=@$Le53_vlrpag?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('tot_vlrpag',15,$Ie53_vlrpag,true,'text',3,'')
	?>
	    </td>
	  </tr>
	  <tr>
	    <td class='bordas' nowrap title="<?=@$Te53_vlranu?>">
	       <?=@$Le53_vlranu?>
	    </td>
	    <td class='bordas'> 
	<?
	  db_input('tot_vlranu',15,$Ie53_vlranu,true,'text',3,'')
	?>
	    </td>
	  </tr>

<?
  }
}  
?>
	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>SALDO</small></b>
	  </td>
	</tr>
	<tr>
  
  <tr>
    <td class='bordas' nowrap title="Valor que deseja anular">
       <b>Valor disponível:</b>
    </td>
    <td class='bordas'> 
<?
  $vlrdis = number_format($vlrdis,"2",".","");		       
  db_input('vlrdis',15,0,true,'text',3);
  if($vlrdis ==0){
    $db_botao = false;
  }
?>
    </td>
  </tr>
  <tr>
    <td class='bordas' nowrap title="Valor que deseja pagar">
       <b>Valor à pagar:</b>
    </td>
    <td class='bordas'> 
<?
  db_input('vlrpag',15,4,true,'text',$db_opcao,"onchange='js_verificar(\"campo\");'");
?>
    </td>
  </tr>
  <tr>
    <td class='bordas' nowrap>
<?
    $dbwhere = "e91_codmov is not null and e80_instit = " . db_getsession("DB_instit") . " and e60_numemp = $e60_numemp";

    if(isset($e50_codord) && $e50_codord != ''){
      $dbwhere .=  " and  e82_codord = $e50_codord ";
    }

    $sql    = $clempagemov->sql_query_conf(null,"k13_descr,e81_codmov,z01_nome,e83_conta,e83_descr,e83_sequencia,e81_valor","","$dbwhere");
    $result = $clempagemov->sql_record($sql);
    if($clempagemov->numrows > 0){
       db_ancora(@$Lk12_cheque,"js_cheque(true);",$db_opcao);
     }else{
        echo $Lk12_cheque;
     }  
     ?>
    </td>
    <td class='bordas'> 
<?
  db_input('k12_cheque',15,4,true,'text',$db_opcao);
?>
<input type='hidden' name='e91_codcheque' >
    </td>
  </tr>
  </table>
     </td>
    </tr>
    <tr>
  <td colspan='4' align='left'>
<?
     if(isset($pag_emp)){
?>
       <iframe name="elementos" id="elementos" src="forms/db_frmemppagamento_elementos.php?db_opcao=<?=$db_opcao?>&e60_numemp=<?=@$e60_numemp?>" width="760" height="100" marginwidth="0" marginheight="0" frameborder="0">
<? 
     }else{  
?>    
      <iframe name="elementos" id="elementos" src="forms/db_frmemppagamento_ordem.php?db_opcao=<?=$db_opcao?>&e50_codord=<?=@$e50_codord?>&e60_numemp=<?=@$e60_numemp?>" width="760" height="130" marginwidth="0" marginheight="0" frameborder="0">
<? 
     } 
?>    
    </iframe>
  </td>
 </tr> 
 <tr>
   <td align='center' colspan='3'>
   <br>
<input name="confirmar" type="submit" id="db_opcao" value="Confirmar" onclick="return js_verificar('botao');" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Voltar" onclick="js_volta();" >
   </td>
 </tr>  
 </table>
  </center>
</form>
 <script>
 function js_tranca(campos){
     arr = campos.split("#");
     for(i=0; i<arr.length; i++ ){
       campo = arr[i];
       if (campo =="k13_conta") {
         document.getElementById('contanaobloqueada').style.display = "none";
         document.getElementById('contabloqueada').style.display    = "";
       }
       eval("document.form1."+campo+".readOnly=true;");
       eval("document.form1."+campo+".style.backgroundColor = '#DEB887'");
     }  
 }
 function js_libera(campos){
     arr = campos.split("#");
     for(i=0; i<arr.length; i++ ){
       campo = arr[i];
       if (campo =="k13_conta") {
         document.getElementById('contanaobloqueada').style.display = "none";
         document.getElementById('contabloqueada').style.display    = "";
       }
       eval("document.form1."+campo+".readOnly=false;");
       eval("document.form1."+campo+".style.backgroundColor = 'white'");
     }  
 }


function js_cheque(){
    js_OpenJanelaIframe('top.corpo','db_iframe_cheque','emp1_emppagamento003.php?js_funcao=parent.js_vai|e91_codcheque|e83_conta|e91_cheque|k13_descr|e91_valor&e50_codord=<?=@$e50_codord?>&e60_numemp=<?=@$e60_numemp?>','Pesquisa',true);
}
<?
if(isset($e81_codmov) && $e81_codmov != '' ){
   echo "js_vai('$e81_codmov','$k13_conta','$k12_cheque','$k13_descr','$vlrpag');";
}
?>
function js_vai(codcheque,conta,sequencia,descr,valor){
  obj = document.form1;
  valor =  new Number(valor);
  disponivel = new Number(obj.vlrdis.value);
  
  if(valor>disponivel){
    alert("O valor do cheque é maior do que o disponivel!");
    return false;
  }  
  
  elementos.js_coloca(valor);
  obj.e91_codcheque.value = codcheque;
  obj.k13_conta.value  = conta;
  obj.k13_descr.value  = descr;
  obj.k12_cheque.value = sequencia;
  valor = new Number(valor);
  obj.vlrpag.value     = valor.toFixed(2);
  js_tranca('vlrpag#k12_cheque#k13_conta');
  elementos.js_tranca();
  db_iframe_cheque.hide();
  js_pesquisak13_conta(false);
  document.getElementById('db_opcao').disabled = false;
}


function js_volta(){
  location.href="emp1_emppagamento001.php";  
}

function js_pesquisak13_conta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr&c61_codigo='+document.form1.o58_codigo.value,'Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.k13_conta.value+'&funcao_js=parent.js_mostrasaltes&c61_codigo='+document.form1.o58_codigo.value,'Pesquisa',false);
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.k13_descr.value = chave; 
  if(erro==true){ 
    document.form1.k13_conta.focus(); 
    document.form1.k13_conta.value = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.k13_conta.value = chave1;
  document.form1.k13_descr.value = chave2;
  db_iframe_saltes.hide();
}

<?
if(isset($e60_numemp)){
  if($vlrdis==0||$vlrdis==''){
      echo " document.form1.confirmar.disabled=true;\n";
     if(empty($confirmar)){
         echo "alert(\"Não existe valor liquidado disponível para ser pago!\");\n";
     }  
  }     
?>
      function js_verificar(tipo){

	  if(tipo=='botao'){
	    if(document.form1.vlrpag.value == '' || document.form1.vlrpag.value == 0 ){
	      alert('Informe o valor à ser pago!!');
	      return false;
	    }
	  }  

        erro=false; 
	vlrpag= new Number(document.form1.vlrpag.value);
    	if(tipo=="botao" && document.form1.k13_conta.value==''){
	  alert('Preencha o campo com a conta da tesouraria!');
	  return false;
	}
	if(isNaN(vlrpag)){
	  erro=true;
	}
	if(vlrpag > '<?=$vlrdis?>'){
	 erro= true;
	}
	if(erro==false){
	  val = vlrpag.toFixed(2);
	  document.form1.vlrpag.value=val
	  if(tipo=='campo'){
	    elementos.js_coloca(val);
	  }
	  return true;
	}else{
	   // alert(erro_msg);
          document.form1.vlrpag.focus();
          document.form1.vlrpag.value="<?=$vlrdis?>";
	  elementos.js_coloca("<?=$vlrdis?>");
	  return false;
	}
	
      }
<?
}
?>

function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empempenho.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?e60_numemp='+chave";
  ?>
}



document.form1.e91_codcheque.value ='';
document.form1.k12_cheque.value ='';
</script>