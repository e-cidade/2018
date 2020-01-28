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

//MODULO: caixa
//CLASSE DA ENTIDADE cancdebitosconcarpeculiar
class cl_cancdebitosconcarpeculiar { 
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
   var $k72_sequencial = 0; 
   var $k72_cancdebitos = 0; 
   var $k72_concarpeculiar = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k72_sequencial = int4 = Código 
                 k72_cancdebitos = int4 = Código cancdebitos 
                 k72_concarpeculiar = varchar(100) = Código carac. peculiar 
                 ";
   //funcao construtor da classe 
   function cl_cancdebitosconcarpeculiar() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cancdebitosconcarpeculiar"); 
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
       $this->k72_sequencial = ($this->k72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k72_sequencial"]:$this->k72_sequencial);
       $this->k72_cancdebitos = ($this->k72_cancdebitos == ""?@$GLOBALS["HTTP_POST_VARS"]["k72_cancdebitos"]:$this->k72_cancdebitos);
       $this->k72_concarpeculiar = ($this->k72_concarpeculiar == ""?@$GLOBALS["HTTP_POST_VARS"]["k72_concarpeculiar"]:$this->k72_concarpeculiar);
     }else{
       $this->k72_sequencial = ($this->k72_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k72_sequencial"]:$this->k72_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k72_sequencial){ 
      $this->atualizacampos();
     if($this->k72_cancdebitos == null ){ 
       $this->erro_sql = " Campo Código cancdebitos nao Informado.";
       $this->erro_campo = "k72_cancdebitos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k72_concarpeculiar == null ){ 
       $this->erro_sql = " Campo Código carac. peculiar nao Informado.";
       $this->erro_campo = "k72_concarpeculiar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k72_sequencial == "" || $k72_sequencial == null ){
       $result = db_query("select nextval('cancdebitosconcarpeculiar_k72_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cancdebitosconcarpeculiar_k72_sequencial_seq do campo: k72_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k72_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cancdebitosconcarpeculiar_k72_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k72_sequencial)){
         $this->erro_sql = " Campo k72_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k72_sequencial = $k72_sequencial; 
       }
     }
     if(($this->k72_sequencial == null) || ($this->k72_sequencial == "") ){ 
       $this->erro_sql = " Campo k72_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cancdebitosconcarpeculiar(
                                       k72_sequencial 
                                      ,k72_cancdebitos 
                                      ,k72_concarpeculiar 
                       )
                values (
                                $this->k72_sequencial 
                               ,$this->k72_cancdebitos 
                               ,'$this->k72_concarpeculiar' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "cancdebitosconcarpeculiar ($this->k72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "cancdebitosconcarpeculiar já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "cancdebitosconcarpeculiar ($this->k72_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k72_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k72_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11743,'$this->k72_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2024,11743,'','".AddSlashes(pg_result($resaco,0,'k72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2024,11744,'','".AddSlashes(pg_result($resaco,0,'k72_cancdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2024,11745,'','".AddSlashes(pg_result($resaco,0,'k72_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k72_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update cancdebitosconcarpeculiar set ";
     $virgula = "";
     if(trim($this->k72_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k72_sequencial"])){ 
       $sql  .= $virgula." k72_sequencial = $this->k72_sequencial ";
       $virgula = ",";
       if(trim($this->k72_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k72_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k72_cancdebitos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k72_cancdebitos"])){ 
       $sql  .= $virgula." k72_cancdebitos = $this->k72_cancdebitos ";
       $virgula = ",";
       if(trim($this->k72_cancdebitos) == null ){ 
         $this->erro_sql = " Campo Código cancdebitos nao Informado.";
         $this->erro_campo = "k72_cancdebitos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k72_concarpeculiar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k72_concarpeculiar"])){ 
       $sql  .= $virgula." k72_concarpeculiar = '$this->k72_concarpeculiar' ";
       $virgula = ",";
       if(trim($this->k72_concarpeculiar) == null ){ 
         $this->erro_sql = " Campo Código carac. peculiar nao Informado.";
         $this->erro_campo = "k72_concarpeculiar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k72_sequencial!=null){
       $sql .= " k72_sequencial = $this->k72_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k72_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11743,'$this->k72_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k72_sequencial"]) || $this->k72_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2024,11743,'".AddSlashes(pg_result($resaco,$conresaco,'k72_sequencial'))."','$this->k72_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k72_cancdebitos"]) || $this->k72_cancdebitos != "")
           $resac = db_query("insert into db_acount values($acount,2024,11744,'".AddSlashes(pg_result($resaco,$conresaco,'k72_cancdebitos'))."','$this->k72_cancdebitos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k72_concarpeculiar"]) || $this->k72_concarpeculiar != "")
           $resac = db_query("insert into db_acount values($acount,2024,11745,'".AddSlashes(pg_result($resaco,$conresaco,'k72_concarpeculiar'))."','$this->k72_concarpeculiar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cancdebitosconcarpeculiar nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cancdebitosconcarpeculiar nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k72_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k72_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11743,'$k72_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2024,11743,'','".AddSlashes(pg_result($resaco,$iresaco,'k72_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2024,11744,'','".AddSlashes(pg_result($resaco,$iresaco,'k72_cancdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2024,11745,'','".AddSlashes(pg_result($resaco,$iresaco,'k72_concarpeculiar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cancdebitosconcarpeculiar
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k72_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k72_sequencial = $k72_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "cancdebitosconcarpeculiar nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k72_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "cancdebitosconcarpeculiar nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k72_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k72_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:cancdebitosconcarpeculiar";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosconcarpeculiar ";
     $sql .= "      inner join cancdebitos  on  cancdebitos.k20_codigo = cancdebitosconcarpeculiar.k72_cancdebitos";
     $sql .= "      inner join concarpeculiar  on  concarpeculiar.c58_sequencial = cancdebitosconcarpeculiar.k72_concarpeculiar";
     $sql .= "      inner join db_config  on  db_config.codigo = cancdebitos.k20_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = cancdebitos.k20_usuario";
     $sql .= "      inner join cancdebitostipo  on  cancdebitostipo.k73_sequencial = cancdebitos.k20_cancdebitostipo";
     $sql2 = "";
     if($dbwhere==""){
       if($k72_sequencial!=null ){
         $sql2 .= " where cancdebitosconcarpeculiar.k72_sequencial = $k72_sequencial "; 
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
   function sql_query_file ( $k72_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cancdebitosconcarpeculiar ";
     $sql2 = "";
     if($dbwhere==""){
       if($k72_sequencial!=null ){
         $sql2 .= " where cancdebitosconcarpeculiar.k72_sequencial = $k72_sequencial "; 
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