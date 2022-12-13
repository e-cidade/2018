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

//MODULO: contabilidade
$clsaltes->rotulo->label();
$clorcreceita->rotulo->label();
$clrotulocampo = new rotulocampo;
$clrotulocampo->label("c70_valor");
$clrotulocampo->label("c70_data");
$clrotulocampo->label("c72_complem");
$clrotulocampo->label("c08_concarpeculiar");
$clrotulocampo->label("c58_descr");
?>
<form name="form1" method="post" action=""  onsubmit="return js_confirmaarrecadacao();">
<fieldset>
  <legend>
    <b>Arrecadação de Receita</b>
  </legend>
<table border="0" align="center" width="100%">
  <tr>
    <td nowrap title="<?=@$Tc70_data?>">
       <?=@$Lc70_data?>
    </td>
    <td> 
<?

if (!isset($c70_data_dia)) {
	
  $c70_data_dia = date("d",db_getsession("DB_datausu"));
  $c70_data_mes = date("m",db_getsession("DB_datausu"));
  $c70_data_ano = date("Y",db_getsession("DB_datausu"));
}

if (isset($HTTP_SESSION_VARS["cdia"])) {
	
  $c70_data_dia = db_getsession("cdia");
  $c70_data_mes = db_getsession("cmes");
  $c70_data_ano = db_getsession("DB_anousu");
}

db_inputdata('c70_data', @$c70_data_dia, @$c70_data_mes, @$c70_data_ano, true, 'text', $db_opcao, "");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk13_conta?>">
       <?
       db_ancora(@$Lk13_conta,"js_pesquisaconta(true);",2);
       ?>
    </td>
    <td nowrap >
    <?
    db_input('k13_conta',10,@$Ik13_conta,true,'text',$db_opcao,"onchange='js_pesquisaconta(false);'");
    db_input('k13_descr',40,@$Ik13_descr,true,'text',3);
    ?>
    </td>
  </tr> 
  <tr>
    <td nowrap title="<?=@$To70_codrec?>">
       <?
       db_ancora(@$Lo70_codrec,"js_pesquisacodrec(true);",2);
       ?>
    </td>
    <td nowrap >
    <?
    db_input('o70_codrec',10,@$Io70_codrec,true,'text',$db_opcao,"onchange='js_pesquisacodrec();'");
    db_input('o57_descr',40,@$Io57_descr,true,'text',3);
    db_input('cadastrar',1,1,true,'hidden',1);
    ?>
    </td> 
  </tr>
  <tr>
    <td nowrap="nowrap">
      <?
        db_ancora(@$Lc08_concarpeculiar, "js_pesquisac08_concarpeculiar(true);", $db_opcao);
      ?>
    </td>
    <td nowrap="nowrap">
      <?
        db_input('c08_concarpeculiar', 10, $Ic08_concarpeculiar, true, 'text', $db_opcao, "onchange='js_pesquisac08_concarpeculiar(false);'");       
        db_input('c58_descr', 40, $Ic58_descr, true, 'text', 3);
      ?>  
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc70_valor?>">
       <?
       echo $Lc70_valor;
       ?>
    </td>
    <td nowrap >
    <?
    if(isset($o70_codrec) && ($o70_codrec+0) != 0){
      $vlronly = 1;
    }else{
      $vlronly = 3;
    }
    db_input('c70_valor', 10, @$Ic70_valor, true, 'text', $vlronly," onchange='document.form1.submit();'");
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap="nowrap" colspan="2">
      <fieldset>
        <legend>
          <b>Texto Complementar</b>
        </legend>
        <table border="0" width="100%">
				  <tr>
				    <td nowrap='nowrap'>
					    <?
						    if (isset($o70_codrec) && ($o70_codrec+0) != 0) {
						      $vlronly = 1;
						    } else {
						      $vlronly = 3;
						    }
						    
						    db_textarea('c72_complem', 5, 60, @$Ic72_complem, true, 'text', $vlronly);
					    ?>
				    </td>
				  </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
   <td align="center" colspan="3">
       <?
       if(isset($o70_codrec) && (($o70_codrec+0)!=0 && ($c70_valor+0)!=0 && ($k13_conta+0)!=0)){
         echo '<input name="lancar" type="submit" value="Arrecada Receita">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp'; 
         echo '<input name="estornar" type="submit" value="Estorna Arrecadação Receita">'; 
       }else{
         echo '<input name="confirma" type="button" value="Confirma Receita" >';
       }
       ?>
   </td>
   </tr>
  <tr>
    <td colspan="3"><hr></td>
  </tr>
  </table>
