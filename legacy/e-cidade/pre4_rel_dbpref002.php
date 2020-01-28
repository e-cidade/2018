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

if( !isset($HTTP_GET_VARS["data1"]) && !isset( $HTTP_GET_VARS["data2"])){

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
          var data1, data2;
          data1 = document.form1.data1_ano.value+'/'+document.form1.data1_mes.value+'/'+document.form1.data1_dia.value;
          data2 = document.form1.data2_ano.value+'/'+document.form1.data2_mes.value+'/'+document.form1.data2_dia.value;
          if( data1 == "//" || data2 == "//"){
            alert("Favor selecionar o período.");
            document.form1.dtjs_data1.click();
            return false;
          }
          window.open('pre4_rel_dbpref002.php?data1='+data1+'&data2='+data2,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
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
                    <td nowrap title="11" width="500">
                      <fieldset><Legend><strong>&nbsp;Informe o Período&nbsp;</strong></legend>
                      <table border="0" align="center">
                        <tr>
                          <td>&nbsp;</td>
                            <td align="left" nowrap title="Período">
                            <strong>Período:&nbsp;&nbsp;</strong>
                            </td>
                            <td>
                              <?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
                              Até
                              <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>
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
    $str_sql = "select q50_data, q50_hora,z01_numcgm, z01_nome, q02_inscr, q50_numpre, q50_numpar, q50_vlrinf
                 from issvarlancval
                inner join isscalc on q01_numpre = q50_numpre
                inner join issbase on q02_inscr = q01_inscr
                inner join cgm on z01_numcgm = q02_numcgm
                where q50_data between '$data1' and '$data2'
                  and not exists ( select *
                                     from arrecant
                                    where k00_numpre = q50_numpre
                                      and k00_numpar = q50_numpar )
                order by q50_data,q50_hora";

    $result = pg_exec($str_sql) or die("FALHA: <br>$str_sql" );
    if(pg_numrows($result)==0){
      db_redireciona('db_erros.php?fechar=true&db_erro=Não existem informações pata gerar o relatório.');
    }
    $head2 = "Lançamentos efetuados no DBPREF";
    $head3 = "Issqn Variável";

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
            $pdf->Cell(15,5,'Data',1,0,"C",1);
            $pdf->Cell(15,5,'Hora',1,0,"C",1);
            $pdf->Cell(15,5,'CGM',1,0,"C",1);
            $pdf->Cell(60,5,'Nome',1,0,"C",1);
            $pdf->cell(20,5,'Inscrição',1,0,"R",1);
            $pdf->cell(15,5,'Numpre',1,0,"L",1);
            $pdf->cell(10,5,'Parcela',1,0,"L",1);
            $pdf->cell(20,5,'Valor',1,1,"R",1);
        }

        $pdf->Cell(15,5,$q50_data,0,0,"R",0);
        $pdf->Cell(15,5,$q50_hora,0,0,"R",0);
        $pdf->Cell(15,5,$z01_numcgm,0,0,"R",0);
        $pdf->Cell(60,5,$z01_nome,0,0,"L",0);
        $pdf->Cell(20,5,$q02_inscr,0,0,"R",0);
        $pdf->Cell(15,5,$q50_numpre,0,0,"L",0);
        $pdf->Cell(10,5,$q50_numpar,0,0,"L",0);
        $pdf->cell(20,5,db_formatar($q50_vlrinf,'f'),0,1,"R",0);
    }

    $pdf->Output();

}
?>