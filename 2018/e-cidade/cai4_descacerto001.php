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

/****
* Cristian Tales
* 2005/1027
****/
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_sql.php");
include("classes/db_arrecad_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clrotulo = new rotulocampo;

if(isset($desconto)){
  if (!empty($DBtxt9)){
     $clarrecad = new cl_arrecad;
     $clarrecad->sql_record( "select * from arrecad 
		                                   inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
																			                      and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
                             		 where k00_numpre=$k00_numpre 
																   and k00_hist = 918 limit 1" );
     $record = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"));
     if( $clarrecad->numrows <> 0 ) {
       echo "<script>alert('Parcelamento ja foi concedido desconto.')</script>";
     }
     elseif(pg_numrows($record) != 0 ){ 
           $numpar = $k00_numpar;
           $receit = $k00_receit;
           $ttotal = 0;
           for($i=0;$i<pg_numrows($record);$i++){
             db_fieldsmemory($record,$i);
            if($numpar!=0 && $numpar == $k00_numpar ){
                if($receit!=0 && $receit == $k00_receit){
                    $ttotal += $vlrcor;
                }else if($receit==0){
                    $ttotal += $vlrcor;
                }
            }else if($numpar==0){
                if(($receit!=0) && ($receit == $k00_receit)){
                    $ttotal += $vlrcor;
                }else if($receit==0){
                    $ttotal += $vlrcor;
                }
            }
           }
           //echo $ttotal."<br>"; 
           $destot = 0;
           $vlrdes = 0;
           pg_query("begin");
           $erro = false;
           for($i=0;$i<pg_numrows($record);$i++){
             db_fieldsmemory($record,$i);
             $processa = false;
             if($numpar!=0 && $numpar == $k00_numpar ){
                if($receit!=0 && $receit == $k00_receit){
                    $processa = true;
                    $desconto = round($vlrcor * ( 100 / $ttotal ),2);
                    $vlrdes = round($DBtxt9 * ($desconto/100),2);
                    $destot = $destot + $vlrdes;
                }else if($receit==0){
                    $processa = true;
                    $desconto = round($vlrcor * ( 100 / $ttotal ),2);
                    $vlrdes = round($DBtxt9 * ($desconto/100),2);
                    $destot = $destot + $vlrdes;
                }
             } else if($numpar==0){
                if(($receit!=0) && ($receit == $k00_receit)){
                    $processa = true;
                    $desconto = round($vlrcor * ( 100 / $ttotal ),2);
                    $vlrdes = round($DBtxt9 * ($desconto/100),2);
                    $destot = $destot + $vlrdes;
                }else if($receit==0){
                    $processa = true;
                    $desconto = round($vlrcor * ( 100 / $ttotal ),2);
                    $vlrdes = round($DBtxt9 * ($desconto/100),2);
                    $destot = $destot + $vlrdes;
                }
             }
             if($processa == true){ 
                $valorcompl = 0;
                if($destot>$DBtxt9){
                    $valorcompl = $DBtxt9 - $destot;
                }
                $vlrdes += $valorcompl;
                $sql = "insert into arrecad
                        ( k00_numcgm, k00_dtoper, k00_receit, k00_hist,
                          k00_valor,  k00_dtvenc, k00_numpre, k00_numpar,
                          k00_numtot, k00_numdig, k00_tipo,   k00_tipojm )
                        values(
                         $k00_numcgm  ,'$k00_dtoper' , $k00_receit, 918   ,
                         $vlrdes*-1   ,'$k00_dtvenc' , $k00_numpre, $k00_numpar ,
                         '$k00_numtot', $k00_numdig  , $k00_tipo  , null)";
                $result = pg_query($sql);
                if($result == false){
                    $erro = true;
                    break;
                }
             }
           }
           $str_sql = "select sum(k00_valor) as flt_valor 
					               from arrecad 
												      inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
															                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
              				  where k00_numpre=$k00_numpre ";
           $res_valor = pg_query( $str_sql );
           db_fieldsmemory( $res_valor, 0 );
           if( $flt_valor < 1 ){
             $erro = true;
             echo "<script>alert('Processamento nao efetuado. Valor do desconto maior que o valor do débito.');</script>";
           }
           
//8160
           $record = debitos_numpre($k00_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"));
           if(pg_numrows($record) != 0){
                $ttotal = 0;
                for($i=0;$i<pg_numrows($record);$i++){
                    db_fieldsmemory($record,$i);
                    if($numpar!=0 && $numpar == $k00_numpar ){
                        $ttotal += $total;
                    }else{
                        $ttotal += $total;
                    }
                }
                if($ttotal==0 || $DBtxt8==100){
                    if($numpar!=0){
                        $sql = "insert into arrecant (
                                        k00_numcgm, k00_dtoper, k00_receit, k00_hist,
                                        k00_valor,  k00_dtvenc, k00_numpre, k00_numpar,
                                        k00_numtot, k00_numdig, k00_tipo,   k00_tipojm )
                                select
                                    k00_numcgm, k00_dtoper, k00_receit, k00_hist,
                                    k00_valor,  k00_dtvenc, k00_numpre, k00_numpar,
                                    k00_numtot, k00_numdig, k00_tipo,   k00_tipojm
                                from arrecad
																inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
																                     and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
                               where k00_numpre = $k00_numpre
                                 and k00_numpar = $numpar ";
                    }else{
                        $sql = "insert into arrecant (
                                        k00_numcgm, k00_dtoper, k00_receit, k00_hist,
                                        k00_valor,  k00_dtvenc, k00_numpre, k00_numpar,
                                        k00_numtot, k00_numdig, k00_tipo,   k00_tipojm )
                                select
                                    k00_numcgm, k00_dtoper, k00_receit, k00_hist,
                                    k00_valor,  k00_dtvenc, k00_numpre, k00_numpar,
                                    k00_numtot, k00_numdig, k00_tipo,   k00_tipojm
                                from arrecad
																     inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
                      																	  and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
                               where k00_numpre = $k00_numpre";
                    }
                    $result = pg_query($sql);
                    if(pg_affected_rows($result)==0){
                        $erro = true;
                    }
                    if($numpar!=0){
                        $sql = "delete from  arrecad where k00_numpre = $k00_numpre
                                    and k00_numpar = $numpar";
                    }else{
                        $sql = "delete from  arrecad where k00_numpre = $k00_numpre";
                    }
                    $result = pg_query($sql);
                    if(pg_affected_rows($result)==0){
                        $erro = true;
                    }

                    if($numpar!=0){
                        $sql = "insert into arrehist values($k00_numpre,$numpar,$k00_hist,
                            '".date("Y-m-d",db_getsession("DB_datausu"))."','".
                            date("H:i")."',".db_getsession("DB_id_usuario")."
                            ,'".$k00_histtxt."')";
                    }else{
                        $sql = "insert into arrehist values($k00_numpre,0,$k00_hist,
                            '".date("Y-m-d",db_getsession("DB_datausu"))."','".
                            date("H:i")."',".db_getsession("DB_id_usuario")."
                            ,'".$k00_histtxt."')";
                    }
                    $result = pg_query($sql);
                    if(pg_affected_rows($result)==0){
                        $erro = true;
                    }
                }
           }
           if($erro == false){
               pg_query("commit");
               echo "<script>alert('Processamento concluído!\\n Não esqueceça de fazer o REPARCELAMENTO.');</script>";
               db_redireciona("cai3_gerfinanc001.php");
           } else {
               echo "<script>alert('Processamento nao efetuado!');</script>";
               pg_query("rollback");
           }
           pg_query("end");
     }
   }
}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
    function js_calcula(){
      var perce = new Number(document.form1.DBtxt8.value);
      if(perce>100){
        alert('Percentual não poderá ser superior a 100%.');
        document.form1.DBtxt8.value = '100';
      }
      var perce = new Number(document.form1.DBtxt8.value);
      var valor = new Number(document.form1.k00_valor.value);
      valor = valor * (perce/100);
      document.form1.DBtxt9.value = valor.toFixed(2) ;
      document.getElementById('executa_desconto').style.visibility = 'visible';
    }
    function js_calculavalor(){
      var valor = new Number(document.form1.DBtxt9.value);
      if(valor>document.form1.k00_valor.value || valor < 0 ){
        alert('Valor maior que o débito.');
	return false;
        document.form1.DBtxt9.value = document.form1.k00_valor.value;
      }
      var valor1 = new Number(document.form1.k00_valor.value);
      var valor  = new Number(document.form1.DBtxt9.value);
      perce =  (valor*100)/valor1;
      document.form1.DBtxt8.value = perce.toFixed(2) ;
      document.getElementById('executa_desconto').style.visibility = 'visible';
    }
    function js_verifica(){
      if(document.form1.k00_histtxt.value==""){
        alert('O histórico do débito deverá ser preenchido.');
            return false;
      }
      var valor = new Number(document.form1.DBtxt9.value);
      if(valor==0){
        alert('Valor Zerado.');
            document.form1.DBtxt9.focus();
            return false;
      }
      return true;

    }
    function js_caljuros(){

      var valor = new Number(document.form1.tvlrjuros.value);
      var valortot = new Number(document.form1.DBtxt9.value);
      if(document.form1.descontojuros.checked){
        valor = valor + valortot;
        document.form1.DBtxt9.value = valor.toFixed(2);
      }else{
        valor =  valortot - valor ;
        document.form1.DBtxt9.value = valor.toFixed(2);
      }

    }
    function js_calmulta(){

      var valor = new Number(document.form1.tvlrmulta.value);
      var valortot = new Number(document.form1.DBtxt9.value);
      if(document.form1.descontojuros.checked){
        valor = valor + valortot;
        document.form1.DBtxt9.value = valor.toFixed(2);
      }else{
        valor =  valortot - valor ;
        document.form1.DBtxt9.value = valor.toFixed(2);
      }
    }
