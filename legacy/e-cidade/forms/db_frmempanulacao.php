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


//MODULO: empenho
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_vlrliq");
$clrotulo->label("e60_vlranu");
$clrotulo->label("e60_vlremp");
$clrotulo->label("e60_vlrpag");
$clrotulo->label("o56_elemento");
$clrotulo->label("e60_coddot");
$clorcdotacao->rotulo->label();

//rotina que calcula os valores disponiveis
if (isset ($e60_vlremp)) {
	$vlrdis = ($e60_vlremp - ($e60_vlrliq + $e60_vlranu));
	$vlranu = $vlrdis;
	if ($vlrdis == 0 || $vlrdis == '') {
		$db_opcao = 33;
	}
	$vlrdis = number_format($vlrdis, 2, ".", "");
}
//fim

if (empty ($e60_numemp)) {
	$db_opcao_inf = 3;
} else {
	$db_opcao_inf = $db_opcao;
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
<table border='0' cellspacing='0' cellpadding='0'>
  <tr>
  <td colspan='2' align='center'>
<?


db_input('dados', 6, 0, true, 'hidden', 3)
?>

<table border="0" cellspacing='0' cellpadding='0'>
  <tr>
    <td nowrap title="<?=@$Te60_numemp?>">
       <?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho','".@$e60_numemp."')",$db_opcao_inf)?>
    </td>
    <td>
<?

 db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3)
?>
     </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
    <?=db_ancora($Lz01_nome,"js_JanelaAutomatica('cgm','".@$e60_numcgm."')",$db_opcao_inf)?>
    </td>
    <td>
<?

 db_input('e60_numcgm', 10, $Ie60_numcgm, true, 'text', 3)
?>
       <?

 db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '')
?>
    </td>
  </tr>
  <tr>
      <td nowrap title="<?=@$Te60_coddot?>">
         <?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao','".@$e60_coddot."','".db_getsession("DB_anousu")."')",$db_opcao_inf)?>
      </td>
      <td>
          <? db_input('e60_coddot',8,$Ie60_coddot,true,'text',3); ?>
      </td>
  </tr>
     <?
 /* busca dados da dotação  */
