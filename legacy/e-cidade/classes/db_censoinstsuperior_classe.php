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

//MODULO: Escola
//CLASSE DA ENTIDADE censoinstsuperior
class cl_censoinstsuperior {
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
   var $ed257_i_codigo = 0;
   var $ed257_c_nome = null;
   var $ed257_i_dependencia = 0;
   var $ed257_i_tipo = 0;
   var $ed257_i_censomunic = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed257_i_codigo = int4 = Código
                 ed257_c_nome = char(150) = Nome
                 ed257_i_dependencia = int4 = Dependência Administrativa
                 ed257_i_tipo = int4 = Tipo de instituição
                 ed257_i_censomunic = int4 = Cidade
                 ";
   //funcao construtor da classe
   function cl_censoinstsuperior() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("censoinstsuperior");
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
       $this->ed257_i_codigo = ($this->ed257_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed257_i_codigo"]:$this->ed257_i_codigo);
       $this->ed257_c_nome = ($this->ed257_c_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["ed257_c_nome"]:$this->ed257_c_nome);
       $this->ed257_i_dependencia = ($this->ed257_i_dependencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed257_i_dependencia"]:$this->ed257_i_dependencia);
       $this->ed257_i_tipo = ($this->ed257_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed257_i_tipo"]:$this->ed257_i_tipo);
       $this->ed257_i_censomunic = ($this->ed257_i_censomunic == ""?@$GLOBALS["HTTP_POST_VARS"]["ed257_i_censomunic"]:$this->ed257_i_censomunic);
     }else{
       $this->ed257_i_codigo = ($this->ed257_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed257_i_codigo"]:$this->ed257_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed257_i_codigo){
      $this->atualizacampos();
     if($this->ed257_c_nome == null ){
       $this->erro_sql = " Campo Nome nao Informado.";
       $this->erro_campo = "ed257_c_nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed257_i_dependencia == null ){
       $this->erro_sql = " Campo Dependência Administrativa nao Informado.";
       $this->erro_campo = "ed257_i_dependencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed257_i_tipo == null ){
       $this->erro_sql = " Campo Tipo de instituição nao Informado.";
       $this->erro_campo = "ed257_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed257_i_censomunic == null ){
       $this->erro_sql = " Campo Cidade nao Informado.";
       $this->erro_campo = "ed257_i_censomunic";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->ed257_i_codigo = $ed257_i_codigo;
     if(($this->ed257_i_codigo == null) || ($this->ed257_i_codigo == "") ){
       $this->erro_sql = " Campo ed257_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into censoinstsuperior(
                                       ed257_i_codigo
                                      ,ed257_c_nome
                                      ,ed257_i_dependencia
                                      ,ed257_i_tipo
                                      ,ed257_i_censomunic
                       )
                values (
                                $this->ed257_i_codigo
                               ,'$this->ed257_c_nome'
                               ,$this->ed257_i_dependencia
                               ,$this->ed257_i_tipo
                               ,$this->ed257_i_censomunic
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela Nacional-Instituição Ensino Superior - CE ($this->ed257_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela Nacional-Instituição Ensino Superior - CE já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela Nacional-Instituição Ensino Superior - CE ($this->ed257_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed257_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed257_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13435,'$this->ed257_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2350,13435,'','".AddSlashes(pg_result($resaco,0,'ed257_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2350,13436,'','".AddSlashes(pg_result($resaco,0,'ed257_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2350,13437,'','".AddSlashes(pg_result($resaco,0,'ed257_i_dependencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2350,13438,'','".AddSlashes(pg_result($resaco,0,'ed257_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2350,13439,'','".AddSlashes(pg_result($resaco,0,'ed257_i_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed257_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update censoinstsuperior set ";
     $virgula = "";
     if(trim($this->ed257_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed257_i_codigo"])){
       $sql  .= $virgula." ed257_i_codigo = $this->ed257_i_codigo ";
       $virgula = ",";
       if(trim($this->ed257_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed257_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed257_c_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed257_c_nome"])){
       $sql  .= $virgula." ed257_c_nome = '$this->ed257_c_nome' ";
       $virgula = ",";
       if(trim($this->ed257_c_nome) == null ){
         $this->erro_sql = " Campo Nome nao Informado.";
         $this->erro_campo = "ed257_c_nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed257_i_dependencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed257_i_dependencia"])){
       $sql  .= $virgula." ed257_i_dependencia = $this->ed257_i_dependencia ";
       $virgula = ",";
       if(trim($this->ed257_i_dependencia) == null ){
         $this->erro_sql = " Campo Dependência Administrativa nao Informado.";
         $this->erro_campo = "ed257_i_dependencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed257_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed257_i_tipo"])){
       $sql  .= $virgula." ed257_i_tipo = $this->ed257_i_tipo ";
       $virgula = ",";
       if(trim($this->ed257_i_tipo) == null ){
         $this->erro_sql = " Campo Tipo de instituição nao Informado.";
         $this->erro_campo = "ed257_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed257_i_censomunic)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed257_i_censomunic"])){
       $sql  .= $virgula." ed257_i_censomunic = $this->ed257_i_censomunic ";
       $virgula = ",";
       if(trim($this->ed257_i_censomunic) == null ){
         $this->erro_sql = " Campo Cidade nao Informado.";
         $this->erro_campo = "ed257_i_censomunic";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed257_i_codigo!=null){
       $sql .= " ed257_i_codigo = $this->ed257_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed257_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13435,'$this->ed257_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed257_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2350,13435,'".AddSlashes(pg_result($resaco,$conresaco,'ed257_i_codigo'))."','$this->ed257_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed257_c_nome"]))
           $resac = db_query("insert into db_acount values($acount,2350,13436,'".AddSlashes(pg_result($resaco,$conresaco,'ed257_c_nome'))."','$this->ed257_c_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed257_i_dependencia"]))
           $resac = db_query("insert into db_acount values($acount,2350,13437,'".AddSlashes(pg_result($resaco,$conresaco,'ed257_i_dependencia'))."','$this->ed257_i_dependencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed257_i_tipo"]))
           $resac = db_query("insert into db_acount values($acount,2350,13438,'".AddSlashes(pg_result($resaco,$conresaco,'ed257_i_tipo'))."','$this->ed257_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed257_i_censomunic"]))
           $resac = db_query("insert into db_acount values($acount,2350,13439,'".AddSlashes(pg_result($resaco,$conresaco,'ed257_i_censomunic'))."','$this->ed257_i_censomunic',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela Nacional-Instituição Ensino Superior - CE nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed257_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela Nacional-Instituição Ensino Superior - CE nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed257_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed257_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed257_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed257_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13435,'$ed257_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2350,13435,'','".AddSlashes(pg_result($resaco,$iresaco,'ed257_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2350,13436,'','".AddSlashes(pg_result($resaco,$iresaco,'ed257_c_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2350,13437,'','".AddSlashes(pg_result($resaco,$iresaco,'ed257_i_dependencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2350,13438,'','".AddSlashes(pg_result($resaco,$iresaco,'ed257_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2350,13439,'','".AddSlashes(pg_result($resaco,$iresaco,'ed257_i_censomunic'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from censoinstsuperior
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed257_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed257_i_codigo = $ed257_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela Nacional-Instituição Ensino Superior - CE nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed257_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabela Nacional-Instituição Ensino Superior - CE nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed257_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed257_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:censoinstsuperior";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed257_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from censoinstsuperior ";
     $sql .= "      left join censomunic  on  censomunic.ed261_i_codigo = censoinstsuperior.ed257_i_censomunic";
     $sql .= "      left join censouf  on  censouf.ed260_i_codigo = censomunic.ed261_i_censouf";
     $sql2 = "";
     if($dbwhere==""){
       if($ed257_i_codigo!=null ){
         $sql2 .= " where censoinstsuperior.ed257_i_codigo = $ed257_i_codigo ";
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
   function sql_query_file ( $ed257_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from censoinstsuperior ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed257_i_codigo!=null ){
         $sql2 .= " where censoinstsuperior.ed257_i_codigo = $ed257_i_codigo ";
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