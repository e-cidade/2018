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

//require("libs/db_stdlib.php");
require("fpdf151/scpdf.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");

include("classes/db_debcontapedido_classe.php");
include("classes/db_debcontaarquivo_classe.php");
include("classes/db_debcontaarquivotipo_classe.php");
include("classes/db_db_config_classe.php");

$cldebcontapedido = new cl_debcontapedido;
$cldebcontaarquivo = new cl_debcontaarquivo;
$cldebcontaarquivotipo = new cl_debcontaarquivotipo;
$cldb_config = new cl_db_config;

$clrotulo = new rotulocampo;
$clrotulo->label("d72_data");

db_postmemory($HTTP_POST_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_geraarquivo() {
  js_OpenJanelaIframe('top.corpo','db_iframe','cai2_geradebconta002.php?anovenc='+document.form1.anovenc.value+'&mesvenc='+document.form1.mesvenc.value+'&d72_data_ano='+document.form1.d72_data_ano.value+'&d72_data_mes='+document.form1.d72_data_mes.value+'&d72_data_dia='+document.form1.d72_data_dia.value+'&tipodebito='+document.form1.tipodebito.value+'&banco='+document.form1.banco.value+'&numpar='+document.form1.numpar.value+'&formatoArq='+document.form1.formatoArq.value+'&linhasBranco='+document.form1.linhasBranco.value,'Gera Remessa para Débito em Conta',true,20);
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
   <form name="form1" action="" method="post" >
      <table border="0" cellpadding="0" cellspacing="0">


  <br><br><br>
  
  </tr>
	<tr>
          <td height="25">&nbsp;</td>
	</tr>

          <tr>
            <td nowrap title="<?=@$Td72_data?>">Data para débito em Conta Corrente:</td>	
            <td>	
              <?
                if(empty($liberar) && empty($deslibera) && empty($d72_data_dia) ){
	                $d72_data_dia = date("d", db_getsession("DB_datausu") );
	                $d72_data_mes = date("m", db_getsession("DB_datausu") );
	                $d72_data_ano = date("Y", db_getsession("DB_datausu") );
                }
                db_inputdata('d72_data',@$d72_data_dia,@$d72_data_mes,@$d72_data_ano,true,'text',1,"")
              ?>
            </td>
          </tr>

          <tr> 
	          <td height="25">Tipo de débito:</td>
            <td height="25">
              <?
                $resulttipodebito = pg_query("
                  select distinct 
                         d66_arretipo, 
                         k00_descr 
                    from debcontapedidotipo
                         inner join arretipo on k00_tipo = d66_arretipo
                   where   k00_instit = ".db_getsession("DB_instit")."    
									order by d66_arretipo desc");
                 
                if (pg_numrows($resulttipodebito) > 0) {
                  ?>
                  <select name="tipodebito" onChange='document.form1.submit()'>
                  <?
                  for ($i = 0; $i < pg_numrows($resulttipodebito); $i ++) {
                    db_fieldsmemory($resulttipodebito, $i);
                    ?>
                    <option value='<?=$d66_arretipo?>'><?=$k00_descr?></option>
                    <?
                  }
                  ?>
		              </select>
		              <?
                }
              ?>
            </td>
          </tr>
          
          <tr> 
	          <td height="25">Parcela:</td>
            <td height="25">
             <?
               //if (pg_numrows($resulttipodebito) == 1) {
                 db_fieldsmemory($resulttipodebito, 0);

                 $sqlnumpar = "
                     select distinct 
                            k00_numpar 
                       from debcontapedido 
                            inner join debcontapedidotipo   on d63_codigo            = d66_codigo 
                            inner join debcontapedidomatric on d68_codigo            = d63_codigo 
                            inner join arrematric           on d68_matric            = k00_matric 
                            inner join arrecad              on arrematric.k00_numpre = arrecad.k00_numpre 
                                                            and k00_tipo             = d66_arretipo 
                      where d63_status = 2 
                        and d66_arretipo = $d66_arretipo
                        and d63_instit   = ".db_getsession("DB_instit")."
                   order by k00_numpar";
                 $resultnumpar = pg_query($sqlnumpar);

                 if (pg_numrows($resultnumpar) > 0) {
                   ?>
                   <select name="numpar">
                   <?
                   for ($i = 0; $i < pg_numrows($resultnumpar); $i ++) {
                     db_fieldsmemory($resultnumpar, $i);
                     ?>
                     <option value='<?=$k00_numpar?>'><?=$k00_numpar?></option>
		                 <?
	                 }
                   ?>
                   </select>
                   <?
                 }
               //}
               
             ?>
            </td>
          </tr>
         
          <tr> 
	          <td height="25"><strong>Exercicio do Vencimento:</strong></td>
            <td height="25">
              <select name="anovenc">
              <?
                $anousu = db_getsession("DB_anousu");
                for($x=$anousu-1; $x<=$anousu+2; $x++) {
									$selected=($x==$anousu)?"selected":"";
                  ?>
                  <option value='<?=$x?>' <?=$selected?>><?=$x?></option>
                  <?
                }
              ?>
              </select>
            </td>
          </tr>

          <tr> 
	          <td height="25"><strong>Mes do Vencimento:</strong></td>
            <td height="25">
             <?
               $meses = array( "01" => "Janeiro",
                               "02" => "Fevereiro",
                               "03" => "Marco",
                               "04" => "Abril",
                               "05" => "Maio",
                               "06" => "Junho",
                               "07" => "Julho",
                               "08" => "Agosto",
                               "09" => "Setembro",
                               "10" => "Outubro",
                               "11" => "Novembro",
                               "12" => "Dezembro" );
               $mesvenc = date("m", db_getsession("DB_datausu") );
               db_select("mesvenc", $meses, true, 1);
               
             ?>
            </td>
          </tr>

          <tr> 
            <td height="25">Banco:</td>
            <td height="25">
              <?
	              $resultbanco = pg_query("
                  select distinct 
                         d62_banco,
                         nomebco
                    from debcontaparam
                         inner join bancos on codbco = d62_banco
                   where d62_instituicao =  ".db_getsession("DB_instit"));

                if (pg_numrows($resultbanco) > 0) {
	                /*?>
	                <select name="banco">
	                <?
                  for ($i = 0; $i < pg_numrows($resultbanco); $i ++) {
                    db_fieldsmemory($resultbanco, $i);
                    ?>
                    <option value='<?=$d62_banco?>'><?=$d62_banco?></option>
                    <?
	                }
	                ?>
	                </select>
	                <?*/
                  db_selectrecord("banco", $resultbanco, true, 1);
	              } else {
                  db_msgbox('Favor configurar os parâmetros do Debito em Conta!');
                }
             ?>
           </td>
         </tr>
		 
		 <tr> 
	          <td height="25"><strong> Formato Arquivo:</strong></td>
            <td height="25">
             <?
               $formato = array( "U" => "UNIX",
                                 "D" => "DOS");
               db_select("formatoArq", $formato, true, 1);
               
             ?>
            </td>
          </tr>
          <tr> 
	        <td height="25"><strong>Linhas em Branco Final Arquivo:</strong></td>
            <td height="25">
            	<? 
				if(!isset($linhasBranco) || $linhasBranco==""){
				  $linhasBranco = 1;
				}
				db_input("linhasBranco",10,1,true,'text',1,"") 
				?>
            
            </td>
          </tr>
          <tr> 
            <td height="25">&nbsp;</td>
            <td height="25"> <input name="processar"  type="button" id="processar" value="Processar" onclick="js_geraarquivo();"> 
            </td>
          </tr>


          <!--<tr> 
	          <td></td>
	          <td>
	            <input name="termometro" style='background: transparent' id="termometro" type="text" value="" size=50>
	          </td>
          </tr> -->
          <tr > 
            <td colspan="2" height="25" align="center" colspan="2" > <input name="processando" id="processando" style='color:red;border:none;visibility:hidden' type="button"  readonly value="Processando. Aguarde..."> 
            </td>
          </tr>
        </table>
      </form>
     </td>
  </tr>
</table>
<? 

db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?

if (@$erro == true) {
  echo "<script>alert('$descricao_erro');</script>";
}


?>