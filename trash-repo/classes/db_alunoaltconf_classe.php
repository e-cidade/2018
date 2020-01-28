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

//MODULO: escola
//CLASSE DA ENTIDADE alunoaltconf
class cl_alunoaltconf {
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
   var $ed277_i_codigo = 0;
   var $ed277_i_alunoalt = 0;
   var $ed277_i_usuario = 0;
   var $ed277_i_data = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed277_i_codigo = int8 = Código
                 ed277_i_alunoalt = int8 = Aluno
                 ed277_i_usuario = int8 = Usuário
                 ed277_i_data = int8 = Data/Hora
                 ";
   //funcao construtor da classe
   function cl_alunoaltconf() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunoaltconf");
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
       $this->ed277_i_codigo = ($this->ed277_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed277_i_codigo"]:$this->ed277_i_codigo);
       $this->ed277_i_alunoalt = ($this->ed277_i_alunoalt == ""?@$GLOBALS["HTTP_POST_VARS"]["ed277_i_alunoalt"]:$this->ed277_i_alunoalt);
       $this->ed277_i_usuario = ($this->ed277_i_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed277_i_usuario"]:$this->ed277_i_usuario);
       $this->ed277_i_data = ($this->ed277_i_data == ""?@$GLOBALS["HTTP_POST_VARS"]["ed277_i_data"]:$this->ed277_i_data);
     }else{
       $this->ed277_i_codigo = ($this->ed277_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed277_i_codigo"]:$this->ed277_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed277_i_codigo){
      $this->atualizacampos();
     if($this->ed277_i_alunoalt == null ){
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed277_i_alunoalt";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed277_i_usuario == null ){
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ed277_i_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed277_i_data == null ){
       $this->erro_sql = " Campo Data/Hora nao Informado.";
       $this->erro_campo = "ed277_i_data";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed277_i_codigo == "" || $ed277_i_codigo == null ){
       $result = db_query("select nextval('alunoaltconf_ed277_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunoaltconf_ed277_i_codigo_seq do campo: ed277_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed277_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from alunoaltconf_ed277_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed277_i_codigo)){
         $this->erro_sql = " Campo ed277_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed277_i_codigo = $ed277_i_codigo;
       }
     }
     if(($this->ed277_i_codigo == null) || ($this->ed277_i_codigo == "") ){
       $this->erro_sql = " Campo ed277_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunoaltconf(
                                       ed277_i_codigo
                                      ,ed277_i_alunoalt
                                      ,ed277_i_usuario
                                      ,ed277_i_data
                       )
                values (
                                $this->ed277_i_codigo
                               ,$this->ed277_i_alunoalt
                               ,$this->ed277_i_usuario
                               ,$this->ed277_i_data
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Confirmação de leitura do Log de Alunos ($this->ed277_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Confirmação de leitura do Log de Alunos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Confirmação de leitura do Log de Alunos ($this->ed277_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed277_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed277_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14189,'$this->ed277_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2495,14189,'','".AddSlashes(pg_result($resaco,0,'ed277_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2495,14190,'','".AddSlashes(pg_result($resaco,0,'ed277_i_alunoalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2495,14191,'','".AddSlashes(pg_result($resaco,0,'ed277_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2495,14192,'','".AddSlashes(pg_result($resaco,0,'ed277_i_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed277_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update alunoaltconf set ";
     $virgula = "";
     if(trim($this->ed277_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed277_i_codigo"])){
       $sql  .= $virgula." ed277_i_codigo = $this->ed277_i_codigo ";
       $virgula = ",";
       if(trim($this->ed277_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed277_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed277_i_alunoalt)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed277_i_alunoalt"])){
       $sql  .= $virgula." ed277_i_alunoalt = $this->ed277_i_alunoalt ";
       $virgula = ",";
       if(trim($this->ed277_i_alunoalt) == null ){
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed277_i_alunoalt";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed277_i_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed277_i_usuario"])){
       $sql  .= $virgula." ed277_i_usuario = $this->ed277_i_usuario ";
       $virgula = ",";
       if(trim($this->ed277_i_usuario) == null ){
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ed277_i_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed277_i_data)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed277_i_data"])){
       $sql  .= $virgula." ed277_i_data = $this->ed277_i_data ";
       $virgula = ",";
       if(trim($this->ed277_i_data) == null ){
         $this->erro_sql = " Campo Data/Hora nao Informado.";
         $this->erro_campo = "ed277_i_data";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed277_i_codigo!=null){
       $sql .= " ed277_i_codigo = $this->ed277_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed277_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14189,'$this->ed277_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed277_i_codigo"]) || $this->ed277_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2495,14189,'".AddSlashes(pg_result($resaco,$conresaco,'ed277_i_codigo'))."','$this->ed277_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed277_i_alunoalt"]) || $this->ed277_i_alunoalt != "")
           $resac = db_query("insert into db_acount values($acount,2495,14190,'".AddSlashes(pg_result($resaco,$conresaco,'ed277_i_alunoalt'))."','$this->ed277_i_alunoalt',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed277_i_usuario"]) || $this->ed277_i_usuario != "")
           $resac = db_query("insert into db_acount values($acount,2495,14191,'".AddSlashes(pg_result($resaco,$conresaco,'ed277_i_usuario'))."','$this->ed277_i_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed277_i_data"]) || $this->ed277_i_data != "")
           $resac = db_query("insert into db_acount values($acount,2495,14192,'".AddSlashes(pg_result($resaco,$conresaco,'ed277_i_data'))."','$this->ed277_i_data',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Confirmação de leitura do Log de Alunos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed277_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Confirmação de leitura do Log de Alunos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed277_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed277_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed277_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed277_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14189,'$ed277_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2495,14189,'','".AddSlashes(pg_result($resaco,$iresaco,'ed277_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2495,14190,'','".AddSlashes(pg_result($resaco,$iresaco,'ed277_i_alunoalt'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2495,14191,'','".AddSlashes(pg_result($resaco,$iresaco,'ed277_i_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2495,14192,'','".AddSlashes(pg_result($resaco,$iresaco,'ed277_i_data'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunoaltconf
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed277_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed277_i_codigo = $ed277_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Confirmação de leitura do Log de Alunos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed277_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Confirmação de leitura do Log de Alunos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed277_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed277_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunoaltconf";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed277_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from alunoaltconf ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = alunoaltconf.ed277_i_usuario";
     $sql .= "      inner join alunoalt  on  alunoalt.ed275_i_codigo = alunoaltconf.ed277_i_alunoalt";
     $sql2 = "";
     if($dbwhere==""){
       if($ed277_i_codigo!=null ){
         $sql2 .= " where alunoaltconf.ed277_i_codigo = $ed277_i_codigo ";
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
   function sql_query_file ( $ed277_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from alunoaltconf ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed277_i_codigo!=null ){
         $sql2 .= " where alunoaltconf.ed277_i_codigo = $ed277_i_codigo ";
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