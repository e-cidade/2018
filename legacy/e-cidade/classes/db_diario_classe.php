<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
//CLASSE DA ENTIDADE diario
class cl_diario {
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
   var $ed95_i_codigo = 0;
   var $ed95_i_escola = 0;
   var $ed95_i_calendario = 0;
   var $ed95_i_aluno = 0;
   var $ed95_i_serie = 0;
   var $ed95_i_regencia = 0;
   var $ed95_c_encerrado = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed95_i_codigo = int8 = Código
                 ed95_i_escola = int8 = Escola
                 ed95_i_calendario = int8 = Calendário
                 ed95_i_aluno = int8 = Aluno
                 ed95_i_serie = int8 = Série
                 ed95_i_regencia = int8 = Disciplina
                 ed95_c_encerrado = char(1) = Encerrado
                 ";
   //funcao construtor da classe
   function cl_diario() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("diario");
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
       $this->ed95_i_codigo = ($this->ed95_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed95_i_codigo"]:$this->ed95_i_codigo);
       $this->ed95_i_escola = ($this->ed95_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed95_i_escola"]:$this->ed95_i_escola);
       $this->ed95_i_calendario = ($this->ed95_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed95_i_calendario"]:$this->ed95_i_calendario);
       $this->ed95_i_aluno = ($this->ed95_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed95_i_aluno"]:$this->ed95_i_aluno);
       $this->ed95_i_serie = ($this->ed95_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed95_i_serie"]:$this->ed95_i_serie);
       $this->ed95_i_regencia = ($this->ed95_i_regencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed95_i_regencia"]:$this->ed95_i_regencia);
       $this->ed95_c_encerrado = ($this->ed95_c_encerrado == ""?@$GLOBALS["HTTP_POST_VARS"]["ed95_c_encerrado"]:$this->ed95_c_encerrado);
     }else{
       $this->ed95_i_codigo = ($this->ed95_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed95_i_codigo"]:$this->ed95_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed95_i_codigo){
      $this->atualizacampos();
     if($this->ed95_i_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed95_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed95_i_calendario == null ){
       $this->erro_sql = " Campo Calendário nao Informado.";
       $this->erro_campo = "ed95_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed95_i_aluno == null ){
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed95_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed95_i_serie == null ){
       $this->erro_sql = " Campo Série nao Informado.";
       $this->erro_campo = "ed95_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed95_i_regencia == null ){
       $this->erro_sql = " Campo Disciplina nao Informado.";
       $this->erro_campo = "ed95_i_regencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed95_c_encerrado == null ){
       $this->erro_sql = " Campo Encerrado nao Informado.";
       $this->erro_campo = "ed95_c_encerrado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed95_i_codigo == "" || $ed95_i_codigo == null ){
       $result = db_query("select nextval('diario_ed95_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: diario_ed95_i_codigo_seq do campo: ed95_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed95_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from diario_ed95_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed95_i_codigo)){
         $this->erro_sql = " Campo ed95_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed95_i_codigo = $ed95_i_codigo;
       }
     }
     if(($this->ed95_i_codigo == null) || ($this->ed95_i_codigo == "") ){
       $this->erro_sql = " Campo ed95_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into diario(
                                       ed95_i_codigo
                                      ,ed95_i_escola
                                      ,ed95_i_calendario
                                      ,ed95_i_aluno
                                      ,ed95_i_serie
                                      ,ed95_i_regencia
                                      ,ed95_c_encerrado
                       )
                values (
                                $this->ed95_i_codigo
                               ,$this->ed95_i_escola
                               ,$this->ed95_i_calendario
                               ,$this->ed95_i_aluno
                               ,$this->ed95_i_serie
                               ,$this->ed95_i_regencia
                               ,'$this->ed95_c_encerrado'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Diário de Classe ($this->ed95_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Diário de Classe já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Diário de Classe ($this->ed95_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed95_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed95_i_codigo));
       if(($resaco!=false)||($this->numrows!=0)){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008657,'$this->ed95_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010118,1008657,'','".AddSlashes(pg_result($resaco,0,'ed95_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010118,1008658,'','".AddSlashes(pg_result($resaco,0,'ed95_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010118,1008659,'','".AddSlashes(pg_result($resaco,0,'ed95_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010118,1008660,'','".AddSlashes(pg_result($resaco,0,'ed95_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010118,1008661,'','".AddSlashes(pg_result($resaco,0,'ed95_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010118,1008662,'','".AddSlashes(pg_result($resaco,0,'ed95_i_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010118,1008809,'','".AddSlashes(pg_result($resaco,0,'ed95_c_encerrado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed95_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update diario set ";
     $virgula = "";
     if(trim($this->ed95_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_codigo"])){
       $sql  .= $virgula." ed95_i_codigo = $this->ed95_i_codigo ";
       $virgula = ",";
       if(trim($this->ed95_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed95_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed95_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_escola"])){
       $sql  .= $virgula." ed95_i_escola = $this->ed95_i_escola ";
       $virgula = ",";
       if(trim($this->ed95_i_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed95_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed95_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_calendario"])){
       $sql  .= $virgula." ed95_i_calendario = $this->ed95_i_calendario ";
       $virgula = ",";
       if(trim($this->ed95_i_calendario) == null ){
         $this->erro_sql = " Campo Calendário nao Informado.";
         $this->erro_campo = "ed95_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed95_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_aluno"])){
       $sql  .= $virgula." ed95_i_aluno = $this->ed95_i_aluno ";
       $virgula = ",";
       if(trim($this->ed95_i_aluno) == null ){
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed95_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed95_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_serie"])){
       $sql  .= $virgula." ed95_i_serie = $this->ed95_i_serie ";
       $virgula = ",";
       if(trim($this->ed95_i_serie) == null ){
         $this->erro_sql = " Campo Série nao Informado.";
         $this->erro_campo = "ed95_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed95_i_regencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_regencia"])){
       $sql  .= $virgula." ed95_i_regencia = $this->ed95_i_regencia ";
       $virgula = ",";
       if(trim($this->ed95_i_regencia) == null ){
         $this->erro_sql = " Campo Disciplina nao Informado.";
         $this->erro_campo = "ed95_i_regencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed95_c_encerrado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed95_c_encerrado"])){
       $sql  .= $virgula." ed95_c_encerrado = '$this->ed95_c_encerrado' ";
       $virgula = ",";
       if(trim($this->ed95_c_encerrado) == null ){
         $this->erro_sql = " Campo Encerrado nao Informado.";
         $this->erro_campo = "ed95_c_encerrado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed95_i_codigo!=null){
       $sql .= " ed95_i_codigo = $this->ed95_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed95_i_codigo));
       if($this->numrows>0){
         for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008657,'$this->ed95_i_codigo','A')");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_codigo"]))
             $resac = db_query("insert into db_acount values($acount,1010118,1008657,'".AddSlashes(pg_result($resaco,$conresaco,'ed95_i_codigo'))."','$this->ed95_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_escola"]))
             $resac = db_query("insert into db_acount values($acount,1010118,1008658,'".AddSlashes(pg_result($resaco,$conresaco,'ed95_i_escola'))."','$this->ed95_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_calendario"]))
             $resac = db_query("insert into db_acount values($acount,1010118,1008659,'".AddSlashes(pg_result($resaco,$conresaco,'ed95_i_calendario'))."','$this->ed95_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_aluno"]))
             $resac = db_query("insert into db_acount values($acount,1010118,1008660,'".AddSlashes(pg_result($resaco,$conresaco,'ed95_i_aluno'))."','$this->ed95_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_serie"]))
             $resac = db_query("insert into db_acount values($acount,1010118,1008661,'".AddSlashes(pg_result($resaco,$conresaco,'ed95_i_serie'))."','$this->ed95_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed95_i_regencia"]))
             $resac = db_query("insert into db_acount values($acount,1010118,1008662,'".AddSlashes(pg_result($resaco,$conresaco,'ed95_i_regencia'))."','$this->ed95_i_regencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if(isset($GLOBALS["HTTP_POST_VARS"]["ed95_c_encerrado"]))
             $resac = db_query("insert into db_acount values($acount,1010118,1008809,'".AddSlashes(pg_result($resaco,$conresaco,'ed95_c_encerrado'))."','$this->ed95_c_encerrado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);

     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diário de Classe nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed95_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diário de Classe nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed95_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed95_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed95_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (isset($lSessaoDesativarAccount) && $lSessaoDesativarAccount === false) {
       if($dbwhere==null || $dbwhere==""){
         $resaco = $this->sql_record($this->sql_query_file($ed95_i_codigo));
       }else{
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if(($resaco!=false)||($this->numrows!=0)){
         for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008657,'$ed95_i_codigo','E')");
           $resac = db_query("insert into db_acount values($acount,1010118,1008657,'','".AddSlashes(pg_result($resaco,$iresaco,'ed95_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010118,1008658,'','".AddSlashes(pg_result($resaco,$iresaco,'ed95_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010118,1008659,'','".AddSlashes(pg_result($resaco,$iresaco,'ed95_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010118,1008660,'','".AddSlashes(pg_result($resaco,$iresaco,'ed95_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010118,1008661,'','".AddSlashes(pg_result($resaco,$iresaco,'ed95_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010118,1008662,'','".AddSlashes(pg_result($resaco,$iresaco,'ed95_i_regencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac = db_query("insert into db_acount values($acount,1010118,1008809,'','".AddSlashes(pg_result($resaco,$iresaco,'ed95_c_encerrado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from diario
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed95_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed95_i_codigo = $ed95_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Diário de Classe nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed95_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Diário de Classe nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed95_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed95_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:diario";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed95_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diario ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = diario.ed95_i_escola";
     $sql .= "      inner join serie  on  serie.ed11_i_codigo = diario.ed95_i_serie";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = diario.ed95_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = diario.ed95_i_calendario";
     $sql .= "      inner join regencia  on  regencia.ed59_i_codigo = diario.ed95_i_regencia";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join duracaocal  on  duracaocal.ed55_i_codigo = calendario.ed52_i_duracaocal";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = regencia.ed59_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = regencia.ed59_i_turma";
     $sql2 = "";
     if($dbwhere==""){
       if($ed95_i_codigo!=null ){
         $sql2 .= " where diario.ed95_i_codigo = $ed95_i_codigo ";
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
   function sql_query_matric ( $ed95_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diario ";
     $sql .= "      inner join aluno on ed47_i_codigo = ed95_i_aluno";
     $sql .= "      inner join matricula on ed60_i_aluno = ed47_i_codigo";
     $sql .= "      inner join regencia on ed59_i_codigo = ed95_i_regencia";
     $sql2 = "";
     if($dbwhere==""){
       if($ed95_i_codigo!=null ){
         $sql2 .= " where diario.ed95_i_codigo = $ed95_i_codigo ";
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

   function sql_query_file ( $ed95_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from diario ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed95_i_codigo!=null ){
         $sql2 .= " where diario.ed95_i_codigo = $ed95_i_codigo ";
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

  function sql_query_relatorio($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }

    $sSql .= ' from diario ';
    $sSql .= ' inner join calendario on ed52_i_codigo = ed95_i_calendario ';
    $sSql .= ' inner join diariofinal on ed74_i_diario = ed95_i_codigo ';
    $sSql .= ' inner join regencia on ed59_i_codigo = ed95_i_regencia ';
    $sSql .= ' inner join disciplina on ed12_i_codigo = ed59_i_disciplina ';
    $sSql .= ' inner join caddisciplina on ed232_i_codigo = ed12_i_caddisciplina ';
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where diario.ed95_i_codigo = $iCodigo ";
      }

    } elseif ($sDbWhere != '') {
      $sSql2 = " where $sDbWhere";
    }
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }

  function sql_query_diario_classe ($ed95_i_codigo=null,$campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from diario ";
     $sql .= "      inner join aluno          on ed47_i_codigo = ed95_i_aluno";
     $sql .= "      inner join matricula      on ed60_i_aluno  = ed47_i_codigo";
     $sql .= "      inner join matriculaserie on ed60_i_codigo = ed221_i_matricula";
     $sql .= "      inner join regencia       on ed59_i_codigo = ed95_i_regencia  ";
     $sql .= "                               and ed59_i_serie  = ed221_i_serie ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed95_i_codigo!=null ){
         $sql2 .= " where diario.ed95_i_codigo = $ed95_i_codigo ";
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

  /**
   *
   */
  public function sql_query_avaliacoes_periodo($iCodigoDiario) {

     $sCamposAvaliacao  = "  ed72_i_codigo         as codigo,";
     $sCamposAvaliacao .= "  ed72_i_procavaliacao  as codigo_elemento,";
     $sCamposAvaliacao .= "  ed72_i_numfaltas      as numero_faltas,";
     $sCamposAvaliacao .= "  ed80_i_codigo         as codigo_faltas_abonadas,";
     $sCamposAvaliacao .= "  ed72_i_valornota      as valor_nota,";
     $sCamposAvaliacao .= "  ed72_i_valornota      as valor_nota_real,";
     $sCamposAvaliacao .= "  ed72_c_valorconceito  as valor_conceito,";
     $sCamposAvaliacao .= "  ed72_t_parecer        as parecer, ";
     $sCamposAvaliacao .= "  ed72_c_aprovmin       as minimo, ";
     $sCamposAvaliacao .= "  ed72_c_amparo         as amparo, ";
     $sCamposAvaliacao .= "  ed41_i_sequencia      as sequencia, ";
     $sCamposAvaliacao .= "  trim(ed93_t_parecer)  as parecerpadronizado, ";
     $sCamposAvaliacao .= "  ed72_i_escola         as escola, ";
     $sCamposAvaliacao .= "  ed72_c_tipo           as origem, ";
     $sCamposAvaliacao .= "  ed72_c_convertido     as convertido, ";
     $sCamposAvaliacao .= "  (select ed39_i_sequencia ";
     $sCamposAvaliacao .= "      from conceito ";
     $sCamposAvaliacao .= "     where conceito.ed39_i_formaavaliacao = ed41_i_formaavaliacao";
     $sCamposAvaliacao .= "       and conceito.ed39_c_conceito = ed72_c_valorconceito) as ordem_conceito, ";
     $sCamposAvaliacao .= "  'A'                   as tipo_elemento, ";
     $sCamposAvaliacao .= "  ed72_t_obs            as observacao, ";
     $sCamposAvaliacao .= "  false                 as em_recuperacao ";

     $sSqlAvaliacao     = " select {$sCamposAvaliacao} ";
     $sSqlAvaliacao    .= " from diarioavaliacao ";
     $sSqlAvaliacao    .= "      inner join procavaliacao on ed41_i_codigo          = ed72_i_procavaliacao ";
     $sSqlAvaliacao    .= "      left  join pareceraval   on ed93_i_diarioavaliacao = ed72_i_codigo";
     $sSqlAvaliacao    .= "      left  join abonofalta    on ed80_i_diarioavaliacao = ed72_i_codigo";
     $sSqlAvaliacao    .= " where ed72_i_diario = {$iCodigoDiario} ";

     $sCamposResultado  = "  ed73_i_codigo         as codigo,";
     $sCamposResultado .= "  ed73_i_procresultado  as codigo_elemento,";
     $sCamposResultado .= "  null                  as numero_faltas,";
     $sCamposResultado .= "  null                  as codigo_faltas_abonadas,";
     $sCamposResultado .= "  ed73_i_valornota      as valor_nota,";
     $sCamposResultado .= "  ed73_valorreal        as valor_nota_real,";
     $sCamposResultado .= "  ed73_c_valorconceito  as valor_conceito,";
     $sCamposResultado .= "  ed73_t_parecer        as parecer, ";
     $sCamposResultado .= "  ed73_c_aprovmin       as minimo,  ";
     $sCamposResultado .= "  ed73_c_amparo         as amparo,  ";
     $sCamposResultado .= "  ed43_i_sequencia      as sequencia, ";
     $sCamposResultado .= "  trim(ed63_t_parecer)  as parecerpadronizado, ";
     $sCamposResultado .= "  null         as escola, ";
     $sCamposResultado .= "  null         as origem, ";
     $sCamposResultado .= "  null         as convertido, ";
     $sCamposResultado .= "  (select ed39_i_sequencia ";
     $sCamposResultado .= "      from conceito ";
     $sCamposResultado .= "     where conceito.ed39_i_formaavaliacao = ed43_i_formaavaliacao";
     $sCamposResultado .= "       and conceito.ed39_c_conceito = ed73_c_valorconceito) as ordem_conceito, ";
     $sCamposResultado .= "  'R'                   as tipo_elemento, ";
     $sCamposResultado .= "  ''                    as observacao, ";
     $sCamposResultado .= "  ed116_diarioresultado is not null   as em_recuperacao ";

     $sSqlResultado     = "select {$sCamposResultado} ";
     $sSqlResultado    .= " from diarioresultado ";
     $sSqlResultado    .= "      inner join procresultado              on ed43_i_codigo          = ed73_i_procresultado";
     $sSqlResultado    .= "      left  join parecerresult              on ed63_i_diarioresultado = ed73_i_codigo";
     $sSqlResultado    .= "      left  join diarioresultadorecuperacao on ed116_diarioresultado  = ed73_i_codigo";
     $sSqlResultado    .= " where ed73_i_diario = {$iCodigoDiario} ";
     $sSqlResultado    .= " order by sequencia ";
     return "{$sSqlAvaliacao} union {$sSqlResultado} ";
  }

  function sql_query_regencia_aluno($ed95_i_codigo=null, $campos="*",$ordem=null,$dbwhere="") {

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
     $sql .= " from turma ";
     $sql .= "      inner join regencia on ed59_i_turma = ed57_i_codigo";
     $sql .= "      inner join matricula on ed57_i_codigo = ed60_i_turma";
     $sql .= "      inner join matriculaserie on ed60_i_codigo = ed221_i_matricula";
     $sql .= "      inner join diario on ed59_i_codigo = ed95_i_regencia ";
     $sql .= "      inner join aluno on ed47_i_codigo = ed95_i_aluno  ";
     $sql .= "                       and ed60_i_aluno  = ed47_i_codigo ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed95_i_codigo!=null ){
         $sql2 .= " where diario.ed95_i_codigo = $ed95_i_codigo ";
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

  function sql_query_regencia($ed95_i_codigo=null, $campos="*",$ordem=null,$dbwhere="") {

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
    $sql .= " from diario ";
    $sql .= "      inner join regencia  on ed59_i_codigo = ed95_i_regencia  ";
    $sql2 = "";
    if($dbwhere==""){
      if($ed95_i_codigo!=null ){
        $sql2 .= " where diario.ed95_i_codigo = $ed95_i_codigo ";
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