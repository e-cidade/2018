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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao = 1;
$botao    = 1;
$db_opcao = 1;

$verificachave = true;

if ( isset( $v50_advog ) ) {
  $veinclu = true;
} else {
  $veinclu = false;
}

$clpardiv        = new cl_pardiv;
$clinicial       = new cl_inicial;
$clinicialnomes  = new cl_inicialnomes;
$clinicialcert   = new cl_inicialcert;
$clinicialmov    = new cl_inicialmov;
$cladvog         = new cl_advog;
$clinicialnumpre = new cl_inicialnumpre;
$clarrecad       = new cl_arrecad;
$clcgm           = new cl_cgm;

$cladvog->rotulo->label();
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");

$clrotulo = new rotulocampo;
$clrotulo->label("v50_inicial");
$clrotulo->label("v50_advog");
$clrotulo->label("v54_descr");
$clrotulo->label("v53_descr");
$clrotulo->label("v50_codlocal");
$clrotulo->label("v51_certidao");

if ( isset( $v13_certidini ) && $v13_certidini != "" and isset( $v13_certidfim ) && $v13_certidfim == "") {
	$v13_certidfim = $v13_certidini;	
}

if ( isset( $nomechave ) && $nomechave != "") {
  $$nomechave = $valorchave;
}

