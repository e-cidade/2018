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
include("dbforms/db_funcoes.php");
include("classes/db_assenta_classe.php");
db_postmemory($HTTP_POST_VARS);
$classenta = new cl_assenta;
$rotulocampo = new rotulocampo;
$rotulocampo->label("rh01_regist");
$rotulocampo->label("z01_nome");

if(isset($rh01_regist) && trim($rh01_regist) != ""){
  $result_assenta = $classenta->sql_record($classenta->sql_query_tipo(null, " * ", " h16_dtconc, h16_assent", "h16_regist = " . $rh01_regist . " and h12_efetiv <> 'N' and h12_reltot > 1"));
  if($classenta->numrows > 0){
    db_fieldsmemory($result_assenta, 0);
    $datai_dia = db_subdata($h16_dtconc,"d");
    $datai_mes = db_subdata($h16_dtconc,"m");
    $datai_ano = db_subdata($h16_dtconc,"A");
    
    $dataf_dia = db_subdata($h16_dtconc,"d");
    $dataf_mes = db_subdata($h16_dtconc,"m");
    $dataf_ano = db_subdata($h16_dtconc,"A");
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
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table  align="center">
  <form name="form1" method="post" action="" >
  <tr>
    <td align="right" title="<?=$Trh01_regist?>">
      <?
      db_ancora(@$Lrh01_regist, "js_pesquisarh01_regist(true);", 1);
      ?>
    </td>
    <td>
      <?
      db_input('rh01_regist', 8, $Irh01_regist, true, 'text', 1, " onchange='js_pesquisarh01_regist(false);'")
      ?>
      <?
      db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" title="Número do processo">
      <b>Processo:</b>
    </td>
    <td>
      <?
      db_input('processo', 8, 1, true, 'text', 1, "")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Período certidão" align="right">
      <b>Período:</b>
    </td>
    <td nowrap>
      <?
      db_inputdata("datai", @$datai_dia, @$datai_mes, @$datai_ano, true, 'text', 1);
      ?>
      <b>&nbsp;a&nbsp;</b>
      <?
      db_inputdata("dataf", @$dataf_dia, @$dataf_mes, @$dataf_ano, true, 'text', 1);
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input name="relatorio" id="relatorio" type="button" value="Relatório" onclick="js_emite();" >
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
function js_emite(){
  gry = "?pcount=0"
  qry+= "&regist="+ document.form1.rh01_regist.value;
  qry+= "&certinic=" + document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
  qry+= "&datacert=" + document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value;
  qry+= "&num_proc=" + document.form1.processo.value;
  qry+= "&tipo_certd=" + document.form1.tipo_certd.value;
  jan = window.open('rec2_gradeefetividade002.php' + qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?funcao_js=parent.js_mostrapessoal1|rh01_regist|z01_nome&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
  }else{
    if(document.form1.rh01_regist.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoal.php?pesquisa_chave='+document.form1.rh01_regist.value+'&funcao_js=parent.js_mostrapessoal&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
    }else{
      document.form1.z01_nome.value = '';
      document.form1.submit();
    }
  }
}
function js_mostrapessoal(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ''; 
  }else{
    document.form1.submit();
  }
}
function js_mostrapessoal1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value   = chave2;
  db_iframe_rhpessoal.hide();
  document.form1.submit();
}
function js_relatorio2(){
  var F = document.form1;
  var datai = "";
  var dataf = "";
  if(F.datai_dia.value != "" && F.datai_mes.value != "" && F.datai_ano.value != ""){
    datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
  }
  if(F.dataf_dia.value != "" && F.dataf_mes.value != "" && F.dataf_ano.value != ""){
    dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;
  }
  if(datai == "" && dataf == ""){
    alert("Informe o período de admissão.");
    F.datai_dia.focus();
  }else{
    qry = "?datai="+datai;
    qry+= "&dataf="+dataf;
    qry+= "&ordem="+F.ordem.value;
    qry+= "&regime="+F.regime.value;
    if(F.listaponto.checked == true){
      qry+= "&fixo=s";
    }else{
      qry+= "&fixo=n";
    }
   qry+= "&lota="+F.lota.value;
//    jan = window.open('rec2_gradeefetividade002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scro
//    jan.moveTo(0,0);
  }
}
</script>
<!--
*
**************
FUNCTION RH551
**************
*
*****************************************************************************
*
*
*  MODULO    : PESSOAL (RECURSOS HUMANOS) 
*  PROGRAMA  : RH551
*  DESCRICAO : Grade de efetividade
*
*****************************************************************************
*
   PRIVATE MATRIC
   CERT = .F.
   BB_WINDOW(1,07,01,11,77)
   FINAL_PAG = .F.
   TIPO_CERTD = .T.
