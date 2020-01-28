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

include("classes/db_empagetipo_classe.php");
include("classes/db_empage_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empagegera_classe.php");
include("classes/db_empageconf_classe.php");
$clempage = new cl_empage;
$clempagetipo = new cl_empagetipo;
$clempagemov = new cl_empagemov;
$clempagegera = new cl_empagegera;
$clempageconf = new cl_empageconf;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

if(isset($e80_data_ano)){
  $data = "$e80_data_ano-$e80_data_mes-$e80_data_dia";
}

  $sqlerro=false;
if(isset($atualizar)){
  
  db_inicio_transacao();
 
  
  //-----------------------------------
  //rotina que inclui os movimentos
  if($sqlerro==false && $movs !=''){
     
    $clempagegera->e87_descgera = "Arquivo gerado";
    $clempagegera->e87_data     = date("Y-m-d",db_getsession("DB_datausu"));
    $clempagegera->e87_hora     = db_hora();
    $clempagegera->incluir(null);
    $erro_msg = $clempagegera->erro_msg;
    if($clempagegera->erro_status==0){
      $sqlerro = true;
    }else{
      $gera = $clempagegera->e87_codgera;
    } 
    $arr =  split("XX",$movs);
    $tot_valor ='';
    for($i=0; $i<count($arr); $i++ ){
       $mov = $arr[$i];  
       //-----------------------------------
       //inclui na tabela empagemov
       if($sqlerro==false){
	 $clempageconf->e86_codmov = $mov;
	 $clempageconf->e86_data   = date("Y-m-d",db_getsession("DB_datausu"));
	 $clempageconf->e86_cheque = 'cheque';
	 $clempageconf->e86_codgera = $gera;
	 $clempageconf->incluir($mov);
	 $erro_msg = $clempageconf->erro_msg;
	 if($clempageconf->erro_status==0){
	       $sqlerro = true;
	 }     
       }  
       //-----------------------------------

       //-----------------------------  
       //rotina para calcular o valor total dos movimentos
        $result = $clempagemov->sql_record($clempagemov->sql_query($mov,"e81_valor as valor"));
	db_fieldsmemory($result,0);
	$tot_valor += $valor;

       
    }	

    $result = $clempagetipo->sql_record($clempagetipo->sql_query($e83_codtipo));
    if($clempagetipo->numrows>0){
      db_fieldsmemory($result,0);
    }
  }
  db_fim_transacao($sqlerro);
}

if(isset($entrar) || isset($nova)){
  db_inicio_transacao();
  $sqlerro=false;
  
  if(empty($nova)){
    $result01 = $clempage->sql_record($clempage->sql_query_file(null,'e80_codage','',"e80_data='$data' and e80_instit = " . db_getsession("DB_instit")));
    $numrows01 = $clempage->numrows;
  } 

  if(isset($nova) || (isset($numrows01) && $numrows01 == 0) ){
    $clempage->e80_data = $data;
		$clempage->e80_instit = db_getsession("DB_instit");
    $clempage->incluir(null);
    if($clempage->erro_status==0){
         $sqlerro = true;
    }else{
      $e80_codage = $clempage->e80_codage;
    }	 
  }else{
    $jatem =  true;
  }
  db_fim_transacao($sqlerro);
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
   <?
   $clrotulo = new rotulocampo;
   $clrotulo->label("e80_data");
   
//sempre que ja existir agenda entra nesta opcao  
  if(isset($e80_codage)){  
	include("forms/db_frmempageconf.php");

//pela primeira vez que entrar neste arquivo, entra nesta opcao para digitar a data da agenda 
  }else if(empty($entrar) && empty($e80_codage)){?>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr><br>
              <td nowrap title="<?=@$Te80_data?>">
                <?=$Le80_data?>
              </td>	
              <td>	
              <?
                db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',1);
              ?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center"><input name="entrar" type="submit" id="boletim"  value="Entrar">
	      </td>
              <td>
            </tr>
          </table>
        </form>
	
<?  
//entra nesta opcao para escolher uma das agendas ou então selecionar uma jah existente
   }else if(isset($jatem)){?>
        <form name="form1" method="post" action="">
         <table>
	   <tr>
	     <td colspan='2' align='center'><b>Já existe agenda para esta data.</b></td>
	   </tr>
	   <tr>
	      <td nowrap title="<?=@$Te80_data?>" align='right'>
	      <?=$Le80_data?>
	      </td>	
	      <td>	
	       <?
		 db_inputdata('e80_data',@$e80_data_dia,@$e80_data_mes,@$e80_data_ano,true,'text',3);
	       ?>
	      </td>
	   </tr>
           <tr>
	     <td class='bordas' align='right'><b>Agendas:</b></td>
<?
	  for($i=0; $i<$numrows01; $i++){
	    db_fieldsmemory($result01,$i);
            $arr[$e80_codage] = $e80_codage;
	  }
?>
             <td class='bordas'><small><?=db_select("e80_codage",$arr,true,1)?></small></td>
            </tr>
            <tr>
              <td colspan="2" align="center">
	      	<input name="alterar" type="submit" value="Alterar selecionda">
	      	<input name="nova" type="submit"    value="Incluir nova">
	      </td>	
            </tr>

	 </table>
       </form>	 
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
<?
if($sqlerro == true && isset($atualizar)){
  db_msgbox($erro_msg);
}

if((isset($e83_codmod) && $e83_codmod == 1) || isset($reemite)){

   if(isset($reemite)){
     $movs = $reemite;
     echo "<script>document.form1.entrar_codord.click();</script>";
   }

   echo ($clempagemov->sql_query($movs,"*"));
   $result = $clempagemov->sql_record($clempagemov->sql_query($movs,"*"));
   db_fieldsmemory($result,0);

   // pesquisa codigo da conta

   $sql = "select c63_banco 
           from conplanoreduz
	        inner join conplanoconta on c61_codcon = c63_codcon and c61_anousu=c63_anousu
	   where c61_anousu = ".db_getsession("DB_anousu")." and c61_reduz = $e83_conta";
   
   $result = pg_exec($sql);
  
   $codbco = pg_result($result,0,0);
  
   //$fd = fsockopen(db_getsession('DB_ip'),4444);
   $fd = fsockopen('192.168.0.111',4444);
   // grava a autenticacao
   fputs($fd,chr(27).chr(160)."$z01_nome\n");
   fputs($fd,chr(27).chr(161)."$munic\n");
   fputs($fd,chr(27).chr(162)."$codbco\n");
   $tot_valor = trim(db_formatar($tot_valor,'p','',2));
   fputs($fd,chr(27).chr(163)."$e81_valor\n");
   fputs($fd,chr(27).chr(164)."$dtin_dia-$dtin_mes-".substr($dtin_ano,2,2)."\n");
   fputs($fd,chr(27).chr(176));
   // fecha a conecção
   fclose($fd);

   echo "<script>\n
         retorna = confirm('Emite Novo cheque?');\n
         if(retorna == true){\n
           obj=document.createElement('input');\n
           obj.setAttribute('name','reemite');\n
           obj.setAttribute('type','hidden');\n
           obj.setAttribute('value','$movs');\n
           document.form1.appendChild(obj);\n
           
	   obj=document.createElement('input');\n
           obj.setAttribute('name','tot_valor');\n
           obj.setAttribute('type','hidden');\n
           obj.setAttribute('value','$tot_valor');\n
           document.form1.appendChild(obj);\n

	   document.form1.submit();\n
	 }\n
         </script>";
};
?>