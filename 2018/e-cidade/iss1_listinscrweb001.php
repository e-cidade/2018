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
include ("libs/db_utils.php");
include("classes/db_listainscrcab_classe.php");
include("classes/db_listainscr_classe.php");
include("classes/db_escrito_classe.php");
include("libs/smtp.class.php");

db_postmemory($HTTP_POST_VARS);

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$cllistainscrcab = new cl_listainscrcab;
$cllistainscr = new cl_listainscr;
$clescrito = new cl_escrito;

$sData = date("Y-m-d",db_getsession("DB_datausu"));

if(isset($acumula)&&!empty($acumula)){
 $matriz = explode("|",$acumula);

 for ($m = 0; $m < count($matriz)-1; $m++) {
  $rsListaInscrCab = $cllistainscrcab->sql_record($cllistainscrcab->sql_query("","*","","p11_codigo = $matriz[$m]"));
  $rsListaInscr = $cllistainscr->sql_record($cllistainscr->sql_query("","","*","","p12_codigo = $matriz[$m]"));
  db_inicio_transacao();
  $sqlerro = false;
  
  for ($y = 0; $y < $cllistainscr->numrows; $y++) {
     $oLista = db_utils::fieldsMemory($rsListaInscr,$y);
     
     $sqlVerificaEscrito  = "  select *                                   ";
     $sqlVerificaEscrito .= "    from escrito                             ";
     $sqlVerificaEscrito .= "   where q10_numcgm = {$oLista->p11_numcgm}  ";
     $sqlVerificaEscrito .= "     and q10_inscr  = {$oLista->p12_inscr}   "; 
    
     $rsVerificaEscrito = pg_query($sqlVerificaEscrito);
     $iVerificaEscrito  = pg_numrows($rsVerificaEscrito);
        
	 if (isset($oLista->p12_tipolanc) && $oLista->p12_tipolanc == 1) {
        $sqlEscrito  = "  select q10_inscr                         ";
        $sqlEscrito .= "    from escrito                           ";
        $sqlEscrito .= "   where q10_inscr = {$oLista->p12_inscr}  ";
                                            
        $rsEscrito = pg_query($sqlEscrito);
        $iEscrito  = pg_numrows($rsEscrito);
        
        if ($iEscrito > 0) {
           $sqlVerificaNull  = "  select q10_inscr,                        ";
           $sqlVerificaNull .= "         q10_numcgm,                       "; 
           $sqlVerificaNull .= "         q10_sequencial                    ";  
           $sqlVerificaNull .= "    from escrito                           "; 
           $sqlVerificaNull .= "   where q10_inscr  = {$oLista->p12_inscr} "; 
           $sqlVerificaNull .= "     and q10_dtfim is null                 ";
                                               
           $rsVerificaNull = pg_query($sqlVerificaNull);
           $iVerificaNull  = pg_numrows($rsVerificaNull);

           if ($iVerificaNull > 0) {
           	 $oVerifNullEscrito = db_utils::fieldsMemory($rsVerificaNull,0);

              if ($sqlerro == false) { 
			     $clescrito->q10_inscr      = $oVerifNullEscrito->q10_inscr;
				 $clescrito->q10_numcgm     = $oVerifNullEscrito->q10_numcgm;	
		         $clescrito->q10_dtfim      = $sData;
		         $clescrito->q10_sequencial = $oVerifNullEscrito->q10_sequencial;
		         $clescrito->alterar($oVerifNullEscrito->q10_sequencial);

		         if ($clescrito->erro_status == 0) {
		            $sqlerro=true;
		            $erro_msg = $clescrito->erro_msg;           
		         }        
			  }        	
           }

                if ($sqlerro == false) {
	    	      $clescrito->q10_inscr  = $oLista->p12_inscr;
		          $clescrito->q10_numcgm = $oLista->p11_numcgm;
	              $clescrito->q10_dtini  = $sData;
	              $clescrito->q10_dtfim  = null;
	              $clescrito->incluir(null);
	
	            if ($clescrito->erro_status == 0) {
	              $sqlerro=true;
	              $erro_msg = $clescrito->erro_msg;           
	            }            
	          }
           
        } else {      	        	
        	if ($sqlerro == false) {
	    	   $clescrito->q10_inscr  = $oLista->p12_inscr;
		       $clescrito->q10_numcgm = $oLista->p11_numcgm;
	           $clescrito->q10_dtini  = $sData;
	           $clescrito->q10_dtfim  = null;
	           $clescrito->incluir(null);
	
	          if ($clescrito->erro_status == 0) {
	              $sqlerro=true;
	              $erro_msg = $clescrito->erro_msg;           
	          }            
	        }
        }	  
       
	 } else if ( isset($oLista->p12_tipolanc) && $oLista->p12_tipolanc == 2 ) {
        if ($iVerificaEscrito > 0) {
		   $oVerEscrito = db_utils::fieldsMemory($rsVerificaEscrito,0);
		   	  
		   	  if ( $oVerEscrito->q10_dtfim == "") {		   	  	
				   if ($sqlerro == false) { 
				   	  $clescrito->q10_inscr      = $oLista->p12_inscr;
				      $clescrito->q10_numcgm     = $oVerEscrito->q10_numcgm;	
		              $clescrito->q10_dtfim      = $sData;
		              $clescrito->q10_sequencial = $oVerEscrito->q10_sequencial;
		              $clescrito->alterar($oVerEscrito->q10_sequencial);
		        
				        if ($clescrito->erro_status == 0) {
		                  $sqlerro=true;
		                  $erro_msg = $clescrito->erro_msg;           
		                }        
				   }
		   	  }      
        }
        
	 }	 
	  
	  if ($sqlerro == false) {	  	
		     $ip11_codigo = $matriz[$m];
		     $cllistainscrcab->p11_processado = 't';
		     $cllistainscrcab->p11_codigo     = $ip11_codigo;
		     $cllistainscrcab->alterar($ip11_codigo);
		     
		   if ($cllistainscrcab->erro_status == 0) {
		      $sqlerro=true;
		      $erro_msg = $cllistainscrcab->erro_msg;           
		   }   
	  }	  
  }
  
  db_fim_transacao($sqlerro);
  
  if ($sqlerro == true) {
  	db_msgbox($erro_msg);
  }

#### Início cria mensagem do email

if ($cllistainscrcab->erro_banco != "") {
  @$cllistainscrcab->erro();
  
} else {
	
//busca dados da instituição
$sqlBuscaDadosInst = " select * from db_config where codigo = ".db_getsession('DB_instit');
$rsBuscaDadosInst  = pg_query($sqlBuscaDadosInst);
$iBuscaDadosInst   = pg_numrows($rsBuscaDadosInst);

if ($iBuscaDadosInst > 0) {
	 $oBuscaDadosInst = db_utils::fieldsMemory($rsBuscaDadosInst,0);
}



//cria mensagem
$mensagem = "
".$oBuscaDadosInst->nomeinst."
Confirmação de Liberação de Lista - Prefeitura On-Line
------------------------------------------------------
Escritório: ".$oLista->z01_nome."
CPF/CNPJ..: ".$oLista->z01_cgccpf."
E-mail....: ".$oLista->z01_email."
Lista.....: ".$oLista->p11_codigo."

".date("d/m/Y - H:i:s")." - ".getenv("REMOTE_ADDR")."

Atenção,
Informamos que as inscrições abaixo foram liberadas para seu Escritório:
";
///// informar as incrições liberadas
$rsListaInscr = $cllistainscr->sql_record("select distinct on (p12_inscr) p12_inscr,
                                                                          z01_nome as nome, 
                                                                          z01_cgccpf as cnpj 
                                             from listainscr 
                                                  inner join empresa on p12_inscr = q02_inscr 
                                            where p12_codigo = {$oLista->p11_codigo} ");

for($y=0;$y<$cllistainscr->numrows;$y++){
 db_fieldsmemory($rsListaInscr,$y);
$mensagem .= "
------------------------------------------------------------------
 $p12_inscr - $nome - $cnpj
";
}
/////
$mensagem .= "
------------------------------------------------------------------
".$oBuscaDadosInst->url."
Não responda este e-mail, ele foi gerado automaticamente pelo Servidor.
----------------------------
";
  //encaminhar email
  $mailpref  = $oBuscaDadosInst->email;
  $z01_email = pg_result($rsListaInscrCab,0,"z01_email");
  $headers   = "Content-Type:text";
  $oMail = new Smtp();
  $oMail->Send($z01_email,'Lista Liberada',$mensagem,$headers); 
 }

##### Fim cria mensagem do email

}
 db_redireciona("iss1_listinscrweb001.php");
 exit();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_emite(){
  jan = window.open('iss2_listinscrweb002.php?fechadas='+document.form1.fechadas.value+'&processadas='+document.form1.processadas.value+'&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+'&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
<br>
<form name="form1" method="post" action="">
  <table  align="center" width="95%" border="1" bordercolor="#eaeaea" cellpadding="2" cellspacing="0">
      <tr bgcolor="#bababa">
        <td colspan="6" align="center"><b>Listas Aguardando Liberação:</b></td>
      </tr>
       <?
       //busca listas
       $result = $cllistainscrcab->sql_record($cllistainscrcab->sql_query("","*","","p11_fechado = 't' and p11_processado = 'f'"));
       if($cllistainscrcab->numrows==0){
        echo "<tr><td colspan='6'>Nenhuma lista aguardando liberação</td></tr>";
       }else{
        $cor1 = "#bababa";
        $cor2 = "#f3f3f3";
        $cor  = $cor1;
        ?>
        <tr>
         <td width="5%"><input type="button" value="M" name="marca" title="Marcar/Desmarcar" onclick="marcar('<?=$cllistainscrcab->numrows?>',this)"></td>
         <td width="10%"><b>Lista</b></td>
         <td width="15%"><b>CGM</b></td>
         <td width="15%"><b>Fone</b></td>
         <td><b>Nome/Razão Social</b></td>
         <td width="5%">&nbsp;</td>
        </tr>
        <?
        for($x=0;$x<$cllistainscrcab->numrows;$x++){
         db_fieldsmemory($result,$x);
         if($cor==$cor1)
          $cor = $cor2;
         else
          $cor = $cor1;
         ?>
         <tr bgcolor="<?=$cor?>">
          <td><input type="checkbox" name="lista" value="<?=$p11_codigo?>"></td>
          <td>&nbsp;<?=$p11_codigo?></td>
          <td>&nbsp;<?=$p11_numcgm?></td>
          <td>&nbsp;<?=$z01_telef?></td>
          <td>&nbsp;<?=$z01_nome?></td>
          <td><input type="button" name="imprimir" value="Imprimir" style="height:18;font-size:10" onclick="js_imprimir('<?=$p11_codigo?>')"></td>
         </tr>
         <?
        }
       ?>
      <tr>
        <td colspan="6" align = "center">
          <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_confirma(<?=$cllistainscrcab->numrows?>)" >
        </td>
      </tr>
     <?}?>
    </table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
 function js_imprimir(codigo){
   window.open('iss2_listinscrweb003.php?p12_codigo='+codigo,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
 function marcar(tudo,documento){
 if(tudo==1){
  if(documento.value=="D"){
    document.form1.lista.checked=false;
   }
   if(documento.value=="M"){
    document.form1.lista.checked=true;
   }
 }else{
  for(i=0;i<tudo;i++){
   if(documento.value=="D"){
    document.form1.lista[i].checked=false;
   }
   if(documento.value=="M"){
    document.form1.lista[i].checked=true;
   }
  }
 }
  if(document.form1.marca.value == "D"){
   document.form1.marca.value="M";
  }else{
   document.form1.marca.value="D";
  }
 }
function js_confirma(tudo){
 var armazena = '';
 var contador = 0;
 if(tudo==1&&document.form1.lista.checked==true){
  armazena = document.form1.lista.value+"|";
  contador++;
 }
 if(tudo>1){
  for(i=0;i<tudo;i++){
   contador++;
   if(document.form1.lista[i].checked==true){
    armazena += document.form1.lista[i].value+"|";
   }
  }
 }
 if(contador==0){
  alert("Marque uma lista para liberar.");
  return false;
 }
 if(armazena!=""){
  location.href="iss1_listinscrweb001.php?acumula="+armazena;
 }
}
</script>
</body>
</html>