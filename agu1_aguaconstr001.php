<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
  include("libs/db_utils.php");
  include("classes/db_aguaconstr_classe.php");
  include("classes/db_aguaconstrcar_classe.php");
  include("classes/db_aguabase_classe.php");
  include("dbforms/db_funcoes.php");
  
  parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
  
  $oPost = db_utils::postMemory($_POST);

  $claguaconstr 	 = new cl_aguaconstr;
  $claguaconstrcar = new cl_aguaconstrcar;
  $claguabase 	 = new cl_aguabase;

  $db_opcao = 22;
  $db_botao = false;
  $lErro    = false;

  if (isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)) {
    $sqlerro = false;
  }

  if (isset($oPost->incluir)) {
  
    if ($sqlerro == false) {
  	
    db_inicio_transacao();
    
    $rsConsultaTipo = $claguaconstr->sql_record($claguaconstr->sql_query_file(null,
                                                                              "*", 
                                                                              null,
                                                                              " x11_matric = {$oPost->x11_matric} and x11_tipo = 'P'"));
    
    if ( $oPost->x11_tipo == "S" ) {
	  if ( $claguaconstr->numrows == 0 ) {
	  	db_msgbox("Não existe nenhuma construção principal definida!");
	    $sqlerro = true;
	  	$lErro   = true;
	  }
    } else if ( $oPost->x11_tipo == "P" ) {
	  if ( $claguaconstr->numrows > 0 ) {
	  	db_msgbox("Já extiste uma função principal definida!");
	  	$sqlerro = true;
	  	$lErro   = true;
	  }
    }
    
    if ( !$sqlerro ) {
      
      $claguaconstr->x11_codconstr   = $oPost->x11_codconstr; 
      $claguaconstr->x11_matric		 = $oPost->x11_matric;
      $claguaconstr->x11_area	     = $oPost->x11_area;
      $claguaconstr->x11_pavimento	 = $oPost->x11_pavimento;
      $claguaconstr->x11_numero 	 = $oPost->x11_numero;
      $claguaconstr->x11_qtdfamilia  = $oPost->x11_qtdfamilia;
      $claguaconstr->x11_qtdpessoas  = $oPost->x11_qtdpessoas;
      $claguaconstr->x11_complemento = $oPost->x11_complemento;
      $claguaconstr->x11_tipo 		 = $oPost->x11_tipo;
    	
      $claguaconstr->incluir($oPost->x11_codconstr);
      $erro_msg = $claguaconstr->erro_msg;
      if($claguaconstr->erro_status==0){
        $sqlerro = true;
      }

      $matriz = split("X", $oPost->caracteristica);
      for($i=0; $i<sizeof($matriz); $i++){
        $x12_codigo = $matriz[$i];
        if($x12_codigo != ""){
          $claguaconstrcar->incluir($x12_codigo, $claguaconstr->x11_codconstr);
          if($claguaconstrcar->erro_status==0){
            $sqlerro=true;
          }
        }
      }  
    }
    
    db_fim_transacao($sqlerro);
  }
}else if(isset($oPost->alterar)){
  if($sqlerro==false){
    db_inicio_transacao();
    
    
    if ($oPost->x11_tipo == "P"){
      
      $rsConsultaTipo = $claguaconstr->sql_record($claguaconstr->sql_query_file(null,"*",null," x11_matric = {$oPost->x11_matric} and x11_tipo = 'P'"));
      $iNroLinhas 	  = $claguaconstr->numrows;
      
      if ( $iNroLinhas > 0 ) {
      	
      	for($i=0; $i < $iNroLinhas; $i++){
		  $oTipoConstr = db_utils::fieldsMemory($rsConsultaTipo,$i);
      	  $claguaconstr->x11_codconstr   = $oTipoConstr->x11_codconstr; 
          $claguaconstr->x11_matric	     = $oTipoConstr->x11_matric;
      	  $claguaconstr->x11_area	     = $oTipoConstr->x11_area;
      	  $claguaconstr->x11_pavimento   = $oTipoConstr->x11_pavimento;
      	  $claguaconstr->x11_numero 	 = $oTipoConstr->x11_numero;
      	  $claguaconstr->x11_qtdfamilia  = $oTipoConstr->x11_qtdfamilia;
      	  $claguaconstr->x11_qtdpessoas  = $oTipoConstr->x11_qtdpessoas;
      	  $claguaconstr->x11_complemento = $oTipoConstr->x11_complemento;
		  $claguaconstr->x11_tipo		 = "S";
      	  $claguaconstr->alterar($oTipoConstr->x11_codconstr);
      	}
	  }
	      	
    } else if ( $oPost->x11_tipo == "S" ){
      $rsConsultaTipo = $claguaconstr->sql_record($claguaconstr->sql_query_file($oPost->x11_codconstr,"*",null," x11_tipo = 'P'"));
      if ( $claguaconstr->numrows > 0 ) {
        $rsConsultaTipo = $claguaconstr->sql_record($claguaconstr->sql_query_file(null,"*",null," x11_matric = {$oPost->x11_matric} and x11_codconstr != {$oPost->x11_codconstr}"));
		$oTipoConstr    = db_utils::fieldsMemory($rsConsultaTipo,0);
		
      	$claguaconstr->x11_codconstr   = $oTipoConstr->x11_codconstr; 
        $claguaconstr->x11_matric	   = $oTipoConstr->x11_matric;
      	$claguaconstr->x11_area	       = $oTipoConstr->x11_area;
      	$claguaconstr->x11_pavimento   = $oTipoConstr->x11_pavimento;
      	$claguaconstr->x11_numero 	   = $oTipoConstr->x11_numero;
      	$claguaconstr->x11_qtdfamilia  = $oTipoConstr->x11_qtdfamilia;
      	$claguaconstr->x11_qtdpessoas  = $oTipoConstr->x11_qtdpessoas;
      	$claguaconstr->x11_complemento = $oTipoConstr->x11_complemento;		
		$claguaconstr->x11_tipo 	   = "P";
		$claguaconstr->alterar($oTipoConstr->x11_codconstr); 	
	  }    	
    }
    
    if ( !$sqlerro ){
    	
      $claguaconstr->x11_codconstr   = $oPost->x11_codconstr; 
      $claguaconstr->x11_matric		 = $oPost->x11_matric;
      $claguaconstr->x11_area	     = $oPost->x11_area;
      $claguaconstr->x11_pavimento	 = $oPost->x11_pavimento;
      $claguaconstr->x11_numero 	 = $oPost->x11_numero;
      $claguaconstr->x11_qtdfamilia  = $oPost->x11_qtdfamilia;
      $claguaconstr->x11_qtdpessoas  = $oPost->x11_qtdpessoas;
      $claguaconstr->x11_complemento = $oPost->x11_complemento;
      $claguaconstr->x11_tipo 		 = $oPost->x11_tipo;
      
      $claguaconstr->alterar($oPost->x11_codconstr);
      
      $erro_msg = $claguaconstr->erro_msg;
      if($claguaconstr->erro_status==0){
        $sqlerro=true;
      }
    
      $claguaconstrcar->excluir(null,$oPost->x11_codconstr);
      if($claguaconstrcar->erro_status==0){
        $sqlerro=true;
      }

      $matriz = split("X", $oPost->caracteristica);
      for($i=0; $i<sizeof($matriz); $i++){
        $x12_codigo = $matriz[$i];
        if($x12_codigo != ""){
          $claguaconstrcar->incluir($x12_codigo, $oPost->x11_codconstr);
          if($claguaconstrcar->erro_status==0){
            $sqlerro=true;
          }
        }
      }  
    }
    db_fim_transacao($sqlerro);
  }
  
}else if(isset($oPost->excluir)){
	
  if($sqlerro==false){
    db_inicio_transacao();
    
    $rsConsultaTipo = $claguaconstr->sql_record($claguaconstr->sql_query_file(null,"*",null," x11_codconstr = ".$oPost->x11_codconstr." and x11_tipo = 'P'"));
    if ( $claguaconstr->numrows > 0 ) {
      $rsConsultaTipo = $claguaconstr->sql_record($claguaconstr->sql_query_file(null,"*",null," x11_matric = {$oPost->x11_matric} and x11_codconstr != {$oPost->x11_codconstr}"));
      if ( $claguaconstr->numrows > 0 ) {
  	    $oTipoConstr    = db_utils::fieldsMemory($rsConsultaTipo,0);
 	    $claguaconstr->x11_codconstr   = $oTipoConstr->x11_codconstr; 
        $claguaconstr->x11_matric	   = $oTipoConstr->x11_matric;
        $claguaconstr->x11_area	       = $oTipoConstr->x11_area;
        $claguaconstr->x11_pavimento   = $oTipoConstr->x11_pavimento;
        $claguaconstr->x11_numero 	   = $oTipoConstr->x11_numero;
        $claguaconstr->x11_qtdfamilia  = $oTipoConstr->x11_qtdfamilia;
        $claguaconstr->x11_qtdpessoas  = $oTipoConstr->x11_qtdpessoas;
        $claguaconstr->x11_complemento = $oTipoConstr->x11_complemento;		
	    $claguaconstr->x11_tipo 	   = "P";
	    $claguaconstr->alterar($oTipoConstr->x11_codconstr); 	
	  }
	} 
    
    $claguaconstrcar->excluir(null,$oPost->x11_codconstr);
    if($claguaconstrcar->erro_status==0){
      $sqlerro=true;
    }
	
    $claguaconstr->excluir($oPost->x11_codconstr);
    $erro_msg = $claguaconstr->erro_msg;
    if($claguaconstr->erro_status==0){
      $sqlerro=true;
    }


    db_fim_transacao($sqlerro);
  }
  
}else if(isset($opcao)){
	
   $result = $claguaconstr->sql_record($claguaconstr->sql_query($oPost->x11_codconstr));
   if($result!=false && $claguaconstr->numrows>0){
     db_fieldsmemory($result,0);
   }
   
   // Busca Caracteristicas 
   $result = $claguaconstrcar->sql_record($claguaconstrcar->sql_query(null,$oPost->x11_codconstr));
   $caracteristica = null;
   $car="X";
   for($i=0; $i<$claguaconstrcar->numrows; $i++){
     db_fieldsmemory($result, $i);
     $caracteristica .= $car.$x12_codigo ;
     $car="X";
   }
   $caracteristica .= $car;

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
    <center>
    <table width="790" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
          <center>
	          <?
	            include("forms/db_frmaguaconstr.php");
	          ?>
          </center>
	      </td>
      </tr>
    </table>
    </center>
  </body>
</html>
<?
if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){
  
    if (!$lErro){
      echo "<script>document.form1.caracteristica.value = '';</script>";
      db_msgbox($erro_msg);
      if($claguaconstr->erro_campo!=""){
        echo "<script> document.form1.".$claguaconstr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$claguaconstr->erro_campo.".focus();</script>";
      }
    } 
}
?>