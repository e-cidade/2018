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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
require(modification("libs/db_utils.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_inssirf_classe.php"));
include(modification("classes/db_rhpessoal_classe.php"));
include(modification("classes/db_rhlota_classe.php"));
include(modification("classes/db_rhlocaltrab_classe.php"));
include(modification("dbforms/db_classesgenericas.php"));


$aux           = new cl_arquivo_auxiliar;
$clinssirf     = new cl_inssirf;
$clrhpessoal   = new cl_rhpessoal;
$clrhlota      = new cl_rhlota;
$clrhlocaltrab = new cl_rhlocaltrab;


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<script>      

   
  function js_emite(){
  
    var aInstituicoes  = oViewInstituicao.getInstituicoesSelecionadas(true);

    if (document.form1.DBtxt36.value == "") {        

      alert ("Ano Folha inválido");
      document.form1.DBtxt36.focus();
      return false; 
    } else if (document.form1.DBtxt35.value == "" ) {

      document.form1.DBtxt35.focus();
      alert ("Mês Folha inválido.");
      return false;
    } else if (document.form1.DBtxt23.value == "") {

      alert ("Ano inicial inválido.");
      document.form1.DBtxt23.focus();
      return false;
    } else if (document.form1.DBtxt25.value == "" ) {

      document.form1.DBtxt25.focus();
      alert ("Mês inicial inválido.");
      return false;
    } else if (document.form1.DBtxt24.value == "") {

      document.form1.DBtxt24.focus();
      alert ("Ano final inválido.");
      return false;
    } else if (document.form1.DBtxt26.value == "") {

      document.form1.DBtxt26.focus();
      alert ("Mês final inválido.");
      return false;
    } else if (document.form1.DBtxt23.value > document.form1.DBtxt24.value){

      document.form1.DBtxt23.focus();
      alert ("Ano Inicial deve ser menor que Ano Final.");
      return false;
    } else if (document.form1.DBtxt36.value > document.form1.ano.value){

      document.form1.DBtxt36.focus();
      alert ("Ano Folha Inválido.");
      return false;
    } else if (document.form1.DBtxt35.value > document.form1.mes.value && document.form1.DBtxt36.value >= document.form1.ano.value){

      document.form1.DBtxt35.focus();
      alert ("Data Folha Inválida.");
      return false;
    } else if (parseInt(document.form1.DBtxt23.value) ==  parseInt(document.form1.DBtxt24.value) && parseInt(document.form1.DBtxt25.value) > parseInt(document.form1.DBtxt26.value)){
      
      document.form1.DBtxt25.focus();
      alert ("Data Inicial deve ser menor que Data Final.");
      return false;
    } else if (aInstituicoes.length < 1) {

      alert ("Selecione pelo menos uma instituição.");
      return false;
    } else {

    qry  = "?anofolha="     + document.form1.DBtxt36.value;
    qry += "&mesfolha="     + document.form1.DBtxt35.value;
    qry += "&anoini="       + document.form1.DBtxt23.value;
    qry += "&mesini="       + document.form1.DBtxt25.value;
    qry += "&anofin="       + document.form1.DBtxt24.value;
    qry += "&mesfin="       + document.form1.DBtxt26.value;
    qry += "&sTipoEmissao=" + document.form1.sTipoEmissao.value;
    qry += "&tipo_res="     + document.form1.tipo_res.value;
    qry += "&tipo_fil="     + document.form1.tipo_fil.value;
    qry += "&tipo_fil="     + document.form1.tipo_fil.value;
    qry += "&instituicoes=" + aInstituicoes.join();
    
    switch (document.form1.tipo_res.value){
       case "m":
           if(document.form1.tipo_fil.value == 'i'){
             if(document.form1.mati.value == "" && document.form1.matf.value == "" ){ 
               alert("Matrícula Inválida.");
               document.form1.mati.focus();
               document.form1.mati.select();
               return false;
             }else{ 
               qry += "&campini=" +document.form1.mati.value;
               qry += "&campfin=" +document.form1.matf.value;
             } 
           }else if(document.form1.tipo_fil.value == 's'){
             if(document.form1.tipoSelMatric.value != ""){
               js_retornalista('tipoSelMatric');
               qry += "&listaSel=" +listaSeleciona;
             }else{
               alert("Selecione ao menos uma matrícula.");
               document.form1.rh01_regist.focus();
               document.form1.rh01_regist.select();
               return false;
             }
           }
       break;
       case "l":
           if(document.form1.tipo_fil.value == 'i'){
             if(document.form1.lotai.value == "" && document.form1.lotaf.value == "" ){ 
               alert("Lotação Inválido.");
               document.form1.lotai.focus();
               document.form1.lotai.select();
               return false;
             }else{ 
               qry += "&campini=" +document.form1.lotai.value;
               qry += "&campfin=" +document.form1.lotaf.value;
             }
           }else if(document.form1.tipo_fil.value == 's'){
             if(document.form1.tipoSelLota.value != ""){
             js_retornalista('tipoSelLota');
             qry += "&listaSel=" +listaSeleciona;
             }else{
               alert("Selecione ao menos uma lotação.");
               document.form1.r70_codigo.focus();
               document.form1.r70_codigo.select();
               return false;
            } 
           }
       break;
       case "t":
           if(document.form1.tipo_fil.value == 'i'){
             if(document.form1.locai.value == "" && document.form1.locaf.value == "" ){ 
               alert("Local Inválido.");
               document.form1.locai.focus();
               document.form1.locai.select();
               return false;
             }else{ 
               qry += "&campini=" +document.form1.locai.value;
               qry += "&campfin=" +document.form1.locaf.value;
             }
           }else if(document.form1.tipo_fil.value == 's'){
             if(document.form1.tipoSelLoca.value != ""){
               js_retornalista('tipoSelLoca')
               qry += "&listaSel=" +listaSeleciona;
             }else{
               alert("Selecione ao menos um local de trabalho.");
               document.form1.rh55_estrut.focus();
               document.form1.rh55_estrut.select();
               return false;
             }
           }
       break;
    }
    qry += "&ordem="     +document.form1.ordem.value;
    qry += "&prev="      +document.form1.prev.value;
 
   janRel = window.open('pes2_relextratorpps002.php'+qry,'','location=0');
  } 
 }
</script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body onLoad="a=1; js_escondetag();" >
<?
 include_once(modification("forms/db_frmrelextratorpps.php"));
?>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>