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

//MODULO: itbi
//CLASSE DA ENTIDADE itbiintermediador
class cl_itbiintermediador {
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
   var $it35_sequencial = 0;
   var $it35_itbi = 0;
   var $it35_cgm = 0;
   var $it35_nome = null;
   var $it35_cnpj_cpf = null;
   var $it35_creci = null;
   var $it35_principal = 'f';
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 it35_sequencial = int4 = Código
                 it35_itbi = int4 = Código ITBI
                 it35_cgm = int4 = CGM
                 it35_nome = varchar(100) = Nome
                 it35_cnpj_cpf = varchar(14) = CPF/CNPJ
                 it35_creci = varchar(20) = CRECI
                 it35_principal = bool = Principal
                 ";
   //funcao construtor da classe
   function cl_itbiintermediador() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("itbiintermediador");
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
       $this->it35_sequencial = ($this->it35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it35_sequencial"]:$this->it35_sequencial);
       $this->it35_itbi = ($this->it35_itbi == ""?@$GLOBALS["HTTP_POST_VARS"]["it35_itbi"]:$this->it35_itbi);
       $this->it35_cgm = ($this->it35_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["it35_cgm"]:$this->it35_cgm);
       $this->it35_nome = ($this->it35_nome == ""?@$GLOBALS["HTTP_POST_VARS"]["it35_nome"]:$this->it35_nome);
       $this->it35_cnpj_cpf = ($this->it35_cnpj_cpf == ""?@$GLOBALS["HTTP_POST_VARS"]["it35_cnpj_cpf"]:$this->it35_cnpj_cpf);
       $this->it35_creci = ($this->it35_creci == ""?@$GLOBALS["HTTP_POST_VARS"]["it35_creci"]:$this->it35_creci);
       $this->it35_principal = ($this->it35_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["it35_principal"]:$this->it35_principal);
     }else{
       $this->it35_sequencial = ($this->it35_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["it35_sequencial"]:$this->it35_sequencial);
     }
   }
   // funcao para Inclusão
   function incluir ($it35_sequencial = null){
      $this->atualizacampos();
     if($this->it35_itbi == null ){
       $this->erro_sql   = " Campo Código ITBI não informado.";
       $this->erro_campo = "it35_itbi";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->it35_cgm == null ){
       $this->it35_cgm = "null";
     }
     if($this->it35_principal == null ){
       $this->erro_sql = " Campo Principal não informado.";
       $this->erro_campo = "it35_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($it35_sequencial == "" || $it35_sequencial == null ){
       $result = db_query("select nextval('itbiintermediador_it35_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: itbiintermediador_it35_sequencial_seq do campo: it35_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->it35_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from itbiintermediador_it35_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $it35_sequencial)){
         $this->erro_sql = " Campo it35_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->it35_sequencial = $it35_sequencial;
       }
     }
     if(($this->it35_sequencial == null) || ($this->it35_sequencial == "") ){
       $this->erro_sql = " Campo it35_sequencial não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into itbiintermediador(
                                       it35_sequencial
                                      ,it35_itbi
                                      ,it35_cgm
                                      ,it35_nome
                                      ,it35_cnpj_cpf
                                      ,it35_creci
                                      ,it35_principal
                       )
                values (
                                $this->it35_sequencial
                               ,$this->it35_itbi
                               ,$this->it35_cgm
                               ,'$this->it35_nome'
                               ,'$this->it35_cnpj_cpf'
                               ,'$this->it35_creci'
                               ,'$this->it35_principal'
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabela de intermediadores do ITBI ($this->it35_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabela de intermediadores do ITBI já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabela de intermediadores do ITBI ($this->it35_sequencial) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it35_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it35_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,21761,'$this->it35_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3921,21761,'','".AddSlashes(pg_result($resaco,0,'it35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3921,21762,'','".AddSlashes(pg_result($resaco,0,'it35_itbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3921,21763,'','".AddSlashes(pg_result($resaco,0,'it35_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3921,21764,'','".AddSlashes(pg_result($resaco,0,'it35_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3921,21765,'','".AddSlashes(pg_result($resaco,0,'it35_cnpj_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3921,21767,'','".AddSlashes(pg_result($resaco,0,'it35_creci'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3921,21768,'','".AddSlashes(pg_result($resaco,0,'it35_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($it35_sequencial=null) {
      $this->atualizacampos();
     $sql = " update itbiintermediador set ";
     $virgula = "";
     if(trim($this->it35_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it35_sequencial"])){
       $sql  .= $virgula." it35_sequencial = $this->it35_sequencial ";
       $virgula = ",";
       if(trim($this->it35_sequencial) == null ){
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "it35_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it35_itbi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it35_itbi"])){
       $sql  .= $virgula." it35_itbi = $this->it35_itbi ";
       $virgula = ",";
       if(trim($this->it35_itbi) == null ){
         $this->erro_sql = " Campo Código ITBI não informado.";
         $this->erro_campo = "it35_itbi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it35_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it35_cgm"])){
        if(trim($this->it35_cgm)=="" && isset($GLOBALS["HTTP_POST_VARS"]["it35_cgm"])){
           $this->it35_cgm = "0" ;
        }
       $sql  .= $virgula." it35_cgm = $this->it35_cgm ";
       $virgula = ",";
     }
     if(trim($this->it35_nome)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it35_nome"])){
       $sql  .= $virgula." it35_nome = '$this->it35_nome' ";
       $virgula = ",";
     }
     if(trim($this->it35_cnpj_cpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it35_cnpj_cpf"])){
       $sql  .= $virgula." it35_cnpj_cpf = '$this->it35_cnpj_cpf' ";
       $virgula = ",";
     }
     if(trim($this->it35_creci)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it35_creci"])){
       $sql  .= $virgula." it35_creci = '$this->it35_creci' ";
       $virgula = ",";
       if(trim($this->it35_creci) == null ){
         $this->erro_sql = " Campo CRECI não informado.";
         $this->erro_campo = "it35_creci";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->it35_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["it35_principal"])){
       $sql  .= $virgula." it35_principal = '$this->it35_principal' ";
       $virgula = ",";
       if(trim($this->it35_principal) == null ){
         $this->erro_sql = " Campo Principal não informado.";
         $this->erro_campo = "it35_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($it35_sequencial!=null){
       $sql .= " it35_sequencial = $this->it35_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->it35_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,21761,'$this->it35_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["it35_sequencial"]) || $this->it35_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3921,21761,'".AddSlashes(pg_result($resaco,$conresaco,'it35_sequencial'))."','$this->it35_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["it35_itbi"]) || $this->it35_itbi != "")
             $resac = db_query("insert into db_acount values($acount,3921,21762,'".AddSlashes(pg_result($resaco,$conresaco,'it35_itbi'))."','$this->it35_itbi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["it35_cgm"]) || $this->it35_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3921,21763,'".AddSlashes(pg_result($resaco,$conresaco,'it35_cgm'))."','$this->it35_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["it35_nome"]) || $this->it35_nome != "")
             $resac = db_query("insert into db_acount values($acount,3921,21764,'".AddSlashes(pg_result($resaco,$conresaco,'it35_nome'))."','$this->it35_nome',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["it35_cnpj_cpf"]) || $this->it35_cnpj_cpf != "")
             $resac = db_query("insert into db_acount values($acount,3921,21765,'".AddSlashes(pg_result($resaco,$conresaco,'it35_cnpj_cpf'))."','$this->it35_cnpj_cpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["it35_creci"]) || $this->it35_creci != "")
             $resac = db_query("insert into db_acount values($acount,3921,21767,'".AddSlashes(pg_result($resaco,$conresaco,'it35_creci'))."','$this->it35_creci',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["it35_principal"]) || $this->it35_principal != "")
             $resac = db_query("insert into db_acount values($acount,3921,21768,'".AddSlashes(pg_result($resaco,$conresaco,'it35_principal'))."','$this->it35_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de intermediadores do ITBI não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->it35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de intermediadores do ITBI não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->it35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->it35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($it35_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($it35_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,21761,'$it35_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3921,21761,'','".AddSlashes(pg_result($resaco,$iresaco,'it35_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3921,21762,'','".AddSlashes(pg_result($resaco,$iresaco,'it35_itbi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3921,21763,'','".AddSlashes(pg_result($resaco,$iresaco,'it35_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3921,21764,'','".AddSlashes(pg_result($resaco,$iresaco,'it35_nome'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3921,21765,'','".AddSlashes(pg_result($resaco,$iresaco,'it35_cnpj_cpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3921,21767,'','".AddSlashes(pg_result($resaco,$iresaco,'it35_creci'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3921,21768,'','".AddSlashes(pg_result($resaco,$iresaco,'it35_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from itbiintermediador
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($it35_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " it35_sequencial = $it35_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabela de intermediadores do ITBI não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$it35_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Tabela de intermediadores do ITBI não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$it35_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$it35_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:itbiintermediador";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($it35_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from itbiintermediador ";
     $sql .= "      left  join cgm  on  cgm.z01_numcgm = itbiintermediador.it35_cgm";
     $sql .= "      inner join itbi  on  itbi.it01_guia = itbiintermediador.it35_itbi";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = itbi.it01_id_usuario";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = itbi.it01_coddepto";
     $sql .= "      inner join itbitransacao  on  itbitransacao.it04_codigo = itbi.it01_tipotransacao";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($it35_sequencial)) {
         $sql2 .= " where itbiintermediador.it35_sequencial = $it35_sequencial ";
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
   public function sql_query_file ($it35_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from itbiintermediador ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($it35_sequencial)){
         $sql2 .= " where itbiintermediador.it35_sequencial = $it35_sequencial ";
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
   * Retorna sql para verificar documentos iguais em uma itbi
   * @param  integer $iItbi
   * @param  string  $sCnpjCpf
   * @param  integer $iSequencial
   * @return string
   */
  public function sql_verifica_documento_itbi($iItbi, $sCnpjCpf, $iSequencial = null){

    $sSql  = "select *                               ";
    $sSql .= "  from itbiintermediador               ";
    $sSql .= " where it35_itbi = ".$iItbi."          ";
    $sSql .= "   and it35_cnpj_cpf = '".$sCnpjCpf."' ";

    if(!empty($iSequencial)){
      $sSql .= " and it35_sequencial != '".$iSequencial."' ";
    }
    return $sSql;
  }

  /**
   * Retorna sql para update de principal
   * @param  integer $iItbi
   * @return string
   */
  public function sql_update_principal($iItbi){

    $sSql  = "update itbiintermediador      ";
    $sSql .= "   set it35_principal = false ";
    $sSql .= " where it35_itbi = ".$iItbi." ";
    return $sSql;
  }

  /**
   * Retorna sql para update de principal do intermediador
   * @param  integer $iSequencial
   * @param  string  $iPrincipal
   * @return string
   */
  public function sql_update_itbiintermediador_principal($iSequencial, $sPrincipal){

    $sSql  = "update itbiintermediador                  ";
    $sSql .= "   set it35_principal = ".$sPrincipal."   ";
    $sSql .= " where it35_sequencial = ".$iSequencial." ";
    return $sSql;
  }

  /**
   * Retorna sql para captura de principal de itbi
   * @param  integer $iItbi
   * @return string
   */
  public function sql_get_principal($iItbi){

    $sSql  = "select *                      ";
    $sSql .= "  from itbiintermediador      ";
    $sSql .= " where it35_principal = true  ";
    $sSql .= "   and it35_itbi = ".$iItbi." ";
    return $sSql;
  }
}
