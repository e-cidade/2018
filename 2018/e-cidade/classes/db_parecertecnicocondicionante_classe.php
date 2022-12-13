<?
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
//CLASSE DA ENTIDADE parecertecnicocondicionante
class cl_parecertecnicocondicionante {
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
   var $am12_sequencial = 0;
   var $am12_parecertecnico = 0;
   var $am12_condicionante = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am12_sequencial = int4 = Condicionante
                 am12_parecertecnico = int4 = Parecer Técnico
                 am12_condicionante = int4 = Condicionante
                 ";
   //funcao construtor da classe
   function cl_parecertecnicocondicionante() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("parecertecnicocondicionante");
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
       $this->am12_sequencial = ($this->am12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am12_sequencial"]:$this->am12_sequencial);
       $this->am12_parecertecnico = ($this->am12_parecertecnico == ""?@$GLOBALS["HTTP_POST_VARS"]["am12_parecertecnico"]:$this->am12_parecertecnico);
       $this->am12_condicionante = ($this->am12_condicionante == ""?@$GLOBALS["HTTP_POST_VARS"]["am12_condicionante"]:$this->am12_condicionante);
     }else{
       $this->am12_sequencial = ($this->am12_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am12_sequencial"]:$this->am12_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am12_sequencial = null){
      $this->atualizacampos();
     if($this->am12_parecertecnico == null ){
       $this->erro_sql = " Campo Parecer Técnico não informado.";
       $this->erro_campo = "am12_parecertecnico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am12_condicionante == null ){
       $this->erro_sql = " Campo Condicionante não informado.";
       $this->erro_campo = "am12_condicionante";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am12_sequencial == "" || $am12_sequencial == null ){
       $result = db_query("select nextval('parecertecnicocondicionante_am12_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: parecertecnicocondicionante_am12_sequencial_seq do campo: am12_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am12_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from parecertecnicocondicionante_am12_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am12_sequencial)){
         $this->erro_sql = " Campo am12_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am12_sequencial = $am12_sequencial;
       }
     }
     if(($this->am12_sequencial == null) || ($this->am12_sequencial == "") ){
       $this->erro_sql = " Campo am12_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into parecertecnicocondicionante(
                                       am12_sequencial
                                      ,am12_parecertecnico
                                      ,am12_condicionante
                       )
                values (
                                $this->am12_sequencial
                               ,$this->am12_parecertecnico
                               ,$this->am12_condicionante
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Parecer/Condicionante ($this->am12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Parecer/Condicionante já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Parecer/Condicionante ($this->am12_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am12_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am12_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20859,'$this->am12_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3753,20859,'','".AddSlashes(pg_result($resaco,0,'am12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3753,20860,'','".AddSlashes(pg_result($resaco,0,'am12_parecertecnico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3753,20862,'','".AddSlashes(pg_result($resaco,0,'am12_condicionante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am12_sequencial=null) {
      $this->atualizacampos();
     $sql = " update parecertecnicocondicionante set ";
     $virgula = "";
     if(trim($this->am12_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am12_sequencial"])){
       $sql  .= $virgula." am12_sequencial = $this->am12_sequencial ";
       $virgula = ",";
       if(trim($this->am12_sequencial) == null ){
         $this->erro_sql = " Campo Condicionante não informado.";
         $this->erro_campo = "am12_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am12_parecertecnico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am12_parecertecnico"])){
       $sql  .= $virgula." am12_parecertecnico = $this->am12_parecertecnico ";
       $virgula = ",";
       if(trim($this->am12_parecertecnico) == null ){
         $this->erro_sql = " Campo Parecer Técnico não informado.";
         $this->erro_campo = "am12_parecertecnico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am12_condicionante)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am12_condicionante"])){
       $sql  .= $virgula." am12_condicionante = $this->am12_condicionante ";
       $virgula = ",";
       if(trim($this->am12_condicionante) == null ){
         $this->erro_sql = " Campo Condicionante não informado.";
         $this->erro_campo = "am12_condicionante";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am12_sequencial!=null){
       $sql .= " am12_sequencial = $this->am12_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am12_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20859,'$this->am12_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am12_sequencial"]) || $this->am12_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3753,20859,'".AddSlashes(pg_result($resaco,$conresaco,'am12_sequencial'))."','$this->am12_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am12_parecertecnico"]) || $this->am12_parecertecnico != "")
             $resac = db_query("insert into db_acount values($acount,3753,20860,'".AddSlashes(pg_result($resaco,$conresaco,'am12_parecertecnico'))."','$this->am12_parecertecnico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am12_condicionante"]) || $this->am12_condicionante != "")
             $resac = db_query("insert into db_acount values($acount,3753,20862,'".AddSlashes(pg_result($resaco,$conresaco,'am12_condicionante'))."','$this->am12_condicionante',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parecer/Condicionante nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parecer/Condicionante nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am12_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am12_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20859,'$am12_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3753,20859,'','".AddSlashes(pg_result($resaco,$iresaco,'am12_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3753,20860,'','".AddSlashes(pg_result($resaco,$iresaco,'am12_parecertecnico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3753,20862,'','".AddSlashes(pg_result($resaco,$iresaco,'am12_condicionante'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from parecertecnicocondicionante
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am12_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am12_sequencial = $am12_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Parecer/Condicionante nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am12_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Parecer/Condicionante nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am12_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am12_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:parecertecnicocondicionante";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am12_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from parecertecnicocondicionante ";
     $sql .= "      inner join parecertecnico  on  parecertecnico.am08_sequencial = parecertecnicocondicionante.am12_parecertecnico";
     $sql .= "      inner join condicionante  on  condicionante.am10_sequencial = parecertecnicocondicionante.am12_condicionante";
     $sql .= "      inner join protprocesso  on  protprocesso.p58_codproc = parecertecnico.am08_protprocesso";
     $sql .= "      inner join empreendimento  on  empreendimento.am05_sequencial = parecertecnico.am08_empreendimento";
     $sql .= "      inner join tipolicenca  on  tipolicenca.am09_sequencial = parecertecnico.am08_tipolicenca";
     $sql .= "      left  join tipolicenca  as a on   a.am09_sequencial = condicionante.am10_tipolicenca";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am12_sequencial)) {
         $sql2 .= " where parecertecnicocondicionante.am12_sequencial = $am12_sequencial ";
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
   public function sql_query_file ($am12_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from parecertecnicocondicionante ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am12_sequencial)){
         $sql2 .= " where parecertecnicocondicionante.am12_sequencial = $am12_sequencial ";
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
