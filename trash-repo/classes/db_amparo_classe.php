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

//MODULO: educação
//CLASSE DA ENTIDADE amparo
class cl_amparo {
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
   var $ed81_i_codigo = 0;
   var $ed81_i_diario = 0;
   var $ed81_i_justificativa = 0;
   var $ed81_i_convencaoamp = 0;
   var $ed81_c_todoperiodo = null;
   var $ed81_c_aprovch = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed81_i_codigo = int8 = Código
                 ed81_i_diario = int8 = Diário de Classe
                 ed81_i_justificativa = int8 = Justificativa Legal
                 ed81_i_convencaoamp = int8 = Convenção
                 ed81_c_todoperiodo = char(1) = Todos os Períodos
                 ed81_c_aprovch = char(1) = Aproveita Carga Horária
                 ";
   //funcao construtor da classe
   function cl_amparo() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("amparo");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?regencia=".@$GLOBALS["HTTP_POST_VARS"]["regencia"];
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
       $this->ed81_i_codigo = ($this->ed81_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed81_i_codigo"]:$this->ed81_i_codigo);
       $this->ed81_i_diario = ($this->ed81_i_diario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed81_i_diario"]:$this->ed81_i_diario);
       $this->ed81_i_justificativa = ($this->ed81_i_justificativa == ""?@$GLOBALS["HTTP_POST_VARS"]["ed81_i_justificativa"]:$this->ed81_i_justificativa);
       $this->ed81_i_convencaoamp = ($this->ed81_i_convencaoamp == ""?@$GLOBALS["HTTP_POST_VARS"]["ed81_i_convencaoamp"]:$this->ed81_i_convencaoamp);
       $this->ed81_c_todoperiodo = ($this->ed81_c_todoperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed81_c_todoperiodo"]:$this->ed81_c_todoperiodo);
       $this->ed81_c_aprovch = ($this->ed81_c_aprovch == ""?@$GLOBALS["HTTP_POST_VARS"]["ed81_c_aprovch"]:$this->ed81_c_aprovch);
     }else{
       $this->ed81_i_codigo = ($this->ed81_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed81_i_codigo"]:$this->ed81_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed81_i_codigo){
      $this->atualizacampos();
     if($this->ed81_i_diario == null ){
       $this->erro_sql = " Campo Diário de Classe nao Informado.";
       $this->erro_campo = "ed81_i_diario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed81_i_justificativa == null ){
       $this->ed81_i_justificativa = "null";
     }
     if($this->ed81_i_convencaoamp == null ){
       $this->ed81_i_convencaoamp = "null";
     }
     if($this->ed81_c_todoperiodo == null ){
       $this->erro_sql = " Campo Todos os Períodos nao Informado.";
       $this->erro_campo = "ed81_c_todoperiodo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed81_c_aprovch == null ){
       $this->erro_sql = " Campo Aproveita Carga Horária nao Informado.";
       $this->erro_campo = "ed81_c_aprovch";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed81_i_codigo == "" || $ed81_i_codigo == null ){
       $result = db_query("select nextval('amparo_ed81_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: amparo_ed81_i_codigo_seq do campo: ed81_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed81_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from amparo_ed81_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed81_i_codigo)){
         $this->erro_sql = " Campo ed81_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed81_i_codigo = $ed81_i_codigo;
       }
     }
     if(($this->ed81_i_codigo == null) || ($this->ed81_i_codigo == "") ){
       $this->erro_sql = " Campo ed81_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into amparo(
                                       ed81_i_codigo
                                      ,ed81_i_diario
                                      ,ed81_i_justificativa
                                      ,ed81_i_convencaoamp
                                      ,ed81_c_todoperiodo
                                      ,ed81_c_aprovch
                       )
                values (
                                $this->ed81_i_codigo
                               ,$this->ed81_i_diario
                               ,$this->ed81_i_justificativa
                               ,$this->ed81_i_convencaoamp
                               ,'$this->ed81_c_todoperiodo'
                               ,'$this->ed81_c_aprovch'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Amparo para disciplinas ($this->ed81_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Amparo para disciplinas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Amparo para disciplinas ($this->ed81_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed81_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed81_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008687,'$this->ed81_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010122,1008687,'','".AddSlashes(pg_result($resaco,0,'ed81_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010122,1008688,'','".AddSlashes(pg_result($resaco,0,'ed81_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010122,1008689,'','".AddSlashes(pg_result($resaco,0,'ed81_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010122,12110,'','".AddSlashes(pg_result($resaco,0,'ed81_i_convencaoamp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010122,1008690,'','".AddSlashes(pg_result($resaco,0,'ed81_c_todoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010122,1008691,'','".AddSlashes(pg_result($resaco,0,'ed81_c_aprovch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed81_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update amparo set ";
     $virgula = "";
     if(trim($this->ed81_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_codigo"])){
       $sql  .= $virgula." ed81_i_codigo = $this->ed81_i_codigo ";
       $virgula = ",";
       if(trim($this->ed81_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed81_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed81_i_diario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_diario"])){
       $sql  .= $virgula." ed81_i_diario = $this->ed81_i_diario ";
       $virgula = ",";
       if(trim($this->ed81_i_diario) == null ){
         $this->erro_sql = " Campo Diário de Classe nao Informado.";
         $this->erro_campo = "ed81_i_diario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed81_i_justificativa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_justificativa"])){
        if(trim($this->ed81_i_justificativa)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_justificativa"])){
           $this->ed81_i_justificativa = "null" ;
        }
       $sql  .= $virgula." ed81_i_justificativa = $this->ed81_i_justificativa ";
       $virgula = ",";
     }
     if(trim($this->ed81_i_convencaoamp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_convencaoamp"])){
        if(trim($this->ed81_i_convencaoamp)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_convencaoamp"])){
           $this->ed81_i_convencaoamp = "null" ;
        }
       $sql  .= $virgula." ed81_i_convencaoamp = $this->ed81_i_convencaoamp ";
       $virgula = ",";
     }
     if(trim($this->ed81_c_todoperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed81_c_todoperiodo"])){
       $sql  .= $virgula." ed81_c_todoperiodo = '$this->ed81_c_todoperiodo' ";
       $virgula = ",";
       if(trim($this->ed81_c_todoperiodo) == null ){
         $this->erro_sql = " Campo Todos os Períodos nao Informado.";
         $this->erro_campo = "ed81_c_todoperiodo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed81_c_aprovch)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed81_c_aprovch"])){
       $sql  .= $virgula." ed81_c_aprovch = '$this->ed81_c_aprovch' ";
       $virgula = ",";
       if(trim($this->ed81_c_aprovch) == null ){
         $this->erro_sql = " Campo Aproveita Carga Horária nao Informado.";
         $this->erro_campo = "ed81_c_aprovch";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed81_i_codigo!=null){
       $sql .= " ed81_i_codigo = $this->ed81_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed81_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008687,'$this->ed81_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010122,1008687,'".AddSlashes(pg_result($resaco,$conresaco,'ed81_i_codigo'))."','$this->ed81_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_diario"]))
           $resac = db_query("insert into db_acount values($acount,1010122,1008688,'".AddSlashes(pg_result($resaco,$conresaco,'ed81_i_diario'))."','$this->ed81_i_diario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_justificativa"]))
           $resac = db_query("insert into db_acount values($acount,1010122,1008689,'".AddSlashes(pg_result($resaco,$conresaco,'ed81_i_justificativa'))."','$this->ed81_i_justificativa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed81_i_convencaoamp"]))
           $resac = db_query("insert into db_acount values($acount,1010122,12110,'".AddSlashes(pg_result($resaco,$conresaco,'ed81_i_convencaoamp'))."','$this->ed81_i_convencaoamp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed81_c_todoperiodo"]))
           $resac = db_query("insert into db_acount values($acount,1010122,1008690,'".AddSlashes(pg_result($resaco,$conresaco,'ed81_c_todoperiodo'))."','$this->ed81_c_todoperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed81_c_aprovch"]))
           $resac = db_query("insert into db_acount values($acount,1010122,1008691,'".AddSlashes(pg_result($resaco,$conresaco,'ed81_c_aprovch'))."','$this->ed81_c_aprovch',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Amparo para disciplinas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed81_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Amparo para disciplinas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed81_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed81_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed81_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed81_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008687,'$ed81_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010122,1008687,'','".AddSlashes(pg_result($resaco,$iresaco,'ed81_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010122,1008688,'','".AddSlashes(pg_result($resaco,$iresaco,'ed81_i_diario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010122,1008689,'','".AddSlashes(pg_result($resaco,$iresaco,'ed81_i_justificativa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010122,12110,'','".AddSlashes(pg_result($resaco,$iresaco,'ed81_i_convencaoamp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010122,1008690,'','".AddSlashes(pg_result($resaco,$iresaco,'ed81_c_todoperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010122,1008691,'','".AddSlashes(pg_result($resaco,$iresaco,'ed81_c_aprovch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from amparo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed81_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed81_i_codigo = $ed81_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Amparo para disciplinas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed81_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Amparo para disciplinas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed81_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed81_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:amparo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed81_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from amparo ";
     $sql .= "      left join convencaoamp  on  convencaoamp.ed250_i_codigo = amparo.ed81_i_convencaoamp";
     $sql .= "      left join justificativa  on  justificativa.ed06_i_codigo = amparo.ed81_i_justificativa";
     $sql .= "      inner join diario  on  diario.ed95_i_codigo = amparo.ed81_i_diario";
     $sql .= "      inner join escola  on   escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina on caddisciplina.ed232_i_codigo = disciplina.ed12_i_caddisciplina";
     $sql2 = "";
     if($dbwhere==""){
       if($ed81_i_codigo!=null ){
         $sql2 .= " where amparo.ed81_i_codigo = $ed81_i_codigo ";
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
   function sql_query_file ( $ed81_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from amparo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed81_i_codigo!=null ){
         $sql2 .= " where amparo.ed81_i_codigo = $ed81_i_codigo ";
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