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

//MODULO: educação
//CLASSE DA ENTIDADE alunocurso
class cl_alunocurso {
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
   var $ed56_i_codigo = 0;
   var $ed56_i_escola = 0;
   var $ed56_i_aluno = 0;
   var $ed56_i_base = 0;
   var $ed56_i_calendario = 0;
   var $ed56_c_situacao = null;
   var $ed56_i_baseant = 0;
   var $ed56_i_calendarioant = 0;
   var $ed56_c_situacaoant = null;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed56_i_codigo = int8 = Código
                 ed56_i_escola = int8 = Escola
                 ed56_i_aluno = int8 = Aluno
                 ed56_i_base = int8 = Base Curricular
                 ed56_i_calendario = int8 = Calendário
                 ed56_c_situacao = char(20) = Situação
                 ed56_i_baseant = int4 = Base Anterior
                 ed56_i_calendarioant = int4 = Calendário Anterior
                 ed56_c_situacaoant = char(20) = Situação Anterior
                 ";
   //funcao construtor da classe
   function cl_alunocurso() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("alunocurso");
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed56_i_escola=".@$GLOBALS["HTTP_POST_VARS"]["ed56_i_escola"]."&ed18_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed18_c_descr"]."&ed56_i_aluno=".@$GLOBALS["HTTP_POST_VARS"]["ed56_i_aluno"]."&ed47_v_nome=".@$GLOBALS["HTTP_POST_VARS"]["ed47_v_nome"]);
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
       $this->ed56_i_codigo = ($this->ed56_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_i_codigo"]:$this->ed56_i_codigo);
       $this->ed56_i_escola = ($this->ed56_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_i_escola"]:$this->ed56_i_escola);
       $this->ed56_i_aluno = ($this->ed56_i_aluno == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_i_aluno"]:$this->ed56_i_aluno);
       $this->ed56_i_base = ($this->ed56_i_base == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_i_base"]:$this->ed56_i_base);
       $this->ed56_i_calendario = ($this->ed56_i_calendario == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_i_calendario"]:$this->ed56_i_calendario);
       $this->ed56_c_situacao = ($this->ed56_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_c_situacao"]:$this->ed56_c_situacao);
       $this->ed56_i_baseant = ($this->ed56_i_baseant == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_i_baseant"]:$this->ed56_i_baseant);
       $this->ed56_i_calendarioant = ($this->ed56_i_calendarioant == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_i_calendarioant"]:$this->ed56_i_calendarioant);
       $this->ed56_c_situacaoant = ($this->ed56_c_situacaoant == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_c_situacaoant"]:$this->ed56_c_situacaoant);
     }else{
       $this->ed56_i_codigo = ($this->ed56_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed56_i_codigo"]:$this->ed56_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed56_i_codigo){
      $this->atualizacampos();
     if($this->ed56_i_escola == null ){
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed56_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed56_i_aluno == null ){
       $this->erro_sql = " Campo Aluno nao Informado.";
       $this->erro_campo = "ed56_i_aluno";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed56_i_base == null ){
       $this->erro_sql = " Campo Base Curricular nao Informado.";
       $this->erro_campo = "ed56_i_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed56_i_calendario == null ){
       $this->erro_sql = " Campo Calendário nao Informado.";
       $this->erro_campo = "ed56_i_calendario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed56_c_situacao == null ){
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "ed56_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed56_i_baseant == null ){
       $this->ed56_i_baseant = "null";
     }
     if($this->ed56_i_calendarioant == null ){
       $this->ed56_i_calendarioant = "null";
     }
     if($ed56_i_codigo == "" || $ed56_i_codigo == null ){
       $result = db_query("select nextval('alunocurso_ed56_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: alunocurso_ed56_i_codigo_seq do campo: ed56_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed56_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from alunocurso_ed56_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed56_i_codigo)){
         $this->erro_sql = " Campo ed56_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed56_i_codigo = $ed56_i_codigo;
       }
     }
     if(($this->ed56_i_codigo == null) || ($this->ed56_i_codigo == "") ){
       $this->erro_sql = " Campo ed56_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into alunocurso(
                                       ed56_i_codigo
                                      ,ed56_i_escola
                                      ,ed56_i_aluno
                                      ,ed56_i_base
                                      ,ed56_i_calendario
                                      ,ed56_c_situacao
                                      ,ed56_i_baseant
                                      ,ed56_i_calendarioant
                                      ,ed56_c_situacaoant
                       )
                values (
                                $this->ed56_i_codigo
                               ,$this->ed56_i_escola
                               ,$this->ed56_i_aluno
                               ,$this->ed56_i_base
                               ,$this->ed56_i_calendario
                               ,'$this->ed56_c_situacao'
                               ,$this->ed56_i_baseant
                               ,$this->ed56_i_calendarioant
                               ,'$this->ed56_c_situacaoant'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cursos do Aluno na escola ($this->ed56_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cursos do Aluno na escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cursos do Aluno na escola ($this->ed56_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed56_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed56_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008395,'$this->ed56_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010067,1008395,'','".AddSlashes(pg_result($resaco,0,'ed56_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010067,1008396,'','".AddSlashes(pg_result($resaco,0,'ed56_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010067,1008397,'','".AddSlashes(pg_result($resaco,0,'ed56_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010067,1008398,'','".AddSlashes(pg_result($resaco,0,'ed56_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010067,1008399,'','".AddSlashes(pg_result($resaco,0,'ed56_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010067,1008400,'','".AddSlashes(pg_result($resaco,0,'ed56_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010067,1008814,'','".AddSlashes(pg_result($resaco,0,'ed56_i_baseant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010067,1008815,'','".AddSlashes(pg_result($resaco,0,'ed56_i_calendarioant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010067,1008816,'','".AddSlashes(pg_result($resaco,0,'ed56_c_situacaoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($ed56_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update alunocurso set ";
     $virgula = "";
     if(trim($this->ed56_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_codigo"])){
       $sql  .= $virgula." ed56_i_codigo = $this->ed56_i_codigo ";
       $virgula = ",";
       if(trim($this->ed56_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed56_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed56_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_escola"])){
       $sql  .= $virgula." ed56_i_escola = $this->ed56_i_escola ";
       $virgula = ",";
       if(trim($this->ed56_i_escola) == null ){
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed56_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed56_i_aluno)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_aluno"])){
       $sql  .= $virgula." ed56_i_aluno = $this->ed56_i_aluno ";
       $virgula = ",";
       if(trim($this->ed56_i_aluno) == null ){
         $this->erro_sql = " Campo Aluno nao Informado.";
         $this->erro_campo = "ed56_i_aluno";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed56_i_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_base"])){
       $sql  .= $virgula." ed56_i_base = $this->ed56_i_base ";
       $virgula = ",";
       if(trim($this->ed56_i_base) == null ){
         $this->erro_sql = " Campo Base Curricular nao Informado.";
         $this->erro_campo = "ed56_i_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed56_i_calendario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_calendario"])){
       $sql  .= $virgula." ed56_i_calendario = $this->ed56_i_calendario ";
       $virgula = ",";
       if(trim($this->ed56_i_calendario) == null ){
         $this->erro_sql = " Campo Calendário nao Informado.";
         $this->erro_campo = "ed56_i_calendario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed56_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_c_situacao"])){
       $sql  .= $virgula." ed56_c_situacao = '$this->ed56_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed56_c_situacao) == null ){
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "ed56_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed56_i_baseant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_baseant"])){
        if(trim($this->ed56_i_baseant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_baseant"])){
           $this->ed56_i_baseant = "null" ;
        }
       $sql  .= $virgula." ed56_i_baseant = $this->ed56_i_baseant ";
       $virgula = ",";
     }
     if(trim($this->ed56_i_calendarioant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_calendarioant"])){
        if(trim($this->ed56_i_calendarioant)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_calendarioant"])){
           $this->ed56_i_calendarioant = "null" ;
        }
       $sql  .= $virgula." ed56_i_calendarioant = $this->ed56_i_calendarioant ";
       $virgula = ",";
     }
     if(trim($this->ed56_c_situacaoant)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed56_c_situacaoant"])){
       $sql  .= $virgula." ed56_c_situacaoant = '$this->ed56_c_situacaoant' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($ed56_i_codigo!=null){
       $sql .= " ed56_i_codigo = $this->ed56_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed56_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008395,'$this->ed56_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008395,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_i_codigo'))."','$this->ed56_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008396,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_i_escola'))."','$this->ed56_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_aluno"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008397,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_i_aluno'))."','$this->ed56_i_aluno',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_base"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008398,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_i_base'))."','$this->ed56_i_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_calendario"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008399,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_i_calendario'))."','$this->ed56_i_calendario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_c_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008400,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_c_situacao'))."','$this->ed56_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_baseant"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008814,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_i_baseant'))."','$this->ed56_i_baseant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_i_calendarioant"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008815,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_i_calendarioant'))."','$this->ed56_i_calendarioant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed56_c_situacaoant"]))
           $resac = db_query("insert into db_acount values($acount,1010067,1008816,'".AddSlashes(pg_result($resaco,$conresaco,'ed56_c_situacaoant'))."','$this->ed56_c_situacaoant',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cursos do Aluno na escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed56_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cursos do Aluno na escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($ed56_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed56_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008395,'$ed56_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010067,1008395,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010067,1008396,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010067,1008397,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_i_aluno'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010067,1008398,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010067,1008399,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_i_calendario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010067,1008400,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010067,1008814,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_i_baseant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010067,1008815,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_i_calendarioant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010067,1008816,'','".AddSlashes(pg_result($resaco,$iresaco,'ed56_c_situacaoant'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from alunocurso
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed56_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed56_i_codigo = $ed56_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cursos do Aluno na escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed56_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cursos do Aluno na escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed56_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed56_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:alunocurso";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed56_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from alunocurso ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = alunocurso.ed56_i_escola";
     $sql .= "      inner join aluno  on  aluno.ed47_i_codigo = alunocurso.ed56_i_aluno";
     $sql .= "      inner join calendario  on  calendario.ed52_i_codigo = alunocurso.ed56_i_calendario";
     $sql .= "      inner join base  on  base.ed31_i_codigo = alunocurso.ed56_i_base";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql .= "      left join alunopossib  on  alunopossib.ed79_i_alunocurso = alunocurso.ed56_i_codigo";
     $sql .= "      left join serie  on  serie.ed11_i_codigo = alunopossib.ed79_i_serie";
     $sql .= "      left join turno  on  turno.ed15_i_codigo = alunopossib.ed79_i_turno";
     $sql2 = "";
     if($dbwhere==""){
       if($ed56_i_codigo!=null ){
         $sql2 .= " where alunocurso.ed56_i_codigo = $ed56_i_codigo ";
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
   function sql_query_file ( $ed56_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from alunocurso ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed56_i_codigo!=null ){
         $sql2 .= " where alunocurso.ed56_i_codigo = $ed56_i_codigo ";
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
  
  function sql_query_alunotransf($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') { 

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
    $sSql .= " from alunocurso ";
    $sSql .= "      inner join alunopossib on ed79_i_alunocurso = ed56_i_codigo ";
    $sSql .= "      inner join serie on ed11_i_codigo = ed79_i_serie ";
    $sSql .= "      inner join escola on ed18_i_codigo = ed56_i_escola ";    
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where alunocurso.ed56_i_codigo = $iCodigo "; 
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
    $sSql .= " from alunocurso ";
    $sSql .= "      inner join aluno on ed47_i_codigo = ed56_i_aluno ";
    $sSql .= "      inner join base on ed31_i_codigo = ed56_i_base ";
    $sSql .= "      inner join cursoedu on ed29_i_codigo = ed31_i_curso ";
    $sSql2 = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where alunocurso.ed56_i_codigo = $iCodigo "; 
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
  
}
?>