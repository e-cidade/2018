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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_gerfcom_classe.php");
$clgerfcom = new cl_gerfcom;
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
$clrotulo->label('DBtxt27');
$clrotulo->label('DBtxt28');
$clrotulo->label('rh55_estrut');
$clrotulo->label('rh55_descr');
$clrotulo->label('r44_selec');
$clrotulo->label('r44_descr');
db_postmemory($HTTP_POST_VARS);
include("dbforms/db_classesgenericas.php");
$geraform = new cl_formulario_rel_pes;

$geraform->lo1nome = "DBtxt27";                  // NOME DO CAMPO DA LOTAÇÃO INICIAL
$geraform->lo2nome = "DBtxt28";                  // NOME DO CAMPO DA LOTAÇÃO FINAL
$geraform->usalota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
$geraform->intlota = true;                      // PERMITIR SELEÇÃO DE LOTAÇÕES
$geraform->manomes = false;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function js_emite(){
  qry = "";
  if(document.form1.r48_semest){
    qry = "&semest="+document.form1.r48_semest.value;
  }
  qry += "&reg="+document.form1.regime.value;
  qry += "&sel="+document.form1.r44_selec.value;
  qry += "&ordem="+document.form1.ordem.value;
  qry += "&vinc="+document.form1.vinculo.value;
  qry += "&folha="+document.form1.folha.value;
  qry += "&tipo="+document.form1.tipo.value;
  qry += "&ano="+document.form1.DBtxt23.value;
  qry += "&mes="+document.form1.DBtxt25.value;
  qry += "&lotaini="+document.form1.DBtxt27.value;
  qry += "&lotafin="+document.form1.DBtxt28.value;
//alert(qry);
  jan = window.open('pes2_resumoporvinculo002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

  <table  align="center">
    <form name="form1" method="post" action="" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <tr >
        <td align="right" nowrap title="Digite o Ano / Mes de compet?ncia" >
        <strong>Ano / Mes:</strong>
        </td>
        <td>
          <?
	  if(!isset($DBtxt23) || (isset($DBtxt23) && ($DBtxt23 == "" || $DBtxt23 == 0))){
	    $DBtxt23 = db_anofolha();
	  }
	  $anosqlcom = $DBtxt23;
            db_input('DBtxt23',4,$IDBtxt23,true,'text',2,'onchange="document.form1.submit();"');
          ?>
	  &nbsp;/&nbsp;
          <?
	  if(!isset($DBtxt25) || (isset($DBtxt25) && ($DBtxt25 == "" || $DBtxt25 == 0))){
	    $DBtxt25 = db_mesfolha();
	  }
	  $messqlcom = $DBtxt25;
            db_input('DBtxt25',2,$IDBtxt25,true,'text',2,'onchange="document.form1.submit();"');
          ?>
        </td>
      </tr>
      <tr> 
        <td align="right" nowrap title="Seleção:" >
        <?
	db_ancora("<b>Seleção:</b>","js_pesquisasel(true)",1);
	?>
        </td>
        <td>
          <?
          db_input('r44_selec',4,$Ir44_selec,true,'text',2,'onchange="js_pesquisasel(false)"');
          db_input('r44_descr',40,$Ir44_selec,true,'text',3,'');
          ?>
	</td>
      </tr>
      <?
      if(!isset($tipo) || (isset($tipo) && $tipo != "T")){
        $geraform->gera_form();
	?>
      <?}else{?>
      <tr> 
        <td align="right" nowrap title="<?=@$Trh55_estrut?>" >
        <?
	db_ancora("<b>Local inicial:</b>","js_pesquisaDBtxt27(true)",1);
	?>
        </td>
        <td>
          <?
	  $DBtxt27 = "";
	  $DBtxt27_descr = "";
          db_input('rh55_estrut',4,$Irh55_estrut,true,'text',2,'onchange="js_pesquisaDBtxt27(false)"',"DBtxt27");
          db_input('rh55_descr',40,$Irh55_estrut,true,'text',3,'',"DBtxt27_descr");
          ?>
	</td>
      </tr>
      <tr> 
        <td align="right" nowrap title="<?=@$Trh55_estrut?>" >
        <?
	db_ancora("<b>Local final:</b>","js_pesquisaDBtxt28(true)",1);
	?>
        </td>
        <td>
          <?
	  $DBtxt28 = "";
	  $DBtxt28_descr = "";
          db_input('rh55_estrut',4,$Irh55_estrut,true,'text',2,'onchange="js_pesquisaDBtxt28(false)"',"DBtxt28");
          db_input('rh55_descr',40,$Irh55_estrut,true,'text',3,'',"DBtxt28_descr");
          ?>
	</td>
      </tr>
      <?}?>
      <tr>
        <td align="right"><strong>Tipo de Resumo:</strong>
        </td>
        <td>
          <?
          $arr_tipo = array("G"=>"Geral", "L"=>"Lotação", "R"=>"Recurso", "O"=>"Órgão", "T"=>"Locais de trabalho");
          db_select('tipo',$arr_tipo,true,4,"onchange='document.form1.submit();'");
          ?>
        </td>
      </tr>
      <tr>
        <td align="right"><strong>Tipo de Folha :</strong>
        </td>
        <td>
          <?
          $arr_folha = array("r14"=>"Salário", "r48"=>"Complementar", "r20"=>"Rescisão", "r35"=>"13o. Salário", "r22"=>"Adiantamento");
          db_select('folha',$arr_folha,true,4,"onchange='document.form1.submit();'");
          ?>
       </td>
     </tr>
      <tr>
        <td align="right"><strong>Regime:</strong>
        </td>
        <td>
          <?
      $result_regimes = pg_exec("select rh52_regime, rh52_descr from rhcadregime");
      if(pg_num_rows($result_regimes) > 0){
	$arr_regimes[0] = "Todos";
        for($i=0; $i<pg_num_rows($result_regimes); $i++){
          $regime_for = pg_result($result_regimes, $i, "rh52_regime");
          $descrr_for = pg_result($result_regimes, $i, "rh52_descr");
          $arr_regimes[$regime_for] = $regime_for." - ".$descrr_for;
        }
        db_select('regime',$arr_regimes,true,4,"");
      }
          ?>
       </td>
     </tr>
     <?
     if(isset($folha) && $folha == "r48"){
       $result_semest = $clgerfcom->sql_record($clgerfcom->sql_query_file($anosqlcom,$messqlcom,null,null,"distinct r48_semest"));
       if($clgerfcom->numrows > 0){
	 echo "
	  <tr>
	    <td align='left' title='Nro. Complementar:'><strong>Nro. Complementar:</strong></td>
            <td>
	      <select name='r48_semest'>
		<option value = '0'>Todos
	      ";
	      for($i=0; $i<$clgerfcom->numrows; $i++){
		db_fieldsmemory($result_semest, $i);
		echo "<option value = '$r48_semest'>$r48_semest";
	      }
	 echo "
	    </td>
	  </tr>
	      ";
       }else{
         echo "
               <tr>
                 <td colspan='2' align='center'>
                   <font color='red'>Sem complementar para este período.</font>
                 </td>
               </tr>
              ";
       }
     }
     ?>
     </tr>
      <tr >
        <td align="right" nowrap title="Vinculo" ><strong>Vinculo :</strong>
        </td>
        <td align="left">
          <?
          $v = array("g"=>"Geral", "a"=>"Ativo", "i"=>"Inativo", "p"=>"Pensionista", "ip"=>"Inativo/Pensionista");
          db_select('vinculo',$v,true,4,"");
          ?>
        </td>
      </tr>
														       
      <tr >
        <td align="right" nowrap title="Ordem" ><strong>Ordem</strong>
        </td>
        <td align="left">
          <?
          $o = array("n"=>"Numerica","a"=>"Alfabetica");
          db_select('ordem',$o,true,4,"");
          ?>
        </td>
      </tr>
														       
      <tr>
        <td >&nbsp;</td>
        <td >&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align = "center"> 
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
        </td>
      </tr>

  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_pesquisasel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostrasel1|r44_selec|r44_descr','Pesquisa',true);
  }else{
     if(document.form1.r44_selec.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_mostrasel','Pesquisa',false);
     }else{
       document.form1.r44_descr.value = '';
     }
  }
}
function js_mostrasel(chave,erro){
  document.form1.r44_descr.value = chave; 
  if(erro==true){ 
    document.form1.r44_selec.focus(); 
    document.form1.r44_selec.value = ''; 
  }
}
function js_mostrasel1(chave1,chave2){
  document.form1.r44_selec.value = chave1;
  document.form1.r44_descr.value   = chave2;
  db_iframe_selecao.hide();
}
function js_pesquisaDBtxt27(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrabestrut.php?funcao_js=parent.js_mostraDBtxt271|rh55_estrut|rh55_descr','Pesquisa',true);
  }else{
     if(document.form1.DBtxt27.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrabestrut.php?pesquisa_chave='+document.form1.DBtxt27.value+'&funcao_js=parent.js_mostraDBtxt27','Pesquisa',false);
     }else{
       document.form1.DBtxt27_descr.value = '';
     }
  }
}
function js_mostraDBtxt27(chave,erro){
  document.form1.DBtxt27_descr.value = chave; 
  if(erro==true){ 
    document.form1.DBtxt27.focus(); 
    document.form1.DBtxt27.value = ''; 
  }
}
function js_mostraDBtxt271(chave1,chave2){
  document.form1.DBtxt27.value = chave1;
  document.form1.DBtxt27_descr.value   = chave2;
  db_iframe_rhlocaltrab.hide();
}
function js_pesquisaDBtxt28(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrabestrut.php?funcao_js=parent.js_mostraDBtxt281|rh55_estrut|rh55_descr','Pesquisa',true);
  }else{
     if(document.form1.DBtxt28.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhlocaltrab','func_rhlocaltrabestrut.php?pesquisa_chave='+document.form1.DBtxt28.value+'&funcao_js=parent.js_mostraDBtxt28','Pesquisa',false);
     }else{
       document.form1.DBtxt28_descr.value = '';
     }
  }
}
function js_mostraDBtxt28(chave,erro){
  document.form1.DBtxt28_descr.value = chave; 
  if(erro==true){ 
    document.form1.DBtxt28.focus(); 
    document.form1.DBtxt28.value = ''; 
  }
}
function js_mostraDBtxt281(chave1,chave2){
  document.form1.DBtxt28.value = chave1;
  document.form1.DBtxt28_descr.value   = chave2;
  db_iframe_rhlocaltrab.hide();
}

function js_pesquisatabdesc(mostra){
     if(mostra==true){
       db_iframe.jan.location.href = 'func_tabdesc.php?funcao_js=parent.js_mostratabdesc1|0|2';
       db_iframe.mostraMsg();
       db_iframe.show();
       db_iframe.focus();
     }else{
       db_iframe.jan.location.href = 'func_tabdesc.php?pesquisa_chave='+document.form1.codsubrec.value+'&funcao_js=parent.js_mostratabdesc';
     }
}
function js_mostratabdesc(chave,erro){
  document.form1.k07_descr.value = chave;
  if(erro==true){
     document.form1.codsubrec.focus();
     document.form1.codsubrec.value = '';
  }
}
function js_mostratabdesc1(chave1,chave2){
     document.form1.codsubrec.value = chave1;
     document.form1.k07_descr.value = chave2;
     db_iframe.hide();
}

</script>


<?
if(isset($ordem)){
  echo "<script>
       // js_emite();
       </script>";  
}
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

?>