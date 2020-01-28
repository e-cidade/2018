<?php
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

//MODULO: Laboratório
//CLASSE DA ENTIDADE lab_laboratorio
class cl_lab_laboratorio {
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
   var $la02_i_codigo = 0;
   var $la02_i_tipo = 0;
   var $la02_c_descr = null;
   var $la02_i_alvara = 0;
   var $la02_i_cnes = 0;
   var $la02_c_endereco = null;
   var $la02_i_telefone = 0;
   var $la02_c_numero = null;
   var $la02_i_turnoatend = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 la02_i_codigo = int4 = Código
                 la02_i_tipo = int4 = Tipo
                 la02_c_descr = char(50) = Descrição
                 la02_i_alvara = int4 = Alvará
                 la02_i_cnes = int4 = CNES
                 la02_c_endereco = char(50) = Endereço
                 la02_i_telefone = int4 = Telefone
                 la02_c_numero = char(20) = Número
                 la02_i_turnoatend = int4 = Turno atendimento
                 ";
   //funcao construtor da classe
   function cl_lab_laboratorio() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("lab_laboratorio");
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
       $this->la02_i_codigo = ($this->la02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_i_codigo"]:$this->la02_i_codigo);
       $this->la02_i_tipo = ($this->la02_i_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_i_tipo"]:$this->la02_i_tipo);
       $this->la02_c_descr = ($this->la02_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_c_descr"]:$this->la02_c_descr);
       $this->la02_i_alvara = ($this->la02_i_alvara == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_i_alvara"]:$this->la02_i_alvara);
       $this->la02_i_cnes = ($this->la02_i_cnes == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_i_cnes"]:$this->la02_i_cnes);
       $this->la02_c_endereco = ($this->la02_c_endereco == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_c_endereco"]:$this->la02_c_endereco);
       $this->la02_i_telefone = ($this->la02_i_telefone == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_i_telefone"]:$this->la02_i_telefone);
       $this->la02_c_numero = ($this->la02_c_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_c_numero"]:$this->la02_c_numero);
       $this->la02_i_turnoatend = ($this->la02_i_turnoatend == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_i_turnoatend"]:$this->la02_i_turnoatend);
     }else{
       $this->la02_i_codigo = ($this->la02_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["la02_i_codigo"]:$this->la02_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($la02_i_codigo){
      $this->atualizacampos();
     if($this->la02_i_tipo == null ){
       $this->erro_sql = " Campo Tipo nao Informado.";
       $this->erro_campo = "la02_i_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la02_c_descr == null ){
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "la02_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la02_i_alvara == null ){
       $this->erro_sql = " Campo Alvará nao Informado.";
       $this->erro_campo = "la02_i_alvara";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la02_i_cnes == null ){
       $this->erro_sql = " Campo CNES nao Informado.";
       $this->erro_campo = "la02_i_cnes";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la02_c_endereco == null ){
       $this->erro_sql = " Campo Endereço nao Informado.";
       $this->erro_campo = "la02_c_endereco";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la02_i_telefone == null ){
       $this->erro_sql = " Campo Telefone nao Informado.";
       $this->erro_campo = "la02_i_telefone";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la02_c_numero == null ){
       $this->erro_sql = " Campo Número nao Informado.";
       $this->erro_campo = "la02_c_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->la02_i_turnoatend == null ){
       $this->la02_i_turnoatend = "null";
     }
     if($la02_i_codigo == "" || $la02_i_codigo == null ){
       $result = db_query("select nextval('lab_laboratorio_la02_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: lab_laboratorio_la02_i_codigo_seq do campo: la02_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->la02_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from lab_laboratorio_la02_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $la02_i_codigo)){
         $this->erro_sql = " Campo la02_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->la02_i_codigo = $la02_i_codigo;
       }
     }
     if(($this->la02_i_codigo == null) || ($this->la02_i_codigo == "") ){
       $this->erro_sql = " Campo la02_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into lab_laboratorio(
                                       la02_i_codigo
                                      ,la02_i_tipo
                                      ,la02_c_descr
                                      ,la02_i_alvara
                                      ,la02_i_cnes
                                      ,la02_c_endereco
                                      ,la02_i_telefone
                                      ,la02_c_numero
                                      ,la02_i_turnoatend
                       )
                values (
                                $this->la02_i_codigo
                               ,$this->la02_i_tipo
                               ,'$this->la02_c_descr'
                               ,$this->la02_i_alvara
                               ,$this->la02_i_cnes
                               ,'$this->la02_c_endereco'
                               ,$this->la02_i_telefone
                               ,'$this->la02_c_numero'
                               ,$this->la02_i_turnoatend
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "lab_laboratorio ($this->la02_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "lab_laboratorio já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "lab_laboratorio ($this->la02_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la02_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->la02_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,15704,'$this->la02_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2753,15704,'','".AddSlashes(pg_result($resaco,0,'la02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2753,15705,'','".AddSlashes(pg_result($resaco,0,'la02_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2753,15706,'','".AddSlashes(pg_result($resaco,0,'la02_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2753,15707,'','".AddSlashes(pg_result($resaco,0,'la02_i_alvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2753,15708,'','".AddSlashes(pg_result($resaco,0,'la02_i_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2753,15709,'','".AddSlashes(pg_result($resaco,0,'la02_c_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2753,15710,'','".AddSlashes(pg_result($resaco,0,'la02_i_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2753,15711,'','".AddSlashes(pg_result($resaco,0,'la02_c_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2753,16031,'','".AddSlashes(pg_result($resaco,0,'la02_i_turnoatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   }
   // funcao para alteracao
   function alterar ($la02_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update lab_laboratorio set ";
     $virgula = "";
     if(trim($this->la02_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_i_codigo"])){
       $sql  .= $virgula." la02_i_codigo = $this->la02_i_codigo ";
       $virgula = ",";
       if(trim($this->la02_i_codigo) == null ){
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "la02_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la02_i_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_i_tipo"])){
       $sql  .= $virgula." la02_i_tipo = $this->la02_i_tipo ";
       $virgula = ",";
       if(trim($this->la02_i_tipo) == null ){
         $this->erro_sql = " Campo Tipo nao Informado.";
         $this->erro_campo = "la02_i_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la02_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_c_descr"])){
       $sql  .= $virgula." la02_c_descr = '$this->la02_c_descr' ";
       $virgula = ",";
       if(trim($this->la02_c_descr) == null ){
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "la02_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la02_i_alvara)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_i_alvara"])){
       $sql  .= $virgula." la02_i_alvara = $this->la02_i_alvara ";
       $virgula = ",";
       if(trim($this->la02_i_alvara) == null ){
         $this->erro_sql = " Campo Alvará nao Informado.";
         $this->erro_campo = "la02_i_alvara";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la02_i_cnes)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_i_cnes"])){
       $sql  .= $virgula." la02_i_cnes = $this->la02_i_cnes ";
       $virgula = ",";
       if(trim($this->la02_i_cnes) == null ){
         $this->erro_sql = " Campo CNES nao Informado.";
         $this->erro_campo = "la02_i_cnes";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la02_c_endereco)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_c_endereco"])){
       $sql  .= $virgula." la02_c_endereco = '$this->la02_c_endereco' ";
       $virgula = ",";
       if(trim($this->la02_c_endereco) == null ){
         $this->erro_sql = " Campo Endereço nao Informado.";
         $this->erro_campo = "la02_c_endereco";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la02_i_telefone)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_i_telefone"])){
       $sql  .= $virgula." la02_i_telefone = $this->la02_i_telefone ";
       $virgula = ",";
       if(trim($this->la02_i_telefone) == null ){
         $this->erro_sql = " Campo Telefone nao Informado.";
         $this->erro_campo = "la02_i_telefone";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la02_c_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_c_numero"])){
       $sql  .= $virgula." la02_c_numero = '$this->la02_c_numero' ";
       $virgula = ",";
       if(trim($this->la02_c_numero) == null ){
         $this->erro_sql = " Campo Número nao Informado.";
         $this->erro_campo = "la02_c_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->la02_i_turnoatend)!="" || isset($GLOBALS["HTTP_POST_VARS"]["la02_i_turnoatend"])){
        if(trim($this->la02_i_turnoatend)=="" && isset($GLOBALS["HTTP_POST_VARS"]["la02_i_turnoatend"])){
           $this->la02_i_turnoatend = "0" ;
        }
       $sql  .= $virgula." la02_i_turnoatend = $this->la02_i_turnoatend ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($la02_i_codigo!=null){
       $sql .= " la02_i_codigo = $this->la02_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->la02_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15704,'$this->la02_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_i_codigo"]) || $this->la02_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2753,15704,'".AddSlashes(pg_result($resaco,$conresaco,'la02_i_codigo'))."','$this->la02_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_i_tipo"]) || $this->la02_i_tipo != "")
           $resac = db_query("insert into db_acount values($acount,2753,15705,'".AddSlashes(pg_result($resaco,$conresaco,'la02_i_tipo'))."','$this->la02_i_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_c_descr"]) || $this->la02_c_descr != "")
           $resac = db_query("insert into db_acount values($acount,2753,15706,'".AddSlashes(pg_result($resaco,$conresaco,'la02_c_descr'))."','$this->la02_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_i_alvara"]) || $this->la02_i_alvara != "")
           $resac = db_query("insert into db_acount values($acount,2753,15707,'".AddSlashes(pg_result($resaco,$conresaco,'la02_i_alvara'))."','$this->la02_i_alvara',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_i_cnes"]) || $this->la02_i_cnes != "")
           $resac = db_query("insert into db_acount values($acount,2753,15708,'".AddSlashes(pg_result($resaco,$conresaco,'la02_i_cnes'))."','$this->la02_i_cnes',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_c_endereco"]) || $this->la02_c_endereco != "")
           $resac = db_query("insert into db_acount values($acount,2753,15709,'".AddSlashes(pg_result($resaco,$conresaco,'la02_c_endereco'))."','$this->la02_c_endereco',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_i_telefone"]) || $this->la02_i_telefone != "")
           $resac = db_query("insert into db_acount values($acount,2753,15710,'".AddSlashes(pg_result($resaco,$conresaco,'la02_i_telefone'))."','$this->la02_i_telefone',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_c_numero"]) || $this->la02_c_numero != "")
           $resac = db_query("insert into db_acount values($acount,2753,15711,'".AddSlashes(pg_result($resaco,$conresaco,'la02_c_numero'))."','$this->la02_c_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["la02_i_turnoatend"]) || $this->la02_i_turnoatend != "")
           $resac = db_query("insert into db_acount values($acount,2753,16031,'".AddSlashes(pg_result($resaco,$conresaco,'la02_i_turnoatend'))."','$this->la02_i_turnoatend',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_laboratorio nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->la02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_laboratorio nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->la02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->la02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   function excluir ($la02_i_codigo=null,$dbwhere=null) {
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($la02_i_codigo));
     }else{
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,15704,'$la02_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2753,15704,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2753,15705,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_i_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2753,15706,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2753,15707,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_i_alvara'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2753,15708,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_i_cnes'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2753,15709,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_c_endereco'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2753,15710,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_i_telefone'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2753,15711,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_c_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2753,16031,'','".AddSlashes(pg_result($resaco,$iresaco,'la02_i_turnoatend'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from lab_laboratorio
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($la02_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " la02_i_codigo = $la02_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "lab_laboratorio nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$la02_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "lab_laboratorio nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$la02_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$la02_i_codigo;
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
       $this->erro_sql   = "Record Vazio na Tabela:lab_laboratorio";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     return $result;
   }
   // funcao do sql
   function sql_query ( $la02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_laboratorio ";
     $sql .= "      left  join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = lab_laboratorio.la02_i_turnoatend";
     $sql2 = "";
     if($dbwhere==""){
       if($la02_i_codigo!=null ){
         $sql2 .= " where lab_laboratorio.la02_i_codigo = $la02_i_codigo ";
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
   function sql_query_file ( $la02_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from lab_laboratorio ";
     $sql2 = "";
     if($dbwhere==""){
       if($la02_i_codigo!=null ){
         $sql2 .= " where lab_laboratorio.la02_i_codigo = $la02_i_codigo ";
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

  /*
   * Função sql que seleciona os laboratórios e os exames.
   *
   * @author Adriano Quilião de Oliveira <adriano.oliveira@dbsellser.com.br>
  */
  function sql_query_labexames ($la02_i_codigo = null, $sCampos = "*", $sOrdem = null, $sWhere="") {

  	$sSql = "select ";
    if ($sCampos != "*" ) {

      $aCampossql = split("#", $sCampos);
      $sVirgula   = "";
      for ($i = 0; $i < sizeof($aCampossql); $i++) {

        $sSql    .= $sVirgula.$aCampossql[$i];
        $sVirgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= " from lab_laboratorio ";
    $sSql .= "    inner join lab_labsetor on la02_i_codigo = la24_i_laboratorio";
    $sSql .= "    inner join lab_setorexame on la24_i_codigo = la09_i_labsetor";
    $sSql .= "    inner join lab_exame on la09_i_exame = la08_i_codigo";
    $sSql2 = "";
    if ($sWhere == "") {

      if ($la02_i_codigo != null) {
        $sSql2 .= " where lab_laboratorio.la02_i_codigo = $la02_i_codigo ";
      }

    } elseif ($sWhere != "") {
      $sSql2 = " where $sWhere";
    }
    $sSql .= $sSql2;
    if ($sOrdem != null) {

      $sSql      .= " order by ";
      $aCampossql = split("#", $sOrdem);
      $sVirgula    = "";
      for ($i = 0; $i < sizeof($aCampossql); $i++) {

       	$sSql    .= $sVirgula.$aCampossql[$i];
        $sVirgula = ",";

      }

    }
    return $sSql;

  }

  public function sql_query_laboratorio( $la02_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "" ) {

     $sql  = "select {$campos} ";
     $sql .= "  from laboratorio.lab_laboratorio ";
     $sql .= "       join lab_labdepart on lab_labdepart.la03_i_laboratorio           = lab_laboratorio.la02_i_codigo";
     $sql .= "  left join limiteatendimento on limiteatendimento.la45_lab_laboratorio = lab_laboratorio.la02_i_codigo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($la02_i_codigo)){
         $sql2 .= " where lab_laboratorio.la02_i_codigo = $la02_i_codigo ";
       }
     } else if (!empty($dbwhere)) {
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if (!empty($ordem)) {
       $sql .= " order by {$ordem}";
     }
     return $sql;
  }
}