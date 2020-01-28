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
$clempautoriza->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("nome");
$clrotulo->label("e44_tipo");
$clrotulo->label("pc50_descr");
$clrotulo->label("e57_codhist");
$clrotulo->label("c58_descr");
if($db_opcao==1){
  $ac="emp1_empempenho004.php";
}else if($db_opcao==2 || $db_opcao==22){
 // $ac="emp1_empautoriza005.php";
}else if($db_opcao==3 || $db_opcao==33){
//  $ac="emp1_empautoriza006.php";
}
$db_disab=true;
if(isset($chavepesquisa)&&$db_opcao==1){
$result_param = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit")));
if ($clpcparam->numrows>0){
	db_fieldsmemory($result_param,0);
	if ($pc30_contrandsol=='t'){
	 $sql =" select pc81_codprocitem
             from ( select solandam.pc43_solicitem,
                           max(pc43_ordem) as pc43_ordem
                      from solandam
                  group by solandam.pc43_solicitem
                  ) as x
                  inner join solandam             on solandam.pc43_solicitem             = x.pc43_solicitem
                                                 and solandam.pc43_ordem                 = x.pc43_ordem
			            inner join solandpadrao         on solandam.pc43_solicitem             = solandpadrao.pc47_solicitem
                                                 and solandam.pc43_ordem                 = solandpadrao.pc47_ordem          
                  inner join pcprocitem           on x.pc43_solicitem                    = pc81_solicitem
                  inner join empautitempcprocitem on empautitempcprocitem.e73_pcprocitem = pcprocitem.pc81_codprocitem    
                  inner join empautitem           on empautitem.e55_autori               = empautitempcprocitem.e73_autori
                                                 and empautitem.e55_sequen               = empautitempcprocitem.e73_sequen
	                inner join solicitemprot        on pc49_solicitem                      = x.pc43_solicitem
                 where e55_autori= $chavepesquisa and 
		       solandpadrao.pc47_pctipoandam <> 7 and 
		       solandam.pc43_depto <> ".db_getsession("DB_coddepto");

         $result_andam = pg_exec($sql);
         if (pg_numrows($result_andam)>0){
      	      $sqltran = "select distinct x.p62_codtran,                   
      				x.pc11_numero,
				x.pc11_codigo,
                            x.p62_dttran, 
                            x.p62_hora, 
                			x.descrdepto, 
							x.login
			from ( select distinct p62_codtran, 
                          p62_dttran, 
                          p63_codproc,                          
                          descrdepto, 
                          p62_hora, 
                          login,
                          pc11_numero,
	 		  pc11_codigo,
                          pc81_codproc,
                          e55_autori,
			  e54_anulad 
		        from proctransferproc
                         
                        inner join solicitemprot on pc49_protprocesso = proctransferproc.p63_codproc
                        inner join solicitem on pc49_solicitem = pc11_codigo
                        inner join proctransfer on p63_codtran = p62_codtran
						inner join db_depart on coddepto = p62_coddepto
						inner join db_usuarios on id_usuario = p62_id_usuario
						inner join pcprocitem on pcprocitem.pc81_solicitem = solicitem.pc11_codigo
						inner join empautitem on empautitem.e55_sequen = pcprocitem.pc81_codprocitem
						inner join empautoriza on empautoriza.e54_autori= empautitem.e55_autori  
             			where  p62_coddeptorec = ".db_getsession("DB_coddepto")."
                 ) as x
				 left join proctransand 	on p64_codtran = x.p62_codtran
				 left join arqproc 	on p68_codproc = x.p63_codproc
			where p64_codtran is null and 
			      p68_codproc is null and 
			      x.e55_autori = $chavepesquisa";

			$result_tran=pg_exec($sqltran);
			if(pg_numrows($result_tran)==0){
      			    $db_disab=false;
			}
      } //else {
        //  $db_disab = false;
      //}
	}
}
}
?>
<form name="form1" method="post" action="<?=$ac?>" >
<input type=hidden name=dadosRet value="">
<input type=hidden name=chavepesquisa value="<?=$chavepesquisa?>" >

