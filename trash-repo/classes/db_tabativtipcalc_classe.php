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

//MODULO: issqn
//CLASSE DA ENTIDADE tabativtipcalc
class cl_tabativtipcalc { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $q11_inscr = 0; 
   var $q11_seq = 0; 
   var $q11_tipcalc = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 q11_inscr = int4 = inscricao 
                 q11_seq = int4 = sequencia 
                 q11_tipcalc = int4 = tipo de calculo 
                 ";
   //funcao construtor da classe 
   function cl_tabativtipcalc() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tabativtipcalc"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->q11_inscr = ($this->q11_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_inscr"]:$this->q11_inscr);
       $this->q11_seq = ($this->q11_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_seq"]:$this->q11_seq);
       $this->q11_tipcalc = ($this->q11_tipcalc == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_tipcalc"]:$this->q11_tipcalc);
     }else{
       $this->q11_inscr = ($this->q11_inscr == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_inscr"]:$this->q11_inscr);
       $this->q11_seq = ($this->q11_seq == ""?@$GLOBALS["HTTP_POST_VARS"]["q11_seq"]:$this->q11_seq);
     }
   }
   // funcao para inclusao
   function incluir ($q11_inscr,$q11_seq){ 
      $this->atualizacampos();
     if($this->q11_tipcalc == null ){ 
       $this->q11_tipcalc = "0";
     }
       $this->q11_inscr = $q11_inscr; 
       $this->q11_seq = $q11_seq; 
     if(($this->q11_inscr == null) || ($this->q11_inscr == "") ){ 
       $this->erro_sql = " Campo q11_inscr nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->q11_seq == null) || ($this->q11_seq == "") ){ 
       $this->erro_sql = " Campo q11_seq nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tabativtipcalc(
                                       q11_inscr 
                                      ,q11_seq 
                                      ,q11_tipcalc 
                       )
                values (
                                $this->q11_inscr 
                               ,$this->q11_seq 
                               ,$this->q11_tipcalc 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->q11_inscr."-".$this->q11_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->q11_inscr."-".$this->q11_seq) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q11_inscr."-".$this->q11_seq;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->q11_inscr,$this->q11_seq));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,298,'$this->q11_inscr','I')");
       $resac = db_query("insert into db_acountkey values($acount,299,'$this->q11_seq','I')");
       $resac = db_query("insert into db_acount values($acount,68,298,'','".AddSlashes(pg_result($resaco,0,'q11_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,68,299,'','".AddSlashes(pg_result($resaco,0,'q11_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,68,300,'','".AddSlashes(pg_result($resaco,0,'q11_tipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($q11_inscr=null,$q11_seq=null) { 
      $this->atualizacampos();
     $sql = " update tabativtipcalc set ";
     $virgula = "";
     if(trim($this->q11_inscr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_inscr"])){ 
       $sql  .= $virgula." q11_inscr = $this->q11_inscr ";
       $virgula = ",";
       if(trim($this->q11_inscr) == null ){ 
         $this->erro_sql = " Campo inscricao nao Informado.";
         $this->erro_campo = "q11_inscr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q11_seq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_seq"])){ 
       $sql  .= $virgula." q11_seq = $this->q11_seq ";
       $virgula = ",";
       if(trim($this->q11_seq) == null ){ 
         $this->erro_sql = " Campo sequencia nao Informado.";
         $this->erro_campo = "q11_seq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->q11_tipcalc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["q11_tipcalc"])){ 
        if(trim($this->q11_tipcalc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["q11_tipcalc"])){ 
           $this->q11_tipcalc = "0" ; 
        } 
       $sql  .= $virgula." q11_tipcalc = $this->q11_tipcalc ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($q11_inscr!=null){
       $sql .= " q11_inscr = $this->q11_inscr";
     }
     if($q11_seq!=null){
       $sql .= " and  q11_seq = $this->q11_seq";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->q11_inscr,$this->q11_seq));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,298,'$this->q11_inscr','A')");
         $resac = db_query("insert into db_acountkey values($acount,299,'$this->q11_seq','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_inscr"]))
           $resac = db_query("insert into db_acount values($acount,68,298,'".AddSlashes(pg_result($resaco,$conresaco,'q11_inscr'))."','$this->q11_inscr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_seq"]))
           $resac = db_query("insert into db_acount values($acount,68,299,'".AddSlashes(pg_result($resaco,$conresaco,'q11_seq'))."','$this->q11_seq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["q11_tipcalc"]))
           $resac = db_query("insert into db_acount values($acount,68,300,'".AddSlashes(pg_result($resaco,$conresaco,'q11_tipcalc'))."','$this->q11_tipcalc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->q11_inscr."-".$this->q11_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->q11_inscr."-".$this->q11_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->q11_inscr."-".$this->q11_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($q11_inscr=null,$q11_seq=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($q11_inscr,$q11_seq));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,298,'$q11_inscr','E')");
         $resac = db_query("insert into db_acountkey values($acount,299,'$q11_seq','E')");
         $resac = db_query("insert into db_acount values($acount,68,298,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_inscr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,68,299,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_seq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,68,300,'','".AddSlashes(pg_result($resaco,$iresaco,'q11_tipcalc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tabativtipcalc
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($q11_inscr != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q11_inscr = $q11_inscr ";
        }
        if($q11_seq != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " q11_seq = $q11_seq ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$q11_inscr."-".$q11_seq;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$q11_inscr."-".$q11_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$q11_inscr."-".$q11_seq;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:tabativtipcalc";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   
   
	function sql_query ( $q11_inscr=null,$q11_seq=null,$campos="*",$ordem=null,$dbwhere="",$iAnoUsu = null) {
		
   	$sql = "select ";
   	if($campos != "*" ){
   		$campos_sql = split("#",$campos);
   		$virgula = "";
   		for($i=0;$i<sizeof($campos_sql);$i++){
   			$sql .= $virgula.$campos_sql[$i];
   			$virgula = ",";
   		}
   	}else{
   		$sql .= $campos;
   	}
   	 
   	if (empty($iAnoUsu)) {
			$iAnoUsu = db_getsession('DB_anousu');
   	}
   	 
   	$sql .= " from tabativtipcalc 																																	";
   	$sql .= "      inner join tabativ       on  tabativ.q07_inscr      = tabativtipcalc.q11_inscr   ";
   	$sql .= "                              and  tabativ.q07_seq        = tabativtipcalc.q11_seq     ";
   	$sql .= "      inner join tipcalc       on  tipcalc.q81_codigo     = tabativtipcalc.q11_tipcalc ";
   	$sql .= "      inner join tipcalcexe    on  tipcalcexe.q83_tipcalc = tipcalc.q81_codigo				  ";
   	$sql .= "                              and  tipcalcexe.q83_anousu  = {$iAnoUsu}                 ";
   	$sql .= "      inner join issbase       on  issbase.q02_inscr      = tabativ.q07_inscr          ";
   	$sql .= "      inner join ativid        on  ativid.q03_ativ        = tabativ.q07_ativ           ";
   	$sql .= "      inner join issbase  as a on  a.q02_inscr            = tabativ.q07_inscr          ";
   	$sql .= "      inner join ativid  as b  on  b.q03_ativ             = tabativ.q07_ativ           ";
   	$sql .= "      inner join cadcalc       on  cadcalc.q85_codigo     = tipcalc.q81_cadcalc        ";
   	$sql .= "      inner join cadvencdesc   on  cadvencdesc.q92_codigo = tipcalcexe.q83_codven      ";
   	$sql .= "      inner join geradesc      on  geradesc.q89_codigo    = tipcalc.q81_gera           ";
   	$sql2 = "                                                                                       ";
   	if($dbwhere==""){
   		if($q11_inscr!=null ){
   			$sql2 .= " where tabativtipcalc.q11_inscr = $q11_inscr ";
   		}
   		if($q11_seq!=null ){
   			if($sql2!=""){
   				$sql2 .= " and ";
   			}else{
   				$sql2 .= " where ";
   			}
   			$sql2 .= " tabativtipcalc.q11_seq = $q11_seq ";
   		}
   	}else if($dbwhere != ""){
   		$sql2 = " where $dbwhere";
   	}
   	$sql .= $sql2;
   	if($ordem != null ){
   		$sql .= " order by ";
   		$campos_sql = split("#",$ordem);
   		$virgula = "";
   		for($i=0;$i<sizeof($campos_sql);$i++){
   			$sql .= $virgula.$campos_sql[$i];
   			$virgula = ",";
   		}
   	}
   	return $sql;
   }
   
   
   function sql_query_file ( $q11_inscr=null,$q11_seq=null,$campos="*",$ordem=null,$dbwhere=""){
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from tabativtipcalc ";
     $sql2 = "";
     if($dbwhere==""){
       if($q11_inscr!=null ){
         $sql2 .= " where tabativtipcalc.q11_inscr = $q11_inscr "; 
       } 
       if($q11_seq!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " tabativtipcalc.q11_seq = $q11_seq "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>