if ( isset( $z01_numcgm) && $z01_numcgm != "" && $veinclu == false) {
   	 
  $sql = "SELECT V13_CERTID AS CERTIDAO 
            FROM (SELECT CERTID.V13_CERTID 
                    FROM CERTID 
                         INNER JOIN CERTDIV ON CERTDIV.V14_CERTID = CERTID.V13_CERTID 
                         INNER JOIN DIVIDA  ON DIVIDA.V01_CODDIV  = CERTDIV.V14_CODDIV 
                                           and v01_instit = ".db_getsession('DB_instit')."   
                   WHERE CERTID.V13_instit = ".db_getsession('DB_instit')." 
                     and exists ( select 1 
                                from arrenumcgm
                               where k00_numpre = v01_numpre
                                 and k00_numcgm = $z01_numcgm )
                   UNION 
                  SELECT CERTID.V13_CERTID 
                    FROM CERTID 
                         INNER JOIN CERTTER   ON CERTTER.V14_CERTID = CERTID.V13_CERTID 
                         INNER JOIN TERMO     ON TERMO.V07_PARCEL = CERTTER.V14_PARCEL 
                                             and TERMO.V07_instit = ".db_getsession('DB_instit')."
                         INNER JOIN TERMODIV  ON TERMODIV.PARCEL = TERMO.V07_PARCEL 
                         INNER JOIN DIVIDA    ON DIVIDA.V01_CODDIV = TERMODIV.CODDIV 
                                             and v01_instit = ".db_getsession('DB_instit')."   
                   WHERE exists ( select 1 
                                from arrenumcgm
                               where k00_numpre = v01_numpre
                                 and k00_numcgm = $z01_numcgm )
                     and CERTID.V13_instit = ".db_getsession('DB_instit')." 

                    union

                  SELECT CERTID.V13_CERTID 
                    FROM CERTID 
                   INNER JOIN (  select distinct       
                                        certter.v14_certid as v14_certid,
                                        v07_parcel
                                   from termo
                                        inner join certter     on certter.v14_parcel = termo.v07_parcel
                                        left  join termoreparc on v08_parcelorigem   = certter.v14_parcel
                                  where termo.v07_instit = ".db_getsession('DB_instit')." 
                                    and v08_parcelorigem is null
                                  union
                                 select distinct       
                                        certter.v14_certid,
                                        v08_parcelorigem as v07_parcel
                                   from termoreparc
                                        inner join certter on certter.v14_parcel = termoreparc.v08_parcel
                                  union      
                                 select distinct 
                                        certter.v14_certid, 
                                        v08_parcelorigem as v07_parcel 
                                   from termoreparc 
                                        inner join certter on certter.v14_parcel     = termoreparc.v08_parcelorigem
                                        inner join termo   on termoreparc.v08_parcel = termo.v07_parcel
                                  where v07_situacao = 2                                 
                               ) TERMO ON TERMO.V14_certid = CERTID.V13_CERTID 
                   INNER JOIN TERMODIV ON TERMODIV.PARCEL   = TERMO.V07_PARCEL 
                   INNER JOIN DIVIDA   ON DIVIDA.V01_CODDIV = TERMODIV.CODDIV 
                          and DIVIDA.V01_instit = ".db_getsession('DB_instit')."
                  WHERE  exists ( select 1 
                                from arrenumcgm
                               where k00_numpre = v01_numpre
                                 and k00_numcgm = $z01_numcgm )
                     and CERTID.V13_instit = ".db_getsession('DB_instit')." 

                 ) AS X 
      WHERE V13_CERTID NOT IN (SELECT INICIALCERT.V51_CERTIDAO 
                                 FROM INICIAL 
                                      INNER JOIN INICIALCERT ON INICIAL.V50_INICIAL = INICIALCERT.V51_INICIAL 
                                WHERE	INICIAL.V50_SITUACAO = 1) 
      ORDER BY V13_CERTID"; 
      
  $nomechave  = "z01_numcgm";
  $valorchave = $z01_numcgm;	    
} else if ( isset( $v13_certidini ) && $v13_certidini != "" and isset( $v13_certidfim ) && $v13_certidfim != "" && $veinclu == false ) {
  
  $sql = "SELECT '*'::CHAR(1) AS MARCA, 
                 V13_CERTID AS CERTIDAO 
           FROM ( SELECT CERTID.V13_CERTID 
                    FROM CERTID 
                         INNER JOIN CERTDIV  ON CERTDIV.V14_CERTID = CERTID.V13_CERTID 
                         INNER JOIN DIVIDA   ON DIVIDA.V01_CODDIV  = CERTDIV.V14_CODDIV 
                                            and DIVIDA.V01_instit  = ".db_getsession('DB_instit')." 
                   WHERE certid.v13_instit = ".db_getsession('DB_instit')."
                     and CERTID.V13_CERTID BETWEEN $v13_certidini and $v13_certidfim
                   UNION	
                  SELECT CERTID.V13_CERTID 
                    FROM CERTID 
                   INNER JOIN (  select distinct       
                                        certter.v14_certid as v14_certid,
                                        v07_parcel
                                   from termo
                                        inner join certter     on certter.v14_parcel = termo.v07_parcel
                                        left  join termoreparc on v08_parcelorigem   = certter.v14_parcel
                                  where termo.v07_instit = ".db_getsession('DB_instit')." 
                                    and v08_parcelorigem is null
                                  union
                                 select distinct       
                                        certter.v14_certid,
                                        v08_parcelorigem as v07_parcel
                                   from termoreparc
                                        inner join certter on certter.v14_parcel = termoreparc.v08_parcel
                                  union      
                                 select distinct 
                                        certter.v14_certid, 
                                        v08_parcelorigem as v07_parcel 
                                   from termoreparc 
                                        inner join certter on certter.v14_parcel     = termoreparc.v08_parcelorigem
                                        inner join termo   on termoreparc.v08_parcel = termo.v07_parcel
                                  where v07_situacao = 2                                 
                               ) TERMO ON TERMO.V14_certid = CERTID.V13_CERTID 
                   INNER JOIN TERMODIV ON TERMODIV.PARCEL   = TERMO.V07_PARCEL 
                   INNER JOIN DIVIDA   ON DIVIDA.V01_CODDIV = TERMODIV.CODDIV 
                          and DIVIDA.V01_instit = ".db_getsession('DB_instit')."
                  WHERE certid.v13_instit = ".db_getsession('DB_instit')." 
                    and CERTID.V13_CERTID BETWEEN $v13_certidini and $v13_certidfim
                  union
                 select distinct 
                        certter.v14_certid
                   from termoreparc 
                  inner join certter on certter.v14_parcel = termoreparc.v08_parcel
                  inner join certid  on certid.v13_certid  = certter.v14_certid
                  where CERTID.V13_CERTID BETWEEN $v13_certidini and $v13_certidfim             
                  ) AS X 
          WHERE V13_CERTID NOT IN (SELECT INICIALCERT.V51_CERTIDAO 
                                     FROM INICIAL 
                                    INNER JOIN INICIALCERT ON INICIAL.V50_INICIAL = INICIALCERT.V51_INICIAL 
                                    WHERE INICIAL.V50_SITUACAO = 1)
          ORDER BY V13_CERTID";
    
  $nomechave  = "v13_certid";
  $valorchave = @$v13_certid;
} else if ( isset( $j01_matric ) && $j01_matric != "" && $veinclu == false) {
  
  $sql = "SELECT V13_CERTID AS CERTIDAO 
            FROM (
                   SELECT CERTID.V13_CERTID 
                     FROM CERTID 
                          INNER JOIN CERTDIV     ON CERTDIV.V14_CERTID    = CERTID.V13_CERTID 
                          INNER JOIN DIVIDA      ON DIVIDA.V01_CODDIV     = CERTDIV.V14_CODDIV
                                                and	divida.v01_instit     = ".db_getsession('DB_instit')."
                          INNER JOIN ARREMATRIC  ON ARREMATRIC.K00_NUMPRE = DIVIDA.V01_NUMPRE
                    WHERE K00_MATRIC = $j01_matric 
                      and certid.v13_instit = ".db_getsession('DB_instit')." 
                    UNION 
                   SELECT CERTID.V13_CERTID 
                     FROM CERTID 
                          INNER JOIN CERTTER     ON CERTTER.V14_CERTID    = CERTID.V13_CERTID 
                          INNER JOIN TERMO       ON TERMO.V07_PARCEL      = CERTTER.V14_PARCEL 
                                                and	termo.v07_instit      = ".db_getsession('DB_instit')."
                          INNER JOIN ARREMATRIC  ON ARREMATRIC.K00_NUMPRE = TERMO.V07_NUMPRE
                    WHERE K00_MATRIC = $j01_matric 
                      and certid.v13_instit = ".db_getsession('DB_instit')." 
                 ) AS X 
           WHERE V13_CERTID NOT IN (SELECT INICIALCERT.V51_CERTIDAO 
                                      FROM INICIAL 
                                           INNER JOIN INICIALCERT ON INICIAL.V50_INICIAL = INICIALCERT.V51_INICIAL 
                                     WHERE INICIAL.V50_SITUACAO = 1 )
           ORDER BY V13_CERTID";  	  
} else if ( isset( $q02_inscr ) && $q02_inscr != "" && $veinclu == false ) {
  
  $sql = "SELECT V13_CERTID AS CERTIDAO 
            FROM (
                   SELECT CERTID.V13_CERTID 
                     FROM CERTID 
                          INNER JOIN CERTDIV    ON CERTDIV.V14_CERTID   = CERTID.V13_CERTID 
                          INNER JOIN DIVIDA     ON DIVIDA.V01_CODDIV    = CERTDIV.V14_CODDIV
                                               and divida.v01_instit    = ".db_getsession('DB_instit')."
                          INNER JOIN ARREINSCR  ON ARREINSCR.K00_NUMPRE = DIVIDA.V01_NUMPRE
                    WHERE K00_INSCR = $q02_inscr 
                      and certid.v13_instit = ".db_getsession('DB_instit')."
                    UNION 
                  SELECT CERTID.V13_CERTID 
                    FROM CERTID 
                         INNER JOIN CERTTER   ON CERTTER.V14_CERTID   = CERTID.V13_CERTID 
                         INNER JOIN TERMO     ON TERMO.V07_PARCEL     = CERTTER.V14_PARCEL 
                                             and termo.v07_instit     = ".db_getsession('DB_instit')."
                         INNER JOIN TERMODIV  ON TERMODIV.PARCEL      = TERMO.V07_PARCEL 
                         INNER JOIN DIVIDA    ON DIVIDA.V01_CODDIV    = TERMODIV.CODDIV 
                                             and divida.v01_instit    = ".db_getsession('DB_instit')."
                         INNER JOIN ARREINSCR ON ARREINSCR.K00_NUMPRE = DIVIDA.V01_NUMPRE
                   WHERE K00_INSCR = $q02_inscr 
                     and certid.v13_instit = ".db_getsession('DB_instit')."
                 ) AS X 
           WHERE V13_CERTID NOT IN (SELECT INICIALCERT.V51_CERTIDAO 
                                      FROM INICIAL 
                                           INNER JOIN INICIALCERT ON INICIAL.V50_INICIAL = INICIALCERT.V51_INICIAL 
                                     WHERE INICIAL.V50_SITUACAO = 1 )
           ORDER BY V13_CERTID";  	  
}

