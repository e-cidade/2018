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

include(modification("fpdf151/pdf.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_folha_classe.php"));
db_postmemory($HTTP_POST_VARS);
$clfolha = new cl_folha;
$clrotulo = new rotulocampo;
$clfolha->rotulo->label();
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("r70_descr");

if(isset($emite)){

  $datagera = date("Y-m-d",db_getsession("DB_datausu"));
  if($recurso =='f'){
    $head8 = 'RECURSO : FUNDEF';
    $where = " r70_estrut between '10300' and '10319' ";
  }elseif($recurso =='t'){
    $head8 = 'RECURSO : TODOS';
    $where = " 1 = 1 ";
  }elseif($recurso =='m'){
    $head8 = 'RECURSO : MDE';
    $where = " r70_estrut between '10320' and '10359' ";
  }elseif($recurso =='a'){
    $head8 = 'RECURSO : ASPS';
    $where = " r70_estrut between '10500' and '10598' ";
  }elseif($recurso =='s'){
    $head8 = 'RECURSO : FES';
    $where = " r70_estrut = '10599' ";
  }else{
    $head8 = 'RECURSO : LIVRE';
    $where = " ( r70_estrut between '10001' and '10299' or r70_estrut between '10360' and '10499' or r70_estrut > '10599' ) ";
  }
  $where .= " and r70_instit = ".db_getsession("DB_instit")." ";
  $where .= " and r38_liq > 0 ";

  $head3 = "EMISSÃO DE ARQUIVOS - BLV BANRISUL";
  $head5 = "ARQUIVO  :  /tmp/blvreme.txt";
  $head6 = "GERAÇÃO  :  ".db_formatar($datagera,"d").' AS '.date("H").':'.date("i").':'.date("s").' HS';

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->setfillcolor(235);
  $entrar = true;
  $alt = 4;

  include(modification("dbforms/db_layouttxt.php"));
  $db_layouttxt = new db_layouttxt(1,"/tmp/blvreme.txt");

  
//  echo "  recurso  ".$recurso.'   '.$where ;
//  $clfolha->sql_query_gerarqbag(null,"*","r38_nome"," $where and r38_banco = '041'");"   entrou  ";exit;
  $result_dados = $clfolha->sql_record($clfolha->sql_query_gerarqbag(null,"*","r38_nome"," $where and r38_banco = '041'"));

  $valortotal = 0;

  if(!$result_dados || pg_num_rows($result_dados) == 0) {
    $erro_msg = "Nenhum registro encontrado.\\nVerifique geração da folha em disco.";
  }

  if($clfolha->numrows > 0){
    db_setaPropriedadesLayoutTxt($db_layouttxt, 1);
    for($i=0; $i<$clfolha->numrows; $i++){
      db_fieldsmemory($result_dados, $i);

      if($entrar == true || $pdf->gety() > $pdf->h - 30){
        $pdf->addpage("L");
        $pdf->setfont('arial','b',8);
        $pdf->cell(30,$alt,$RLr38_regist,1,0,"C",1);
        $pdf->cell(20,$alt,$RLz01_numcgm,1,0,"C",1);
        $pdf->cell(65,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(20,$alt,$RLz01_cgccpf,1,0,"C",1);
        $pdf->cell(65,$alt,$RLr70_descr,1,0,"C",1);
        $pdf->cell(20,$alt,$RLr38_liq,1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_banco,1,0,"C",1);
        $pdf->cell(15,$alt,$RLr38_agenc,1,0,"C",1);
        $pdf->cell(25,$alt,$RLr38_conta,1,1,"C",1);
        $entrar = false;
      }

      $pdf->setfont('arial','',7);
      $pdf->cell(30,$alt,$r38_regist,1,0,"C",0);
      $pdf->cell(20,$alt,$z01_numcgm,1,0,"C",0);
      $pdf->cell(65,$alt,$z01_nome,1,0,"L",0);
      $pdf->cell(20,$alt,$z01_cgccpf,1,0,"C",0);
      $pdf->cell(65,$alt,$r70_descr,1,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($r38_liq,'f'),1,0,"R",0);
      $pdf->cell(15,$alt,$r38_banco,1,0,"C",0);
      $pdf->cell(15,$alt,$r38_agenc,1,0,"R",0);
      $pdf->cell(25,$alt,$r38_conta,1,1,"R",0);

      $agenciaregistro = $r38_agenc;
      $contaregistro = substr($r38_conta,0,(strlen($r38_conta) - 1));
      $dvcontaregistro = substr($r38_conta, (strlen($r38_conta) - 1), 1);
      $nomeregistro  = $r38_nome;
      $valorregistro  = $r38_liq; 
      $matricularegistro = $r38_regist;
      db_setaPropriedadesLayoutTxt($db_layouttxt, 3);
      $valortotal += $r38_liq;
    }
    $pdf->ln(2);
    $pdf->setfont('arial','b',8);
    $pdf->cell(180,$alt,"Totalização ","TLB",0,"R",1);
    $pdf->cell(20,$alt,$clfolha->numrows,"TB",0,"R",1);
    $pdf->cell(20,$alt,db_formatar($valortotal,"f"),"TB",0,"R",1);
    $pdf->cell(55,$alt,"","TRB",1,"C",1);
    db_setaPropriedadesLayoutTxt($db_layouttxt, 5);
    $pdf->Output("/tmp/blvreme.pdf",false,true);
  }

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table align="center" border="0">
  <form name="form1" method="post" action="">
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
  <tr>
    <td>
      <strong>Ano / Mês:</strong>
    </td>
    <td>
      <?
      $ano = db_anofolha();
      db_input('ano',4,0,true,'text',3,"");
      ?>
      <b>&nbsp;/&nbsp;</b>
      <?
      $mes = db_mesfolha();
      db_input('mes',2,0,true,'text',3,"");
      ?>
    </td>
  </tr>
      <tr>
        <td ><b>Recurso</b</td>
        <td >
         <?
           $x = array("f"=>"Fundef","m"=>"MDE","a"=>"ASPS","s"=>"FES","l"=>"Livre","t"=>"Todos");
           db_select('recurso',$x,true,4,"");
         ?>
	
	</td>
      </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input name="emite" id="emite" type="submit" value="Processar">
    </td>
  </tr>
</form>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_detectaarquivo(arquivo,pdf){
  listagem = arquivo+"#Download arquivo TXT (pagamento eletrônico)|";
  listagem+= pdf+"#Download relatório";
  js_montarlista(listagem,"form1");
}
</script>
<?
if(isset($emite)){
  if(isset($erro_msg)){
    db_msgbox($erro_msg);
  }else{
    echo "
          <script>js_detectaarquivo('/tmp/blvreme.txt','/tmp/blvreme.pdf');</script>
         ";
  }
}
?>