if ((isset ($e60_coddot))) {
	$instit = db_getsession("DB_instit");
	$clorcdotacao->sql_record($clorcdotacao->sql_query_file("", "", "*", "", "o58_coddot=$e60_coddot and o58_instit=$instit"));
	if ($clorcdotacao->numrows > 0) {
		$result = db_dotacaosaldo(8, 2, 2, "true", "o58_coddot=$e60_coddot", $e60_anousu);
		db_fieldsmemory($result, 0);
		$atual = number_format($atual, 2, ",", ".");
		$reservado = number_format($reservado, 2, ",", ".");
		$atudo = number_format($atual_menos_reservado, 2, ",", ".");
	} else {
		$nops = " Dotação $e60_coddot  não encontrada ";
	}

}
?>
          <tr>
             <td nowrap title="<?=@$To58_orgao ?>"><?=@$Lo58_orgao ?> </td>
	     <td nowrap>
	       <? db_input('o58_orgao',8,"$Io58_orgao",true,'text',3,"");  ?>
	       <? db_input('o40_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_unidade ?>"><?=@$Lo58_unidade ?> </td>
	     <td nowrap>
	       <? db_input('o58_unidade',8,"",true,'text',3,"");  ?>
	       <? db_input('o41_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_funcao ?>"><?=@$Lo58_funcao ?> </td>
	     <td nowrap>
	       <? db_input('o58_funcao',8,"",true,'text',3,"");  ?>
	       <? db_input('o52_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
           <tr>
             <td nowrap title="<?=@$To58_subfuncao ?>" ><?=@$Lo58_subfuncao ?> </td>
	     <td nowrap>
	       <? db_input('o58_subfuncao',8,"",true,'text',3,"");  ?>
	       <? db_input('o53_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_programa ?>"    ><?=@$Lo58_programa ?> </td>
	     <td nowrap>
	       <? db_input('o58_programa',8,"",true,'text',3,"");  ?>
	       <? db_input('o54_descr',40,"",true,'text',3,"");  ?>
             </td>
	  </tr>
           <tr>
             <td nowrap title="<?=@$To58_projativ ?>"><?=@$Lo58_projativ ?> </td>
	     <td nowrap>
	       <? db_input('o58_projativ',8,"",true,'text',3,"");  ?>
	       <? db_input('o55_descr',40,"",true,'text',3,"");  ?>
	     </td>
           </tr>
           <tr>
             <td nowrap title="<?=@$To56_elemento ?>" ><?=@$Lo56_elemento ?> </td>
	     <td nowrap>
	       <? db_input('o58_elemento',8,"",true,'text',3,"");  ?>
	       <? db_input('o56_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
          <tr>
             <td nowrap title="<?=@$To58_codigo ?>" ><?=@$Lo58_codigo ?> </td>
	     <td nowrap>
	       <? db_input('o58_codigo',8,"",true,'text',3,"");  ?>
	       <? db_input('o15_descr',40,"",true,'text',3,"");  ?>
	     </td>
	  </tr>
 <tr>
    <td>
       <b>Tipo:</b>
    </td>
    <td colspan='3' nowrap>
     <?
      $oEmpAnuladoTipo  = new cl_empanuladotipo;
      $rsEmpAnuladoTipo = $oEmpAnuladoTipo->sql_record(
                                                      $oEmpAnuladoTipo->sql_query(null,"*",
                                                                                  "e38_sequencial")
                                                              );
       $e94_empanuladotipo = 2;
       db_selectrecord("e94_empanuladotipo",$rsEmpAnuladoTipo,true,1);
      ?>
     </td>
</tr>
 <tr>
   <td align='center' colspan='2'>
<input name="confirmarn" type="submit" value="Confirmar e não imprimir" onclick="return js_verificar('botao','naoimprimir');"  <?=($db_botao==false?"disabled":"")?>>
<input name="confirmar" type="submit" id="op1" value="Confirmar e imprimir" onclick="return js_verificar('botao','imprimir');"  <?=($db_botao==false?"disabled":"")?>>
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
   </td>
 </tr>

    </table>
     </td>
     <td valign='bottom'>
    <table cellspacing='0' cellpadding='0'>
<?

if (isset ($e60_anousu) && $e60_anousu < db_getsession("DB_anousu")) {
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


db_input('e60_vlremp', 15, $Ie60_vlremp, true, 'text', 3, '')
?>
	  </td>
	</tr>
	<tr class='bordas'>
	  <td class='bordas' nowrap title="<?=@$Te60_vlranu?>">
	     <?=@$Le60_vlranu?>
	  </td>
	  <td class='bordas'>
      <?

 db_input('e60_vlranu', 15, $Ie60_vlranu, true, 'text', 3, '')
?>
	  </td>
	</tr>
	<tr>
	  <td class='bordas' nowrap title="<?=@$Te60_vlrliq?>">
	     <?=@$Le60_vlrliq?>
	  </td>
	  <td class='bordas'>
      <?

 db_input('e60_vlrliq', 15, $Ie60_vlrliq, true, 'text', 3, '')
?>
	  </td>
	</tr>
	<tr>
	  <td class='bordas' nowrap title="<?=@$Te60_vlrpag?>">
	     <?=@$Le60_vlrpag?>
	  </td>
	  <td class='bordas'>
      <?

 db_input('e60_vlrpag', 15, $Ie60_vlrpag, true, 'text', 3, '')
?>
	  </td>
	</tr>

	<tr class='bordas'>
	  <td class='bordas02' colspan='2' align='center' nowrap title="<?=@$Te60_vlremp?>">
	    <b><small>SALDO</small></b>
	  </td>
	</tr>

  <tr class='bordas'>
    <td class='bordas' nowrap title="Valor que deseja anular">
       <b>Valor disponível:</b>
    </td>
    <td class='bordas'>
<?

 db_input('vlrdis', 15, 0, true, 'text', 3);
?>
    </td>
  </tr>
  <tr class='bordas'>
    <td class='bordas' nowrap title="Valor que deseja anular">
       <b>Valor à anular:</b>
    </td>
    <td class='bordas'>
<?


db_input('vlranu', 15, 4, true, 'text', $db_opcao, "onchange='js_verificar(\"campo\");'");
?>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
      <b>Motivo</b>
      </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
  <?


db_textarea('c72_complem', 3, 40, 0, true, 'text', $db_opcao);
?>
    </td>
  </tr>
  </table>
     </td>
     </tr>
     <tr>
  <td colspan='4' align='center'>
    <br>
    <iframe name="elementos" id="elementos" src="forms/db_frmempanulacao_elementos.php?db_opcao=<?=$db_opcao?>&e60_numemp=<?=@$e60_numemp?>" width="720" height="150" marginwidth="0" marginheight="0" frameborder="0">
    </iframe>
  </td>
  <td valign='top'>
    <br>
  </td>
 </tr>
 </table>
  </center>
</form>
<script>
function js_naoimprimir(){
  obj=document.createElement('input');
  obj.setAttribute('name','naoimprimir');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','true');
  document.form1.appendChild(obj);
}
function js_imprimir(){
  obj=document.createElement('input');
  obj.setAttribute('name','imprimir');
  obj.setAttribute('type','hidden');
  obj.setAttribute('value','true');
  document.form1.appendChild(obj);
}
<?

if (isset ($e60_numemp)) {
	if (isset ($e60_vlremp)) {
		if ($vlrdis == 0 || $vlrdis == '') {
			echo "document.form1.confirmar.disabled=true;";
			if (empty ($confirmar)) {
				echo "alert(\"Não existe valor para anular!\");\n";
			}
		}
	}
?>
     function js_verificar(tipo,acao){
        erro=false;
	vlranu= new Number(document.form1.vlranu.value);
	if(vlranu > '<?=$vlrdis?>'){
	 erro= true;
	}
	if(isNaN(vlranu)){
	  erro=true;
	}

  if (tipo == "botao"){
       if (erro == false){
<?
	$result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
  if ($clempempenho->numrows > 0){
       db_fieldsmemory($result, 0);
?>
       var e60_anousu   = <?=$e60_anousu?>;
       var anousu       = <?=db_getsession("DB_anousu")?>;
       var e60_vlremp   = <?=$e60_vlremp?>;
       var e60_vlranu   = <?=$e60_vlranu?>;
<?
       $autori          = 0;
			 $result_itemsol  = $clempautitem->sql_record($clempautitem->sql_query_anuaut(null,null," distinct e54_autori as autori",null,"e54_anulad is null and e61_numemp = $e60_numemp and pc11_codigo is not null"));
       $numrows_itemsol = $clempautitem->numrows;
       if ($numrows_itemsol > 0){
            db_fieldsmemory($result_itemsol,0);
       }
?>
       var autori       = <?=$autori?>;

       if (e60_anousu >= anousu                       &&
            (e60_vlremp - (vlranu + e60_vlranu) == 0) &&
           autori > 0){
            var op = document.createElement("input");
            op.setAttribute("type","hidden");
            op.setAttribute("name","reservar");
<?
						$rsPcparam = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession('DB_instit'),"pc30_gerareserva"));
						if ($clpcparam->numrows > 0){
							db_fieldsmemory($rsPcparam,0);
              $lGeraReserva = $pc30_gerareserva;
						}else{
							$lGeraReserva = "t";
						}

						if( $lGeraReserva == "f"  ){
?>
							op.setAttribute("value","false");
<?
						}else{
?>
							if (confirm("Recriar reservas de solicitacao de compras?")){
								 op.setAttribute("value","true");
							} else {
								 op.setAttribute("value","false");
							}
<?
						}
?>
            document.form1.appendChild(op);
       }
<?
					}
?>
       }
  }

        if(acao=='imprimir'){
          js_imprimir();
	}else{
          js_naoimprimir();
	}

	if(erro==false){
	  val = vlranu.toFixed(2);
	  document.form1.vlranu.value=val
	  if(tipo=='campo'){
	    elementos.js_coloca(val);
	  }
	  return true;
	}else{
          document.form1.vlranu.focus();
          document.form1.vlranu.value="<?=$vlrdis?>";
	  elementos.js_coloca("<?=$vlrdis?>");
	  return false;
	}

      }
<?

}
?>


function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp&empenhofolha=f','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_empempenho.hide();
  <?


echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?e60_numemp='+chave";
?>
}
</script>