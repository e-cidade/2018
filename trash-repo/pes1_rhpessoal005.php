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
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesrescisao_classe.php");
include("classes/db_rhpesfgts_classe.php");
include("classes/db_rhpesdoc_classe.php");
include("classes/db_rhpessoalmov_classe.php");
include("classes/db_rhraca_classe.php");
include("classes/db_rhinstrucao_classe.php");
include("classes/db_rhestcivil_classe.php");
include("classes/db_rhnacionalidade_classe.php");
include("classes/db_cfpess_classe.php");
include("classes/db_rhfotos_classe.php");
include("classes/db_rhpesorigem_classe.php");
include("libs/db_libpessoal.php");

$clrhpessoal = new cl_rhpessoal;
$clrhpesrescisao = new cl_rhpesrescisao;
$clrhpesfgts = new cl_rhpesfgts;
$clrhpesdoc = new cl_rhpesdoc;
$clrhpessoalmov = new cl_rhpessoalmov;
$clrhraca = new cl_rhraca;
$clrhinstrucao = new cl_rhinstrucao;
$clrhestcivil = new cl_rhestcivil;
$clrhnacionalidade = new cl_rhnacionalidade;
$clcfpess = new cl_cfpess;
$clrhfotos = new cl_rhfotos;
$clrhpesorigem = new cl_rhpesorigem;
$rhimp = '';
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$db_opcao = 22;
$db_botao = false;

$ano = db_anofolha();
$mes = db_mesfolha();

if(isset($alterar)){
  $sqlerro=false;
  db_inicio_transacao();
  if(trim($rh01_anoche) == "" && $rh01_nacion != 10){
  	$sqlerro = true;
  	$erro_msg = "Ano de chegada inv�lido.";
  }
  
  if($sqlerro == false){
    $clrhpessoal->alterar($rh01_regist);
    $erro_msg = $clrhpessoal->erro_msg;
    if($clrhpessoal->erro_status==0){  	
      $sqlerro=true;    
    }

    if($sqlerro == false){
  	  if(trim($rh15_banco)!=""){
  	    $result_fgts = $clrhpesfgts->sql_record($clrhpesfgts->sql_query_file($rh01_regist));
  	    if($clrhpesfgts->numrows > 0){
    	  $clrhpesfgts->rh15_regist = $rh01_regist;
          $clrhpesfgts->alterar($rh01_regist);
  	    }else{
  	  	  $clrhpesfgts->rh15_regist = $rh01_regist;
          $clrhpesfgts->incluir($rh01_regist);
  	    }
        if($clrhpesfgts->erro_status==0){
          $erro_msg = $clrhpesfgts->erro_msg;
          $sqlerro=true;
        }
  	  }else{
        $clrhpesfgts->excluir($rh01_regist);
        if($clrhpesfgts->erro_status==0){
          $erro_msg = $clrhpesfgts->erro_msg;
          $sqlerro=true;
        }else{
          unset($rh15_data_dia,$rh15_data_mes,$rh15_data_ano,$rh15_banco,$rh15_agencia,$rh15_agencia_d,$rh15_contac,$rh15_contac_d,$db90_descr);
        }
      }
    }
  }

  if($sqlerro == false && trim($localrecebefoto) != ""){
    $clrhfotos->excluir($rh01_numcgm);
    if($clrhfotos->erro_status==0){
      $erro_msg = $clrhfotos->erro_msg;
      $sqlerro=true;
    }else{

	  	// Abre o arquivo
	  	$arquivograva = fopen($localrecebefoto,"rb");
	  	// L� o arquivo inteiro
	  	$dados = fread($arquivograva,filesize($localrecebefoto));
	  	// Fecha o arquivo
	    fclose($arquivograva);
	
	    // Criando o Objeto.
	    $oidgrava = pg_lo_create($conn);
	    $clrhfotos->rh50_oid    = $oidgrava;
	    $clrhfotos->rh50_numcgm = $rh01_numcgm;
	    $clrhfotos->incluir($rh01_numcgm);
	    if($clrhfotos->erro_status==0){
	      $erro_msg = $clrhfotos->erro_msg;
	      $sqlerro=true;
	    }
			// Abrindo o objeto
			$objeto = pg_lo_open($conn,$oidgrava,"w");
			// Inserindo Dados no arquivo
			pg_lo_write($objeto,$dados);
			// Fechando a conexao com o objeto
			pg_lo_close($objeto);
    }
  }
  $db_opcao = 2;
  $db_botao = true;

  db_fim_transacao($sqlerro);
  $result = $clrhpessoal->sql_record($clrhpessoal->sql_query($rh01_regist,"rh02_funcao"));
  if($clrhpessoal->numrows > 0){
    db_fieldsmemory($result,0);
  }

}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $db_botao = true;
   //die($clrhpessoal->sql_query($chavepesquisa));	
   $result = $clrhpessoal->sql_record($clrhpessoal->sql_query($chavepesquisa));   
   if($clrhpessoal->numrows > 0){
     db_fieldsmemory($result,0);
     $result_rhpesfgts = $clrhpesfgts->sql_record($clrhpesfgts->sql_query_banco($rh01_regist,"rh15_data,rh15_banco,rh15_agencia,rh15_agencia_d,rh15_contac,rh15_contac_d,db90_descr"));
     if($clrhpesfgts->numrows > 0){
     	db_fieldsmemory($result_rhpesfgts,0);
     }
   }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
               datagrid.widget.js");
  db_app::load("estilos.css,grid.style.css");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.rh01_numcgm.select();" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmrhpessoal.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