<?
db_input('lanc_emp',6,"",true,'hidden',3)
?>
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Te54_autori?>">
       <?=@$Le54_autori?>
    </td>
    <td> 
<?
db_input('e54_autori',6,$Ie54_autori,true,'text',3)
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_numcgm?>">
       <?=$Le54_numcgm?>
    </td>
    <td> 
<?
db_input('e54_numcgm',10,$Ie54_numcgm,true,'text',3);
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_codcom?>">
       <?=@$Le54_codcom?>
    </td>
    <td> 
<?
if(isset($e54_codcom) && $e54_codcom==''){
  $pc50_descr='';
}
  
  /*
     a opção mantem a seleção escolhida pelo usuario ao trocar o tipo de compra

     
  */  
  if (isset($tipocompra) && $tipocompra!=''){
      $e54_codcom=$tipocompra;
  }    

  $result=$clpctipocompra->sql_record($clpctipocompra->sql_query_file(null,"pc50_codcom as e54_codcom,pc50_descr"));
  db_selectrecord("e54_codcom",$result,true,$db_opcao,"","","","","js_reload(this.value)");

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_tipol?>">
       <?=@$Le54_tipol?>
    </td>
    <td> 
<?
if(isset($tipocompra) || isset($e54_codcom)){
   if(isset($e54_codcom) && empty($tipocompra)){
     $tipocompra=$e54_codcom;
   }
   $result=$clcflicita->sql_record($clcflicita->sql_query_file(null,"l03_tipo,l03_descr",'',"l03_codcom=$tipocompra"));
   if($clcflicita->numrows>0){
     db_selectrecord("e54_tipol",$result,true,1,"","","");
     $dop=$db_opcao;
   }else{
     $e54_tipol='';
     $e54_numerl='';
      db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
      $dop='3';
   }  
}else{   
      $dop='3';
     $e54_tipol='';
     $e54_numerl='';
  db_input('e54_tipol',8,$Ie54_tipol,true,'text',3);
}  
?>
       <?=@$Le54_numerl?>
<?
db_input('e54_numerl',8,$Ie54_numerl,true,'text',$dop);
?>
    </td>
  </tr>  
  <tr>
    <td nowrap title="<?=@$Te54_codtipo?>">
       <?=$Le54_codtipo?>
    </td>
    <td> 
<?
  $result=$clemptipo->sql_record($clemptipo->sql_query_file(null,"e41_codtipo,e41_descr"));
  db_selectrecord("e54_codtipo",$result,true,$db_opcao);

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te57_codhist?>">
       <?=$Le57_codhist?>
    </td>
    <td> 
<?

  $result=$clemphist->sql_record($clemphist->sql_query_file(null,"e40_codhist,e40_descr"));
  db_selectrecord("e57_codhist",$result,true,1,"","","","Nenhum");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te44_tipo?>">
       <?=$Le44_tipo?>
    </td>
    <td> 
<?
  $result=$clempprestatip->sql_record($clempprestatip->sql_query_file(null,"e44_tipo as tipo,e44_descr,e44_obriga","e44_obriga "));
  $numrows =  $clempprestatip->numrows;
  $arr = array();
  for($i=0; $i<$numrows; $i++){
     db_fieldsmemory($result,$i);  
     if($e44_obriga == 0 && empty($e44_tipo)){
       $e44_tipo = $tipo;
     }  
     $arr[$tipo] = $e44_descr;
  }
  db_select("e44_tipo",$arr,true,1);

?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_destin?>">
       <?=@$Le54_destin?>
    </td>
    <td> 
