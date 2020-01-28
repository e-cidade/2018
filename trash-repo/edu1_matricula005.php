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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_matricula_classe.php");
include("classes/db_matriculamov_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmatricula    = new cl_matricula;
$clmatriculamov = new cl_matriculamov;
$clmatricula->rotulo->label();
$db_opcao = 1;
$result   = $clmatricula->sql_record($clmatricula->sql_query($matricula));
db_fieldsmemory($result,0);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11px;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.cabec1{
 font-size: 11px;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10px;
 font-weight: bold;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 10px;
}
.aluno2{
 color: #000000;
 font-family : Verdana;
 font-size: 10px;
 font-weight: bold;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="left" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
   <fieldset style="width:95%;background:#EAEAEA;"><legend><b>Dados da Matricula</b></legend>
   <table border="0" cellspacing="0" width="100%" height="100%" cellpadding="2">
   <tr>
    <td align="center" valign="top">
     <table border='0' width="100%" bgcolor="#EAEAEA" cellspacing="0px">
      <tr>
       <td width="15%"><b>N°:</b></td>
       <td><?=$ed60_matricula?> - <?=db_formatar($ed60_d_datamatricula,'d')?></td>
       <td><b>Última Atualização:</b></td>
       <td><?=db_formatar($ed60_d_datamodif,'d')?></td>
      </tr>
      <tr>
       <td width="15%"><b>Escola:</b></td>
       <td><?=$ed18_c_nome?></td>
       <td><b>Calendário:</b></td>
       <td><?=$ed52_c_descr?></td>
      </tr>
      <tr>
       <td><b>Curso:</b></td>
       <td><?=$ed29_c_descr?></td>
       <td><b>Base:</b></td>
       <td><?=$ed31_c_descr?></td>
      </tr>
      <tr>
       <td><b>Turma:</b></td>
       <td><?=$ed57_c_descr?></td>
       <td><b>Etapa:</b></td>
       <td><?=$ed11_c_descr?></td>
      </tr>
      <tr>
       <td><b>Situação:</b></td>
       <td><?=Situacao($ed60_c_situacao,$ed60_i_codigo)?></td>
       <td><b>Concluída:</b></td>
       <td><?=$ed60_c_concluida=="S"?"SIM":"NÃO"?></td>
      </tr>
     </table>
     <br>
    </td>
   </tr>
   <tr class="titulo" style="border:1px solid #f3f3f3">
    <td>
     <table width="100%" cellspacing="0">
      <tr class="titulo" style="border:1px solid #f3f3f3">
       <td>
        &nbsp;&nbsp;<b>Movimentação da Matrícula N° <?=$matricula?> - <?=$ed47_v_nome?>:</b>
       </td>
       <td align="right">
       <a href="javascript:js_historico(<?=$ed60_matricula?>);" class="titulo" >Histórico de Matrículas</a>&nbsp;&nbsp;
       </td>
      </tr>
     </table>
    </td>
   </tr>
   <tr>
    <td colspan="2">
     <table border="1" width="100%" bgcolor="#f3f3f3" cellspacing="0" cellpading="0">
      <?
      $array_mov = array();
      $sCamposResult  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,ed57_c_descr,";
      $sCamposResult .= " ed60_matricula,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr,nome";
      $sOrderResult   = " ed229_d_dataevento,ed229_i_codigo";
      $sWhereResult   = " ed229_i_matricula = $ed60_matricula AND ";
      $sWhereResult  .= " ed229_c_procedimento NOT LIKE  'CANCELAR ENCERRAMENTO%'";
      $sWhereResult  .= " AND ed229_c_procedimento NOT LIKE  'ENCERRAR%'";
      $result         = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                               $sCamposResult,
                                                                               $sOrderResult,
                                                                               $sWhereResult
                                                                              )
                                                   );
      if ($clmatriculamov->numrows > 0) {
      	
        for ($f = 0; $f < $clmatriculamov->numrows; $f++) {
        	
          db_fieldsmemory($result,$f);
          $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
          $iContador = count($array_mov)-1; 
          $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d');
          $array_mov[$iContador] .= "#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
          $array_mov[$iContador] .= "#".$ed60_matricula."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
          $array_mov[$iContador] .= "#".$ed229_c_procedimento."#".$ed229_t_descr."#".$nome;   
             
        }
      }
      
      $sCamposResult1  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,ed57_c_descr,";
      $sCamposResult1 .= " ed60_matricula,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr,nome";
      $sOrderResult1   = "ed229_d_dataevento DESC,ed229_i_codigo DESC LIMIT 1";
      $sWhereResult1   = " ed229_i_matricula = $ed60_matricula AND ed229_c_procedimento LIKE  'CANCELAR ENCERRAMENTO%'";
      $result1         = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                                $sCamposResult1,
                                                                                $sOrderResult1,
                                                                                $sWhereResult1 
                                                                               )
                                                    );
      if ($clmatriculamov->numrows > 0) {
      	
        db_fieldsmemory($result1,0);
        $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
        $iContador = count($array_mov)-1; 
        $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d');
        $array_mov[$iContador] .= "#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
        $array_mov[$iContador] .= "#".$ed60_matricula."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
        $array_mov[$iContador] .= "#".$ed229_c_procedimento."#".$ed229_t_descr."#".$nome;      
        
      }
      
      $sCamposResult2  = " ed229_i_codigo,ed229_d_dataevento,ed18_i_codigo,ed18_c_nome,ed60_i_codigo,ed57_c_descr,";
      $sCamposResult2 .= " ed60_matricula,ed52_i_ano,ed11_c_descr,ed229_c_procedimento,ed229_t_descr,nome";
      $sOrderResult2   = " ed229_d_dataevento DESC,ed229_i_codigo DESC LIMIT 1";
      $sWhereResult2  = " ed229_i_matricula = $ed60_matricula AND ed229_c_procedimento LIKE  'ENCERRAR%'";
      $result2         = $clmatriculamov->sql_record($clmatriculamov->sql_query("",
                                                                                $sCamposResult2,
                                                                                $sOrderResult2,
                                                                                $sWhereResult2
                                                                               )
                                                    );
      if ($clmatriculamov->numrows > 0) {
      	
        db_fieldsmemory($result2,0);
        $array_mov[]  = str_replace("-","",$ed229_d_dataevento).$ed229_i_codigo;
        $iContador = count($array_mov)-1; 
        $array_mov[$iContador] .= "|".db_formatar($ed229_d_dataevento,'d');
        $array_mov[$iContador] .= "#".$ed18_i_codigo." - ".substr($ed18_c_nome,0,30);
        $array_mov[$iContador] .= "#".$ed60_matricula."#".$ed57_c_descr."#".$ed52_i_ano."#".$ed11_c_descr;
        $array_mov[$iContador] .= "#".$ed229_c_procedimento."#".$ed229_t_descr."#".$nome;  
            
      }
      array_multisort($array_mov,SORT_ASC);
      if (count($array_mov) > 0) {
      	
        ?>
        <tr class="titulo" align="center">
         <td>Data</td>
         <td>Escola</td>
         <td>Matr.</td>
         <td>Turma</td>
         <td>Ano</td>
         <td>Etapa</td>
         <td>Procedimento</td>
        </tr>
        
        <?
        for ($f = 0; $f < count($array_mov); $f++) {
        	
          $array_mov1 = explode("|",$array_mov[$f]);
          $array_mov2 = explode("#",$array_mov1[1]);
          
          if ($f > 0) {
            ?>
	        <tr><td height="1" bgcolor="black" colspan="7"></td></tr>
            <?
          }
          ?>
          <tr bgcolor="#dbdbdb">
           <td class="aluno2" align="center"><?=$array_mov2[0]?></td>
           <td class="aluno2"><?=$array_mov2[1]?></td>
           <td class="aluno2" align="center"><?=$array_mov2[2]?></td>
           <td class="aluno2" align="center"><?=$array_mov2[3]?></td>
           <td class="aluno2" align="center"><?=$array_mov2[4]?></td>
           <td class="aluno2" align="center"><?=$array_mov2[5]?></td>
           <td class="aluno2"><?=$array_mov2[6]?></td>
	      </tr>
	      <tr>
           <td>&nbsp;</td>
            <td bgcolor="#f3f3f3" colspan="6" class="aluno2">
             <table width="100%">
              <tr>
               <td width="60%">
                <?=$array_mov2[7]?>
               </td>
               <td align="right" valign="top">
                <b>Usuário: </b><?=$array_mov2[8]?>
               </td>
              </tr>
             </table>
            </td>
           </tr>
        <?
        }
      } else {
      	
        ?>
        <tr>
         <td>
          Nenhum registro.
         </td>
        </tr>
        
        <?
      }
      ?>
        </table>
       </td>
      </tr>
      <tr>
       <td align="center">
        <input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_movimentos.hide();">
       </td>
      </tr>
     </table>
    </fieldset>
   </center>
  </td>
 </tr>
</table>
</body>
</html>

<script>
function js_historico(matricula) {
	
  js_OpenJanelaIframe('','db_iframe_historico','edu1_matricula006.php?matricula='+matricula,
		              'Histórico de Matrículas',true,0,0,screen.availWidth-60,screen.availHeight);
  
}
</script>