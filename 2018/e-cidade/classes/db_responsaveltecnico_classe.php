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
//CLASSE DA ENTIDADE responsaveltecnico
class cl_responsaveltecnico {
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
   var $am07_sequencial = 0;
   var $am07_empreendimento = 0;
   var $am07_cgm = 0;
   // cria propriedade com as variaveis do arquivo
   var $campos = "
                 am07_sequencial = int4 = Código do Reponsável Técnico
                 am07_empreendimento = int4 = Código do Empreendimento
                 am07_cgm = int4 = Código do CGM
                 ";
   //funcao construtor da classe
   function cl_responsaveltecnico() {
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("responsaveltecnico");
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
       $this->am07_sequencial = ($this->am07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am07_sequencial"]:$this->am07_sequencial);
       $this->am07_empreendimento = ($this->am07_empreendimento == ""?@$GLOBALS["HTTP_POST_VARS"]["am07_empreendimento"]:$this->am07_empreendimento);
       $this->am07_cgm = ($this->am07_cgm == ""?@$GLOBALS["HTTP_POST_VARS"]["am07_cgm"]:$this->am07_cgm);
     }else{
       $this->am07_sequencial = ($this->am07_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["am07_sequencial"]:$this->am07_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($am07_sequencial){
      $this->atualizacampos();
     if($this->am07_empreendimento == null ){
       $this->erro_sql = " Campo Código do Empreendimento não informado.";
       $this->erro_campo = "am07_empreendimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->am07_cgm == null ){
       $this->erro_sql = " Campo Código do CGM não informado.";
       $this->erro_campo = "am07_cgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($am07_sequencial == "" || $am07_sequencial == null ){
       $result = db_query("select nextval('responsaveltecnico_am07_sequencial_seq')");
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: responsaveltecnico_am07_sequencial_seq do campo: am07_sequencial";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
       $this->am07_sequencial = pg_result($result,0,0);
     }else{
       $result = db_query("select last_value from responsaveltecnico_am07_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $am07_sequencial)){
         $this->erro_sql = " Campo am07_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->am07_sequencial = $am07_sequencial;
       }
     }
     if(($this->am07_sequencial == null) || ($this->am07_sequencial == "") ){
       $this->erro_sql = " Campo am07_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into responsaveltecnico(
                                       am07_sequencial
                                      ,am07_empreendimento
                                      ,am07_cgm
                       )
                values (
                                $this->am07_sequencial
                               ,$this->am07_empreendimento
                               ,$this->am07_cgm
                      )";
     $result = db_query($sql);
     if($result==false){
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cadastro de Responsável Técnico ($this->am07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cadastro de Responsável Técnico já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cadastro de Responsável Técnico ($this->am07_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am07_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am07_sequencial  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,20798,'$this->am07_sequencial','I')");
         $resac = db_query("insert into db_acount values($acount,3743,20798,'','".AddSlashes(pg_result($resaco,0,'am07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3743,20799,'','".AddSlashes(pg_result($resaco,0,'am07_empreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3743,20800,'','".AddSlashes(pg_result($resaco,0,'am07_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   }
   // funcao para alteracao
   public function alterar ($am07_sequencial=null) {
      $this->atualizacampos();
     $sql = " update responsaveltecnico set ";
     $virgula = "";
     if(trim($this->am07_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am07_sequencial"])){
       $sql  .= $virgula." am07_sequencial = $this->am07_sequencial ";
       $virgula = ",";
       if(trim($this->am07_sequencial) == null ){
         $this->erro_sql = " Campo Código do Reponsável Técnico não informado.";
         $this->erro_campo = "am07_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am07_empreendimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am07_empreendimento"])){
       $sql  .= $virgula." am07_empreendimento = $this->am07_empreendimento ";
       $virgula = ",";
       if(trim($this->am07_empreendimento) == null ){
         $this->erro_sql = " Campo Código do Empreendimento não informado.";
         $this->erro_campo = "am07_empreendimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->am07_cgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["am07_cgm"])){
       $sql  .= $virgula." am07_cgm = $this->am07_cgm ";
       $virgula = ",";
       if(trim($this->am07_cgm) == null ){
         $this->erro_sql = " Campo Código do CGM não informado.";
         $this->erro_campo = "am07_cgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($am07_sequencial!=null){
       $sql .= " am07_sequencial = $this->am07_sequencial";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->am07_sequencial));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,20798,'$this->am07_sequencial','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am07_sequencial"]) || $this->am07_sequencial != "")
             $resac = db_query("insert into db_acount values($acount,3743,20798,'".AddSlashes(pg_result($resaco,$conresaco,'am07_sequencial'))."','$this->am07_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am07_empreendimento"]) || $this->am07_empreendimento != "")
             $resac = db_query("insert into db_acount values($acount,3743,20799,'".AddSlashes(pg_result($resaco,$conresaco,'am07_empreendimento'))."','$this->am07_empreendimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["am07_cgm"]) || $this->am07_cgm != "")
             $resac = db_query("insert into db_acount values($acount,3743,20800,'".AddSlashes(pg_result($resaco,$conresaco,'am07_cgm'))."','$this->am07_cgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Responsável Técnico nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->am07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Responsável Técnico nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->am07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->am07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       }
     }
   }
   // funcao para exclusao
   public function excluir ($am07_sequencial=null,$dbwhere=null) {

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($am07_sequencial));
       } else {
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,20798,'$am07_sequencial','E')");
           $resac  = db_query("insert into db_acount values($acount,3743,20798,'','".AddSlashes(pg_result($resaco,$iresaco,'am07_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3743,20799,'','".AddSlashes(pg_result($resaco,$iresaco,'am07_empreendimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,3743,20800,'','".AddSlashes(pg_result($resaco,$iresaco,'am07_cgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from responsaveltecnico
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($am07_sequencial)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " am07_sequencial = $am07_sequencial ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) {
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cadastro de Responsável Técnico nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$am07_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Cadastro de Responsável Técnico nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$am07_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$am07_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:responsaveltecnico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql
   public function sql_query ($am07_sequencial = null,$campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos}";
     $sql .= "  from responsaveltecnico ";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = responsaveltecnico.am07_cgm";
     $sql .= "      inner join empreendimento  on  empreendimento.am05_sequencial = responsaveltecnico.am07_empreendimento";
     $sql .= "      inner join bairro  on  bairro.j13_codi = empreendimento.am05_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = empreendimento.am05_ruas";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = empreendimento.am05_cgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am07_sequencial)) {
         $sql2 .= " where responsaveltecnico.am07_sequencial = $am07_sequencial ";
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
   public function sql_query_file ($am07_sequencial = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from responsaveltecnico ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($am07_sequencial)){
         $sql2 .= " where responsaveltecnico.am07_sequencial = $am07_sequencial ";
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
   *  Método que retora query para buscar profissões dos responsáveis
   * @param  integer $am07_empreendimento
   * @param  string  $sCampos
   * @return string  $sSql
   */
  public function sql_query_profissao ($am07_empreendimento = null, $sCampos = null) {

    if (is_null($sCampos)) {
      $sCampos = "am07_sequencial, z01_numcgm, z01_nome, z01_profis, rh70_descr";
    }

    $sSql  = "select $sCampos";
    $sSql .= "  from responsaveltecnico ";
    $sSql .= "    inner join cgm on am07_cgm = z01_numcgm ";
    $sSql .= "    left join cgmfisico on z01_numcgm = z04_numcgm ";
    $sSql .= "    left join rhcbo on z04_rhcbo = rh70_sequencial ";

    if (!is_null($am07_empreendimento)) {
      $sSql .= "  where am07_empreendimento = $am07_empreendimento";
    }

    return $sSql;
  }

}
