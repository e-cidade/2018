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

//MODULO: ambulatorial
//CLASSE DA ENTIDADE sau_prestadorvinculos
class cl_sau_prestadorvinculos { 
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
   var $s111_i_codigo = 0; 
   var $s111_i_prestador = 0; 
   var $s111_procedimento = 0; 
   var $s111_c_situacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 s111_i_codigo = int4 = Código 
                 s111_i_prestador = int4 = Prestador 
                 s111_procedimento = int4 = Exame 
                 s111_c_situacao = char(1) = Situação 
                 ";
   //funcao construtor da classe 
   function cl_sau_prestadorvinculos() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_prestadorvinculos"); 
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
       $this->s111_i_codigo = ($this->s111_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s111_i_codigo"]:$this->s111_i_codigo);
       $this->s111_i_prestador = ($this->s111_i_prestador == ""?@$GLOBALS["HTTP_POST_VARS"]["s111_i_prestador"]:$this->s111_i_prestador);
       $this->s111_procedimento = ($this->s111_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["s111_procedimento"]:$this->s111_procedimento);
       $this->s111_c_situacao = ($this->s111_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["s111_c_situacao"]:$this->s111_c_situacao);
     }else{
       $this->s111_i_codigo = ($this->s111_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["s111_i_codigo"]:$this->s111_i_codigo);
     }
   }
   // funcao para Inclusão
   function incluir ($s111_i_codigo){ 
      $this->atualizacampos();
     if($this->s111_i_prestador == null ){ 
       $this->erro_sql = " Campo Prestador não informado.";
       $this->erro_campo = "s111_i_prestador";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s111_procedimento == null ){ 
       $this->erro_sql = " Campo Exame não informado.";
       $this->erro_campo = "s111_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->s111_c_situacao == null ){ 
       $this->erro_sql = " Campo Situação não informado.";
       $this->erro_campo = "s111_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($s111_i_codigo == "" || $s111_i_codigo == null ){
       $result = db_query("select nextval('sau_prestadorviinculos_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_prestadorviinculos_codigo_seq do campo: s111_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->s111_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_prestadorviinculos_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $s111_i_codigo)){
         $this->erro_sql = " Campo s111_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->s111_i_codigo = $s111_i_codigo; 
       }
     }
     if(($this->s111_i_codigo == null) || ($this->s111_i_codigo == "") ){ 
       $this->erro_sql = " Campo s111_i_codigo não declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_prestadorvinculos(
                                       s111_i_codigo 
                                      ,s111_i_prestador 
                                      ,s111_procedimento 
                                      ,s111_c_situacao 
                       )
                values (
                                $this->s111_i_codigo 
                               ,$this->s111_i_prestador 
                               ,$this->s111_procedimento 
                               ,'$this->s111_c_situacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Prestador Vinculos ($this->s111_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Prestador Vinculos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Prestador Vinculos ($this->s111_i_codigo) não Incluído. Inclusão Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s111_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s111_i_codigo  ));
       if(($resaco!=false)||($this->numrows!=0)){

         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,13566,'$this->s111_i_codigo','I')");
         $resac = db_query("insert into db_acount values($acount,2374,13566,'','".AddSlashes(pg_result($resaco,0,'s111_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2374,13567,'','".AddSlashes(pg_result($resaco,0,'s111_i_prestador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2374,13568,'','".AddSlashes(pg_result($resaco,0,'s111_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2374,13570,'','".AddSlashes(pg_result($resaco,0,'s111_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     return true;
   } 
   // funcao para alteracao
   public function alterar ($s111_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_prestadorvinculos set ";
     $virgula = "";
     if(trim($this->s111_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s111_i_codigo"])){ 
       $sql  .= $virgula." s111_i_codigo = $this->s111_i_codigo ";
       $virgula = ",";
       if(trim($this->s111_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código não informado.";
         $this->erro_campo = "s111_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s111_i_prestador)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s111_i_prestador"])){ 
       $sql  .= $virgula." s111_i_prestador = $this->s111_i_prestador ";
       $virgula = ",";
       if(trim($this->s111_i_prestador) == null ){ 
         $this->erro_sql = " Campo Prestador não informado.";
         $this->erro_campo = "s111_i_prestador";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s111_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s111_procedimento"])){ 
       $sql  .= $virgula." s111_procedimento = $this->s111_procedimento ";
       $virgula = ",";
       if(trim($this->s111_procedimento) == null ){ 
         $this->erro_sql = " Campo Exame não informado.";
         $this->erro_campo = "s111_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->s111_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["s111_c_situacao"])){ 
       $sql  .= $virgula." s111_c_situacao = '$this->s111_c_situacao' ";
       $virgula = ",";
       if(trim($this->s111_c_situacao) == null ){ 
         $this->erro_sql = " Campo Situação não informado.";
         $this->erro_campo = "s111_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($s111_i_codigo!=null){
       $sql .= " s111_i_codigo = $this->s111_i_codigo";
     }
     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       $resaco = $this->sql_record($this->sql_query_file($this->s111_i_codigo));
       if ($this->numrows > 0) {

         for ($conresaco = 0; $conresaco < $this->numrows; $conresaco++) {

           $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac = db_query("insert into db_acountkey values($acount,13566,'$this->s111_i_codigo','A')");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s111_i_codigo"]) || $this->s111_i_codigo != "")
             $resac = db_query("insert into db_acount values($acount,2374,13566,'".AddSlashes(pg_result($resaco,$conresaco,'s111_i_codigo'))."','$this->s111_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s111_i_prestador"]) || $this->s111_i_prestador != "")
             $resac = db_query("insert into db_acount values($acount,2374,13567,'".AddSlashes(pg_result($resaco,$conresaco,'s111_i_prestador'))."','$this->s111_i_prestador',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s111_procedimento"]) || $this->s111_procedimento != "")
             $resac = db_query("insert into db_acount values($acount,2374,13568,'".AddSlashes(pg_result($resaco,$conresaco,'s111_procedimento'))."','$this->s111_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           if (isset($GLOBALS["HTTP_POST_VARS"]["s111_c_situacao"]) || $this->s111_c_situacao != "")
             $resac = db_query("insert into db_acount values($acount,2374,13570,'".AddSlashes(pg_result($resaco,$conresaco,'s111_c_situacao'))."','$this->s111_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $result = db_query($sql);
     if (!$result) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prestador Vinculos não Alterado. Alteração Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->s111_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prestador Vinculos não foi Alterado. Alteração Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->s111_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->s111_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   public function excluir ($s111_i_codigo=null,$dbwhere=null) { 

     $lSessaoDesativarAccount = db_getsession("DB_desativar_account", false);
     if (!isset($lSessaoDesativarAccount) || (isset($lSessaoDesativarAccount)
       && ($lSessaoDesativarAccount === false))) {

       if (empty($dbwhere)) {

         $resaco = $this->sql_record($this->sql_query_file($s111_i_codigo));
       } else { 
         $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
       }
       if (($resaco != false) || ($this->numrows!=0)) {

         for ($iresaco = 0; $iresaco < $this->numrows; $iresaco++) {

           $resac  = db_query("select nextval('db_acount_id_acount_seq') as acount");
           $acount = pg_result($resac,0,0);
           $resac  = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
           $resac  = db_query("insert into db_acountkey values($acount,13566,'$s111_i_codigo','E')");
           $resac  = db_query("insert into db_acount values($acount,2374,13566,'','".AddSlashes(pg_result($resaco,$iresaco,'s111_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2374,13567,'','".AddSlashes(pg_result($resaco,$iresaco,'s111_i_prestador'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2374,13568,'','".AddSlashes(pg_result($resaco,$iresaco,'s111_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
           $resac  = db_query("insert into db_acount values($acount,2374,13570,'','".AddSlashes(pg_result($resaco,$iresaco,'s111_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         }
       }
     }
     $sql = " delete from sau_prestadorvinculos
                    where ";
     $sql2 = "";
     if (empty($dbwhere)) {
        if (!empty($s111_i_codigo)){
          if (!empty($sql2)) {
            $sql2 .= " and ";
          }
          $sql2 .= " s111_i_codigo = $s111_i_codigo ";
        }
     } else {
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if ($result == false) { 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Prestador Vinculos não Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$s111_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     } else {
       if (pg_affected_rows($result) == 0) {
         $this->erro_banco = "";
         $this->erro_sql = "Prestador Vinculos não Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$s111_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       } else {
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$s111_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_prestadorvinculos";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   public function sql_query ($s111_i_codigo = null,$campos = "*", $ordem = null, $dbwhere = "") { 

     $sql  = "select {$campos}";
     $sql .= "  from sau_prestadorvinculos ";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = sau_prestadorvinculos.s111_procedimento";
     $sql .= "      inner join sau_prestadores  on  sau_prestadores.s110_i_codigo = sau_prestadorvinculos.s111_i_prestador";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = sau_prestadores.s110_i_numcgm";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($s111_i_codigo)) {
         $sql2 .= " where sau_prestadorvinculos.s111_i_codigo = $s111_i_codigo "; 
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
   public function sql_query_file ($s111_i_codigo = null, $campos = "*", $ordem = null, $dbwhere = "") {

     $sql  = "select {$campos} ";
     $sql .= "  from sau_prestadorvinculos ";
     $sql2 = "";
     if (empty($dbwhere)) {
       if (!empty($s111_i_codigo)){
         $sql2 .= " where sau_prestadorvinculos.s111_i_codigo = $s111_i_codigo "; 
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
