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
include("libs/db_sql.php");
include("classes/db_numpref_classe.php");
include("dbforms/db_funcoes.php");

$clnumpref = new cl_numpref;
$clnumpref->rotulo->label();

$clnumpref->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k06_descr");


if(isset($HTTP_POST_VARS["alterar"])) {
  $result = pg_exec("select k03_anousu from numpref where k03_anousu = ".db_getsession("DB_anousu"));
  db_postmemory($HTTP_POST_VARS);

  $k03_numpre = $k03_numpre==""?0:$k03_numpre;
  $k03_defope = $k03_defope==""?0:$k03_defope;
  $k03_recjur = $k03_recjur==""?0:$k03_recjur;
  $k03_numsli = $k03_numsli==""?0:$k03_numsli;
  $k03_codbco = $k03_codbco==""?0:$k03_codbco;
  $k03_recmul = $k03_recmul==""?0:$k03_recmul;
  
  if(pg_numrows($result) == 0) {
    pg_exec("insert into numpref(k03_anousu,
                                 k03_numpre,                                 
                                 k03_defope,
                                 k03_recjur,
                                 k03_numsli,
                                 k03_impend,
                                 k03_unipri,
                                 k03_codbco,
                                 k03_codage,
                                 k03_recmul,
                                 k03_calrec,
								 k03_msg,
                                 k03_certissvar,
                                 k03_reccert,
                                 k03_taxagrupo)
                          values(".db_getsession("DB_anousu").",
                                 $k03_numpre,
                                 $k03_defope,
                                 $k03_recjur,
                                 $k03_numsli,
                                 '$k03_impend',
                                 '$k03_unipri',
                                 $k03_codbco,
                                 '$k03_codage',
                                 $k03_recmul,
                                 '$k03_calrec',
								 '$k03_msg',
								 '$k03_certissvar',
                                 '$k03_reccert',
                                 '$k03_taxagrupo')") or die("Erro(40) inserindo em numpref");
  } else {
    pg_exec("update numpref set  k03_numpre = $k03_numpre,
                                      k03_defope = $k03_defope,
                                      k03_recjur = $k03_recjur,
                                      k03_numsli = $k03_numsli,
                                      k03_impend = '$k03_impend',
                                      k03_unipri = '$k03_unipri',
                                      k03_codbco = $k03_codbco,
                                      k03_codage = '$k03_codage',
                                      k03_recmul = $k03_recmul,
                                      k03_calrec = '$k03_calrec',
									  k03_msg = '$k03_msg',
									  k03_certissvar = '$k03_certissvar'
									  k03_reccert = '$k03_reccert'
									  k03_taxagrupo = '$k03_taxagrupo'
			 where k03_anousu = ".db_getsession("DB_anousu")) or die("Erro(52) alterando numpref.");

  }
}
$result = pg_exec("select * from numpref where k03_anousu = ".db_getsession("DB_anousu"));
if(pg_numrows($result) > 0)
  db_fieldsmemory($result,0);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table width="27%" border="0" cellspacing="0" cellpadding="0">
            <tr> 
              <td nowrap colspan=2><br></td>
            </tr>

            <tr> 
              <td width="75%" nowrap title='<?=$Tk03_anousu?>'><?=$Lk03_anusu?></td>
              <td width="25%" nowrap><input name="k03_anousu" type="text" id="k03_anousu" value="<?=@$k03_anousu?>" size="4" readonly></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_numpre?>'><?=$Lk03_numpre?></td>
              <td nowrap><input name="k03_numpre" type="text" id="k03_numpre" value="<?=@$k03_numpre?>" size="10"></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_defope?>'><?=$Lk03_defope?></td>
              <td nowrap><input name="k03_defope" type="text" id="k03_defope" value="<?=@$k03_defope?>" size="10"></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_recjur?>'><?=$Lk03_recjur?></td>
              <td nowrap><input name="k03_recjur" type="text" id="k03_recjur" value="<?=@$k03_recjur?>" size="10"></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_numsli?>'><?=$Lk03_numsli?></td>
              <td nowrap><input name="k03_numsli" type="text" id="k03_numsli" value="<?=@$k03_numsli?>" size="10"></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_impend?>'><?=$Lk03_impend?></td>
              <td nowrap><select name="k03_impend" id="k03_impend">
                  <option value="f" <? echo isset($k03_impend)?($k03_impend=="f"?"selected":""):"" ?>>N&atilde;o</option>
                  <option value="t" <? echo isset($k03_impend)?($k03_impend=="t"?"selected":""):"" ?>>Sim</option>
                </select></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_unipri?>'><?=$Lk03_unipri?></td>
              <td nowrap><select name="k03_unipri" id="k03_unipri">
                  <option value="f" <? echo isset($k03_unipri)?($k03_unipri=="f"?"selected":""):"" ?>>N&atilde;o</option>
                  <option value="t" <? echo isset($k03_unipri)?($k03_unipri=="t"?"selected":""):"" ?>>Sim</option>
                </select></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_codbco?>'><?=$Lk03_codbco?></td>
              <td nowrap><input name="k03_codbco" type="text" id="k03_codbco" value="<?=@$k03_codbco?>" size="10"></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_codage?>'><?=$Lk03_codage?></td>
              <td nowrap><input name="k03_codage" type="text" id="k03_codage" value="<?=@$k03_codage?>" size="5" maxlength="5"></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_recmul?>'><?=$Lk03_recmul?></td>
              <td nowrap><input name="k03_recmul" type="text" id="k03_recmul" value="<?=@$k03_recmul?>" size="10"></td>
            </tr>
            <tr> 
              <td nowrap title='<?=$Tk03_calrec?>'><?=$Lk03_calrec?></td>
              <td nowrap><select name="k03_calrec" id="k03_calrec">
                  <option value="f" <? echo isset($k03_calrec)?($k03_calrec=="f"?"selected":""):"" ?>>N&atilde;o</option>
                  <option value="t" <? echo isset($k03_calrec)?($k03_calrec=="t"?"selected":""):"" ?>>Sim</option>
                </select></td>
            </tr>
<!-- ********************************************************************************************************************************** -->
			 <tr>
				<td nowrap title="<?=@$Tk03_reccert?>">
				   <?=@$Lk03_reccert?>
				</td>
				<td>
				<?
				$x = array("f"=>"NAO","t"=>"SIM");
				db_select('k03_reccert',$x,true,$db_opcao,"");
				?>
				</td>
			</tr>
			<tr>
				<td nowrap colspan=2 title="<?=@$Tk03_taxagrupo?>">
				   <?
				   db_ancora(@$Lk03_taxagrupo,"js_pesquisak03_taxagrupo(true);",$db_opcao);
				   ?>
<!--				</td>
				<td> -->
				   <?
			    	db_input('k03_taxagrupo',10,$Ik03_taxagrupo,true,'text',$db_opcao," onchange='js_pesquisak03_taxagrupo(false);'")
		    		?>
				   <?
          			db_input('k06_descr',50,$Ik06_descr,true,'text',3,'')
				   ?>
				</td>
			  </tr>
<!-- *********************************************************************************************************************************** -->
			
            <tr> 
              <td nowrap title='<?=$Tk03_certissvar?>'><?=$Lk03_certissvar?></td>
              <td nowrap><select name="k03_certissvar" id="k03_certissvar">
                  <option value="f" <? echo isset($k03_certissvar)?($k03_certissvar=="f"?"selected":""):"" ?>>N&atilde;o</option>
                  <option value="t" <? echo isset($k03_certissvar)?($k03_certissvar=="t"?"selected":""):"" ?>>Sim</option>
                </select></td>
            </tr>

            <tr>
              <td nowrap title='<?=$Tk03_msg?>'><?=$Lk03_msg?></td>
              <td nowrap><textarea name="k03_msg" cols="60" rows="7" wrap="OFF" id="k03_msg"><?=@$k03_msg?></textarea></td>
            </tr>
            <tr> 
              <td nowrap>&nbsp;</td>
              <td height="30" nowrap><input name="alterar" type="submit" id="alterar" value="Alterar"></td>
            </tr>
          </table>
        </form>
      </center>	
	</td>
  </tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>