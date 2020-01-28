<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

//MODULO: meioambiente
//CLASSE DA ENTIDADE empreendimento
class cl_empreendimento {
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
   var $am05_sequencial = 0;
   var $am05_nome = null;
   var $am05_nomefanta = null;
   var $am05_numero = 0;
   var $am05_complemento = null;
   var $am05_cep = null;
   var $am05_bairro = 0;
   var $am05_ruas = 0;
   var $am05_cnpj = null;
   var $am05_cgm = 0;
   var $am05_areatotal = 0;
   var $am05_protprocesso = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am05_sequencial = int4 = Código do Empreendimento
                 am05_nome = varchar(40) = Nome
                 am05_nomefanta = varchar(100) = Nome Fantasia
                 am05_numero = int4 = Número
                 am05_complemento = varchar(100) = Complemento
                 am05_cep = varchar(8) = CEP
                 am05_bairro = int4 = Código Bairro
                 am05_ruas = int4 = Código Logradouro
                 am05_cnpj = varchar(14) = CNPJ
                 am05_cgm = int4 = CGM
                 am05_areatotal = float8 = Área Total
                 am05_protprocesso = int4 = Protocolo
                 ";
   //funcao construtor da classe
   function cl_empreendimento() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("empreendimento");
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
       $this->am05_sequencial = ($this->am05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_sequencial"]:$this->am05_sequencial);
       $this->am05_nome = ($this->am05_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_nome"]:$this->am05_nome);
       $this->am05_nomefanta = ($this->am05_nomefanta == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_nomefanta"]:$this->am05_nomefanta);
       $this->am05_numero = ($this->am05_numero == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_numero"]:$this->am05_numero);
       $this->am05_complemento = ($this->am05_complemento == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_complemento"]:$this->am05_complemento);
       $this->am05_cep = ($this->am05_cep == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_cep"]:$this->am05_cep);
       $this->am05_bairro = ($this->am05_bairro == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_bairro"]:$this->am05_bairro);
       $this->am05_ruas = ($this->am05_ruas == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_ruas"]:$this->am05_ruas);
       $this->am05_cnpj = ($this->am05_cnpj == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_cnpj"]:$this->am05_cnpj);
       $this->am05_cgm = ($this->am05_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_cgm"]:$this->am05_cgm);
       $this->am05_areatotal = ($this->am05_areatotal == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_areatotal"]:$this->am05_areatotal);
       $this->am05_protprocesso = ($this->am05_protprocesso == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_protprocesso"]:$this->am05_protprocesso);
     }else{
       $this->am05_sequencial = ($this->am05_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am05_sequencial"]:$this->am05_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($am05_sequencial=null){
      $this->atualizacampos();
     if($this->am05_numero == null ){
       $this->erro_sql = " Campo Número não informado.";
       $this->erro_campo = "am05_numero";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am05_cep == null ){
       $this->erro_sql = " Campo CEP não informado.";
       $this->erro_campo = "am05_cep";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am05_bairro == null ){
       $this->erro_sql = " Campo Código Bairro não informado.";
       $this->erro_campo = "am05_bairro";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am05_ruas == null ){
       $this->erro_sql = " Campo Código Logradouro não informado.";
       $this->erro_campo = "am05_ruas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am05_cgm == null ){
       $this->erro_sql = " Campo CGM não informado.";
       $this->erro_campo = "am05_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am05_areatotal == null ){
       $this->am05_areatotal = "0";
     }
     if($this->am05_protprocesso == null ){
       $this->erro_sql = " Campo Protocolo não informado.";
       $this->erro_campo = "am05_protprocesso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am05_sequencial == "" || $am05_sequencial == null ){
       $result = db_query("select nextval('empreendimento_am05_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: empreendimento_am05_sequencial_seq do campo: am05_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am05_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from empreendimento_am05_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am05_sequencial)){
         $this->erro_sql = " Campo am05_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am05_sequencial = $am05_sequencial;
       }
     }
     if(($this->am05_sequencial == null) || ($this->am05_sequencial == "") ){
       $this->erro_sql = " Campo am05_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into empreendimento(
                                       am05_sequencial
                                      ,am05_nome
                                      ,am05_nomefanta
                                      ,am05_numero
                                      ,am05_complemento
                                      ,am05_cep
                                      ,am05_bairro
                                      ,am05_ruas
                                      ,am05_cnpj
                                      ,am05_cgm
                                      ,am05_areatotal
                                      ,am05_protprocesso
                       )
                values (
                                $this->am05_sequencial
                               ,'$this->am05_nome'
                               ,'$this->am05_nomefanta'
                               ,$this->am05_numero
                               ,'$this->am05_complemento'
                               ,'$this->am05_cep'
                               ,$this->am05_bairro
                               ,$this->am05_ruas
                               ,'$this->am05_cnpj'
                               ,$this->am05_cgm
                               ,$this->am05_areatotal
                               ,$this->am05_protprocesso
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro dos empreendimentos ($this->am05_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro dos empreendimentos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro dos empreendimentos ($this->am05_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am05_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am05_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20785,'$this->am05_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3741,20785,'','".AddSlashes(pg_result($resaco,0,'am05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20786,'','".AddSlashes(pg_result($resaco,0,'am05_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20787,'','".AddSlashes(pg_result($resaco,0,'am05_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20788,'','".AddSlashes(pg_result($resaco,0,'am05_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20789,'','".AddSlashes(pg_result($resaco,0,'am05_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20790,'','".AddSlashes(pg_result($resaco,0,'am05_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20791,'','".AddSlashes(pg_result($resaco,0,'am05_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20792,'','".AddSlashes(pg_result($resaco,0,'am05_ruas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20797,'','".AddSlashes(pg_result($resaco,0,'am05_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20803,'','".AddSlashes(pg_result($resaco,0,'am05_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,20922,'','".AddSlashes(pg_result($resaco,0,'am05_areatotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3741,21142,'','".AddSlashes(pg_result($resaco,0,'am05_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am05_sequencial=null) {
      $this->atualizacampos();
     $sql = " update empreendimento set ";
     $virgula = "";
     if(trim($this->am05_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_sequencial"])){
       $sql  .= $virgula." am05_sequencial = $this->am05_sequencial ";
       $virgula = ",";
       if(trim($this->am05_sequencial) == null ){
         $this->erro_sql = " Campo Código do Empreendimento não informado.";
         $this->erro_campo = "am05_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am05_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_nome"])){
       $sql  .= $virgula." am05_nome = '$this->am05_nome' ";
       $virgula = ",";
     }
     if(trim($this->am05_nomefanta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_nomefanta"])){
       $sql  .= $virgula." am05_nomefanta = '$this->am05_nomefanta' ";
       $virgula = ",";
     }
     if(trim($this->am05_numero)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_numero"])){
       $sql  .= $virgula." am05_numero = $this->am05_numero ";
       $virgula = ",";
       if(trim($this->am05_numero) == null ){
         $this->erro_sql = " Campo Número não informado.";
         $this->erro_campo = "am05_numero";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am05_complemento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_complemento"])){
       $sql  .= $virgula." am05_complemento = '$this->am05_complemento' ";
       $virgula = ",";
     }
     if(trim($this->am05_cep)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_cep"])){
       $sql  .= $virgula." am05_cep = '$this->am05_cep' ";
       $virgula = ",";
       if(trim($this->am05_cep) == null ){
         $this->erro_sql = " Campo CEP não informado.";
         $this->erro_campo = "am05_cep";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am05_bairro)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_bairro"])){
       $sql  .= $virgula." am05_bairro = $this->am05_bairro ";
       $virgula = ",";
       if(trim($this->am05_bairro) == null ){
         $this->erro_sql = " Campo Código Bairro não informado.";
         $this->erro_campo = "am05_bairro";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am05_ruas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_ruas"])){
       $sql  .= $virgula." am05_ruas = $this->am05_ruas ";
       $virgula = ",";
       if(trim($this->am05_ruas) == null ){
         $this->erro_sql = " Campo Código Logradouro não informado.";
         $this->erro_campo = "am05_ruas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am05_cnpj)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_cnpj"])){
       $sql  .= $virgula." am05_cnpj = '$this->am05_cnpj' ";
       $virgula = ",";
     }
     if(trim($this->am05_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_cgm"])){
       $sql  .= $virgula." am05_cgm = $this->am05_cgm ";
       $virgula = ",";
       if(trim($this->am05_cgm) == null ){
         $this->erro_sql = " Campo CGM não informado.";
         $this->erro_campo = "am05_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am05_areatotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_areatotal"])){
        if(trim($this->am05_areatotal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["am05_areatotal"])){
           $this->am05_areatotal = "0" ;
        }
       $sql  .= $virgula." am05_areatotal = $this->am05_areatotal ";
       $virgula = ",";
     }
     if(trim($this->am05_protprocesso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am05_protprocesso"])){
       $sql  .= $virgula." am05_protprocesso = $this->am05_protprocesso ";
       $virgula = ",";
       if(trim($this->am05_protprocesso) == null ){
         $this->erro_sql = " Campo Protocolo não informado.";
         $this->erro_campo = "am05_protprocesso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am05_sequencial!=null){
       $sql .= " am05_sequencial = $am05_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am05_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20785,'$this->am05_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_sequencial"]) || $this->am05_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3741,20785,'".AddSlashes(pg_result($resaco,$conresaco,'am05_sequencial'))."','$this->am05_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_nome"]) || $this->am05_nome != "")
             $resac = db_query("insert into db_acount values($acount,3741,20786,'".AddSlashes(pg_result($resaco,$conresaco,'am05_nome'))."','$this->am05_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_nomefanta"]) || $this->am05_nomefanta != "")
             $resac = db_query("insert into db_acount values($acount,3741,20787,'".AddSlashes(pg_result($resaco,$conresaco,'am05_nomefanta'))."','$this->am05_nomefanta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_numero"]) || $this->am05_numero != "")
             $resac = db_query("insert into db_acount values($acount,3741,20788,'".AddSlashes(pg_result($resaco,$conresaco,'am05_numero'))."','$this->am05_numero',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_complemento"]) || $this->am05_complemento != "")
             $resac = db_query("insert into db_acount values($acount,3741,20789,'".AddSlashes(pg_result($resaco,$conresaco,'am05_complemento'))."','$this->am05_complemento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_cep"]) || $this->am05_cep != "")
             $resac = db_query("insert into db_acount values($acount,3741,20790,'".AddSlashes(pg_result($resaco,$conresaco,'am05_cep'))."','$this->am05_cep',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_bairro"]) || $this->am05_bairro != "")
             $resac = db_query("insert into db_acount values($acount,3741,20791,'".AddSlashes(pg_result($resaco,$conresaco,'am05_bairro'))."','$this->am05_bairro',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_ruas"]) || $this->am05_ruas != "")
             $resac = db_query("insert into db_acount values($acount,3741,20792,'".AddSlashes(pg_result($resaco,$conresaco,'am05_ruas'))."','$this->am05_ruas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_cnpj"]) || $this->am05_cnpj != "")
             $resac = db_query("insert into db_acount values($acount,3741,20797,'".AddSlashes(pg_result($resaco,$conresaco,'am05_cnpj'))."','$this->am05_cnpj',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_cgm"]) || $this->am05_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3741,20803,'".AddSlashes(pg_result($resaco,$conresaco,'am05_cgm'))."','$this->am05_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_areatotal"]) || $this->am05_areatotal != "")
             $resac = db_query("insert into db_acount values($acount,3741,20922,'".AddSlashes(pg_result($resaco,$conresaco,'am05_areatotal'))."','$this->am05_areatotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am05_protprocesso"]) || $this->am05_protprocesso != "")
             $resac = db_query("insert into db_acount values($acount,3741,21142,'".AddSlashes(pg_result($resaco,$conresaco,'am05_protprocesso'))."','$this->am05_protprocesso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos empreendimentos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos empreendimentos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am05_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am05_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20785,'$am05_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3741,20785,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20786,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20787,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_nomefanta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20788,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_numero'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20789,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_complemento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20790,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_cep'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20791,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_bairro'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20792,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_ruas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20797,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_cnpj'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20803,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,20922,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_areatotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3741,21142,'','".AddSlashes(pg_result($resaco,$iresaco,'am05_protprocesso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from empreendimento
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am05_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am05_sequencial = $am05_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro dos empreendimentos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am05_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro dos empreendimentos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am05_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am05_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:empreendimento";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am05_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from empreendimento ";
     $sql .= "      inner join bairro  on  bairro.j13_codi = empreendimento.am05_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = empreendimento.am05_ruas";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empreendimento.am05_cgm";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = empreendimento.am05_protprocesso";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = protprocesso.p58_numcgm";
     $sql .= "      inner join db_config  on  db_config.codigo = protprocesso.p58_instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = protprocesso.p58_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = protprocesso.p58_coddepto";
     $sql .= "      inner join tipoproc  on  tipoproc.p51_codigo = protprocesso.p58_codigo";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am05_sequencial)) {
         $sql2 .= " where empreendimento.am05_sequencial = $am05_sequencial ";
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

   public function sql_query_empreendimento_atividade ($am05_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = " select {$campos}                                                                            ";
     $sql .= "   from empreendimento                                                                       ";
     $sql .= "        inner join bairro                         on bairro.j13_codi     = am05_bairro       ";
     $sql .= "        inner join ruas                           on ruas.j14_codigo     = am05_ruas         ";
     $sql .= "        inner join cgm                            on cgm.z01_numcgm      = am05_cgm          ";
     $sql .= "        left  join empreendimentoatividadeimpacto on am06_empreendimento = am05_sequencial   ";
     $sql .= "                                                 and am06_principal is true                  ";
     $sql .= "        left join atividadeimpacto                on am03_sequencial = am06_atividadeimpacto ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am05_sequencial)) {
         $sql2 .= " where empreendimento.am05_sequencial = $am05_sequencial ";
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
   public function sql_query_file ($am05_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from empreendimento ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am05_sequencial)){
         $sql2 .= " where empreendimento.am05_sequencial = $am05_sequencial ";
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
   * Funcão que cria a sql para buscar condicinantes vinculadas ao empreendimento
   * @param  int $iCodigoEmpreendimento
   * @param  int $iCodigoTipoLicenca
   * @return string
   */
  public function sql_query_condicionante ($iCodigoEmpreendimento, $iCodigoTipoLicenca) {

    $sSql  = " select distinct am10_sequencial, am10_descricao, am10_padrao                                           ";
    $sSql .= "   from condicionante                                                                                   ";
    $sSql .= "        inner join condicionantetipolicenca       on am17_condicionante    = am10_sequencial               ";
    $sSql .= "        left  join condicionanteatividadeimpacto  on am11_condicionante    = am10_sequencial               ";
    $sSql .= "        left  join empreendimentoatividadeimpacto on am06_atividadeimpacto = am11_atividadeimpacto ";
    $sSql .= "  where am17_tipolicenca = {$iCodigoTipoLicenca}                                                        ";
    $sSql .= "    and ( am10_vinculatodasatividades = 't' or  am06_empreendimento = {$iCodigoEmpreendimento} )        ";

    return $sSql;
  }

  /**
   * Buscamos o último parecer com sua respectiva licença, caso exista, por empreendimento
   *
   * @param  int $iCodigoEmpreendimento
   * @return string    Query da Consulta
   */
  public function sql_query_licenca($iCodigoEmpreendimento) {

    $sSql  = "select am13_sequencial,                                                                              ";
    $sSql .= "       am08_sequencial,                                                                              ";
    $sSql .= "       am08_tipolicenca,                                                                             ";
    $sSql .= "       am08_dataemissao,                                                                             ";
    $sSql .= "       am08_datavencimento,                                                                          ";
    $sSql .= "       am08_protprocesso,                                                                            ";
    $sSql .= "       am08_favoravel                                                                                ";
    $sSql .= "  from parecertecnico                                                                                ";
    $sSql .= "       left join licencaempreendimento on am08_sequencial = am13_parecertecnico                      ";
    $sSql .= " where am08_sequencial in (select max(am08_sequencial)                                               ";
    $sSql .= "                             from empreendimento                                                     ";
    $sSql .= "                                  inner join parecertecnico on am05_sequencial = am08_empreendimento ";
    $sSql .= "                            where am05_sequencial = {$iCodigoEmpreendimento})                        ";

    return $sSql;
  }
}