DATA_INIC_CERT = .T.
public primeira_vez
PRIMEIRA_VEZ = .T.
*
      DATACERT = DATE()  // Leandro 11/03/2003
      NOME     = SPACE(40)
      VOLTA    = .T.
      NUM_PROC = SPACE(6)
      STORE 0 TO PAG, MATRIC, MATRAC
      //DATACERT = DATE()
      @ 08,02 SAY "Registro (grade) ...: [      - ]  ["+SPACE(38)+"]"
      @ 09,02 SAY "Processo ...........: [      ]"
      @ 10,02 SAY "Periodo Certidao ...: [  /  /    ] a [  /  /    ]"
      @ 08,25 GET MATRIC PICT "999999" 
      READ
      IF EMPTY(MATRIC)
        @ 08,37 GET NOME PICT "@!S38"
        READ
        IF EMPTY(NOME)
           EXIT
        ELSE
           NUMCGM = BB_READCGM(NOME,3,1)
           IF LASTKEY() = 27
              LOOP
           ENDIF
           DB_SELECT("PESSOAL","SELECT * FROM PESSOAL" + BB_CONDICAOSUBPES("rh01_") + " AND rh01_NUMCGM = " + DB_SQLFORMAT(STR(NUMCGM,6)))
           *
           REG = RECNO()
           COUNT TO IND REST WHILE PESSOAL->rh01_NUMCGM = NUMCGM
           GO REG
           BROWSE = IND > 1
           IF !BROWSE
              MATRIC = PESSOAL->rh01_REGIST
           ENDIF
           DO WHILE .T.
              IF BROWSE
                 SELECT "PESSOAL"
                 GO REG
                 MATRIC = BROW_PESS(12,04,IND)
                 IF LASTKEY() = 27 .OR. LASTKEY() = 13
                    EXIT
                 ENDIF
              ELSE
                 EXIT 
              ENDIF
           ENDDO
           IF LASTKEY() = 27
              LOOP
           ENDIF 
           NOME = TRANSACAO->Z01_NOME
        ENDIF
      ELSE
        *
        IF !DB_SELECT("PESSOAL","SELECT * FROM PESSOAL" + BB_CONDICAOSUBPES("rh01_") + " AND rh01_REGIST = " + DB_SQLFORMAT(STR(MATRIC,6)))
        *
        ***IF EOF()
           BB_MENS("Matricula do funcionario nao cadastrado",.T.)
           LOOP
        ENDIF
        *
        *
        IF !DB_SELECT("CGM","SELECT * FROM CGM WHERE Z01_NUMCGM = " + DB_SQLFORMAT(STR(PESSOAL->rh01_NUMCGM,6)))
        *
           BB_MENS("No do CGM do funcionario nao cadastrado",.T.)
           LOOP
        ENDIF
        *
        NOME    = CGM->Z01_NOME
        NUMCGM  = CGM->Z01_NUMCGM 
        *
      ENDIF
      *
      @ 08,25 SAY STR(MATRIC,6)+"-"+MOD11(STR(MATRIC,6))
      @ 08,37 SAY SUBSTR(NOME,1,38)
      @ 09,25 GET NUM_PROC PICT "999999"
      READ
      *
      AREAS = ALIAS()
      *
      CSQL  = "SELECT * FROM ASSENTA INNER JOIN TIPOASSE ON H12_ASSENT = H16_ASSENT WHERE  H16_REGIST = " + DB_SQLFORMAT(STR(PESSOAL->rh01_REGIST,6))
      CSQL += " AND H12_EFETIV <> 'N' AND H12_RELTOT > 1 ORDER BY H16_DTCONC,H16_ASSENT "

      DB_SELECT("ASSENTA",CSQL)
      *
      SELECT AREAS
      RECNO_ASSENTA_INICIAL = RECNO()
      *
      CERTINIC = ASSENTA->H16_DTCONC
      @ 10,25 GET CERTINIC VALID !EMPTY(DATACERT)
      READ
      IF CERTINIC != ASSENTA->H16_DTCONC
         TIPO_CERTD = .F.
      ENDIF   
      @ 10,40 GET DATACERT VALID !EMPTY(DATACERT) .AND. DATACERT > CERTINIC
      READ
      ANO_FIM = VAL(SUBSTR(DTOC(DATACERT),-4))
      *
      IF !DB_SELECT("PESSOAL","SELECT * FROM PESSOAL" + BB_CONDICAOSUBPES("rh01_") + " AND rh01_NUMCGM = " + DB_SQLFORMAT(STR(NUMCGM,6)))
         BB_MENS("No do CGM deste funcionario nao cadastrado",.T.)
         LOOP
      ENDIF
      *
      IF BB_PERG2("Confirma emissao da grade (s/n)","S","N") = 2
         LOOP
      ENDIF 
   *
   -->