<table border="0" width="100%" class='box-table-detalhamento'>
  <?

if( isset($o70_codrec) && isset($cadastrar)){
  
  $sql = "select fc_estruturalreceita(".db_getsession("DB_anousu").",$o70_codrec) as o50_estrutreceita";
  $result = pg_query($sql);
  db_fieldsmemory($result,0);

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
  $ultimo = "ss";
  $tem_desdobramento = false;
  for($i=0; $i<$tam; $i++){
      if($matriz02[$i]==''){
	      continue;
      }
      for($s=0; $s<$espaco; $s++){
         $esp=$esp."&nbsp;"; 
      }  
      $result=$clorcfontes->sql_record($clorcfontes->sql_query_file(null,null,'o57_codfon,o57_fonte,o57_descr','',"o57_fonte='".$matriz02[$i]."' and o57_anousu = ".db_getsession("DB_anousu")));
      if($clorcfontes->numrows>0){
	db_fieldsmemory($result,0);
	

        $result=$clorcfontesdes->sql_record($clorcfontesdes->sql_query_file(db_getsession("DB_anousu"),$o57_codfon));
	if($clorcfontesdes->numrows>0){
	  $tem_desdobramento = true;
	  break;
	}
	
	if($ultimo!=$o57_fonte){
          $ultimo = $o57_fonte;
        if(empty($prim)){    
  	  echo"  
	    <tr>
	      <td  align='left'><b>Detalhamento:</b></td>
	      <td><small>".db_formatar($o57_fonte,"receita_int")."</small></td>
	      <td><small>$esp $o57_descr</small></td>
	      <td>&nbsp;</td>
	    </tr>
	   "; 
	   $prim="false";
        }else{
	    echo "
		 <tr>
		  <td>&nbsp;</td>
		  <td><small>".db_formatar($o57_fonte,"receita_int")."</small></td>
		  <td><small>$esp $o57_descr</small></td>
		  <td>&nbsp;</td>
		</tr> 
	    ";
	}    
	}
      }else{
	$nops=true;
	if($ultimo!=$o57_fonte){
          $ultimo = $o57_fonte;
        if(empty($prim)){    
  	  echo"  
	    <tr>
	      <td  align='left'><b>Detalhamento:</b></td>
	      <td><small> ".db_formatar($matriz02[$i],"receita_int")."</small></td>
	      <td><small>$esp Não encontrado</small></td>
	      <td>&nbsp;</td>
	    </tr>
	   "; 
	   $prim="false";
	}else{   
	  echo "
	       <tr>
					<td>&nbsp;</td>
					<td><small> ".db_formatar($matriz02[$i],"receita_int")."</small></td>
					<td><small>$esp Não encontrado</small></td>
					<td>&nbsp;</td>
	      </tr> 
	  ";
	}
	}
      }	
      
  }

  if($tem_desdobramento==true && $c70_valor > 0){

    $clorcfontesdes = new cl_orcfontesdes;
    $clorcfontesdes->rotulo->label();
    $result = $clorcfontes->sql_record($clorcfontes->sql_query($o57_codfon,db_getsession("DB_anousu")));
    
    db_fieldsmemory($result,0);

    $mae  = db_le_mae_rec_sin($o57_fonte,false);

    $sql = "select o70_codrec,o57_fonte,o57_codfon,o57_descr,o60_perc
            from orcfontes 
                 inner join orcfontesdes on o57_codfon = o60_codfon and o60_anousu = ".db_getsession("DB_anousu")." 
		 inner join orcreceita on o57_codfon = o70_codfon and o70_anousu = ".db_getsession("DB_anousu")."
	    where o57_anousu = ".db_getsession("DB_anousu")." and o57_fonte like '$mae%'
     order by o60_perc desc";

    $result = pg_query($sql);

    global $vlrperc;
    $vlrtot = $c70_valor;
    $vlrsoma = 0;

    echo "<tr>";
    echo "<td colspan='3'>";
    echo "<table border='0' width='100%'>";
      $totsoma          = 0;
      $flag_refazer     = false;
      $perc_valido      = 0;
      $perc_processados = 0;
      for($i=0;$i<pg_numrows($result);$i++){
      	db_fieldsmemory($result,$i);

 	      $vlrperc  = round($vlrtot * ($o60_perc/100),2);
        $totsoma += round($vlrtot * ($o60_perc/100),2);

        if ($o60_perc > 0){
//          echo $vlrperc."<br>";
          $perc_processados++;
        }

        if ($o60_perc > 0 && $vlrperc > 0){
          $perc_valido++;
        }

        if ($i + 1 == pg_numrows($result)){
//          echo $totsoma." => Proc ".$perc_valido." => ".$perc_processados;
          if ($perc_valido != $perc_processados){
            $flag_refazer = true;
          }

          if ($perc_valido == $perc_processados){
          	if (round($totsoma,2) != $vlrtot){
              $flag_refazer = true;
            }
          }
        } 
      }
      
      for($i=0;$i<pg_numrows($result);$i++){
      	db_fieldsmemory($result,$i);
	    
        if ($flag_refazer == false){
          if ($o60_perc == 0){
            $vlrperc = 0;
          } else {
            $vlrperc = round($vlrtot * ($o60_perc/100),2);
          }
        } else if ($flag_refazer == true){
          if ($i == 0){
            $vlrperc = $vlrtot;
          } else {
            $vlrperc = 0;
          }
        }

	echo "<tr>";
	echo "<td width=\"20%\">".$o57_fonte;
	$codrec = "db_rec_".$o70_codrec;
	global $$codrec;
	$$codrec = $vlrperc;
	db_input("db_rec_$o70_codrec",15,4,true,'hidden',3);
        echo "</td>";
	echo "<td width=\"50%\" align=\"left\">$o57_descr</td>";
	echo "<td width=\"10%\" align=\"center\">$o60_perc%</td>";
	echo "<td width=\"20%\">";
	$codrec = "vlrperc_".$o57_fonte;
	global $$codrec;
	$$codrec = db_formatar($vlrperc,'f');
	db_input("vlrperc_$o57_fonte",15,4,true,'text',3);
	echo "</td>";
	echo "</tr>";
      }
      echo "</table>";
      echo "<td>";
	echo "<tr>";
  }
}  
?>
  </table>