</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.v07_parcel.focus()" >
    <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <?
    if(isset($v07_parcel) and !empty($v07_parcel)){
        ?>
        </center>
        <table width="790" border="0" cellspacing="0" cellpadding="0">
            <form name="form1" action="" method="post" onSubmit="return js_verifica()">
                <tr>
                    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
                    <table width="686" height="27" border="0" cellpadding="0" cellspacing="0">
                        <?
                        $sql = "select v07_numpre, v07_totpar,
                                      sum( FC_CORRE(K00_RECEIT,K00_DTOPER,K00_VALOR,v07_dtlanc,extract(year from v07_dtlanc)::integer,K00_DTVENC) ) as flt_corre,
                                      sum( round( FC_CORRE(K00_RECEIT,K00_DTOPER,K00_VALOR,v07_dtlanc,extract(year from v07_dtlanc)::integer,K00_DTVENC) *
                                             FC_MULTA(K00_RECEIT,K00_DTVENC,v07_dtlanc,K00_DTOPER,extract(year from v07_dtlanc)::integer ),2 ) ) as flt_multa,
                                      sum( round( FC_CORRE(K00_RECEIT,K00_DTOPER,K00_VALOR,v07_dtlanc,extract(year from v07_dtlanc)::integer,K00_DTVENC) *
                                            FC_JUROS(K00_RECEIT,K00_DTVENC,v07_dtlanc,K00_DTOPER,FALSE,extract(year from v07_dtlanc)::integer ),2 ) ) as flt_juro
                                FROM TERMO
                                INNER JOIN TERMODIV     ON TERMO.V07_PARCEL     = TERMODIV.PARCEL
                                INNER JOIN DIVIDA       ON TERMODIV.CODDIV      = DIVIDA.V01_CODDIV
                       																 and divida.v01_instit =	".db_getsession('DB_instit') ."
                                INNER JOIN ARREOLD      ON ARREOLD.K00_NUMPRE   = DIVIDA.V01_NUMPRE AND ARREOLD.K00_NUMPAR = DIVIDA.V01_NUMPAR
                                WHERE TERMO.V07_PARCEL = $v07_parcel 
																  and	termo.v07_instit =".db_getsession('DB_instit') ."
                                GROUP BY v07_numpre, v07_totpar ";
                        //AND EXISTS ( SELECT * FROM ARRECAD WHERE ARRECAD.K00_NUMPRE = TERMO.V07_NUMPRE )
                        $result = pg_query( $sql );
			if( pg_numrows( $result ) > 0 ){
                            db_fieldsmemory( $result, 0 );
			}
			else {
			  $v07_numpre = 0;
			}
                        
                        if(!isset($k00_numpar)){
                            $numpar = 0 ;
                        }else{
                            $numpar = $k00_numpar;
                        }
                        if(isset($k00_receit)){
                            $receit = $k00_receit;
                        }else
                            $receit = 0;
                        $record = debitos_numpre($v07_numpre,0,0,db_getsession("DB_datausu"),db_getsession("DB_anousu"));

                        if($record!=false){
                            if(pg_numrows($record) != 0){
                                $sql = "select count(*) as int_totpar from ( select distinct k00_numpre, k00_numpar from arrecad where k00_numpre=$v07_numpre ) tt";
                                $result = pg_query( $sql );
                                db_fieldsmemory( $result, 0 );
                                
                                $matrec=array();
                                $matpar["0"]="Todas Parcelas ...";
                                $matrec["0"]="Todas Receitas ...";
                                $valor = 0;
                                $tvlrcor= 0;
                                $tvlrjuros= 0;
                                $tvlrmulta= 0;
                                $tvlrdesconto= 0;
                                $ttotal = 0;
                                for($i=0;$i<pg_numrows($record);$i++){
                                    db_fieldsmemory($record,$i);
                                    $matpar[$k00_numpar]= "$k00_numpar";
                                    if($numpar!=0 && $k00_numpar == $numpar) {
                                        $matrec[$k00_receit] ="$k02_descr";
                                        if($receit!=0 && $k00_receit == $receit){
                                            $valor += $total;
                                            $tvlrcor+= $vlrcor;
                                            $tvlrjuros+= $vlrjuros;
                                            $tvlrmulta+= $vlrmulta;
                                            $tvlrdesconto+= $vlrdesconto;
                                            $ttotal+= $total;
                                        }else if($receit==0){
                                            $valor += $total;
                                            $tvlrcor+= $vlrcor;
                                            $tvlrjuros+= $vlrjuros;
                                            $tvlrmulta+= $vlrmulta;
                                            $tvlrdesconto+= $vlrdesconto;
                                            $ttotal+= $total;
                                        }
                                    }else if($numpar==0){
                                        $matrec[$k00_receit] ="$k02_descr";
                                        if($receit!=0 && $k00_receit == $receit){
                                            $valor += $total;
                                            $tvlrcor+= $vlrcor;
                                            $tvlrjuros+= $vlrjuros;
                                            $tvlrmulta+= $vlrmulta;
                                            $tvlrdesconto+= $vlrdesconto;
                                            $ttotal+= $total;
                                        }else if($receit==0){
                                            $valor += $total;
                                            $tvlrcor+= $vlrcor;
                                            $tvlrjuros+= $vlrjuros;
                                            $tvlrmulta+= $vlrmulta;
                                            $tvlrdesconto+= $vlrdesconto;
                                            $ttotal+= $total;
                                        }
                                    }
                                }
                                $k00_valor = $valor;
                                $clarrecad = new cl_arrecad;
                                $result = $clarrecad->sql_record($clarrecad->sql_query("","cgm.z01_nome#arretipo.k00_descr",""," arrecad.k00_numpre = $k00_numpre"));
                                db_fieldsmemory($result,0);
                                ?>
                                <tr>
                                    <td width="110">Nome</td>
                                    <td width="214">
                                        <?
                                        $clrotulo->label("z01_nome");
                                        db_input('z01_nome',40,$Iz01_nome,true,'text',3)
                                        ?>
                                    </td>
                                    <td width="104">Valor:</td>
                                    <td width="258">
                                        <?
                                        $clrotulo->label("k00_valor");
                                        db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrcor')
                                        ?>
                                    </td>
                                    </tr>
                                    <tr>
                                    <td>TipoD&eacute;bito:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_descr");
                                        db_input('k00_descr',40,$Ik00_descr,true,'text',3)
                                        ?>
                                    </td>
                                    <td>Juros:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_valor");
                                        db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrjuros')
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>C&oacute;digo:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_numpre");
                                        db_input('k00_numpre',8,$Ik00_numpre,true,'text',3)
                                        ?>
                                    </td>
                                    <td>Multa:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_valor");
                                        db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrmulta')
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Parcela:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_numpar");
                                        $k00_numpar = $numpar;
                                        db_select('k00_numpar',$matpar,true,3," onchange='document.form1.submit();' ");
                                        ?>
                                    </td>
                                    <td>Desconto:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_valor");
                                        db_input('k00_valor',15,$Ik00_valor,true,'text',3,'','tvlrdesconto')
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Receita:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_receit");
                                        $k00_receit = $receit;
                                        db_select('k00_receit',$matrec,true,3," onchange='document.form1.submit();' ")
                                        ?>
                                    </td>
                                    <td>Total:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_valor");
                                        db_input('k00_valor',15,$Ik00_valor,true,'text',3,"",'ttotal');
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><div align="right"></div></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp; </td>
                                </tr>
                                <tr>
                                    <td> <div align="right"></div></td>
                                    <td align="right">Total Liberado Para desconto:</td>
                                    <td>
                                        <?
                                        $clrotulo->label("k00_valor");
                                        $k00_valor = $tvlrcor;
                                        db_input('k00_valor',15,$Ik00_valor,true,'text',3)
                                        ?>
                                    </td>
                                    <td>&nbsp; </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">Percentual: </td>
                                    <td>
                                        <?
                                        $clrotulo->label("DBtxt8");
                                        db_input('DBtxt8',15,$IDBtxt8,true,'text',3," onchange='js_calcula()'")
                                        ?>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                               <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">Valor Juro/Multa Original</td>
                                    <td>
				       <?
				       $flt_juromulta = $flt_juro+$flt_multa;
	                               db_input('flt_juromulta',15,$flt_juromulta,true,'text',3,"onfocus='js_calculavalor()'", "flt_juromulta");
				       ?>
 			            </td>
				</tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">Total de Parcelas:</td>
                                    <td>
				       <?
	                               db_input('v07_totpar',15,$v07_totpar,true,'text',3,"onfocus='js_calculavalor()'", "v07_totpar");
				       ?>
 			            </td>
				</tr> 			
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">Parcelas Restante:</td>
                                    <td>
				       <?
	                               db_input('int_totpar',15,$int_totpar,true,'text',3,"onfocus='js_calculavalor()'", "int_totpar");
				       ?>
 			            </td>
				</tr> 			
			
                                <tr>
                                    <td>&nbsp;</td>
                                    <td align="right">Acerto Juro/Multa</td>
                                    <td>
                                        <?
                                        $clrotulo->label("DBtxt9");
                                        $DBtxt9 = number_format( ( ( $flt_juro+$flt_multa )/$v07_totpar ) * $int_totpar, 2, ".","");
                                        db_input('DBtxt9',15,$IDBtxt9,true,'text',3,"onfocus='js_calculavalor()'", "DBtxt9");
                                        ?>
                                    </td>
                                    <td>&nbsp;</td>
                                </tr>
                                <tr align="center">
                                    <td colspan="4"><input name="calcular" type="button" id="calcular" value="Calcular Desconto" onclick="js_calculavalor()"></td>
                                </tr>
                                <tr align="center"><td colspan="4">&nbsp;</td></tr>
                                <tr align="center"><td colspan="4">&nbsp;</td></tr>
                                <tr align="center">
                                    <td colspan="4" >
                                    <table id="executa_desconto" style="visibility:hidden" width="77%" border="0" cellspacing="0">
                                        <tr>
                                            <td width="16%">Hist&oacute;rico:</td>
                                            <td width="84%">
                                                <?
                                                $clrotulo->label("k00_hist");
                                                $record = pg_exec("select * from histcalc order by k01_descr");
                                                db_selectrecord('k00_hist',$record,true,2,"","","");
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Observa&ccedil;&atilde;o:</td>
                                            <td>
                                                <?
                                                $clrotulo->label("k00_histtxt");
                                                db_textarea('k00_histtxt',5,70,$Ik00_histtxt,true,'text',2)
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td><input name="desconto" type="submit" id="desconto3" value="Lan&ccedil;ar Desconto"></td>
                                        </tr>
                                    </table></td>
                                </tr>
                                <tr align="center"><td colspan="4">&nbsp; </td></tr>
                                <tr align="center"><td colspan="4"></td></tr>
                                <?
                            }
                        }else{
                            $mostra=true;
                        }
                        ?>
                    </table>
                    </td>
                </tr>
            </form>
        </table>
        </center>
        <?
    }else{
        ?>
        <table width="100%" height="100%" border="0" cellspacing="0" bgcolor="#CCCCCC">
            <form name="form1" action="" method="post">
                <tr>
                    <td height="20" align="right">&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td width="52%" height="20" align="right"><strong>Deconto Juro/Multa Parcelamento:</strong></td>
                    <td width="48%">
                        <?
                        $clrotulo->label("v07_parcel");
                        db_input('v07_parcel',8,$Iv07_parcel,true,'text',2)
                        ?>
                    </td>
                </tr>
                <tr align="center">
                        <td height="19" colspan="2"><input type="submit" name="Submit" value="Enviar"></td>
                </tr>
                <tr align="center"><td colspan="2">&nbsp; </td></tr>
            </form>
        </table>
        <?
    }
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<?
if(isset($mostra)){
    echo "<script>alert('Parcelamento sem débito');</script>";
    db_redireciona("cai4_descacerto001.php");
}
?>