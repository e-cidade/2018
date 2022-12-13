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
include("classes/db_rhfuncao_classe.php");
include("classes/db_rhregime_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$clrhfuncao = new cl_rhfuncao;
$clrhregime = new cl_rhregime;
$clrhfuncao->rotulo->label();
$clrotulo = new rotulocampo;
$saldo = 0;
if(!isset($ano) || (isset($ano) && trim($ano)=="")){
  $ano = db_anofolha();
}
if(!isset($mes) || (isset($mes) && trim($mes)=="")){
  $mes = db_mesfolha();
}
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

<script>
function js_MudaLink(nome) {
  document.getElementById('processando').style.visibility = 'visible';
  document.getElementById('processandoTD').innerHTML = '<h3>Aguarde, processando ...</h3>';
  for(i = 0;i < document.links.length;i++) {
    var L = document.links[i].id;
	if(L!=""){
 	  document.getElementById(L).style.backgroundColor = '#CCCCCC';
	  document.getElementById(L).hideFocus = true;
	}
  }
  document.getElementById(nome).style.backgroundColor = '#E8EE6F';
}

function js_relatorio(){
    <?
    if(!empty($funcao)) {
      
      echo "jan = window.open('pes2_consrhfuncao003.php?funcao='+document.form1.rh37_funcao.value+'&ano=$ano&mes=$mes&colunas1=".@$colunas1."','sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
    }else{
      echo "jan = window.open('pes2_consrhfuncao002.php?ano=$ano&mes=$mes&colunas1=".@$colunas1."','sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
    }
    ?>
    jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="DDD"></div>
<div id="processando" style="position:absolute; left:25px; top:107px; width:975px; height:400px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
<Table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" id="processandoTD" onclick="document.getElementById('processando').style.visibility='hidden'">
    </td>
  </tr>
</Table>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

    <center>
    <?      	
	  if(isset($funcao) && trim($funcao)!=""){

      $where = " ";
       if(isset($colunas1) && $colunas1!=""){
         $where = " and rh30_codreg in (".$colunas1.") ";
       }
			
	  	$porfuncao = true;

        $sql1 = "
				select 
           rh37_funcao,
           rh37_descr,
           rh37_vagas,
           count(rh01_regist) as ocupados,
           rh30_vinculo as r01_tpvinc
     from rhfuncao 
          inner join rhpessoalmov  on rhpessoalmov.rh02_funcao  = rhfuncao.rh37_funcao
		                              and rhpessoalmov.rh02_anousu  = $ano
		                              and rhpessoalmov.rh02_mesusu  = $mes
		                              and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")."
		      inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist 
          left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
          inner join rhregime  on rhregime.rh30_codreg  = rhpessoalmov.rh02_codreg
		                          and rhregime.rh30_instit  = rhpessoalmov.rh02_instit 
          inner join cgm       on cgm.z01_numcgm        = rhpessoal.rh01_numcgm 
          inner join rhlota    on rhlota.r70_codigo     = rhpessoalmov.rh02_lota
		                          and rhlota.r70_instit     = rhpessoalmov.rh02_instit 
          where rh37_funcao = $funcao
			      and rh37_instit = ".db_getsession("DB_instit")."
           and rh05_seqpes is null
					 $where
           group by 
               rh37_funcao,
               rh37_descr,
               rh30_vinculo,
               rh37_vagas
		";	
		 $result_funcao = pg_query($sql1);
        if(pg_numrows($result_funcao) == 0){
      	  db_msgbox("Cargo nÃ£o encontrado");
      	  echo "<script>location.href = 'pes3_consrhfuncao001.php'</script>";
        }else{
          db_fieldsmemory($result_funcao,0);
          $ocup = 0;
          for($i=0;$i<pg_numrows($result_funcao);$i++){
            db_fieldsmemory($result_funcao,$i);
            $ocup += $ocupados;
          }
          if($rh37_vagas != 0){
             $saldo = $rh37_vagas - $ocup;
          }
          $ocupados = $ocup;
        }
	  }else{
	  	$porfuncao = false;
        $result_funcoes = $clrhfuncao->sql_record($clrhfuncao->sql_query_file(null,db_getsession("DB_instit"),"rh37_funcao,
                                                                                                               rh37_descr,
                                                                                                               rh37_vagas",
                                                                                                              "rh37_funcao"));
        if($clrhfuncao->numrows == 0){
      	  db_msgbox("Nenhum cargo encontrado");
      	  echo "<script>location.href = 'pes3_consrhfuncao001.php'</script>";
        }
	  }

   $result_regime = $clrhregime->sql_record($clrhregime->sql_query_file(null, "rh30_vinculo","", " rh30_instit = ".db_getsession('DB_instit')." and rh30_codreg in (".@$colunas1.")"));
   $colunas = "";    
   $virgula = "";
   for($x = 0; $x < $clrhregime->numrows; $x ++) {
     db_fieldsmemory($result_regime, $x);
     $colunas .= $virgula.strtolower($rh30_vinculo);
     $virgula = ",";
   }
	?>
	    <form name='form1'>
        <table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td colspan="2"> 
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                   <? 
                   if($porfuncao == true){
                   ?>
                     <td nowrap class="tabcols" width="10%" align="right">
                       <strong style=\"color:blue\">
                         <?
                         db_ancora("$Lrh37_funcao","","3");
                         ?>
                       </strong>
                     </td>
                     <td class="tabcols" nowrap width="30%"> 
                       <?
                       db_input('rh37_funcao', 8, $Irh37_funcao, true, 'text', 3);
                       ?>
                       <?
                       db_input('rh37_descr', 30, $Irh37_descr, true, 'text', 3);
                       ?>
                     </td>
                     <td width="60%">                     
                       <table border="0" cellspacing="0" cellpadding="0">
                         <tr>
                           <td colspan="2"></td>
                         </tr>
                         <tr>
                           <td colspan="2"></td>
                         </tr>
                         <tr>
                           <td nowrap class="tabcols" align="right">
                             <strong class="links2">
                               VAGAS:  &nbsp;&nbsp;
                             </strong>
                           </td>
                           <td nowrap class="tabcols" align="right">
                             <strong  class="links2">
                               <?=$rh37_vagas?>
                             </strong>
                           </td>
                         </tr>
                         <tr>
                           <td class="tabcols" nowrap align="right">
                             <strong  class="links2">
                               OCUPADAS: &nbsp;&nbsp;
                             </strong>
                           </td>
                           <td nowrap class="tabcols" align="right">
                             <strong  class="links2">
                               <?=$ocupados?>
                             </strong>
                           </td>
                         </tr>
                         <tr>
                           <td class="tabcols" nowrap align="right">
                             <strong  class="links2">
                               SALDO: &nbsp;&nbsp;
                             </strong>
                           </td>
                           <td nowrap class="tabcols" align="right">
                             <strong  class="links2">
                               <?=$saldo?>
                             </strong>
                           </td>
                         </tr>
                         <tr>
                           <td class="tabcols" nowrap align="right" colspan="2">
                             <strong  class="links2">
                               <?
                               db_ancora("VER CARGOS","location.href = 'pes3_consrhfuncao002.php';","1");
                               ?>
                             </strong>
                           </td>
                         </tr>
                       </table>
                     </td>
                   <?
                   }else{
                   ?>
                     <td nowrap class="tabcols">
                       <BR>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                       <b>TODOS OS CARGOS</b>
                     </td>
                   <?
                   }
                   ?>
                </tr>
              </table>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"  height="90%"  valign="middle"> 
	          <table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="center">
                    <?
                    $qry = "";
                    $rog = "?";
                    if(isset($funcao) && trim($funcao)!=""){
                      $qry .= $rog."funcao=$funcao";
                      $rog = "&";
                    }
                    if(isset($ano) && trim($ano)!=""){
                      $qry .= $rog."ano=$ano";
                      $rog = "&";
                    }
                    if(isset($mes) && trim($mes)!=""){
                      $qry .= $rog."mes=$mes";
                      $rog = "&";
                    }
                    if(isset($colunas1) && trim($colunas1)!=""){
                      $qry .= $rog."colunas1=$colunas1";
                      $rog = "&";
                    }
                    //echo $qry;
                    ?> 
                    <iframe id="registros" height="95%" width="95%" name="registros" src="pes3_consrhfuncao021.php<?=$qry?>"></iframe>
                    <? 
                    if(isset($funcao) && trim($funcao)!=""){
                    ?>
                    <input type="hidden" name="funcao"  value="<?=$funcao?>">
                    <?
                    }
                    ?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="retornar" type="button" id="retornar" value="Nova Pesquisa" title="Inicio da Consulta" onclick="location.href='pes3_consrhfuncao001.php'"> 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <input name="pesquisar" type="submit" id="pesquisar"  title="Atualiza a Consulta" value="Atualizar">
              &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; 
	          <input name="imprimir" type="button" id="imprimir" value="Imprimir" title="Imprimir" onclick="js_relatorio();">
              <strong>
                &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                Período:
              </strong>
              &nbsp;&nbsp;
       	      <?
    	      db_input("ano",4,'',true,'text',4)
	          ?>
	          &nbsp;/&nbsp;
	          <?
    	      db_input("mes",2,'',true,'text',4);
    	      db_input("colunas1",2,'',true,'hidden',3);
	          ?>
            </td>   
           </tr>
        </table>
      </form>
  </center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>