</fieldset>
</form>
<script>
function js_confirmaarrecadacao(){
  valor = new Number(document.form1.c70_valor.value);
  if(valor==0){
    alert('Valor deverá ser preenchido');
    document.form1.c70_valor.focus();
    return false;
  }
  return true;
}

function js_pesquisacodrec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?funcao_js=parent.js_mostracodrec1|o70_codrec|o57_descr','Pesquisa',true);
  }else{
    if(document.form1.o70_codrec.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_orcreceita','func_orcreceita.php?pesquisa_chave='+document.form1.o70_codrec.value+'&funcao_js=parent.js_mostracodrec','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}

function js_mostracodrec(chave,erro){
  document.form1.o57_descr.value = chave;
  if(erro==true){
     document.form1.o70_codrec.focus();
     document.form1.o70_codrec.value = '';
  }else{
    document.form1.submit();
  }
}

function js_mostracodrec1(chave1,chave2){
  document.form1.o70_codrec.value = chave1;
  document.form1.o57_descr.value = chave2;
  db_iframe_orcreceita.hide();
  document.form1.submit();
}

function js_pesquisaconta(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr','Pesquisa',true);
  }else{
    if(document.form1.k13_conta.value != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe_saltes','func_saltes.php?pesquisa_chave='+document.form1.k13_conta.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
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

function js_pesquisac08_concarpeculiar(mostra) {

 if (mostra == true) {
 
    var sUrl = 'func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr';
    js_OpenJanelaIframe('', 'db_iframe_concarpeculiar', sUrl, 'Pesquisa', true);
  } else {
    
    if ($('c08_concarpeculiar').value != '') {
    
      var sUrl  = 'func_concarpeculiar.php?pesquisa_chave=';
          sUrl += $('c08_concarpeculiar').value+'&funcao_js=parent.js_mostraconcarpeculiar';
      js_OpenJanelaIframe('', 'db_iframe_concarpeculiar', sUrl, 'Pesquisa', false);
    } else {
      $('c58_descr').value = '';
    }
  }
}

function js_mostraconcarpeculiar(chave1, erro) {

  if (erro == true) {
  
    $('c08_concarpeculiar').value = '';
    $('c58_descr').value          = chave1;
    $('c08_concarpeculiar').focus;
  } else {
    $('c58_descr').value          = chave1;
  }   
}

function js_mostraconcarpeculiar1(chave1, chave2) {  

  $('c08_concarpeculiar').value = chave1;
  $('c58_descr').value          = chave2;
  
  db_iframe_concarpeculiar.hide();
}
</script>