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
include("classes/db_movrel_classe.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clmovrel = new cl_movrel;
$clrotulo = new rotulocampo;
$clrotulo->label("r54_regist");
$clrotulo->label("z01_nome");
$clrotulo->label("DBtxt23");
$clrotulo->label("DBtxt25");
$clrotulo->label("r54_codrel");
$clrotulo->label("r56_descr");
$clrotulo->label("r54_codeve");
$clrotulo->label("r55_descr");
$queryst = "";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="if(document.form1.r54_codrel)document.form1.r54_codrel.focus();">
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
      <center>
      <?
      if(!isset($r54_regist) && !isset($r54_codeve) && !isset($r54_codrel)){
      ?>
      <form name="form1" method="post" action="pes3_consmovrel001.php">
        <table border="0">
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"><strong>Ano/Mês:</strong></td>
            <td>
              <?
              $ano = db_anofolha();
              db_input("ano",4,'',true,'text',3)
              ?>
              &nbsp;/&nbsp;
              <?
              $mes = db_mesfolha();
              db_input("mes",2,'',true,'text',3)
              ?>
            </td>
          </tr>
          <tr>
            <td align="right" nowrap title="<?=@$Tr54_codrel?>">
              <?
              db_ancora(@$Lr54_codrel,"js_pesquisar54_codrel(true);",1);
              ?>
            </td>
            <td> 
              <?
              db_input('r54_codrel',8,$Ir54_codrel,true,'text',1,"onchange='js_pesquisar54_codrel(false);' tabIndex='1'");
              db_input('r56_descr',40,$Ir56_descr,true,'text',3,"");
              ?>
            </td>
          </tr>
          <tr> 
            <td align="right" title="<?=$Tr54_regist?>"> 
              <?
              db_ancora(@ $Lr54_regist, "js_pesquisar54_regist(true);", 1);
              ?>
            </td>
            <td> 
              <?
              db_input('r54_regist', 8, $Ir54_regist, true, 'text', 1, " onchange='js_pesquisar54_regist(false);' tabIndex='2'")
              ?>
              <?
              db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, '');
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr54_codeve?>">
              <?
              db_ancora(@$Lr54_codeve,"js_pesquisar54_codeve(true);",1);
              ?>
            </td>
            <td> 
              <?
              db_input('r54_codeve',8,$Ir54_codeve,true,'text',1,"onchange='js_pesquisar54_codeve(false);' tabIndex='3'");
              db_input('r55_descr',40,$Ir55_descr,true,'text',3,"");
              ?>
            </td>
          </tr>
          <tr> 
            <td height="25" colspan="2" align="center">
              <input type="button" value="Consultar" name="pesquisar" onclick="js_abrejan(true);" tabIndex='4'>
            </td>
          </tr>
        </table>
      </form>
      <?
      }else{
        $dbwhere = "";
        $ano = db_anofolha();
        $mes = db_mesfolha();
        $queryst = "?ano=".$ano."&mes=".$mes;
        if(isset($r54_codrel) && $r54_codrel != ""){
          $dbwhere .= " and r54_codrel = '".$r54_codrel."' ";
          $queryst .= "&r54_codrel=".$r54_codrel;
        }
        if(isset($r54_regist) && $r54_regist != ""){
          $dbwhere .= " and r54_regist = ".$r54_regist;
          $queryst .= "&r54_regist=".$r54_regist;
        }
        if(isset($r54_codeve) && $r54_codeve != ""){
          $dbwhere .= " and r54_codeve = '".$r54_codeve."' ";
          $queryst .= "&r54_codeve=".$r54_codeve;
        }
        $dbwhere .= " and r54_instit = ".db_getsession('DB_instit');  
        db_input('r54_codrel',8,$Ir54_codrel,true,'hidden',3);
        db_input('r54_codeve',8,$Ir54_codeve,true,'hidden',3);
        db_input('r54_regist',8,$Ir54_regist,true,'hidden',3);
        $repassa = array("r54_codrel"=>$r54_codrel,"r54_codeve"=>$r54_codeve,"r54_regist"=>$r54_regist);
 
        $sql = $clmovrel->sql_query_dados(null,"r54_codrel,r54_codeve,r54_regist,z01_nome,r54_quant1,r54_quant2,r54_quant3,r54_lancad,rh05_recis","r54_lancad,z01_nome","r54_anomes = '".$ano.$mes."' $dbwhere",$ano,$mes);
//	echo $sql;
        db_lovrot($sql,15,"()","","","","NoMe",$repassa);
        ?>  
        <BR>
        <input type='button' value='Nova consulta' onclick='location.href=\"pes3_consmovrel001.php\"' tabIndex='1'>
        <input type='button' value='Imprimir' name='relatorio' onclick='js_abrejan(false);' tabIndex='4'>
        <?
      }
      ?>
      </center>
    </td>
  </tr>
</table>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_abrejan(TorF){
  if(TorF == true){
    if(document.form1.r54_codrel.value == "" && document.form1.r54_regist.value == "" && document.form1.r54_codeve.value == ""){
      alert("Sem dados para consultar.");
      document.form1.r54_codrel.focus();
    }else{
      document.form1.submit();
    }
  }else{
    jan = window.open('pes2_consmovrel002.php<?=$queryst?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}
function js_pesquisar54_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.r54_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.r54_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
    }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.r54_regist.focus(); 
    document.form1.r54_regist.value = ''; 
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.r54_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisar54_codeve(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_relac','func_relac.php?funcao_js=parent.js_mostrarelac1|r55_codeve|r55_descr|r56_dirarq&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,'20');
  }else{
    if(document.form1.r54_codeve.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_relac','func_relac.php?pesquisa_chave='+document.form1.r54_codeve.value+'&funcao_js=parent.js_mostrarelac&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false,'0');
    }else{
      document.form1.r55_descr.value = '';
    }
  }
}
function js_pesquisar54_codrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_convenio','func_convenioalt.php?funcao_js=parent.js_mostraconvenio1|r56_codrel|r56_descr|r56_dirarq&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true,20);
  }else{
    if(document.form1.r54_codrel.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_convenio','func_convenioalt.php?pesquisa_chave='+document.form1.r54_codrel.value+'&funcao_js=parent.js_mostraconvenio&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false,'0');
    }else{
      document.form1.r56_descr.value = '';
      document.form1.diretorio_arquivo.value = ''; 
    }
  }
}
function js_mostraconvenio(chave1,chave2,erro){
  document.form1.r56_descr.value  = chave1;
  if(erro==true){
    document.form1.r54_codrel.value = '';
    document.form1.r54_codrel.focus(); 
  }
}
function js_mostraconvenio1(chave1,chave2,chave3){
  document.form1.r54_codrel.value = chave1;
  document.form1.r56_descr.value  = chave2;
  db_iframe_convenio.hide();
}
function js_mostrarelac(chave,erro){
  document.form1.r55_descr.value  = chave;
  if(erro==true){ 
    document.form1.r54_codeve.value = '';
    document.form1.r54_codeve.focus(); 
  }
}
function js_mostrarelac1(chave1,chave2){
  document.form1.r54_codeve.value = chave1;
  document.form1.r55_descr.value  = chave2;
  db_iframe_relac.hide();
}
</script>