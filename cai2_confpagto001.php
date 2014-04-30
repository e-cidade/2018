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
include("classes/db_cadban_classe.php");

db_postmemory($_POST);

$iInstit     = db_getsession("DB_instit");

$rotulocampo = new rotulocampo;
$clcadban    = new cl_cadban;

$rotulocampo->label("k15_codbco");
$rotulocampo->label("k15_conta");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio(){
  var F = document.form1;
	var datacompi = '';
	var datacompf = '';
  var datai     = '';
  var dataf     = '';

	if(F.datai_ano.value != '' && F.datai_mes.value != '' && F.datai_dia.value != '' ){
    var datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
	}
	if(F.dataf_ano.value != '' && F.dataf_mes.value != '' && F.dataf_dia.value != '' ){
    var dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;
	}
	var queryStr  = 'tipocampo='+F.dtfiltrar.value;
      queryStr +='&datai='+datai;
      queryStr +='&dataf='+dataf;
      queryStr +='&banco='+F.k15_codbco.value;

      if ( F.k15_codbco.value != 0 ) {
	      queryStr +='&conta='+F.k15_conta.value;
      }
      
      queryStr +='&imprimirsemdif='+F.imprimirsemdif.value;
      queryStr +='&difapartir='+F.difapartir.value;
      queryStr +='&parcunica='+F.parcunica.value;
      queryStr +='&totarquivo='+F.totarquivo.value;
      queryStr +='&ordem='+F.ordem.value;
      queryStr +='&quebrarpagina='+F.quebrarpagina.value;
      queryStr +='&imprime_origem='+F.imprime_origem.value;
      

  jan = window.open('cai2_confpagto002.php?'+queryStr,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
    	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="2" cellpadding="0">
            <tr> 
              <td colspan="2" nowrap>
							&nbsp;
							</td>
            </tr>
	          <tr>
        	    <td>
         	      <b>Data a filtrar : </b>
        	    </td>
         	    <td>
							  <?	
		  					$arrayDtfiltrar = array ("arq" => "Data do Arquivo", "pag" => "Data do Pagamento");
	  						db_select('dtfiltrar', $arrayDtfiltrar, true, 1);
   						  ?>      
              </td>
         	  </tr>
            <tr> 
              <td nowrap>
							  <b>Data Inicial:</b>
							</td>
              <td nowrap>
							<?
							  db_inputdata('datai',@$datai_dia,@$datai_mes,@$datai_ano,true,'text',1,"");
              ?>
              </td>
            </tr>
            <tr> 
              <td nowrap>
							  <b>Data Final:</b>
							</td>
              <td nowrap>
                <?
							  db_inputdata('dataf',@$dataf_dia,@$dataf_mes,@$dataf_ano,true,'text',1,"");								
								?>
              </td>
            </tr>
	          <tr>
        	    <td>
         	      <b>Mostrar valores sem diferenca : </b>
        	    </td>
         	    <td>
							  <?	
		  					$xy = array ("sim" => "Sim", "nao" => "Nao");
	  						db_select('imprimirsemdif', $xy, true, 1);
   						  ?>      
              </td>
         	  </tr>
            <tr>
        	    <td>
                <b>Mostrar parcela unica: </b>
        	    </td>
         	    <td>
           			<?
								$xy = array ("sim" => "Sim", "nao" => "Nao");
                db_select('parcunica', $xy, true, 1);
                ?>      
        	    </td>
            </tr>	  
            <tr>
        	    <td>
                <b>Totalizar por banco/arquivo: </b>
        	    </td>
         	    <td>
           			<?
								$xy = array ("sim" => "Sim", "nao" => "Nao");
                db_select('totarquivo', $xy, true, 1);
                ?>      
        	    </td>
            </tr>	  
            <tr>
        	    <td>
                <b>Ordem: </b>
        	    </td>
         	    <td>
           			<?
								$xy = array ("d" => "Diferença", "a" => "Nome", "n" => "Numpre/parcela");
                db_select('ordem', $xy, true, 1);
                ?>      
        	    </td>
            </tr>	  
	          <tr>
        	    <td>
         	      <b>Quebra pagina entre movimento: </b>
        	    </td>
         	    <td>
							  <?	
		  					$xy = array ("nao" => "Nao", "sim" => "Sim");
	  						db_select('quebrarpagina', $xy, true, 1);
   						  ?>      
              </td>
         	  </tr>
        	  <tr>
          	  <td>
              	<b>Considerar diferenca a partir de: </b>
        	    </td>
              <td>
							  <?
  							db_input('difapartir',10,"",true,'text',1,'');
  							?>
      	      </td>
            </tr> 
            <tr>
              <td nowrap title="<?=$Tk13_codbco?>">
							  <?=$Lk15_codbco?>
							</td>
              <td nowrap>
                <?

          				$sCamposBanco = "distinct cadban.k15_codbco,cgm.z01_nome";
          				$sSqlBanco    = $clcadban->sql_query_disarq("",$sCamposBanco,"cadban.k15_codbco"," k15_instit= $iInstit");
          			  $rsBanco      = $clcadban->sql_record($sSqlBanco); 
          				db_selectrecord("k15_codbco",$rsBanco,true,2,"","","","0","document.form1.submit()");
          				
         				?>
              </td>
            </tr>
            <?
      				if ( isset($k15_codbco) && $k15_codbco != 0 ) {
            ?>
            <tr>
              <td nowrap title="<?=$Tk15_conta?>">
                <?=$Lk15_conta?>
              </td>
              <td nowrap>
                <?
                
                  $sCamposConta = "distinct cadban.k15_conta,cadban.k15_conta ";
                  $sWhereConta  = "     cadban.k15_instit = {$iInstit}        ";
                  $sWhereConta .= " and cadban.k15_codbco = {$k15_codbco}     ";
                  $sSqlConta    = $clcadban->sql_query_disarq("",$sCamposConta,"cadban.k15_conta",$sWhereConta);
                  $rsConta      = $clcadban->sql_record($sSqlConta); 
                  db_selectrecord("k15_conta",$rsConta,true,2,"","","",array("0","Todos"),"",1);
                  
                ?>
              </td>
            </tr>            
            <?            
          		}
          	?>			
            <tr>
              <td nowrap >
                <b>Numpre Origem:</b>
              </td>
              <td nowrap>
                <?
                  $aNumpreOrigem = array("n"=>"Não",
                                         "s"=>"Sim");
      
                  db_select('imprime_origem',$aNumpreOrigem,true,1,'');
                ?>
              </td>
            </tr>            
            <tr>
              <td>&nbsp;</td><td>&nbsp;</td>
            </tr>
            <tr> 
              <td align="center" colspan="2" nowrap> <?// echo "<br> $xx";?>
							  <input name="relatorio" type="button" id="relatorio" onClick="js_relatorio()" value="Relatório">
      	      </td>
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