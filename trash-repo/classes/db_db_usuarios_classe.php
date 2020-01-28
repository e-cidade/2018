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


//MODULO: configuracoes
//CLASSE DA ENTIDADE db_usuarios
class cl_db_usuarios {
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
   var $id_usuario = 0;
   var $nome = null;
   var $login = null;
   var $senha = null;
   var $usuarioativo = null;
   var $email = null;
   var $usuext = 0;
   var $administrador = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 id_usuario = int4 = Cod. Usu�rio
                 nome = varchar(40) = nome do usuario
                 login = varchar(20) = login do usuario
                 senha = varchar(20) = senha
                 usuarioativo = char(1) = se usuario ativo ou nao
                 email = varchar(50) = email
                 usuext = int4 = Utiliza DBPortal
                 administrador = int4 = Administrador
                 ";
   //funcao construtor da classe
   function cl_db_usuarios() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_usuarios");
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
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
       $this->nome = ($this->nome == ""?@$GLOBALS["HTTP_POST_VARS"]["nome"]:$this->nome);
       $this->login = ($this->login == ""?@$GLOBALS["HTTP_POST_VARS"]["login"]:$this->login);
       $this->senha = ($this->senha == ""?@$GLOBALS["HTTP_POST_VARS"]["senha"]:$this->senha);
       $this->usuarioativo = ($this->usuarioativo == ""?@$GLOBALS["HTTP_POST_VARS"]["usuarioativo"]:$this->usuarioativo);
       $this->email = ($this->email == ""?@$GLOBALS["HTTP_POST_VARS"]["email"]:$this->email);
       $this->usuext = ($this->usuext == ""?@$GLOBALS["HTTP_POST_VARS"]["usuext"]:$this->usuext);
       $this->administrador = ($this->administrador == ""?@$GLOBALS["HTTP_POST_VARS"]["administrador"]:$this->administrador);
     }else{
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
     }
   }
   // funcao para inclusao
   function incluir ($id_usuario){
      $this->atualizacampos();
     if($this->nome == null ){
       $this->erro_sql = " Campo nome do usuario nao Informado.";
       $this->erro_campo = "nome";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->login == null ){
       $this->erro_sql = " Campo login do usuario nao Informado.";
       $this->erro_campo = "login";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->senha == null ){
       $this->erro_sql = " Campo senha nao Informado.";
       $this->erro_campo = "senha";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->usuarioativo == null ){
       $this->erro_sql = " Campo se usuario ativo ou nao nao Informado.";
       $this->erro_campo = "usuarioativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->usuext == null ){
       $this->erro_sql = " Campo Utiliza DBPortal nao Informado.";
       $this->erro_campo = "usuext";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->administrador == null ){
       $this->erro_sql = " Campo Administrador nao Informado.";
       $this->erro_campo = "administrador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($id_usuario == "" || $id_usuario == null ){
       $result = db_query("select nextval('db_usuarios_id_usuario_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_usuarios_id_usuario_seq do campo: id_usuario";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->id_usuario = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from db_usuarios_id_usuario_seq");
       if(($result != false) && (pg_result($result,0,0) < $id_usuario)){
         $this->erro_sql = " Campo id_usuario maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->id_usuario = $id_usuario;
       }
     }
     if(($this->id_usuario == null) || ($this->id_usuario == "") ){
       $this->erro_sql = " Campo id_usuario nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_usuarios(
                                       id_usuario
                                      ,nome
                                      ,login
                                      ,senha
                                      ,usuarioativo
                                      ,email
                                      ,usuext
                                      ,administrador
                       )
                values (
                                $this->id_usuario
                               ,'$this->nome'
                               ,'$this->login'
                               ,'$this->senha'
                               ,'$this->usuarioativo'
                               ,'$this->email'
                               ,$this->usuext
                               ,$this->administrador
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->id_usuario) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->id_usuario) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->id_usuario));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,568,'$this->id_usuario','I')");
       $resac = db_query("insert into db_acount values($acount,109,568,'','".AddSlashes(pg_result($resaco,0,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,109,570,'','".AddSlashes(pg_result($resaco,0,'nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,109,571,'','".AddSlashes(pg_result($resaco,0,'login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,109,572,'','".AddSlashes(pg_result($resaco,0,'senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,109,573,'','".AddSlashes(pg_result($resaco,0,'usuarioativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,109,574,'','".AddSlashes(pg_result($resaco,0,'email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,109,3598,'','".AddSlashes(pg_result($resaco,0,'usuext'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,109,12093,'','".AddSlashes(pg_result($resaco,0,'administrador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($id_usuario=null) {
      $this->atualizacampos();
     $sql = " update db_usuarios set ";
     $virgula = "";
     if(trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){
       $sql  .= $virgula." id_usuario = $this->id_usuario ";
       $virgula = ",";
       if(trim($this->id_usuario) == null ){
         $this->erro_sql = " Campo Cod. Usu�rio nao Informado.";
         $this->erro_campo = "id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["nome"])){
       $sql  .= $virgula." nome = '$this->nome' ";
       $virgula = ",";
       if(trim($this->nome) == null ){
         $this->erro_sql = " Campo nome do usuario nao Informado.";
         $this->erro_campo = "nome";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->login)!="" || isset($GLOBALS["HTTP_POST_VARS"]["login"])){
       $sql  .= $virgula." login = '$this->login' ";
       $virgula = ",";
       if(trim($this->login) == null ){
         $this->erro_sql = " Campo login do usuario nao Informado.";
         $this->erro_campo = "login";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if( trim($this->senha)!="" ){

       $sql  .= $virgula." senha = '$this->senha' ";
       $virgula = ",";

       /*if(trim($this->senha) == null ){
         $this->erro_sql = " Campo senha nao Informado.";
         $this->erro_campo = "senha";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }*/
     }
     if(trim($this->usuarioativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["usuarioativo"])){
       $sql  .= $virgula." usuarioativo = '$this->usuarioativo' ";
       $virgula = ",";
       if(trim($this->usuarioativo) == null ){
         $this->erro_sql = " Campo se usuario ativo ou nao nao Informado.";
         $this->erro_campo = "usuarioativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->email)!="" || isset($GLOBALS["HTTP_POST_VARS"]["email"])){
       $sql  .= $virgula." email = '$this->email' ";
       $virgula = ",";
     }
     if(trim($this->usuext)!="" || isset($GLOBALS["HTTP_POST_VARS"]["usuext"])){
       $sql  .= $virgula." usuext = $this->usuext ";
       $virgula = ",";
       if(trim($this->usuext) == null ){
         $this->erro_sql = " Campo Utiliza DBPortal nao Informado.";
         $this->erro_campo = "usuext";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->administrador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["administrador"])){
       $sql  .= $virgula." administrador = $this->administrador ";
       $virgula = ",";
       if(trim($this->administrador) == null ){
         $this->erro_sql = " Campo Administrador nao Informado.";
         $this->erro_campo = "administrador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($id_usuario!=null){
       $sql .= " id_usuario = $this->id_usuario";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->id_usuario));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,568,'$this->id_usuario','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,109,568,'".AddSlashes(pg_result($resaco,$conresaco,'id_usuario'))."','$this->id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["nome"]))
           $resac = db_query("insert into db_acount values($acount,109,570,'".AddSlashes(pg_result($resaco,$conresaco,'nome'))."','$this->nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["login"]))
           $resac = db_query("insert into db_acount values($acount,109,571,'".AddSlashes(pg_result($resaco,$conresaco,'login'))."','$this->login',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["senha"]))
           $resac = db_query("insert into db_acount values($acount,109,572,'".AddSlashes(pg_result($resaco,$conresaco,'senha'))."','$this->senha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["usuarioativo"]))
           $resac = db_query("insert into db_acount values($acount,109,573,'".AddSlashes(pg_result($resaco,$conresaco,'usuarioativo'))."','$this->usuarioativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["email"]))
           $resac = db_query("insert into db_acount values($acount,109,574,'".AddSlashes(pg_result($resaco,$conresaco,'email'))."','$this->email',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["usuext"]))
           $resac = db_query("insert into db_acount values($acount,109,3598,'".AddSlashes(pg_result($resaco,$conresaco,'usuext'))."','$this->usuext',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["administrador"]))
           $resac = db_query("insert into db_acount values($acount,109,12093,'".AddSlashes(pg_result($resaco,$conresaco,'administrador'))."','$this->administrador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->id_usuario;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($id_usuario=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($id_usuario));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,568,'$id_usuario','E')");
         $resac = db_query("insert into db_acount values($acount,109,568,'','".AddSlashes(pg_result($resaco,$iresaco,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,109,570,'','".AddSlashes(pg_result($resaco,$iresaco,'nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,109,571,'','".AddSlashes(pg_result($resaco,$iresaco,'login'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,109,572,'','".AddSlashes(pg_result($resaco,$iresaco,'senha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,109,573,'','".AddSlashes(pg_result($resaco,$iresaco,'usuarioativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,109,574,'','".AddSlashes(pg_result($resaco,$iresaco,'email'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,109,3598,'','".AddSlashes(pg_result($resaco,$iresaco,'usuext'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,109,12093,'','".AddSlashes(pg_result($resaco,$iresaco,'administrador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_usuarios
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($id_usuario != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " id_usuario = $id_usuario ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$id_usuario;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = " nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$id_usuario;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$id_usuario;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_usuarios";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function enviar_senha($id_usuario,$email,$nome,$login,$senha,$nomeinst,$url=null,$enviar){
    $erro = false;

    if ($enviar == true){
         $sConso = "bcdfghjklmnpqrstvwxyzbcdfghjklmnpqrstvwxyz";
         $sVogal = "aeiou";
         $sNum   = "123456789";
         $passwd = "";
         $y      = strlen($sConso)-1; //conta o num de caracteres da variavel $sConso
         $z      = strlen($sVogal)-1; //conta o num de caracteres da variavel $sVogal
         $r      = strlen($sNum)-1;   //conta o num de caracteres da variavel $sNum

         for($x=0;$x<=1;$x++){
              $rand    = rand(0,$y); //Funcao rand() - gera um valor randomico
              $rand1   = rand(0,$z);
              $rand2   = rand(0,$r);
              $str     = substr($sConso,$rand,1); // substr() - retorna parte de uma string
              $str1    = substr($sVogal,$rand1,1);
              $str2    = substr($sNum,$rand2,1);
              $passwd .= $str.$str1.$str2;
         }
         $mensagemDestinatario = "<html>
                                    <head>
                                        <title>DBSeller Inform&aacute;tica Ltda.</title>
                                        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
                                    </head>
                                  <body bgcolor=#CCCCCC bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
                                    <table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                                      <tr>
                                        <td nowrap align=\"center\" valign=\"top\">
                                          <table width=\"100%\" height=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
                                            <tr>
                                              <td nowrap bgcolor=\"#6699CC\"><font size=\"1\" color=\"#FFFFFF\" >&nbsp;&nbsp;$nomeinst</font></td>
                                              <td height=\"60\"  nowrap align=\"left\" bgcolor=\"#6699CC\"><font color=\"#FFFFFF\"><strong>&nbsp;&nbsp;&nbsp;.: Bem-vindo ao Prefeitura On-Line :.
                                            </tr>
                                            <tr>
                                              <td colspan=\"2\">
                                                <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                                  <tr>
                                                    <td>&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Foi solicitada uma senha para o usuario
                                                        <strong>$nome</strong>,<br> Esta senha foi gerada automaticamente para voce ter acesso ao sistema
                                                        <strong>Prefeitura On-Line.</strong>
                                                    </td>
                                                  </tr>
                                                  <tr>
                                                    <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
                                                  </tr>
                                                  <tr>
                                                        <td>
                                                      <fieldset>
                                                        <legend><strong><font size=\"2\"> Dados da conta</font> </strong></legend>
                                                                  <table>
                                                           <tr>
                                                                       <td><ul>
                                                                           <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Login: <strong>$login</strong></font></li>
                                                                 </ul></td>
                                                                     </tr>
                                                                       <tr>
                                                                         <td><ul>
                                                               <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Senha: </font><font size=\"2\" face=\"Arial, Helvetica, sans-serif\" atyle=\"letter-spacing:0.3em\"><strong>$passwd</strong></font></li>
                                                                                   </ul></td>
                                                                       </tr>
                                                                       <tr>
                                                                         <td><ul>
                                                                                 <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Data de criacao: </font><font size=\"2\" face=\"Arial, Helvetica, sans-serif\" atyle=\"letter-spacing:0.3em\"><strong>" . date("d-m-Y",db_getsession("DB_datausu")) . "</strong></font></li>
                                                                 </ul></td>
                                                                       </tr>
                                                                       <tr>
                                                                       <td><ul>
                                                                                 <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Hora de criacao: </font><font size=\"2\" face=\"Arial, Helvetica, sans-serif\" atyle=\"letter-spacing:0.3em\"><strong>" . db_hora() . "</strong></font></li>
                                                                                 </ul></td>
                                                                       </tr>
                                                                      </table>
                                                            </fieldset>
                                                          </td>
                                                        </tr>
                                                  <tr>
                                                    <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
                                                  </tr>";
         if ($url != null){
              $mensagemDestinatario .= "
                                                  <tr>
                                                    <td align=\"center\"><p><a target=\"_blank\" href=\"$url\"><font size=\"2\">Para acessar o sistema clique aqui e escolha a opcao Prefeitura OnLine</font></a></p></td>
                                                  </tr>";
         }

         $mensagemDestinatario .= "
                                                  <tr>
                                                    <td>&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td align=\"center\"><p><font size=\"1\">Este e-mail foi enviado automaticamente,
                                                                                             por favor nao responda.</font></p></td>
                                                  </tr>
                                                  <tr>
                                                    <td align=\"center\"><p><a target=\"_blank\" href=\"http://www.dbseller.com.br\"><font size=\"1\">DBSeller Inform&aacute;tica Ltda.</font></a></p></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table></td>
                                        </tr>
                                      </table>
                                    </body>
                                  </html>";
    } else {
         $mensagemDestinatario = "<html>
                                   <head>
                                    <title>DBSeller Inform&aacute;tica Ltda.</title>
                                    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
                                   </head>
                                   <body bgcolor=#CCCCCC bgcolor=\"#FFFFFF\" leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\">
                                    <table width=\"100%\" height=\"100%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
                                     <tr>
                                       <td nowrap align=\"center\" valign=\"top\">
                                         <table width=\"100%\" height=\"100%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
                                          <tr>
                                            <td nowrap bgcolor=\"#6699CC\"><font size=\"1\" color=\"#FFFFFF\" >&nbsp;&nbsp;$nomeinst</font></td>
                                            <td height=\"60\"  nowrap align=\"left\" bgcolor=\"#6699CC\"><font color=\"#FFFFFF\"><strong>&nbsp;&nbsp;&nbsp;.: Bem-vindo ao Prefeitura On-Line :.
                                          </tr>
                                          <tr>
                                            <td colspan=\"2\">
                                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                                               <tr>
                                                 <td>&nbsp;</td>
                                               </tr>
                                               <tr>
                                                 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Usuario <strong>$nome</strong> foi incluido com sucesso, <br> Esta cadastro servira para voce ter acesso ao sistema
                                                     <strong>Prefeitura On-Line.</strong>
                                                 </td>
                                               </tr>
                                               <tr>
                                                 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
                                               </tr>
                                               <tr>
                                                       <td>
                                                               <fieldset>
                                                                 <legend><strong><font size=\"2\"> Dados da conta</font> </strong></legend>
                                                                   <table>
                                                                    <tr>
                                                                      <td><ul>
                                                                              <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Login: <strong>$login</strong></font></li>
                                                                                </ul></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><ul>
                                                                              <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Senha: </font><font size=\"2\" face=\"Arial, Helvetica, sans-serif\" atyle=\"letter-spacing:0.3em\"><strong>$senha</strong></font></li>
                                                                                </ul></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td nowrap><ul>
                                                                              <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Data de criacao: </font><font size=\"2\" face=\"Arial, Helvetica, sans-serif\" atyle=\"letter-spacing:0.3em\"><strong>" . date("d-m-Y",db_getsession("DB_datausu")) . "</strong></font></li>
                                                                                 </ul></td>
                                                        </tr>
                                                                    <tr>
                                                                      <td><ul>
                                                                              <li><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Hora de criacao: </font><font size=\"2\" face=\"Arial, Helvetica, sans-serif\" atyle=\"letter-spacing:0.3em\"><strong>" . db_hora() . "</strong></font></li>
                                                                                </ul></td>
                                                                    </tr>
                                                                  </table>
                                                         </fieldset>
                                                       </td>
                                                     </tr>
                                               <tr>
                                                 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;</font></td>
                                               </tr>";
         if ($url != null){
              $mensagemDestinatario .= "
                                               <tr>
                                                 <td align=\"center\"><p><a target=\"_blank\" href=\"$url\"><font size=\"2\">Para acessar o sistema clique aqui e escolha a opcao Prefeitura OnLine</font></a></p></td>
                                               </tr>";
         }

         $mensagemDestinatario .= "
                                               <tr>
                                                 <td>&nbsp;</td>
                                               </tr>
                                               <tr>
                                                 <td align=\"center\"><p><font size=\"1\">Este e-mail foi enviado automaticamente,
                                                                                          por favor nao responda.</font></p></td>
                                               </tr>
                                               <tr>
                                                 <td align=\"center\"><p><a target=\"_blank\" href=\"http://www.dbseller.com.br\"><font size=\"1\">DBSeller Inform&aacute;tica Ltda.</font></a></p></td>
                                               </tr>
                                              </table></td>
                                            </tr>
                                         </table></td>
                                       </tr>
                                     </table>
                                   </body>
                                 </html>";
    }

    $headers = "Content-Type:text/html;";
    $erro    = mail($email,"Senha do site Prefeitura On-Line",$mensagemDestinatario,$headers);

    if ($erro == false && $enviar == true){
         $this->senha = Encriptacao::encriptaSenha($passwd);

         $this->alterar($id_usuario);
         if ($this->erro_status == "0"){
              $erro = true;
         }
    }

    return $erro;
  }
   function sql_query ( $id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_usuarios ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_usuario!=null ){
         $sql2 .= " where db_usuarios.id_usuario = $id_usuario ";
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
   function sql_query_file ( $id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_usuarios ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_usuario!=null ){
         $sql2 .= " where db_usuarios.id_usuario = $id_usuario ";
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

  function sql_query_instit ( $id_usuario=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_usuarios ";
     $sql .= "      inner join db_depusu on db_usuarios.id_usuario = db_depusu.id_usuario ";
     $sql .= "      inner join db_depart on db_depart.coddepto     = db_depusu.coddepto ";
     $sql2 = "";
     if($dbwhere==""){
       if($id_usuario!=null ){
         $sql2 .= " where db_usuarios.id_usuario = $id_usuario ";
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