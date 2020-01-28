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
//CLASSE DA ENTIDADE partilharemessawebservice
class cl_partilharemessawebservice { 
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
   var $v89_sequencial = 0; 
   var $v89_numnov = 0; 
   var $v89_db_remessawebservice = 0; 
   var $v89_resposta = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v89_sequencial = int4 = Sequencial 
                 v89_numnov = int4 = Numpre do Recibo 
                 v89_db_remessawebservice = int4 = Remessa Web Service 
                 v89_resposta = text = Resposta 
                 ";
   //funcao construtor da classe 
   function cl_partilharemessawebservice() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("partilharemessawebservice"); 
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
       $this->v89_sequencial = ($this->v89_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v89_sequencial"]:$this->v89_sequencial);
       $this->v89_numnov = ($this->v89_numnov == ""?@$GLOBALS["HTTP_POST_VARS"]["v89_numnov"]:$this->v89_numnov);
       $this->v89_db_remessawebservice = ($this->v89_db_remessawebservice == ""?@$GLOBALS["HTTP_POST_VARS"]["v89_db_remessawebservice"]:$this->v89_db_remessawebservice);
       $this->v89_resposta = ($this->v89_resposta == ""?@$GLOBALS["HTTP_POST_VARS"]["v89_resposta"]:$this->v89_resposta);
     }else{
       $this->v89_sequencial = ($this->v89_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v89_sequencial"]:$this->v89_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v89_sequencial){ 
      $this->atualizacampos();
     if($this->v89_numnov == null ){ 
       $this->erro_sql = " Campo Numpre do Recibo nao Informado.";
       $this->erro_campo = "v89_numnov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v89_db_remessawebservice == null ){ 
       $this->erro_sql = " Campo Remessa Web Service nao Informado.";
       $this->erro_campo = "v89_db_remessawebservice";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v89_sequencial == "" || $v89_sequencial == null ){
       $result = db_query("select nextval('partilharemessawebservice_v89_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: partilharemessawebservice_v89_sequencial_seq do campo: v89_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v89_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from partilharemessawebservice_v89_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v89_sequencial)){
         $this->erro_sql = " Campo v89_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v89_sequencial = $v89_sequencial; 
       }
     }
     if(($this->v89_sequencial == null) || ($this->v89_sequencial == "") ){ 
       $this->erro_sql = " Campo v89_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into partilharemessawebservice(
                                       v89_sequencial 
                                      ,v89_numnov 
                                      ,v89_db_remessawebservice 
                                      ,v89_resposta 
                       )
                values (
                                $this->v89_sequencial 
                               ,$this->v89_numnov 
                               ,$this->v89_db_remessawebservice 
                               ,'$this->v89_resposta' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Remessa de Partilhas ($this->v89_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Remessa de Partilhas j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Remessa de Partilhas ($this->v89_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v89_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v89_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19058,'$this->v89_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3390,19058,'','".AddSlashes(pg_result($resaco,0,'v89_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3390,19059,'','".AddSlashes(pg_result($resaco,0,'v89_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3390,19060,'','".AddSlashes(pg_result($resaco,0,'v89_db_remessawebservice'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3390,19061,'','".AddSlashes(pg_result($resaco,0,'v89_resposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v89_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update partilharemessawebservice set ";
     $virgula = "";
     if(trim($this->v89_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v89_sequencial"])){ 
       $sql  .= $virgula." v89_sequencial = $this->v89_sequencial ";
       $virgula = ",";
       if(trim($this->v89_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v89_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v89_numnov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v89_numnov"])){ 
       $sql  .= $virgula." v89_numnov = $this->v89_numnov ";
       $virgula = ",";
       if(trim($this->v89_numnov) == null ){ 
         $this->erro_sql = " Campo Numpre do Recibo nao Informado.";
         $this->erro_campo = "v89_numnov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v89_db_remessawebservice)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v89_db_remessawebservice"])){ 
       $sql  .= $virgula." v89_db_remessawebservice = $this->v89_db_remessawebservice ";
       $virgula = ",";
       if(trim($this->v89_db_remessawebservice) == null ){ 
         $this->erro_sql = " Campo Remessa Web Service nao Informado.";
         $this->erro_campo = "v89_db_remessawebservice";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v89_resposta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v89_resposta"])){ 
       $sql  .= $virgula." v89_resposta = '$this->v89_resposta' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($v89_sequencial!=null){
       $sql .= " v89_sequencial = $this->v89_sequencial";
     }
    
     $resaco = $this->sql_record($this->sql_query_file($this->v89_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19058,'$this->v89_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v89_sequencial"]) || $this->v89_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3390,19058,'".AddSlashes(pg_result($resaco,$conresaco,'v89_sequencial'))."','$this->v89_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v89_numnov"]) || $this->v89_numnov != "")
           $resac = db_query("insert into db_acount values($acount,3390,19059,'".AddSlashes(pg_result($resaco,$conresaco,'v89_numnov'))."','$this->v89_numnov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v89_db_remessawebservice"]) || $this->v89_db_remessawebservice != "")
           $resac = db_query("insert into db_acount values($acount,3390,19060,'".AddSlashes(pg_result($resaco,$conresaco,'v89_db_remessawebservice'))."','$this->v89_db_remessawebservice',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v89_resposta"]) || $this->v89_resposta != "")
           $resac = db_query("insert into db_acount values($acount,3390,19061,'".AddSlashes(pg_result($resaco,$conresaco,'v89_resposta'))."' ,'$this->v89_resposta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remessa de Partilhas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v89_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Remessa de Partilhas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v89_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v89_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v89_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v89_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19058,'$v89_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3390,19058,'','".AddSlashes(pg_result($resaco,$iresaco,'v89_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3390,19059,'','".AddSlashes(pg_result($resaco,$iresaco,'v89_numnov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3390,19060,'','".AddSlashes(pg_result($resaco,$iresaco,'v89_db_remessawebservice'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3390,19061,'','".AddSlashes(pg_result($resaco,$iresaco,'v89_resposta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from partilharemessawebservice
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v89_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v89_sequencial = $v89_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Remessa de Partilhas nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v89_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Remessa de Partilhas nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v89_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v89_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:partilharemessawebservice";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from partilharemessawebservice ";
     $sql .= "      inner join db_remessawebservice  on  db_remessawebservice.db127_sequencial = partilharemessawebservice.v89_db_remessawebservice";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_remessawebservice.db127_usuario";
     $sql .= "      inner join db_sistemaexterno  on  db_sistemaexterno.db124_sequencial = db_remessawebservice.db127_sistemaexterno";
     $sql2 = "";
     if($dbwhere==""){
       if($v89_sequencial!=null ){
         $sql2 .= " where partilharemessawebservice.v89_sequencial = $v89_sequencial "; 
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
   function sql_query_file ( $v89_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from partilharemessawebservice ";
     $sql2 = "";
     if($dbwhere==""){
       if($v89_sequencial!=null ){
         $sql2 .= " where partilharemessawebservice.v89_sequencial = $v89_sequencial "; 
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