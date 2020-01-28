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

$clrotulo = new rotulocampo;
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e82_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("e87_descrgera");

$dbwhere = " e80_instit = " . db_getsession("DB_instit") . " and e81_codage = $e80_codage";
$dbwhere .= " and e86_codmov is null ";

if(empty($dtin_dia)){
  $dtin_dia = date('d',db_getsession('DB_datausu'));
  $dtin_mes = date('m',db_getsession('DB_datausu'));
  $dtin_ano = date('Y',db_getsession('DB_datausu'));
}	 

if(isset($procura) &&  isset($e82_codord) && $e82_codord != ""){
  $result = $clpagordem->sql_record($clpagordem->sql_query_file($e82_codord,'e50_numemp')); 
  db_fieldsmemory($result,0);
   $dbwhere .= " and e60_numemp = $e50_numemp ";
}
$numrows05 = 0;
if(!isset($mensagem_mostra)){
    $data_valorConta = $dtin_ano."-".$dtin_mes."-".$dtin_dia;
    $result05  = $clempagepag->sql_record("select substr(fc_saltessaldo(e83_conta,'".$data_valorConta."','".$data_valorConta."',null,".db_getsession("DB_instit")."),41,13)::float8 as vervaloratualsaltes, *
                                           from (".
                                          $clempagepag->sql_query_conf(
                                                                       null,
								       null,
								       "
								        distinct e83_codtipo as codtipo, 
									e83_descr,
									e83_sequencia,e83_conta                       
								       ",
								       "
								        e83_descr
								       ",
								       "
								        $dbwhere
								       "
								      ).
				          ") as x"
                                         );
    $numrows05 = $clempagepag->numrows;
    for($r=0; $r<$numrows05; $r++){
      db_fieldsmemory($result05,$r);
      $arr[$codtipo] = $e83_descr;
    }
}
?>
<script>
function js_mascara(evt){
  var evt = (evt) ? evt : (window.event) ? window.event : "";
  
  if( (evt.charCode >46 && evt.charCode <58) || evt.charCode ==0 ){//8:backspace|46:delete|190:. 
    return true;
  }else{
    return false;
  }  
}

function js_pesquisar(form){
  query = 'data=<?=($e80_data_ano."_".$e80_data_mes."_".$e80_data_ano)?>';
  query += "&e80_codage=<?=$e80_codage?>";
  if(form.e82_codord.value != ""){
    query += "&e82_codord="+form.e82_codord.value;
  }
  if(form.e82_codord02.value != ""){
    query += "&e82_codord02="+form.e82_codord02.value;
  }
  if(form.e60_codemp.value != ""){
    codemp = form.e60_codemp.value;
     arr = codemp.split('/');
    if(arr.length==2){
      query += "&e60_codemp="+arr[0]+"&e60_emiss="+arr[1];  
    }else{  
      query += "&e60_codemp="+form.e60_codemp.value;
    }  
  }
  if(form.e60_numemp.value != ""){
    query += "&e60_numemp="+form.e60_numemp.value;
  }
  if(form.z01_numcgm.value != ""){
    query += "&z01_numcgm="+form.z01_numcgm.value;
  }
  if( form.dtin_dia.value != "" && form.dtin_mes.value != "" && form.dtin_ano.value != ""  ){
    query +="&dtin="+ form.dtin_ano.value+"_"+form.dtin_mes.value+"_"+form.dtin_dia.value; 
  }

  if(form.e83_codtipo.value!=0){
    query += "&e83_codtipo="+form.e83_codtipo.value;
  }

  document.form1.cheques.options[0].selected = true;
  js_liberar_botao(true);

  //selecionadas,naum selecionadas ou todas
  ordem.location.href = "emp4_empageconf002_ordem.php?"+query;
}
function js_atualizar(){
	if(ordem.document.form1){
          obj = ordem.document.form1;
	  var coluna='';
	  var sep=''; 
	  for(i=0; i<obj.length; i++){
	    nome = obj[i].name.substr(0,5);  
	    
	    if(nome=="CHECK" && obj[i].checked==true){
	      ord = obj[i].name.substring(6);
	      coluna += sep+obj[i].value;
	      sep= "XX";
	    }
	  } 
	  if(coluna==''){
	    alert("Selecione um movimento!");
	    return false;

	  }
	  document.form1.movs.value = coluna;
	  return true;
        }else{
	  alert("Clique em pesquisar para selecionar um movimento!");
	  return false;
	}	  
	//return coluna ;

}
function js_troca(campo){
  document.form1.e83_sequencia.value= eval('document.form1.e83_sequencia_'+campo.value+'.value');
  document.form1.vervaloratualsaltes.value= eval('document.form1.vervaloratualsaltes_'+campo.value+'.value');
  document.form1.vervaloratualsaltescheque.value= eval('document.form1.vervaloratualsaltescheque_'+campo.value+'.value');
  document.form1.vervaloratualsalteschequeliq.value= eval('document.form1.vervaloratualsalteschequeliq_'+campo.value+'.value');
}

function js_ver(){
  
  
  query = "?e80_codage=<?=$e80_codage?>";
  if(document.form1.e83_codtipo.value!=0){
    query += "&e83_codtipo="+document.form1.e83_codtipo.value;
  }
  js_OpenJanelaIframe('','db_iframe_anula','func_empageconf001.php'+query,'Pesquisa',true);
}
function js_anular(){
  
  
  query = "?e80_codage=<?=$e80_codage?>";
  if(document.form1.e83_codtipo.value!=0){
    query += "&e83_codtipo="+document.form1.e83_codtipo.value;
  }
  js_OpenJanelaIframe('','db_iframe_anula','emp4_empageconfcanc001.php'+query,'Pesquisa',true);
}


</script>
<form name="form1" method="post" action="">
  <?=db_input('codtipo',10,'',true,'hidden',3);?>
  <?=db_input('movs',10,'',true,'hidden',3);?>
  
  <?=db_input('nome_imp',10,'',true,'hidden',3);?>
  <?=db_input('munic_imp',10,'',true,'hidden',3);?>
  <?=db_input('codbco_imp',10,'',true,'hidden',3);?>
  <?=db_input('data_imp',10,'',true,'hidden',3);?>
  <?=db_input('emite_vals',10,'',true,'hidden',3);?>
  <?=db_input('verso_imp',10,'',true,'hidden',3);?>
  <?=db_input('cheque_imp',10,'',true,'hidden',3);?>

  <?=db_input('valor_dos_cheques',50,'',true,'hidden',3);?>
  <table border='0' align='left' cellpadding='0' cellspacing='0'>
      <tr>     
        <td align='left' valign='top' nowrap>
          <table  border='0' cellpadding='0' cellspacing='0' >  
	    <tr>
	      <td nowrap title="<?=@$Te82_codord?>" align='right'>
		 <? db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",$db_opcao);  ?>
	      </td>
	      <td> 
		 <? db_input('e82_codord',10,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord(false);'")  ?>
 	   <? db_ancora("<b>até</b>","js_pesquisae82_codord02(true);",$db_opcao);  ?>
		 <? db_input('e82_codord',8,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord02(false);'","e82_codord02")?>
                <input name="procura" type="submit"  value="Pesquisa tipos">
	      </td>
	    </tr>
	  <tr>
	    <td nowrap title="<?=@$Tz01_numcgm?>" align='right'>
	    <?
	       db_ancora("<b>Nome:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
	     ?>        
	    </td>
	    <td> 
	<?
	db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'")
	?>
	       <?
	db_input('z01_nome',30,$Iz01_nome,true,'text',3,'')
	       ?>
	    </td>
	  </tr>

	    
	    <tr> 
	      <td  align="right" nowrap title="<?=$Te60_numemp?>">
		   <? db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  ?>
	      </td>
	      
	      <td  nowrap> 
	       
		<input name="e60_codemp" title='<?=$Te60_codemp?>' size="10" type='text'  onKeyPress="return js_mascara(event);" >
		       <? db_ancora(@$Le60_numemp,"js_pesquisae60_numemp(true);",$db_opcao);  ?>
		       <? db_input('e60_numemp',10,$Ie60_numemp,true,'text',$db_opcao," onchange='js_pesquisae60_numemp(false);'")  ?>
		    </td>
		  </tr>
	</table>  
      </td>  
      <td align='left'>
        <table width='100%' cellpadding='0' cellspacing='0' border='0' >
	    <tr>
	      <td nowrap title="<?=@$Te80_codage?>" align='right'>
	      <?=$Le80_codage?>
	      </td>	
	      <td>	
		 <? db_input('e80_codage',10,$Ie80_codage,true,'text',3)?>
	      <?=$Le80_data?>
	       <?
		 db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',3);
	       ?>
	      </td>
	    </tr>
	  <tr>
	    <td><b>Data :</b></td>
	    <td>
              <?
	        db_inputdata('dtin',$dtin_dia,$dtin_mes,$dtin_ano,true,'text',1);
	      ?>
        </td>
        </tr>
	      <?
	          $arr='';
		  for($i=0; $i<$numrows05; $i++){
		    db_fieldsmemory($result05,$i);
		    if($i==0){
		      $pritipo=$codtipo;
		    }
		    $arr[$codtipo] = $e83_descr;
 	            $re = 'e83_sequencia_'.$codtipo;
		    $$re = $e83_sequencia;
		    db_input($re,10,'',true,'hidden',1,'');

 	            $valsaldo = 'vervaloratualsaltes_'.$codtipo;
		    $$valsaldo = trim(db_formatar($vervaloratualsaltes,"f"));
		    db_input($valsaldo,10,'',true,'hidden',1,'');
		    
		    $pardata = "";
		    $sqlpar1 = "select k29_chqemitidonaoautent from caiparametro where k29_instit = ".db_getsession("DB_instit");
	        $resultpar1 = pg_query($sqlpar1);
	        $linhaspar1 = pg_num_rows($resultpar1);
	        if($linhaspar1>0){
	          db_fieldsmemory($resultpar1, 0);
	          if(($k29_chqemitidonaoautent !="")||($k29_chqemitidonaoautent == null)){
	            $pardata = " and e86_data >= '".$k29_chqemitidonaoautent."'";
	          }
	        }
		    
		                        
  $sqlll = "select sum(e91_valor)
  from empagepag
       inner join      empagemov     on e81_codmov = e85_codmov
       inner join      empage        on empage.e80_codage = empagemov.e81_codage
       inner join      empageconf    on e86_codmov = e81_codmov
       inner join      empagetipo    on e83_codtipo= e85_codtipo
       inner join      saltes        on k13_conta = e83_conta
       left  join      empageconfche on e91_codmov = e86_codmov
       															and e91_ativo is true
       left  join      corconf       on k12_codmov = e91_codcheque
       left  join      empagemovforma on e97_codmov = e81_codmov
       left outer join empord        on e82_codmov = e85_codmov
       left outer join empempenho    on e81_numemp = e60_numemp
       left outer join cgm           on z01_Numcgm = e60_numcgm
       left outer join pagordem      on e50_codord = e82_codord
       left outer join  empageslip   on e89_codmov = e81_codmov
  where e85_codtipo = $codtipo and e80_instit = " . db_getsession("DB_instit") . " and (e97_codmov is null or e97_codforma = 2)
  and k12_data is null ".$pardata;
  
//  where e85_codtipo = $codtipo and e86_data between '2007-01-01' and '2007-03-07' and (e97_codmov is null or e97_codforma = 2)

                    $resss = pg_exec($sqlll) or die($sqlll);
                    // cheque emitidos
                    $valsaldoc = 'vervaloratualsaltescheque_'.$codtipo;
                    if( pg_numrows($resss) > 0 ){
                      $$valsaldoc = trim(db_formatar(pg_result($resss,0,'sum'),"f"));
                    }else{
                      $$valsaldoc = "0";
                    }
                    db_input($valsaldoc,10,'',true,'hidden',1,'');
                  
                    // saldo - cheque
                    $valsaldol = 'vervaloratualsalteschequeliq_'.$codtipo;
                    if( pg_numrows($resss) > 0 ){
                      $$valsaldol = trim(db_formatar( $vervaloratualsaltes - pg_result($resss,0,'sum'),"f"));
                    }else{
                      $$valsaldol = "0";
                    }
                    db_input($valsaldol,10,'',true,'hidden',1,'');
		    
		    
		  }
	      ?>
        <tr>
        <td valign="top">
	    <?=$Le83_codtipo?>
	    <small>
        </td>
        <td nowrap>
	    <?
	    if($numrows05>0){
              db_select("e83_codtipo",$arr,true,1,"onchange='js_troca(this);'");
              if(empty($atualizar) && empty($prever)){
                 $result  = $clempagetipo->sql_record($clempagetipo->sql_query($pritipo,"e83_sequencia,substr(fc_saltessaldo(e83_conta,'".$data_valorConta."','".$data_valorConta."',null,".db_getsession("DB_instit")."),41,13) as vervaloratualsaltes"));
              }else{
                 $result  = $clempagetipo->sql_record($clempagetipo->sql_query($e83_codtipo,"e83_sequencia,substr(fc_saltessaldo(e83_conta,'".$data_valorConta."','".$data_valorConta."',null,".db_getsession("DB_instit")."),41,13) as vervaloratualsaltes"));
              }
              db_fieldsmemory($result,0);
              $vervaloratualsaltes = trim(db_formatar($vervaloratualsaltes,"f"));
           echo "<br>Sequencia:";
          db_input('e83_sequencia',10,'',true,'text',3,'');
           echo "<br>Tesouraria:";
          db_input('vervaloratualsaltes',15,'',true,'text',3,'');
           echo "Cheques:";
          db_input('vervaloratualsaltescheque',15,'',true,'text',3,'');
        }else{
           echo "<br>Sequencia:";
           db_input('e83_sequencia',10,'',true,'text',3,'');
           echo "<br>Tesouraria:";
          db_input('vervaloratualsaltes',15,'',true,'text',3,'');
           echo "Cheques:";
           db_input('vervaloratualsaltescheque',15,'',true,'text',3,'');
        } 
	    echo "<br>Saldo Atual:";
        db_input("vervaloratualsalteschequeliq",15,'',true,'text',3,'');
	    ?>
	    <script>
	    document.form1.e83_codtipo.onchange();
	    </script>
	    </small>
	    </td>  
	  </tr>  
          <tr>
	    <td><b>Credor: </b></td>
	    <td><?=db_input('z01_nome',52,@$z01_nome,true,'text',1,'','credor')?></td>
	  </tr> 
          <tr>
	    <td><?=@$Le87_descrgera?></td>
            <td class='bordas' align='left'><small><?=db_input('e87_descrgera',20,@$I87_descrgera,true,'hidden',1,'')?></small></td>
	  </tr>  
	</table>
	  
      </td>
    </tr>
	  <tr>
	    <td  align='center' valign='top' colspan='2'>
	    <b>Verso</b>
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	    <b>Imprimir verso</b>
	    <?
        $check = "";
	    if(isset($imprimirverso) || isset($prever)){
	      $check = " checked ";
	    }
	    ?>
	    <input type='checkbox' name='imprimirverso' value='imprimirverso' <?=($check)?>>
	    </td>
	  </tr>  
	  <tr>
	    <td colspan='2' align='center' >
  <?
  if(isset($emiteverso)){
    $verso='';
  }
  db_textarea('verso',5,88,0,true,'text',1);
  ?>
	    </td>
	  </tr>
    <?
      if(isset($numrows05) && $numrows05 == 0){
	$db_botao = false;
	if(!isset($mensagem_mostra)){
          echo "<b><small>Não existe movimento disponivel para emitir cheque.</small></b>";
	}else{
	  echo "<b><small>".$mensagem_mostra."</small></b>";
	}
      }else{
	$db_botao = true;
      }
    ?>
    <tr>
      <td colspan='2' align='center'>
	<input name="atualizar" type="submit" id="pesquisar" value="Processar" onclick='return js_atualizar();' <?=($db_botao==false?"disabled":"")?> >
	<input name="prever" type="submit" value="Visualizar verso"  <?=($db_botao==false?"disabled":"")?> onclick='return js_atualizar();' >
	<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick='js_pesquisar(this.form);' <?=($db_botao==false?"disabled":"")?> >
	<input name="fechar" type="button"  value="Fechar" onclick='parent.document.form1.submit();' >
	    <b>Total: </b>
	     <?=db_input('total',10,'',true,'text',3)?>
            <b>Cheques: </b>
             <?
	          $arr_c = array("1"=>"1","2"=>"2","3"=>"3","4"=>"4","5"=>"5","6"=>"6");
	          db_select("cheques",$arr_c,true,1,"onchange='js_liberar_botao(true);'");
	     ?>
         <input name="valorescheques" type="button" value="Informar valores" onclick='js_informar_valores();' disabled>
	
      </td>
    </tr>
   <tr>
     <td colspan='2' >
    <?
      $sr = '';
      if(isset($prever)){
        $sr = "src='emp4_empageconf002_ordem.php?e80_codage=$e80_codage&movs=$movs&e83_codtipo=$e83_codtipo'";
      } 
    ?>
       <iframe name="ordem" <?=$sr?>   width="730" height="200"  marginwidth="0" marginheight="0" frameborder="0" style="z-Index:50;"  >
       </iframe>
       <iframe name="ch" src='emp4_empageconfcheque.php'  width="1" height="2"  marginwidth="0" marginheight="0" frameborder="0" style="z-Index:50;"  >
       </iframe>
     </td>
   </tr>
  </table>
  </center>
</form>
<script>
document.form1.total.value='0.00';

function js_fechariframe(con){
  document.form1.cheques.options[(con-1)].selected = true;
  top.corpo.db_iframe_informar_valores.hide();
  js_liberar_botao(false);
}

function js_recebeval(con,valores_cheques){
  document.form1.cheques.options[(con-1)].selected = true;
  document.form1.valor_dos_cheques.value = valores_cheques;
  top.corpo.db_iframe_informar_valores.hide();
  js_liberar_botao(false);
}

function js_informar_valores(){
  js_OpenJanelaIframe('top.corpo','db_iframe_informar_valores','func_informarvalores.php?quantidade='+document.form1.cheques.value+'&total='+document.form1.total.value+'&ch='+document.form1.valor_dos_cheques.value,'Pesquisa',true);
}

function js_liberar_botao(limpar){
	if(limpar == true){
    document.form1.valor_dos_cheques.value = "";
	}
  if(document.form1.cheques.value > 1){
  	valor = new Number(document.form1.total.value);
  	if(valor > 0){
  	  document.form1.valorescheques.disabled = false;
  	}
  }else{
  	document.form1.valorescheques.disabled = true;
  }
}

function js_pesquisae60_codemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
  }else{
   // js_OpenJanelaIframe('top.corpo','db_iframe_empempenho02','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}
function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho02.hide();
}


function js_pesquisae60_numemp(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho1|e60_numemp','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?pesquisa_chave='+document.form1.e60_numemp.value+'&funcao_js=parent.js_mostraempempenho','Pesquisa',false);
  }
}

//------------------------------------------------------------
function js_mostraempempenho(chave,erro){
  if(erro==true){ 
    document.form1.e60_numemp.focus(); 
    document.form1.e60_numemp.value = ''; 
  }
}
function js_mostraempempenho1(chave1){
  document.form1.e60_numemp.value = chave1;
  db_iframe_empempenho.hide();
}

//-----------------------------------------------------------
//---ordem 01
function js_pesquisae82_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e82_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord01 != "" && ord02 != ""){
      alert("Selecione uma ordem menor que a segunda!");
      document.form1.e82_codord.focus(); 
      document.form1.e82_codord.value = ''; 
      return false;
    }
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+ord01+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
  }
}
function js_mostrapagordem(chave,erro){
  if(erro==true){ 
    document.form1.e82_codord.focus(); 
    document.form1.e82_codord.value = ''; 
  }
}
function js_mostrapagordem1(chave1,chave2){
  document.form1.e82_codord.value = chave1;
  db_iframe_pagordem.hide();
}
//-----------------------------------------------------------
//---ordem 02
function js_pesquisae82_codord02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e82_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
      alert("Selecione uma ordem maior que a primeira");
      document.form1.e82_codord02.focus(); 
      document.form1.e82_codord02.value = ''; 
      return false;
    }
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+ord02+'&funcao_js=parent.js_mostrapagordem02','Pesquisa',false);
  }
}
function js_mostrapagordem02(chave,erro){
  if(erro==true){ 
    document.form1.e82_codord02.focus(); 
    document.form1.e82_codord02.value = ''; 
  }
}
function js_mostrapagordem102(chave1,chave2){
  document.form1.e82_codord02.value = chave1;
  db_iframe_pagordem.hide();
}

//---------------------------------------------------
function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
//------------------------------------------------------------
<?if( empty($atualizar) ){?>
//rotina que irá retornar a data para emitir o cheque com a do ultimo acesso
function js_verif(){
  if(parent.document.form1.dtp_dia.value != ''){
    document.form1.dtin_dia.value = parent.document.form1.dtp_dia.value;
    document.form1.dtin_mes.value = parent.document.form1.dtp_mes.value;
    document.form1.dtin_ano.value = parent.document.form1.dtp_ano.value;
  }
}
js_verif();
//--------------------------------
<?}?>

//quando o usuario procurar os tipo eh clicado automaticamente em pesquisar
<?if(isset($procura)){?>
   js_pesquisar(document.form1);
<?}?>
</script>