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

//MODULO: material
//CLASSE DA ENTIDADE matmater
class cl_matmater {
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
   var $m60_codmater = 0;
   var $m60_descr = null;
   var $m60_codmatunid = 0;
   var $m60_quantent = 0;
   var $m60_codant = null;
   var $m60_ativo = 'f';
   var $m60_controlavalidade = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 m60_codmater = int8 = Código do material
                 m60_descr = varchar(80) = Descricao do material
                 m60_codmatunid = int8 = Unidade de entrada
                 m60_quantent = float4 = Quantidade de entrada
                 m60_codant = varchar(20) = Código anterior do item
                 m60_ativo = bool = Ativo
                 m60_controlavalidade = int4 = Controlar validade
                 ";
   //funcao construtor da classe
   function cl_matmater() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matmater");
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
       $this->m60_codmater = ($this->m60_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m60_codmater"]:$this->m60_codmater);
       $this->m60_descr = ($this->m60_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["m60_descr"]:$this->m60_descr);
       $this->m60_codmatunid = ($this->m60_codmatunid == ""?@$GLOBALS["HTTP_POST_VARS"]["m60_codmatunid"]:$this->m60_codmatunid);
       $this->m60_quantent = ($this->m60_quantent == ""?@$GLOBALS["HTTP_POST_VARS"]["m60_quantent"]:$this->m60_quantent);
       $this->m60_codant = ($this->m60_codant == ""?@$GLOBALS["HTTP_POST_VARS"]["m60_codant"]:$this->m60_codant);
       $this->m60_ativo = ($this->m60_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["m60_ativo"]:$this->m60_ativo);
       $this->m60_controlavalidade = ($this->m60_controlavalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["m60_controlavalidade"]:$this->m60_controlavalidade);
     }else{
       $this->m60_codmater = ($this->m60_codmater == ""?@$GLOBALS["HTTP_POST_VARS"]["m60_codmater"]:$this->m60_codmater);
     }
   }
   // funcao para inclusao
   function incluir ($m60_codmater){
      $this->atualizacampos();
     if($this->m60_descr == null ){
       $this->erro_sql = " Campo Descricao do material nao Informado.";
       $this->erro_campo = "m60_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m60_codmatunid == null ){
       $this->erro_sql = " Campo Unidade de entrada nao Informado.";
       $this->erro_campo = "m60_codmatunid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m60_quantent == null ){
       $this->erro_sql = " Campo Quantidade de entrada nao Informado.";
       $this->erro_campo = "m60_quantent";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m60_ativo == null ){
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "m60_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->m60_controlavalidade == null ){
       $this->erro_sql = " Campo Controlar validade nao Informado.";
       $this->erro_campo = "m60_controlavalidade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($m60_codmater == "" || $m60_codmater == null ){
       $result = db_query("select nextval('matmater_m60_codmater_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matmater_m60_codmater_seq do campo: m60_codmater";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->m60_codmater = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from matmater_m60_codmater_seq");
       if(($result != false) && (pg_result($result,0,0) < $m60_codmater)){
         $this->erro_sql = " Campo m60_codmater maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->m60_codmater = $m60_codmater;
       }
     }
     if(($this->m60_codmater == null) || ($this->m60_codmater == "") ){
       $this->erro_sql = " Campo m60_codmater nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matmater(
                                       m60_codmater
                                      ,m60_descr
                                      ,m60_codmatunid
                                      ,m60_quantent
                                      ,m60_codant
                                      ,m60_ativo
                                      ,m60_controlavalidade
                       )
                values (
                                $this->m60_codmater
                               ,'$this->m60_descr'
                               ,$this->m60_codmatunid
                               ,$this->m60_quantent
                               ,'$this->m60_codant'
                               ,'$this->m60_ativo'
                               ,$this->m60_controlavalidade
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Materiais ($this->m60_codmater) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Materiais já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Materiais ($this->m60_codmater) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m60_codmater;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->m60_codmater));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,6260,'$this->m60_codmater','I')");
       $resac = db_query("insert into db_acount values($acount,1016,6260,'','".AddSlashes(pg_result($resaco,0,'m60_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1016,6261,'','".AddSlashes(pg_result($resaco,0,'m60_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1016,6264,'','".AddSlashes(pg_result($resaco,0,'m60_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1016,6265,'','".AddSlashes(pg_result($resaco,0,'m60_quantent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1016,6284,'','".AddSlashes(pg_result($resaco,0,'m60_codant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1016,8008,'','".AddSlashes(pg_result($resaco,0,'m60_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1016,11974,'','".AddSlashes(pg_result($resaco,0,'m60_controlavalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($m60_codmater=null) {
      $this->atualizacampos();
     $sql = " update matmater set ";
     $virgula = "";
     if(trim($this->m60_codmater)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m60_codmater"])){
       $sql  .= $virgula." m60_codmater = $this->m60_codmater ";
       $virgula = ",";
       if(trim($this->m60_codmater) == null ){
         $this->erro_sql = " Campo Código do material nao Informado.";
         $this->erro_campo = "m60_codmater";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m60_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m60_descr"])){
       $sql  .= $virgula." m60_descr = '$this->m60_descr' ";
       $virgula = ",";
       if(trim($this->m60_descr) == null ){
         $this->erro_sql = " Campo Descricao do material nao Informado.";
         $this->erro_campo = "m60_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m60_codmatunid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m60_codmatunid"])){
       $sql  .= $virgula." m60_codmatunid = $this->m60_codmatunid ";
       $virgula = ",";
       if(trim($this->m60_codmatunid) == null ){
         $this->erro_sql = " Campo Unidade de entrada nao Informado.";
         $this->erro_campo = "m60_codmatunid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m60_quantent)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m60_quantent"])){
       $sql  .= $virgula." m60_quantent = $this->m60_quantent ";
       $virgula = ",";
       if(trim($this->m60_quantent) == null ){
         $this->erro_sql = " Campo Quantidade de entrada nao Informado.";
         $this->erro_campo = "m60_quantent";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m60_codant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m60_codant"])){
       $sql  .= $virgula." m60_codant = '$this->m60_codant' ";
       $virgula = ",";
     }
     if(trim($this->m60_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m60_ativo"])){
       $sql  .= $virgula." m60_ativo = '$this->m60_ativo' ";
       $virgula = ",";
       if(trim($this->m60_ativo) == null ){
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "m60_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->m60_controlavalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["m60_controlavalidade"])){
       $sql  .= $virgula." m60_controlavalidade = $this->m60_controlavalidade ";
       $virgula = ",";
       if(trim($this->m60_controlavalidade) == null ){
         $this->erro_sql = " Campo Controlar validade nao Informado.";
         $this->erro_campo = "m60_controlavalidade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($m60_codmater!=null){
       $sql .= " m60_codmater = $this->m60_codmater";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->m60_codmater));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6260,'$this->m60_codmater','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m60_codmater"]))
           $resac = db_query("insert into db_acount values($acount,1016,6260,'".AddSlashes(pg_result($resaco,$conresaco,'m60_codmater'))."','$this->m60_codmater',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m60_descr"]))
           $resac = db_query("insert into db_acount values($acount,1016,6261,'".AddSlashes(pg_result($resaco,$conresaco,'m60_descr'))."','$this->m60_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m60_codmatunid"]))
           $resac = db_query("insert into db_acount values($acount,1016,6264,'".AddSlashes(pg_result($resaco,$conresaco,'m60_codmatunid'))."','$this->m60_codmatunid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m60_quantent"]))
           $resac = db_query("insert into db_acount values($acount,1016,6265,'".AddSlashes(pg_result($resaco,$conresaco,'m60_quantent'))."','$this->m60_quantent',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m60_codant"]))
           $resac = db_query("insert into db_acount values($acount,1016,6284,'".AddSlashes(pg_result($resaco,$conresaco,'m60_codant'))."','$this->m60_codant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m60_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1016,8008,'".AddSlashes(pg_result($resaco,$conresaco,'m60_ativo'))."','$this->m60_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["m60_controlavalidade"]))
           $resac = db_query("insert into db_acount values($acount,1016,11974,'".AddSlashes(pg_result($resaco,$conresaco,'m60_controlavalidade'))."','$this->m60_controlavalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Materiais nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->m60_codmater;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Materiais nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->m60_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->m60_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($m60_codmater=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($m60_codmater));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,6260,'$m60_codmater','E')");
         $resac = db_query("insert into db_acount values($acount,1016,6260,'','".AddSlashes(pg_result($resaco,$iresaco,'m60_codmater'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1016,6261,'','".AddSlashes(pg_result($resaco,$iresaco,'m60_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1016,6264,'','".AddSlashes(pg_result($resaco,$iresaco,'m60_codmatunid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1016,6265,'','".AddSlashes(pg_result($resaco,$iresaco,'m60_quantent'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1016,6284,'','".AddSlashes(pg_result($resaco,$iresaco,'m60_codant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1016,8008,'','".AddSlashes(pg_result($resaco,$iresaco,'m60_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1016,11974,'','".AddSlashes(pg_result($resaco,$iresaco,'m60_controlavalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matmater
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($m60_codmater != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " m60_codmater = $m60_codmater ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Materiais nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$m60_codmater;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Materiais nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$m60_codmater;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$m60_codmater;
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
        $this->erro_sql   = "Record Vazio na Tabela:matmater";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $m60_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matmater ";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
     $sql2 = "";
     if($dbwhere==""){
       if($m60_codmater!=null ){
         $sql2 .= " where matmater.m60_codmater = $m60_codmater ";
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
   function sql_query_com($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {
		$sql = "select ";
		if ($campos != "*") {
			$campos_sql = split("#", $campos);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		} else {
			$sql .= $campos;
		}
		$sql .= " from matmater ";
		$sql .= "      inner join transmater  on transmater.m63_codmatmater = matmater.m60_codmater";
		$sql .= "      inner join pcmater  on pcmater.pc01_codmater = transmater.m63_codpcmater";
		$sql2 = "";
		if ($dbwhere == "") {
			if ($m60_codmater != null) {
				$sql2 .= " where matmater.m60_codmater = $m60_codmater ";
			}
		} else
			if ($dbwhere != "") {
				$sql2 = " where $dbwhere";
			}
		$sql .= $sql2;
		if ($ordem != null) {
			$sql .= " order by ";
			$campos_sql = split("#", $ordem);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}

  function sql_query_com_pcmater($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {
		$sql = "select ";
		if ($campos != "*") {
			$campos_sql = split("#", $campos);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		} else {
			$sql .= $campos;
		}
		$sql .= " from matmater ";
    $sql .= "      left join transmater                   on transmater.m63_codmatmater = matmater.m60_codmater";
    $sql .= "      left join pcmater                      on pcmater.pc01_codmater      = transmater.m63_codpcmater";
    $sql .= "      left join matmatermaterialestoquegrupo on matmater.m60_codmater      = m68_matmater";
    $sql2 = "";
		if ($dbwhere == "") {
			if ($m60_codmater != null) {
				$sql2 .= " where matmater.m60_codmater = $m60_codmater ";
			}
		} else
			if ($dbwhere != "") {
				$sql2 = " where $dbwhere";
			}
		$sql .= $sql2;
		if ($ordem != null) {
			$sql .= " order by ";
			$campos_sql = split("#", $ordem);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}


   function sql_query_deptoestoque($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {
		$sql = "select ";
		if ($campos != "*") {
			$campos_sql = split("#", $campos);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		} else {
			$sql .= $campos;
		}
		$sql .= " from matmater ";
		$sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
		$sql .= "      inner join matestoque  on  matestoque.m70_codmatmater = matmater.m60_codmater";
		$sql .= "      inner join matestoqueitem  on  matestoqueitem.m71_codmatestoque = matestoque.m70_codigo";
		$sql2 = "";
		if ($dbwhere == "") {
			if ($m60_codmater != null) {
				$sql2 .= " where matmater.m60_codmater = $m60_codmater ";
			}
		} else
			if ($dbwhere != "") {
				$sql2 = " where $dbwhere";
			}
		if ($dbwhere == "") {
			if ($m60_codmater != null) {
				$sql2 .= " where matmater.m60_codmater = $m60_codmater ";
			}
		} else
			if ($dbwhere != "") {
				$sql2 = " where $dbwhere";
			}
		$sql .= $sql2;
		if ($ordem != null) {
			$sql .= " order by ";
			$campos_sql = split("#", $ordem);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}
   function sql_query_file ( $m60_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matmater ";
     $sql2 = "";
     if($dbwhere==""){
       if($m60_codmater!=null ){
         $sql2 .= " where matmater.m60_codmater = $m60_codmater ";
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

   function sql_query_file_pcmater ( $m60_codmater=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from matmater ";
     $sql .= "      inner join transmater  on transmater.m63_codmatmater  = matmater.m60_codmater";
     $sql .= "      inner join pcmater     on pcmater.pc01_codmater       = transmater.m63_codpcmater";
     $sql2 = "";
     if($dbwhere==""){
       if($m60_codmater!=null ){
         $sql2 .= " where matmater.m60_codmater = $m60_codmater ";
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

   function sqlQuerySaldo( $m60_codmater=null,$campos="*",$ordem=null,$dbwhere="") {

    $clmatparam = db_utils::getDao("matparam");
    $rsParam = $clmatparam->sql_record($clmatparam->sql_query_file());
    $oParam = db_utils::fieldsMemory($rsParam,0);

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matmater ";
    $sql .= "      inner join matestoque  on  matmater.m60_codmater = matestoque.m70_codmatmater";
    $sql .= "      inner join matestoqueitem on m71_codmatestoque = m70_codigo";
    $sql .= "      left  join matestoqueitemlote on m77_matestoqueitem = m71_codlanc";
    $sql .= "      inner join db_depart  on  db_depart.coddepto = matestoque.m70_coddepto";
    $sql .= "      inner join matunid  on  matunid.m61_codmatunid = matmater.m60_codmatunid";
    if ($oParam->m90_tipocontrol != "S") {
      $sql .= "      inner join db_almox on m91_depto = db_depart.coddepto ";
      $sql .= "      inner join db_almoxdepto on m91_codigo = m92_codalmox";
    }
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m70_codigo != null) {
        $sql2 .= " and matestoque.m70_codigo = $m70_codigo ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 .= " and $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
   function sql_query_unisai($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {
		$sql = "select ";
		if ($campos != "*") {
			$campos_sql = split("#", $campos);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		} else {
			$sql .= $campos;
		}
		$sql .= " from matmater ";
		$sql .= " inner join matmaterunisai on matmaterunisai.m62_codmater= matmater.m60_codmater";
		$sql .= " inner join matunid on matunid.m61_codmatunid = matmaterunisai.m62_codmatunid";
		$sql2 = "";
		if ($dbwhere == "") {
			if ($m60_codmater != null) {
				$sql2 .= " where matmater.m60_codmater = $m60_codmater ";
			}
		} else
			if ($dbwhere != "") {
				$sql2 = " where $dbwhere";
			}
		$sql .= $sql2;
		if ($ordem != null) {
			$sql .= " order by ";
			$campos_sql = split("#", $ordem);
			$virgula = "";
		for ($i = 0; $i < sizeof($campos_sql); $i ++) {
			$sql .= $virgula.$campos_sql[$i];
			$virgula = ",";
		}
	  }
	return $sql;
  }

	  function sql_query_config($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {
		$sql = "select distinct ";
		if ($campos != "*") {
			$campos_sql = split("#", $campos);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		} else {
			$sql .= $campos;
		}

		$sql .= " from matmater 																															    ";
		$sql .= "	  inner join matunid    on matunid.m61_codmatunid     = matmater.m60_codmatunid ";
   	$sql .= "   left  join matestoque on matestoque.m70_codmatmater = matmater.m60_codmater   ";
    $sql .= "   left  join db_depart  on db_depart.coddepto         = matestoque.m70_coddepto ";

		$sql2 = "";
		if ($dbwhere == "") {
			if ($m60_codmater != null) {
				$sql2 .= " where matmater.m60_codmater = $m60_codmater ";
			}
		} else
			if ($dbwhere != "") {
				$sql2 = " where $dbwhere";
			}
		$sql .= $sql2;
		if ($ordem != null) {
			$sql .= " order by ";
			$campos_sql = split("#", $ordem);
			$virgula = "";
			for ($i = 0; $i < sizeof($campos_sql); $i ++) {
				$sql .= $virgula.$campos_sql[$i];
				$virgula = ",";
			}
		}
		return $sql;
	}

  function sql_query_com_subgrupo($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matmater ";
    $sql .= "      inner join transmater  on transmater.m63_codmatmater = matmater.m60_codmater";
    $sql .= "      inner join pcmater     on pcmater.pc01_codmater = transmater.m63_codpcmater";
    $sql .= "      inner join pcsubgrupo  on pcmater.pc01_codsubgrupo = pc04_codsubgrupo";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m60_codmater != null) {
        $sql2 .= " where matmater.m60_codmater = $m60_codmater ";
      }
    } else
      if ($dbwhere != "") {
        $sql2 = " where $dbwhere";
      }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }


  function sql_query_material_nota($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {
    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matmater ";
    $sql .= "      inner join matestoque                     on matestoque.m70_codmatmater                        = matmater.m60_codmater";
    $sql .= "      inner join matestoqueitem                 on matestoqueitem.m71_codmatestoque                  = matestoque.m70_codigo";
    $sql .= "      inner join matestoqueinimei               on matestoqueinimei.m82_matestoqueitem               = matestoqueitem.m71_codlanc";
    $sql .= "      inner join matestoqueini                  on matestoqueini.m80_codigo                          = matestoqueinimei.m82_matestoqueini";
    $sql .= "      left  join matestoqueitemnota             on matestoqueitemnota.m74_codmatestoqueitem          = matestoqueitem.m71_codlanc";
    $sql .= "      left  join empnota                        on empnota.e69_codnota                               = matestoqueitemnota.m74_codempnota";
    $sql .= "      left  join matestoqueitemnotafiscalmanual on matestoqueitemnotafiscalmanual.m79_matestoqueitem = matestoqueitem.m71_codlanc";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m60_codmater != null) {
        $sql2 .= " where matmater.m60_codmater = $m60_codmater ";
      }
    } else
    if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }

  public function sql_query_material_desdobramento($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matmater ";
    $sql .= "      inner join transmater    on transmater.m63_codmatmater = matmater.m60_codmater";
    $sql .= "      inner join pcmater       on pcmater.pc01_codmater      = transmater.m63_codpcmater";
    $sql .= "      inner join pcmaterele    on pcmater.pc01_codmater      = pcmaterele.pc07_codmater";
    $sql .= "      inner join conplanoorcamentoanalitica on pcmaterele.pc07_codele     = c61_codcon";
    $sql .= "                                           and c61_anousu             = ".db_getsession("DB_anousu");
    if ($dbwhere == "") {
      if ($m60_codmater != null) {
        $sql2 .= " where matmater.m60_codmater = $m60_codmater ";
      }
    } else
      if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return analiseQueryPlanoOrcamento($sql);
  }

  function sql_query_grupo($m60_codmater = null, $campos = "*", $ordem = null, $dbwhere = "") {

    $sql = "select ";
    if ($campos != "*") {
      $campos_sql = split("#", $campos);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    } else {
      $sql .= $campos;
    }
    $sql .= " from matmater ";
    $sql .= "      inner join transmater                   on transmater.m63_codmatmater = matmater.m60_codmater";
    $sql .= "      inner join pcmater                      on pcmater.pc01_codmater      = transmater.m63_codpcmater";
    $sql .= "      inner join matmatermaterialestoquegrupo  on matmater.m60_codmater      = m68_matmater";
    $sql .= "      inner join materialestoquegrupo          on m68_materialestoquegrupo   = m65_sequencial";
    $sql .= "      inner join materialestoquegrupoconta     on m66_materialestoquegrupo   = m65_sequencial";
    $sql .= "      inner join conplanoreduz                 on m66_codcon                 = c61_codcon";
    $sql .= "                                              and m66_anousu                 = c61_anousu";
    $sql2 = "";
    if ($dbwhere == "") {
      if ($m60_codmater != null) {
        $sql2 .= " where matmater.m60_codmater = $m60_codmater ";
      }
    } else
      if ($dbwhere != "") {
      $sql2 = " where $dbwhere";
    }
    $sql .= $sql2;
    if ($ordem != null) {
      $sql .= " order by ";
      $campos_sql = split("#", $ordem);
      $virgula = "";
      for ($i = 0; $i < sizeof($campos_sql); $i ++) {
        $sql .= $virgula.$campos_sql[$i];
        $virgula = ",";
      }
    }
    return $sql;
  }
}
?>