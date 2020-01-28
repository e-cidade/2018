<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_alunotransfturma_classe.php");
include("classes/db_calendario_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clalunotransfturma = new cl_alunotransfturma;
$clcalendario = new cl_calendario;
$db_opcao = 1;
$db_botao = true;
$nomeescola = db_getsession("DB_nomedepto");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec{
 text-align: left;
 font-size: 10;
 color: #DEB887;
 background-color:#444444;
 border:1px solid #CCCCCC;
}
.aluno{
 font-size: 11;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
   <br>
   <form name="form1" method="post" action="">
   <fieldset style="width:95%"><legend><b><?=$nomeescola?> - Trocas de Turmas</b></legend>
    <?
    $result = $clcalendario->sql_record($clcalendario->sql_query_calturma("","ed52_i_codigo,ed52_c_descr,ed52_i_ano","ed52_i_ano desc"," ed38_i_escola = $escola AND ed52_c_passivo = 'N'"));?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td align="left" valign="top">
       <b>Selecione o Calendário:</b>
       <select name="calendario" style="font-size:9px;width:200px;height:18px;" onchange="js_botao(this.value)">
        <option value=""></option>
        <?
        for($i=0;$i<$clcalendario->numrows;$i++) {
         db_fieldsmemory($result,$i);
         $selected = isset( $calendario ) && $ed52_i_codigo == $calendario ? "selected" : "";
         echo "<option value='$ed52_i_codigo' $selected>$ed52_c_descr</option>\n";
        }
        ?>
       </select>
      </td>
     </tr>
    </table>
    <?if(isset($calendario)){?>
     <br>
     <table border="0" cellspacing="2px" width="100%" height="100%" cellpadding="1px" bgcolor="#cccccc">
     <tr>
      <td align="center" valign="top">
       <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
        <tr class='cabec'>
         <td rowspan="2" align='center'><b>Matrícula</b></td>
         <td rowspan="2" align='center'><b>Aluno</b></td>
         <td colspan="2" align='center'><b>ORIGEM</b></td>
         <td colspan="2" align='center'><b>DESTINO</b></td>
         <td align='center'>&nbsp;</td>
        </tr>
        <tr class='cabec'>
         <td align='center'><b>Turma - Etapa</b></td>
         <td align='center'><b>Calendario</b></td>
         <td align='center'><b>Turma - Etapa</b></td>
         <td align='center'><b>Calendario</b></td>
         <td align='center'><b>Data da Troca de Turma</b></td>
        </tr>
        <?
        $campos1 = "ed60_i_codigo,
                    ed60_matricula,
                    ed47_i_codigo,
                    ed47_v_nome,
                    calendario.ed52_c_descr as caldescr,
                    calendariodestino.ed52_c_descr as caldescrdestino,
                    turma.ed57_c_descr as nomeorigem,
                    case when turma.ed57_i_tipoturma = 1
                     then fc_nomeetapaturma(turma.ed57_i_codigo)
                     else serie.ed11_c_descr
                    end as serieorigem,
                    turmadestino.ed57_c_descr as nomedestino,
                    case when turmadestino.ed57_i_tipoturma = 1
                     then fc_nomeetapaturma(turmadestino.ed57_i_codigo)
                     else (select serie1.ed11_c_descr
                           from serie as serie1
                            inner join matriculaserie as matriculaserie1 on matriculaserie1.ed221_i_serie = serie1.ed11_i_codigo
                            inner join matricula as matricula1 on matricula1.ed60_i_codigo = matriculaserie1.ed221_i_matricula
                           where matriculaserie1.ed221_c_origem = 'S'
                           and matricula1.ed60_i_aluno = ed47_i_codigo
                           and matricula1.ed60_i_turma = ed69_i_turmadestino)
                    end as seriedestino,
                    ed69_d_datatransf";
        $result = $clalunotransfturma->sql_record($clalunotransfturma->sql_query("",$campos1,"ed69_d_datatransf,turma.ed57_c_descr"," turma.ed57_i_escola = $escola AND turma.ed57_i_calendario = $calendario"));
        if($clalunotransfturma->numrows>0){
         $cor1 = "#DBDBDB";
         $cor2 = "#f3f3f3";
         $cor = "";
         for($c=0;$c<$clalunotransfturma->numrows;$c++){
          db_fieldsmemory($result,$c);
          if($cor==$cor1){
           $cor = $cor2;
          }else{
           $cor = $cor1;
          }
          ?>
          <tr bgcolor="<?=$cor?>">
           <td class='aluno' align='center'><?=$ed60_matricula?></td>
           <td class='aluno' ><?=$ed47_v_nome?> - <?=$ed47_i_codigo?></td>
           <td class='aluno' align='center'><?=$nomeorigem?> - <?=$serieorigem?></td>
           <td class='aluno' align='center'><?=$caldescr?></td>
           <td class='aluno' align='center'><?=$nomedestino?> - <?=$seriedestino?></td>
           <td class='aluno' align='center'><?=$caldescrdestino?></td>
           <td class='aluno' align='center'><?=db_formatar($ed69_d_datatransf,'d')?></td>
          </tr>
          <?
         }
        }else{
         ?>
         <table border='1px' width="100%" bgcolor="#cccccc" style="" cellspacing="0px">
          <tr bgcolor="#EAEAEA">
           <td class='aluno'>NENHUMA TRANSFERÊNCIA NESTE CALENDÁRIO.</td>
          </tr>
         </table>
         <?
        }
        ?>
       </table>
      </td>
     </tr>
     </table>
    <?}?>
   </fieldset>
   </form>
  </td>
 </tr>
</table>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_botao(calendario){
 if(calendario!=""){
  location.href = "edu3_transfturma001.php?calendario="+calendario;
 }
}
<?if(!isset($calendario) && pg_num_rows($result)>0){?>
 document.form1.calendario.options[1].selected = true;
 js_botao(document.form1.calendario.options[1].value);
<?}?>
</script>