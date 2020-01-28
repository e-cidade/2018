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

    //require("libs/db_stdlib.php");
    //require("libs/db_conecta.php");
    //include("libs/db_sessoes.php");
    //include("libs/db_usuariosonline.php");
    include("fpdf151/pdf.php");
    include("dbforms/db_funcoes.php");
    include("dbforms/db_classesgenericas.php");

if( !isset($HTTP_GET_VARS["ano"]) && !isset( $HTTP_GET_VARS["mes"])){

    db_postmemory($HTTP_POST_VARS);
    ?>

    <html>
    <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script>
    function js_emite(){
      jmes=document.form1.mes.value;
      if(jmes=="mes"){
        alert("Favor selecionar o mês!");
        document.form1.mes.focus();
        return false
      }
     window.open('pre4_rel_dbpref001.php?ano='+document.form1.ano.value+'&mes='+document.form1.mes.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');

    }

     function js_criames(obj){
       for(i=1;i<document.form1.mes.length;i){
         document.form1.mes.options[i] = null;
       }
       var dth = new Date(<?=date("Y")?>,<?=date("m")?>,'1');
        if(document.form1.ano.options[0].value != obj.value ){
         for(j=1;j<13;j++){
           var dt = new Date(<?=date("Y")?>,j,'1');
           document.form1.mes.options[j] = new Option(db_mes(j),dt.getMonth());
           document.form1.mes.options[j].value = j;
         }
        }else{
         for(j=1;j<dth.getMonth()+1;j++){
           var dt = new Date(<?=date("Y")?>,j,'1');
           document.form1.mes.options[j] = new Option(db_mes(j),dt.getMonth());
           document.form1.mes.options[j].value = j;
         }
       }
     }


    </script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    </head>
    <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
      <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
      </table>

      <table  align="center">
        <form name="form1" method="post" action="">
          <tr>
             <td>&nbsp;</td>
             <td>&nbsp;</td>
          </tr>
           <tr>
             <td colspan=2  align="center">
             </td>
           </tr>
          <tr >
            <td colspan=2 >
                <table align="center" >
                  <tr>
                    <td nowrap title="11" width="400">
                      <fieldset><Legend><strong>&nbsp;Informe a Competência&nbsp;</strong></legend>
                      <table border="0" align="center">
                        <tr>
                          <td>&nbsp;</td>
                          <td>
                               Competência:
                                <select name="ano" onchange="js_criames(this)">
                                <?
                                  $sano = date("Y");
                                  if(date("m")==12)
                                   $sano ++;
                                  //for($ci = $sano; $ci >= ($sano-10); $ci--){
                                  for($ci = $sano; $ci >= 2000; $ci--){
                                    echo "<option value=".$ci." >$ci</option>";
                                  }
                                ?>
                                </select>
                                <select class="digitacgccpf" name="mes" id="mes" >
                                  <option value="mes">Mês</option>
                                </select>
                                <script>
                                js_criames(document.form1.ano);
                                </script>

                          </td>
                          <td>&nbsp;</td>
                        </tr>
                        </tr>
                      </table>
                      </fieldset>
                    </td>
                  </tr>
                </table>
           </td>
          </tr>
          <tr>
            <td colspan="2" align = "center">
              <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
            </td>
          </tr>
        </form>
       </table>
      <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

    ?>
    </body>
    </html>
    <?
} else {
    db_postmemory($HTTP_POST_VARS);
    $str_sql = "select issqnret.*,
                  case when arrecad.k00_numpre is not null then
                     'no financeiro'
                  else
                        case when arrecant.k00_numpre is not null then
                             'ja pago'
                        else
                            'não lançado'
                        end
                  end as situacao
                  from (
                        select z01_numcgm, z01_nome, q20_inscr, q20_planilha, q20_numpre, sum( q21_valor ) as q21_valor
                          from issplan
                         inner join issplanit on q21_planilha = q20_planilha
                         inner join cgm on z01_numcgm = q20_numcgm
                         where q20_ano = $ano
                           and q20_mes = $mes
													 and q20_situacao <> 5 
													 and q21_status = 1 
                        group by z01_numcgm, z01_nome, q20_inscr, q20_planilha, q20_numpre
                      ) as issqnret
                  left join arrecad on arrecad.k00_numpre = q20_numpre
                  left join arrecant on arrecant.k00_numpre = q20_numpre
                order by q20_planilha ";

    $result = pg_exec($str_sql) or die("FALHA: <br>$str_sql" );
    if(pg_numrows($result)==0){
      db_redireciona('db_erros.php?fechar=true&db_erro=Não existem informações pata gerar o relatório. ('.$ano.'/'.$mes.').');
    }
    $head2 = "Lançamentos efetuados no DBPREF";
    $head3 = "Issqn Retido na Fontes";
    $head5 = "Competência: $ano/$mes";
    $pdf = new PDF();
    $pdf->Open();
    $pdf->AliasNbPages();
    $pdf->SetTextColor(0,0,0);
    $pdf->SetFillColor(220);
    $pdf->SetFont('Arial','B',7);

    $pag = 1;

    for ($x = 0 ; $x < pg_numrows($result);$x++){
        db_fieldsmemory($result,$x);
        if (($pdf->gety() > $pdf->h - 30) || $pag == 1 ){
            $pdf->addpage();
            $pag = 0;
            $pdf->Cell(1,10,'',0,1,"",0);
            $pdf->Cell(15,5,'CGM',1,0,"C",1);
            $pdf->Cell(60,5,'Nome',1,0,"C",1);
            $pdf->cell(20,5,'Inscrição',1,0,"R",1);
            $pdf->cell(20,5,'Planilha',1,0,"R",1);
            $pdf->cell(20,5,'Valor',1,0,"R",1);
            $pdf->cell(20,5,'Situação',1,1,"L",1);
        }

        $pdf->Cell(15,5,$z01_numcgm,0,0,"R",0);
        $pdf->Cell(60,5,$z01_nome,0,0,"L",0);
        $pdf->Cell(20,5,$q20_inscr,0,0,"R",0);
        $pdf->Cell(20,5,$q20_planilha,0,0,"R",0);
        $pdf->cell(20,5,db_formatar($q21_valor,'f'),0,0,"R",0);
        $pdf->Cell(20,5,$situacao,0,1,"L",0);
    }

    $pdf->Output();

}
?>