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
include("libs/db_utils.php");
include("libs/db_jsplibwebseller.php");

include("classes/db_agendamentos_ext_classe.php");
include("classes/db_undmedhorario_ext_classe.php");

include("dbforms/db_funcoes.php");

?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">

<style>
a:hover {
  color:blue;
}
a:visited {
  color: black;
  font-weight: bold;
}
a:active {
  color: black;
  font-weight: bold;
}
.cabec {
  text-align: center;
  font-size: 11;
  color: darkblue;
  background-color:#aacccc ;
  border:1px solid $FFFFFF;
  font-weight: bold;
}
.corpo {
  font-size: 9;
  color: black;
  background-color:#ccddcc;
}

</style>
<link href="estilos.css" rel="stylesheet" type="text/css">

<?


db_postmemory($HTTP_POST_VARS);

$clagendamentos  = new cl_agendamentos_ext;
$clundmedhorario = new cl_undmedhorario_ext;


$clagendamentos->gerar_faa = isset($gerar_faa)&&$gerar_faa==true?true:null;

echo "<script>
         if( parent.document.form1.relatorioagenda != undefined ){ 
           parent.document.form1.relatorioagenda.disabled = false;
         }
         if( parent.document.form1.relatoriofa != undefined ){
           parent.document.form1.relatoriofa.disabled = false;
         }
     </script>";

if(isset($gerar_faa) && $gerar_faa==true){
    //$clagendamentos->cria_table_gera_FA($sd27_i_codigo, $chave_diasemana,$sd23_d_consulta, $clagendamentos,$clundmedhorario, null );
    ?><script>
    sParam  = "sau4_agendamento002.php";
    sParam += "?sd27_i_codigo=<?=$sd27_i_codigo?>";
    sParam += "&chave_diasemana=<?=$chave_diasemana?>";
    sParam += "&sd23_d_consulta=<?=$sd23_d_consulta?>";
    sParam += "&sTransf=true";
    sParam += "&sLado=de";

    location.href = sParam;
    </script><?
}else{
    //$clagendamentos->cria_table($sd27_i_codigo, $chave_diasemana,$sd23_d_consulta, $clagendamentos,$clundmedhorario, null );
    ?><script>
    sParam  = "sau4_agendamento002.php";
    sParam += "?sd27_i_codigo=<?=$sd27_i_codigo?>";
    sParam += "&chave_diasemana=<?=$chave_diasemana?>";
    sParam += "&sd23_d_consulta=<?=$sd23_d_consulta?>";
    sParam += "&sTransf=false";
    sParam += "&sLado=";

    location.href = sParam;
    </script><?
	
}

//Verifica quantidade agendada
//if( $clagendamentos->total_agendado == 0){
//	db_msgbox("Profissional não possui agendamento para esta data.");
//	$consulta = isset($consulta)?$consulta:"sd23_d_consulta";
//	echo "<script>
//	           parent.document.form1.".$consulta.".value = '';
//	           parent.document.form1.".$consulta.".focus();
//	     </script>";
//	exit;
//}
?>