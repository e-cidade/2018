<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

//MODULO: cadastro
//CLASSE DA ENTIDADE setor
class cl_setor { 
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
   var $j30_codi = null; 
   var $j30_descr = null; 
   var $j30_alipre = 0; 
   var $j30_aliter = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 j30_codi = char(4) = Setor 
                 j30_descr = varchar(40) = Descricao 
                 j30_alipre = float8 = Aliquota Predial 
                 j30_aliter = float8 = Aliquota Territorial 
                 ";
   //funcao construtor da classe 
   function cl_setor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("setor"); 
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
       $this->j30_codi = ($this->j30_codi == ""?@$GLOBALS["HTTP_POST_VARS"]["j30_codi"]:$this->j30_codi);
       $this->j30_descr = ($this->j30_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["j30_descr"]:$this->j30_descr);
       $this->j30_alipre = ($this->j30_alipre == ""?@$GLOBALS["HTTP_POST_VARS"]["j30_alipre"]:$this->j30_alipre);
       $this->j30_aliter = ($this->j30_aliter == ""?@$GLOBALS["HTTP_POST_VARS"]["j30_aliter"]:$this->j30_aliter);
     }else{
       $this->j30_codi = ($this->j30_codi == ""?@$GLOBALS["HTTP_POST_VARS"]["j30_codi"]:$this->j30_codi);
     }
   }
   // funcao para Inclus�o
   function incluir ($j30_codi){ 
      $this->atualizacampos();
     if($this->j30_descr == null ){ 
       $this->erro_sql = " Campo Descricao n�o informado.";
       $this->erro_campo = "j30_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j30_alipre == null ){ 
       $this->erro_sql = " Campo Aliquota Predial n�o informado.";
       $this->erro_campo = "j30_alipre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->j30_aliter == null ){ 
       $this->erro_sql = " Campo Aliquota Territorial n�o informado.";
       $this->erro_campo = "j30_aliter";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->j30_codi = $j30_codi; 
     if(($this->j30_codi == null) || ($this->j30_codi == "") ){ 
       $this->erro_sql = " Campo j30_codi n�o declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into setor(
                                       j30_codi 
                                      ,j30_descr 
                                      ,j30_alipre 
                                      ,j30_aliter 
                       )
                values (
                                '$this->j30_codi' 
                               ,'$this->j30_descr' 
                               ,$this->j30_alipre 
                               ,$this->j30_aliter 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = " ($this->j30_codi) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = " j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = " ($this->j30_codi) n�o Inclu�do. Inclus�o Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclus�o efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j30_codi;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j30_codi  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,71,'$this->j30_codi','I')");
         $resac = db_query("insert into db_acount values($acount,16,71,'','".AddSlashes(pg_result($resaco,0,'j30_codi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,16,72,'','".AddSlashes(pg_result($resaco,0,'j30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,16,651,'','".AddSlashes(pg_result($resaco,0,'j30_alipre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,16,652,'','".AddSlashes(pg_result($resaco,0,'j30_aliter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($j30_codi=null) { 
      $this->atualizacampos();
     $sql = " update setor set ";
     $virgula = "";
     if(trim($this->j30_codi)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j30_codi"])){ 
       $sql  .= $virgula." j30_codi = '$this->j30_codi' ";
       $virgula = ",";
       if(trim($this->j30_codi) == null ){ 
         $this->erro_sql = " Campo Setor n�o informado.";
         $this->erro_campo = "j30_codi";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j30_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j30_descr"])){ 
       $sql  .= $virgula." j30_descr = '$this->j30_descr' ";
       $virgula = ",";
       if(trim($this->j30_descr) == null ){ 
         $this->erro_sql = " Campo Descricao n�o informado.";
         $this->erro_campo = "j30_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j30_alipre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j30_alipre"])){ 
       $sql  .= $virgula." j30_alipre = $this->j30_alipre ";
       $virgula = ",";
       if(trim($this->j30_alipre) == null ){ 
         $this->erro_sql = " Campo Aliquota Predial n�o informado.";
         $this->erro_campo = "j30_alipre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->j30_aliter)!="" || isset($GLOBALS["HTTP_POST_VARS"]["j30_aliter"])){ 
       $sql  .= $virgula." j30_aliter = $this->j30_aliter ";
       $virgula = ",";
       if(trim($this->j30_aliter) == null ){ 
         $this->erro_sql = " Campo Aliquota Territorial n�o informado.";
         $this->erro_campo = "j30_aliter";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($j30_codi!=null){
       $sql .= " j30_codi = '$this->j30_codi'";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->j30_codi));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,71,'$this->j30_codi','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j30_codi"]) || $this->j30_codi != "")
             $resac = db_query("insert into db_acount values($acount,16,71,'".AddSlashes(pg_result($resaco,$conresaco,'j30_codi'))."','$this->j30_codi',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j30_descr"]) || $this->j30_descr != "")
             $resac = db_query("insert into db_acount values($acount,16,72,'".AddSlashes(pg_result($resaco,$conresaco,'j30_descr'))."','$this->j30_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j30_alipre"]) || $this->j30_alipre != "")
             $resac = db_query("insert into db_acount values($acount,16,651,'".AddSlashes(pg_result($resaco,$conresaco,'j30_alipre'))."','$this->j30_alipre',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["j30_aliter"]) || $this->j30_aliter != "")
             $resac = db_query("insert into db_acount values($acount,16,652,'".AddSlashes(pg_result($resaco,$conresaco,'j30_aliter'))."','$this->j30_aliter',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " n�o Alterado. Altera��o Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->j30_codi;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " n�o foi Alterado. Altera��o Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->j30_codi;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$this->j30_codi;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($j30_codi=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($j30_codi));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,71,'$j30_codi','E')");
           $resac  = db_query("insert into db_acount values($acount,16,71,'','".AddSlashes(pg_result($resaco,$iresaco,'j30_codi'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,16,72,'','".AddSlashes(pg_result($resaco,$iresaco,'j30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,16,651,'','".AddSlashes(pg_result($resaco,$iresaco,'j30_alipre'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,16,652,'','".AddSlashes(pg_result($resaco,$iresaco,'j30_aliter'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from setor
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($j30_codi)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " j30_codi = '$j30_codi' ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = " n�o Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$j30_codi;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = " n�o Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$j30_codi;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com sucesso.\\n";
         $this->erro_sql .= "Valores : ".$j30_codi;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_num_rows($result);
      if ($this->numrows == 0) {
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:setor";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($j30_codi = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from setor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j30_codi)) {
         $sql2 .= " where setor.j30_codi = '$j30_codi' "; 
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
   public function sql_query_file ($j30_codi = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from setor ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($j30_codi)){
         $sql2 .= " where setor.j30_codi = '$j30_codi' "; 
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

  public function vinculosSetor(array $aCampos, array $aWhere) {

    $sCampos = count($aCampos) > 0 ? implode(', ', $aCampos) : '*';

    $sSql  = "select {$sCampos}";
    $sSql .= "  from setor";
    $sSql .= "       left join face            on j37_setor = j30_codi";
    $sSql .= "       left join lote            on j34_setor = j30_codi";
    $sSql .= "       left join zonassetorvalor on j141_setor = j30_codi";

    if(count($aWhere) > 0) {
      $sSql .= " where " . implode(' AND ', $aWhere);
    }

    return $sSql;
  }
}