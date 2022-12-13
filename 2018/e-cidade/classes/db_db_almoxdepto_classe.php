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

//MODULO: material
//CLASSE DA ENTIDADE db_almoxdepto
class cl_db_almoxdepto { 
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
   var $m92_codalmox = 0; 
   var $m92_depto = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 m92_codalmox = int4 = Codigo Almox. 
                 m92_depto = int4 = Depart. 
                 ";
   //funcao construtor da classe 
   function cl_db_almoxdepto() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_almoxdepto"); 
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
       $this->m92_codalmox = ($this->m92_codalmox == ""?@$GLOBALS["HTTP_POST_VARS"]["m92_codalmox"]:$this->m92_codalmox);
       $this->m92_depto = ($this->m92_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["m92_depto"]:$this->m92_depto);
     }else{
       $this->m92_codalmox = ($this->m92_codalmox == ""?@$GLOBALS["HTTP_POST_VARS"]["m92_codalmox"]:$this->m92_codalmox);
       $this->m92_depto = ($this->m92_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["m92_depto"]:$this->m92_depto);
     }
   }
   // funcao para inclusao
   function incluir ($m92_codalmox,$m92_depto){ 
      $this->atualizacampos();
       $this->m92_codalmox = $m92_codalmox; 
       $this->m92_depto = $m92_depto; 
     if(($this->m92_codalmox == null) || ($this->m92_codalmox == "") ){ 
       $this->erro_sql = " Campo m92_codalmox nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->m92_depto == null) || ($this->m92_depto == "") ){ 
       $this->erro_sql = " Campo m92_depto nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_almoxdepto(
                                       m92_codalmox 
                                      ,m92_depto 
                       )
                values (
                                $this->m92_codalmox 
                               ,$this->m92_depto 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "departamento que referenciam a algum almox. ($this->m92_codalmox."-".$this->m92_depto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "departamento que referenciam a algum almox. já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "departamento que referenciam a algum almox. ($this->m92_codalmox."-".$this->m92_depto) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m92_codalmox."-".$this->m92_depto;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m92_codalmox,$this->m92_depto));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,7166,'$this->m92_codalmox','I')");
       $resac = db_query("insert into db_acountkey values($acount,7167,'$this->m92_depto','I')");
       $resac = db_query("insert into db_acount values($acount,1190,7166,'','".AddSlashes(pg_result($resaco,0,'m92_codalmox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1190,7167,'','".AddSlashes(pg_result($resaco,0,'m92_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($m92_codalmox=null,$m92_depto=null) { 
      $this->atualizacampos();
     $sql = " update db_almoxdepto set ";
     $virgula = "";
     if(trim($this->m92_codalmox)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m92_codalmox"])){ 
       $sql  .= $virgula." m92_codalmox = $this->m92_codalmox ";
       $virgula = ",";
       if(trim($this->m92_codalmox) == null ){ 
         $this->erro_sql = " Campo Codigo Almox. nao Informado.";
         $this->erro_campo = "m92_codalmox";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m92_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m92_depto"])){ 
       $sql  .= $virgula." m92_depto = $this->m92_depto ";
       $virgula = ",";
       if(trim($this->m92_depto) == null ){ 
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "m92_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m92_codalmox!=null){
       $sql .= " m92_codalmox = $this->m92_codalmox";
     }
     if($m92_depto!=null){
       $sql .= " and  m92_depto = $this->m92_depto";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m92_codalmox,$this->m92_depto));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7166,'$this->m92_codalmox','A')");
         $resac = db_query("insert into db_acountkey values($acount,7167,'$this->m92_depto','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m92_codalmox"]))
           $resac = db_query("insert into db_acount values($acount,1190,7166,'".AddSlashes(pg_result($resaco,$conresaco,'m92_codalmox'))."','$this->m92_codalmox',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m92_depto"]))
           $resac = db_query("insert into db_acount values($acount,1190,7167,'".AddSlashes(pg_result($resaco,$conresaco,'m92_depto'))."','$this->m92_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     
   //  die($sql);
     
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "departamento que referenciam a algum almox. nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m92_codalmox."-".$this->m92_depto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "departamento que referenciam a algum almox. nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m92_codalmox."-".$this->m92_depto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m92_codalmox."-".$this->m92_depto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($m92_codalmox=null,$m92_depto=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m92_codalmox,$m92_depto));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,7166,'$m92_codalmox','E')");
         $resac = db_query("insert into db_acountkey values($acount,7167,'$m92_depto','E')");
         $resac = db_query("insert into db_acount values($acount,1190,7166,'','".AddSlashes(pg_result($resaco,$iresaco,'m92_codalmox'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1190,7167,'','".AddSlashes(pg_result($resaco,$iresaco,'m92_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_almoxdepto
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m92_codalmox != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m92_codalmox = $m92_codalmox ";
        }
        if($m92_depto != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m92_depto = $m92_depto ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "departamento que referenciam a algum almox. nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m92_codalmox."-".$m92_depto;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "departamento que referenciam a algum almox. nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m92_codalmox."-".$m92_depto;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m92_codalmox."-".$m92_depto;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_almoxdepto";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m92_codalmox=null,$m92_depto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_almoxdepto ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = db_almoxdepto.m92_depto";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = db_almoxdepto.m92_codalmox";
     $sql .= "      inner join db_depart as a on  a.coddepto = db_almox.m91_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m92_codalmox!=null ){
         $sql2 .= " where db_almoxdepto.m92_codalmox = $m92_codalmox "; 
       } 
       if($m92_depto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_almoxdepto.m92_depto = $m92_depto "; 
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
   function sql_query_file ( $m92_codalmox=null,$m92_depto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_almoxdepto ";
     $sql2 = "";
     if($dbwhere==""){
       if($m92_codalmox!=null ){
         $sql2 .= " where db_almoxdepto.m92_codalmox = $m92_codalmox "; 
       } 
       if($m92_depto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_almoxdepto.m92_depto = $m92_depto "; 
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
   function sql_query_inf ( $m92_codalmox=null,$m92_depto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_almoxdepto ";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = db_almoxdepto.m92_codalmox";
     $sql .= "      inner join db_depart as a on  a.coddepto = db_almox.m91_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m92_codalmox!=null ){
         $sql2 .= " where db_almoxdepto.m92_codalmox = $m92_codalmox "; 
       } 
       if($m92_depto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_almoxdepto.m92_depto = $m92_depto "; 
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
   function sql_query_instit ( $m92_codalmox=null,$m92_depto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_almoxdepto ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = db_almoxdepto.m92_depto";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = db_almoxdepto.m92_codalmox";
     $sql .= "      inner join db_depart db_departalmox on  db_departalmox.coddepto = db_almox.m91_depto";
		 $sql .= "      inner join db_departorg on db_departalmox.coddepto = db_departorg.db01_coddepto and db_departorg.db01_anousu = " . db_getsession("DB_anousu");
		 $sql .= "      inner join orcorgao  on orcorgao.o40_orgao = db_departorg.db01_orgao and orcorgao.o40_anousu = db_departorg.db01_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($m92_codalmox!=null ){
         $sql2 .= " where db_almoxdepto.m92_codalmox = $m92_codalmox "; 
       } 
       if($m92_depto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_almoxdepto.m92_depto = $m92_depto "; 
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
   function sql_query_almox( $m92_codalmox=null,$m92_depto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_almoxdepto ";
     $sql .= "      inner join db_almox  on  db_almox.m91_codigo = db_almoxdepto.m92_codalmox";
     $sql .= "      inner join db_depart on  coddepto = db_almox.m91_depto";
     $sql2 = "";
     if($dbwhere==""){
       if($m92_codalmox!=null ){
         $sql2 .= " where db_almoxdepto.m92_codalmox = $m92_codalmox "; 
       } 
       if($m92_depto!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_almoxdepto.m92_depto = $m92_depto "; 
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