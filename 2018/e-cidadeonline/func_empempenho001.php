<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("dbforms/db_funcoes.php");
include("classes/db_empempenho_classe.php");
include("classes/db_orcdotacao_classe.php");
include("classes/db_empempaut_classe.php");
include("classes/db_empemphist_classe.php");
include("classes/db_emphist_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clempempenho = new cl_empempenho;
$clorcdotacao = new cl_orcdotacao;
$clempempaut  = new cl_empempaut;
$clempemphist = new cl_empemphist;
$clemphist    = new cl_emphist;

$clempempenho->rotulo->label();
$clempempaut->rotulo->label();
$clempemphist->rotulo->label();
$clemphist->rotulo->label();

if (isset($e60_numemp) and $e60_numemp !=""){
    $res = $clempempenho->sql_record($clempempenho->sql_query($e60_numemp));
    if ($clempempenho->numrows > 0 ) {   
         db_fieldsmemory($res,0,true);
         //-----
         $ra=$clempempaut->sql_record($clempempaut->sql_query_file($e60_numemp));
         if ($clempempaut->numrows > 0){
                db_fieldsmemory($ra,0,true);
         }        
         //------
         $rhist=$clempemphist->sql_record($clempemphist->sql_query($e60_numemp));
         if ($clempemphist->numrows > 0){
                db_fieldsmemory($rhist,0,true);
         }      
      
    }
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<table width="100%" border="0" align="center" cellspacing="2" class="bold4">
 <tr>
  <td width="25%" align="right" nowrap title="<?=$Te60_numemp?>"><?=$Le60_numemp?></td>
  <td width="15%" align="left" nowrap><? db_input("e60_numemp",15,"",true,"text",3); ?> </td>
  <td width="*%" align="left" nowrap title="<?=$Te60_codemp ?>"><?=$Le60_codemp ?>
   <?
    db_input("e60_codemp",15,"",true,"text",3);
   if($e60_anousu!=db_getsession("DB_anousu"))
     echo "<font color='red'><b>RESTOS À PAGAR</b></font>";
   ?>
  </td>
 </tr>
 <tr>
  <td align="right" nowrap title="<?=$Te61_autori?>">
   <?=$Le61_autori?></td>
  <td align="left" nowrap><? db_input("e61_autori",15,"",true,"text",3); ?> </td>
  <td align="left" nowrap title="<?=$Te60_destin ?>"><?=$Le60_destin ?><? db_input("e60_destin",40,"",true,"text",3); ?></td>
 </tr>
 <tr>
  <td align="right" nowrap title="<?=$Te60_emiss?>"><?=$Le60_emiss?></td>
  <td align="left" nowrap>
    <?
    if(isset($e60_emiss) and ($e60_emiss != "")){
      list($e60_emiss_dia,$e60_emiss_mes,$e60_emiss_ano)= split('[/.-]',$e60_emiss);
     }
     db_inputdata('e60_emiss',@$e60_emiss_dia,@$e60_emiss_mes,@$e60_emiss_ano,true,'text',3,"");
    ?>
   </td>
  <td align="left" nowrap title="<?=$Te60_vencim ?>">
   <?=$Le60_vencim ?>
   <? if (isset($e60_vencim) and ($e60_vencim != "")) {
       list($e60_vencim_dia,$e60_vencim_mes,$e60_vencim_ano) = split('[/.-]',$e60_vencim);
      }
      db_inputdata('e60_vencim',@$e60_vencim_dia,@$e60_vencim_mes,@$e60_vencim_ano,true,'text',3,"");
   ?>
  </td>
 </tr>
 <tr>
  <td  align="right" nowrap title="<?=$Te60_numcgm ?>"><b><?=$Le60_numcgm?></b></td>
  <td  colspan="2"  align="left" nowrap title="<?=$Te60_numcgm ?>">
   <? db_input("e60_numcgm",8,"",true,"text",3);
      db_input("z01_nome",40,"",true,"text",3);
   ?>
  </td>
 </tr>
 <?  //-----------  dotacão
     if (isset($e60_coddot) and ($e60_coddot !="")) {
         $sql= $clorcdotacao->sql_query($e60_anousu,$e60_coddot,"o56_elemento,o56_descr,fc_estruturaldotacao(o58_anousu,o58_coddot) as o58_estrutdespesa");
         $res = $clorcdotacao->sql_record($sql);
         if ($clorcdotacao->numrows >0 ){
             db_fieldsmemory($res,0,true);
         }
     }
     ?>
 <tr>
  <td  align="right" nowrap title="<?=$Te60_coddot ?>">
   <?=$Le60_coddot?></td>
  <td  colspan=2   align="left" >
   <? db_input("e60_coddot",8,"",true,"text",3);
      db_input("o58_estrutdespesa",50,"",true,"text",3);   ?> </td>
 </tr>
 <tr>
  <td  align="right"> &nbsp; </td>
  <td  colspan="2" align="left" nowrap >
      <? db_input("o56_elemento",20,"",true,"text",3);
         db_input("o56_descr",50,"",true,"text",3);   ?> </td>
 </tr>
 <tr> <!--- valor --->
  <td   align="right" nowrap title="<?=$Te60_vlremp ?>"><?=$Le60_vlremp ?></td>
  <td   align="left" nowrap title="<?=$Te60_vlremp ?>"><? db_input("e60_vlremp",8,"",true,"text",3);?></td>
  <td   align="left" nowrap title="<?=$Te60_codtipo ?>">
     <?=$Le60_codtipo ?>
     <?  db_input("e60_codtipo",6,"",true,"text",3);
         db_input("e41_descr",20,"",true,"text",3);   ?>
  </td>
 </tr>
 <tr>
  <td   align="right" nowrap title="<?=$Te60_vlrliq ?>"><?=$Le60_vlrliq ?></td>
  <td   align="left" nowrap title="<?=$Te60_vlremp ?>"><? db_input("e60_vlrliq",8,"",true,"text",3);?></td>
  <td   align="left" nowrap >
  <?=@$Le63_codhist ?>
  <?   db_input("e63_codhist",6,"",true,"text",3);
       db_input("e40_descr",40,"",true,"text",3);  ?></td>
 </tr>
 <tr>
  <td align="right" nowrap title="<?=$Te60_vlrpag ?>"><?=$Le60_vlrpag ?></td>
  <td align="left" nowrap title="<?=$Te60_vlrpag ?>"><? db_input("e60_vlrpag",8,"",true,"text",3);?></td>
  <td align="left" nowrap title="<?=$Te60_resumo ?>"><?=$Le60_resumo ?>  </td>
 </tr>
 <tr>
  <td align="right" nowrap title="<?=$Te60_vlranu ?>"><?=$Le60_vlranu ?></td>
  <td align="left" nowrap title="<?=$Te60_vlranu ?>"><? db_input("e60_vlranu",8,"",true,"text",3);?></td>
  <td rowspan=2 align="left" ><?  db_textarea("e60_resumo",3,40,""); ?> </td>
 </tr>
 <tr>
  <td align="right" nowrap title="Valor a Pagar"><strong>A pagar:</strong></td>
  <td align="left" nowrap title="<?=$Te60_vlranu ?>">
  <?
  $e60_apagar = $e60_vlremp-$e60_vlranu-$e60_vlrpag;
   db_input("e60_apagar",8,"",true,"text",3);
  ?></td>
 </tr>
</table>
<div align="center" class="bold2">
LANÇAMENTOS
</div>
<?
 $sql = " select c70_codlan,
                    c70_data,
                    c53_descr,
                    c70_valor,
                    c82_reduz,
                    c72_complem
             from conlancamemp
                  inner join conlancam on c70_codlan = c75_codlan
                  left  outer join conlancampag on c82_codlan = c70_codlan
                  inner join conlancamdoc on c71_codlan = c70_codlan
                  inner join conhistdoc on c53_coddoc = c71_coddoc
                  left join conlancamcompl on c72_codlan=c70_codlan
             where  c75_numemp=$e60_numemp
              order by c75_data, c75_codlan
           ";
  db_lovrot($sql,15,"","","");
?>
</body>
</html>
<script>
 parent.form.abertos.disabled = true;
 parent.form.todos.disabled = true;
 parent.form.imprimir.disabled = true;
</script>