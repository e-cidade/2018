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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_libcontabilidade.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_orcorgao_classe.php");
include ("classes/db_orcpparec_classe.php");
include ("classes/db_conplanoreduz_classe.php");
include ("classes/db_orcreceita_classe.php");
include ("classes/db_orcppa_classe.php");
include ("classes/db_orcdotacao_classe.php");
include("classes/db_orcparametro_classe.php");


db_postmemory($HTTP_POST_VARS);

$clorcorgao = new cl_orcorgao;
$clrotulo = new rotulocampo;
$clorcpparec = new cl_orcpparec;
$clconplanoreduz = new cl_conplanoreduz;
$clorcreceita = new cl_orcreceita;
$clorcppa = new cl_orcppa;
$clorcparametro = new cl_orcparametro;
$clorcdotacao = new cl_orcdotacao;

$clrotulo->label("o23_orgao");
$clrotulo->label("o21_codleippa");
$clrotulo->label("o21_descr");
$clrotulo->label("o23_indica");

$instit = db_getsession("DB_instit");
/*  se clicado no processar, inicia exportação
 *  -------------------------------
 */
if (isset ($exportar) && $exportar == "Exportar") {
	$erro = false;
	/*
	 *  exporta receitas 
	 * ------------------------
	 */
	if ($exporta_fontes == "sim") {
		/*  --------------------------------------------
		 *  seleciona as receitas do orcpparec
		 */
		$res = $clorcpparec->sql_record(
		       $clorcpparec->sql_query(
		              null, 
			      "o27_exercicio,o27_codfon,o27_valor,o27_concarpeculiar", 
			      null, 
			      " o27_exercicio = $anoexe_exporta "));
		if ($clorcpparec->numrows > 0) {
			// db_criatabela($res);
			for ($x = 0; $x < $clorcpparec->numrows; $x ++) {
				db_fieldsmemory($res, $x);
				/* 
				 * para cada codfon, verifica se tem recurso no conplanoreduz
				 * -----------------------------------------------------------------------------
				 */
				$rres = $clconplanoreduz->sql_record(
				  $clconplanoreduz->sql_query(
				        null,
					null,
					"c61_codigo,c61_instit", 
					null, 
					"c61_codcon=$o27_codfon  and 
					 c61_instit= $instit and 
					 c61_anousu= $o27_exercicio"));
				if ($clconplanoreduz->numrows > 0) {
					for ($y = 0; $y < $clconplanoreduz->numrows; $y ++) {
						db_fieldsmemory($rres, $y);
					}
				} else {
					// não migra ppa quando nao tem recurso no reduzido
					continue;
				} //endif
				/* insere no orcreceita do exercicio escolhido 
				 * ------------------------------------------------------ 
				 */
				$clorcreceita->o70_codfon         = $o27_codfon;
				$clorcreceita->o70_instit         = $c61_instit;
				$clorcreceita->o70_anousu         = $o27_exercicio;
				$clorcreceita->o70_codrec         = null;
				$clorcreceita->o70_codigo         = $c61_codigo; //recurso
				$clorcreceita->o70_valor          = $o27_valor;
				$clorcreceita->o70_reclan         = 'false';
        $clorcreceita->o70_concarpeculiar = $o27_concarpeculiar;

				$teste = $clorcreceita->incluir($clorcreceita->o70_anousu, $clorcreceita->o70_codrec);
				if ($clorcreceita->erro_status == 0) {
					db_msgbox(" Falha no Processo : mensagem :".$clorcreceita->erro_msg." fonte :".$clorcreceita->o70_codfon);
					$erro = true;
					break;
				}
			} //endfor

			if ($erro == false) {
			  	$rr = pg_query("
					update
					orcreceita
					set o70_valor = o70_valor*-1

					where
					o70_anousu = ".db_getsession("DB_anousu")." and
					o70_codrec in

					(
					select o70_codrec
					from  orcreceita
					   inner join orcfontes on o70_codfon=o57_codfon and o70_anousu=o57_anousu
					   where o70_anousu=".db_getsession("DB_anousu")."
					   and fc_conplano_grupo(".db_getsession("DB_anousu").",substr(o57_fonte,1,2)||'%',9000) is true
					 )
				");
				db_msgbox(" Processamento concluído com sucesso ");
			} else {
				db_msgbox(" Ocorreram erros durante o processamento - Contate Suporte !");
			} //endif

		} //endif
	} //endif

	if ($exporta_elementos == "sim") {
		/*  --------------------------------------------
		 *  seleciona as elementos do orcppa  e cria orcdotação
		 */
		$res = $clorcppa->sql_record($clorcppa->sql_query_exporta($anoexe_exporta, null, "*", null, ""));
		if ($clorcppa->numrows > 0) {
			// db_criatabela($res);
			for ($x = 0; $x < $clorcppa->numrows; $x ++) {
				db_fieldsmemory($res, $x);
				
				$clorcdotacao->o58_anousu = $anoexe_exporta;
				$clorcdotacao->o58_coddot = null;
				$clorcdotacao->o58_orgao = $o23_orgao;
				$clorcdotacao->o58_unidade = $o23_unidade;
				$clorcdotacao->o58_funcao =  $o23_funcao;
				$clorcdotacao->o58_subfuncao = $o23_subfuncao;
				$clorcdotacao->o58_programa =  $o23_programa;
				$clorcdotacao->o58_projativ =  $o23_acao;
				$clorcdotacao->o58_codele = $o25_codele;
				$clorcdotacao->o58_codigo =  $o26_codigo;
				$clorcdotacao->o58_valor =  $o24_valor;
				$clorcdotacao->o58_instit =  $o55_instit;				
				$result = $clorcparametro->sql_record(
                                " update orcparametro set o50_coddot = o50_coddot + 1	where o50_anousu = ".$anoexe_exporta);
        		$result = $clorcparametro->sql_record(
                              $clorcparametro->sql_query_file($anoexe_exporta,'o50_coddot as o58_coddot'));                                     
                if($result != false && $clorcparametro->numrows>0){  
                	    db_fieldsmemory($result,0);
                	    $clorcdotacao->incluir($anoexe_exporta,$o58_coddot); 
                	    if($clorcdotacao->erro_status==0){
                	         db_msgbox("Falha na exportação".$clorcdotacao->erro_msg);            
                	    	 $erro_trans=true;   
                	    	 break;    
                	    } 	
                } // endif
			} //endfor
		} //endif
	} //endif
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table  align=center border=0>
  <form name="form1" method="post" action="" >
  <tr>
    <td nowrap title="<?=@$To21_codleippa?>" align='right'>
      <?



db_ancora(@ $Lo21_codleippa, "js_pesquisao21_codleippa(true);", 1);
?>
    </td>
    <td align='left' colspan=2> 
      <?



db_input('o21_codleippa', 8, $Io21_codleippa, true, 'text', 1, " onchange='js_pesquisao21_codleippa(true);'")
?>
      <?

 db_input('o21_descr', 40, $Io21_descr, true, 'text', 3, '')
?>
    </td>
  </tr>
      
<? 
 if (isset ($chavepesquisa) && $chavepesquisa != "") {

	echo "<tr><td>Exercicio para exportação  </td>";

	echo "<td colspan=2>";
	/*
	 *  seleciona os anos da lei e libera em um select
	 *  -----------------------------------------------------------
	 */
	echo "<select name=anoexe_exporta>";
	$sql = "select * from orcppalei";
	$res = pg_exec($sql);
	if (pg_numrows($res) > 0) {
		db_fieldsmemory($res, 0);
		for ($x = $o21_anoini; $x <= $o21_anofim; $x ++) {
			echo "<option value=$x > $x </option>";
		}
	}
	echo "</select>";
	echo "</td></tr>";
?>
    <tr>
          <td><? db_ancora("Exportar fontes da Receita","js_fontes()",1);   ?> </td>
            <td ><select name="exporta_fontes">
                      <option value="nao">Não </option>
                      <option value="sim">Sim </option>
                     </select>
            </td>
        <td align=left>
             <? db_ancora("Fontes que não serão exportados","js_fontes_nao_exportados()",1);   ?>
          </td>    
    </tr>
    
    
    <tr>
            <td><? db_ancora("Exportar elementos de Despesa","js_elementos()",1);   ?> </td>
            <td colspan=1><select name=exporta_elementos>
                      <option value=nao>Não </option>
                      <option value=sim>Sim </option>
                     </select>
            </td>
            <td align=left>
             <? db_ancora("Elementos que não serão exportados","js_elementos_nao_exportados()",1);   ?>
          </td>
    </tr>
    
    <tr>   
       <td colspan=3> &nbsp; </td>       
    </tr>
            
    <tr>   
       <td colspan=3 align="center">
             <input type=submit name=exportar value=Exportar>
       </td>       
    </tr>
    
    <?



}
?>
  
</form>
</table>
<?



db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
<script>
function js_pesquisao21_codleippa(){
   js_OpenJanelaIframe('top.corpo','db_iframe_orcppalei','func_orcppalei.php?funcao_js=parent.js_mostraorcppalei1|o21_codleippa|o21_descr','Pesquisa',true);
}

function js_mostraorcppalei1(chave1,chave2){
   document.form1.o21_codleippa.value = chave1;  
   db_iframe_orcppalei.hide();
   location.href='orc4_exportappa001.php?chavepesquisa='+chave1;
}

function js_fontes(){
   js_OpenJanelaIframe('top.corpo','db_iframe_orcpparec','func_orcpparec.php?funcao_js=','Pesquisa',true);
}
function js_elementos(){
   js_OpenJanelaIframe('top.corpo','db_iframe_orcppa','func_orcppa.php?funcao_js=','Pesquisa',true);
}
function js_fontes_nao_exportados(){
	ano = document.form1.anoexe_exporta.value;
    jan = window.open('orc4_exportappa_rel001.php?exercicio='+ano,'safo','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
}
function js_elementos_nao_exportados(){
	ano = document.form1.anoexe_exporta.value;
    jan = window.open('orc4_exportappa_rel002.php?exercicio='+ano,'safo','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
}

</script>
</html>