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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_lab_laboratorio_classe.php");
include("classes/db_lab_labresp_classe.php");
include("classes/db_lab_turnohora_classe.php");
include("classes/db_lab_labdepart_classe.php");
include("classes/db_lab_labcgm_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$cllab_laboratorio = new cl_lab_laboratorio;
$cllab_labresp = new cl_lab_labresp;
$cllab_turnohora = new cl_lab_turnohora;
$cllab_labdepart = new cl_lab_labdepart;
$cllab_labcgm = new cl_lab_labcgm;
$db_opcao = 1;
$db_botao = true;
$iBloqueioTipo=1;
if(isset($incluir)){
  db_inicio_transacao();
     
     //Dados do laboratorio
     $cllab_laboratorio->incluir(null);
     $iLaboratorio = $cllab_laboratorio->la02_i_codigo;
     
     if ($cllab_laboratorio->erro_status != "0") {
         
         if($la02_i_tipo==1){
             
             $cllab_labdepart->la03_i_departamento=$la03_i_departamento;
             $cllab_labdepart->la03_i_laboratorio=$iLaboratorio;
             $cllab_labdepart->incluir(null);
             if ($cllab_labdepart->erro_status == "0"){
                 
                 $cllab_laboratorio->erro_status=0;
                 $cllab_laboratorio->erro_sql   = $cllab_labdepart->erro_sql;
                 $cllab_laboratorio->erro_campo = $cllab_labdepart->erro_campo;
                 $cllab_laboratorio->erro_banco = $cllab_labdepart->erro_banco;
                 $cllab_laboratorio->erro_msg   = $cllab_labdepart->erro_msg;

            }
        
         }else{
        
             $cllab_labcgm->la04_i_cgm=$la04_i_cgm;
             $cllab_labcgm->la04_i_laboratorio=$iLaboratorio;
             $cllab_labcgm->incluir(null);
             if ($cllab_labcgm->erro_status == "0"){

                 $cllab_laboratorio->erro_status=0;
                 $cllab_laboratorio->erro_sql   = $cllab_labcgm->erro_sql;
                 $cllab_laboratorio->erro_campo = $cllab_labcgm->erro_campo;
                 $cllab_laboratorio->erro_banco = $cllab_labcgm->erro_banco;
                 $cllab_laboratorio->erro_msg   = $cllab_labcgm->erro_msg;

             }
         }
     }   
    
     /*
     if ($cllab_laboratorio->erro_status != "0") {
            
         //vai receber uma string com turnos separados por '|' e em cada turno o codigo do turno e horaraio vai ser separado por ',' 
         // EX:   '1,08:00,12:00|2,13:00,18:00'
         $cllab_turnohora->la07_i_laboratorio= $iLaboratorio;
         $aTurnos=explode("|",$str_horarios);

         for ($iX=0; $iX < count($aTurnos); $iX++){

                $aVet=explode(",",$aTurnos[$iX]);
                $cllab_turnohora->la07_i_turno=$aVet[0];
                $cllab_turnohora->la07_c_inicio=$aVet[1];
                $cllab_turnohora->la07_c_fim=$aVet[2];
                if($cllab_turnohora->erro_status!="0"){
                   
                   $cllab_turnohora->incluir(null);
                   if ($cllab_turnohora->erro_status == "0"){
                      
                      $cllab_laboratorio->erro_status=0;
                      $cllab_laboratorio->erro_sql = $cllab_turnohora->erro_sql;
                      $cllab_laboratorio->erro_campo = $cllab_turnohora->erro_campo;
                      $cllab_laboratorio->erro_banco = $cllab_turnohora->erro_banco;
                      $cllab_laboratorio->erro_msg   = $cllab_turnohora->erro_msg;

                   }
                   
                }
        
         }

     }
     */
  db_fim_transacao();
}
?>
<h:tml>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<!--<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>-->
<br><br>
<center>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <fieldset><b><legend><?=converteCodificacao("Laboratório")?></legend></b>
    <center>
	<?
	include("forms/db_frmlab_laboratorio.php");
	?>
    </center>
    </fieldset>
    </center>
	</td>
  </tr>
</table>
<center>
<?
//db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","la02_i_tipo",true,1,"la02_i_tipo",true);
</script>
<?
if(isset($incluir)){
  if($cllab_laboratorio->erro_status=="0"){
    $cllab_laboratorio->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if($cllab_laboratorio->erro_campo!=""){
      echo "<script> document.form1.".$cllab_laboratorio->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_laboratorio->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_laboratorio->erro(true,false);
    db_redireciona("lab1_lab_laboratorio002?chavepesquisa=".$cllab_laboratorio->la02_i_codigo);
  }
}
?>