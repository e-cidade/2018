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

//21.833.694.
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clcgm = new cl_cgm;
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('k00_numpre');
$clrotulo->label('v07_parcel');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
.tabcols {
  font-size:11px;
}
.tabcols1 {
  text-align: right;
  font-size:11px;
}
.btcols {
	height: 17px;
	font-size:10px;
}
.links {
	font-weight: bold;
	color: #0033FF;
	text-decoration: none;
	font-size:10px;
    cursor: hand;
}
a.links:hover {
    color:black;
	text-decoration: underline;
}
.links2 {
	font-weight: bold;
	color: #0587CD;
	text-decoration: none;
	font-size:10px;
}
a.links2:hover {
    color:black;
	text-decoration: underline;
}
.nome {
  color:black;  
}
a.nome:hover {
  color:blue;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="DDD"></div>
<div id="processando" style="position:absolute; left:17px; top:107px; width:755px; height:205px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
<Table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td align="center" valign="middle" id="processandoTD"></td>
</tr>
</Table>
</div>

<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0"><tr><td height="430" align="left" valign="top" bgcolor="#CCCCCC">
  <center>
    <?	
	$mensagem_semdebitos = false;
	if(isset($HTTP_POST_VARS["pesquisar"]) || isset($matricula) || isset($inscricao)) {
	//aqui é pra se clicar no link da matricula em cai3_gerfinanc002.php
	  if(isset($inscricao) && !empty($inscricao))
   	    $HTTP_POST_VARS["q02_inscr"] = $inscricao;
      if(isset($matricula) && !empty($matricula))
	    $HTTP_POST_VARS["j01_matric"] = $matricula;
	
	  if(!empty($HTTP_POST_VARS["z01_numcgm"])) {
	    $result = pg_exec("select z01_numcgm as k00_numcgm from cgm where z01_numcgm = ".$HTTP_POST_VARS["z01_numcgm"]);
		if(pg_numrows($result) == 0) {
		  db_msgbox("Numcgm inexistente");
		  db_redireciona();
		  exit;
		} else {
		  $resultaux = $result;
	      if(!($result = debitos_tipos_numcgm($HTTP_POST_VARS["z01_numcgm"]))) {
            //db_msgbox('Sem débitos a pagar');
			$mensagem_semdebitos = true;
			$result = $resultaux;		
			unset($resultaux);
		  }
		  $arg = "numcgm=".$HTTP_POST_VARS["z01_numcgm"];		  
	    }
	  } else if(!empty($HTTP_POST_VARS["z01_numcgm"])) {
 	    $result = pg_exec("select z01_numcgm as k00_numcgm from cgm where z01_numcgm = ".$HTTP_POST_VARS["db_numcgm"]);
		if(pg_numrows($result) == 0) {
		  db_msgbox("Numcgm inexistente");
		  db_redireciona();
		  exit;
		} else {
		  $resultaux = $result;
	      if(!($result = debitos_tipos_numcgm($HTTP_POST_VARS["db_numcgm"]))) {
            //db_msgbox('Sem débitos a pagar');
			$mensagem_semdebitos = true;
			$result = $resultaux;
			unset($resultaux);
		  }
		  $arg = "numcgm=".$HTTP_POST_VARS["db_numcgm"];
		}
	  } else if(!empty($HTTP_POST_VARS["j01_matric"])) {
  	    $result = pg_exec("select j01_matric,j01_numcgm as k00_numcgm 
		                   from iptubase 
		                   where j01_matric = ".$HTTP_POST_VARS["j01_matric"]);
		if(pg_numrows($result) == 0) {
		  db_msgbox("Matrícula inexistente");
		  db_redireciona();
		  exit;
		} else {
	      $resultaux = $result;
		  if(!($result = debitos_tipos_matricula($HTTP_POST_VARS["j01_matric"]))) {
            //db_msgbox('Sem débitos a pagar');
			$mensagem_semdebitos = true;
            $result = $resultaux;
			unset($resultaux);
		  }
		  $arg = "matric=".$HTTP_POST_VARS["j01_matric"];
		}
	  } else if(!empty($HTTP_POST_VARS["q02_inscr"])) {
  	    $result = pg_exec("select q02_inscr,q02_numcgm as k00_numcgm from issbase where q02_inscr = ".$HTTP_POST_VARS["q02_inscr"]);
		if(pg_numrows($result) == 0) {
		  db_msgbox("Inscrição inexistente");
		  db_redireciona();
		  exit;
		} else {
          $resultaux = $result;
		  if(!($result = debitos_tipos_inscricao($HTTP_POST_VARS["q02_inscr"]))) {
            //db_msgbox('Sem débitos a pagar');
			$mensagem_semdebitos = true;
            $result = $resultaux;
			unset($resultaux);
		  }
	      $arg = "inscr=".$HTTP_POST_VARS["q02_inscr"];
		}
	  } else if(!empty($HTTP_POST_VARS["k00_numpre"])) {
		if(!($result = debitos_tipos_numpre($HTTP_POST_VARS["k00_numpre"]))) {

/*          $result = pg_query("select k00_numpre,k00_numpar
		                       from recibopaga 
							        left outer join tabrec on k02_codigo = k00_receit
									left outer join histcalc on k01_codigo = k00_hist
							   where k00_numnov = ".$HTTP_POST_VARS["k00_numpre"]." limit 1
							   union
							   select k00_numpre,k00_numpar
		                       from recibo 
							        left outer join tabrec on k02_codigo = k00_receit
									left outer join histcalc on k01_codigo = k00_hist
							   where k00_numpre = ".$HTTP_POST_VARS["k00_numpre"]." limit 1
							   ");
          if(pg_numrows($result)>0){
		     db_redireciona("cai3_gerfinanc555.php?recibo=true&numpre=".$HTTP_POST_VARS["k00_numpre"]."&numpar=0");
			 exit;
		  }
*/
		  $mensagem_semdebitos = true;

          db_msgbox('Sem débitos a pagar');
		  db_redireciona();
		  exit;		
		}
		$resultaux = 1;
	    $arg = "numpre=".$HTTP_POST_VARS["k00_numpre"];
	  }  else if(!empty($HTTP_POST_VARS["v07_parcel"])) {
	    $Rec = pg_exec("select v07_numpre from termo where v07_parcel = ".$HTTP_POST_VARS["v07_parcel"]);
		if(pg_numrows($Rec) == 0)
		  db_erro("Erro(175) não foi encontrado numpre pelo codigo do parcelamento ".$HTTP_POST_VARS["v07_parcel"]);
	    if(!($result = debitos_tipos_numpre(pg_result($Rec,0,0)))) {
          db_msgbox('Sem débitos a pagar');
		  $mensagem_semdebitos = true;
		  db_redireciona();
		  exit;		
		}
		$resultaux = 1;
	    $arg = "numpre=".pg_result($Rec,0,0);
		$Parcelamento = $HTTP_POST_VARS["v07_parcel"];		
		pg_freeresult($Rec);
	  }
	  $dados = pg_exec("select z01_numcgm,z01_nome,z01_ender,z01_munic,z01_uf,z01_cgccpf,z01_ident 
	                    from cgm 
						where z01_numcgm = ".pg_result($result,0,"k00_numcgm"));
	  db_fieldsmemory($dados,0);	  
	?>
        <table width="100%" border="1" cellspacing="0" cellpadding="0">
          <tr> 
            <td colspan="2"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="33%"> <table width="104%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td nowrap title="Clique Aqui para ver os dados cadastrais." class="tabcols"><strong style=\"color:blue\><a href='' onclick='js_mostracgm();return false;'>NumCgm:&nbsp;</a></strong></td>
                        <td class="tabcols" nowrap title="Clique Aqui para ver os dados cadastrais."> 
                          <input class="btcols" type="text" name="z01_numcgm" value="<?=@$z01_numcgm?>" size="5" readonly> 
                          &nbsp;&nbsp;&nbsp; 
                          <?
					  parse_str($arg);
					  if(isset($matric))
					    $Label = "<a href='' onclick='js_mostrabic_matricula();return false;'>Matrícula:</a>";
					  else if(isset($inscr))
					    $Label = "<a href='' onclick='js_mostrabic_inscricao();return false;'>Inscrição:</a>";
					  else if(isset($numpre))
					    $Label = "Numpre:";
					  else if(isset($Parcelamento))
					    $Label = "Parcelamento:";
					  if(isset($Label))
					  echo "<strong style=\"color:blue\">$Label</strong> <input style=\"border: 1px solid blue;font-weight: bold;background-color:#80E6FF\" class=\"btcols\" type=\"text\" name=\"Label\" value=\"".@$matric.@$inscr.@$numpre.@$Parcelamento."\" size=\"10\" readonly>\n";
					  ?>
                        </td>
                      </tr>
                      <tr> 
                        <td nowrap class="tabcols"><strong>Nome:</strong></td>
                        <td nowrap><input class="btcols" type="text" name="z01_nome" value="<?=@$z01_nome?>" size="46" readonly> 
                          &nbsp;</td>
                      </tr>
                      <tr> 
                        <td nowrap class="tabcols"><strong>Endereço:</strong></td>
                        <td nowrap><input class="btcols" type="text" name="z01_ender" value="<?=@$z01_ender?>" size="46" readonly> 
                        </td>
                      </tr>
                      <tr> 
                        <td nowrap class="tabcols"><strong>Município:</strong></td>
                        <td><input class="btcols" type="text" name="z01_munic" value="<?=@$z01_munic?>" size="20" readonly> 
                          <strong class="tabcols">UF:</strong> <input class="btcols" type="text" name="z01_uf" value="<?=@$z01_uf?>" size="2" maxlength="2" readonly=""> 
                          &nbsp;</td>
                      </tr>
                      <form name="formatu" action="cai3_gerfinanc001.php" method="post">
                        <tr> 
                          <td height="21" colspan="2" nowrap class="tabcols"> 
                            &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; </td>
                      </form>
                    </table></td>
                  <td width="67%" valign="top"> 
                    <?
		    $numrows = pg_numrows($result);
			   echo "<script>
			   function js_envia(chave){
			     debitos.location.href=chave;		        
			   }
			   </script>
				  ";
            echo "<table border=\"1\" cellspacing=\"0\" cellpadding=\"0\">\n<tr class=\"links\">\n<td valign=\"top\" style=\"font-size:11px\"><form name=\"form2\" method=\"post\" target=\"debitos\">\n";
	  	    if(isset($resultaux)) {
		      for($i = 0;$i < $numrows;$i++) {
               echo "
				<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				  <tr>
				    <td valign=\"top\" class=\"links\" id=\"tipodeb$i\">
				      <a title=\"".pg_result($result,$i,"k00_tipo")."\" class=\"links\" href=\"\" id=\"tipodeb$i\" onClick=\"js_envia('div4_importa002.php?".$arg."&tipo=".pg_result($result,$i,"k00_tipo")."&emrec=".pg_result($result,$i,"k00_emrec")."&agnum=".pg_result($result,$i,"k00_agnum")."&agpar=".pg_result($result,$i,"k00_agpar")."&db_datausu=');return false;\" target=\"debitos\">".pg_result($result,$i,"k00_descr")."&nbsp;</a>
					</td>
				  </tr>
				</table>\n";
			    if($i == 8)
			      echo "</td><td style=\"font-size:11px\" valign=\"top\">\n";
		      }
			}
		   
		  ?>
                  </td>
                </tr>
			      <td height="2"></form> 
</table>
              </td>
          </tr>
          <tr> 
            <td colspan="2" align="center" valign="middle"> <table border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td> 
                    <!--iframe height="205" width="755" name="debitos" src="div4_importa002.php?matricula=<?=@$matricula?>&inscricao=<?=$inscricao?>&tipo2=<?=@$tipo2?>"></iframe-->
                    <iframe id="debitos" height="235" width="755" name="debitos" src="cai3_gerfinanc007.php"></iframe> 
                  </td>
                </tr>
                <tr> 
                  <td align="right"> <table border="1" bordercolor="#000000" cellspacing="0" cellpadding="0" width="100%">
                      <tr bgcolor="#666666"> 
                        <th style="font-size:11px">Valor</th>
                        <th style="font-size:11px">Valor Corr.</th>
                        <th style="font-size:11px">Juros</th>
                        <th style="font-size:11px">Multa</th>
                        <th style="font-size:11px">Desconto</th>
                        <th style="font-size:11px">Total</th>
                      </tr>
                      <tr> 
                        <td class="tabcols1"><font id="valor1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="valorcorr1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="juros1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="multa1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="desconto1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="total1">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                      </tr>
                      <tr> 
                        <td class="tabcols1"><font id="valor2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="valorcorr2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="juros2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="multa2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="desconto2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="total2">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                      </tr>
                      <tr> 
                        <td class="tabcols1"><font id="valor3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="valorcorr3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="juros3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="multa3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="desconto3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                        <td class="tabcols1"><font id="total3">0.00</font><img src="imagens/alinha.gif" border="0" width="5"></td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td height="26" align="center"> 
              <input type="button" name="enviar" id="enviar" value="Emitir Recibo" onClick="return js_emiterecibo()" disabled>
            </td>
          </tr>
        </table>
    <?
	} else {
	?>
    <form name="form1" method="post">
	
          <table border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td height="25" title="<?=$Tz01_nome?>"> 
                <?
				db_ancora($Lz01_nome,'js_mostranomes(true);',4)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("z01_numcgm",6,$Iz01_numcgm,true,'text',4," onchange='js_mostranomes(false);'")
				?>
                <?
				db_input("z01_nome",40,$Iz01_nome,true,'text',5)
				?>
              </td>
            </tr>
            <tr> 
              <td height="25" title="<?=$Tj01_matric?>"> 
                <?
				db_ancora($Lj01_matric,'js_mostramatricula(true);',2)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("j01_matric",8,$Ij01_matric,true,'text',4)
				?>
              </td>
            </tr>
            <tr> 
              <td height="25" title="<?=$Tq02_inscr?>"> 
                <?
				db_ancora($Lq02_inscr,'js_mostrainscricao(true);',4)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("q02_inscr",8,$Iq02_inscr,true,'text',4)
				?>
              </td>
            </tr>
            <tr> 
              <td height="25" title="<?=$Tk00_numpre?>"> 
                <?
				db_ancora($Lk00_numpre,'js_mostranumpre(true);',3)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("k00_numpre",8,$Ik00_numpre,true,'text',4)
				?>
              </td>
            </tr>
            <tr> 
              <td height="25" title="<?=$Tv07_parcel?>"> 
                <?
				db_ancora($Lv07_parcel,'js_mostraparcel(true);',3)
				?>
              </td>
              <td height="25"> 
                <?
				db_input("v07_parcel",8,$Iv07_parcel,true,'text',4)
				?>
              </td>
            </tr>
            <tr> 
              <td height="25">&nbsp;</td>
              <td height="25"><input onClick="if((this.form.v07_parcel.value=='' && this.form.z01_numcgm.value=='' && this.form.j01_matric.value=='' && this.form.q02_inscr.value=='' && this.form.k00_numpre.value=='')) { alert('Informe numcgm, matricula, inscrição,parcelamento ou numpre.');return false; }"  type="submit" value="Pesquisar" name="pesquisar"></td>
            </tr>
          </table>
        </form>
    <?
	}
	?>
  </center>
</td></tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
// mostra os dados do cgm do contribuinte
function js_mostracgm(){
  func_nome.jan.location.href = 'prot3_conscgm002.php?fechar=func_nome&numcgm=<?=@$z01_numcgm?>';
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}


// esta funcao é utilizada quando clicar na matricula após pesquisar
// a mesma
function js_mostrabic_matricula(){
  func_nome.jan.location.href = 'cad3_conscadastro_002.php?cod_matricula=<?=@$matric?>';
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}
// esta funcao é utilizada quando clicar na inscricao após pesquisar
// a mesma
function js_mostrabic_inscricao(){
  func_nome.jan.location.href = 'iss3_consinscr003.php?numeroDaInscricao=<?=@$inscr?>';
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}


function js_mostranomes(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_nome.php?funcao_js=parent.js_preenche|0|1';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_preenche';	
  }
}
 function js_preenche(chave,chave1){
   document.form1.z01_numcgm.value = chave;
   document.form1.z01_nome.value = chave1;
   func_nome.hide();
 }

function js_mostramatricula(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_iptubase.php?funcao_js=parent.js_preenchematricula|0|1';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenchematricula';	
  }
}
 function js_preenchematricula(chave,chave1){
   document.form1.j01_matric.value = chave;
   document.form1.z01_nome.value = chave1;
   func_nome.hide();
 }
function js_mostrainscricao(mostra){
  if(mostra==true){
    func_nome.jan.location.href = 'func_issbase.php?funcao_js=parent.js_preencheinscricao|0|1';
    func_nome.mostraMsg();
    func_nome.show();
    func_nome.focus();
  }else{
    func_nome.jan.location.href = 'func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_preencheinscricao';	
  }
}
 function js_preencheinscricao(chave,chave1){
   document.form1.q02_inscr.value = chave;
   document.form1.z01_nome.value = chave1;
   func_nome.hide();
 }
 
	
</script>
<?

$func_nome = new janela('func_nome','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=770;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

?>