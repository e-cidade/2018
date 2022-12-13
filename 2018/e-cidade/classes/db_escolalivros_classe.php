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
//CLASSE DA ENTIDADE escolalivros
class cl_escolalivros {
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
   var $ed134_i_codigo = 0;
   var $ed134_i_escola = 0;
   var $ed134_i_ano = 0;
   var $ed134_i_serie = 0;
   var $ed134_i_devolucao = 0;
   var $ed134_i_reutilizavel = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed134_i_codigo = int8 = Código
                 ed134_i_escola = int8 = Escola
                 ed134_i_ano = int4 = Ano Referente
                 ed134_i_serie = int4 = Série/Ano
                 ed134_i_devolucao = int4 = Qtde. Livros Devolvidos no Ano Anterior
                 ed134_i_reutilizavel = int4 = Qtde. Livros Devolvidos com Reutilização
                 ";
   //funcao construtor da classe
   function cl_escolalivros() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("escolalivros");
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
       $this->ed134_i_codigo = ($this->ed134_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_i_codigo"]:$this->ed134_i_codigo);
       $this->ed134_i_escola = ($this->ed134_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_i_escola"]:$this->ed134_i_escola);
       $this->ed134_i_ano = ($this->ed134_i_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_i_ano"]:$this->ed134_i_ano);
       $this->ed134_i_serie = ($this->ed134_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_i_serie"]:$this->ed134_i_serie);
       $this->ed134_i_devolucao = ($this->ed134_i_devolucao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_i_devolucao"]:$this->ed134_i_devolucao);
       $this->ed134_i_reutilizavel = ($this->ed134_i_reutilizavel == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_i_reutilizavel"]:$this->ed134_i_reutilizavel);
     }else{
       $this->ed134_i_codigo = ($this->ed134_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed134_i_codigo"]:$this->ed134_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed134_i_codigo){
      $this->atualizacampos();
     if($this->ed134_i_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed134_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed134_i_ano == null ){
       $this->erro_sql = " Campo Ano Referente nao Informado.";
       $this->erro_campo = "ed134_i_ano";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed134_i_serie == null ){
       $this->erro_sql = " Campo Série/Ano nao Informado.";
       $this->erro_campo = "ed134_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed134_i_devolucao == null ){
       $this->ed134_i_devolucao = "null";
     }
     if($this->ed134_i_reutilizavel == null ){
       $this->ed134_i_reutilizavel = "null";
     }
     if($ed134_i_codigo == "" || $ed134_i_codigo == null ){
       $result = db_query("select nextval('escolalivros_ed134_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: escolalivros_ed134_i_codigo_seq do campo: ed134_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed134_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from escolalivros_ed134_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed134_i_codigo)){
         $this->erro_sql = " Campo ed134_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed134_i_codigo = $ed134_i_codigo;
       }
     }
     if(($this->ed134_i_codigo == null) || ($this->ed134_i_codigo == "") ){
       $this->erro_sql = " Campo ed134_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into escolalivros(
                                       ed134_i_codigo
                                      ,ed134_i_escola
                                      ,ed134_i_ano
                                      ,ed134_i_serie
                                      ,ed134_i_devolucao
                                      ,ed134_i_reutilizavel
                       )
                values (
                                $this->ed134_i_codigo
                               ,$this->ed134_i_escola
                               ,$this->ed134_i_ano
                               ,$this->ed134_i_serie
                               ,$this->ed134_i_devolucao
                               ,$this->ed134_i_reutilizavel
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Devolução de Livros por Escola ($this->ed134_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Devolução de Livros por Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Devolução de Livros por Escola ($this->ed134_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed134_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed134_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,13461,'$this->ed134_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2355,13461,'','".AddSlashes(pg_result($resaco,0,'ed134_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2355,13462,'','".AddSlashes(pg_result($resaco,0,'ed134_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2355,13463,'','".AddSlashes(pg_result($resaco,0,'ed134_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2355,13464,'','".AddSlashes(pg_result($resaco,0,'ed134_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2355,13465,'','".AddSlashes(pg_result($resaco,0,'ed134_i_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2355,13466,'','".AddSlashes(pg_result($resaco,0,'ed134_i_reutilizavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed134_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update escolalivros set ";
     $virgula = "";
     if(trim($this->ed134_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_codigo"])){
       $sql  .= $virgula." ed134_i_codigo = $this->ed134_i_codigo ";
       $virgula = ",";
       if(trim($this->ed134_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed134_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed134_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_escola"])){
       $sql  .= $virgula." ed134_i_escola = $this->ed134_i_escola ";
       $virgula = ",";
       if(trim($this->ed134_i_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed134_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed134_i_ano)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_ano"])){
       $sql  .= $virgula." ed134_i_ano = $this->ed134_i_ano ";
       $virgula = ",";
       if(trim($this->ed134_i_ano) == null ){
         $this->erro_sql = " Campo Ano Referente nao Informado.";
         $this->erro_campo = "ed134_i_ano";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed134_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_serie"])){
       $sql  .= $virgula." ed134_i_serie = $this->ed134_i_serie ";
       $virgula = ",";
       if(trim($this->ed134_i_serie) == null ){
         $this->erro_sql = " Campo Série/Ano nao Informado.";
         $this->erro_campo = "ed134_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed134_i_devolucao)==null){
       $this->ed134_i_devolucao = "null" ;
     }
     $sql  .= $virgula." ed134_i_devolucao = $this->ed134_i_devolucao ";
     $virgula = ",";
     if(trim($this->ed134_i_reutilizavel)==null){
       $this->ed134_i_reutilizavel = "null" ;
     }
     $sql  .= $virgula." ed134_i_reutilizavel = $this->ed134_i_reutilizavel ";
     $virgula = ",";
     $sql .= " where ";
     if($ed134_i_codigo!=null){
       $sql .= " ed134_i_codigo = $this->ed134_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed134_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13461,'$this->ed134_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2355,13461,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_i_codigo'))."','$this->ed134_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,2355,13462,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_i_escola'))."','$this->ed134_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_ano"]))
           $resac = db_query("insert into db_acount values($acount,2355,13463,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_i_ano'))."','$this->ed134_i_ano',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_serie"]))
           $resac = db_query("insert into db_acount values($acount,2355,13464,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_i_serie'))."','$this->ed134_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_devolucao"]))
           $resac = db_query("insert into db_acount values($acount,2355,13465,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_i_devolucao'))."','$this->ed134_i_devolucao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed134_i_reutilizavel"]))
           $resac = db_query("insert into db_acount values($acount,2355,13466,'".AddSlashes(pg_result($resaco,$conresaco,'ed134_i_reutilizavel'))."','$this->ed134_i_reutilizavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devolução de Livros por Escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed134_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Devolução de Livros por Escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed134_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed134_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed134_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed134_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13461,'$ed134_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2355,13461,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2355,13462,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2355,13463,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_i_ano'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2355,13464,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2355,13465,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_i_devolucao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2355,13466,'','".AddSlashes(pg_result($resaco,$iresaco,'ed134_i_reutilizavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from escolalivros
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed134_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed134_i_codigo = $ed134_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Devolução de Livros por Escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed134_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Devolução de Livros por Escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed134_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed134_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:escolalivros";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   function sql_query ( $ed134_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from escolalivros ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = escolalivros.ed134_i_escola";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join censouf  on  censouf.ed260_i_codigo = escola.ed18_i_censouf";
     $sql .= "      inner join censomunic  on  censomunic.ed261_i_codigo = escola.ed18_i_censomunic";
     $sql .= "      left join censodistrito  on  censodistrito.ed262_i_codigo = escola.ed18_i_censodistrito";
     $sql .= "      left  join censoorgreg  on  censoorgreg.ed263_i_codigo = escola.ed18_i_censoorgreg";
     $sql .= "      left  join censolinguaindig  on  censolinguaindig.ed264_i_codigo = escola.ed18_i_linguaindigena";
     $sql2 = "";
     if($dbwhere==""){
       if($ed134_i_codigo!=null ){
         $sql2 .= " where escolalivros.ed134_i_codigo = $ed134_i_codigo ";
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
   function sql_query_file ( $ed134_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from escolalivros ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed134_i_codigo!=null ){
         $sql2 .= " where escolalivros.ed134_i_codigo = $ed134_i_codigo ";
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