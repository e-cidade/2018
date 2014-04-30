<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_utils.php");
include("libs/db_usuariosonline.php");
include("classes/db_turma_classe.php");
include("classes/db_turmaturno_classe.php");
include("classes/db_matricula_classe.php");
include("classes/db_escola_classe.php");
include("classes/db_escolaestrutura_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_regenciahorario_classe.php");
include("classes/db_turmaserieregimemat_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_jsplibwebseller.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clturma               = new cl_turma;
$clturmaturno          = new cl_turmaturno;
$clescola              = new cl_escola;
$clescolaestrutura     = new cl_escolaestrutura;
$clregencia            = new cl_regencia;
$clregenciahorario     = new cl_regenciahorario;
$clmatricula           = new cl_matricula;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$db_opcao              = 22;
$db_opcao1             = 3;
$db_botao              = false;
$db_botao2             = true;
$codigoescola          = db_getsession("DB_coddepto");

if (isset($alterar)) {
  
  
  $db_opcao  = 2;
  $db_opcao1 = 3;
  db_inicio_transacao();
  
  /**
   * verificamos se foi alterado o turno, para desvincular na regenciahorarios
   */
  $oDaoTurma           = db_utils::getDao("turma");
  $oDaoRegencia        = db_utils::getDao("regencia");
  $oDaoRegenciaHorario = db_utils::getDao("regenciahorario");
  $iTurnoSelecionado   = $ed57_i_turno;
  $iTurma              = $ed57_i_codigo;
  
  $sSqlTurma   = $oDaoTurma->sql_query_file($iTurma,"ed57_i_turno", null, null);
  $rsTurma     = $oDaoTurma->sql_record($sSqlTurma);
  $iTurnoAtual = db_utils::fieldsMemory($rsTurma, 0)->ed57_i_turno;
  
  /*
   * se os turnos forem diferentes, desvinculamos na regenciahorarios
   */
  if ($iTurnoAtual != $iTurnoSelecionado) {
    
    $sCampo       = "array_to_string(array_accum(ed59_i_codigo),',' ) as ed59_i_codigo";
    $sSqlRegencia = $oDaoRegencia->sql_query_file(null, $sCampo, null, "ed59_i_turma = {$iTurma}");
    $rsRegencia   = $oDaoRegencia->sql_record($sSqlRegencia);
    $sRegencias   = db_utils::fieldsMemory($rsRegencia, 0)->ed59_i_codigo;
    
    $sUpdateregenciahorario = "update regenciahorario set ed58_ativo = false where ed58_i_regencia in ({$sRegencias})";
    if (!db_query($sUpdateregenciahorario)) {
      
      $sErro = "ERRO : erro ao desvincular regenciahorario";
      db_msgbox($sErro);
      db_fim_transacao(true);
    }
  }
  
  if ($ed57_i_tipoturma == 2 && isset($ed57_censoprogramamaiseducacao)) {
  	$ed57_censoprogramamaiseducacao = '';
  }
  
  $clturma->ed57_c_descr = trim($ed57_c_descr);
  $clturma->alterar($ed57_i_codigo);
  db_fim_transacao();
  $db_botao = true;
  
} else if(isset($chavepesquisa)) {
	
  $db_opcao  = 2;
  $db_opcao1 = 3;
  $result    = $clturma->sql_record($clturma->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
  $result1  = $clmatricula->sql_record($clmatricula->sql_query_file(""," count(*) ",""," ed60_i_turma = $ed57_i_codigo"));
  db_fieldsmemory($result1,0);
  $ed57_i_nummatr = $count;
  $result1        = $clturmaturno->sql_record($clturmaturno->sql_query("",
                                                                       "ed246_i_turno,ed15_c_nome as ed15_c_nomeadd",
                                                                       "",
                                                                       " ed246_i_turma = $ed57_i_codigo"
                                                                      )
                                             );
 if ($clturmaturno->numrows > 0) {
   db_fieldsmemory($result1,0);
 }
 ?>
  <script>
   parent.document.formaba.a2.disabled = false;
   parent.document.formaba.a3.disabled = false;
   parent.document.formaba.a4.disabled = false;
   parent.document.formaba.a5.disabled = false;
   
   top.corpo.iframe_a2.location.href='edu1_regenciaabas001.php?ed59_i_turma=<?=$ed57_i_codigo?>'+
                                     '&ed57_c_descr=<?=$ed57_c_descr?>&ed57_i_tipoturma=<?=$ed57_i_tipoturma?>';
   top.corpo.iframe_a3.location.href='edu1_regenciahorarioabas001.php?ed59_i_turma=<?=$ed57_i_codigo?>'+
                                     '&ed57_c_descr=<?=$ed57_c_descr?>&ed57_i_turno=<?=$ed57_i_turno?>';

   var sHRefAbaAluno  = 'edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
       sHRefAbaAluno += '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
   
   if (<?=$ed57_i_tipoturma?> == 6) {
     
     sHRefAbaAluno  = 'edu1_alunoturmaprogressao001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
     sHRefAbaAluno += '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
   }
   top.corpo.iframe_a4.location.href=sHRefAbaAluno;
   top.corpo.iframe_a5.location.href='edu1_parecerturma001.php?ed105_i_turma=<?=$ed57_i_codigo?>'+
                                     '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
  </script>
 <?
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_validaTipoTurma();" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%"><legend><b>Alteração de Turma</b></legend>
    <?include("forms/db_frmturma.php");?>
   </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed57_c_descr",true,1,"ed57_c_descr",true);
</script>
<?
if (isset($alterar)) {
  

		
  
  
  if ($clturma->erro_status == "0") {
  	
    $clturma->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    
    if ($clturma->erro_campo != "") {
    	
      echo "<script> document.form1.".$clturma->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clturma->erro_campo.".focus();</script>";
      
    }
    
  } else {
  	
    $clturma->erro(true,false);
    $result = $clturma->sql_record($clturma->sql_query("",
                                                       "ed57_i_turno as turnoant,ed15_c_nome as descrant",
                                                       "",
                                                       " ed57_i_codigo = $ed57_i_codigo"
                                                      )
                                  );
    db_fieldsmemory($result,0);
    
    if ($turnoant != $ed57_i_turno) {
    	
      $result1 = $clregencia->sql_record($clregencia->sql_query("",
                                                                "ed59_i_codigo as regencia",
                                                                "",
                                                                " ed59_i_turma = $ed57_i_codigo"
                                                               )
                                        );
                                        
      for ($c = 0; $c < $clregencia->numrows; $c++) {
      	
        db_fieldsmemory($result1,$c);
        $sSqlRegenciaHorario = $clregenciahorario->sql_query_file(null, 
                                                                  "ed58_i_codigo", 
                                                                  null, 
                                                                  "ed588_i_regencia={$regencia}"
                                                                 );
        $rsRegenciaHorario    = $clregenciahorario->sql_record($sSqlRegenciaHorario);
        $iTotalLinhasRegencia = $clregenciahorario->numrows; 
        if ($iTotalLinhasRegencia > 0) {
          
          for ($iDiario = 0; $iDiario < $iTotalLinhasRegencia; $iDiario++) {
            
            $clregenciahorario->ed58_i_codigo = db_utils::fieldsMemory($rsRegenciaHorario, $iDiario)->ed58_i_codigo; 
            $clregenciahorario->ed58_ativo    = "false";
            $clregenciahorario->alterar($clregenciahorario->ed58_i_codigo);
          }
        }
        
        /**
         * Cancelamos os dados da regencia para false
         */
        
      }
    }
    $sCampos = "ed246_i_codigo as codconf,ed246_i_turno as turnoaddant";
    $result2 = $clturmaturno->sql_record($clturmaturno->sql_query_file("",
                                                                       $sCampos,
                                                                       "",
                                                                       " ed246_i_turma = $ed57_i_codigo"
                                                                      )
                                        );
    $linhas2 = $clturmaturno->numrows;
    if ($linhas2 > 0) {
      $turnoaddant = pg_result($result2,0,'turnoaddant');
    }
    
    if ($ed246_i_turno == "") {
    	
      if ($clturmaturno->numrows > 0) {
      	
        db_fieldsmemory($result2,0);
        $clturmaturno->excluir($codconf);
        $exclusaoadd = true;
        
      }
      
    } else {
    	
      if ($clturmaturno->numrows > 0) {
      	
        db_fieldsmemory($result2,0);
        $clturmaturno->ed246_i_turma  = $ed57_i_codigo;
        $clturmaturno->ed246_i_codigo = $codconf;
        $clturmaturno->alterar($codconf);
        
      } else {
      	
        $clturmaturno->ed246_i_turma = $ed57_i_codigo;
        $clturmaturno->incluir(null);
        
      }
  }
  
  if ($linhas2 > 0) {
  	
    if (($ed246_i_turno != "" && $ed246_i_turno != $turnoaddant) || isset($exclusaoadd)) {
      
      $sWhere   = " ed59_i_turma = $ed57_i_codigo AND ed17_i_turno = $turnoaddant and ed58_ativo is true  ";
      $result1  = $clregenciahorario->sql_record($clregenciahorario->sql_query("",
                                                                               "ed58_i_codigo as codreghora",
                                                                               "",
                                                                               $sWhere
                                                                              )
                                               );
      $linhas11 = $clregenciahorario->numrows;
      for ($c = 0; $c < $linhas11; $c++) {
      	
        db_fieldsmemory($result1,$c);
        $clregenciahorario->ed58_i_codigo = $codreghora;
        $clregenciahorario->ed58_ativo    = "false"; 
        $clregenciahorario->alterar($codreghora);
        
      }
    }
  }
  ?>
   <script>
    parent.document.formaba.a2.disabled = false;
    parent.document.formaba.a3.disabled = false;
    parent.document.formaba.a4.disabled = false;
    parent.document.formaba.a5.disabled = false;
    top.corpo.iframe_a2.location.href='edu1_regenciaabas001.php?ed59_i_turma=<?=$ed57_i_codigo?>'+
                                       '&ed57_c_descr=<?=$ed57_c_descr?>&ed57_i_tipoturma=<?=$ed57_i_tipoturma?>';
    top.corpo.iframe_a3.location.href='edu1_regenciahorarioabas001.php?ed59_i_turma=<?=$ed57_i_codigo?>'+
                                      '&ed57_c_descr=<?=$ed57_c_descr?>&ed57_i_turno=<?=$ed57_i_turno?>';

    var sHRefAbaAluno  = 'edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
        sHRefAbaAluno += '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
    
    if (<?=$ed57_i_tipoturma?> == 6) {
      
      sHRefAbaAluno  = 'edu1_alunoturmaprogressao001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
      sHRefAbaAluno += '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
    }
    top.corpo.iframe_a4.location.href=sHRefAbaAluno;
    top.corpo.iframe_a5.location.href='edu1_parecerturma001.php?ed105_i_turma=<?=$ed57_i_codigo?>'+
                                      '&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
    top.corpo.iframe_a1.location.href='edu1_turma002.php?chavepesquisa=<?=$ed57_i_codigo?>';
   </script>
  <?
 }
}
if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>