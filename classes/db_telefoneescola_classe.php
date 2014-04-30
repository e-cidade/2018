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

//MODULO: educação
//CLASSE DA ENTIDADE telefoneescola
class cl_telefoneescola {
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
   var $ed26_i_codigo = 0;
   var $ed26_i_escola = 0;
   var $ed26_i_tipotelefone = 0;
   var $ed26_i_numero = 0;
   var $ed26_i_ramal = 0;
   var $ed26_t_obs = null;
   var $ed26_i_ddd = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed26_i_codigo = int8 = Código
                 ed26_i_escola = int8 = Escola
                 ed26_i_tipotelefone = int8 = Tipo Telefone
                 ed26_i_numero = int4 = Número
                 ed26_i_ramal = int4 = Ramal
                 ed26_t_obs = text = Observações
                 ed26_i_ddd = int4 = DDD
                 ";
   //funcao construtor da classe
   function cl_telefoneescola() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("telefoneescola");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed26_i_escola=".@$GLOBALS["HTTP_POST_VARS"]["ed26_i_escola"]."&descrdepto=".@$GLOBALS["HTTP_POST_VARS"]["descrdepto"]);
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
       $this->ed26_i_codigo = ($this->ed26_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed26_i_codigo"]:$this->ed26_i_codigo);
       $this->ed26_i_escola = ($this->ed26_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed26_i_escola"]:$this->ed26_i_escola);
       $this->ed26_i_tipotelefone = ($this->ed26_i_tipotelefone == ""?@$GLOBALS["HTTP_POST_VARS"]["ed26_i_tipotelefone"]:$this->ed26_i_tipotelefone);
       $this->ed26_i_numero = ($this->ed26_i_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["ed26_i_numero"]:$this->ed26_i_numero);
       $this->ed26_i_ramal = ($this->ed26_i_ramal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed26_i_ramal"]:$this->ed26_i_ramal);
       $this->ed26_t_obs = ($this->ed26_t_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ed26_t_obs"]:$this->ed26_t_obs);
       $this->ed26_i_ddd = ($this->ed26_i_ddd == ""?@$GLOBALS["HTTP_POST_VARS"]["ed26_i_ddd"]:$this->ed26_i_ddd);
     }else{
       $this->ed26_i_codigo = ($this->ed26_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed26_i_codigo"]:$this->ed26_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed26_i_codigo){
      $this->atualizacampos();
     if($this->ed26_i_escola == null ){
       $this->erro_sql = " Campo Escola não Informado.";
       $this->erro_campo = "ed26_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed26_i_tipotelefone == null ){
       $this->erro_sql = " Campo Tipo Telefone não Informado.";
       $this->erro_campo = "ed26_i_tipotelefone";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed26_i_numero == null ){
       $this->erro_sql = " Campo Número não Informado.";
       $this->erro_campo = "ed26_i_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed26_i_ramal == null ){
       $this->ed26_i_ramal = "null";
     }
     if($this->ed26_i_ddd == null ){
       $this->erro_sql = " Campo DDD não Informado.";
       $this->erro_campo = "ed26_i_ddd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed26_i_codigo == "" || $ed26_i_codigo == null ){
       $result = db_query("select nextval('telefoneescola_ed26_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: telefoneescola_ed26_i_codigo_seq do campo: ed26_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed26_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from telefoneescola_ed26_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed26_i_codigo)){
         $this->erro_sql = " Campo ed26_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed26_i_codigo = $ed26_i_codigo;
       }
     }
     if(($this->ed26_i_codigo == null) || ($this->ed26_i_codigo == "") ){
       $this->erro_sql = " Campo ed26_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into telefoneescola(
                                       ed26_i_codigo
                                      ,ed26_i_escola
                                      ,ed26_i_tipotelefone
                                      ,ed26_i_numero
                                      ,ed26_i_ramal
                                      ,ed26_t_obs
                                      ,ed26_i_ddd
                       )
                values (
                                $this->ed26_i_codigo
                               ,$this->ed26_i_escola
                               ,$this->ed26_i_tipotelefone
                               ,$this->ed26_i_numero
                               ,$this->ed26_i_ramal
                               ,'$this->ed26_t_obs'
                               ,$this->ed26_i_ddd
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Telefones da Escola ($this->ed26_i_codigo) nao Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Telefones da Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Telefones da Escola ($this->ed26_i_codigo) nao Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed26_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed26_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008251,'$this->ed26_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010042,1008251,'','".AddSlashes(pg_result($resaco,0,'ed26_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010042,1008252,'','".AddSlashes(pg_result($resaco,0,'ed26_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010042,1008253,'','".AddSlashes(pg_result($resaco,0,'ed26_i_tipotelefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010042,1008254,'','".AddSlashes(pg_result($resaco,0,'ed26_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010042,1008255,'','".AddSlashes(pg_result($resaco,0,'ed26_i_ramal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010042,1008256,'','".AddSlashes(pg_result($resaco,0,'ed26_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010042,12626,'','".AddSlashes(pg_result($resaco,0,'ed26_i_ddd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed26_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update telefoneescola set ";
     $virgula = "";
     if(trim($this->ed26_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_codigo"])){
       $sql  .= $virgula." ed26_i_codigo = $this->ed26_i_codigo ";
       $virgula = ",";
       if(trim($this->ed26_i_codigo) == null ){
         $this->erro_sql = " Campo Código não Informado.";
         $this->erro_campo = "ed26_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed26_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_escola"])){
       $sql  .= $virgula." ed26_i_escola = $this->ed26_i_escola ";
       $virgula = ",";
       if(trim($this->ed26_i_escola) == null ){
         $this->erro_sql = " Campo Escola não Informado.";
         $this->erro_campo = "ed26_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed26_i_tipotelefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_tipotelefone"])){
       $sql  .= $virgula." ed26_i_tipotelefone = $this->ed26_i_tipotelefone ";
       $virgula = ",";
       if(trim($this->ed26_i_tipotelefone) == null ){
         $this->erro_sql = " Campo Tipo Telefone não Informado.";
         $this->erro_campo = "ed26_i_tipotelefone";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed26_i_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_numero"])){
       $sql  .= $virgula." ed26_i_numero = $this->ed26_i_numero ";
       $virgula = ",";
       if(trim($this->ed26_i_numero) == null ){
         $this->erro_sql = " Campo Número não Informado.";
         $this->erro_campo = "ed26_i_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed26_i_ramal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_ramal"])){
        if(trim($this->ed26_i_ramal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_ramal"])){
           $this->ed26_i_ramal = "null" ;
        }
       $sql  .= $virgula." ed26_i_ramal = $this->ed26_i_ramal ";
       $virgula = ",";
     }
     if(trim($this->ed26_t_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed26_t_obs"])){
       $sql  .= $virgula." ed26_t_obs = '$this->ed26_t_obs' ";
       $virgula = ",";
     }
     if(trim($this->ed26_i_ddd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_ddd"])){
       $sql  .= $virgula." ed26_i_ddd = $this->ed26_i_ddd ";
       $virgula = ",";
       if(trim($this->ed26_i_ddd) == null ){
         $this->erro_sql = " Campo DDD não Informado.";
         $this->erro_campo = "ed26_i_ddd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed26_i_codigo!=null){
       $sql .= " ed26_i_codigo = $this->ed26_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed26_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008251,'$this->ed26_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010042,1008251,'".AddSlashes(pg_result($resaco,$conresaco,'ed26_i_codigo'))."','$this->ed26_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010042,1008252,'".AddSlashes(pg_result($resaco,$conresaco,'ed26_i_escola'))."','$this->ed26_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_tipotelefone"]))
           $resac = db_query("insert into db_acount values($acount,1010042,1008253,'".AddSlashes(pg_result($resaco,$conresaco,'ed26_i_tipotelefone'))."','$this->ed26_i_tipotelefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_numero"]))
           $resac = db_query("insert into db_acount values($acount,1010042,1008254,'".AddSlashes(pg_result($resaco,$conresaco,'ed26_i_numero'))."','$this->ed26_i_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_ramal"]))
           $resac = db_query("insert into db_acount values($acount,1010042,1008255,'".AddSlashes(pg_result($resaco,$conresaco,'ed26_i_ramal'))."','$this->ed26_i_ramal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed26_t_obs"]))
           $resac = db_query("insert into db_acount values($acount,1010042,1008256,'".AddSlashes(pg_result($resaco,$conresaco,'ed26_t_obs'))."','$this->ed26_t_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed26_i_ddd"]))
           $resac = db_query("insert into db_acount values($acount,1010042,12626,'".AddSlashes(pg_result($resaco,$conresaco,'ed26_i_ddd'))."','$this->ed26_i_ddd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Telefones da Escola nao Alterado. Alteracão Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed26_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Telefones da Escola nao foi Alterado. Alteracão Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed26_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed26_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008251,'$ed26_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010042,1008251,'','".AddSlashes(pg_result($resaco,$iresaco,'ed26_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010042,1008252,'','".AddSlashes(pg_result($resaco,$iresaco,'ed26_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010042,1008253,'','".AddSlashes(pg_result($resaco,$iresaco,'ed26_i_tipotelefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010042,1008254,'','".AddSlashes(pg_result($resaco,$iresaco,'ed26_i_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010042,1008255,'','".AddSlashes(pg_result($resaco,$iresaco,'ed26_i_ramal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010042,1008256,'','".AddSlashes(pg_result($resaco,$iresaco,'ed26_t_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010042,12626,'','".AddSlashes(pg_result($resaco,$iresaco,'ed26_i_ddd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from telefoneescola
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed26_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed26_i_codigo = $ed26_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Telefones da Escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed26_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Telefones da Escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed26_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed26_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:telefoneescola";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed26_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from telefoneescola ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = telefoneescola.ed26_i_escola";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      inner join tipotelefone  on  tipotelefone.ed13_i_codigo = telefoneescola.ed26_i_tipotelefone";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql2 = "";
     if($dbwhere==""){
       if($ed26_i_codigo!=null ){
         $sql2 .= " where telefoneescola.ed26_i_codigo = $ed26_i_codigo ";
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
   function sql_query_file ( $ed26_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from telefoneescola ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed26_i_codigo!=null ){
         $sql2 .= " where telefoneescola.ed26_i_codigo = $ed26_i_codigo ";
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