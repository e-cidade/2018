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
//MODULO: escola
//CLASSE DA ENTIDADE rechumanoativ
class cl_rechumanoativ {
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
   var $ed22_i_codigo = 0;
   var $ed22_i_rechumanoescola = 0;
   var $ed22_i_atividade = 0;
   var $ed22_i_horasmanha = 0;
   var $ed22_i_horastarde = 0;
   var $ed22_i_horasnoite = 0;
   var $ed22_i_atolegal = 0;
   var $ed22_ativo = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 ed22_i_codigo = int8 = Código
                 ed22_i_rechumanoescola = int8 = Matrícula
                 ed22_i_atividade = int8 = Atividade
                 ed22_i_horasmanha = int4 = Horas Manhã
                 ed22_i_horastarde = int4 = Horas Tarde
                 ed22_i_horasnoite = int4 = Horas Noite
                 ed22_i_atolegal = int4 = Ato Legal
                 ed22_ativo = bool = Ativo
                 ";
   //funcao construtor da classe
   function cl_rechumanoativ() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("rechumanoativ");
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
       $this->ed22_i_codigo = ($this->ed22_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed22_i_codigo"]:$this->ed22_i_codigo);
       $this->ed22_i_rechumanoescola = ($this->ed22_i_rechumanoescola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed22_i_rechumanoescola"]:$this->ed22_i_rechumanoescola);
       $this->ed22_i_atividade = ($this->ed22_i_atividade == ""?@$GLOBALS["HTTP_POST_VARS"]["ed22_i_atividade"]:$this->ed22_i_atividade);
       $this->ed22_i_horasmanha = ($this->ed22_i_horasmanha == ""?@$GLOBALS["HTTP_POST_VARS"]["ed22_i_horasmanha"]:$this->ed22_i_horasmanha);
       $this->ed22_i_horastarde = ($this->ed22_i_horastarde == ""?@$GLOBALS["HTTP_POST_VARS"]["ed22_i_horastarde"]:$this->ed22_i_horastarde);
       $this->ed22_i_horasnoite = ($this->ed22_i_horasnoite == ""?@$GLOBALS["HTTP_POST_VARS"]["ed22_i_horasnoite"]:$this->ed22_i_horasnoite);
       $this->ed22_i_atolegal = ($this->ed22_i_atolegal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed22_i_atolegal"]:$this->ed22_i_atolegal);
       $this->ed22_ativo = ($this->ed22_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed22_ativo"]:$this->ed22_ativo);
     }else{
       $this->ed22_i_codigo = ($this->ed22_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed22_i_codigo"]:$this->ed22_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed22_i_codigo){
      $this->atualizacampos();
     if($this->ed22_i_rechumanoescola == null ){
       $this->erro_sql = " Campo Matrícula não informado.";
       $this->erro_campo = "ed22_i_rechumanoescola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed22_i_atividade == null ){
       $this->erro_sql = " Campo Atividade não informado.";
       $this->erro_campo = "ed22_i_atividade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed22_i_horasmanha == null ){
       $this->ed22_i_horasmanha = "0";
     }
     if($this->ed22_i_horastarde == null ){
       $this->ed22_i_horastarde = "0";
     }
     if($this->ed22_i_horasnoite == null ){
       $this->ed22_i_horasnoite = "0";
     }
     if($this->ed22_i_atolegal == null ){
       $this->ed22_i_atolegal = "0";
     }
     if($this->ed22_ativo == null ){
       $this->erro_sql = " Campo Ativo não informado.";
       $this->erro_campo = "ed22_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed22_i_codigo == "" || $ed22_i_codigo == null ){
       $result = db_query("select nextval('rechumanoativ_ed22_i_codigo_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: rechumanoativ_ed22_i_codigo_seq do campo: ed22_i_codigo";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->ed22_i_codigo = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from rechumanoativ_ed22_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed22_i_codigo)){
         $this->erro_sql = " Campo ed22_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed22_i_codigo = $ed22_i_codigo;
       }
     }
     if(($this->ed22_i_codigo == null) || ($this->ed22_i_codigo == "") ){
       $this->erro_sql = " Campo ed22_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into rechumanoativ(
                                       ed22_i_codigo
                                      ,ed22_i_rechumanoescola
                                      ,ed22_i_atividade
                                      ,ed22_i_horasmanha
                                      ,ed22_i_horastarde
                                      ,ed22_i_horasnoite
                                      ,ed22_i_atolegal
                                      ,ed22_ativo
                       )
                values (
                                $this->ed22_i_codigo
                               ,$this->ed22_i_rechumanoescola
                               ,$this->ed22_i_atividade
                               ,$this->ed22_i_horasmanha
                               ,$this->ed22_i_horastarde
                               ,$this->ed22_i_horasnoite
                               ,$this->ed22_i_atolegal
                               ,'$this->ed22_ativo'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Atividades do Recurso Humano na escola ($this->ed22_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Atividades do Recurso Humano na escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Atividades do Recurso Humano na escola ($this->ed22_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed22_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed22_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008543,'$this->ed22_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,1010096,1008543,'','".AddSlashes(pg_result($resaco,0,'ed22_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010096,1008544,'','".AddSlashes(pg_result($resaco,0,'ed22_i_rechumanoescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010096,1008545,'','".AddSlashes(pg_result($resaco,0,'ed22_i_atividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010096,1008546,'','".AddSlashes(pg_result($resaco,0,'ed22_i_horasmanha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010096,1008547,'','".AddSlashes(pg_result($resaco,0,'ed22_i_horastarde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010096,1008548,'','".AddSlashes(pg_result($resaco,0,'ed22_i_horasnoite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010096,14573,'','".AddSlashes(pg_result($resaco,0,'ed22_i_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010096,21043,'','".AddSlashes(pg_result($resaco,0,'ed22_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($ed22_i_codigo=null) {
      $this->atualizacampos();
     $sql = " update rechumanoativ set ";
     $virgula = "";
     if(trim($this->ed22_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_codigo"])){
       $sql  .= $virgula." ed22_i_codigo = $this->ed22_i_codigo ";
       $virgula = ",";
       if(trim($this->ed22_i_codigo) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "ed22_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed22_i_rechumanoescola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_rechumanoescola"])){
       $sql  .= $virgula." ed22_i_rechumanoescola = $this->ed22_i_rechumanoescola ";
       $virgula = ",";
       if(trim($this->ed22_i_rechumanoescola) == null ){
         $this->erro_sql = " Campo Matrícula não informado.";
         $this->erro_campo = "ed22_i_rechumanoescola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed22_i_atividade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_atividade"])){
       $sql  .= $virgula." ed22_i_atividade = $this->ed22_i_atividade ";
       $virgula = ",";
       if(trim($this->ed22_i_atividade) == null ){
         $this->erro_sql = " Campo Atividade não informado.";
         $this->erro_campo = "ed22_i_atividade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed22_i_horasmanha)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horasmanha"])){
        if(trim($this->ed22_i_horasmanha)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horasmanha"])){
           $this->ed22_i_horasmanha = "0" ;
        }
       $sql  .= $virgula." ed22_i_horasmanha = $this->ed22_i_horasmanha ";
       $virgula = ",";
     }
     if(trim($this->ed22_i_horastarde)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horastarde"])){
        if(trim($this->ed22_i_horastarde)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horastarde"])){
           $this->ed22_i_horastarde = "0" ;
        }
       $sql  .= $virgula." ed22_i_horastarde = $this->ed22_i_horastarde ";
       $virgula = ",";
     }
     if(trim($this->ed22_i_horasnoite)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horasnoite"])){
        if(trim($this->ed22_i_horasnoite)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horasnoite"])){
           $this->ed22_i_horasnoite = "0" ;
        }
       $sql  .= $virgula." ed22_i_horasnoite = $this->ed22_i_horasnoite ";
       $virgula = ",";
     }
     if(trim($this->ed22_i_atolegal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_atolegal"])){
        if(trim($this->ed22_i_atolegal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_atolegal"])){
           $this->ed22_i_atolegal = "0" ;
        }
       $sql  .= $virgula." ed22_i_atolegal = $this->ed22_i_atolegal ";
       $virgula = ",";
     }
     if(trim($this->ed22_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed22_ativo"])){
       $sql  .= $virgula." ed22_ativo = '$this->ed22_ativo' ";
       $virgula = ",";
       if(trim($this->ed22_ativo) == null ){
         $this->erro_sql = " Campo Ativo não informado.";
         $this->erro_campo = "ed22_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed22_i_codigo!=null){
       $sql .= " ed22_i_codigo = $this->ed22_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->ed22_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,1008543,'$this->ed22_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_codigo"]) || $this->ed22_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,1010096,1008543,'".AddSlashes(pg_result($resaco,$conresaco,'ed22_i_codigo'))."','$this->ed22_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_rechumanoescola"]) || $this->ed22_i_rechumanoescola != "")
             $resac = db_query("insert into db_acount values($acount,1010096,1008544,'".AddSlashes(pg_result($resaco,$conresaco,'ed22_i_rechumanoescola'))."','$this->ed22_i_rechumanoescola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_atividade"]) || $this->ed22_i_atividade != "")
             $resac = db_query("insert into db_acount values($acount,1010096,1008545,'".AddSlashes(pg_result($resaco,$conresaco,'ed22_i_atividade'))."','$this->ed22_i_atividade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horasmanha"]) || $this->ed22_i_horasmanha != "")
             $resac = db_query("insert into db_acount values($acount,1010096,1008546,'".AddSlashes(pg_result($resaco,$conresaco,'ed22_i_horasmanha'))."','$this->ed22_i_horasmanha',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horastarde"]) || $this->ed22_i_horastarde != "")
             $resac = db_query("insert into db_acount values($acount,1010096,1008547,'".AddSlashes(pg_result($resaco,$conresaco,'ed22_i_horastarde'))."','$this->ed22_i_horastarde',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_horasnoite"]) || $this->ed22_i_horasnoite != "")
             $resac = db_query("insert into db_acount values($acount,1010096,1008548,'".AddSlashes(pg_result($resaco,$conresaco,'ed22_i_horasnoite'))."','$this->ed22_i_horasnoite',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed22_i_atolegal"]) || $this->ed22_i_atolegal != "")
             $resac = db_query("insert into db_acount values($acount,1010096,14573,'".AddSlashes(pg_result($resaco,$conresaco,'ed22_i_atolegal'))."','$this->ed22_i_atolegal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["ed22_ativo"]) || $this->ed22_ativo != "")
             $resac = db_query("insert into db_acount values($acount,1010096,21043,'".AddSlashes(pg_result($resaco,$conresaco,'ed22_ativo'))."','$this->ed22_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividades do Recurso Humano na escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed22_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Atividades do Recurso Humano na escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed22_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed22_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($ed22_i_codigo=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($ed22_i_codigo));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,1008543,'$ed22_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,1010096,1008543,'','".AddSlashes(pg_result($resaco,$iresaco,'ed22_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010096,1008544,'','".AddSlashes(pg_result($resaco,$iresaco,'ed22_i_rechumanoescola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010096,1008545,'','".AddSlashes(pg_result($resaco,$iresaco,'ed22_i_atividade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010096,1008546,'','".AddSlashes(pg_result($resaco,$iresaco,'ed22_i_horasmanha'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010096,1008547,'','".AddSlashes(pg_result($resaco,$iresaco,'ed22_i_horastarde'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010096,1008548,'','".AddSlashes(pg_result($resaco,$iresaco,'ed22_i_horasnoite'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010096,14573,'','".AddSlashes(pg_result($resaco,$iresaco,'ed22_i_atolegal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,1010096,21043,'','".AddSlashes(pg_result($resaco,$iresaco,'ed22_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from rechumanoativ
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($ed22_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " ed22_i_codigo = $ed22_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Atividades do Recurso Humano na escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed22_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Atividades do Recurso Humano na escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed22_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed22_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao do recordset
   public function sql_record($sql) {
     $result = db_query($sql);
     if (!$result) {
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:rechumanoativ";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($ed22_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from rechumanoativ ";
     $sql .= "      left  join atolegal  on  atolegal.ed05_i_codigo = rechumanoativ.ed22_i_atolegal";
     $sql .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_codigo = rechumanoativ.ed22_i_rechumanoescola";
     $sql .= "      inner join atividaderh  on  atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = rechumanoescola.ed75_i_escola";
     $sql .= "      inner join rechumano  on  rechumano.ed20_i_codigo = rechumanoescola.ed75_i_rechumano";
     $sql .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal";
     $sql .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm";
     $sql .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo";
     $sql .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed22_i_codigo)) {
         $sql2 .= " where rechumanoativ.ed22_i_codigo = $ed22_i_codigo ";
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
   // funcao do sql
   public function sql_query_file ($ed22_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from rechumanoativ ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed22_i_codigo)){
         $sql2 .= " where rechumanoativ.ed22_i_codigo = $ed22_i_codigo ";
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
    $sSql  .= " from rechumanoativ ";
    $sSql  .= "      left  join atolegal  on  atolegal.ed05_i_codigo = rechumanoativ.ed22_i_atolegal ";
    $sSql  .= "      left  join tipoato  on  tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato ";
    $sSql  .= "      inner join rechumanoescola  on  rechumanoescola.ed75_i_codigo = rechumanoativ.ed22_i_rechumanoescola ";
    $sSql  .= "      inner join atividaderh  on  atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade ";
    $sSql  .= "      inner join escola  on  escola.ed18_i_codigo = rechumanoescola.ed75_i_escola ";
    $sSql  .= "      inner join rechumano  on  rechumano.ed20_i_codigo = rechumanoescola.ed75_i_rechumano ";
    $sSql  .= "      left join rechumanopessoal  on  rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "      left join rhpessoal  on  rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal ";
    $sSql  .= "      left join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm ";
    $sSql  .= "      left join rechumanocgm  on  rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "      left join cgm as cgmcgm on  cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm ";
    $sSql2  = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where rechumanoativ.ed22_i_codigo = $iCodigo ";
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

   function sql_query_resultadofinal($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '') {

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

    $iInst    = db_getsession("DB_instit");
    $iAno   = db_anofolha();
    $iMes   = db_mesfolha();

    $sSql  .= " FROM rechumanoativ ";
    $sSql  .= "   INNER JOIN rechumanoescola ON rechumanoescola.ed75_i_codigo = rechumanoativ.ed22_i_rechumanoescola ";
    $sSql  .= "   INNER JOIN rechumano ON rechumano.ed20_i_codigo = rechumanoescola.ed75_i_rechumano ";
    $sSql  .= "    ";
    $sSql  .= "   LEFT JOIN atividaderh ON atividaderh.ed01_i_codigo = rechumanoativ.ed22_i_atividade ";
    $sSql  .= "   LEFT JOIN escoladiretor ON escoladiretor.ed254_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "   LEFT JOIN turno ON turno.ed15_i_codigo = escoladiretor.ed254_i_turno ";
    $sSql  .= "   LEFT JOIN atolegal ON atolegal.ed05_i_codigo = rechumanoativ.ed22_i_atolegal ";
    $sSql  .= "   LEFT JOIN tipoato ON tipoato.ed83_i_codigo = atolegal.ed05_i_tipoato ";
    $sSql  .= "    ";
    $sSql  .= "   LEFT JOIN rechumanopessoal ON rechumanopessoal.ed284_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "   LEFT JOIN rhpessoal ON rhpessoal.rh01_regist = rechumanopessoal.ed284_i_rhpessoal ";
    $sSql  .= "   LEFT JOIN cgm AS cgmrh ON cgmrh.z01_numcgm = rhpessoal.rh01_numcgm ";
    $sSql  .= "    ";
    $sSql  .= "   LEFT JOIN rhpessoalmov ON rh02_anousu = ".$iAno." ";
    $sSql  .= "                          AND rh02_mesusu = ".$iMes." ";
    $sSql  .= "                          AND rh02_regist = rh01_regist ";
    $sSql  .= "                          AND rh02_instit = ".$iInst." ";
    $sSql  .= "   LEFT JOIN rhfuncao ON rhfuncao.rh37_funcao = rhpessoal.rh01_funcao ";
    $sSql  .= "                      AND rh37_instit  = rh02_instit ";
    $sSql  .= "   LEFT JOIN rechumanocgm ON rechumanocgm.ed285_i_rechumano = rechumano.ed20_i_codigo ";
    $sSql  .= "   LEFT JOIN cgm AS cgmcgm ON cgmcgm.z01_numcgm = rechumanocgm.ed285_i_cgm ";
    $sSql2  = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sSql2 .= " where rechumanoativ.ed22_i_codigo = $iCodigo ";
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

   // funcao do sql
   public function sql_query_tipohoratrabalho ($ed22_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from rechumanoativ ";
     $sql .= "      inner join agendaatividade  on  agendaatividade.ed129_rechumanoativ = rechumanoativ.ed22_i_codigo";
     $sql .= "      inner join tipohoratrabalho on  tipohoratrabalho.ed128_codigo       = agendaatividade.ed129_tipohoratrabalho";
     $sql .= "      inner join atividaderh      on  atividaderh.ed01_i_codigo           = rechumanoativ.ed22_i_atividade";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed22_i_codigo)) {
         $sql2 .= " where rechumanoativ.ed22_i_codigo = $ed22_i_codigo ";
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

  public function sql_query_horarios_regencia($ed22_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";

     $sql .= "  from rechumanoativ ";
     $sql .= " inner join atividaderh       on ed01_i_codigo         = ed22_i_atividade ";
     $sql .= " inner join agendaatividade   on ed129_rechumanoativ   = ed22_i_codigo ";
     $sql .= " inner join tipohoratrabalho  on ed128_codigo          = ed129_tipohoratrabalho ";
     $sql .= " inner join diasemana         on ed32_i_codigo         = ed129_diasemana ";
     $sql .= " inner join rechumanohoradisp on ed33_rechumanoescola  = ed22_i_rechumanoescola ";
     $sql .= "                             and ed33_i_diasemana      = ed129_diasemana ";
     $sql .= "                             and ed33_tipohoratrabalho = ed129_tipohoratrabalho ";
     $sql .= " inner join periodoescola     on ed17_i_codigo = ed33_i_periodo ";
     $sql .= " inner join periodoaula       on ed08_i_codigo = ed17_i_periodoaula ";
     $sql .= " inner join turno             on ed15_i_codigo = ed17_i_turno ";
     $sql .= " inner join turnoreferente    on ed231_i_turno = ed15_i_codigo ";


     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed22_i_codigo)) {
         $sql2 .= " where rechumanoativ.ed22_i_codigo = $ed22_i_codigo ";
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

  /**
   * Busca todas as atividades e agendas do profissional
   */
  public function sql_query_agenda_atividade ($ed22_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from rechumanoativ  ";
     $sql .= " inner join atividaderh       on ed01_i_codigo        = ed22_i_atividade ";
     $sql .= " inner join rechumanoescola   on ed75_i_codigo        = ed22_i_rechumanoescola ";
     $sql .= " inner join agendaatividade   on ed129_rechumanoativ  = ed22_i_codigo ";
     $sql .= " inner join tipohoratrabalho  on ed128_codigo         = ed129_tipohoratrabalho ";
     $sql .= " inner join diasemana         on ed32_i_codigo        = ed129_diasemana ";

     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($ed22_i_codigo)) {
         $sql2 .= " where rechumanoativ.ed22_i_codigo = $ed22_i_codigo ";
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

  // funcao do sql
  public function sql_query_funcao_profissional ($ed22_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") {

    $sql  = "select {$campos}";
    $sql .= "  from rechumanoativ ";
    $sql .= "      inner join atividaderh      on  atividaderh.ed01_i_codigo           = rechumanoativ.ed22_i_atividade";
    $sql2 = "";
    if (empty($dbwhere)) {
      if (!empty($ed22_i_codigo)) {
        $sql2 .= " where rechumanoativ.ed22_i_codigo = $ed22_i_codigo ";
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
