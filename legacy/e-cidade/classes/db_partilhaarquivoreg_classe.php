<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: juridico
//CLASSE DA ENTIDADE partilhaarquivoreg
class cl_partilhaarquivoreg { 
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
   var $v79_sequencial = 0; 
   var $v79_partilhaarquivo = 0; 
   var $v79_processoforopartilhacusta = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v79_sequencial = int4 = Sequencial 
                 v79_partilhaarquivo = int4 = Arquivo de Remessa 
                 v79_processoforopartilhacusta = int4 = Custa da partilha do processo 
                 ";
   //funcao construtor da classe 
   function cl_partilhaarquivoreg() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("partilhaarquivoreg"); 
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
       $this->v79_sequencial = ($this->v79_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v79_sequencial"]:$this->v79_sequencial);
       $this->v79_partilhaarquivo = ($this->v79_partilhaarquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["v79_partilhaarquivo"]:$this->v79_partilhaarquivo);
       $this->v79_processoforopartilhacusta = ($this->v79_processoforopartilhacusta == ""?@$GLOBALS["HTTP_POST_VARS"]["v79_processoforopartilhacusta"]:$this->v79_processoforopartilhacusta);
     }else{
       $this->v79_sequencial = ($this->v79_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v79_sequencial"]:$this->v79_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v79_sequencial){ 
      $this->atualizacampos();
     if($this->v79_partilhaarquivo == null ){ 
       $this->erro_sql = " Campo Arquivo de Remessa nao Informado.";
       $this->erro_campo = "v79_partilhaarquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v79_processoforopartilhacusta == null ){ 
       $this->erro_sql = " Campo Custa da partilha do processo nao Informado.";
       $this->erro_campo = "v79_processoforopartilhacusta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v79_sequencial == "" || $v79_sequencial == null ){
       $result = db_query("select nextval('partilhaarquivoreg_v79_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: partilhaarquivoreg_v79_sequencial_seq do campo: v79_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v79_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from partilhaarquivoreg_v79_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v79_sequencial)){
         $this->erro_sql = " Campo v79_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v79_sequencial = $v79_sequencial; 
       }
     }
     if(($this->v79_sequencial == null) || ($this->v79_sequencial == "") ){ 
       $this->erro_sql = " Campo v79_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into partilhaarquivoreg(
                                       v79_sequencial 
                                      ,v79_partilhaarquivo 
                                      ,v79_processoforopartilhacusta 
                       )
                values (
                                $this->v79_sequencial 
                               ,$this->v79_partilhaarquivo 
                               ,$this->v79_processoforopartilhacusta 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Custas enviadas no arquivo da partilha ($this->v79_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Custas enviadas no arquivo da partilha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Custas enviadas no arquivo da partilha ($this->v79_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v79_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v79_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18258,'$this->v79_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3232,18258,'','".AddSlashes(pg_result($resaco,0,'v79_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3232,18270,'','".AddSlashes(pg_result($resaco,0,'v79_partilhaarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3232,18271,'','".AddSlashes(pg_result($resaco,0,'v79_processoforopartilhacusta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v79_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update partilhaarquivoreg set ";
     $virgula = "";
     if(trim($this->v79_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v79_sequencial"])){ 
       $sql  .= $virgula." v79_sequencial = $this->v79_sequencial ";
       $virgula = ",";
       if(trim($this->v79_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v79_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v79_partilhaarquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v79_partilhaarquivo"])){ 
       $sql  .= $virgula." v79_partilhaarquivo = $this->v79_partilhaarquivo ";
       $virgula = ",";
       if(trim($this->v79_partilhaarquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo de Remessa nao Informado.";
         $this->erro_campo = "v79_partilhaarquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v79_processoforopartilhacusta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v79_processoforopartilhacusta"])){ 
       $sql  .= $virgula." v79_processoforopartilhacusta = $this->v79_processoforopartilhacusta ";
       $virgula = ",";
       if(trim($this->v79_processoforopartilhacusta) == null ){ 
         $this->erro_sql = " Campo Custa da partilha do processo nao Informado.";
         $this->erro_campo = "v79_processoforopartilhacusta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v79_sequencial!=null){
       $sql .= " v79_sequencial = $this->v79_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v79_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18258,'$this->v79_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v79_sequencial"]) || $this->v79_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3232,18258,'".AddSlashes(pg_result($resaco,$conresaco,'v79_sequencial'))."','$this->v79_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v79_partilhaarquivo"]) || $this->v79_partilhaarquivo != "")
           $resac = db_query("insert into db_acount values($acount,3232,18270,'".AddSlashes(pg_result($resaco,$conresaco,'v79_partilhaarquivo'))."','$this->v79_partilhaarquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v79_processoforopartilhacusta"]) || $this->v79_processoforopartilhacusta != "")
           $resac = db_query("insert into db_acount values($acount,3232,18271,'".AddSlashes(pg_result($resaco,$conresaco,'v79_processoforopartilhacusta'))."','$this->v79_processoforopartilhacusta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custas enviadas no arquivo da partilha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v79_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custas enviadas no arquivo da partilha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v79_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v79_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18258,'$v79_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3232,18258,'','".AddSlashes(pg_result($resaco,$iresaco,'v79_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3232,18270,'','".AddSlashes(pg_result($resaco,$iresaco,'v79_partilhaarquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3232,18271,'','".AddSlashes(pg_result($resaco,$iresaco,'v79_processoforopartilhacusta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from partilhaarquivoreg
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v79_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v79_sequencial = $v79_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Custas enviadas no arquivo da partilha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v79_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Custas enviadas no arquivo da partilha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v79_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v79_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:partilhaarquivoreg";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v79_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from partilhaarquivoreg ";
     $sql .= "      inner join processoforopartilhacusta  on  processoforopartilhacusta.v77_sequencial = partilhaarquivoreg.v79_processoforopartilhacusta";
     $sql .= "      inner join partilhaarquivo  on  partilhaarquivo.v78_sequencial = partilhaarquivoreg.v79_partilhaarquivo";
     $sql .= "      inner join taxa  on  taxa.ar36_sequencial = processoforopartilhacusta.v77_taxa";
     $sql .= "      inner join processoforopartilha  on  processoforopartilha.v76_sequencial = processoforopartilhacusta.v77_processoforopartilha";
     $sql2 = "";
     if($dbwhere==""){
       if($v79_sequencial!=null ){
         $sql2 .= " where partilhaarquivoreg.v79_sequencial = $v79_sequencial "; 
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
   // funcao do sql 
   function sql_query_file ( $v79_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from partilhaarquivoreg ";
     $sql2 = "";
     if($dbwhere==""){
       if($v79_sequencial!=null ){
         $sql2 .= " where partilhaarquivoreg.v79_sequencial = $v79_sequencial "; 
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