$numrows = 0;
if ($veinclu == false ) {
  
  $resulta = @db_query( $sql );
  $numrows = @pg_numrows( $resulta );
  //  if($clinicial->erro_status=="0"){
    //    $veinclu=true;
  //  }  
  if ( $numrows == 0 && $veinclu == false ) {
    db_redireciona("jur1_emiteinicial001.php?invalido=true");
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
<script>
function termo(qual,total){
  document.getElementById('termometro').innerHTML='processando registro... '+qual+' de '+total;
}
</script>

<style type="text/css">
<!--
td {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
}
input {
  font-family: Arial, Helvetica, sans-serif;
  font-size: 12px;
  height: 17px;
  border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
<tr> 
<td width="360" height="18">&nbsp;</td>
<td width="263">&nbsp;</td>
<td width="25">&nbsp;</td>
<td width="140">&nbsp;</td>
</tr>
</table>
<center>
	<table height="430" width="790" border="0" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc">
		<tr> 
			<td>&nbsp;</td>
		</tr> 
		<tr> 
			<td align="center" valign="top" bgcolor="#cccccc">     
				<?
					include("forms/db_frmemiteinicial.php");
				?>   
			</td>
		</tr>
	</table>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();



$clinicialnomes->v58_tipo="PP";

if(isset($incluir)){
  
  $sqlerro=false;
  $msg_inclusao="";
  
  $rsPardiv = $clpardiv->sql_record($clpardiv->sql_query_file(null,"v04_tipoinicial",null,"v04_instit = ".db_getsession('DB_instit') ) );
  if (pg_num_rows($rsPardiv) > 0 ) {
    db_fieldsmemory($rsPardiv,0);
  } else {
    db_msgbox(_M('tributario.juridico.db_frmemiteinicial.marque_certidao'));
    $sqlerro=true;
  }

   
  
  db_inicio_transacao();
  
  $gerou=false;
  $iniini = 0;
  $perc = 0;
  
  for($i=0; $i<$numcert; $i++){
    $x="certid".$i;
    
    if ($perc >= round($numcert / 500,1)) {
      $perc = round($i / $numcert * 100,1);
      echo "<script>termo($perc,100);</script>";
      flush();
      $perc = 0;
    } else {
      $perc = $perc + 0.2;
    }
    
    if(isset($$x) && $$x!=""){
      
      if ($nomechave == 'v13_certid') {
        $gera = true;
      } else {
        if ( $gerou == false ) {
          $gera = true;
          $gerou=true;
        } else {
          $gera = false;
        }
      }
      
      if ($gera == true) {
        
        $usuario=db_getsession("DB_id_usuario");
        $data=date("Y-m-d",db_getsession("DB_datausu"));
        $clinicial->v50_advog=$v50_advog;
        $clinicial->v50_data=$data;
        $clinicial->v50_id_login=$usuario;
        $clinicial->v50_codlocal=$v50_codlocal;
        $clinicial->v50_codmov="0";
        $clinicial->v50_situacao="1";
        $clinicial->v50_instit = db_getsession('DB_instit');
        $clinicial->incluir(null);
        if ($clinicial->erro_status==0){
          $sqlerro=true;
        }
        $erro=$clinicial->erro_msg;
        
      }
      
      $msg_inclusao=$clinicial->erro_msg;
      $inicial=$clinicial->v50_inicial;  
      if ($iniini == 0) $iniini = $inicial;
      
      $codinicial=$clinicial->v50_inicial;  
      
      if ($sqlerro==false){
        $clinicialcert->v51_certidao=$$x;  
        $clinicialcert->v51_inicial=$inicial;  
        $clinicialcert->incluir($inicial,$$x);  
        if ($clinicialcert->erro_status==0){
          $sqlerro=true;
          $erro=$clinicialcert->erro_msg;
        }
      }
      
      if ($sqlerro==false){
        $clinicialmov->atuinicialmov($inicial,"1");
        if ($clinicialmov->erro_status==0){
          $sqlerro=true;
        }
      }
      if ($sqlerro==false){
        $sql="select distinct k00_numcgm, k00_numpre 
        from (
        select distinct k00_numcgm, k00_numpre 
        from inicial
        inner join inicialcert 	   on v50_inicial=v51_inicial 
        inner join certid 	       on v13_certid=v51_certidao
        and v13_instit = ".db_getsession('DB_instit')."
        left outer join certdiv 	 on v14_certid=v13_certid
        left outer join divida 	   on v14_coddiv=v01_coddiv
        and v01_instit = ".db_getsession('DB_instit')."
        left outer join arrenumcgm on arrenumcgm.k00_numpre=v01_numpre
        where v50_inicial = $inicial and v50_instit = ".db_getsession('DB_instit')."
        union 
        select distinct k00_numcgm, k00_numpre 
        from inicial
        inner join inicialcert 	        on v50_inicial = v51_inicial 
        inner join certid 	            on v13_certid  = v51_certidao
        and v13_instit  = ".db_getsession('DB_instit')."
        left outer join certter	        on v14_certid=v51_certidao
        left outer join termo 	        on v07_parcel=v14_parcel
        and v07_instit = ".db_getsession('DB_instit')."
        left outer join arrenumcgm as x on x.k00_numpre=v07_numpre
        where v50_inicial = $inicial and v50_instit = ".db_getsession('DB_instit')."  ) as x";
        $result = db_query($sql);
        $numso=pg_numrows($result);
        for($xr=0;$xr<$numso;$xr++){
          db_fieldsmemory($result,$xr);
          if ($k00_numcgm == 0 or $k00_numpre == 0){
            continue;
          }
          $result_nomes=$clinicialnomes->sql_record($clinicialnomes->sql_query_file($inicial,$k00_numcgm));
          if($clinicialnomes->numrows==0){
            if ($sqlerro==false){
              $clinicialnomes->v58_inicial=$inicial;
              $clinicialnomes->v58_numcgm=$k00_numcgm;
              $clinicialnomes->incluir($inicial,$k00_numcgm);
              if ($clinicialnomes->erro_status==0){
                $sqlerro=true;
                $erro=$clinicialnomes->erro_msg;
                break;
              }
            }
          }
          if ($sqlerro==false){
            $result_existeininum=$clinicialnumpre->sql_record($clinicialnumpre->sql_query_file(null,"*",null,"v59_inicial=$inicial and v59_numpre=$k00_numpre"));
            if ($clinicialnumpre->numrows==0){
              $clinicialnumpre->v59_inicial=$inicial;
              $clinicialnumpre->v59_numpre=$k00_numpre;
              $clinicialnumpre->incluir($inicial,$k00_numpre);
              $numpre=$k00_numpre;
              if ($clinicialnumpre->erro_status==0){
                $sqlerro=true;
                $erro=$clinicialnumpre->erro_msg;
                break;
              }
            }
          }


          if ($sqlerro==false){
            $clarrecad->k00_tipo=$v04_tipoinicial;
            $clarrecad->alterar_arrecad("k00_numpre=$numpre");
            if ($clarrecad->erro_status==0){
              $sqlerro=true;
              $erro=$clarrecad->erro_msg;
              break;
            }
          }
        }
        
      }
      
    } // fim do if isset variavel da certidao
  } // fim do for
  
  $msg_inclusao =  ($inicial - $iniini + 1) . " iniciais geradas: " . $iniini . " a "  . $inicial . "." ;
  $veinclu=true;

  db_fim_transacao($sqlerro);
  $verificachave=false;  
  
}

if(isset($incluir)){
  if($clinicial->erro_status=="0"){
    $clinicial->erro(true,false);
    if($clinicial->erro_campo!=""){
      echo "<script> document.form1.".$clinicial->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clinicial->erro_campo.".focus();</script>";
    }
  }else{
    $clinicial->erro_msg=$msg_inclusao;
    if ($sqlerro==false&&$msg_inclusao!=""){
      db_msgbox($msg_inclusao);
      ?>
      <script>
      function js_AbreJanelaRelatorio() { 
        if(confirm('Emitir iniciais?')==true){
          jan = window.open('div2_inicial_002.php?v50_inicial=<?=$iniini?>&v50_inicial_fim=<?=$inicial?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
          jan.moveTo(0,0);
        }
      }
      
      js_AbreJanelaRelatorio();  
      
      </script>
      <?   
      
      
      db_redireciona("jur1_emiteinicial001.php");
    }else{
      db_msgbox($erro);
    }
  }
}

?>