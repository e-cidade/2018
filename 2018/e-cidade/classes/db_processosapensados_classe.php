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

//MODULO: protocolo
//CLASSE DA ENTIDADE processosapensados
class cl_processosapensados { 
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
   var $p30_sequencial = 0; 
   var $p30_procprincipal = 0; 
   var $p30_procapensado = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 p30_sequencial = int4 = Sequencial 
                 p30_procprincipal = int4 = Processo Principal 
                 p30_procapensado = int4 = Processo Apensado 
                 ";
   //funcao construtor da classe 
   function cl_processosapensados() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("processosapensados"); 
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
       $this->p30_sequencial = ($this->p30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p30_sequencial"]:$this->p30_sequencial);
       $this->p30_procprincipal = ($this->p30_procprincipal == ""?@$GLOBALS["HTTP_POST_VARS"]["p30_procprincipal"]:$this->p30_procprincipal);
       $this->p30_procapensado = ($this->p30_procapensado == ""?@$GLOBALS["HTTP_POST_VARS"]["p30_procapensado"]:$this->p30_procapensado);
     }else{
       $this->p30_sequencial = ($this->p30_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["p30_sequencial"]:$this->p30_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($p30_sequencial){ 
      $this->atualizacampos();
     if($this->p30_procprincipal == null ){ 
       $this->erro_sql = " Campo Processo Principal nao Informado.";
       $this->erro_campo = "p30_procprincipal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->p30_procapensado == null ){ 
       $this->erro_sql = " Campo Processo Apensado nao Informado.";
       $this->erro_campo = "p30_procapensado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($p30_sequencial == "" || $p30_sequencial == null ){
       $result = db_query("select nextval('processosapensados_p30_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: processosapensados_p30_sequencial_seq do campo: p30_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->p30_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from processosapensados_p30_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $p30_sequencial)){
         $this->erro_sql = " Campo p30_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->p30_sequencial = $p30_sequencial; 
       }
     }
     if(($this->p30_sequencial == null) || ($this->p30_sequencial == "") ){ 
       $this->erro_sql = " Campo p30_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into processosapensados(
                                       p30_sequencial 
                                      ,p30_procprincipal 
                                      ,p30_procapensado 
                       )
                values (
                                $this->p30_sequencial 
                               ,$this->p30_procprincipal 
                               ,$this->p30_procapensado 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Apensar Processos ($this->p30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Apensar Processos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Apensar Processos ($this->p30_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p30_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->p30_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15053,'$this->p30_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2647,15053,'','".AddSlashes(pg_result($resaco,0,'p30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2647,15055,'','".AddSlashes(pg_result($resaco,0,'p30_procprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2647,15054,'','".AddSlashes(pg_result($resaco,0,'p30_procapensado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($p30_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update processosapensados set ";
     $virgula = "";
     if(trim($this->p30_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p30_sequencial"])){ 
       $sql  .= $virgula." p30_sequencial = $this->p30_sequencial ";
       $virgula = ",";
       if(trim($this->p30_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "p30_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p30_procprincipal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p30_procprincipal"])){ 
       $sql  .= $virgula." p30_procprincipal = $this->p30_procprincipal ";
       $virgula = ",";
       if(trim($this->p30_procprincipal) == null ){ 
         $this->erro_sql = " Campo Processo Principal nao Informado.";
         $this->erro_campo = "p30_procprincipal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->p30_procapensado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["p30_procapensado"])){ 
       $sql  .= $virgula." p30_procapensado = $this->p30_procapensado ";
       $virgula = ",";
       if(trim($this->p30_procapensado) == null ){ 
         $this->erro_sql = " Campo Processo Apensado nao Informado.";
         $this->erro_campo = "p30_procapensado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($p30_sequencial!=null){
       $sql .= " p30_sequencial = $this->p30_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->p30_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15053,'$this->p30_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p30_sequencial"]) || $this->p30_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2647,15053,'".AddSlashes(pg_result($resaco,$conresaco,'p30_sequencial'))."','$this->p30_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p30_procprincipal"]) || $this->p30_procprincipal != "")
           $resac = db_query("insert into db_acount values($acount,2647,15055,'".AddSlashes(pg_result($resaco,$conresaco,'p30_procprincipal'))."','$this->p30_procprincipal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["p30_procapensado"]) || $this->p30_procapensado != "")
           $resac = db_query("insert into db_acount values($acount,2647,15054,'".AddSlashes(pg_result($resaco,$conresaco,'p30_procapensado'))."','$this->p30_procapensado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Apensar Processos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->p30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Apensar Processos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->p30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->p30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($p30_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($p30_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15053,'$p30_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2647,15053,'','".AddSlashes(pg_result($resaco,$iresaco,'p30_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2647,15055,'','".AddSlashes(pg_result($resaco,$iresaco,'p30_procprincipal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2647,15054,'','".AddSlashes(pg_result($resaco,$iresaco,'p30_procapensado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from processosapensados
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($p30_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " p30_sequencial = $p30_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Apensar Processos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$p30_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Apensar Processos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$p30_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$p30_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:processosapensados";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $p30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processosapensados ";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = processosapensados.p30_procapensado and  protprocesso.p58_codproc = processosapensados.p30_procprincipal";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($p30_sequencial!=null ){
         $sql2 .= " where processosapensados.p30_sequencial = $p30_sequencial "; 
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
   function sql_query_file ( $p30_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from processosapensados ";
     $sql2 = "";
     if($dbwhere==""){
       if($p30_sequencial!=null ){
         $sql2 .= " where processosapensados.p30_sequencial = $p30_sequencial "; 
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
  
  
  function sql_query_processo_principal ($p30_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {
    
    $sql = "select ";
    if ($campos != "*" ) {
       
      $campos_sql = split("#",$campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from processosapensados ";
    $sql .= "      inner join protprocesso  on protprocesso.p58_codproc = processosapensados.p30_procprincipal";
    $sql .= "      inner join cgm           on  cgm.z01_numcgm          = protprocesso.p58_numcgm";
    $sql .= "      inner join db_config     on  db_config.codigo        = protprocesso.p58_instit";
    $sql .= "      inner join db_usuarios   on  db_usuarios.id_usuario  = protprocesso.p58_id_usuario";
    $sql .= "      inner join db_depart     on  db_depart.coddepto      = protprocesso.p58_coddepto";
    $sql .= "      inner join tipoproc      on  tipoproc.p51_codigo     = protprocesso.p58_codigo";
    $sql2 = "";
    if ($dbwhere == "") {
       
      if ($p30_sequencial != null) {
        $sql2 .= " where processosapensados.p30_sequencial = $p30_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null ) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#",$ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql);$i++) {

        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  function sql_query_processo_apensado ($p30_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    
    if ($campos != "*" ) {
      
      $campos_sql = split("#", $campos);
      $virgula    = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from processosapensados ";
    $sql .= "      inner join protprocesso  on protprocesso.p58_codproc = processosapensados.p30_procapensado";
    $sql .= "      inner join cgm           on  cgm.z01_numcgm          = protprocesso.p58_numcgm";
    $sql .= "      inner join db_config     on  db_config.codigo        = protprocesso.p58_instit";
    $sql .= "      inner join db_usuarios   on  db_usuarios.id_usuario  = protprocesso.p58_id_usuario";
    $sql .= "      inner join db_depart     on  db_depart.coddepto      = protprocesso.p58_coddepto";
    $sql .= "      inner join tipoproc      on  tipoproc.p51_codigo     = protprocesso.p58_codigo";
    $sql2 = "";
    
    if ($dbwhere == "") {
      
      if ($p30_sequencial != null) {
        $sql2 .= " where processosapensados.p30_sequencial = $p30_sequencial ";
      }
    } else if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      
      $sql        .= " order by ";
      $campos_sql  = split("#", $ordem);
      $virgula     = "";
      for ($i = 0; $i < sizeof($campos_sql); $i++) {
        
        $sql     .= $virgula.$campos_sql[$i];
        $virgula  = ",";
      }
    }
    return $sql;
  }    
}
?>