// form - formul�rio onde est�o os campos
// foco - campo que receber� foco no in�cio
// tfoco- true se programador quer que campo informado receba o foco e false se n�o quer
// inicio - �ndice inicial da tabula��o. Caso passado 0 (zero), a fun��o come�ar� do 1 (um)
// campo  - campo que receber� o foco ao sair do �ltimo campo
// tcampo - true se programador quer usar a vari�vel campo
// VER COM PAULO
function js_tabulacao(form,foco,tfoco,inicio,campo,tcampo){
  eval("x = document."+form+";");

  if(inicio == 0){  // Seta �ndice inicial
    indx = 1;
  }else{
    indx = inicio;
  }

  mark = 0;
  for(i=0; i<x.length; i++){
    if(x.elements[i].disabled == false){                // Se campo estiver desabilitado, n�o recebe tabIndex
      if(x.elements[i].type == 'select-one'){           // Testa se campo � um select
        if(x.elements[i].name == "rh01_sexo"){
          if(x.rh01_regist.readOnly == true){
            x.elements[i].tabIndex = 2;
          }else{
            x.elements[i].tabIndex = 3;
          }
        }else{
          x.elements[i].tabIndex = indx;                // Seta �ndice da tabula��o
        }
        mark = i;
        indx ++;                                        // Valor do pr�ximo �ndice
      }else if(x.elements[i].type == 'text'){           // Testa se campo � um text e n�o � readOnly
        if(x.elements[i].readOnly == false){
          if(x.elements[i].name == "rh01_numcgm"){
            if(x.rh01_regist.readOnly == true){
              x.elements[i].tabIndex = 1;
            }else{
              x.elements[i].tabIndex = 2;
            }
          }else{
            x.elements[i].tabIndex = indx;
          }
          indx ++;
          mark = i;
        }else{
          x.elements[i].tabIndex = x.length;
        }
      }else if(x.elements[i].type == 'checkbox'){       // Testa se campo � um checkbox
        x.elements[i].tabIndex = indx;
        indx ++;
        mark = i;
      }else if(x.elements[i].type == 'button'){         // Testa se � um bot�o, se for, testa se � bot�o ao lado das datas
        if(x.elements[i].value != "D"){
          x.elements[i].tabIndex = indx;
          indx ++;
          mark = i;
        }
      }else if(x.elements[i].type == 'submit'){         // Testa se � um bot�o do tipo submit
        x.elements[i].tabIndex = indx;
        indx ++;
        mark = i;
      }else if(x.elements[i].type == 'reset'){          // Testa se � um bot�o do tipo reset
        x.elements[i].tabIndex = indx;
        indx ++;
        mark = i;
      }
    }
  }
  if(tfoco == true){                                    // Se programador quer focar o campo informado, entrar�
    eval("x."+foco+".focus();");
  }
  if(mark > 0 && 1==2){
  	if(x.elements[mark]){
  	  if(x.elements[mark].onblur){
  	  	x.elements[mark].onblur+= eval("x."+campo+".focus()");
  	  }else{
  	  	x.elements[mark].onblur = eval("x."+campo+".focus()");
  	  }
  	}
  }
}
if(document.form1.rh01_regist.readOnly == true){
  js_tabulacao("form1","rh01_numcgm",true,0,"rh01_numcgm",true);
}else{
  js_tabulacao("form1","rh01_regist",true,0,"rh01_regist",true);
}
</script>
<?
if(isset($alterar)){
  db_msgbox($erro_msg);
  if($sqlerro==true){
    if($clrhpessoal->erro_campo!=""){
      echo "<script> document.form1.".$clrhpessoal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhpessoal->erro_campo.".focus();</script>";
    };
  }else{
    $chavepesquisa = $rh01_regist;
    $liberaaba = true;
  }
}
if(isset($chavepesquisa)){
	$rh01_regist = $chavepesquisa; 
 echo "<script>
      function js_db_libera(){
         parent.document.formaba.rhpesdoc.disabled=false;
         top.corpo.iframe_rhpesdoc.location.href='pes1_rhpesdoc001.php?rh16_regist=".@$rh01_regist."&z01_nome=$z01_nome&rhimp=".@$rhimp."';
         
         parent.document.formaba.rhpessoalmov.disabled=false;
         top.corpo.iframe_rhpessoalmov.location.href='pes1_rhpessoalmov001.php?rh02_regist=".@$rh01_regist."&z01_nome=$z01_nome';
 
         parent.document.formaba.rhdepend.disabled=false;
         top.corpo.iframe_rhdepend.location.href='pes1_rhdepend001.php?rh31_regist=".@$rh01_regist."&vmenu=true&z01_nome=$z01_nome';
         
         parent.document.formaba.rhsuspensaopag.disabled=false;
         top.corpo.iframe_rhsuspensaopag.location.href='pes1_rhsuspensaopag001.php?iMatricula=".@$rh01_regist."';
         
     ";
         if(isset($liberaaba)){
           echo "  parent.mo_camada('rhpesdoc');";
         }
 echo"}\n
    js_db_libera();
  </script>\n
 ";
}
 if($db_opcao==22||$db_opcao==33){
    echo "<script>document.form1.pesquisar.click();</script>";
 }
?>
