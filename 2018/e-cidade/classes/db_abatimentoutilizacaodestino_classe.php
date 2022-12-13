<?php
/**
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
//MODULO: arrecadacao
//CLASSE DA ENTIDADE abatimentoutilizacaodestino
class cl_abatimentoutilizacaodestino { 
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
   var $k170_utilizacao = 0; 
   var $k170_numpre = 0; 
   var $k170_numpar = 0; 
   var $k170_receit = 0; 
   var $k170_hist = 0; 
   var $k170_tipo = 0; 
   var $k170_valor = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k170_utilizacao = int4 = Abatimento Utilizacao 
                 k170_numpre = int4 = Numpre Destino 
                 k170_numpar = int4 = Parcela destino 
                 k170_receit = int4 = Receita destino 
                 k170_hist = int4 = Historico Destino 
                 k170_tipo = int4 = Tipo Destino 
                 k170_valor = float4 = k170_valor 
                 ";
   //funcao construtor da classe 
   function cl_abatimentoutilizacaodestino() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("abatimentoutilizacaodestino"); 
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
       $this->k170_utilizacao = ($this->k170_utilizacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k170_utilizacao"]:$this->k170_utilizacao);
       $this->k170_numpre = ($this->k170_numpre == ""?@$GLOBALS["HTTP_POST_VARS"]["k170_numpre"]:$this->k170_numpre);
       $this->k170_numpar = ($this->k170_numpar == ""?@$GLOBALS["HTTP_POST_VARS"]["k170_numpar"]:$this->k170_numpar);
       $this->k170_receit = ($this->k170_receit == ""?@$GLOBALS["HTTP_POST_VARS"]["k170_receit"]:$this->k170_receit);
       $this->k170_hist = ($this->k170_hist == ""?@$GLOBALS["HTTP_POST_VARS"]["k170_hist"]:$this->k170_hist);
       $this->k170_tipo = ($this->k170_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["k170_tipo"]:$this->k170_tipo);
       $this->k170_valor = ($this->k170_valor == ""?@$GLOBALS["HTTP_POST_VARS"]["k170_valor"]:$this->k170_valor);
     }else{
     }
   }
   // funcao para Inclusão
   function incluir (){ 
      $this->atualizacampos();
     if($this->k170_utilizacao == null ){ 
       $this->erro_sql = " Campo Abatimento Utilizacao não informado.";
       $this->erro_campo = "k170_utilizacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k170_numpre == null ){ 
       $this->erro_sql = " Campo Numpre Destino não informado.";
       $this->erro_campo = "k170_numpre";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k170_numpar == null ){ 
       $this->erro_sql = " Campo Parcela destino não informado.";
       $this->erro_campo = "k170_numpar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k170_receit == null ){ 
       $this->erro_sql = " Campo Receita destino não informado.";
       $this->erro_campo = "k170_receit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k170_hist == null ){ 
       $this->erro_sql = " Campo Historico Destino não informado.";
       $this->erro_campo = "k170_hist";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k170_tipo == null ){ 
       $this->erro_sql = " Campo Tipo Destino não informado.";
       $this->erro_campo = "k170_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k170_valor == null ){ 
       $this->erro_sql = " Campo k170_valor não informado.";
       $this->erro_campo = "k170_valor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into abatimentoutilizacaodestino(
                                       k170_utilizacao 
                                      ,k170_numpre 
                                      ,k170_numpar 
                                      ,k170_receit 
                                      ,k170_hist 
                                      ,k170_tipo 
                                      ,k170_valor 
                       )
                values (
                                $this->k170_utilizacao 
                               ,$this->k170_numpre 
                               ,$this->k170_numpar 
                               ,$this->k170_receit 
                               ,$this->k170_hist 
                               ,$this->k170_tipo 
                               ,$this->k170_valor 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "abatimento utilizacao destino () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "abatimento utilizacao destino já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "abatimento utilizacao destino () não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ( $oid=null ) { 
      $this->atualizacampos();
     $sql = " update abatimentoutilizacaodestino set ";
     $virgula = "";
     if(trim($this->k170_utilizacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k170_utilizacao"])){ 
       $sql  .= $virgula." k170_utilizacao = $this->k170_utilizacao ";
       $virgula = ",";
       if(trim($this->k170_utilizacao) == null ){ 
         $this->erro_sql = " Campo Abatimento Utilizacao não informado.";
         $this->erro_campo = "k170_utilizacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k170_numpre)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k170_numpre"])){ 
       $sql  .= $virgula." k170_numpre = $this->k170_numpre ";
       $virgula = ",";
       if(trim($this->k170_numpre) == null ){ 
         $this->erro_sql = " Campo Numpre Destino não informado.";
         $this->erro_campo = "k170_numpre";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k170_numpar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k170_numpar"])){ 
       $sql  .= $virgula." k170_numpar = $this->k170_numpar ";
       $virgula = ",";
       if(trim($this->k170_numpar) == null ){ 
         $this->erro_sql = " Campo Parcela destino não informado.";
         $this->erro_campo = "k170_numpar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k170_receit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k170_receit"])){ 
       $sql  .= $virgula." k170_receit = $this->k170_receit ";
       $virgula = ",";
       if(trim($this->k170_receit) == null ){ 
         $this->erro_sql = " Campo Receita destino não informado.";
         $this->erro_campo = "k170_receit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k170_hist)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k170_hist"])){ 
       $sql  .= $virgula." k170_hist = $this->k170_hist ";
       $virgula = ",";
       if(trim($this->k170_hist) == null ){ 
         $this->erro_sql = " Campo Historico Destino não informado.";
         $this->erro_campo = "k170_hist";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k170_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k170_tipo"])){ 
       $sql  .= $virgula." k170_tipo = $this->k170_tipo ";
       $virgula = ",";
       if(trim($this->k170_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo Destino não informado.";
         $this->erro_campo = "k170_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k170_valor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k170_valor"])){ 
       $sql  .= $virgula." k170_valor = $this->k170_valor ";
       $virgula = ",";
       if(trim($this->k170_valor) == null ){ 
         $this->erro_sql = " Campo k170_valor não informado.";
         $this->erro_campo = "k170_valor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
$sql .= "oid = '$oid'";     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "abatimento utilizacao destino não Alterado. Alteração Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "abatimento utilizacao destino não foi Alterado. Alteração Executada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ( $oid=null ,$dbwhere=null) { 

     $sql = " delete from abatimentoutilizacaodestino
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
       $sql2 = "oid = '$oid'";
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "abatimento utilizacao destino não Excluído. Exclusão Abortada.\\n";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "abatimento utilizacao destino não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
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
        $this->erro_sql   = "Record Vazio na Tabela:abatimentoutilizacaodestino";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($oid = null, $campos = "abatimentoutilizacaodestino.oid,*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from abatimentoutilizacaodestino ";
     $sql .= "      inner join abatimentoutilizacao  on  abatimentoutilizacao.k157_sequencial = abatimentoutilizacaodestino.k170_utilizacao";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = abatimentoutilizacao.k157_usuario";
     $sql .= "      inner join abatimento  on  abatimento.k125_sequencial = abatimentoutilizacao.k157_abatimento";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($oid)) {
          $sql2 = " where abatimentoutilizacaodestino.oid = '$oid'";
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
   public function sql_query_file ($oid = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from abatimentoutilizacaodestino ";
     $sql2 = "";
     if (empty($dbwhere)) {
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