<?
db_input('e54_destin',90,$Ie54_destin,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Te54_resumo?>" valign ='top'>
       <?=@$Le54_resumo?>
    </td>
    <td> 
<?
db_textarea('e54_resumo',6,90,$Ie54_resumo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
<?
    $anousu = db_getsession("DB_anousu");

    if ($anousu > 2007){
?>
  <tr>
    <td nowrap title="<?=@$Te54_concarpeculiar?>"><?
       db_ancora(@$Le54_concarpeculiar,"js_pesquisae54_concarpeculiar(true);",$db_opcao);
    ?></td>
    <td>
    <?
      if (isset($concarpeculiar) && trim(@$concarpeculiar) != ""){
        $e54_concarpeculiar = $concarpeculiar;
        $c58_descr          = $descr_concarpeculiar;
      }
      db_input("e54_concarpeculiar",10,$Ie54_concarpeculiar,true,"text",$db_opcao,"onChange='js_pesquisae54_concarpeculiar(false);'");
      db_input("c58_descr",50,0,true,"text",3);
    ?>
    </td>
  </tr>
<?
    } else {
      $e54_concarpeculiar = 0;
      $c58_descr          = "";
      db_input("e54_concarpeculiar",10,0,true,"hidden",3,"");
    }
?>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
       type="submit" 
       id="db_opcao" 
       value="<?=($db_opcao==1||$db_opcao==33?"Empenhar e imprimir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       "<?=($db_botao==false?"disabled":($db_disab==false?"disabled":""))?>" >

<?if($db_opcao==1){?>
   <input name="op" 
          type="button" 
	  value="Empenhar e não imprimir" 
	  "<?=($db_disab==false?"disabled":"")?>" onclick="js_naoimprimir();" >
<?}?>

<input name="lanc" type="button" id="lanc" value="Lançar autorizações" onclick="parent.location.href='emp1_empautoriza001.php';">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar autorizações" onclick="js_pesquisa();" >
  <table>
  <tr>
    <td colspan='2' align='center' nowrap>
    <fieldset>
        <b>
	  <input name='opc' type='radio' value='0' id='id_0' checked> <label for="id_0">Não liquidar</label>
	  <input name='opc' type='radio' value='1' id='id_1'> <label for="id_1">Liquidar</label>
	  <input name='opc' type='radio' value='2' id='id_2'><label for="id_2" >Ordem de Pagamento</label>
	  <!--
	  <input name='opc' type='radio' value='3' id='id_3'><label for="id_3" >Liquidar + Nota de Liquidação</label>
	  -->
	</b>
    </fieldset>
    </td>
  </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisae54_concarpeculiar(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_empempenho','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr&filtro=despesa','Pesquisa',true,'0','1');
  }else{
     if(document.form1.e54_concarpeculiar.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_empempenho','db_iframe_concarpeculiar','func_concarpeculiar.php?pesquisa_chave='+document.form1.e54_concarpeculiar.value+'&funcao_js=parent.js_mostraconcarpeculiar&filtro=despesa','Pesquisa',false);
     }else{
       document.form1.c58_descr.value = ''; 
     }
  }
}
function js_mostraconcarpeculiar(chave,erro){
  document.form1.c58_descr.value = chave; 
  if(erro==true){ 
    document.form1.e54_concarpeculiar.focus(); 
    document.form1.e54_concarpeculiar.value = ''; 
  }
}
function js_mostraconcarpeculiar1(chave1,chave2){
  document.form1.e54_concarpeculiar.value = chave1;
  document.form1.c58_descr.value          = chave2;
  db_iframe_concarpeculiar.hide();
}
function js_naoimprimir(){
  obj=document.createElement('input');
  obj.setAttribute('name','naoimprimir');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','true');
  document.form1.appendChild(obj);
  document.form1.incluir.click();
}
function js_reload(valor){
  obj=document.createElement('input');
  obj.setAttribute('name','tipocompra');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value',valor);
  document.form1.appendChild(obj);
  document.form1.submit();
}
function js_pesquisae54_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_empempenho','db_iframe_cgm','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,0);
  }else{
     if(document.form1.e54_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_empempenho','db_iframe_cgm','func_nome.php?pesquisa_chave='+document.form1.e54_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.e54_numcgm.focus(); 
    document.form1.e54_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.e54_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}
function js_pesquisae54_login(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_empempenho','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.e54_login.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_empempenho','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.e54_login.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.e54_login.focus(); 
    document.form1.e54_login.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.e54_login.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_empempenho','db_iframe_orcreservaaut','func_orcreservaaut.php?funcao_js=parent.js_preenchepesquisa|e54_autori','Pesquisa',true,0);
}
function js_preenchepesquisa(chave){
    db_iframe_orcreservaaut.hide();
  <?
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
</script>