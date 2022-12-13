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

//MODULO: cadastro
//CLASSE DA ENTIDADE certidaoexistenciaprotprocesso
class cl_certidaoexistenciaprotprocesso { 
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
   var $j134_sequencial = 0; 
   var $j134_certidaoexistencia = 0; 
   var $j134_protprocesso = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j134_sequencial = int4 = Sequencial 
                 j134_certidaoexistencia = int4 = Certidão de existência 
                 j134_protprocesso = int4 = Processo certidão existência 
                 ";
   //funcao construtor da classe 
   function cl_certidaoexistenciaprotprocesso() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("certidaoexistenciaprotprocesso"); 
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
       $this->j134_sequencial = ($this->j134_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j134_sequencial"]:$this->j134_sequencial);
       $this->j134_certidaoexistencia = ($this->j134_certidaoexistencia == ""?@$GLOBALS["HTTP_POST_VARS"]["j134_certidaoexistencia"]:$this->j134_certidaoexistencia);
       $this->j134_protprocesso = ($this->j134_protprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["j134_protprocesso"]:$this->j134_protprocesso);
     }else{
       $this->j134_sequencial = ($this->j134_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["j134_sequencial"]:$this->j134_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($j134_sequencial){ 
      $this->atualizacampos();
     if($this->j134_certidaoexistencia == null ){ 
       $this->erro_sql = " Campo Certidão de existência nao Informado.";
       $this->erro_campo = "j134_certidaoexistencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j134_protprocesso == null ){ 
       $this->erro_sql = " Campo Processo certidão existência nao Informado.";
       $this->erro_campo = "j134_protprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($j134_sequencial == "" || $j134_sequencial == null ){
       $result = db_query("select nextval('certidaoexistenciaprotprocesso_j134_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: certidaoexistenciaprotprocesso_j134_sequencial_seq do campo: j134_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->j134_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from certidaoexistenciaprotprocesso_j134_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $j134_sequencial)){
         $this->erro_sql = " Campo j134_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->j134_sequencial = $j134_sequencial; 
       }
     }
     if(($this->j134_sequencial == null) || ($this->j134_sequencial == "") ){ 
       $this->erro_sql = " Campo j134_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into certidaoexistenciaprotprocesso(
                                       j134_sequencial 
                                      ,j134_certidaoexistencia 
                                      ,j134_protprocesso 
                       )
                values (
                                $this->j134_sequencial 
                               ,$this->j134_certidaoexistencia 
                               ,$this->j134_protprocesso 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Processo certidão existência ($this->j134_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Processo certidão existência já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Processo certidão existência ($this->j134_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j134_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->j134_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18856,'$this->j134_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3343,18856,'','".AddSlashes(pg_result($resaco,0,'j134_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3343,18857,'','".AddSlashes(pg_result($resaco,0,'j134_certidaoexistencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3343,18858,'','".AddSlashes(pg_result($resaco,0,'j134_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($j134_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update certidaoexistenciaprotprocesso set ";
     $virgula = "";
     if(trim($this->j134_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j134_sequencial"])){ 
       $sql  .= $virgula." j134_sequencial = $this->j134_sequencial ";
       $virgula = ",";
       if(trim($this->j134_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "j134_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j134_certidaoexistencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j134_certidaoexistencia"])){ 
       $sql  .= $virgula." j134_certidaoexistencia = $this->j134_certidaoexistencia ";
       $virgula = ",";
       if(trim($this->j134_certidaoexistencia) == null ){ 
         $this->erro_sql = " Campo Certidão de existência nao Informado.";
         $this->erro_campo = "j134_certidaoexistencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j134_protprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j134_protprocesso"])){ 
       $sql  .= $virgula." j134_protprocesso = $this->j134_protprocesso ";
       $virgula = ",";
       if(trim($this->j134_protprocesso) == null ){ 
         $this->erro_sql = " Campo Processo certidão existência nao Informado.";
         $this->erro_campo = "j134_protprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j134_sequencial!=null){
       $sql .= " j134_sequencial = $this->j134_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->j134_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18856,'$this->j134_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j134_sequencial"]) || $this->j134_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3343,18856,'".AddSlashes(pg_result($resaco,$conresaco,'j134_sequencial'))."','$this->j134_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j134_certidaoexistencia"]) || $this->j134_certidaoexistencia != "")
           $resac = db_query("insert into db_acount values($acount,3343,18857,'".AddSlashes(pg_result($resaco,$conresaco,'j134_certidaoexistencia'))."','$this->j134_certidaoexistencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["j134_protprocesso"]) || $this->j134_protprocesso != "")
           $resac = db_query("insert into db_acount values($acount,3343,18858,'".AddSlashes(pg_result($resaco,$conresaco,'j134_protprocesso'))."','$this->j134_protprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo certidão existência nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j134_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Processo certidão existência nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->j134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($j134_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($j134_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18856,'$j134_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3343,18856,'','".AddSlashes(pg_result($resaco,$iresaco,'j134_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3343,18857,'','".AddSlashes(pg_result($resaco,$iresaco,'j134_certidaoexistencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3343,18858,'','".AddSlashes(pg_result($resaco,$iresaco,'j134_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from certidaoexistenciaprotprocesso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($j134_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " j134_sequencial = $j134_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Processo certidão existência nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j134_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Processo certidão existência nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j134_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$j134_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:certidaoexistenciaprotprocesso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $j134_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidaoexistenciaprotprocesso ";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = certidaoexistenciaprotprocesso.j134_protprocesso";
     $sql .= "      inner join certidaoexistencia  on  certidaoexistencia.j133_sequencial = certidaoexistenciaprotprocesso.j134_certidaoexistencia";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql .= "      inner join iptubase  on  iptubase.j01_matric = certidaoexistencia.j133_matric";
     $sql .= "      inner join iptuconstr  on  iptuconstr.j39_matric = certidaoexistencia.j133_iptuconstr";
     $sql .= "      inner join db_usuarios  as a on   a.id_usuario = certidaoexistencia.j133_db_usuarios";
     $sql2 = "";
     if($dbwhere==""){
       if($j134_sequencial!=null ){
         $sql2 .= " where certidaoexistenciaprotprocesso.j134_sequencial = $j134_sequencial "; 
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
   function sql_query_file ( $j134_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from certidaoexistenciaprotprocesso ";
     $sql2 = "";
     if($dbwhere==""){
       if($j134_sequencial!=null ){
         $sql2 .= " where certidaoexistenciaprotprocesso.j134_sequencial = $j134_sequencial "; 
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